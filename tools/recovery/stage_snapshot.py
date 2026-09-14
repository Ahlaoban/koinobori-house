"""Verify and unpack a snapshot into a NEW private directory; never boot PHP.

Original permissions remain in the manifest. Extracted files are 0600,
directories 0700, including the source configuration (never publish it).
Failed staging is retained for diagnosis and is never reused automatically.
"""
from pathlib import Path, PurePosixPath
import gzip
import hashlib
import json
import os
import tarfile

PARTS = {'database.sql.gz', 'site.tar.gz', 'source-before.json',
         'source-after.json', 'capture-summary.json'}
MAX_TRANSPORT_BYTES = 256 * 1024 * 1024
MAX_SQL_BYTES = 1024 ** 3


def digest(stream):
    h = hashlib.sha256()
    for block in iter(lambda: stream.read(1048576), b''):
        h.update(block)
    return h.hexdigest()


def file_digest(path):
    with path.open('rb') as stream:
        return digest(stream)


def archive_parts(name):
    parts = PurePosixPath(name).parts
    if not parts or parts[0] != 'site' or '..' in parts:
        raise ValueError('Unsafe archive path')
    # POSIX permits a literal backslash in a filename. Preserve it unchanged;
    # Windows would interpret it as a separator and must reject such archives.
    if os.name != 'posix' and '\\' in name:
        raise ValueError('POSIX filename cannot be safely restored on this platform')
    return parts


def unpack_site(archive, manifest, root):
    root.mkdir(mode=0o700)
    seen = set()
    total = 0
    with tarfile.open(str(archive), 'r:gz') as tf:
        for item in tf:
            parts = archive_parts(item.name)
            if item.isdir():
                continue
            name = '/'.join(parts[1:])
            if not item.isfile() or name not in manifest or name in seen:
                raise ValueError('Unexpected, duplicate or nonregular member')
            total += item.size
            if item.size != manifest[name]['bytes'] or total > 1024**3:
                raise ValueError('Unexpected extracted size')
            target = root.joinpath(*parts[1:])
            target.resolve().relative_to(root.resolve())
            target.parent.mkdir(mode=0o700, parents=True, exist_ok=True)
            with tf.extractfile(item) as src, target.open('xb') as dst:
                for block in iter(lambda: src.read(1048576), b''):
                    dst.write(block)
            os.chmod(str(target), 0o600)
            if file_digest(target) != manifest[name]['sha256']:
                raise ValueError('Extracted content differs from manifest')
            seen.add(name)
    if seen != set(manifest):
        raise ValueError('Incomplete snapshot')
    return len(seen), total


def stage(bundle, expected_hash, destination):
    if destination.parent.resolve() != destination.parent or destination.is_symlink():
        raise ValueError('Unexpected private destination')
    if bundle.stat().st_size > MAX_TRANSPORT_BYTES:
        raise ValueError('Transport exceeds size limit')
    if file_digest(bundle) != expected_hash:
        raise ValueError('Transport hash mismatch')
    os.umask(0o077)
    destination.mkdir(mode=0o700)
    with tarfile.open(str(bundle), 'r:') as tf:
        members = tf.getmembers()
        if (len(members) != len(PARTS) or {m.name for m in members} != PARTS
                or any(not m.isfile() or m.size < 0 for m in members)
                or sum(m.size for m in members) > MAX_TRANSPORT_BYTES):
            raise ValueError('Unexpected transport members')
        for item in members:
            with tf.extractfile(item) as src, (destination / item.name).open('xb') as dst:
                for block in iter(lambda: src.read(1048576), b''):
                    dst.write(block)
    summary = json.loads((destination / 'capture-summary.json').read_text())
    for name in ('database.sql.gz', 'site.tar.gz'):
        part = destination / name
        expected = summary['archives'][name]
        if part.stat().st_size != expected['bytes'] or file_digest(part) != expected['sha256']:
            raise ValueError('Captured archive hash mismatch')
    # Read the complete gzip stream, including its checksum, without importing SQL.
    sql_bytes = 0
    with gzip.open(str(destination / 'database.sql.gz'), 'rb') as stream:
        for block in iter(lambda: stream.read(1048576), b''):
            sql_bytes += len(block)
            if sql_bytes > MAX_SQL_BYTES:
                raise ValueError('Database exceeds uncompressed size limit')
    if not sql_bytes:
        raise ValueError('Empty database dump')
    before = json.loads((destination / 'source-before.json').read_text())
    after = json.loads((destination / 'source-after.json').read_text())
    if before != after:
        raise ValueError('Unstable source manifest')
    count, size = unpack_site(destination / 'site.tar.gz', before, destination / 'site')
    result = {'files_verified': count, 'bytes_verified': size,
              'database_gzip_verified': True, 'database_uncompressed_bytes': sql_bytes,
              'transport_sha256': expected_hash, 'php_executed': False,
              'database_imported': False, 'location': 'private_outside_web'}
    with (destination / 'stage-result.json').open('x') as out:
        json.dump(result, out, indent=2)
    return result

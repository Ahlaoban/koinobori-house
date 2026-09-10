"""Capture privee du staging KH : aucune ecriture dans le site ni dans sa base.

Compatible Python 3.6. A executer seulement depuis le terminal du compte source.
Les archives et journaux restent sous kh2027-private, jamais dans le depot/Web.
Un succes valide la lecture des archives, pas une restauration WordPress.
"""
import datetime
import gzip
import hashlib
import json
import os
from pathlib import Path
import stat
import subprocess
import tarfile

HOME = Path('/home3/heal3867')
SOURCE = HOME / 'staging.koinoborihouse.com'
PRIVATE = HOME / 'kh2027-private'


def digest_stream(stream):
    h = hashlib.sha256()
    for block in iter(lambda: stream.read(1024 * 1024), b''):
        h.update(block)
    return h.hexdigest()


def digest(path):
    with path.open('rb') as stream:
        return digest_stream(stream)


def inventory(root):
    result = {}
    for path in sorted(root.rglob('*')):
        if path.is_symlink():
            raise RuntimeError('Symlink detected; manual review required')
        if path.is_file():
            result[path.relative_to(root).as_posix()] = {
                'sha256': digest(path), 'bytes': path.stat().st_size,
                'mode': oct(stat.S_IMODE(path.stat().st_mode)),
            }
    return result


def validate_archive(path, reference):
    seen = set()
    with tarfile.open(str(path), 'r:gz') as archive:
        for member in archive:
            if member.isdir():
                continue
            if not member.isfile() or not member.name.startswith('site/'):
                raise RuntimeError('Unexpected archive entry')
            name = member.name[len('site/'):]
            if name not in reference or name in seen or '..' in Path(name).parts:
                raise RuntimeError('Unexpected or duplicate archive path')
            with archive.extractfile(member) as stream:
                if digest_stream(stream) != reference[name]['sha256']:
                    raise RuntimeError('Archive differs from source manifest')
            if member.size != reference[name]['bytes']:
                raise RuntimeError('Archive size differs from manifest')
            seen.add(name)
    if seen != set(reference):
        raise RuntimeError('Incomplete archive')


def write_json(path, data):
    with path.open('x', encoding='utf-8') as handle:
        json.dump(data, handle, ensure_ascii=False, indent=2)


def capture():
    os.umask(0o077)
    if SOURCE.is_symlink() or SOURCE.resolve() != SOURCE:
        raise RuntimeError('Unexpected source path')
    if SOURCE.stat().st_uid != os.getuid():
        raise RuntimeError('Unexpected source owner')
    if PRIVATE.is_symlink() or PRIVATE.resolve().parent != HOME:
        raise RuntimeError('Unexpected private path')
    if PRIVATE.exists():
        if not PRIVATE.is_dir() or stat.S_IMODE(PRIVATE.stat().st_mode) != 0o700:
            raise RuntimeError('Private directory permissions not restrictive')
        if PRIVATE.stat().st_uid != os.getuid():
            raise RuntimeError('Unexpected private owner')
    else:
        PRIVATE.mkdir(mode=0o700)
    started = datetime.datetime.utcnow().strftime('%Y%m%dT%H%M%SZ')
    run = PRIVATE / ('staging-' + started)
    run.mkdir(mode=0o700)  # Collision stops the run; no replacement of backups.
    print('CAPTURE_STARTED', run.name, flush=True)
    base = ['wp', '--path=' + str(SOURCE), '--skip-plugins', '--skip-themes']
    query = ("SELECT TABLE_NAME,ENGINE FROM information_schema.TABLES "
             "WHERE TABLE_SCHEMA=DATABASE() AND TABLE_TYPE='BASE TABLE' "
             "AND (ENGINE IS NULL OR ENGINE <> 'InnoDB');")
    engines = subprocess.run(base + ['db', 'query', query, '--skip-column-names'],
                             stdout=subprocess.PIPE, stderr=subprocess.PIPE,
                             universal_newlines=True, timeout=60)
    if engines.returncode:
        raise RuntimeError('Database engine check failed')
    nontransactional = engines.stdout.strip()
    if nontransactional not in ('', 'wprs_wfls_role_counts\tMEMORY'):
        raise RuntimeError('Unexpected nontransactional tables; manual review required')
    # READ locks only on this database, never a server-wide/global read lock.
    # MEMORY is not covered by a consistent InnoDB snapshot.
    dump_options = (['--skip-single-transaction', '--lock-tables', '--quick']
                    if nontransactional else
                    ['--single-transaction', '--quick', '--skip-lock-tables'])
    before = inventory(SOURCE)
    write_json(run / 'source-before.json', before)
    print('SOURCE_MANIFEST', len(before), flush=True)
    sql = run / 'database.sql'
    with (run / 'database-export.log').open('xb') as log:
        result = subprocess.run(base + ['db', 'export', str(sql)] + dump_options,
                                stdout=log, stderr=log, timeout=300)
    if result.returncode or not sql.exists() or not sql.stat().st_size:
        raise RuntimeError('Database export failed; private log retained')
    sql_gz = run / 'database.sql.gz'
    with sql.open('rb') as src, gzip.open(str(sql_gz), 'wb') as dst:
        for block in iter(lambda: src.read(1024 * 1024), b''):
            dst.write(block)
    with gzip.open(str(sql_gz), 'rb') as stream:
        if digest_stream(stream) != digest(sql):
            raise RuntimeError('Compressed database differs from export')
    print('DATABASE_ARCHIVE_READABLE', flush=True)
    archive = run / 'site.tar.gz'
    with tarfile.open(str(archive), 'w:gz') as target:
        target.add(str(SOURCE), arcname='site', recursive=True)
    validate_archive(archive, before)
    after = inventory(SOURCE)
    write_json(run / 'source-after.json', after)
    if before != after:
        raise RuntimeError('Source changed during capture; keep evidence and retry deliberately')
    summary = {
        'started_utc': started,
        'finished_utc': datetime.datetime.utcnow().isoformat() + 'Z',
        'source': str(SOURCE), 'directory': str(run),
        'source_files': len(before), 'source_files_stable': True,
        'database_capture_method': 'database_table_read_locks' if nontransactional else 'single_transaction',
        'nontransactional_tables': nontransactional,
        'archive_members_verified': True,
        'database_gzip_verified': True, 'restoration_executed': False,
        'off_host_copy_verified': False,
        'archives': {p.name: {'bytes': p.stat().st_size, 'sha256': digest(p)}
                     for p in [archive, sql_gz]},
    }
    write_json(run / 'capture-summary.json', summary)
    print(json.dumps(summary, sort_keys=True), flush=True)


if __name__ == '__main__':
    capture()

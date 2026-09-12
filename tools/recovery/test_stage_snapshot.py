import hashlib
import gzip
import io
import json
from pathlib import Path
import tarfile
import tempfile
import unittest
from unittest.mock import patch

from stage_snapshot import archive_parts, stage, unpack_site


class StageTests(unittest.TestCase):
    def test_posix_literal_backslash_is_preserved(self):
        with patch('stage_snapshot.os.name', 'posix'):
            self.assertEqual(archive_parts('site/upgrade/theme\\style.css'),
                             ('site', 'upgrade', 'theme\\style.css'))

    def test_windows_rejects_posix_backslash_filename(self):
        with patch('stage_snapshot.os.name', 'nt'):
            with self.assertRaises(ValueError):
                archive_parts('site/upgrade/theme\\style.css')

    def test_posix_path_traversal_still_rejected(self):
        with patch('stage_snapshot.os.name', 'posix'):
            with self.assertRaises(ValueError):
                archive_parts('site/../../outside')

    def run_case(self, names, reference):
        with tempfile.TemporaryDirectory() as temp:
            root = Path(temp)
            archive = root / 'site.tar.gz'
            with tarfile.open(str(archive), 'w:gz') as tf:
                for name in names:
                    entry = tarfile.TarInfo(name)
                    entry.size = 3
                    tf.addfile(entry, io.BytesIO(b'abc'))
            return unpack_site(archive, reference, root / 'private-site')

    def reference(self):
        return {'index.php': {'bytes': 3, 'sha256': hashlib.sha256(b'abc').hexdigest()}}

    def test_faithful_private_copy(self):
        self.assertEqual(self.run_case(['site/index.php'], self.reference()), (1, 3))

    def test_path_escape(self):
        with self.assertRaises(ValueError):
            self.run_case(['site/../../escape'], self.reference())

    def test_duplicate(self):
        with self.assertRaises(ValueError):
            self.run_case(['site/index.php', 'site/index.php'], self.reference())

    def test_missing_file(self):
        with self.assertRaises(ValueError):
            self.run_case([], self.reference())


class TransportTests(unittest.TestCase):
    def setUp(self):
        self.tmp = tempfile.TemporaryDirectory()
        self.addCleanup(self.tmp.cleanup)
        self.root = Path(self.tmp.name).resolve()
        self.destination = self.root / 'private-restoration'

    def bundle(self, corrupt_sql=False, unstable=False, altered_site=False,
               extra_member=False):
        site = io.BytesIO()
        with tarfile.open(fileobj=site, mode='w:gz') as tf:
            entry = tarfile.TarInfo('site/index.php')
            entry.size = 3
            tf.addfile(entry, io.BytesIO(b'abc'))
        manifest = {'index.php': {'bytes': 3, 'sha256': hashlib.sha256(b'abc').hexdigest()}}
        sql = gzip.compress(b'-- inert fixture, never imported\nSELECT 1;\n')
        if corrupt_sql:
            sql = sql[:-4]  # Correct transport hash, but invalid gzip trailer.
        parts = {'site.tar.gz': site.getvalue(), 'database.sql.gz': sql}
        summary = {'archives': {name: {'bytes': len(data),
                    'sha256': hashlib.sha256(data).hexdigest()}
                    for name, data in parts.items()}}
        if altered_site:
            parts['site.tar.gz'] += b'altered'
        parts.update({'capture-summary.json': json.dumps(summary).encode(),
                      'source-before.json': json.dumps(manifest).encode(),
                      'source-after.json': json.dumps({} if unstable else manifest).encode()})
        if extra_member:
            parts['unexpected.txt'] = b'no'
        bundle = self.root / 'transfer.tar'
        with tarfile.open(str(bundle), 'w:') as tf:
            for name, data in parts.items():
                entry = tarfile.TarInfo(name)
                entry.size = len(data)
                tf.addfile(entry, io.BytesIO(data))
        return bundle, hashlib.sha256(bundle.read_bytes()).hexdigest()

    def test_full_private_staging(self):
        bundle, digest = self.bundle()
        result = stage(bundle, digest, self.destination)
        self.assertEqual((self.destination / 'site/index.php').read_bytes(), b'abc')
        self.assertEqual(result['files_verified'], 1)
        self.assertTrue(result['database_gzip_verified'])
        self.assertFalse(result['database_imported'])
        self.assertFalse(result['php_executed'])
        self.assertEqual(json.loads((self.destination / 'stage-result.json').read_text()), result)

    def test_transport_corruption_stops_before_destination_creation(self):
        bundle, digest = self.bundle()
        bundle.write_bytes(bundle.read_bytes() + b'corruption')
        with self.assertRaisesRegex(ValueError, 'Transport hash mismatch'):
            stage(bundle, digest, self.destination)
        self.assertFalse(self.destination.exists())

    def test_existing_destination_preserved(self):
        bundle, digest = self.bundle()
        self.destination.mkdir()
        marker = self.destination / 'keep.txt'
        marker.write_text('keep')
        with self.assertRaises(FileExistsError):
            stage(bundle, digest, self.destination)
        self.assertEqual(marker.read_text(), 'keep')

    def test_corrupt_database_never_reports_success(self):
        bundle, digest = self.bundle(corrupt_sql=True)
        with self.assertRaises((EOFError, OSError)):
            stage(bundle, digest, self.destination)
        self.assertFalse((self.destination / 'stage-result.json').exists())
        self.assertFalse((self.destination / 'site').exists())

    def test_unstable_source_rejected(self):
        bundle, digest = self.bundle(unstable=True)
        with self.assertRaisesRegex(ValueError, 'Unstable source manifest'):
            stage(bundle, digest, self.destination)

    def test_changed_captured_archive_rejected(self):
        bundle, digest = self.bundle(altered_site=True)
        with self.assertRaisesRegex(ValueError, 'Captured archive hash mismatch'):
            stage(bundle, digest, self.destination)

    def test_unexpected_transport_member_rejected(self):
        bundle, digest = self.bundle(extra_member=True)
        with self.assertRaisesRegex(ValueError, 'Unexpected transport members'):
            stage(bundle, digest, self.destination)


if __name__ == '__main__':
    unittest.main()

"""Tests de rejet d'archives incompletes ou modifiees, sans donnees KH."""
import io
from pathlib import Path
import tarfile
import tempfile
import unittest
from capture_snapshot import inventory, validate_archive


class ArchiveTests(unittest.TestCase):
    def setUp(self):
        self.tmp = tempfile.TemporaryDirectory()
        self.addCleanup(self.tmp.cleanup)
        self.root = Path(self.tmp.name)
        self.source = self.root / 'source'
        self.source.mkdir()
        (self.source / 'sample.txt').write_text('fixture without private data')
        self.manifest = inventory(self.source)
        self.archive = self.root / 'test.tar.gz'

    def test_complete_archive(self):
        with tarfile.open(str(self.archive), 'w:gz') as target:
            target.add(str(self.source), arcname='site')
        validate_archive(self.archive, self.manifest)

    def test_changed_content_is_rejected(self):
        (self.source / 'sample.txt').write_text('changed')
        with tarfile.open(str(self.archive), 'w:gz') as target:
            target.add(str(self.source), arcname='site')
        with self.assertRaises(RuntimeError):
            validate_archive(self.archive, self.manifest)

    def test_missing_file_is_rejected(self):
        with tarfile.open(str(self.archive), 'w:gz'):
            pass
        with self.assertRaises(RuntimeError):
            validate_archive(self.archive, self.manifest)

    def test_path_escape_is_rejected(self):
        with tarfile.open(str(self.archive), 'w:gz') as target:
            member = tarfile.TarInfo('site/../outside')
            member.size = 1
            target.addfile(member, io.BytesIO(b'x'))
        with self.assertRaises(RuntimeError):
            validate_archive(self.archive, self.manifest)


if __name__ == '__main__':
    unittest.main()

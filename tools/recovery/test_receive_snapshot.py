import io
from pathlib import Path
import tempfile
import unittest
from receive_snapshot import receive


class ReceiveTests(unittest.TestCase):
    def setUp(self):
        self.tmp = tempfile.TemporaryDirectory()
        self.addCleanup(self.tmp.cleanup)
        self.dest = Path(self.tmp.name).resolve() / 'transfer.tar'

    def test_exact_transfer(self):
        self.assertEqual(receive(io.BytesIO(b'fixture'), self.dest), 7)
        self.assertEqual(self.dest.read_bytes(), b'fixture')

    def test_existing_file_is_preserved(self):
        self.dest.write_bytes(b'keep')
        with self.assertRaises(FileExistsError):
            receive(io.BytesIO(b'replacement'), self.dest)
        self.assertEqual(self.dest.read_bytes(), b'keep')

    def test_oversized_transfer_is_rejected(self):
        with self.assertRaises(RuntimeError):
            receive(io.BytesIO(b'12345'), self.dest, limit=4)

    def test_empty_transfer_is_rejected(self):
        with self.assertRaisesRegex(RuntimeError, 'Empty transfer'):
            receive(io.BytesIO(b''), self.dest)
        self.assertEqual(self.dest.read_bytes(), b'')


if __name__ == '__main__':
    unittest.main()

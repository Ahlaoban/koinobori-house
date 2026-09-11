"""Reception SSH a usage unique : aucun argument client, aucune extraction.

La cle autorisee doit imposer cette commande et interdire shell/forwarding/PTY.
Fichier prive exclusif, limite de taille et delai borne. Aucune donnee affichee.
"""
from pathlib import Path
import os
import signal
import sys

DESTINATION = Path('/home3/sc3heal3867/kh2027-private/incoming/transfer.tar')
MAX_BYTES = 256 * 1024 * 1024


def receive(stream, destination, limit=MAX_BYTES):
    if destination.is_symlink() or destination.parent.resolve() != destination.parent:
        raise RuntimeError('Unexpected destination path')
    size = 0
    with destination.open('xb') as output:
        for chunk in iter(lambda: stream.read(1024 * 1024), b''):
            size += len(chunk)
            if size > limit:
                raise RuntimeError('Transfer exceeds size limit; partial file retained')
            output.write(chunk)
    if not size:
        raise RuntimeError('Empty transfer; empty file retained')
    return size


if __name__ == '__main__':
    if os.getuid() != 1473 or Path.home() != Path('/home3/sc3heal3867'):
        raise RuntimeError('Unexpected receiver account')
    os.umask(0o077)
    signal.alarm(180)
    size = receive(sys.stdin.buffer, DESTINATION)
    print('RECEIVED_BYTES', size)

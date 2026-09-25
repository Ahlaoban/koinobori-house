"""Détourage des photos produits : fond clair relié aux bords -> transparent.

Les koi sont photographiés sur fond blanc (ou presque). On efface uniquement la zone claire
connectée aux bords de l'image : les blancs à l'intérieur du koi (nuages, bandes des drapeaux)
restent opaques. Les petits éléments séparés du koi (ex. mention « 100 cm ») sont retirés.
Bord adouci sur 2 px et couleur « décontaminée » (plus de liseré blanc sur fond coloré).

    python tools/images/koi-cutout.py catalog/images/kairo/001-la-promesse-de-la-mer/main.jpg out.webp

Sortie : WebP avec transparence, même définition que l'original.
"""
import sys

import numpy as np
from PIL import Image
from scipy import ndimage

TOLERANCE = 40      # écart max (0-255) au fond pour être « fond »
SOFT = 60           # largeur de la rampe d'alpha sur le bord
KEEP_RATIO = 0.02   # garde les morceaux d'au moins 2 % de la surface du plus grand


def cutout(src, dst):
    rgb = np.asarray(Image.open(src).convert("RGB")).astype(np.float32)
    h, w, _ = rgb.shape
    border = np.concatenate([rgb[0], rgb[-1], rgb[:, 0], rgb[:, -1]])
    bg = np.median(border, axis=0)
    dist = np.abs(rgb - bg).max(axis=2)

    # Fond = composantes « proches du fond » qui touchent un bord.
    near = dist < TOLERANCE
    labels, _ = ndimage.label(near)
    edge_labels = np.unique(np.concatenate([labels[0], labels[-1], labels[:, 0], labels[:, -1]]))
    edge_labels = edge_labels[edge_labels != 0]
    background = np.isin(labels, edge_labels)

    # Premier plan : ne garder que le koi (et ses gros morceaux), pas les petites inscriptions.
    fg_labels, n = ndimage.label(~background)
    if n:
        sizes = ndimage.sum(np.ones_like(fg_labels), fg_labels, index=range(1, n + 1))
        keep = [i + 1 for i, s in enumerate(sizes) if s >= KEEP_RATIO * sizes.max()]
        background |= ~np.isin(fg_labels, keep)
        background = ndimage.binary_fill_holes(~background) == 0  # trous intérieurs = koi

    # Alpha doux sur une bande de 2 px autour du koi.
    alpha = np.where(background, 0.0, 1.0)
    band = ndimage.binary_dilation(background, iterations=2) & ~background
    alpha[band] = np.clip((dist[band] - 8) / SOFT, 0.15, 1.0)

    # Décontamination : retirer la part de fond mélangée aux pixels du bord.
    a = alpha[..., None]
    safe = np.maximum(a, 1e-3)
    out = np.where(a > 0, (rgb - (1 - a) * bg) / safe, 0)
    out = np.clip(out, 0, 255)

    rgba = np.dstack([out, alpha * 255]).astype(np.uint8)
    Image.fromarray(rgba).save(dst, "WEBP", quality=88, method=6)


if __name__ == "__main__":
    cutout(sys.argv[1], sys.argv[2])

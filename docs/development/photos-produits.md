# Photos produits : détourage

Depuis le thème 1.3.21, les galeries (home et pages des cinq mondes) affichent les koi **sans cadre**, posés sur le
papier, et agrandis sur le fond de leur collection au survol. Pour que le koi soit net et opaque, sa photo
principale doit être **détourée** : fond transparent, fichier WebP ou PNG, nom contenant `-detoure`.

## Ajouter ou remplacer une photo

1. Déposer l'original (fond blanc ou très clair, koi entier) dans `catalog/images/<collection>/<produit>/main.jpg`.
2. Détourer : `python tools/images/koi-cutout.py catalog/images/<collection>/<produit>/main.jpg <sortie>-detoure.webp`.
   Contrôler le résultat sur un fond coloré : le script retire le fond relié aux bords et **les petits éléments
   détachés du koi** (moins de 2 % de sa surface : mention de taille, mais aussi un ruban ou une flamme isolés).
3. Importer le fichier `…-detoure.webp` dans la médiathèque et le choisir comme image principale du produit
   (français et anglais). Garder `-detoure` dans le nom du fichier.

## Si la photo n'est pas détourée

Une photo dont le nom ne contient pas `-detoure` (ajout direct dans l'admin, import du catalogue, image d'attente)
n'est pas cassée : son fond blanc est fondu dans le papier (`mix-blend-mode: multiply`, section 7bis de
`kh-foundations.css`). En contrepartie, le papier et le fond de collection se devinent à travers le koi,
surtout au survol. C'est un repli d'attente, pas l'état visé.

`catalog/master.csv` référence encore les originaux (`main.jpg`) : un import du catalogue remet les photos non
détourées ; refaire ensuite l'étape 3, ou relancer `tools/staging/pc8/product-cutouts.php`.

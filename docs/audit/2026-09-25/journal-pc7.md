# Journal PC7 — pages des cinq mondes (thème 1.3.14), 2026-09-25

Staging uniquement, compte `heal3867`. Production jamais touchée, aucun secret lu.
Demande Alain : « les pages des cinq collections, illustration du monde en tête, fond washi, comme la home », puis « utilise les images de `koinobori-house-images/collections/` ».

## Ce qui change

- `inc/world-archive.php` : sur les 5 archives `product_cat` (reconnues par le slug de la catégorie française, les pages EN comprises), le titre Kadence est masqué (filtre `kadence_post_layout`, `title = hide`) et une section `.kh-world-hero` est imprimée sur `kadence_hero_header` : bannière de la collection, puis titre (H1 = nom de la catégorie), nom japonais et description. Les autres catégories et la Boutique gardent l'affichage Kadence.
- Textes modifiables dans l'admin (Produits › Catégories) : nom, description, et nouveau champ **« Nom japonais »** (term meta `kh_kanji`), rempli sur la catégorie française ; la catégorie anglaise le reprend si son propre champ est vide.
- Bannière : la miniature de la catégorie si elle est posée dans l'admin, sinon celle du thème `assets/images/collection-<monde>-{960,1920}.webp`, dérivée des fichiers choisis par Alain (Mer = `cat-mer-hero`, Motifs = `cat-motifs-hero`, Hanami = `cat-floral-hero`, Kaïro = `collection-kairo-banner-nouvelle-charte`, Territoires = `cat-territoires-hero`). Texte incrusté dans les images : affichées entières (16:9, jamais recadrées), largeur `min(100 %, 118svh)` pour tenir dans l'écran, `alt` vide (le H1 nomme le monde).
- `tools/staging/pc7/world-terms-seed.php` : remplit une seule fois les noms japonais (FR) et les descriptions FR/EN avec les accroches de la home ; n'écrit jamais dans un champ déjà rempli.

## Déroulé (heures UTC)

| Heure | Action | Résultat |
|---|---|---|
| ~10:40 | Première version (illustrations portrait `monde-*`), fichiers `w1314-*` déposés, empreintes et `php -l` OK | copie dans le thème **refusée par le contrôle de sécurité de Claude** ; version abandonnée, fichiers `w1314-*` périmés |
| — | Choix d'Alain : bannières du dossier `collections/` ; aperçu local desktop et mobile | validé |
| ~14:10 | Alain dépose `kh-w1314c.tar` (SHA-256 `63c25cde…`) et l'extrait dans `~/kh2027-private/tools/w1314c/` | `sha256sum -c` 16 / 16 « Réussi », `php -l` 3 fichiers sans erreur |
| 14:14:41 | Alain bascule le thème 1.3.13 → **1.3.14** (ancien dossier : `backups/theme-dir-1.3.13-20260925T141441Z`) | `wp eval` : `1.3.14` |
| ~14:16 | Contrôle Claude (lecture seule) des 10 pages FR/EN | 200, bannière du bon monde, un seul H1, plus de titre Kadence, produits présents (6/3/1/4/3) ; home (5 cartes) et Boutique inchangées |
| ~14:18 | Alain : `world-terms-seed.php` en essai à blanc | gardes toutes vraies, 15 valeurs prévues |
| ~14:20 | Alain : même script avec `KH_APPLY=1` | sauvegarde `pc7-world-terms-<UTC>.json` puis écriture, confirmée par Alain |
| ~14:22 | Contrôle Claude des 10 pages | noms japonais (EN hérités du FR) et accroches affichés, aucune description en double |

## Retour arrière

Thème : remettre `backups/theme-dir-1.3.13-20260925T141441Z` à la place de `wp-content/themes/koinobori-child`. Textes : `tools/staging/pc4/restore.php` avec `pc7-world-terms-<UTC>.json` (descriptions) ; les noms japonais n'existaient pas avant : `wp term meta delete <id> kh_kanji` pour les ids listés dans la sauvegarde (FR : 50, 69, 44, 77, 85). Le thème 1.3.13 ignore ces données : rien ne s'affiche en double si on ne revient que sur le thème.

## Points ouverts pour Alain / Catherine

- Texte incrusté dans les bannières : Hanami porte « Floral » (ancien nom), Territoires « Huit régions, une même âme » avec huit régions japonaises (la collection regroupe Bretagne, USA…), Motifs en anglais sur la page FR, Kaïro écrit **回路** alors que la home et le champ portent **回廊**.
- Mer : 海 figure deux fois (bannière et champ « Nom japonais ») ; vider le champ pour n'en garder qu'un.
- Pendant le chargement, la bannière montre un aplat noir un instant : proposer la couleur du papier au prochain déploiement.
- Descriptions = accroches courtes de la home, à réécrire au besoin dans l'admin.

## Soir — thème 1.3.15 → 1.3.19, déployé par Alain à 18:29 UTC

Demandes d'Alain au fil de la recette, regroupées dans une seule archive `kh-w1319.tar` (7 fichiers ; 1.3.15 à 1.3.18 jamais déposées) :

| Version | Changement |
|---|---|
| 1.3.15 | Bannière Hanami remplacée par `cat-hanami-hero.webp` (titre « Hanami » au lieu de « Floral »), sous un nouveau nom `collection-hanami-v2-*` |
| 1.3.16 | Plus de titre ni de nom japonais sous la bannière (H1 gardé pour lecteurs d'écran et référencement), accroche centrée ; cartes produits sans fond blanc ni ombre, photos fondues au papier (multiply) ; plus d'aplat noir au chargement ; galerie **2 par rangée**, koi entiers (taille `woocommerce_single` non recadrée), colonne de droite décalée |
| 1.3.17 | Home « Créations BCDG » : fond blanc retiré, koi entiers |
| 1.3.18 | **Koi au survol** : immobiles au repos ; survol ou clavier = le koi grandit (×1,45 mondes, ×1,8 home) et flotte au vent (balancement + ondulation SVG `#kh-wind`) sur la bannière voilée de sa collection ; les autres s'estompent ; tout revient quand la souris part. Écrans à souris seulement, sans animation si « réduire les animations » |
| 1.3.19 | Koi agrandi entier et net : fond plus grand que le koi, bannière voilée à 80 %, zone d'ondulation élargie |

| Heure (UTC) | Action | Résultat |
|---|---|---|
| ~18:25 | Alain dépose `kh-w1319.tar` (SHA-256 `236af4a5…`) et l'extrait dans `~/kh2027-private/tools/w1319/` | `sha256sum -c` 7 / 7 « Réussi », `php -l` sans erreur |
| 18:29:37 | Bascule 1.3.14 → **1.3.19** (ancien dossier : `backups/theme-dir-1.3.14-20260925T182937Z`) | `wp eval` : `1.3.19` |
| ~18:32 | Contrôle Claude, lecture seule : home FR/EN + 10 pages mondes + Boutique | 200 ; bannières (Hanami v2) ; grille 2 colonnes et vignettes non recadrées sur les mondes, 4 colonnes non recadrées sur la home ; filtre `#kh-wind` présent ; un seul H1 ; **Boutique inchangée** (4 colonnes, vignettes carrées, pas d'effet) ; survol vérifié à l'écran sur Territoires |

Retour arrière : remettre `backups/theme-dir-1.3.14-20260925T182937Z`.

Reste : photos à fond non blanc (« Les Voix du Pont » gris clair, Stars & Stripes avec la mention « 100 cm » dans l'image) ; produits sans photo (Mer, Motifs) ; prototype « papier teinté par monde » en attente ; idée d'Alain : remplacer la simulation par une vraie vidéo du koi au vent (champ vidéo déjà présent via `kh-product-media`, à brancher au survol).

## Soir — PC8 photos détourées + thème 1.3.21, déployés par Alain à 18:57 UTC

Remarque d'Alain : « on ne doit pas du tout voir l'arrière-plan à travers le koi ». Le fond blanc des photos n'était masqué que par un mode de fusion (multiply), qui laissait voir le papier et la bannière à travers le koi. Solution : photos détourées (fond transparent) et plus aucun mode de fusion sur les photos produits.

- 1.3.20 (jamais déposée seule, incluse) : bannière entière fondue dans le papier (multiply, effacement bas et bords, sans cadre) ; papier de toute la page teinté à la couleur du monde (voile `color` sur le washi) ; articles remontés (bannière limitée à la hauteur d'écran, marges Kadence réduites : galerie à 789 px au lieu de 1 016 sur un écran de 935 px).
- 1.3.21 : photos produits sans mode de fusion (home et mondes).
- `tools/images/koi-cutout.py` : détourage des originaux `catalog/images/*/main.jpg` (fond clair relié aux bords rendu transparent, blancs intérieurs conservés, petits éléments retirés dont la mention « 100 cm », bord adouci et décontaminé).
- `tools/staging/pc8/product-cutouts.php` : pose des 8 photos détourées comme image principale des produits FR et de leurs traductions EN.

| Heure (UTC) | Action | Résultat |
|---|---|---|
| ~18:55 | Alain dépose `kh-w1321.tar` (14 fichiers) dans `~/kh2027-private/tools/w1321/` | `sha256sum -c` 14 / 14, `php -l` sans erreur |
| ~18:56 | PC8 essai à blanc | gardes vraies ; 8 produits, paires FR/EN 111↔113, 186↔220, 195↔221, 196↔222, 197↔223, 198↔224, 199↔225, 205↔226 |
| 18:57:28 | PC8 appliqué | sauvegarde `pc8-product-cutouts-20260925T185728Z.json` ; pièces jointes 333 à 340 ; 16 × `stored=true` ; anciennes images conservées |
| 18:57:52 | Bascule 1.3.19 → **1.3.21** (ancien dossier : `backups/theme-dir-1.3.19-20260925T185752Z`) | `wp eval` : `1.3.21` |
| ~19:00 | Contrôle Claude, lecture seule | photos `-detoure` servies sur home, mondes FR/EN et Boutique ; `mix-blend-mode: normal` ; survol vérifié à l'écran sur Kaïro : koi opaque, entier, bannière en filigrane |

Retour arrière : images = `restore.php` avec `pc8-product-cutouts-20260925T185728Z.json` (remet les anciens `_thumbnail_id`) ; thème = `backups/theme-dir-1.3.19-20260925T185752Z`. **Les deux ensemble** : le thème 1.3.21 sur les anciennes photos montrerait leurs rectangles blancs.

Note : la règle `.gitignore` `*stripe*` (clés Stripe) écarte aussi `…stars-stripes…` ; l'image détourée a été ajoutée explicitement (`git add -f`).

## Soir — PC9 ordre Kaïro + thème 1.3.22, déployés par Alain à 19:09 UTC

Remarques d'Alain : (1) au survol, le dernier koi d'une page monde envahissait l'écran ; (2) Kaïro doit suivre l'ordre des épisodes.

- Cause (1) : la dernière image, chargée en différé, reçoit `sizes="auto"` ; WordPress y associe une réserve `contain-intrinsic-size: 3000px 1500px` : boîte de 581 × 1500 px au lieu de 581 × 286, et fond du survol agrandi d'autant. Correctif 1.3.22 : filtre `wp_img_tag_add_auto_sizes` à `false` sur les galeries des mondes et de la home (simulation navigateur : 581 × 286).
- (2) Tri par défaut « ordre du menu puis titre », tous à 0 : « L'Ombre » avant « La Promesse ». PC9 `tools/staging/pc9/kairo-order.php` : ordre du menu 1 à 4 en FR et EN (modifiable ensuite dans l'admin, Produits › Trier).

| Heure (UTC) | Action | Résultat |
|---|---|---|
| ~19:07 | Alain dépose `kh-w1322.tar` (4 fichiers) dans `~/kh2027-private/tools/w1322/` | extraction vérifiée (tailles) |
| 19:09:36 | PC9 appliqué | sauvegarde `pc9-kairo-order-20260925T190936Z.json` ; 186/220 → 1, 195/221 → 2, 196/222 → 3, 197/223 → 4 |
| 19:09:37 | Bascule 1.3.21 → **1.3.22** (ancien dossier : `backups/theme-dir-1.3.21-20260925T190937Z`) | `wp eval` : `1.3.22` (thème chargé sans erreur) |
| ~19:12 | Contrôle Claude | serveur OK (HTTP 401 d'authentification basique, `error_log` inchangé depuis le 28/08) ; rendu navigateur **non vérifié** : l'onglet de Claude a perdu l'authentification basique du staging → recette Alain |

Retour arrière : ordre = `restore.php` avec `pc9-kairo-order-20260925T190936Z.json` ; thème = `backups/theme-dir-1.3.21-20260925T190937Z`.

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

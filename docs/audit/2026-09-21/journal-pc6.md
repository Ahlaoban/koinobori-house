# Journal PC6 — home éditable sur le staging, 2026-09-21

Même session cPanel (ouverte par Alain, compte `heal3867`). GO Alain « GO home éditable ». Source : branche `feat/home-editable`, commit `e34172b` (PR #24). Aucun secret lu, production jamais touchée.

**Écart au plan annoncé** : l'essai devait passer par le clone privé avant le staging. Déployé directement sur le staging : le clone demande une autre session cPanel (compte `sc3heal3867`), son état est en partie simulé, et le staging n'est pas public, est sauvegardé, avec retour arrière automatique.

| UTC | Action | Résultat |
|---|---|---|
| 18:18:52 | Dépôt `kh-pc6-e34172b.tar` (SHA-256 `78c40f3b0763d071ab4c6d3fccccd34a158c3f3a55e68326ddc362fbe9d86929`, identique poste / serveur) dans `~/kh2027-private/tools/e-e34172b/` | `sha256sum -c` 63 / 63, `php -l` 18 fichiers sans erreur |
| 18:18:52 | **Lecture seule** : `inc/home-blocks.php` chargé à la main dans `wp eval` (thème actif 1.2.0), contenu généré FR et EN | FR 10 903 octets, EN 10 770 octets, 7 sections chacune, **11 liens résolus par langue** (5 mondes, Kaïro, L'Atelier, Lifestyle, Entreprises, Collectivités, ancre `#kh-worlds`), balisage des blocs relu |
| 18:19:53 | Sauvegarde base `staging-db-20260921T181953Z.sql.gz` SHA-256 `449652864ac8b34a080cbcfd3c356680fd639581926a1259b5d6fb7da7bd9a21` | écrite, `0600` |
| 18:19:5x | Bascule du thème 1.2.0 → **1.3.0** (ancien dossier dans `backups/theme-dir-1.2.0-20260921T181953Z`), test de chargement | `SWAPPED`, `boot-ok 1.3.0 seed-fn` |
| 18:20 | `home-blocks-seed.php` en dry-run (12 gardes vraies) | 318 : 4 428 → 10 903 octets, 319 : 4 329 → 10 770 octets, 78 blocs et 11 liens chacune, contrôle doctrine passé |
| 18:20:01 | `KH_APPLY=1 KH_CONFIRM=home-blocks-seed` | sauvegarde `pc6-home-post-content-20260921T182001Z.json` SHA-256 `538b5cc054e65478e464ecdf62f1717860a61fe7e9826ad2087a8c12fe53595a` ; contenu stocké **identique** au contenu généré ×2 ; révision 324 créée sur la page 318 |
| 18:20 | Second passage | « already holds the home blocks, left untouched » ×2 : une modification d'Alain ou de Catherine ne sera jamais écrasée |
| 18:2x | Rendu côté serveur (`the_content`, langue forcée) | 318 et 319 : aucun code court non résolu, 4 produits, 1 seul `<h1>`, 7 sections, image du hero présente ; 0 article donc bloc articles vide ; `error_log` inchangé depuis le 28/08 |

## Retour arrière

Contenu des pages : `restore.php` (PC4) avec `pc6-home-post-content-20260921T182001Z.json`, ou Révisions dans l'éditeur. Thème : remettre `backups/theme-dir-1.2.0-20260921T181953Z`. **Les deux vont ensemble** : le thème 1.2.0 n'affiche pas le contenu de la page, le 1.3.0 n'affiche que lui.

## Non vérifié

Rendu dans un navigateur (espacements des blocs, bouton, image du hero, mobile) et **validité des blocs dans l'éditeur** (aucun bloc ne doit être signalé « contenu inattendu ») : le Chrome de Claude ne passe pas le certificat du staging. Recette par Alain.

## 2026-09-25 — fond washi Manus (thème 1.3.1)

Recette Alain du 24/09 : home OK, **fond blanc** au lieu du papier washi. Causes : voile ivoire à 92 % dans `kh-editorial.css` + fond blanc Kadence (`.site` / `.content-bg`, palette9) par-dessus le fond du `body`. Correctif PR #25 (`b730eb4`) : `fond_washi_koinobori_house.png` du pack Manus (consigne README_assets.md : arrière-plan global, secours ivoire) converti en WebP 2560×1440 de 90 874 octets, posé en calque fixe couvrant l'écran (`body::before`, image non répétable), voile retiré, conteneurs Kadence transparents, hero transparent, Lifestyle en `--kh-white-88`.

| UTC | Action | Résultat |
|---|---|---|
| 08:39:28 | Dépôt `kh-fond-b730eb4.tar` (SHA-256 `1d5a5b47…95b7`), extraction `~/kh2027-private/tools/f-b730eb4/` | 56 / 56 conformes |
| 08:39 | Sauvegarde `theme-koinobori-child-1.3.0-20260925T083928Z.tar.gz` SHA-256 `acb89b1a…1ef1` + dossier `theme-dir-1.3.0-20260925T083928Z` | `0600` |
| 08:39 | Bascule 1.3.0 → **1.3.1**, test de chargement | `SWAPPED`, `boot-ok 1.3.1`, image présente, règle CSS présente |
| 08:39 | `wp litespeed-purge all` | 401 (auth basique), sans effet : cache LiteSpeed désactivé |

CSS et image seulement, aucun PHP ni base. Retour arrière : remettre `theme-dir-1.3.0-…`. Rendu (papier, nuages, hero, mobile, autres pages) : recette Alain.

## 2026-09-25 — hero Manus fondu dans le papier (thème 1.3.3 → 1.3.7), validé par Alain

Demande Alain : l'image du koi doit faire partie du fond, sans cadre. Essais successifs sur le staging, chacun avec sauvegarde (`~/kh2027-private/backups/`) et contrôle d'empreinte :

| UTC | Thème | Changement | Résultat |
|---|---|---|---|
| 08:44 | 1.3.2 | multiply + masque radial sur le bloc image | bords encore visibles (image plus foncée que le papier) |
| 08:51 | 1.3.3 | conception Manus (README_HERO.md) : `hero_fond_web_16x9` en fond de toute la section (`::before`, cover, multiply, fondu vers le bas) ; bloc image retiré des pages 318/319 par `tools/staging/pc6/home-hero-remove-image.php` (dry-run puis apply, sauvegarde `pc6-hero-post-content-20260925T085134Z.json`, contenu stocké identique) | plus de cadre |
| 08:59 | 1.3.4 | nouveau fichier Manus `fond_hero_washi_koinobori_house.webp` (2048×1152, 321 Ko, déposé par Alain dans le pack `03_fonds_et_textures`) à la place | retenu |
| 09:03 | 1.3.5 | titre plus petit sur 3 lignes | Alain préfère le grand titre |
| 09:04 | — | **incident** : le chargeur cPanel n'avait pas écrasé les fichiers, l'ancien CSS 1.3.2 a été recopié une minute ; corrigé en renvoyant les fichiers sous des noms uniques + contrôle d'empreinte avant copie (règle adoptée) |
| 09:08 | 1.3.6 | cadrage validé : bloc de texte dans la moitié gauche, grand titre 3 lignes | — |
| 09:11 | 1.3.7 | surtitre du hero en vermillon (spécificité corrigée) | **« OK ! c'est joli maintenant » (Alain)** |

État final : `.kh-home-hero::before` = `hero-washi-manus.webp` en cover, `mix-blend-mode: multiply`, fondu vers le bas ; texte dans `.kh-home-hero__inner` (50 % à gauche) ; l'image du hero n'est plus un contenu de page mais un fond du thème (mobile : cadrage 72 % pour garder le koi). Un bloc image ajouté depuis l'éditeur reste possible sous le texte.

## 2026-09-25 — visuels Manus de la home (thème 1.3.8 → 1.3.11), validés par Alain

| UTC | Thème | Changement | Résultat |
|---|---|---|---|
| 09:17 | 1.3.8 | trait de pinceau vermillon Manus (`05_ornements`) sous les titres de section ; `lifestyle_koi_hero` (Manus) importée dans la médiathèque (attachment 328, meta `_kh_asset`) et insérée en bloc image sous le titre Lifestyle & Koi des pages 318/319 par `tools/staging/pc6/home-lifestyle-image.php` (sauvegarde `pc6-lifestyle-post-content-20260925T091804Z.json`, contenu stocké identique) | déployé |
| 09:32 | — | **incident cache** : l'archive fixait la date des fichiers (`mtime` constant) ; WordPress versionne le CSS par cette date, le navigateur d'Alain a resservi l'ancien CSS du hero. Corrigé : `touch` des fichiers du thème sur le serveur, archives désormais datées de l'heure réelle, images modifiées renommées | hero rétabli |
| 09:36 | 1.3.9 | **les cinq mondes** en cartes illustrées, choix A d'Alain : fichiers Manus `0N_<monde>_1024x1792` (WebP 640 px) en fond de carte ; `tools/staging/pc6/home-worlds-cards.php` ajoute à chaque carte sa classe `kh-world--<monde>` (reconnue par le lien de catégorie FR/EN) et son nom japonais (海 文様 花見 回廊 地域, paragraphe modifiable) ; sauvegarde `pc6-worlds-post-content-20260925T093641Z.json` | lisibilité insuffisante, kanji sur le titre |
| 09:56 | 1.3.10 | cause du chevauchement : `.kh-world__kanji` de la charte v3 (absolu, vermillon, 60 %) ; règles préfixées `.kh-home-world`, voiles renforcés, ombres de texte, Hanami en encre sombre ; image Motifs remplacée par `04_motifs_coherent` | — |
| 10:05 | 1.3.11 | image Kaïro remplacée par `02_kairo_hero` (`monde-kairo-hero.webp`) | **« OK merge » (Alain)** |

Légendes de la maquette Manus citant des œuvres protégées (Princesse Mononoké, Voyage de Chihiro, RAN, Kaguya-hime, NHK Japanology) **non reprises** : remplacées par nos accroches.

## 2026-09-25 — survol des cinq mondes (1.3.12 → 1.3.13), validé par Alain

| UTC | Thème | Changement | Résultat |
|---|---|---|---|
| 10:18 | 1.3.12 | carte survolée agrandie (×1,08, ombre), les quatre autres estompées (opacité 45 %, saturation 60 %) ; même effet au clavier ; rien sur écrans tactiles ; pas d'agrandissement si « réduire les animations » | la carte survolée s'estompait aussi (règle d'estompage plus prioritaire) |
| 10:21 | 1.3.13 | estompage limité aux cartes `:not(:hover):not(:focus-within)` | **« OK merge » (Alain)** |

Note : la PR #27 (visuels 1.3.8 à 1.3.11) a été mergée juste avant la demande d'Alain de patienter ; la commande était déjà partie. Version mergée = version validée ; le survol est livré à part (PR suivante).

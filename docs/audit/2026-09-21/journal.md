# Journal PC4 — corrections du staging, 2026-09-21

Session cPanel ouverte par Alain (`cow.o2switch.net:2083`, compte `heal3867`) dans l'onglet Chrome piloté par Claude. Aucun mot de passe échangé, aucun secret lu. Scripts = `main` `8e66680`. Production jamais touchée.

| UTC | Environnement | Commande / action | Résultat |
|---|---|---|---|
| 10:07:34 | staging (lecture) | pré-vol : `pwd -P`, `wp config get DB_NAME`, `table_prefix`, `wp_get_environment_type()`, `home`, `siteurl`, `wp core version`, thème actif | `/home3/heal3867/staging.koinoborihouse.com`, `heal3867_wp551`, `wprs_`, `staging`, `https://staging.koinoborihouse.com` ×2, WP 7.1.1, `koinobori-child` 1.1.0 ; `~/kh2027-private/backups` absent |
| 10:08 | privé | `mkdir -m 700` `tools/pc4`, `tools/inv-8e66680`, `backups` | créés `drwx------` |
| 10:09:27 | privé | dépôt par le chargeur cPanel (7 + 2 fichiers) | le chargeur n'a retenu que le premier fichier de chaque lot (`pc4/lib.php`, `inv-8e66680/inventory.php`), empreintes conformes ; dossiers laissés en place, non utilisés |
| 10:10:19 | privé | dépôt de `kh-pc4-8e66680.tar` (SHA-256 `df63b90b…a6c2`, identique au poste), extraction dans `tools/b-8e66680/` (`0700`), `sha256sum`, `php -l` | 9 scripts (`pc4/` ×7, `inv/` ×2) : empreintes **identiques à `git show 8e66680:<fichier>`** ; **17 fichiers sans erreur de syntaxe**, dont les 8 fichiers PHP modifiés le 21/09 (thème `enquiries` `header` `footer` `kh-home`, `kh-product-media`, mu-plugin de redirection, 2 outils `recovery`) déposés dans `lint/` pour ce seul contrôle |
| 10:10:38 | staging (lecture) | dry-run `i1` `i2` `i5` `i6` (`--skip-plugins --skip-themes`) puis `i3` (plugins chargés) | 12 gardes vraies ×5. **i1** : formulaires 7 `pays` et 8 `country`, `select_country` → `input_text`, libellé, obligatoire et règles inchangés. **i2** : `redirect_lang=false`, `show_on_front=page`, `page_on_front=318` → passerait à `true`. **i5** : `blogname` vide → « Koinobori House ». **i6** (rapport seul) : zone 1 France (forfait 5, gratuit dès 55), reste du monde sans méthode, `allowed_countries=all`. **i3** : 232↔234, 235↔236, 230↔231 publiées, chacune seule dans son groupe → 3 paires à lier. Aucune écriture |
| 10:11:18 | staging (lecture) | `inventory.php` corrigé (transaction READ ONLY) puis `inventory-sanitize.php` sur le serveur | 13 gardes vraies ; privé `staging-inventory-20260921T101119Z.private.json` SHA-256 `3f2833bb…2aba` (reste sur le serveur) ; public `…public.json` SHA-256 `e677751c1cd11127e18c5f4f82c529551c45dd4026a3e3160326aa0298a7093c`, scans = 0 hit |

## Mesures qui remplacent les non-mesures du 17/09

- **K2 sitemaps SEOPress** : `seopress_xml_sitemap_general_enable = true` (mesuré). Les sitemaps sont activés.
- **K3 Complianz** : `cmplz_wizard_completed_once` **absente** (`null`). Complianz 7.5.4 écrit cette option à la fin de l'assistant : son absence est cohérente avec un assistant jamais terminé, sans le prouver. À confirmer dans l'admin.
- Inchangé depuis le 17/09 : LiteSpeed 7.9.1 actif mais cache `false` ; menus 144 (7 entrées, `primary` + `mobile`) et 145 (non affecté) ; notifications actives sur les 8 formulaires ; thème enfant 1.1.0.

## Fichiers laissés sur le serveur (hors racine web, `0600` / `0700`)

`~/kh2027-private/tools/b-8e66680/{pc4,inv,lint}`, `tools/kh-pc4-8e66680.tar`, `tools/pc4/lib.php`, `tools/inv-8e66680/inventory.php`, `inventory/staging-inventory-20260921T101119Z.{private,public}.json` + `.sha256`, `pc4-preflight.txt`, `pc4-deposit.txt`, `pc4-deposit2.txt`, `pc4-dryrun.txt`, `pc4-inventory.txt`, dossier `backups/` vide.

## Écritures

Aucune à ce stade. Sauvegarde puis application : après feu vert d'Alain, script par script.

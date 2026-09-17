# Tests PR #14 sur le clone privé — journal du 2026-09-17

Clone `sc3heal3867.universe.wf` (compte cPanel `sc3heal3867`, serveur `cow`), session cPanel ouverte par Alain, pilotée par Claude. Aucun mot de passe échangé. Base du clone : `sc3heal3867_kh2027drill`. Toutes les écritures des scripts de test sont annulées par `ROLLBACK`.

## Accès HTTP au clone (préalable)

| Constat | Preuve | Action |
|---|---|---|
| `/` en 403 sans invite | `public_html/.htaccess` lignes 1-2 : `Require all denied` + `Options -Indexes`, verrou initial d'Astra du 10/09 (`docs/audit/2026-09-10/TRANSFERT-ISOLE.md` §3, §39) jamais retiré après ajout du bloc d'authentification | Lignes retirées **par Alain** dans l'éditeur cPanel (Claude bloqué par le classifieur de sécurité pour toute édition de `.htaccess`). Bloc cPanel `cp:ppd`, `SSLRequireSSL`, en-têtes `noindex`, garde MU inchangés |
| `/fr/` toujours 403 après ce retrait, `WWW-Authenticate` présent | HEAD depuis le serveur : `/kh2027-preflight.php` 401, `/index.php` 401, `/fr/` 403, `.css` 401 ; journal d'accès : `/fr/contact/` déjà 403 le 16/09 16:51 | Cause : `<Files "index.php">` avec `<RequireAll>` `Require valid-user` + `Require expr "%{REQUEST_FILENAME} == '…/index.php'"` ; sur URL réécrite l'expression est fausse → 403 avant l'invite. Correction à appliquer par Alain : remplacer le `RequireAll` par `Require valid-user` seul (authentification conservée) |

Permissions vérifiées : `public_html` `750` (groupe `nobody`), `wp-config.php` `600`, pas de `.htaccess` dans `~` ni `wp-content`. Garde MU `000-kh2027-web-review.php` présent (21 870 o, 16/09 16:51).

## PHP CLI du clone

`alt_php.ini` du compte : PHP 8.1.34, `open_basedir` = `public_html:kh2027-private/web-control:/tmp`, fonctions réseau/shell/processus désactivées. Le `wp` global (`/usr/local/bin/wp`, phar) est illisible dans ce contexte → WP-CLI muet. Invocation reprise d'Astra : `kh2027-private/web-control/php-command.json` (`php -n -d …` avec `open_basedir` incluant `/usr/local/bin/wp`), lancée via `python3 subprocess`, variables `WP_CLI_CONFIG_PATH/CACHE_DIR/PACKAGES_DIR` dans `web-control/`. Les scripts doivent résider dans `web-control/`.

## Fichiers déployés sur le clone (thème enfant, état PR #14 `95e8f54`)

Sauvegarde préalable : `kh2027-private/theme-before-pr14fix-20260917T105235Z/` (SHA-256 : walker `17d80fca…31e7`, editorial `ae788bcf…e525`, footer `5f1a16cb…dfed`, header.js `02ba3ddd…256b`).

| Fichier | SHA-256 déployé | = Git |
|---|---|---|
| `inc/class-icon-walker.php` | `87d6cfd9…8c1b` | oui |
| `inc/editorial.php` | `ae788bcf…e525` (identique à l'original : correctif revenu au code strict) | oui |
| `inc/footer.php` | `87541bed…9c38` (second dépôt ; le premier n'avait pas écrasé) | oui |
| `assets/js/header.js` | `d8ca12cb…ca84` | oui |

`php -l` : sans erreur ×3.

## Résultats

| Test | Résultat | Détail |
|---|---|---|
| 1 menu 5 pages **sans** classes assigné à `kh_header` | **PASS** | 5 `<li>` / 5 `</li>`, clés `shop,lifestyle,house,professionals,contact`, aucune icône neutre |
| 2 mêmes items **avec** classes `icon-*` | **PASS** | mêmes clés, 5 `</li>` |
| 3 lien custom inconnu | **PASS** | clé `link`, 6 `</li>`, icône neutre présente |
| 4 traduction légale EN (234) en brouillon | **PASS** | strict = `''`, légal = `https://sc3heal3867.universe.wf/fr/mentions-legales/` ; `notice-warning` sans « bloquant » |
| 5 page légale FR (232) en brouillon | **PASS** | `''` en FR et EN ; `notice-error` « bloquant avant mise en ligne » citant `mentions-legales` |
| 6 HTTP `/fr/` `/en/` | **BLOQUÉ** | 403 par le `Require expr` (voir ci-dessus) ; à refaire après correction `.htaccess` par Alain, puis authentification basique saisie par Alain dans l'onglet |
| 7 `import_enquiry_notification_drafts.php` (corrigé B2) en dry-run | **PASS** | « The 12 disabled notification drafts and six confirmations are already installed », aucune écriture ; le chemin rollback n'est exerçable que sur une base réinitialisée |

Script : `tools/recovery/test_pr14_blockers.php` @ `34679c6` (SHA `f831347c…160b`), première version `29c94a7` ne collectait pas les résultats (`eval-file` inclut le fichier dans une méthode ; corrigé via `$GLOBALS`). Sortie brute : `kh2027-private/web-control/tests-pr14-run2.txt`, `test7-dryrun.txt`.

Note : sur le clone, 232 ↔ 234 sont appariés dans Polylang (`fr=232 en=234`), contrairement au staging (inventaire du 17/09, I3).

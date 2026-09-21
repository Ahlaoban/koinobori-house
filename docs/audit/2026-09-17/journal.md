# Journal PC2 — inventaire staging, 2026-09-17

Session cPanel ouverte par Alain (`cow.o2switch.net:2083`, compte `heal3867`), terminal cPanel piloté par Claude. Aucun mot de passe échangé. Aucune écriture dans WordPress ni dans la racine web.

| UTC | Environnement | Commande / action | Résultat |
|---|---|---|---|
| ~09:05 | compte cPanel | `find /home3/heal3867 -maxdepth 3 -name wp-config.php` | 8 installations WP sur le compte ; staging = `/home3/heal3867/staging.koinoborihouse.com`, prod = `/home3/heal3867/koinoborihouse.com` ; `~/kh2027-private` existant (snapshots Astra du 10/09) ; `wp` 2.12.0, PHP CLI 8.3.33 |
| ~09:08 | staging | `wp config get DB_NAME` / `table_prefix` / `wp eval wp_get_environment_type(), realpath(ABSPATH), home, siteurl` / `wp core version` | **`heal3867_wp551`** (la valeur `wp320` de juin est périmée), `wprs_`, **`staging`** (déjà défini, aucune ligne à poser), `https://staging.koinoborihouse.com`, WP 7.1 |
| ~09:10 | prod (lecture) | `wp config get DB_NAME --path=…/koinoborihouse.com` | `heal3867_wp354` : base distincte, refusée explicitement par le script |
| ~09:12 | staging | `wp plugin list` | 14 plugins actifs (liste complète dans l'inventaire) |
| 09:16 | privé | `mkdir -p ~/kh2027-private/{tools,inventory}` + `chmod 700` | créés, `drwx------` |
| 09:17 | privé | upload `inventory.php` (gestionnaire cPanel) ; `chmod 600` ; `sha256sum` ; `php -l` | `e294756cdbb797b9f0731b3269abaea22ffb78de17d334fd88d2317ab0974561` = empreinte Git LF du commit `85b4030` ; « No syntax errors » |
| 09:17:37 | staging | `KH_INVENTORY_OUT=~/kh2027-private/inventory KH_INVENTORY_GIT_SHA=85b4030 wp eval-file ~/kh2027-private/tools/inventory.php --skip-plugins --skip-themes --exec="define('DISABLE_WP_CRON', true);"` | **13 gardes = true**, `staging-inventory-20260917T091737Z.private.json`, 66 631 octets, `0600`, SHA-256 `7018f2a44a5a46e5f059108bd258c5e0e03e3e36b50227db01ee8867775048fe` (consigné ici, jamais dans Git ; le fichier reste sur le serveur) |
| 09:18 | privé | upload `inventory-sanitize.php` ; `sha256sum` ; `php -l` ; `php tools/inventory-sanitize.php <privé> inventory` | `56d8ea7dd44b136e0aff91811d3298de842371de0017753c08bdd78efef66584` = Git ; scans secrets/emails/chemins/bases/query = **0 hit**, `EXIT=0` ; `staging-inventory-20260917T091737Z.public.json`, 61 631 octets, SHA-256 **`8be47d896a8770be0587c9b2e173771f872b98fc377426150d962c79591fd038`** ; `chmod 600` |
| 09:20 | privé | lecture du JSON public via le visualiseur cPanel (pas de téléchargement) | contenu analysé, cf `ANALYSE.md` |
| 09:47 | privé → poste | autorisation Alain (« OK ») ; `zip -j public-20260917.zip <public.json> <.sha256>` (SHA-256 zip `190f4674d678dc4cf2e4e5a65d20e1b8095501a54a687d96b35720595729896e`) ; téléchargement cPanel ; extraction locale | SHA-256 du JSON public recontrôlé en local = `8be47d89…d038` ; `JSON.parse` OK ; grep `heal3867|/home3|xkeysib|@domaine` = 0 ; fichiers committés dans `docs/audit/2026-09-17/` |

Fichiers serveur laissés en place (hors racine web, `0600`/`0700`) : `~/kh2027-private/tools/inventory.php`, `inventory-sanitize.php`, `~/kh2027-private/inventory/*.private.json`, `*.public.json`, `*.sha256`, `public-20260917.zip`. Copie locale du zip dans `C:\Users\herbi\Downloads\` (hors dépôt, `*.zip` ignoré).

Écarts par rapport à la procédure prévue : aucun. Correction préalable : constantes `KH_STAGING_DB` / `KH_STAGING_ROOT` / `KH_PRIVATE_ROOT` mises à jour depuis les valeurs relevées (commit `85b4030`) avant dépôt.

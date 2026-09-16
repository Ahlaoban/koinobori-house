# Inventaire staging (PC2) — procédure

Lecture seule. Session cPanel ouverte par Alain, terminal cPanel. Aucune clé SSH persistante. Inventaire seul dans la session : aucune correction.

## Prérequis (Alain)

1. Renseigner `KH_STAGING_ROOT` dans `inventory.php` avec le chemin réel du docroot staging (gestionnaire de fichiers cPanel). Tant qu'il est vide, le script refuse de tourner.
2. Confirmer `heal3867_wp320` et le préfixe `wprs_`.
3. Poser `define( 'WP_ENVIRONMENT_TYPE', 'staging' );` dans le `wp-config.php` du staging (une ligne, capture avant/après). Sans elle, la garde `environment_staging` échoue.
4. Créer hors racine web : `~/kh2027-private/tools/` et `~/kh2027-private/inventory/` (`chmod 700`).

## Dépôt du script

Déposer `inventory.php` dans `~/kh2027-private/tools/` via le gestionnaire cPanel, puis comparer :

```
sha256sum ~/kh2027-private/tools/inventory.php
```

avec `git hash-object` local n'étant pas SHA-256, utiliser `sha256sum tools/staging/inventory.php` sur le poste. Les deux valeurs doivent être identiques.

## Pré-vol (lecture seule)

```
wp --info
wp core version --extra
wp config get DB_NAME
wp config get table_prefix
wp eval 'echo wp_get_environment_type(), PHP_EOL;'
wp eval 'echo realpath( ABSPATH ), PHP_EOL;'
```

Attendus : `heal3867_wp320`, `wprs_`, `staging`, chemin = `KH_STAGING_ROOT`.

## Exécution

```
cd <docroot staging>
KH_INVENTORY_OUT=~/kh2027-private/inventory KH_INVENTORY_GIT_SHA=<sha du commit du script> \
wp eval-file ~/kh2027-private/tools/inventory.php --skip-plugins --skip-themes --exec="define('DISABLE_WP_CRON', true);"
sha256sum ~/kh2027-private/inventory/staging-inventory-*.private.json
```

Le script affiche sur stderr le résultat de chaque garde, puis sur stdout le nom du fichier, son SHA-256 et les compteurs par section. Le JSON privé reste sur le serveur (0600) ; une copie est rapatriée par le gestionnaire cPanel dans `C:\dev\_koinobori-safety-<date>\` (hors dépôt).

## Assainissement (poste local)

```
php tools/staging/inventory-sanitize.php C:\dev\_koinobori-safety-<date>\staging-inventory-<UTC>.private.json docs/audit/<date>
```

Refus si un secret, un email, un chemin serveur, un nom de base ou une query string subsiste. Produit `…public.json` + `.sha256`. Revue humaine (Alain ou Codex) avant `git add`. Le SHA-256 du privé est consigné dans le journal local, jamais dans Git.

## Journal

Chaque exécution : date UTC, environnement, commande exacte, SHA du script, résultat des gardes, SHA des deux JSON → `docs/audit/<date>/journal.md`.

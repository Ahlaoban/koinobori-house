# Inventaire staging (PC2) — procédure

Lecture seule. Session cPanel ouverte par Alain, terminal cPanel. Aucune clé SSH persistante. Inventaire seul dans la session : aucune correction.

## Prérequis (Alain)

1. `KH_STAGING_ROOT` = `/home3/heal3867/staging.koinoborihouse.com`, `KH_STAGING_DB` = `heal3867_wp551`, préfixe `wprs_` (relevés en session cPanel le 2026-09-17 ; la base `wp320` de juin est périmée).
2. La production est `heal3867_wp354` sur le même compte : le script la refuse explicitement.
3. `WP_ENVIRONMENT_TYPE` vaut déjà `staging` sur le staging (vérifié le 2026-09-17) : rien à poser.
4. Créer hors racine web : `~/kh2027-private/tools/` et `~/kh2027-private/inventory/` (`chmod 700`).

## Dépôt du script

Déposer `inventory.php` dans `~/kh2027-private/tools/` via le gestionnaire cPanel, puis comparer :

```
sha256sum ~/kh2027-private/tools/inventory.php
```

avec l'empreinte du fichier **tel que committé** (fins de ligne LF, indépendantes de l'autocrlf du poste Windows) :

```
git show <sha du commit>:tools/staging/inventory.php | sha256sum
```

Les deux valeurs doivent être identiques. Le poste de développement n'a pas d'interpréteur PHP : la vérification syntaxique se fait sur le serveur, en lecture seule, avant toute exécution :

```
php -l ~/kh2027-private/tools/inventory.php
```

## Pré-vol (lecture seule)

```
wp --info
wp core version --extra
wp config get DB_NAME
wp config get table_prefix
wp eval 'echo wp_get_environment_type(), PHP_EOL;'
wp eval 'echo realpath( ABSPATH ), PHP_EOL;'
```

Attendus : `heal3867_wp551`, `wprs_`, `staging`, chemin = `KH_STAGING_ROOT`.

## Exécution

```
cd <docroot staging>
KH_INVENTORY_OUT=~/kh2027-private/inventory KH_INVENTORY_GIT_SHA=<sha du commit du script> \
wp eval-file ~/kh2027-private/tools/inventory.php --skip-plugins --skip-themes --exec="define('DISABLE_WP_CRON', true);"
sha256sum ~/kh2027-private/inventory/staging-inventory-*.private.json
```

Le script affiche sur stderr le résultat de chaque garde, puis sur stdout le nom du fichier, son SHA-256 et les compteurs par section. Le JSON privé reste sur le serveur (0600) : il n'est jamais rapatrié.

## Assainissement (serveur, hors racine web)

Le poste de développement n'ayant pas de PHP, l'assainissement se fait sur le serveur, dans `~/kh2027-private/`. Déposer `inventory-sanitize.php` dans `tools/`, comparer son empreinte à celle de Git et passer `php -l` comme pour `inventory.php`, puis :

```
cd ~/kh2027-private
php tools/inventory-sanitize.php inventory/staging-inventory-<UTC>.private.json inventory
```

L'entrée doit se terminer par `.private.json` et le dossier de sortie doit exister. Refus si un secret, un email, un chemin serveur, un nom de base ou une query string subsiste. Produit `…public.json` + `.sha256`. Seuls ces deux fichiers sont rapatriés (gestionnaire cPanel), leur SHA-256 recontrôlé en local, puis revue humaine (Alain ou Codex) avant `git add` dans `docs/audit/<date>/`. Le SHA-256 du privé est consigné dans le journal, le fichier privé jamais dans Git.

## Journal

Chaque exécution : date UTC, environnement, commande exacte, SHA du script, résultat des gardes, SHA des deux JSON → `docs/audit/<date>/journal.md`.

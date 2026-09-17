# PC4 — corrections staging (préparation locale, exécution après GO Alain)

Ordre validé par Codex : **I1 → I2 → I3 → I5 → I6**. Chaque script : gardes d'identité staging (12), `dry-run` par défaut, `KH_APPLY=1 KH_CONFIRM=<jeton>` pour écrire, sauvegarde privée `0600` dans `~/kh2027-private/backups/` avant toute écriture, transaction avec rollback, restauration par `restore.php`. Tout est exécuté en session cPanel ouverte par Alain, depuis le docroot staging, scripts déposés dans `~/kh2027-private/tools/pc4/` après comparaison d'empreinte et `php -l`.

| Script | Jeton | Plugins | Écrit | Restauration |
|---|---|---|---|---|
| `i1-country-free-text.php` | `i1-country-free-text` | indifférent | `wprs_fluentform_forms.form_fields` id 7, 8 | `row` |
| `i2-redirect-lang.php` | `i2-redirect-lang` | indifférent | option `polylang.redirect_lang` | `option` |
| `i3-polylang-pairs.php` | `i3-polylang-pairs` | **chargés** (Polylang) | groupes de traduction 232↔234, 235↔236, 230↔231 | `polylang_group` |
| `i5-blogname.php` | `i5-blogname` | indifférent | option `blogname` = « Koinobori House » | `option` |
| `i6-shipping-zones.php` | — | indifférent | **rien** (rapport seul ; `KH_I6_TARIFFS_APPROVED=false`) | — |
| `restore.php <backup.json>` | `restore` | selon le kind | rejoue la sauvegarde | — |

## Avant toute écriture

1. Sauvegarde complète du staging (UpdraftPlus ou export SQL cPanel) avec empreinte notée dans le journal.
2. Dépôt et contrôle :
   ```
   sha256sum ~/kh2027-private/tools/pc4/*.php      # = git show <sha>:tools/staging/pc4/<f> | sha256sum
   for f in ~/kh2027-private/tools/pc4/*.php; do php -l "$f"; done
   ```
3. Dry-run de chaque script, lecture du plan, GO Alain script par script.

## I2 — contrôle HTTP avant / après

Le thème enfant de la PR #14 n'est pas encore sur le staging : ne pas chercher `id="kh-home-title"`. Marqueurs = titres des pages 318/319 relevés dans l'inventaire.

```
curl -sS -I -u "$KH_BASIC_USER" https://staging.koinoborihouse.com/fr/ | grep -i -E "^HTTP|^location"
curl -sS -I -u "$KH_BASIC_USER" https://staging.koinoborihouse.com/en/ | grep -i -E "^HTTP|^location"
curl -sS -u "$KH_BASIC_USER" https://staging.koinoborihouse.com/fr/ | grep -c "Des carpes de vent originales"
curl -sS -u "$KH_BASIC_USER" https://staging.koinoborihouse.com/en/ | grep -c "Original wind carps"
```

Attendu après I2 : `200` sans `location`, comptages ≥ 1. Avant I2 : noter les valeurs réelles (le constat « index du blog » date du 10/09).

## Après chaque script

- `wp litespeed-purge all` (ou purge depuis l'admin) si le cache est actif ; il était `false` le 17/09.
- Journal : date UTC, script, SHA, mode, sauvegarde (nom + SHA-256), résultat, contrôle après.
- Ne pas enchaîner I6 : rapport seul tant que les tarifs et le périmètre ne sont pas approuvés.

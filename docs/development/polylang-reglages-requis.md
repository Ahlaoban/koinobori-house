# Polylang — réglages requis (staging et production)

Réglage constaté faux sur le staging source le 2026-09-10 et corrigé uniquement sur le clone privé : sans `redirect_lang`, `/fr/` et `/en/` affichent l'index du blog au lieu des pages d'accueil 318 (FR) / 319 (EN), bien que `show_on_front=page` et `page_on_front=318`.

| Clé `polylang` | Valeur requise | Pourquoi |
|---|---|---|
| `redirect_lang` | `true` (`1`) | La racine de langue `/fr/` doit résoudre la page d'accueil de la langue, pas l'archive des articles |
| `hide_default` | `false` (`0`) | Doctrine CLAUDE.md : « Hide URL language for default = OFF » |
| `force_lang` | `1` | Langue portée par le répertoire `/fr/` `/en/` |

## Vérification (lecture seule)

```
wp option pluck polylang redirect_lang
wp option pluck polylang hide_default
wp option pluck polylang force_lang
```

Contrôle HTTP, en deux temps par langue (le staging est derrière une authentification basique : passer `-u` avec les identifiants saisis au clavier, jamais dans un script) :

```
# En-têtes seuls : statut attendu 200, aucune redirection
curl -sS -I -u "$KH_BASIC_USER" https://staging.koinoborihouse.com/fr/ | grep -i -E "^HTTP|^location"
curl -sS -I -u "$KH_BASIC_USER" https://staging.koinoborihouse.com/en/ | grep -i -E "^HTTP|^location"

# Corps : le gabarit d'accueil doit être rendu (1 occurrence attendue), pas l'index du blog
curl -sS -u "$KH_BASIC_USER" https://staging.koinoborihouse.com/fr/ | grep -c 'id="kh-home-title"'
curl -sS -u "$KH_BASIC_USER" https://staging.koinoborihouse.com/en/ | grep -c 'id="kh-home-title"'
```

Attendu : `HTTP/2 200` sans `location` pour `/fr/` et `/en/`, et `1` aux deux comptages. Un `0` signifie que la racine de langue sert encore l'archive des articles.

## Correction (point de contrôle 4, après sauvegarde vérifiée)

```
wp option patch update polylang redirect_lang 1
wp cache flush
```

Consigner date UTC, environnement, valeur avant/après dans `docs/audit/<date>/journal.md`. Le mu-plugin de diagnostic `koino-diag-frontpage.php` (2026-09-07) a rempli son rôle et a été retiré de l'arbre le 2026-09-16.

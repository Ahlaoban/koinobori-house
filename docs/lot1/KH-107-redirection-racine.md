# KH-107 — mu-plugin redirection racine i18n (Lot 1)

- **Ticket** : KH-107
- **Lot** : 1
- **Date exécution** : 2026-06-13
- **Statut** : 🟡 **Code prêt** (versionné repo) — déploiement staging + vérif à faire par Alain
- **Dépend de** : KH-104 (Polylang Free, `/fr/` + `/en/` actifs)
- **Environnement cible** : **staging** d'abord (`staging.koinoborihouse.com`), puis prod après KH-104b

## 1. Objectif

Redirection de la racine `/` vers la bonne langue, **sans** dépendre du détecteur natif Polylang (désactivé en KH-104 §3, anti double-mécanisme).

- `/` → **302** vers `/fr/` si `Accept-Language` `fr*`, sinon `/en/` (fallback EN)
- Cookie `koino_lang_pref` (90 j) **PRIME** sur `Accept-Language` (choix manuel > navigateur)
- **Jamais 301** sur la racine
- **Jamais** de redirection `/fr/` ↔ `/en/` (restent accessibles + indexables)

## 2. Code versionné — `wp/mu-plugins/koino-lang-redirect.php`

mu-plugin (chargé automatiquement, **avant** les plugins normaux → passe avant la redirection racine native de Polylang qui s'exécute sur `template_redirect`).

Choix techniques :

| Point | Décision | Raison |
|---|---|---|
| Hook | `init` priorité 1 | `pluggable.php` chargé (`wp_redirect` dispo) **et** avant le `template_redirect` de Polylang |
| Périmètre requête | racine exacte uniquement, via comparaison `home_url` path vs `REQUEST_URI` path | gère sous-dossier ; exclut nativement `/fr/`, `/en/`, `/wp-json/`, `/wp-admin/`, etc. |
| Garde-fous | `is_admin` / cron / ajax / `REST_REQUEST` / `WP_CLI` / `XMLRPC_REQUEST` + GET/HEAD seulement | ne jamais perturber admin, API, cron, POST de formulaires |
| Langue | cookie `koino_lang_pref` (`fr`/`en`) sinon parse `Accept-Language` (tri par `q`, fallback EN) | doctrine choix manuel > navigateur, défaut EN |
| Cookie | 90 j glissants, `path=/`, `Secure` si HTTPS, `HttpOnly=false`, `SameSite=Lax` | lisible par le sélecteur JS (KH-115) |
| Statut | `wp_redirect( $target, 302 )` | jamais 301 |
| Cache | `nocache_headers()` + `Vary: Cookie, Accept-Language` | racine non cacheable (cf exclusion LiteSpeed), pas de mauvaise langue servie par un cache/CDN |
| Query string | préservée (UTM, etc.) | `/?utm=x` → `/fr/?utm=x` |

Pas de boucle possible : `/fr/` et `/en/` ont un `req_path` ≠ racine → jamais redirigés.

## 3. Déploiement (Alain)

1. Créer le dossier `wp-content/mu-plugins/` sur le **staging** s'il n'existe pas.
2. Y téléverser `koino-lang-redirect.php` (fichier seul, pas de zip — les mu-plugins se chargent automatiquement).
3. Wordfence est désactivé sur le staging (KH-104 §1) → pas de blocage.

## 4. Vérifications attendues (avant prod)

- [ ] `staging.koinoborihouse.com/` avec `Accept-Language: fr-FR` → **302** vers `/fr/`
- [ ] `staging.koinoborihouse.com/` avec `Accept-Language: en-US` → **302** vers `/en/`
- [ ] `staging.koinoborihouse.com/` sans `Accept-Language` → **302** vers `/en/` (fallback)
- [ ] Cookie `koino_lang_pref=en` posé puis visite `/` avec `Accept-Language: fr-FR` → **302** vers `/en/` (cookie prime)
- [ ] Statut bien **302** (jamais 301) — vérifier en-tête HTTP
- [ ] `/fr/` et `/en/` → **200**, jamais redirigés entre eux
- [ ] `/wp-admin/`, `/wp-json/`, `/wp-login.php` → non perturbés
- [ ] Googlebot UA sur `/` → 302 (les deux langues restant indexables en direct)
- [ ] En-tête `Vary: Cookie, Accept-Language` présent sur la 302

## 5. Hors scope KH-107

- **Sélecteur de langue header/footer** (qui écrit `koino_lang_pref` sur choix manuel) → **KH-115**
- **hreflang + canonical + sitemaps séparés** → **KH-109** (RankMath)
- **Pages WC traduites + attribut Taille** → **KH-106** (Polylang for WooCommerce)
- **Gate complet 10 URLs** (cache, WC) → **KH-104b**

## 6. Suite

Une fois vérifié staging → reporter dans KH-104b-pré (le test racine `/` 302 Accept-Language + Googlebot fait partie du gate). Déploiement prod groupé après validation KH-104b.

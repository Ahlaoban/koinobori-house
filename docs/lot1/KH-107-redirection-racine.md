# KH-107 — mu-plugin redirection racine i18n (Lot 1)

- **Ticket** : KH-107
- **Lot** : 1
- **Date exécution** : 2026-06-13
- **Statut** : 🟡 **Code prêt** (versionné repo) — déploiement staging + vérif à faire par Alain
- **Cadre** : ⚠️ **KH-107 n'est plus un ticket isolé — c'est une composante du gate [KH-104b-pré](KH-104b-pre-plan.md)** (décision Alain 2026-06-13). Sa validation = groupe A (+ contributions C/D/E) du plan KH-104b-pré.
- **Dépend de** : KH-104 (Polylang Free, `/fr/` + `/en/` actifs)
- **Environnement cible** : **staging uniquement** (`staging.koinoborihouse.com`). **Pas de prod** tant que KH-104b non validé.

## 1. Objectif

Redirection de la racine `/` vers la bonne langue, **sans** dépendre du détecteur natif Polylang (désactivé en KH-104 §3, anti double-mécanisme).

- `/` → **302** vers `/fr/` si `Accept-Language` `fr*`, sinon `/en/` (fallback EN)
- Cookie `koino_lang_pref` (90 j) **PRIME** sur `Accept-Language` (choix manuel > navigateur)
- **Jamais 301** sur la racine
- **Jamais** de redirection `/fr/` ↔ `/en/` (restent accessibles + indexables)

## 2. Code versionné — `wp/mu-plugins/koino-lang-redirect.php`

mu-plugin (chargé automatiquement, **avant** les plugins normaux → passe avant la redirection racine native de Polylang).

> ⚠️ **v1.1.0 (2026-06-13)** — correctif après run 1 du gate KH-104b-pré.
> v1.0.0 utilisait le hook `init` priorité 1, en supposant que Polylang redirige `/` sur `template_redirect` (après `init`). **Faux** : Polylang redirige `/` vers la langue par défaut (toujours `/fr/`) **avant `init`** (phase de choix de langue). Résultat run 1 : notre logique ne tournait jamais sur `/` (A2/A3/A4/A6/C1 + cookie/Vary/Cache-Control en échec, tout partait vers `/fr/`). **Correctif** : exécution **au chargement du mu-plugin** (les mu-plugins sont chargés avant les extensions → on gagne la course de façon déterministe). À ce stade `pluggable.php` n'est pas chargé → redirection en `header()` natif au lieu de `wp_redirect()`.

Choix techniques :

| Point | Décision | Raison |
|---|---|---|
| Déclenchement | **exécution directe au chargement du mu-plugin** (pas de hook) | s'exécute avant l'inclusion de Polylang → gagne la course sur `/`, indépendamment de l'ordre des hooks |
| Redirection | `header( 'Location: …', true, 302 )` natif + `exit` | `wp_redirect()` indisponible si tôt (pluggable.php pas chargé) |
| Signature | en-tête `X-Redirect-By: koino-lang-redirect` | preuve dans les tests que c'est nous, pas Polylang |
| Périmètre requête | racine exacte uniquement, via comparaison `home_url` path vs `REQUEST_URI` path | `home_url()` non filtré à ce stade (Polylang pas chargé) ; exclut nativement `/fr/`, `/en/`, `/wp-json/`, `/wp-admin/`, etc. |
| Garde-fous | `is_admin` / cron / ajax / `REST_REQUEST` / `WP_CLI` / `XMLRPC_REQUEST` + GET/HEAD seulement | ne jamais perturber admin, API, cron, POST de formulaires |
| Langue | cookie `koino_lang_pref` (`fr`/`en`) sinon parse `Accept-Language` (tri par `q`, fallback EN) | doctrine choix manuel > navigateur, défaut EN |
| Cookie | 90 j glissants, `path=/`, `Secure` si HTTPS, `HttpOnly=false`, `SameSite=Lax` | lisible par le sélecteur JS (KH-115) |
| Statut | 302 | jamais 301 sur la racine |
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

Le déploiement staging + les vérifs (§3-4) s'exécutent **dans le cadre du gate** [KH-104b-pré](KH-104b-pre-plan.md) — voir ce plan pour la matrice complète (PASS/FAIL, captures, risques, rollback) et le script automatique `KH-104b-pre-tests.sh`. La checklist §4 ci-dessous = vue détaillée du groupe A du gate. **Pas de déploiement prod** avant validation KH-104b (pré puis full).

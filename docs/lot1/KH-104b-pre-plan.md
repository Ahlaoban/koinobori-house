# KH-104b-pré — plan d'exécution du gate i18n (sans WooCommerce) (Lot 1)

- **Ticket** : KH-104b (phase **pré**, sans WC)
- **Lot** : 1
- **Date rédaction plan** : 2026-06-13
- **Statut** : 🟡 plan prêt — exécution staging à faire par Alain
- **Environnement** : **staging uniquement** (`staging.koinoborihouse.com`). **Aucun déploiement production à ce stade.**
- **Compose** : KH-104 (Polylang Free) + **KH-107 (mu-plugin redirection racine — désormais composante de ce gate, plus un ticket isolé)**

## 0. Position dans le gate KH-104b

KH-104b se découpe en deux phases (cf KH-104 §5) :

| Phase | Contenu | Prérequis | Statut |
|---|---|---|---|
| **pré** (ce doc) | Fondation routage i18n : racine `/` 302, cookie, Accept-Language, `/fr/`+`/en/` directs, Googlebot, headers cache, intégrité Polylang Free | KH-104 ✅ + KH-107 (déploiement staging) | **en cours** |
| **full** | 10 URLs WC FR/EN (boutique/shop, produit/product, panier/cart, commande/checkout, mon-compte/my-account) + attribut Taille | KH-106 (licence Polylang for WC) + KH-109 (RankMath) | ⛔ bloqué (licence non achetée) |

**hreflang / canonical / sitemaps séparés** = fournis par RankMath (**KH-109, non fait**) → **hors pré**, testés en **full** (section conditionnelle §6 ci-dessous, marquée N/A tant que KH-109 absent).

**Règle gate** : `pré PASS` est nécessaire mais **pas suffisant** pour débloquer Lots 2-8. Le déblocage Lots 2-8 exige `full PASS` (zéro conflit Polylang for WC). pré valide la fondation avant d'engager l'achat de licence.

## 1. Périmètre testé (pré)

| Groupe | Objet | Dépend de |
|---|---|---|
| A | Racine `/` — négociation langue 302 | KH-107 |
| B | `/fr/` + `/en/` directs, jamais croisés | KH-104 |
| C | Comportement Googlebot UA | KH-104 + KH-107 |
| D | Admin / API / login non perturbés | KH-107 (garde-fous) |
| E | Intégrité Polylang Free (pas de double redirection / boucle) | KH-104 + KH-107 |
| F | hreflang / canonical / sitemaps | KH-109 — **N/A en pré** |

## 2. Préalable déploiement staging (Alain — je n'ai pas d'accès o2switch)

1. cPanel → Gestionnaire de fichiers → `staging.koinoborihouse.com/wp-content/`.
2. Créer le dossier `mu-plugins/` s'il n'existe pas.
3. Y téléverser `wp/mu-plugins/koino-lang-redirect.php` du repo (**fichier seul, pas de zip** — les mu-plugins se chargent automatiquement, pas d'activation).
4. WP admin → Extensions → onglet **« Indispensables / Must-Use »** → vérifier la présence de **« Koinobori — redirection racine i18n »**.
5. Vérifier l'absence d'erreur PHP (`wp-content/debug.log` si `WP_DEBUG`, ou logs o2switch).
6. Rappel KH-104 §1 : Wordfence est **désactivé** sur le staging (pas de lockout pendant les tests).

## 3. Procédure de test

Deux voies, complémentaires :

- **Voie A — script automatique** : `docs/lot1/KH-104b-pre-tests.sh` (curl). Couvre groupes A/B/C/D/E. Sortie PASS/FAIL + log horodaté. Voir en-tête du script pour l'usage (basic auth + `-k` cert auto-signé).
- **Voie B — navigateur (preuves visuelles)** : Chrome DevTools → onglet **Réseau**, case **« Conserver le journal »** + **« Désactiver le cache »**. Reproduire A1/A6/B1/B2 et capturer les en-têtes (302, `Location`, `Set-Cookie`, `Vary`, `Cache-Control`).

> Avant la série cookie (A6/A7/A8) : **vider les cookies** du domaine entre les cas, sinon un cookie résiduel fausse le résultat.

## 4. Matrice de tests + critères PASS/FAIL

### Groupe A — Racine `/` (KH-107)

| # | Requête | Attendu (PASS) | FAIL si |
|---|---|---|---|
| A1 | `GET /` · `Accept-Language: fr-FR,fr;q=0.9` · sans cookie | **302** → `Location: …/fr/` | autre code / `/en/` |
| A2 | `GET /` · `Accept-Language: en-US,en;q=0.9` · sans cookie | **302** → `…/en/` | autre |
| A3 | `GET /` · **sans** Accept-Language · sans cookie | **302** → `…/en/` (fallback EN) | `/fr/` ou autre |
| A4 | `GET /` · `Accept-Language: de-DE` · sans cookie | **302** → `…/en/` (non-fr ⇒ fallback) | `/fr/` |
| A5 | `GET /` · `Accept-Language: fr-CA` (fr*) · sans cookie | **302** → `…/fr/` | `/en/` |
| A6 | `GET /` · `Cookie: koino_lang_pref=en` · `Accept-Language: fr-FR` | **302** → `…/en/` (**cookie prime**) | `/fr/` ⇒ cookie ne prime pas |
| A7 | `GET /` · `Cookie: koino_lang_pref=fr` · `Accept-Language: en-US` | **302** → `…/fr/` | `/en/` |
| A8 | `GET /` · `Cookie: koino_lang_pref=xx` (invalide) · `Accept-Language: fr` | **302** → `…/fr/` (cookie ignoré ⇒ header) | `/en/` ou erreur |
| A9 | n'importe quel `GET /` | statut **= 302** | **= 301 ⇒ HARD FAIL** |
| A10 | `GET /` (A1) | `Set-Cookie: koino_lang_pref=fr` · `Path=/` · `SameSite=Lax` · `Secure` · expiration ~90 j | cookie absent / sans Secure en HTTPS / sans SameSite |
| A11 | `GET /` (A1) | en-tête `Vary: Cookie, Accept-Language` présent | absent |
| A12 | `GET /` (A1) | `Cache-Control` no-cache (nocache_headers) | en-tête cache positif (`max-age>0`, `public`) |
| A13 | `GET /?utm_source=x` · Accept-Language fr | `Location: …/fr/?utm_source=x` (query préservée) | query perdue |
| A14 | `HEAD /` · Accept-Language fr | **302** → `…/fr/` (idem GET) | autre |

### Groupe B — Langues directes (jamais touchées)

| # | Requête | Attendu (PASS) | FAIL si |
|---|---|---|---|
| B1 | `GET /fr/` | **200**, aucun `Location` | redirection / ≠200 |
| B2 | `GET /en/` | **200**, aucun `Location` | redirection / ≠200 |
| B3 | `GET /fr/` · `Accept-Language: en-US` | **200** `/fr/` (pas de renégociation) | redirigé vers `/en/` ⇒ **HARD FAIL** |
| B4 | `GET /en/` · `Cookie: koino_lang_pref=fr` | **200** `/en/` (pas de renégociation) | redirigé vers `/fr/` ⇒ **HARD FAIL** |

### Groupe C — Googlebot

| # | Requête | Attendu (PASS) | FAIL si |
|---|---|---|---|
| C1 | `GET /` · UA Googlebot · Accept-Language en | **302** → `…/en/` (Googlebot traité comme tout client ; les 2 langues restent indexables en direct) | bloqué / 5xx / 301 |
| C2 | `GET /fr/` + `GET /en/` · UA Googlebot | **200** chacune | ≠200 |

> ⚠️ **noindex actif sur staging** (basic auth + noindex KH-100b/104). Donc C ne teste que le **routage** sous UA Googlebot, **pas** l'indexabilité réelle. L'indexabilité réelle se vérifie en prod après retrait du noindex (post-launch) — **hors KH-104b-pré**.

### Groupe D — Admin / API non perturbés

| # | Requête | Attendu (PASS) | FAIL si |
|---|---|---|---|
| D1 | `GET /wp-admin/` (non connecté) | redirection vers `wp-login.php` (comportement WP natif), **pas** vers `/fr/`÷`/en/` | redirigé vers `/fr/` ou `/en/` |
| D2 | `GET /wp-login.php` | **200** (page login) | redirigé / 5xx |
| D3 | `GET /wp-json/` | **200** JSON REST, **pas** de 302 langue | redirigé vers `/fr/`÷`/en/` |

### Groupe E — Intégrité Polylang Free

| # | Requête | Attendu (PASS) | FAIL si |
|---|---|---|---|
| E1 | `GET /` (suivi des redirections, `curl -IL`) | **exactement 1 saut** : `/` → `/fr/` (ou `/en/`) → 200 | chaîne `/`→`/en/`→`/fr/` (double mécanisme Polylang+mu-plugin) ⇒ **HARD FAIL** |
| E2 | `GET /fr/` + `GET /en/` | aucune boucle de redirection | `ERR_TOO_MANY_REDIRECTS` / >1 saut |

## 5. Critère GO / NO-GO (pré)

- **GO KH-104b-pré** = **tous** les tests des groupes **A, B, C, D, E = PASS**.
- **HARD FAIL bloquants** (NO-GO immédiat, ne pas continuer) :
  - tout **301** sur la racine (A9),
  - toute redirection `/fr/` → `/en/` ou inverse (B3, B4),
  - double saut de redirection sur `/` (E1) = conflit Polylang/mu-plugin.
- **F (hreflang/canonical/sitemaps)** : **N/A en pré** (KH-109 non fait) → ne bloque pas le GO pré, sera exigé au gate **full**.
- **GO pré ≠ déblocage Lots 2-8.** Déblocage = **full PASS** (après licence Polylang for WC). pré = feu vert pour engager l'achat de licence + préparer full.

## 6. Section conditionnelle F — hreflang / canonical / sitemaps (à activer SI KH-109 fait)

Tant que RankMath (KH-109) n'est pas configuré, **marquer N/A**. Quand KH-109 sera fait :

| # | Vérif | Attendu |
|---|---|---|
| F1 | `<head>` de `/fr/` | `<link rel="alternate" hreflang="fr-FR">`, `hreflang="en">`, `hreflang="x-default">` cohérents |
| F2 | canonical | canonical **par langue**, jamais cross-lang |
| F3 | sitemaps | `/fr/sitemap.xml` + `/en/sitemap.xml` séparés, 200 |

## 7. Preuves à conserver (captures)

Stocker hors repo (contiennent l'URL staging + parfois l'en-tête d'auth) — dossier local Alain `captures/KH-104b-pre/` :

1. **Sortie du script** `KH-104b-pre-tests.sh` (fichier log horodaté, résumé PASS/FAIL).
2. **Screenshots DevTools Réseau** :
   - `/` (A1) : ligne 302 + onglet En-têtes montrant `Location: /fr/`, `Set-Cookie: koino_lang_pref`, `Vary`, `Cache-Control`.
   - `/` (A6) : 302 → `/en/` avec cookie `=en` (preuve « cookie prime »).
   - `/fr/` (B1) et `/en/` (B2) : 200.
3. **`curl -IL` sur `/`** (E1) : preuve du saut unique.
4. Capture onglet **Must-Use** des extensions (mu-plugin chargé).
5. Date, heure, navigateur, et commit `wp/mu-plugins/koino-lang-redirect.php` (hash) au moment du test.

> ❌ Ne **pas** committer ces captures (URL/credentials staging). Les conserver en local.

## 8. Risques + mitigations

| # | Risque | Mitigation |
|---|---|---|
| R1 | Polylang gagne la course ⇒ double redirection / mauvaise langue (E1) | mu-plugin hook `init` prio 1 (avant `template_redirect` Polylang). Si échec : monter plus tôt (`setup_theme`) ou confirmer « Détecter langue navigateur » bien OFF (KH-104 §3) |
| R2 | Basic auth staging bloque curl | `--user "StagingKH:****"` dans le script |
| R3 | Cert auto-signé staging | `curl -k` (déjà dans le script) |
| R4 | Cookie non posé (« headers already sent ») | mu-plugin agit sur `init` (aucune sortie avant) ; vérifier aucun espace/BOM avant `<?php`, aucun notice PHP |
| R5 | LiteSpeed sert une 302 en cache avec mauvaise langue | racine exclue du cache (doctrine) + `nocache_headers` + `Vary` ; au besoin purger le cache LiteSpeed avant test |
| R6 | noindex actif ⇒ indexabilité réelle non testable | attendu ; pré teste le routage, pas l'indexation. Indexation = prod post-launch |
| R7 | Cookie résiduel fausse A6/A7/A8 | vider cookies entre cas (script envoie un `Cookie:` explicite par requête, isolé) |

## 9. Rollback

- **mu-plugin** = **un seul fichier**, aucune écriture en base.
  - Rollback staging : renommer `wp-content/mu-plugins/koino-lang-redirect.php` → `koino-lang-redirect.php.off` (ou supprimer). Effet **immédiat** : retour au comportement natif Polylang. **Zéro impact données.**
- **Git** : branche `feat/kh-107-root-redirect` (PR #1) **non mergée sur `main`** → `main` intact. Rollback = fermer la PR sans merge.
- **Production** : **non concernée** (aucun déploiement prod à ce stade).

## 10. Suite

1. Alain déploie sur staging (§2) + exécute (§3-4).
2. Si **pré PASS** : reporter résultats ici (section « Résultats » à ajouter), puis décider de l'achat licence Polylang for WC pour KH-104b-**full** (10 URLs WC) + KH-109 (RankMath → hreflang/canonical/sitemaps).
3. Si **HARD FAIL** : bloquer, diagnostiquer (R1 en priorité), corriger le mu-plugin, re-tester.

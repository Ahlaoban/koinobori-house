# KH-104 — Polylang Free (i18n FR/EN) (Lot 1)

- **Ticket** : KH-104
- **Lot** : 1
- **Date exécution** : 2026-06-13
- **Statut** : ✅ **Cœur PASS** (sélecteur visuel header/footer → KH-115 ; gate complet → KH-104b)
- **Environnement** : **staging** `staging.koinoborihouse.com`

## 1. Préalable — incident résolu pendant l'exécution

- Le **re-clone du staging** (depuis prod post KH-101/102/103) a copié Wordfence, qui a **verrouillé l'accès** (lockout brute-force ; IP Alain dynamique → allow-list obsolète).
- **Résolution** : désactivation de Wordfence sur le staging par renommage du dossier `staging.koinoborihouse.com/wp-content/plugins/wordfence` → `wordfence-OFF`.
- **Décision** : Wordfence reste **désactivé sur le staging** (inutile derrière basic auth, évite les lockouts pendant les tests i18n). Prod inchangée (Wordfence actif).
- Staging re-cloné = DB `heal3867_wp551`, basic auth re-posé (user `StagingKH`), noindex actif.

## 2. Configuration Polylang Free

- Plugin **Polylang** (WP SYNTEX / Chouby), version Free — PAS Pro, PAS Polylang for WooCommerce (= KH-106).
- Langues via assistant :
  - **Français `fr_FR` (code `fr`) = langue par défaut** ⭐ (ajouté en 1er)
  - **English `en_US` (code `en`)** = secondaire
- Médias : **traduction désactivée** (1 seule bibliothèque — doctrine).
- Contenu existant assigné au **français**.

## 3. Réglages URL (Langues → Réglages → Modifications des URL)

| Réglage | Valeur | Doctrine |
|---|---|---|
| Mode | **Répertoire dans les permaliens** (`/fr/`, `/en/`) | ✅ |
| « Cacher l'info de langue dans l'URL de la langue par défaut » | **DÉCOCHÉ (OFF)** | 🔴 critique — FR garde `/fr/`, indexable |
| `/language/` dans les permaliens | **Retiré** | URLs propres |
| **Détecter la langue du navigateur** | **OFF** (lien « Activer » = état désactivé) | 🔴 la redirection racine = mu-plugin KH-107, pas Polylang (anti double-mécanisme) |

- Permaliens re-enregistrés (flush des règles de réécriture) → `/fr/` et `/en/` actifs.

## 4. Vérifications

- ✅ `staging.koinoborihouse.com/fr/` → page FR (200)
- ✅ `staging.koinoborihouse.com/en/` → page EN (200, contenu non traduit = normal, traductions = KH-115/106)
- ⚠️ `staging.koinoborihouse.com/` → redirige actuellement vers `/en/` (comportement natif Polylang) → **sera écrasé par le mu-plugin KH-107** (302 vers `/fr/` si Accept-Language `fr*`, sinon `/en/`)
- ✅ FR confirmé langue par défaut (étoile)

## 5. Hors scope KH-104 (suite)

- **Sélecteur de langue header + footer** → KH-115 (dépend des menus/header). Polylang le fournit nativement (menu metabox / widget / bloc) dès que la structure existe.
- **Redirection racine 302 + cookie `koino_lang_pref`** → KH-107 (mu-plugin custom versionné).
- **hreflang fr-FR/en/x-default + canonical + sitemaps séparés** → KH-109 (RankMath).
- **Pages WC traduites (boutique/panier/checkout/compte) + attribut Taille** → KH-106 (Polylang for WooCommerce, licence requise).
- **Gate complet** (10 URLs + hreflang + cookie + Googlebot + cache) → KH-104b (pré sans WC, full avec WC).

## 6. Suite immédiate

**KH-107** — mu-plugin racine : 302 `/` → `/fr/` (Accept-Language `fr*`) ou `/en/` (fallback EN), cookie `koino_lang_pref` 90j, cookie prime sur header, jamais 301, jamais redirection `/fr/`↔`/en/`. Code versionné repo `wp/mu-plugins/`.

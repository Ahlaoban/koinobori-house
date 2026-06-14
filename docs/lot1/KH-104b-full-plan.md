# KH-104b-full — Gate bilingue WooCommerce 10 URLs (Lot 1)

- **Ticket** : KH-104b-full (gate bloquant Lots 2-8)
- **Lot** : 1
- **Date plan** : 2026-06-14
- **Statut** : 🟡 **prêt — ouvre dès GO KH-109 ferme (V15 25/27 + V16 Santé OK)**
- **Pré-requis amont** : KH-104 (Polylang) PASS · KH-106 (Polylang for WC) GO · KH-106b (slugs) GO · KH-107 (racine) PASS · **KH-109 (SEOPress) GO**
- **Environnement** : **staging uniquement** · pas de prod · pas de merge PR #1 · **pas d'import catalogue réel** · KH-107 non touché · SEOPress non reconfiguré · Lots 2-8 non ouverts

## 0. Règle du gate (CLAUDE.md)

> Tester 10 URLs FR+EN + hreflang + canonical + sitemaps + cookie `koino_lang_pref` + racine `/` 302 Accept-Language + Googlebot UA. **Zéro conflit Polylang for WC = GO. Sinon = bloquer Lots 2-8.**

## 1. Décision actée AVANT exécution — base produit EN

La cible CLAUDE.md `/en/product/test` **n'est pas atteignable** sans Polylang Pro (KH-106b §1, décision Alain 2026-06-13 = **SKIP Pro**, option B). La base de réécriture produit single reste `produit` pour les deux langues.

| Cible CLAUDE.md | Réel staging (Free + add-on) | Statut |
|---|---|---|
| `/en/product/test` | **`/en/produit/test`** | **Limitation acceptée** (≠ hard fail) |

→ Le gate teste `/en/produit/<slug>/` (pas `/en/product/`). **Réouverture Pro conditionnée** : seulement si KH-104b-full démontre, **données à l'appui**, que `/en/product/` est réellement indispensable (pas sur hypothèse). Sinon GO avec limitation documentée.

## 2. Pré-requis d'exécution — 1 produit test bilingue

Le gate exige des URLs produit vivantes. **Un seul produit test** (≠ import catalogue réel, conforme doctrine) :

1. **Produits → Ajouter** : titre FR `Test`, slug **`test`**, prix quelconque, statut **Publié**.
   - Type : **variable + 1 variation** (Taille 75) pour exercer le même schéma que le catalogue (sync KH-010). Simple accepté si plus rapide — noter le choix.
2. Metabox **Langue = FR**.
3. **+ (traduire)** vers EN → produit EN, slug **`test`** (Polylang autorise le même slug par langue), statut **Publié**, Langue = EN.
4. Vérifier : `/fr/produit/test/` et `/en/produit/test/` → 200.
5. **À supprimer après le gate** (produit jetable, pas de prod).

## 3. Périmètre testé — matrice 10 URLs

| # | URL FR | URL EN | Attendu |
|---|---|---|---|
| 1 | `/fr/boutique/` | `/en/shop/` | 200, langue servie correcte |
| 2 | `/fr/produit/test/` | `/en/produit/test/` | 200 (base `produit` 2 langues = limitation §1) |
| 3 | `/fr/panier/` | `/en/cart/` | 200 |
| 4 | `/fr/commande/` | `/en/checkout/` | **302 vers panier/cart MÊME langue** (panier vide = WC normal) **ou** 200 |
| 5 | `/fr/mon-compte/` | `/en/my-account/` | 200 (ou 302 login same-lang) |

## 4. Contrôles transverses (sur les 10 + racine)

| Contrôle | Attendu PASS | Source |
|---|---|---|
| **Langue servie** | `<html lang="fr...">` sur `/fr/*`, `lang="en...">` sur `/en/*` | Polylang |
| **Pas de croisement** | aucune `Location:` `/fr/*`→`/en/*` ni l'inverse | Polylang + KH-107 |
| **Canonical self** | chaque URL → canonical de **sa propre** langue, jamais cross-lang | SEOPress |
| **hreflang** | `fr-FR` + `en`(/`en-US`) + `x-default`, **série unique** | Polylang |
| **Sitemaps par langue** | `/sitemaps.xml` couvre FR **et** EN | SEOPress (KH-109) |
| **Cookie** | `/` pose `koino_lang_pref` (SameSite=Lax, Secure, 90j) | KH-107 |
| **Racine 302** | `/` → 302 selon Accept-Language/cookie, **jamais 301** | KH-107 |
| **Googlebot UA** | `/` route en 302 ; `/fr/` `/en/` directs en 200 | KH-107 |
| **1 seul saut** | `/` = 1 redirection ; `/fr/` `/en/` = 0 (pas de conflit Polylang/mu-plugin) | intégrité |

## 5. Critères PASS / FAIL

**GO KH-104b-full** si **tous** :
- 10 URLs servent **un 200** (checkout/mon-compte : 302 same-lang panier/login accepté)
- **0 croisement** `/fr/`↔`/en/`
- langue servie correcte sur les 10
- canonical **self par langue** sur les 10 (jamais cross-lang)
- hreflang **série unique** (0 doublon) sur FR et EN
- sitemaps couvrent **FR + EN**
- cookie `koino_lang_pref` posé sur `/`
- racine `/` = 302 (jamais 301), 1 seul saut
- Googlebot route correctement
- Site Health : aucune anomalie critique neuve
- **= « zéro conflit Polylang for WC »**

## 6. Hard fails (bloquants Lots 2-8 — arrêt immédiat)

1. **301 sur `/`** (viole doctrine racine)
2. **Croisement `/fr/`↔`/en/`** (redirection d'une langue vers l'autre)
3. **Mauvaise langue servie** (ex. `/en/shop/` rend du FR)
4. **Canonical cross-lang** (`/fr/*` pointe `/en/*` ou inverse)
5. **Doublon hreflang** (SEOPress + Polylang émettent 2 séries)
6. **404** sur une des 10 URLs
7. **Double saut** sur `/` (>1 redirect = conflit Polylang for WC / mu-plugin)
8. Anomalie **critique** Site Health imputable à Polylang for WC

## 7. Exceptions acceptables (≠ fail)

| Exception | Raison |
|---|---|
| `/en/produit/test` (base `produit` au lieu de `product`) | limitation §1, Pro skip option B |
| checkout/mon-compte → **302 same-lang** (panier vide / login) | comportement WooCommerce normal |
| `en-US` au lieu de `en` dans hreflang | écart mineur doctrine, noter |
| **A11 Vary** absent | LiteSpeed strippe Vary, moot car Cache-Control no-store (exception connue KH-104b-pré) |
| **D2** `/wp-login.php` | exception connue KH-104b-pré |
| V-produit canonical/hreflang | mécanisme SEOPress+Polylang **identique** aux pages déjà validées KH-109 |

## 8. Outillage de test

| Couche | Script | Couvre |
|---|---|---|
| Racine / direct / Googlebot / admin / intégrité (A-E) | `KH-104b-pre-tests.ps1` (PowerShell) **ou** `.sh` (Git Bash) | racine 302, cookie, /fr//en/ directs, Googlebot, 1 saut |
| SEO i18n (F) : canonical/hreflang/sitemaps | `KH-109-seo-tests.ps1` (PowerShell) | V1-V14 KH-109 |
| **10 URLs WC + langue servie + canonical/hreflang par URL** | **`KH-104b-full-tests.ps1`** (PowerShell, ce ticket) | matrice §3-4 sur les 10 |

Le gate complet = re-run des 3 (ou des parties pertinentes) + preuves visuelles + Site Health.

## 9. Plan d'exécution

1. **GO KH-109 ferme** (V15 25/27 + V16 OK) — pré-condition.
2. Créer **produit test bilingue** (§2) → `/fr/produit/test/` + `/en/produit/test/` = 200.
3. **Réglages → Permaliens → Enregistrer** (flush, expose le produit dans le sitemap).
4. Run **`KH-104b-full-tests.ps1 -Auth "staging:MOTDEPASSE"`** (slug produit `test` par défaut).
5. Run **`KH-104b-pre-tests.sh`** (A-E, attendu 25/27) + **`KH-109-seo-tests.ps1`** (F, attendu 12/12) pour les couches racine + SEO.
6. **V16** Site Health.
7. Collecter preuves visuelles (head FR/EN, sitemap FR+EN, 1 fiche produit chaque langue).
8. **Verdict §5** GO/NO-GO.
9. **Supprimer le produit test**.
10. Si GO → **débloquer la décomposition Lots 2-8** (pas l'exécution — décomposition différée séparée).

## 10. Sortie du gate

- **GO** = « zéro conflit Polylang for WC » prouvé → décomposition Lots 2-8 autorisée.
- **NO-GO** = un hard fail §6 → Lots 2-8 restent bloqués, ajustement ciblé puis re-run.
- Reste interdit dans tous les cas : prod, merge PR #1, catalogue réel, modif KH-107, reconfig SEOPress.

# KH-106b — Slugs WooCommerce bilingues (pré-KH-109) (Lot 1)

- **Ticket** : KH-106b (suite de KH-106, prépare KH-104b-full)
- **Lot** : 1
- **Date plan** : 2026-06-13
- **Statut** : ✅ **GO (2026-06-13)** — slugs WC bilingues alignés sur staging (1 correctif `commander→commande`). Base produit `/en/produit/` acceptée (skip Pro). KH-107 non régressé.
- **Environnement** : **staging uniquement** · pas de prod · pas de merge · KH-107 non touché
- **Hors scope strict** : KH-109, KH-104b-full, RankMath, robots, sitemap, canonical, hreflang, mu-plugin KH-107, production

## Objectif
Slugs WC cohérents FR/EN avant KH-109 :

| FR | EN |
|---|---|
| produit | product |
| boutique | shop |
| panier | cart |
| commande | checkout |
| mon-compte | my-account |

## 1. 🔴 Finding bloquant — `produit→product` exige Polylang Pro

Source officielle ([doc](https://polylang.pro/doc/translating-woocommerce-urls-and-strings/)) : la traduction des **bases de réécriture** WooCommerce (base produit, base catégorie, base étiquette, endpoints) se fait via le groupe **« URL slugs » de Languages → Translations**, **réservé à Polylang Pro**. *« The free Polylang + Polylang for WooCommerce add-on cannot translate URL slugs. »*

Les 5 cibles ne sont pas homogènes :

| Cible | Nature | Free + add-on ? |
|---|---|---|
| boutique → shop | **Page WP** (slug de page, par langue) | ✅ |
| panier → cart | Page WP | ✅ |
| commande → checkout | Page WP | ✅ |
| mon-compte → my-account | Page WP | ✅ |
| **produit → product** | **Base de réécriture produit single** | ❌ **Pro requis** |

(Idem bases **catégorie-produit / étiquette-produit** → Pro ; hors des 5 cibles mais à noter pour le SEO complet.)

**✅ Décision Alain 2026-06-13 — SKIP Polylang Pro.**
- On **n'achète pas** Polylang Pro. Stack reste **Free + add-on WC** (doctrine respectée).
- On exécute **uniquement les 4 slugs de page** (shop/cart/checkout/my-account, Free).
- La base produit reste **`/en/produit/…`** (acceptée comme limite connue).
- **Réouverture du sujet Pro conditionnée** : seulement **si KH-104b-full démontre, données à l'appui, que `/en/product/` est réellement indispensable** (pas sur hypothèse).
- Séquence verrouillée : **slugs WC (ce ticket) → KH-109 → KH-104b-full**.

→ §8 tranché = **Option B** (avec condition de réouverture data-driven).

## 2. Phase 0 — Audit (Alain, lecture seule, AUCUNE modif)

À relever et me communiquer :

1. **Réglages → Permaliens** → section « Permaliens des produits » : valeur actuelle de la **base du produit** (ex. `produit` ? `boutique` ? base catégorie ?). Noter aussi base catégorie / étiquette.
2. **Pages → Toutes les pages** → pour chacune des 4 pages WC, le **slug FR** et le **slug de sa traduction EN** :
   - Boutique → (slug FR `boutique` ?) · EN → (slug `shop` ? ou autre ?)
   - Panier → `panier` ? · EN `cart` ?
   - Validation de la commande → `commande` ? · EN `checkout` ?
   - Mon compte → `mon-compte` ? · EN `my-account` ?
   *(L'assistant Polylang a probablement déjà créé les EN en anglais — à confirmer.)*
3. **WooCommerce → Réglages → Avancé → Configuration des pages** : quelles pages sont assignées (Panier/Commande/Mon compte) et le sont-elles **par langue** (Polylang for WC gère l'assignation par langue) ?
4. **Languages → Traductions (Traductions des chaînes)** : existe-t-il un **groupe « URL slugs » / « Slugs d'URL »** ? La **base produit** y est-elle listée et **le champ est-il éditable + enregistrable** ? *(C'est le test décisif Free vs Pro : si éditable → on peut traduire `produit` ; si absent/verrouillé → confirme qu'il faut Pro.)*
5. Rappel des URLs EN actuelles observées : fiche produit EN = `/en/produit/…` (base FR), à confirmer.

**L'audit tranche empiriquement le finding §1** (ne pas se fier qu'à la doc : vérifier le comportement réel de notre install).

## 3. Localisation exacte des réglages

| Élément | Où | Stack |
|---|---|---|
| Slugs des pages Boutique/Panier/Checkout/Mon compte | **Pages → éditer la page EN → Permalien → slug** (un par langue) | Free ✅ |
| Assignation des pages WC par langue | WooCommerce → Réglages → Avancé → Configuration des pages (+ metabox Langue par page) | Free ✅ |
| Base produit / catégorie / étiquette | Réglages → Permaliens (valeur globale) **puis** Languages → Translations groupe « URL slugs » | **Pro** ❌ |
| Endpoints (orders, edit-account…) | Languages → Translations (URL slugs) | **Pro** ❌ |

## 4. Procédure de modification (staging) — APRÈS audit + décision §8

### 4.A — Slugs de page EN (Free, toujours faisable)
Pour chaque page WC EN dont le slug n'est pas déjà l'anglais cible :
1. Pages → ouvrir la page **EN** (ex. « Shop »)
2. Bouton **Modifier** à côté du permalien → mettre le slug : `shop` / `cart` / `checkout` / `my-account`
3. **Mettre à jour**
4. Ne PAS toucher aux slugs FR (boutique/panier/commande/mon-compte restent)
5. Réglages → Permaliens → **Enregistrer** (flush des règles de réécriture)

### 4.B — Base produit `produit→product` (conditionnel)
- **Si audit §2.4 montre le groupe « URL slugs » éditable** → traduire `produit→product` (+ éventuellement catégorie/étiquette) → Enregistrer → flush permaliens.
- **Sinon (Pro requis)** → **NE PAS exécuter** ; appliquer la décision §8 (accepter `/en/produit/` ou acheter Pro). 

> ⚠️ Toute modif = **slugs/chaînes en base** uniquement. Pas de code, pas de mu-plugin, pas de RankMath/robots/sitemap/canonical/hreflang.

## 5. URLs à vérifier après modification

FR (inchangées, doivent rester 200) :
`/fr/boutique/` · `/fr/panier/` · `/fr/commande/` · `/fr/mon-compte/` · `/fr/produit/<slug>/`

EN (cibles) :
`/en/shop/` · `/en/cart/` · `/en/checkout/` · `/en/my-account/` · fiche produit EN (`/en/product/<slug>/` **si** §4.B fait, sinon `/en/produit/<slug>/`)

+ vérifier : pas de 404, pas de redirection `/fr/`↔`/en/`, chaque page sert la bonne langue.

## 6. Risques SEO

- **Staging = noindex** (KH-100b/104) → **aucun impact SEO live**. C'est le bon moment pour figer les slugs.
- Changer un slug = changer l'URL : si déjà indexée, il faudrait un 301 → **non applicable ici** (noindex, pas de catalogue réel).
- **Implication prod** : figer les slugs **maintenant** pour que la prod lance directement avec les bonnes URLs (éviter tout changement post-indexation au soft launch). Les redirections éventuelles = ressort de **RankMath KH-109** (hors scope ici).
- Canonical / hreflang / sitemap : **non touchés** (KH-109). Les slugs choisis seront simplement repris par RankMath plus tard.

## 7. Risques de régression KH-107

- **Négligeable.** Le mu-plugin KH-107 ne traite **que la racine `/`** (les autres chemins le contournent). Slugs de page et base produit n'ont **aucune** interaction avec la redirection racine.
- Le flush des permaliens régénère des règles de réécriture (rewrite), pas le mu-plugin (PHP au chargement).
- **Contrôle** : re-run `KH-104b-pre-tests.sh` après modif → doit rester **25/27 inchangé** (A sauf Vary, B, C, E verts ; A11+D2 = exceptions connues). Le script ne teste pas les pages WC → le résultat ne doit pas bouger.

## 8. Décision requise — base produit `produit→product`

| Option | Effet | Coût / impact |
|---|---|---|
| **A. Audit d'abord** (recommandé) | Vérifier si le groupe « URL slugs » est éditable dans notre Free+add-on avant de décider | 0 € — lecture seule |
| **B. Accepter `/en/produit/`** | Traduire seulement les 4 pages ; fiches EN restent sous base FR `produit` | 0 € — mais URL EN imparfaite, et la cible CLAUDE.md `/en/product/test` du gate KH-104b non atteinte |
| **C. Polylang Pro** | Acheter Pro (Business Pack ~139 €/an, ou Pro 99 €) → traduire la base produit → `/en/product/` | **Changement de stack** (CLAUDE.md = Free) → validation explicite requise |

→ Trancher après audit (§2.4 est décisif).

## 9. Plan GO / NO-GO

**GO** si, après §4 :
- 4 slugs EN = `shop` / `cart` / `checkout` / `my-account`, URLs EN → 200
- slugs FR inchangés (`boutique`/`panier`/`commande`/`mon-compte`) → 200
- base produit : selon décision §8 (GO conditionnel sur ce point documenté comme limite si option B)
- re-run KH-107 = **25/27 inchangé** (aucune régression)
- Site Health : aucune nouvelle anomalie critique
- aucun croisement `/fr/`↔`/en/`, aucun 404

**NO-GO / rollback** si : régression KH-107, croisement de langues, 404 après flush, ou comportement inattendu de Polylang.

## 10. Rollback

- **Capturer en Phase 0** les valeurs d'origine (slugs FR/EN des 4 pages, réglages Permaliens, valeurs du groupe URL slugs si présent).
- **Revert** : restaurer les slugs de page d'origine + réglages Permaliens d'origine → Réglages → Permaliens → **Enregistrer** (flush). Modifs **en base uniquement**, réversibles. mu-plugin KH-107 **jamais touché**. Pas de prod, PR #1 non mergée.

## 11. Séquence
1. Alain exécute **Phase 0 audit** (§2) → me communique les relevés (surtout §2.2 slugs EN actuels + §2.4 groupe URL slugs).
2. Je confirme le finding §1 (Free vs Pro) sur la réalité staging.
3. Décision §8 (Alain) pour `produit`.
4. Exécution §4 (4 pages + base produit conditionnelle) → vérif §5 + re-run KH-107 → verdict §9.

## 12. Résultats (2026-06-13) — 🟢 GO

**Audit §2** : l'assistant Polylang for WC avait déjà créé les **slugs EN en anglais**. État relevé (codes HTTP) :

| Langue | shop/cart/checkout/my-account · boutique/panier/commande/mon-compte |
|---|---|
| EN | shop 200 · cart 200 · checkout **302** (→ /en/cart/, panier vide = WC normal) · my-account 200 |
| FR | boutique 200 · panier 200 · **commande 301 → /fr/commander/** ❌ · mon-compte 200 |

→ **7/8 déjà bons.** Seule anomalie : la page checkout FR avait le slug **`commander`** (≠ doctrine `/fr/commande/`).

**Correctif unique** (page `Validation de la commande` FR, ID 76) : slug **`commander` → `commande`** + flush permaliens. EN intact. mu-plugin KH-107 non touché.

**Vérifs post-correctif** :
- `/fr/commande/` → **302** (→ /fr/panier/, panier vide) — plus de 301 vers `commander` ✅
- Tous les autres slugs inchangés et corrects
- **Non-régression KH-107** : re-run `KH-104b-pre-tests.sh` = **25/27** (`resultat-pre-postslug.txt`), cœur i18n intact (A sauf Vary, B, C, E verts ; A11+D2 exceptions connues) ✅

**Limite acceptée** (décision §1) : base produit **single** reste `/en/produit/…` (traduisible seulement avec Polylang Pro). Réouverture conditionnée à une preuve au KH-104b-full.

**Slugs finaux** : `/fr/boutique/ · /fr/panier/ · /fr/commande/ · /fr/mon-compte/ · /fr/produit/…` ↔ `/en/shop/ · /en/cart/ · /en/checkout/ · /en/my-account/ · /en/produit/…`

**Verdict KH-106b = 🟢 GO.** Rollback dispo (slug d'origine `commander`).

## 13. Suite
**KH-109 RankMath** (hreflang/canonical/sitemaps) → puis **KH-104b-full** (10 URLs). Si KH-104b-full démontre `/en/product/` indispensable (données) → rouvrir Polylang Pro.

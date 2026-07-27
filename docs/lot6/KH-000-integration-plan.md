# KH-000 — Plan d'intégration Design System « Ma (間) » dans WordPress/Kadence

> **Statut : plan validé par Alain le 2026-06-29 (arbitrages inclus). Aucun changement appliqué.**
> Source de vérité AD : **KH-000 Design System v1.2** (Manus), fichier `KH-000 — Koinobori House Design System.md` / `KH-000-Design-System.pdf`.
> Ce doc traduit la charte en plan d'exécution WordPress/WooCommerce/Kadence **sans toucher le socle** (WooCommerce, Polylang FR+EN slugs traduits, SEOPress sitemaps/hreflang) et **sans rouvrir le header** (validé v2, cf [[project_koinobori_ux_header_001_2026-06-26]]).

## 0. Contexte & cadrage

- **KH-000 v1.2 supersède** le premier doc « Washi & Encre » (lu en début de session). Palette/typo affinées : washi `#F7F3EC`, vermillon `#C8311A`, +Shippori Mincho, concept des **5 Mondes** = 5 collections.
- **Pas de migration** : reste WordPress/WooCommerce/Kadence Free + thème enfant `koinobori-child`. Pas de React/Shopify/headless. Pas d'Elementor. Pas de WP Rocket (LiteSpeed o2switch).
- **Header verrouillé** : la charte §9 confirme « header développé et validé par Claude → ne pas recréer, s'aligner dessus ». Le portage WP du header = Lot 7.
- Gouvernance : UX/AD = arbitrage exclusif Alain. Claude propose/structure, ne fige pas sans GO ([[feedback_ux_governance_alain_owns]]).
- Travaux **staging d'abord**, réversibles.

## 1. Arbitrages Alain (2026-06-29) — verrouillés

| # | Sujet | Décision |
|---|---|---|
| **A** | OR vs vermillon | **Or autorisé pour icônes / détails / header.** **Vermillon réservé aux CTA.** (Maintenu : jamais or en aplat de fond massif.) |
| **B** | Scope B2B/B2G | **B2B/B2G avancé (compte Pro, dashboard, demande de design en ligne, galerie) = PHASE 2.** MVP = landing + formulaire Fluent Forms uniquement. |
| **C** | Plugins | Ignorer Yoast (→ **SEOPress** verrouillé) et WP Rocket (→ **LiteSpeed**). Stack inchangé. |
| **D** | Fonts | **Self-host** les 5 polices (PAS Google Fonts CDN → RGPD/CNIL + perf). |
| **E** | Cartes produit | **Pas de blanc pur** (`#FFFFFF`) — blanc cassé/washi légèrement plus clair que le fond. |
| **F** | Rubriques header | ✅ **ARBITRÉ 2026-07-27 (C4)** : `Accueil · Boutique · Arts de vivre · Lifestyle · L'Atelier · Professionnels · Contact` (charte v2.0 §3). Annule la proposition `Boutique·Collections·Kaïro·Univers·Journal·Contact` et le mega-panel A-1. Reconciliation au portage Lot 7. |
| **G** | Wording « cousu à la main » | À vérifier vs doctrine production (jamais fabrication interne/origine) lors de la rédaction. |
| + | Asset header | Header validé utilise `blue-night-texture-III` + `logo-color-gold-III-cart` ; la charte cite `blue-night-texture-II` / `logo-color-gold-III`. **Le header validé prime.** Note de naming à réconcilier docs. |

## 2. Les 5 couches

### Couche 1 — Réglages globaux Kadence (Customizer, zéro code)

| Élément (charte) | Réglage |
|---|---|
| Palette §4 | Enregistrer 8 couleurs globales (washi, sumi, vermillon, or, brume, indigo, sakura, forêt) ; **désactiver la palette Kadence par défaut** (§16.3). |
| Fond global | Body = **washi `#F7F3EC`**, jamais blanc pur (§17.2). |
| Typo §5 | H1–H3 = Cormorant Garamond italic ; corps = Lora ; UI/prix/boutons = DM Sans. (Shippori Mincho + Noto Serif JP enqueue couche 2.) |
| Tailles | héros 56–80px, sous-titres 20–28px, corps 16–18px. |
| Boutons globaux | **vermillon** fond (CTA), washi texte, **radius 0**, DM Sans uppercase ls .15em. |
| Espacement *Ma* | padding vertical sections **≥ 80–120px** (§2, §17.12). |
| Footer | builder Kadence : logo **noir** `logo-color.png` + cartouche 鯉のぼり filigrane. |

**Staging-safe : 100% réversible** (Customizer export/import, aucun fichier touché).

### Couche 2 — Thème enfant `koinobori-child` (functions.php + style.css)

| Tâche | Détail |
|---|---|
| **Self-host 5 fonts** (D) | Cormorant Garamond, Shippori Mincho, Lora, DM Sans, Noto Serif JP en local (`/fonts/`), `@font-face` + enqueue **avant** Kadence (§16.2). |
| CSS vars `:root` | tous les `--kh-*` (§4) + 5 sous-palettes Mondes (`--kh-mer-*`, `--kh-kairo-*`, etc.). |
| Override palette éditeur | `add_theme_support('editor-color-palette', …)` (§16.3). |
| Neutraliser radius | `border-radius:0 !important` boutons/cartes WC (§16.4). |
| Désactiver anim Kadence | éviter conflits (§16.5). |
| Enqueue JS | `reveal.js` (IntersectionObserver §8) + pétales sakura (Hanami). |
| Classes utilitaires | `.kh-title-hero`, `.kh-subtitle-jp`, `.kh-body`, `.kh-ui`, `.kh-kanji`, `.kh-reveal`, `.kh-divider`, `.kh-kanji-bg`, `.kh-nav-link`, `.btn-primary`, `.btn-secondary`, `.product-card`. |
| `prefers-reduced-motion` | bloc §8. |
| **Couleurs (A)** | icônes/détails/séparateurs/nav-underline = **or `#B8860B`** autorisé ; CTA = **vermillon** ; jamais or en aplat de fond massif. |

**Staging-safe : oui** (versionné, rollback git ; fonts = ajout fichiers).

### Couche 3 — Blocs Gutenberg / Kadence (patterns + pages)

Contenu rédigé par le directeur de création (la charte le précise) ; Claude fournit l'ossature.

| Page | Pattern | Images (banque `koinobori-house-images/`) |
|---|---|---|
| Accueil | hero immersif + manifeste + **5 Mondes (grille asymétrique `1.3fr 1fr 1fr 1fr 1fr`)** + bestsellers + newsletter + lifestyle | `bg-hero-sky`, `bg-manifeste`, `cat-*-hero` |
| 5 pages Mondes (Mer/Kaïro/Hanami/Motifs/Territoires) | hero monde + ambiance + kanji fond, **palette propre par monde** (§6) | `cat-{mer,kairo,floral,motifs,territoires}-hero/ambiance` |
| Boutique | grille produits WC + filtres par monde | `boutique-hero`, `bg-boutique-fond` |
| Fiche produit | **2 blocs** (design/usage + signature BCDG) + carte + CTA vermillon | `prod-*`, `bg-product-page` |
| Univers | histoire + BCDG + manifeste | `apropos-hero`, `lifestyle-*` |
| Contact / B2B / B2G | formulaires Fluent Forms — **MVP = landing + form only (B)** | — |
| Footer | logo noir + cartouche filigrane + nav | — |
| Patterns réutilisables | ink-divider, kanji-bg, cartouche watermark, world-card, point vermillon | `texture-washi` |

**Staging-safe : oui** (contenu BDD ; backup UpdraftPlus avant).

### Couche 4 — CSS custom (hors portée Kadence)

- **WooCommerce** : `.product-card` (fond blanc cassé (E), hover élévation + zoom image 103%), prix Cormorant, bouton panier → `.btn-primary` (vermillon), grille archive, single-product, cart/checkout.
- **Signature §10** : `.kh-divider` (trait pinceau clip-path / `texture-washi`), `.kh-kanji-bg` (200–400px opacity .04), cartouche watermark (.04–.08), point vermillon, **nav underline = or (A)**.
- **Layouts asymétriques** (§16) là où Kadence Row est insuffisant.
- **Anims §8** : hover cartes, hero zoom loop 8s, pétales keyframes.
- Override styles résiduels Kadence.

**Staging-safe : oui** (Kadence Custom CSS pour l'itératif, style.css enfant pour le structurel).

### Couche 5 — Différé Lot 6/7

- **Portage header** validé v2 → Kadence (CSS/JS/PHP). **Aligner rubriques sur charte §9 (F)**.
- Surcharges templates WC PHP (`archive-product.php`, `single-product.php`, `cart/`) pour boutons/structure profonds (§16).
- Mobile : hamburger or + overlay Cormorant italic (§9 mobile).
- Pétales + animations avancées, polish.
- Optimisation images → WebP (`tools/png-to-webp.js`).
- ❌ **B2B/B2G avancé = PHASE 2 (B)** : compte Pro, dashboard, demande de design en ligne, galerie. **Pas** Lot 6/7.

## 3. Ordre d'exécution

1. **Backup staging** (UpdraftPlus).
2. **Couche 1** (Customizer : palette + typo + boutons + espacement) — effet immédiat, réversible.
3. **Couche 2** (fonts self-host + vars + overrides functions.php).
4. **Couche 4** (CSS structurel WC + signature).
5. **Couche 3** (patterns ; homepage d'abord ; contenu validé Alain).
6. **Couche 5** (Lot 6/7).

Tout sur **staging**. Aucune prod, aucun commit avant GO explicite.

## 4. Garde-fous maintenus (pas de conflit)

washi pas blanc pur ✓ · radius 0 ✓ · 5 collections = 5 Mondes (Mer·Kaïro·Hanami·Motifs·Territoires) ✓ · fiche **2 blocs** ✓ · marque fixe (Koinobori House ≠ signature BCDG ; cartouche 鯉のぼり) ✓ · vermillon parcimonie (CTA) ✓ · jamais Chine/atelier ✓ · jamais or en aplat de fond massif ✓ · Polylang FR+EN ✓ · sélecteur FR/EN sans drapeau ✓ · socle WC/Polylang/SEOPress intouché ✓.

## 5. Points ouverts

- **F** : rubriques header définitives (à arbitrer avant portage Lot 7).
- **G** : formulation « cousu à la main » à valider vs doctrine production.
- Naming assets header (II vs III) à réconcilier dans la doc charte.
- Mapping fin noms d'images charte (`cat-floral-*`) ↔ banque (`collections/hanami/`).

---

*Plan validé Alain 2026-06-29. Liens : [[project_koinobori_charte_manus_washi_encre_2026-06-29]], [[project_koinobori_ux_header_001_2026-06-26]], [[feedback_ux_governance_alain_owns]], [[feedback_wording_fiche_produit]].*

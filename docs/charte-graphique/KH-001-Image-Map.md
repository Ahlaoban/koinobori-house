# KH-001 — Koinobori House Image Map
## Guide d'intégration des images — Version 1.0
### Document complémentaire au KH-000 Design System v1.2

> **À l'attention de Claude :** Ce document précise, pour chaque image disponible dans l'archive `KH-000-images-completes.zip`, **où exactement** elle doit être placée dans WordPress/WooCommerce, **dans quel type de bloc Kadence**, et **avec quels paramètres CSS**. Les images sont remplaçables à tout moment via la médiathèque WordPress sans modifier le code.

> **Règle de remplacement :** Toutes les images peuvent être remplacées après preview via Médiathèque → Remplacer. Le code ne doit jamais référencer un chemin local — toujours l'URL WordPress générée après upload en médiathèque.

---

## Priorités d'intégration

| Priorité | Signification |
|---|---|
| 🔴 CRITIQUE | Visible immédiatement au chargement — à intégrer en PR2 |
| 🟡 IMPORTANT | Visible en scrollant — à intégrer en PR2/PR3 |
| 🟢 OPTIONNEL | Pages secondaires ou éléments décoratifs — PR3/PR4 |

---

## Page d'accueil (`/` et `/en/`)

### Section 1 — Hero principal

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `bg-hero-sky.png` | 🔴 CRITIQUE | **Kadence Row** — `background-image` de la Row hero | `background-size: cover`, `background-position: center top`, `background-attachment: fixed` (parallaxe), hauteur min `100vh` |
| `hero-koinobori.png` | 🔴 CRITIQUE | **Kadence Advanced Image** — image principale centrée sur le hero | `object-fit: contain`, largeur max `700px`, centré horizontalement, z-index au-dessus du fond |

> **Overlay obligatoire sur le hero :** Appliquer un gradient `linear-gradient(to bottom, rgba(26,20,16,0.15) 0%, rgba(26,20,16,0.55) 100%)` sur la Row pour garantir la lisibilité du titre Cormorant.

### Section 2 — Les 5 Mondes (cartes cinématographiques)

Layout asymétrique : 2 grandes cartes en haut (Mer + Kaïro), 3 cartes en bas (Hanami + Motifs + Territoires). Chaque carte = **Kadence Advanced Image** avec overlay au survol.

| Image | Priorité | Carte | Paramètres CSS |
|---|---|---|---|
| `cat-mer-hero.png` | 🔴 CRITIQUE | Carte Monde Mer (grande, haut gauche) | `background-size: cover`, `background-position: center`, hauteur `420px` desktop |
| `cat-kairo-hero.png` | 🔴 CRITIQUE | Carte Monde Kaïro (grande, haut droite) | `background-size: cover`, `background-position: center top`, hauteur `420px` desktop |
| `cat-floral-hero.png` | 🟡 IMPORTANT | Carte Monde Hanami (petite, bas gauche) | `background-size: cover`, `background-position: center`, hauteur `280px` desktop |
| `cat-motifs-hero.png` | 🟡 IMPORTANT | Carte Monde Motifs (petite, bas centre) | `background-size: cover`, `background-position: center`, hauteur `280px` desktop |
| `cat-territoires-hero.png` | 🟡 IMPORTANT | Carte Monde Territoires (petite, bas droite) | `background-size: cover`, `background-position: center bottom`, hauteur `280px` desktop |

> **Overlay survol cartes :** Au survol, appliquer `background-color: rgba(26,20,16,0.4)` en transition `300ms ease-out`. Le titre du Monde apparaît en Cormorant Garamond Italic blanc centré.

### Section 3 — Bestsellers / Créations BCDG

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `prod-okusai.png` | 🔴 CRITIQUE | **WooCommerce product card** — image principale produit Ōkusai | `object-fit: contain`, fond `#F7F3EC` (washi), pas de crop |
| `prod-sakura.png` | 🔴 CRITIQUE | **WooCommerce product card** — image principale produit Sakura | `object-fit: contain`, fond `#F7F3EC` |
| `prod-kairo-horizon.png` | 🔴 CRITIQUE | **WooCommerce product card** — image principale KAÏRO Koi 1 | `object-fit: contain`, fond `#0D0D0D` (sumi — fond sombre pour KAÏRO) |
| `prod-kairo-vaisseau.png` | 🟡 IMPORTANT | **WooCommerce product card** — image principale KAÏRO Koi 2 | `object-fit: contain`, fond `#0D0D0D` |
| `prod-bleu-mouette.png` | 🟡 IMPORTANT | **WooCommerce product card** — Municipalités bleu | `object-fit: contain`, fond `#F0F4F8` (bleu très clair) |
| `prod-rouge-vague.png` | 🟡 IMPORTANT | **WooCommerce product card** — Municipalités rouge | `object-fit: contain`, fond `#F7F3EC` |

### Section 4 — Manifeste / Storytelling

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `bg-manifeste.png` | 🟡 IMPORTANT | **Kadence Row** — `background-image` de la section manifeste | `background-size: cover`, `background-position: center`, overlay `rgba(247,243,236,0.88)` pour lisibilité du texte sumi |
| `apropos-manifeste.png` | 🟡 IMPORTANT | **Kadence Advanced Image** — image éditoriale à droite du texte manifeste | `object-fit: cover`, largeur `50%` desktop, pleine largeur mobile |

### Section 5 — Newsletter

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `bg-newsletter.png` | 🟢 OPTIONNEL | **Kadence Row** — fond de la section newsletter | `background-size: cover`, `background-position: center`, overlay indigo `rgba(27,43,94,0.75)` |
| `newsletter-hero.png` | 🟢 OPTIONNEL | **Kadence Advanced Image** — illustration décorative section newsletter | `object-fit: contain`, largeur max `300px`, opacité `0.85` |

---

## Page Boutique (`/boutique` et `/en/shop`)

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `boutique-hero.png` | 🔴 CRITIQUE | **Kadence Row** — hero pleine largeur page Boutique | `background-size: cover`, `background-position: center top`, hauteur min `320px`, overlay `rgba(26,20,16,0.45)` |
| `bg-boutique-fond.png` | 🟡 IMPORTANT | **Body** ou **Kadence Row** principale — fond de la grille produits | `background-size: 600px`, `background-repeat: repeat`, `opacity: 0.4` en overlay sur `#F7F3EC` |

> **Grille produits WooCommerce :** Chaque fiche produit utilise l'image principale WooCommerce définie dans la section "Créations BCDG" ci-dessus. Le fond de chaque carte est `#F7F3EC` (washi) — jamais blanc pur.

---

## Page Collections (`/collections` et `/en/collections`)

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `bg-collections.png` | 🔴 CRITIQUE | **Kadence Row** — hero page Collections | `background-size: cover`, `background-position: center`, hauteur min `400px`, overlay `rgba(26,20,16,0.5)` |
| `collection-banner.png` | 🟡 IMPORTANT | **Kadence Advanced Image** — bannière éditoriale section intro | `object-fit: cover`, pleine largeur, hauteur `200px` |

---

## Pages des 5 Mondes

### Monde Mer (`/collections/mer`)

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `cat-mer-hero.png` | 🔴 CRITIQUE | **Kadence Row** — hero pleine largeur page Mer | `background-size: cover`, `background-position: center top`, hauteur min `60vh`, overlay `rgba(27,43,94,0.4)` |
| `cat-mer-ambiance.png` | 🟡 IMPORTANT | **Kadence Advanced Image** — section éditoriale "L'univers de la Mer" | `object-fit: cover`, largeur `50%` desktop, pleine largeur mobile |

### Monde Kaïro (`/collections/kairo`)

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `cat-kairo-hero.png` | 🔴 CRITIQUE | **Kadence Row** — hero pleine largeur page Kaïro | `background-size: cover`, `background-position: center top`, hauteur min `60vh`, overlay `rgba(13,13,13,0.55)` (sombre — univers nuit) |
| `hero-kairo.png` | 🟡 IMPORTANT | **Kadence Advanced Image** — image principale section intro Kaïro | `object-fit: cover`, pleine largeur, hauteur `500px` desktop |
| `bg-kairo-section.png` | 🟡 IMPORTANT | **Kadence Row** — fond section narrative Kaïro | `background-size: cover`, `background-position: center`, overlay `rgba(13,13,13,0.7)` |
| `collection-kairo-banner.png` | 🟢 OPTIONNEL | **Kadence Advanced Image** — bannière collection Kaïro | `object-fit: cover`, pleine largeur, hauteur `180px` |
| `prod-kairo-horizon.png` | 🔴 CRITIQUE | **WooCommerce** — image produit KAÏRO Koi 1 dans la page | `object-fit: contain`, fond `#0D0D0D` |
| `prod-kairo-vaisseau.png` | 🟡 IMPORTANT | **WooCommerce** — image produit KAÏRO Koi 2 dans la page | `object-fit: contain`, fond `#0D0D0D` |

### Monde Hanami (`/collections/hanami`)

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `cat-floral-hero.png` | 🔴 CRITIQUE | **Kadence Row** — hero pleine largeur page Hanami | `background-size: cover`, `background-position: center`, hauteur min `60vh`, overlay `rgba(242,196,206,0.25)` (léger — ne pas noyer les tons rose) |
| `cat-floral-ambiance.png` | 🟡 IMPORTANT | **Kadence Advanced Image** — section éditoriale Hanami | `object-fit: cover`, largeur `50%` desktop |

### Monde Motifs (`/collections/motifs`)

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `cat-motifs-hero.png` | 🔴 CRITIQUE | **Kadence Row** — hero pleine largeur page Motifs | `background-size: cover`, `background-position: center`, hauteur min `60vh`, overlay `rgba(247,243,236,0.3)` |
| `cat-motifs-ambiance.png` | 🟡 IMPORTANT | **Kadence Advanced Image** — section éditoriale Motifs | `object-fit: cover`, largeur `50%` desktop |
| `cat-motifs-detail.png` | 🟢 OPTIONNEL | **Kadence Advanced Image** — zoom texture, section "Le détail qui fait la différence" | `object-fit: cover`, carré `300×300px`, flottant à droite du texte |

### Monde Territoires (`/collections/territoires`)

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `cat-territoires-hero.png` | 🔴 CRITIQUE | **Kadence Row** — hero pleine largeur page Territoires | `background-size: cover`, `background-position: center bottom`, hauteur min `60vh`, overlay `rgba(61,90,62,0.35)` (vert forêt) |
| `cat-territoires-ambiance.png` | 🟡 IMPORTANT | **Kadence Advanced Image** — section éditoriale Territoires | `object-fit: cover`, largeur `50%` desktop |

---

## Pages produits WooCommerce (`/produit/[slug]`)

| Image | Priorité | Emplacement WooCommerce | Paramètres |
|---|---|---|---|
| `prod-okusai.png` | 🔴 CRITIQUE | Image principale produit Ōkusai (champ "Image du produit" WC) | `object-fit: contain`, fond washi |
| `prod-sakura.png` | 🔴 CRITIQUE | Image principale produit Sakura | `object-fit: contain`, fond washi |
| `prod-kairo-horizon.png` | 🔴 CRITIQUE | Image principale KAÏRO Koi 1 | `object-fit: contain`, fond sumi `#0D0D0D` |
| `prod-kairo-vaisseau.png` | 🟡 IMPORTANT | Image galerie KAÏRO Koi 2 (champ "Galerie du produit" WC) | `object-fit: contain`, fond sumi |
| `prod-bleu-mouette.png` | 🟡 IMPORTANT | Image principale Municipalités bleu | `object-fit: contain`, fond `#F0F4F8` |
| `prod-rouge-vague.png` | 🟡 IMPORTANT | Image principale Municipalités rouge | `object-fit: contain`, fond washi |
| `bg-product-page.png` | 🟡 IMPORTANT | **Kadence Row** — fond de la section description produit | `background-size: cover`, `background-position: center`, overlay `rgba(247,243,236,0.92)` |
| `packaging-detail.png` | 🟢 OPTIONNEL | **Kadence Advanced Image** — section "Emballage & livraison" en bas de fiche produit | `object-fit: contain`, largeur max `400px` |

---

## Page Univers / À propos (`/univers`)

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `apropos-hero.png` | 🔴 CRITIQUE | **Kadence Row** — hero pleine largeur page Univers | `background-size: cover`, `background-position: center top`, hauteur min `50vh`, overlay `rgba(26,20,16,0.4)` |
| `apropos-manifeste.png` | 🟡 IMPORTANT | **Kadence Advanced Image** — image éditoriale section manifeste | `object-fit: cover`, largeur `45%` desktop, à droite du texte |
| `hero-alt-atelier.png` | 🟡 IMPORTANT | **Kadence Advanced Image** — section "L'atelier BCDG" | `object-fit: cover`, pleine largeur, hauteur `400px` |
| `lifestyle-interieur-1.png` | 🟡 IMPORTANT | **Kadence Advanced Image** — section "Dans votre intérieur" | `object-fit: cover`, largeur `50%` desktop |
| `lifestyle-interieur-2.png` | 🟢 OPTIONNEL | **Kadence Advanced Image** — 2e image lifestyle intérieur | `object-fit: cover`, largeur `50%` desktop |
| `lifestyle-exterieur.png` | 🟡 IMPORTANT | **Kadence Advanced Image** — section "Dans votre jardin / espace extérieur" | `object-fit: cover`, largeur `50%` desktop |
| `bg-indigo-waves.png` | 🟡 IMPORTANT | **Kadence Row** — fond section narrative indigo (chiffres clés, histoire) | `background-size: cover`, `background-position: center top` (lune visible en haut droite), overlay `rgba(27,43,94,0.6)` |

---

## Page Collection Ōkusai (`/collections/okusai`)

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `collection-okusai-hero.png` | 🔴 CRITIQUE | **Kadence Row** — hero page collection Ōkusai | `background-size: cover`, `background-position: center`, hauteur min `60vh`, overlay `rgba(27,43,94,0.45)` |
| `prod-okusai.png` | 🔴 CRITIQUE | **WooCommerce** — image produit dans la page | `object-fit: contain`, fond washi |

---

## Page Paiement / Checkout (`/paiement`)

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `packaging-paiement.png` | 🔴 CRITIQUE | **Kadence Advanced Image** — colonne droite de la page checkout, section "Votre commande arrive ainsi" | `object-fit: contain`, largeur max `320px`, fond washi, ombre légère `0 4px 24px rgba(26,20,16,0.08)` |

---

## Page Mon compte (`/mon-compte`)

| Image | Priorité | Bloc Kadence | Paramètres CSS |
|---|---|---|---|
| `compte-hero.png` | 🟢 OPTIONNEL | **Kadence Row** — hero page Mon compte | `background-size: cover`, `background-position: center`, hauteur `220px`, overlay `rgba(26,20,16,0.5)` |

---

## Fonds de page généraux (réutilisables)

Ces images peuvent être utilisées sur plusieurs pages selon le contexte éditorial.

| Image | Usage principal | Usage secondaire | Overlay recommandé |
|---|---|---|---|
| `bg-hero-sky.png` | Hero accueil | Hero alternatif toute page | `rgba(26,20,16,0.35)` |
| `bg-indigo-waves.png` | Sections narratives sombres | Fond page Univers | `rgba(27,43,94,0.55)` |
| `bg-washi-dark.png` | Sections éditoriales sombres sur washi | Fond footer | `rgba(26,20,16,0.15)` |
| `bg-manifeste.png` | Section manifeste accueil | Section storytelling B2G | `rgba(247,243,236,0.88)` |
| `bg-hero-encre.png` | Hero alternatif sombre | Section BCDG signature | `rgba(26,20,16,0.45)` |
| `hero-alt-ciel.png` | Hero alternatif accueil | Fond section saisonnière | `rgba(26,20,16,0.3)` |
| `texture-washi.png` | **Fond body global** (PR1) | Overlay décoratif sur sections claires | `background-blend-mode: multiply`, `opacity: 0.6` |

---

## Images à NE PAS intégrer dans WordPress

Ces images sont des assets de développement ou de charte — elles ne doivent pas apparaître sur le site public.

| Image | Raison |
|---|---|
| `charte-preview-1.png` | Aperçu de charte — usage interne uniquement |
| `charte-preview-2.png` | Aperçu de charte — usage interne uniquement |
| `homepage-section1-hero.png` | Maquette de référence — usage interne |
| `homepage-section2-mondes.png` | Maquette de référence — usage interne |
| `homepage-section3-produits.png` | Maquette de référence — usage interne |
| `header-mockup-closed.png` | Maquette header — usage interne |
| `header-mockup-open.png` | Maquette header — usage interne |
| `shoji-panel-left.png` | Asset header React — non utilisé dans WP |
| `shoji-panel-right.png` | Asset header React — non utilisé dans WP |
| `logo-left.png` | Asset header React — non utilisé dans WP |
| `logo-right.png` | Asset header React — non utilisé dans WP |
| `koi-rouge-header.png` | Non utilisé dans le header Claude |
| `koi-noir-header.png` | Non utilisé dans le header Claude |

---

## Paramètres CSS globaux pour les images

### Règles communes à toutes les images du site

```css
/* Toutes les images produits WooCommerce */
.woocommerce-product-gallery__image img,
.woocommerce ul.products li.product a img {
  object-fit: contain;
  background-color: var(--kh-washi);
}

/* Toutes les images de fond (background-image) */
/* Appliquer systématiquement un overlay via ::before ou gradient inline */
.kh-row-with-bg::before {
  content: '';
  position: absolute;
  inset: 0;
  background: var(--overlay-color, rgba(26,20,16,0.4));
  z-index: 1;
}

/* Images éditoriales (Advanced Image Kadence) */
.kb-image img {
  border-radius: 0; /* Jamais de border-radius sur les images */
}
```

### Règle de contraste texte / image

> **Règle absolue :** Tout texte posé sur une image doit avoir un overlay garantissant un ratio de contraste minimum AA (4.5:1). Vérifier systématiquement avec l'outil Chrome DevTools → Accessibility → Contrast.

| Fond image | Couleur texte recommandée | Overlay minimum |
|---|---|---|
| Image sombre (Kaïro, nuit) | `#F7F3EC` (washi) ou `#C9A96E` (or) | `rgba(13,13,13,0.5)` |
| Image claire (Hanami, ciel) | `#1A1410` (sumi) | `rgba(247,243,236,0.7)` |
| Image indigo (Mer, vagues) | `#F7F3EC` (washi) ou `#C9A96E` (or) | `rgba(27,43,94,0.55)` |
| Image washi/neutre | `#1A1410` (sumi) | Aucun overlay nécessaire |

---

## Ordre d'intégration recommandé par PR

| PR | Pages / sections | Images à intégrer |
|---|---|---|
| **PR2** — Header + Hero accueil | Header (Claude), Section 1 hero, Section 2 Mondes | `bg-hero-sky.png`, `hero-koinobori.png`, 5 × `cat-*-hero.png` |
| **PR3** — WooCommerce + Produits | Boutique, Fiches produits, Panier, Checkout | 6 × `prod-*.png`, `boutique-hero.png`, `bg-boutique-fond.png`, `packaging-paiement.png` |
| **PR4** — Pages éditoriales | Univers, Collections, 5 Mondes | `apropos-hero.png`, `bg-manifeste.png`, `bg-indigo-waves.png`, ambiances, lifestyle |
| **PR5** — Finitions | Newsletter, Mon compte, fonds secondaires | `bg-newsletter.png`, `compte-hero.png`, fonds alternatifs |

---

*KH-001 Image Map — Version 1.0 — Juin 2026*

*Document complémentaire au KH-000 Design System v1.2 — Koinobori House*

*Les images sont remplaçables à tout moment via la médiathèque WordPress sans modifier le code.*

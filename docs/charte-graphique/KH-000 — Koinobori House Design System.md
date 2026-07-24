# KH-000 — Koinobori House Design System
## Document de référence officiel — Version 1.2
### "Ma (間) : L'Intervalle Enchanté"

> **À l'attention de Claude et de tout collaborateur technique :** Ce document est la **bible graphique absolue** du projet Koinobori House. Avant toute décision de design ou de développement, la question est : *"Est-ce conforme au Design System ?"* — pas : *"Est-ce conforme au code React ?"*. Le rendu visuel est indépendant du framework. Ce document décrit le rendu. Votre mission est de le reproduire fidèlement dans WordPress/WooCommerce.

---

## Table des matières

1. [Distinction fondamentale des marques](#1)
2. [Concept directeur — Ma (間)](#2)
3. [Actifs fixes — INTOUCHABLES](#3)
4. [Palette de couleurs](#4)
5. [Typographie](#5)
6. [Les 5 Mondes — Architecture narrative](#6)
7. [Composants UI — Référence de code](#7)
8. [Animations et interactions](#8)
9. [Header Fusuma — Comportement exact](#9)
10. [Éléments signature récurrents](#10)
11. [Architecture des pages](#11)
12. [Ton éditorial](#12)
13. [Bilingue FR / EN](#13)
14. [Segments clients](#14)
15. [Inventaire complet des images](#15)
16. [Recommandations techniques pour WordPress/WooCommerce](#16)
17. [Règles absolues — Ce qu'on ne fait JAMAIS](#17)

---

## §1 — Distinction fondamentale des marques {#1}

> **KOINOBORI HOUSE** est la maison de vente, le canal e-commerce, l'espace d'accueil du visiteur. C'est le nom du site, le nom de la boutique, l'identité commerciale.
>
> **BCDG** est la **signature créative** apposée sur les produits. C'est l'artiste, le créateur, la main qui dessine. On n'entre pas dans "la boutique BCDG" — on entre dans la maison Koinobori House, et on y découvre les créations de BCDG.

Cette distinction doit être maintenue dans chaque texte, chaque titre, chaque description produit, chaque balise meta. La confusion entre les deux marques dilue l'identité de chacune.

**Exemples d'application :**

| Contexte | ❌ Incorrect | ✅ Correct |
|---|---|---|
| Titre de page | "Boutique BCDG" | "Koinobori House" |
| Description produit | "Koinobori BCDG Mer" | "Mer — Ōkusai · Création BCDG" |
| Meta title | "BCDG koinobori shop" | "Koinobori House — L'art du vent" |
| Signature produit | *(absente)* | "Signé BCDG" en mention discrète |

---

## §2 — Concept directeur : Ma (間) — L'Intervalle Enchanté {#2}

*Ma* (間) est l'un des concepts les plus profonds de l'esthétique japonaise. Il désigne l'espace entre les choses — le silence entre deux notes de musique, la pause entre deux respirations, le vide entre deux colonnes d'un temple. Ce n'est pas l'absence : c'est la **présence de ce qui n'est pas encore là, mais qu'on pressent**.

Le site Koinobori House est construit sur ce principe. Il ne dit pas tout. Il ne montre pas tout d'un coup. Il **invite à avancer**, à découvrir, à rester. L'internaute ne consomme pas un catalogue — il **voyage** dans un monde.

### Les trois émotions cibles

L'**émerveillement** — "Je n'ai jamais rien vu de pareil." Chaque page doit provoquer un moment de beauté inattendue. L'**envoûtement** — "Je ne veux pas partir." Le site doit être vivant, animé, respirant. Pas un musée figé. Le **désir** — "Je veux posséder cela." La beauté des créations BCDG doit être mise en scène comme des œuvres d'art, pas comme des articles de catalogue.

### Traduction pratique pour WordPress

Le concept *Ma* se traduit concrètement par : des sections très aérées avec beaucoup de padding vertical (minimum 120px), des textes courts et percutants (jamais plus de 4–5 mots pour un titre héros), des images qui respirent (jamais recadrées à l'excès), et des espaces blancs intentionnels entre chaque élément. Résister à la tentation de "remplir" les pages.

---

## §3 — Actifs fixes — INTOUCHABLES {#3}

Les éléments suivants ne peuvent être ni redessinés, ni remplacés, ni modifiés. Ils doivent être utilisés tels quels, en tant qu'images PNG.

| Fichier | Description | Usage |
|---|---|---|
| `logo-color.png` | Wordmark "Koinobori House" en calligraphie noire | Usage général (footer, pages intérieures, emails) |
| `logo-color-gold-III` | Wordmark "Koinobori House" en version or | **Header au repos** — centré sur fond nuit |
| `blue-night-texture-II` | Fond nuit indigo étoilée | **Header au repos** — fond permanent |
| `shoji-center-texture-II` | Texture washi chaude | **Header au survol** — fond révélé derrière le menu |

**Règle absolue :** Le logo calligraphique ne doit **jamais** être recréé en police numérique. Il doit toujours être utilisé comme image PNG. Le cartouche rouge 鯉のぼり est une partie intégrante du logo-right.png — il ne doit pas être recréé séparément.

---

## §4 — Palette de couleurs {#4}

### Palette globale — "Encre, Or et Brume"

La palette globale est le fil conducteur entre les 5 mondes. Elle est sobre, raffinée, jamais criarde.

| Rôle | Nom | Hex | OKLCH | Usage |
|---|---|---|---|---|
| **Fond principal** | Washi chaud | `#F7F3EC` | `oklch(0.965 0.012 75)` | Fond de **toutes** les pages — jamais de blanc pur |
| **Encre** | Sumi profond | `#1A1410` | `oklch(0.12 0.015 45)` | Textes, titres, contours |
| **Accent souverain** | Vermillon | `#C8311A` | `oklch(0.52 0.22 28)` | Cartouche, CTAs principaux — utilisé avec **parcimonie** |
| **Accent précieux** | Or brûlé | `#B8860B` | `oklch(0.62 0.12 75)` | Séparateurs, icônes, hover states |
| **Brume** | Gris perle | `#E8E4DC` | `oklch(0.91 0.008 75)` | Fonds secondaires, séparateurs |
| **Indigo** | Nuit profonde | `#1B2B5E` | `oklch(0.28 0.08 265)` | Sections narratives, monde Mer |
| **Rose** | Sakura | `#F2C4CE` | `oklch(0.84 0.06 10)` | Monde Hanami **uniquement** |
| **Vert** | Forêt | `#3D5A3E` | `oklch(0.38 0.07 145)` | Monde Territoires **uniquement** |

**Règle d'or :** Le vermillon `#C8311A` est une couleur de *signature*, pas de décoration. Il ne doit jamais être utilisé en aplat de fond ou en couleur dominante. Il marque les moments importants : le bouton d'achat, le cartouche, un titre de section clé.

### Variables CSS à déclarer dans WordPress (functions.php ou style.css)

```css
:root {
  --kh-washi:      #F7F3EC;
  --kh-sumi:       #1A1410;
  --kh-vermillon:  #C8311A;
  --kh-or:         #B8860B;
  --kh-brume:      #E8E4DC;
  --kh-indigo:     #1B2B5E;
  --kh-sakura:     #F2C4CE;
  --kh-foret:      #3D5A3E;

  /* Palettes des 5 Mondes */
  --kh-mer-1:      #1B2B5E;
  --kh-mer-2:      #F0F4F8;
  --kh-mer-3:      #C8D8E8;

  --kh-kairo-1:    #0D0D0D;
  --kh-kairo-2:    #C8311A;
  --kh-kairo-3:    #8B6914;

  --kh-hanami-1:   #F2C4CE;
  --kh-hanami-2:   #D4A853;
  --kh-hanami-3:   #8FAF7E;

  --kh-motifs-1:   #F7F3EC;
  --kh-motifs-2:   #1A1410;
  --kh-motifs-3:   #B8860B;

  --kh-territoires-1: #3D5A3E;
  --kh-territoires-2: #C17F3A;
  --kh-territoires-3: #E8D5B0;
}
```

---

## §5 — Typographie {#5}

Le système typographique croise l'élégance éditoriale occidentale avec la précision du sérif japonais. Il ne doit jamais ressembler à un site générique.

| Rôle | Police | Variante | Taille indicative |
|---|---|---|---|
| **Titres héros** | Cormorant Garamond | Italic 300–400 | 56–80px |
| **Sous-titres** | Shippori Mincho | Regular 400 | 20–28px |
| **Corps de texte** | Lora | Regular 400 | 16–18px |
| **UI / Prix / Labels** | DM Sans | Light 300 | 13–16px |
| **Mots japonais / Kanji** | Noto Serif JP | Regular 400 | Variable |
| **Citations éditoriales** | Cormorant Garamond | Italic 600 | 22–28px |

### Import Google Fonts (à placer dans `<head>` ou `functions.php`)

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400;1,600&family=DM+Sans:wght@300;400&family=Lora:ital,wght@0,400;1,400&family=Noto+Serif+JP:wght@400;500&family=Shippori+Mincho:wght@400;500&display=swap" rel="stylesheet">
```

### Règles typographiques

Les titres héros ne doivent jamais dépasser 4–5 mots. La typographie japonaise est économe. Un titre comme *"L'art du vent"* est plus puissant que *"Découvrez notre collection de koinobori artisanaux"*.

**Interdiction absolue :** Ne jamais utiliser Inter, Roboto, ou toute police sans-serif générique pour les titres. Ne jamais utiliser une seule police pour tout le site. Le système à 5 polices est intentionnel et doit être respecté intégralement.

### CSS typographique de base

```css
/* Titres héros */
.kh-title-hero {
  font-family: 'Cormorant Garamond', Georgia, serif;
  font-style: italic;
  font-weight: 300;
  letter-spacing: -0.02em;
  line-height: 1.1;
  color: var(--kh-sumi);
}

/* Sous-titres japonais */
.kh-subtitle-jp {
  font-family: 'Shippori Mincho', 'Noto Serif JP', serif;
  font-weight: 400;
  letter-spacing: 0.05em;
  color: var(--kh-sumi);
}

/* Corps de texte */
.kh-body {
  font-family: 'Lora', Georgia, serif;
  font-weight: 400;
  line-height: 1.75;
  color: var(--kh-sumi);
}

/* Labels UI, prix, navigation */
.kh-ui {
  font-family: 'DM Sans', system-ui, sans-serif;
  font-weight: 300;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  font-size: 0.75rem;
}

/* Kanji décoratifs */
.kh-kanji {
  font-family: 'Noto Serif JP', serif;
  font-weight: 400;
}
```

---

## §6 — Les 5 Mondes — Architecture narrative {#6}

Chaque catégorie de produits est un **monde à part entière**, avec son atmosphère cinématographique, sa palette, ses images, son ton éditorial. L'internaute qui passe de Mer à Hanami doit ressentir qu'il change de pays intérieur.

### Monde I — Mer 海

> *"Profond, large, sans fin. Comme la mer qui nourrit toutes les vies."*

**Référence cinématographique :** *Princesse Mononoké* (Miyazaki) — la mer primordiale, les esprits de l'eau. Aussi *La Vague* d'Hokusai — la mer comme force cosmique.

**Atmosphère :** Mystique, puissant, profond. La nuit sur l'océan. Le torii de Miyajima dans les vagues.

**Palette :** Indigo nuit `#1B2B5E` · Blanc écume `#F0F4F8` · Argent nacré `#C8D8E8` · Touches d'or `#B8860B`

**Images disponibles :** `cat-mer-hero.png` · `cat-mer-ambiance.png`

---

### Monde II — Kaïro 回廊

> *"Le corridor des ombres et de la lumière. Là où l'histoire devient légende."*

**Référence cinématographique :** *RAN* de Kurosawa — épopée tragique, ors et carmins, armures laquées. Aussi *Kagemusha* — la solitude du guerrier, la nuit avant le combat.

**Atmosphère :** Théâtral, souverain, dramatique. Noir laque et or brûlé.

**Palette :** Noir laque `#0D0D0D` · Vermillon `#C8311A` · Or brûlé `#8B6914` · Gris acier `#4A4A4A`

**Images disponibles :** `cat-kairo-hero.png` · `hero-kairo.png` · `bg-kairo-section.png` · `prod-kairo-horizon.png` · `prod-kairo-vaisseau.png`

---

### Monde III — Hanami 花見

> *"Le temps des cerisiers. La beauté qui sait qu'elle est éphémère."*

**Référence cinématographique :** *Le Voyage de Chihiro* (Miyazaki) — la lumière dorée du soir, les esprits qui dansent. Aussi *Kaguya-hime* — la pureté, la douceur.

**Atmosphère :** Onirique, tendre, lumineux. Rose sakura et or doux. Des pétales qui tombent.

**Palette :** Rose sakura `#F2C4CE` · Or doux `#D4A853` · Vert tendre `#8FAF7E` · Blanc lait `#FDF8F2`

**Images disponibles :** `cat-floral-hero.png` · `cat-floral-ambiance.png` · `prod-sakura.png`

---

### Monde IV — Motifs 文様

> *"Les formes qui portent des vœux. Chaque motif est une prière tissée dans le tissu."*

**Référence cinématographique :** *Kaguya-hime* (Takahata) — la géométrie céleste, la pureté des formes. Documentaires NHK sur les artisans japonais.

**Atmosphère :** Pur, précis, contemplatif. Blanc ivoire et encre sumi. Les motifs traditionnels (asanoha, seigaiha, kikko, shippo, uroko) comme un alphabet visuel millénaire.

**Palette :** Blanc ivoire `#F7F3EC` · Sumi noir `#1A1410` · Or patiné `#B8860B` · Rouge discret `#8B2020`

**Images disponibles :** `cat-motifs-hero.png` · `cat-motifs-ambiance.png` · `cat-motifs-detail.png`

---

### Monde V — Territoires 地域

> *"Le Japon profond. Chaque région a son âme, chaque ville sa mémoire."*

**Référence cinématographique :** Documentaires NHK *Japanology* — le Japon des régions, les festivals locaux. *Mononoke* pour la forêt primaire.

**Atmosphère :** Enraciné, terreux, vivant. Vert forêt et ocre. Les 47 préfectures comme autant de caractères.

**Palette :** Vert forêt `#3D5A3E` · Ocre terre `#C17F3A` · Beige sable `#E8D5B0` · Encre `#1A1410`

**Images disponibles :** `cat-territoires-hero.png` · `cat-territoires-ambiance.png`

---

## §7 — Composants UI — Référence de code {#7}

### Fond de page global

```css
body {
  background-color: var(--kh-washi); /* #F7F3EC — jamais #FFFFFF */
  color: var(--kh-sumi);
  font-family: 'Lora', Georgia, serif;
}
```

### Bouton principal (CTA — "Ajouter au panier", "Commander")

```css
.btn-primary {
  background: var(--kh-vermillon);    /* #C8311A */
  color: var(--kh-washi);             /* #F7F3EC */
  font-family: 'DM Sans', sans-serif;
  font-weight: 300;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  font-size: 0.75rem;
  padding: 0.75rem 2rem;
  border: none;
  border-radius: 0;                   /* JAMAIS de border-radius */
  transition: background 160ms ease-out, transform 160ms ease-out;
  cursor: pointer;
}
.btn-primary:hover {
  background: #A02515;
  transform: scale(1.02);
}
.btn-primary:active {
  transform: scale(0.97);
}
```

### Bouton secondaire (outline)

```css
.btn-secondary {
  background: transparent;
  color: var(--kh-sumi);
  font-family: 'DM Sans', sans-serif;
  font-weight: 300;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  font-size: 0.75rem;
  padding: 0.75rem 2rem;
  border: 1px solid var(--kh-sumi);
  border-radius: 0;
  transition: all 200ms ease-out;
}
.btn-secondary:hover {
  background: var(--kh-sumi);
  color: var(--kh-washi);
}
```

### Carte produit

```css
.product-card {
  background: #FFFFFF;                /* Fond blanc pur pour les cartes produit — contraste avec le fond washi */
  border: none;
  box-shadow: 0 2px 8px rgba(26, 20, 16, 0.08);
  transition: box-shadow 250ms ease-out, transform 250ms ease-out;
  overflow: hidden;
  border-radius: 0;                   /* Pas de border-radius */
}
.product-card:hover {
  box-shadow: 0 8px 32px rgba(26, 20, 16, 0.16);
  transform: translateY(-4px);
}
.product-card img {
  transition: transform 250ms ease-out;
  object-fit: contain;
}
.product-card:hover img {
  transform: scale(1.03);
}

/* Nom du produit */
.product-card .product-name {
  font-family: 'Cormorant Garamond', serif;
  font-style: italic;
  font-weight: 400;
  font-size: 1.25rem;
  color: var(--kh-sumi);
}

/* Prix */
.product-card .product-price {
  font-family: 'Cormorant Garamond', serif;
  font-weight: 400;
  font-size: 1.75rem;
  color: var(--kh-sumi);
  letter-spacing: -0.01em;
}
```

### Séparateur "pinceau d'encre"

Le séparateur entre les sections doit simuler un trait de pinceau irrégulier — jamais une ligne CSS droite et uniforme. Utiliser l'image `texture-washi.png` ou un SVG de trait de pinceau.

```css
.kh-divider {
  width: 120px;
  height: 2px;
  background: var(--kh-vermillon);
  /* Légère irrégularité simulée via clip-path */
  clip-path: polygon(0 0, 100% 20%, 100% 80%, 0 100%);
  margin: 2rem 0;
}
```

### Kanji décoratif en fond de section

```css
.kh-kanji-bg {
  position: absolute;
  font-family: 'Noto Serif JP', serif;
  font-size: clamp(200px, 30vw, 400px);
  opacity: 0.04;
  color: var(--kh-sumi);
  user-select: none;
  pointer-events: none;
  z-index: 0;
  line-height: 1;
}
```

### Navigation — lien avec soulignement animé

```css
.kh-nav-link {
  font-family: 'DM Sans', sans-serif;
  font-weight: 300;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  font-size: 0.75rem;
  color: var(--kh-sumi);
  text-decoration: none;
  position: relative;
}
.kh-nav-link::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 1px;
  background: var(--kh-or);           /* Or brûlé pour le soulignement */
  transition: width 200ms ease-out;
}
.kh-nav-link:hover::after {
  width: 100%;
}
```

### Texture washi en fond de page

```css
/* Appliquer sur body ou sur les sections principales */
.kh-washi-texture {
  background-color: var(--kh-washi);
  background-image: url('/wp-content/themes/koinobori-house/assets/images/texture-washi.png');
  background-repeat: repeat;
  background-size: 400px 400px;
  background-blend-mode: multiply;
}
```

---

## §8 — Animations et interactions {#8}

### Principes généraux

Le site doit être **vivant en permanence**, même sans interaction. Les animations doivent être discrètes, jamais distrayantes. Respecter `prefers-reduced-motion`.

```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}
```

### Animations d'interaction des cartes et boutons

| Élément | Au repos | Au survol | Durée |
|---|---|---|---|
| Carte produit | Ombre légère | Élévation 4px + ombre profonde + zoom image 103% | 250ms ease-out |
| Bouton CTA | Fond vermillon | Fond plus sombre + scale(1.02) | 160ms ease-out |
| Bouton `:active` | Normal | scale(0.97) | 100ms ease-out |
| Lien navigation | Texte normal | Soulignement or qui se dessine gauche→droite | 200ms ease-out |
| Image hero | Statique | Zoom très lent 100%→103% en boucle | 8000ms linear |

### Animations de révélation au scroll (Intersection Observer)

Chaque section se dévoile comme un kakemono qu'on déroule. Implémenter avec Intersection Observer API ou une bibliothèque légère (AOS, GSAP ScrollTrigger).

```css
/* État initial — avant révélation */
.kh-reveal {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 600ms ease-out, transform 600ms ease-out;
}
.kh-reveal.kh-reveal--text {
  transform: translateY(15px);
  transition-delay: 100ms;
}
.kh-reveal.kh-reveal--image {
  transform: none;                    /* Images : fondu seul, pas de glissement */
  transition-delay: 200ms;
}
.kh-reveal.kh-reveal--card {
  transform: translateY(25px);
}

/* État révélé — après passage dans le viewport */
.kh-reveal.is-visible {
  opacity: 1;
  transform: translateY(0);
}

/* Stagger pour les grilles de cartes */
.kh-reveal.kh-reveal--card:nth-child(1) { transition-delay: 0ms; }
.kh-reveal.kh-reveal--card:nth-child(2) { transition-delay: 80ms; }
.kh-reveal.kh-reveal--card:nth-child(3) { transition-delay: 160ms; }
.kh-reveal.kh-reveal--card:nth-child(4) { transition-delay: 240ms; }
```

```javascript
// Intersection Observer — à placer dans le JS du thème
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('is-visible');
    }
  });
}, { threshold: 0.15 });

document.querySelectorAll('.kh-reveal').forEach(el => observer.observe(el));
```

### Animations permanentes (background)

Ces animations tournent en boucle, discrètes, jamais distrayantes :

```css
/* Pétales de sakura — pages Hanami uniquement */
@keyframes petal-fall {
  0%   { transform: translateY(-10px) rotate(0deg);   opacity: 0; }
  10%  { opacity: 0.4; }
  90%  { opacity: 0.3; }
  100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
}
.kh-petal {
  position: fixed;
  width: 8px;
  height: 8px;
  background: var(--kh-sakura);
  border-radius: 50% 0 50% 0;
  animation: petal-fall linear infinite;
  pointer-events: none;
  z-index: 0;
}
/* Varier la durée et le délai pour chaque pétale : 8s à 12s, délais de 0 à 6s */
```

---

## §9 — Header — Comportement exact {#9}

> **Note de développement :** Le header a été développé et validé par **Claude** (Anthropic). Ce document décrit fidèlement son comportement et ses assets pour assurer la cohérence avec le Design System KH-000. Ne pas recréer ce header depuis zéro — utiliser le code source fourni par Claude comme référence.

Le header est la signature interactive la plus distinctive du site. Il repose sur une **métaphore nuit / intérieur** : au repos, l'utilisateur voit la nuit étoilée ; au survol, la navigation s'ouvre sur un fond de texture washi chaleureux.

### Principe des deux états

| État | Fond | Logo | Navigation |
|---|---|---|---|
| **Repos** (pas de survol) | `blue-night-texture-II` — nuit indigo étoilée | `logo-color-gold-III` — version or du wordmark, centré en grand format | Cachée |
| **Survol** (hover) | `shoji-center-texture-II` — texture washi chaude | Masqué en fondu | Icônes or révélées au centre |

### Assets obligatoires du header

| Fichier | Rôle | État |
|---|---|---|
| `blue-night-texture-II` | Fond nuit indigo — arrière-plan permanent du header | Repos |
| `logo-color-gold-III` | Wordmark "Koinobori House" en version or — centré, grand format | Repos |
| `shoji-center-texture-II` | Texture washi chaude — révélée derrière les icônes au survol | Survol |

> **Important :** Le logo utilisé dans le header est **`logo-color-gold-III`** (version or), et non `logo-color.png` (version noire). Le fond nuit est **`blue-night-texture-II`**, et non `bg-indigo-waves.png`. Ces distinctions sont critiques pour la lisibilité.

### Comportement animé

**Au repos :**
- Le fond `blue-night-texture-II` couvre 100% du header
- Le logo `logo-color-gold-III` est centré, visible en grand format
- La navigation est entièrement masquée (opacity 0)

**Au survol (hover sur le header) :**
1. Le logo disparaît en fondu (opacity 0, transition ~200ms)
2. La texture `shoji-center-texture-II` apparaît en overlay sur le fond nuit
3. Les icônes de navigation or apparaissent au centre (opacity 1, transition ~400ms avec délai 200ms)
4. Une ligne vermillon `#C8311A` apparaît en bas du header

**À la sortie du survol :**
- Séquence inversée : menu disparaît en premier, logo réapparaît en dernier

### Navigation — icônes et rubriques

Les icônes sont des SVG inline, `stroke: #C9A96E` (or) au repos, `stroke: #C8311A` (vermillon) au survol. `fill: none`, `stroke-width: 1.4`, `viewBox 0 0 24 24`.

| Icône | Rubrique | URL |
|---|---|---|
| Étiquette / tag | Boutique | `/boutique` |
| Calques empilés | Collections | `/collections` |
| Koi stylisé | Kaïro | `/collections/kairo` |
| *Séparateur or* | — | — |
| Boussole | Univers | `/univers` |
| Carnet à lignes | Journal | `/journal` |
| Enveloppe | Contact | `/contact` |
| *Séparateur or* | — | — |
| FR / EN | Polylang | Sélecteur de langue |
| Sac / panier | Panier | WooCommerce cart |

Chaque icône possède un **tooltip** : fond sumi `rgba(26,20,16,0.88)`, texte or vif `#E8C87A`, bordure `rgba(201,169,110,0.25)`, DM Sans Light 9px, lettrespace 0.18em, majuscules. Apparaît sous l'icône au survol.

### Onboarding (première visite)

Une seule fois par session : le header s'ouvre automatiquement 900ms après le chargement de la page, reste ouvert 2 secondes, puis se referme. Un hint "Survolez pour naviguer" apparaît en bas du header pendant l'onboarding. Géré via `sessionStorage`.

### Mobile — comportement alternatif

Sur mobile (< 768px) : le fond nuit reste visible, le logo `logo-color-gold-III` est centré. Un bouton hamburger (3 traits or) s'affiche à droite. Au clic, un overlay s'ouvre avec les liens de navigation en **Cormorant Garamond Italic** grand format sur fond washi `shoji-center-texture-II`.

---

## §10 — Éléments signature récurrents {#10}

Ces éléments visuels doivent apparaître régulièrement sur tout le site pour créer une cohérence reconnaissable.

**1. La ligne d'encre vermillon** — un trait de pinceau sumi, irrégulier, comme tracé à la main. Utilisé comme séparateur de sections et sous les titres de section. Jamais une ligne CSS droite et uniforme. Implémenter avec l'image `texture-washi.png` ou un SVG de trait de pinceau.

**2. Le cartouche rouge 鯉のぼり** — apparaît en watermark discret sur les fonds de page (opacité 0.04–0.08), en favicon, et comme signature sur les images produits. Toujours l'image PNG — jamais recréé en CSS.

**3. La texture washi** — fond papier légèrement grainé sur toutes les pages. Jamais un blanc pur `#FFFFFF`. Toujours le washi chaud `#F7F3EC` avec la texture `texture-washi.png` en overlay.

**4. Les kanji décoratifs** — des caractères japonais en très grande taille (200–400px), opacité 0.04, en fond de section. Exemples : 海 (mer), 花 (fleur), 風 (vent), 夢 (rêve), 間 (ma). Utiliser la classe `.kh-kanji-bg` définie au §7.

**5. Le point vermillon** — un petit cercle plein `#C8311A` de 6–8px utilisé comme puce de liste, séparateur de breadcrumb, ou accent de navigation.

---

## §11 — Architecture des pages {#11}

### Pages B2C (grand public)

| Page | URL FR | URL EN | Description |
|---|---|---|---|
| Accueil | `/` | `/en` | Hero immersif, 5 mondes, bestsellers, manifeste |
| Boutique | `/boutique` | `/en/shop` | Catalogue complet avec filtres par monde/catégorie |
| Collections | `/collections` | `/en/collections` | Vue éditoriale des 5 mondes |
| Mer | `/collections/mer` | `/en/collections/sea` | Page monde Mer |
| Kaïro | `/collections/kairo` | `/en/collections/kairo` | Page monde Kaïro |
| Hanami | `/collections/hanami` | `/en/collections/hanami` | Page monde Hanami |
| Motifs | `/collections/motifs` | `/en/collections/motifs` | Page monde Motifs |
| Territoires | `/collections/territoires` | `/en/collections/territories` | Page monde Territoires |
| Produit | `/produit/[slug]` | `/en/product/[slug]` | Page produit détaillée |
| Univers | `/univers` | `/en/about` | Histoire, manifeste, BCDG |
| Contact | `/contact` | `/en/contact` | Formulaire de contact |
| Paiement | `/paiement` | `/en/checkout` | Image `packaging-paiement.png` |

### Pages B2G & B2B (comptes professionnels)

| Page | Description |
|---|---|
| **Espace Professionnel** | Landing page dédiée — storytelling "500 koi pour les 100 ans d'une ville" |
| **Créer un compte Pro** | Formulaire de création de compte B2G/B2B |
| **Tableau de bord Pro** | Historique commandes, demandes de design, suivi |
| **Demande de design** | Formulaire de brief créatif — minimum 150 articles |
| **Galerie de réalisations** | Portfolio des commandes institutionnelles |

**Storytelling B2G clé :** La commande des 500 koinobori pour le centenaire d'une ville côtière doit être racontée comme une **installation artistique monumentale** — pas comme une commande commerciale. C'est la preuve sociale la plus puissante du site.

---

## §12 — Ton éditorial — "Le Murmure du Vent" {#12}

Koinobori House parle comme **un passeur de culture** — quelqu'un qui connaît profondément le Japon et qui partage ce savoir avec générosité, sans condescendance. Ni professeur, ni vendeur. Un ami qui vous emmène dans un endroit qu'il aime.

**En français :** Élégant, littéraire, jamais précieux. Des phrases courtes. Des silences. Des images.

**En anglais :** Poétique mais direct. L'anglais de la marque est celui des grandes maisons — Aesop, Comme des Garçons, Muji. Pas celui du e-commerce américain.

| ❌ À éviter | ✅ À privilégier |
|---|---|
| "Découvrez notre magnifique collection !" | "Chaque koinobori est une prière dans le vent." |
| "Livraison gratuite dès 50€" | "Emballé dans le silence. Livré avec soin." |
| "Produit de qualité artisanale" | "Cousu à la main. Signé BCDG." |
| "Add to cart" | "Bring it home" |
| "Best seller" | "Chosen by the wind" |
| "Bienvenue sur notre site" | *(silence — laisser l'image parler)* |

**Mots interdits :** "Magnifique", "superbe", "incroyable", "unique en son genre", "de qualité", "artisanal" (sans contexte), "découvrez", "bienvenue".

**Mots de la maison :** *Ma (間), koinobori, BCDG, vent, ciel, vol, encre, washi, sumi, estampe, cartouche, tradition, création, signature, installation, territoire, voyage.*

---

## §13 — Bilingue FR / EN {#13}

Le site est bilingue dès le lancement. Les deux langues coexistent avec la même qualité éditoriale — pas de traduction automatique visible. Implémenter avec **Polylang** sur WordPress.

Les mots japonais (koinobori, ma, hanami, etc.) restent en japonais dans les deux langues — ils font partie de l'identité de la marque. Les titres héros peuvent être en japonais avec traduction en sous-titre. Les descriptions produits sont intégralement traduites.

**Sélecteur de langue :** Discret, en haut à droite du header. Jamais un drapeau — toujours `FR / EN` en DM Sans Light, lettrespace large.

---

## §14 — Segments clients {#14}

### B2C — Le Voyageur Curieux
**Profil :** Adulte 28–55 ans, sensible à l'art et au design, attrait pour la culture japonaise. Marchés principaux : États-Unis, Allemagne, pays nordiques, France.

**Parcours :** Entrée par la beauté (hero, images) → découverte des mondes → coup de cœur produit → achat.

**Message clé :** "Un objet d'art qui vole. Une œuvre qui vit dans votre espace."

### B2G — La Collectivité qui Crée
**Profil :** Mairies, offices de tourisme, régions, musées. Budget événementiel ou décoration permanente.

**Parcours :** Entrée par l'Espace Professionnel → storytelling "500 koi" → formulaire de brief → devis personnalisé.

**Message clé :** "Transformez votre ville en œuvre d'art vivante. Minimum 150 pièces, design exclusif."

### B2B — Le Prescripteur
**Profil :** Décorateurs d'intérieur, hôtels de luxe, restaurants japonais haut de gamme, galeries d'art.

**Message clé :** "Des créations BCDG pour vos espaces. Exclusivité sur demande."

---

## §15 — Inventaire complet des images {#15}

Toutes les images sont disponibles dans l'archive `koinobori-house-toutes-images-PNG.zip`. Elles doivent être uploadées dans la médiathèque WordPress et référencées via leurs URLs WordPress — jamais en chemin relatif.

### Assets header (PRIORITÉ ABSOLUE)

> Ces trois fichiers sont fournis par Claude dans son projet WordPress. Les inclure dans le thème enfant Kadence sous `assets/images/header/`.

| Fichier | Usage | État du header |
|---|---|---|
| `blue-night-texture-II` | Fond nuit indigo étoilée — arrière-plan permanent | Repos |
| `logo-color-gold-III` | Wordmark or centré — visible au repos | Repos |
| `shoji-center-texture-II` | Texture washi chaude — révélée au survol | Survol |
| `logo-color.png` | Wordmark noir — footer, pages intérieures, emails | Hors header |

### Images de catégories (bannières et ambiances)

| Fichier | Catégorie | Usage recommandé |
|---|---|---|
| `cat-mer-hero.png` | Mer | Hero page Mer, bannière collection |
| `cat-mer-ambiance.png` | Mer | Section éditoriale, fond de carte |
| `cat-motifs-hero.png` | Motifs | Hero page Motifs |
| `cat-motifs-ambiance.png` | Motifs | Section éditoriale |
| `cat-motifs-detail.png` | Motifs | Zoom produit, détail texture |
| `cat-floral-hero.png` | Hanami | Hero page Hanami |
| `cat-floral-ambiance.png` | Hanami | Section éditoriale |
| `cat-kairo-hero.png` | Kaïro | Hero page Kaïro |
| `cat-territoires-hero.png` | Territoires | Hero page Territoires |
| `cat-territoires-ambiance.png` | Territoires | Section éditoriale |

### Images lifestyle & éditoriales

| Fichier | Usage recommandé |
|---|---|
| `apropos-hero.png` | Hero page Univers / À propos |
| `apropos-manifeste.png` | Section manifeste, fond éditorial |
| `lifestyle-interieur-1.png` | Section "Dans votre intérieur" |
| `lifestyle-interieur-2.png` | Section lifestyle, page Univers |
| `lifestyle-exterieur.png` | Section "Dans votre jardin" |
| `boutique-hero.png` | Hero page Boutique |
| `hero-alt-ciel.png` | Hero alternatif page d'accueil |
| `hero-alt-atelier.png` | Section "L'atelier BCDG" |
| `bg-boutique-fond.png` | Fond de page Boutique |
| `bg-hero-encre.png` | Fond hero alternatif sombre |
| `newsletter-hero.png` | Section newsletter |
| `collection-okusai-hero.png` | Hero collection Ōkusai |
| `collection-kairo-banner.png` | Bannière collection Kaïro |
| `packaging-paiement.png` | **Page paiement** — emballage washi KOINOBORI HOUSE |

### Images produits BCDG (réelles)

| Fichier | Produit |
|---|---|
| `prod-okusai.png` | Ōkusai sur fond marin |
| `prod-bleu-mouette.png` | Poisson Municipalités (bleu) |
| `prod-rouge-vague.png` | Poisson Municipalités (rouge) |
| `prod-sakura.png` | Koinobori sakura (photo réelle) |
| `prod-kairo-horizon.png` | KAÏRO — Koi 1 |
| `prod-kairo-vaisseau.png` | KAÏRO — Koi 2 |

### Fonds de page

| Fichier | Usage |
|---|---|
| `bg-hero-sky.png` | Hero principal accueil |
| `bg-indigo-waves.png` | Sections narratives indigo |
| `bg-washi-dark.png` | Sections sombres sur washi |
| `bg-manifeste.png` | Section manifeste |
| `bg-collections.png` | Page collections |
| `bg-newsletter.png` | Section newsletter |
| `bg-product-page.png` | Fond page produit |
| `bg-kairo-section.png` | Section Kaïro |
| `texture-washi.png` | **Texture de fond générale — à appliquer sur body** |

---

## §16 — Recommandations techniques pour WordPress/WooCommerce {#16}

### Architecture recommandée

```
WordPress + WooCommerce
        ↓
Thème enfant Kadence (ou thème enfant Genesis/Blocksy)
        ↓
Design System KH-000 (ce document)
        ↓
CSS custom + JS vanilla dans le thème enfant
        ↓
Polylang (bilingue FR/EN)
```

### Fichiers à créer dans le thème enfant

```
koinobori-house-child/
├── style.css              ← Variables CSS + styles globaux (§4, §5, §7)
├── functions.php          ← Enqueue fonts, scripts, styles
├── header.php             ← Header (développé par Claude — voir §9)
├── footer.php             ← Footer Koinobori House
├── js/
│   ├── shoji.js           ← Onboarding header + gestion hover (§9)
│   └── reveal.js          ← Intersection Observer animations (§8)
├── assets/
│   └── images/
│       ├── header/
│       │   ├── blue-night-texture-II   ← Fond nuit header (repos)
│       │   ├── logo-color-gold-III     ← Logo or header (repos)
│       │   └── shoji-center-texture-II ← Texture washi menu (survol)
│       ├── logo-color.png          ← Logo noir (footer, emails)
│       └── texture-washi.png       ← Texture fond générale
└── woocommerce/           ← Templates WooCommerce surchargés
    ├── archive-product.php
    ├── single-product.php
    └── cart/
```

### Surcharge WooCommerce — Boutons

Dans `woocommerce/` du thème enfant, surcharger les templates pour appliquer les classes `.btn-primary` et `.btn-secondary` aux boutons WooCommerce :

```php
// Dans woocommerce/single-product/add-to-cart/simple.php
// Remplacer la classe par défaut par btn-primary
<button type="submit" class="btn-primary single_add_to_cart_button">
  <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
</button>
```

### Layouts asymétriques dans Kadence

Kadence a une tendance naturelle aux grilles régulières. Pour les layouts asymétriques (comme la section "5 Mondes" ou la grille produits 2+2), utiliser les **Blocs Kadence avancés** avec CSS custom, ou sortir des blocs Kadence et écrire du HTML/CSS directement dans un bloc HTML personnalisé.

**Exemple — Section 5 Mondes en HTML/CSS pur :**

```html
<!-- Bloc HTML Kadence ou Gutenberg -->
<section class="kh-five-worlds kh-washi-texture">
  <div class="kh-section-header kh-reveal">
    <h2 class="kh-title-hero">Les 5 Mondes</h2>
    <p class="kh-subtitle-jp">五つの世界</p>
    <div class="kh-divider"></div>
  </div>
  <div class="kh-worlds-grid">
    <a href="/collections/mer" class="kh-world-card kh-world-card--mer kh-reveal kh-reveal--card">
      <span class="kh-kanji-bg">海</span>
      <div class="kh-world-card__content">
        <h3>Mer</h3>
        <p class="kh-kanji">海</p>
        <span class="kh-ui">Découvrir →</span>
      </div>
    </a>
    <!-- ... autres mondes ... -->
  </div>
</section>
```

```css
.kh-five-worlds {
  padding: 120px 0;
}
.kh-worlds-grid {
  display: grid;
  grid-template-columns: 1.3fr 1fr 1fr 1fr 1fr; /* Première carte plus large */
  gap: 1rem;
  padding: 0 2rem;
}
.kh-world-card {
  position: relative;
  aspect-ratio: 2/3;
  overflow: hidden;
  display: flex;
  align-items: flex-end;
  text-decoration: none;
  transition: transform 250ms ease-out;
}
.kh-world-card:hover { transform: translateY(-6px); }

.kh-world-card--mer         { background: var(--kh-mer-1); }
.kh-world-card--kairo       { background: var(--kh-kairo-1); }
.kh-world-card--hanami      { background: var(--kh-hanami-1); }
.kh-world-card--motifs      { background: var(--kh-motifs-1); border: 1px solid var(--kh-brume); }
.kh-world-card--territoires { background: var(--kh-territoires-1); }
```

### Plugins recommandés

| Plugin | Rôle |
|---|---|
| **Polylang** | Bilingue FR/EN |
| **WooCommerce** | E-commerce (catalogue, panier, paiement) |
| **Kadence Blocks** | Constructeur de blocs avancé |
| **Advanced Custom Fields (ACF)** | Champs personnalisés (monde, collection, storytelling) |
| **Yoast SEO** | SEO multilingue |
| **WP Rocket** | Performance (cache, lazy load) |

### Points de vigilance WordPress/Kadence

**1. Layouts asymétriques :** Kadence favorise les grilles régulières. Pour les layouts asymétriques du Design System, utiliser `grid-template-columns` avec des fractions inégales (ex. `1.3fr 1fr 1fr`), ou des blocs HTML/CSS personnalisés hors des blocs Kadence.

**2. Typographie :** Kadence a ses propres réglages de polices. S'assurer que les polices Google Fonts du Design System sont chargées **avant** les styles Kadence, et que les classes `.kh-title-hero`, `.kh-body`, etc. surchargent les styles Kadence.

**3. Couleurs :** Désactiver la palette de couleurs par défaut de Kadence et la remplacer par la palette KH-000. Dans `functions.php` :

```php
add_theme_support('editor-color-palette', [
  ['name' => 'Washi',     'slug' => 'kh-washi',     'color' => '#F7F3EC'],
  ['name' => 'Sumi',      'slug' => 'kh-sumi',      'color' => '#1A1410'],
  ['name' => 'Vermillon', 'slug' => 'kh-vermillon', 'color' => '#C8311A'],
  ['name' => 'Or',        'slug' => 'kh-or',        'color' => '#B8860B'],
  ['name' => 'Indigo',    'slug' => 'kh-indigo',    'color' => '#1B2B5E'],
]);
```

**4. Border-radius :** Kadence applique des border-radius par défaut sur les boutons et les cartes. Les neutraliser globalement :

```css
/* Dans style.css du thème enfant */
.wp-block-button__link,
.woocommerce button.button,
.woocommerce #respond input#submit,
.woocommerce a.button {
  border-radius: 0 !important;
}
```

**5. Animations :** Kadence inclut des animations basiques. Les désactiver et utiliser uniquement les animations du Design System (§8) pour éviter les conflits.

---

## §17 — Règles absolues — Ce qu'on ne fait JAMAIS {#17}

Ces règles sont non négociables. Toute déviation doit être soumise à validation avant implémentation.

1. **Jamais Inter ou Roboto** pour les titres — ces polices n'ont aucune âme pour cette marque.
2. **Jamais de fond blanc pur** `#FFFFFF` — toujours le washi chaud `#F7F3EC`.
3. **Jamais de border-radius** sur les boutons principaux — la rigueur japonaise préfère l'angle droit.
4. **Jamais de gradient violet/rose générique** — c'est le signe d'un design générique sans identité.
5. **Jamais de layout entièrement centré** — les layouts asymétriques sont la règle.
6. **Jamais de texte en surimpression sans contrôle du contraste** — vérifier systématiquement la lisibilité sur fond d'image.
7. **Jamais de koi SVG simplifiés** dans le header. Le header est défini par ses trois assets fixes : `blue-night-texture-II`, `logo-color-gold-III`, `shoji-center-texture-II`. Ne pas introduire d'autres éléments visuels dans le header sans validation.
8. **Jamais de logo recréé en police numérique** — toujours l'image PNG du logo calligraphique.
9. **Jamais de traduction automatique visible** — les deux langues ont la même qualité éditoriale.
10. **Jamais de faux avis clients** — la confiance se construit sur des preuves réelles.
11. **Jamais de shadow box générique** (`box-shadow: 0 4px 6px rgba(0,0,0,0.1)`) — utiliser les valeurs exactes du §7.
12. **Jamais de padding inférieur à 80px** entre les sections principales — le *Ma* exige de l'espace.

---

*Koinobori House — "L'art du vent" — Créations BCDG*

*KH-000 Design System — Version 1.2 — Juin 2026 — Header : assets exacts validés (blue-night-texture-II / logo-color-gold-III / shoji-center-texture-II)*

*Document produit par Manus · Directeur artistique : Koinobori House*

*Les textes de contenu (descriptions produits, pages, manifeste) sont à rédiger par le directeur de création.*

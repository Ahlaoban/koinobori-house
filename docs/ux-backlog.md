# UX Backlog — Koinobori House

But : **capturer** les observations / opportunités / arbitrages UX rencontrés pendant les Lots 2-8, **sans les exécuter**. Arbitrage en bloc au chantier UX dédié (Alain + Manus).

**Règle** : on **logue**, on ne **fige** pas. Toute entrée = ouverte, jamais « décidée ». Direction artistique = arbitrage exclusif Alain (cf gouvernance UX 2026-06-15). Manus peut alimenter / trancher ces points.

> ⚠️ **Révision 2026-07-27** — adoption de la charte v2.0 « Ma, L'Intervalle enchanté » (arbitrages C1-C6). A-1, A-2 et A-4 annulés. Les statuts ci-dessous ont été rectifiés en conséquence. Référence : [charte-graphique/README.md](charte-graphique/README.md) et [ux-architecture.md](ux-architecture.md).

Format par entrée :

```
### [ID] Titre court
**Observation** : ce qui est constaté.
**Pourquoi c'est important** : enjeu UX / désirabilité / cohérence.
**Impact** : pages/zones touchées, ampleur.
**Décision requise plus tard** : la question ouverte à arbitrer.
**Statut** : ouvert / en attente Manus / arbitré
```

---

## Entrées

### [UX-Header-001] ⭐ PRIORITAIRE — Header interactif "Shoji" (battants coulissants)
**Observation** : concept validé en principe (header dont 2 panneaux shoji s'écartent au survol pour révéler un menu iconique central, logo KOINOBORI HOUSE qui se scinde Koinobori←/House→ et reste cliquable, fond intérieur indigo non-noir). Prototype vanilla exploré (`koinobori-house-images/header-shoji.html`) — **mécanique fonctionnelle mais rendu pas encore au niveau voulu par Alain**. Réf. d'interaction = header maijinn.com (`C:\dev\maijinn-saas` `PublicLayout.tsx` : split logo + reveal menu, mots s'arrêtent juste après le menu). Direction visuelle alimentée par Manus + mockup ChatGPT (cartouche → shoji, indigo, icônes or).
**Pourquoi c'est important** : signature d'entrée du site, forte différenciation premium ; demande explicite Alain.
**Impact** : header global, identité, navigation, sélecteur langue (recoupe [[UX-001]]), portage WP/Kadence (vanilla HTML/CSS/JS, pas React).
**Décision requise plus tard** : rendu final des battants (idée « doubles battants qui se replient »), easing/vitesse, écartement, fond indigo/or exact, intégration logo+cartouche fixes, version mobile. Possible reprise via Codex/Manus.
**Statut** : ✅ **VALIDÉ (prototype provisoire) — 2026-06-29**. Mécanisme = **glissement** (battants washi coulissants), prototype `header-shoji-koino-v1.html` (vanilla HTML/CSS/JS autonome ; anim CSS, JS = déclencheurs). Spec : slide **1 s** ; scroll down → header **masqué** (revient scroll up / souris < 70px haut) ; **split logo** réel (coupe 56,6 %) ; **grille carrée continue** (`--cell = --header-h/2`) ; centre **texture II claire** (`shoji-center-texture-II.png`) en **tuile** (⚠️ 399 Ko → compresser) ; icônes **marron `#463E32`** placeholders + **tooltip instantané** ; bouton **corail** ; hauteur **responsive** `clamp(110px,13vw,185px)`. **Reste** : vrais rubriques → icônes + labels, compression texture, portage thème (Lot 7).

**Mise à jour 2026-07-27 (C5)** : le header Shoji V2 est **verrouillé définitivement** — il est reconnu comme la réalisation du principe Fusuma de la charte v2.0 §6. Il n'est plus « revisitable ». Enrichissements exigés au portage Lot 7, sans rouvrir le design : (a) navigation au **clavier**, (b) forme **simplifiée d'emblée sur mobile** et sous `prefers-reduced-motion`, (c) version sans animation pleinement fonctionnelle, la nav jamais durablement masquée, (d) rubriques = **nav v2.0 à plat, 7 entrées** (le mega-panel A-1 est annulé), (e) bouton « Mon compte » corail `#E05A5A` → **vermillon `#C8311A`**.

### [UX-001] Emplacement + visibilité du sélecteur de langue
**Observation** : Polylang fournit un sélecteur fonctionnel ; emplacement par défaut non arbitré (header ? footer ? les deux ?).
**Pourquoi c'est important** : 1ère décision du visiteur (FR/EN), affecte conversion + perception premium ; CLAUDE.md prévoit header + footer.
**Impact** : header, footer, toutes pages.
**Décision requise plus tard** : forme (drapeaux / texte / FR-EN), position, comportement mobile.
**Statut** : ✅ **arbitré 2026-07-24** — FR/EN texte sans drapeau, dans la rangée révélée du header V2 (desktop) + visible dès l'overlay mobile + footer. Cf [ux-architecture.md](ux-architecture.md).

### [UX-002] Structure de la homepage
**Observation** : construite en blocs natifs empilés (neutre) au Lot 3.
**Pourquoi c'est important** : porte la promesse « j'ai découvert quelque chose d'unique » (objectif design Manus).
**Impact** : homepage FR + EN.
**Décision requise plus tard** : narration, hero, ordre des sections, immersion vs sobriété.
**Statut** : ✅ **ré-arbitré 2026-07-27** — remplacé par les **11 mouvements** de la charte v2.0 §4 : Header Fusuma → Hero → les cinq mondes → Créations BCDG → L'Atelier → Lifestyle & Koi → Arts de vivre → Manifeste → Professionnels → Newsletter → Footer ligne d'horizon. Supersède l'énuméré S0-S9 du 2026-07-24 ; le principe « immersion d'abord » d'A-8 reste vrai, les 11 mouvements l'étant eux-mêmes. Le bandeau confiance n'a plus de mouvement dédié → point ouvert O-1. Cf [ux-architecture.md](ux-architecture.md) §4.

### [UX-003] Présentation des collections (5)
**Observation** : grille produits Kadence par défaut au Lot 2.
**Pourquoi c'est important** : navigation « collection-first » / gallery-like envisagée (brief Manus) vs grille e-commerce générique.
**Impact** : pages collections, navigation.
**Décision requise plus tard** : hiérarchie des 5 collections (Mer · Motifs · Hanami · Kaïro · Territoires), traitement éditorial vs catalogue.
**Statut** : ✅ **ré-arbitré 2026-07-27** — **le mega-panel et le label « Collections » sont supprimés** (A-1 et A-2 annulés par C4). Nav v2.0 à plat, 7 entrées. Les 5 collections WooCommerce sont inchangées et présentées comme « **les cinq mondes** », atteintes par la homepage (mouvement 3) et par la Boutique. Pages Mondes éditoriales à palette propre conservées. Territoires = Monde-collection seul (A-3, toujours en vigueur). Cf [ux-architecture.md](ux-architecture.md) §2-3.

### [UX-004] Mise en avant de Kaïro + récit « The Nameless Ship »
**Observation** : Kaïro = collection + produits (Lot 2), sans dispositif narratif.
**Pourquoi c'est important** : héros original BCDG, univers extensible ; ne doit PAS virer boutique de merch manga.
**Impact** : collection Kaïro, homepage, storytelling.
**Décision requise plus tard** : dispositif narratif, place dans la hiérarchie, équilibre avec les autres collections.
**Statut** : 🔶 **partiellement ré-arbitré 2026-07-27** — **le slot menu Kaïro est supprimé** (A-4 annulé par C4). Le récit « The Nameless Ship » survit **hors navigation**, porté par la page Monde Kaïro. Asymétrie assumée conservée, sans dérive merch. **Reste ouvert** : sa place sur la homepage, l'ancienne section S3 ayant disparu avec les 11 mouvements → point ouvert O-9, arbitrage Alain. Cf [ux-architecture.md](ux-architecture.md) §8.

### [UX-005] Place du storytelling BCDG
**Observation** : texte signature BCDG présent en bloc 2 de fiche (doctrine 2 blocs) ; pas de dispositif marque dédié.
**Pourquoi c'est important** : différenciation « maison de marque » vs Etsy/Amazon ; distinction logo Koinobori House (site) ≠ signature BCDG (produits).
**Impact** : fiches produits, à-propos, homepage.
**Décision requise plus tard** : ampleur et emplacement du storytelling sans confondre les deux identités.
**Statut** : ✅ **arbitré 2026-07-24, support renommé 2026-07-27** — teaser court sur la homepage (mouvement 8 « Le Manifeste » + mention signature), récit complet porté par la page « **L'Atelier** » (C2), qui remplace l'ancienne page « Univers ». Cf [ux-architecture.md](ux-architecture.md).

### [UX-006] Structure visuelle des pages produits
**Observation** : 2 blocs doctrine (design/usage → signature BCDG) + galerie standard.
**Pourquoi c'est important** : perception premium / poétique vs fiche WC générique. (Plus de bloc atelier/transparence : supprimé doctrine 2026-06-18.)
**Impact** : ~17 fiches produits.
**Décision requise plus tard** : mise en page, typographie, immersion, traitement images.
**Statut** : ✅ **arbitré 2026-07-27** — charte v2.0 §10.2 : 2 colonnes desktop (galerie généreuse à gauche, infos à droite), puis **4 mouvements éditoriaux** — L'histoire de la pièce · **Détails et matières** (C1, jamais « fabrication ») · Dimensions et installation · Livraison et entretien — puis 2-3 pièces du même monde. Bouton panier angles droits, fond vermillon, DM Sans. La règle des 2 blocs de `long_desc` reste en vigueur pour le contenu rédigé : les deux coexistent. Cf [ux-architecture.md](ux-architecture.md) §7.

### [UX-007] Niveau de richesse visuelle / images d'ambiance
**Observation** : **aucune** image d'ambiance / arrière-plan posée (choix réversibilité).
**Pourquoi c'est important** : curseur minimalisme ↔ richesse = décision esthétique majeure, difficile à inverser si baked-in.
**Impact** : tout le site.
**Décision requise plus tard** : usage d'arrière-plans, ambiances, densité visuelle.
**Statut** : ✅ **arbitré 2026-07-24** — sobre + accents : fond washi dominant, images d'ambiance limitées au hero homepage + heros des pages Mondes, signatures discrètes (kanji fond opacity .04, divider pinceau). Cf [ux-architecture.md](ux-architecture.md).

### [UX-008] Header & footer définitifs
**Observation** : header (logo + cartouche) et footer (liens essentiels + légales) en structure minimale.
**Pourquoi c'est important** : cadre permanent de toutes les pages.
**Impact** : global.
**Décision requise plus tard** : layout, contenu éditorial, sticky, colonnes footer.
**Statut** : 🔶 **partiellement arbitré, révisé 2026-07-27** — header : clos (V2 verrouillé, confirmé par C5 comme réalisation du principe Fusuma v2.0 §6 ; rubriques = nav v2.0 à plat, A-1 annulé). Footer : **structure colonnes abandonnée**, remplacée par le footer « ligne d'horizon » v2.0 §11 (fond washi, trait fin, 72-96 px, 3 zones sur une ligne desktop, newsletter séparée, ❌ aucun bloc sombre ni méga-menu). **Bloqué par les points ouverts O-1 et O-2** : où logent Livraison / Retours / Livraison USA, et les 2 liens directs Entreprises / Collectivités d'A-6, dans un footer à 4 liens centraux. Cf [ux-architecture.md](ux-architecture.md) §8.

### [UX-009] Palette & typographie finales
**Observation** : libérées par Alain (sauf logo + cartouche 鯉のぼり fixes) → Manus explore librement ; défauts neutres en attendant.
**Pourquoi c'est important** : identité visuelle ; doivent coexister avec le tampon rouge fixe.
**Impact** : design tokens, tout le thème (Lot 6).
**Décision requise plus tard** : tokens définitifs (hex, font families/weights, spacing) issus de Manus.
**Statut** : ✅ **ré-arbitré 2026-07-27** — tokens définitifs = **charte v2.0 §5.1 et §5.2**. Palette : washi `#F8F4EE` · sumi `#1A1410` · vermillon `#C8311A` · or `#B8860B` · indigo `#2B3A6B` · blanc cartes `#FFFDFC`. Typos : Cormorant Garamond (titres) · Lora (corps) · DM Sans (interface) · Noto Serif JP (kanji). Annule la palette et les typos de KH-000 v1.2, ainsi que l'or `#C9A96E`. Le principe « vermillon = action, or = détails précieux et citations » est inchangé depuis l'arbitrage A du 2026-06-29 : l'or reste interdit sur boutons, panier, liens et aplats massifs. Point ouvert O-3 : 4 familles typographiques = risque perf à instrumenter avant G4. Cf [charte-graphique/README.md](charte-graphique/README.md).

### [UX-010] Parcours utilisateur global
**Observation** : parcours WooCommerce standard (browse → cart → checkout) fonctionnel.
**Pourquoi c'est important** : le brief Manus invite à challenger les conventions e-commerce.
**Impact** : navigation, tunnel, B2C vs captation B2B/B2G.
**Décision requise plus tard** : réagencements proposés par Manus, dans la limite buildabilité WP/WC/Kadence.
**Statut** : ✅ **arbitré 2026-07-24, chemin d'entrée révisé 2026-07-27** — parcours principal découverte (home → **les cinq mondes, mouvement 3** → page Monde → fiche → panier) + parcours alternatif transactionnel (home → Boutique → filtres → fiche) + captation B2B/B2G hors tunnel via la page d'aiguillage Professionnels (A-6). Le mega-panel n'est plus un point d'entrée (A-1 annulé). Cf [ux-architecture.md](ux-architecture.md) §6.

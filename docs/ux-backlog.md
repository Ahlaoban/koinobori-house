# UX Backlog — Koinobori House

But : **capturer** les observations / opportunités / arbitrages UX rencontrés pendant les Lots 2-8, **sans les exécuter**. Arbitrage en bloc au chantier UX dédié (Alain + Manus).

**Règle** : on **logue**, on ne **fige** pas. Toute entrée = ouverte, jamais « décidée ». Direction artistique = arbitrage exclusif Alain (cf gouvernance UX 2026-06-15). Manus peut alimenter / trancher ces points.

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

### [UX-001] Emplacement + visibilité du sélecteur de langue
**Observation** : Polylang fournit un sélecteur fonctionnel ; emplacement par défaut non arbitré (header ? footer ? les deux ?).
**Pourquoi c'est important** : 1ère décision du visiteur (FR/EN), affecte conversion + perception premium ; CLAUDE.md prévoit header + footer.
**Impact** : header, footer, toutes pages.
**Décision requise plus tard** : forme (drapeaux / texte / FR-EN), position, comportement mobile.
**Statut** : en attente Manus

### [UX-002] Structure de la homepage
**Observation** : construite en blocs natifs empilés (neutre) au Lot 3.
**Pourquoi c'est important** : porte la promesse « j'ai découvert quelque chose d'unique » (objectif design Manus).
**Impact** : homepage FR + EN.
**Décision requise plus tard** : narration, hero, ordre des sections, immersion vs sobriété.
**Statut** : en attente Manus

### [UX-003] Présentation des collections (6)
**Observation** : grille produits Kadence par défaut au Lot 2.
**Pourquoi c'est important** : navigation « collection-first » / gallery-like envisagée (brief Manus) vs grille e-commerce générique.
**Impact** : pages collections, navigation.
**Décision requise plus tard** : hiérarchie des 6 collections, traitement éditorial vs catalogue.
**Statut** : en attente Manus

### [UX-004] Mise en avant de Kaïro + récit « The Nameless Ship »
**Observation** : Kaïro = collection + produits (Lot 2), sans dispositif narratif.
**Pourquoi c'est important** : héros original BCDG, univers extensible ; ne doit PAS virer boutique de merch manga.
**Impact** : collection Kaïro, homepage, storytelling.
**Décision requise plus tard** : dispositif narratif, place dans la hiérarchie, équilibre avec les autres collections.
**Statut** : en attente Manus

### [UX-005] Place du storytelling BCDG
**Observation** : texte signature BCDG présent en bas de fiche (doctrine 3 blocs) ; pas de dispositif marque dédié.
**Pourquoi c'est important** : différenciation « maison de marque » vs Etsy/Amazon ; distinction logo Koinobori House (site) ≠ signature BCDG (produits).
**Impact** : fiches produits, à-propos, homepage.
**Décision requise plus tard** : ampleur et emplacement du storytelling sans confondre les deux identités.
**Statut** : en attente Manus

### [UX-006] Structure visuelle des pages produits
**Observation** : 3 blocs doctrine (design/usage → BCDG → transparence) + galerie standard.
**Pourquoi c'est important** : perception premium / poétique vs fiche WC générique ; wording atelier en bas (doctrine).
**Impact** : ~50 fiches produits.
**Décision requise plus tard** : mise en page, typographie, immersion, traitement images.
**Statut** : en attente Manus

### [UX-007] Niveau de richesse visuelle / images d'ambiance
**Observation** : **aucune** image d'ambiance / arrière-plan posée (choix réversibilité).
**Pourquoi c'est important** : curseur minimalisme ↔ richesse = décision esthétique majeure, difficile à inverser si baked-in.
**Impact** : tout le site.
**Décision requise plus tard** : usage d'arrière-plans, ambiances, densité visuelle.
**Statut** : en attente Manus

### [UX-008] Header & footer définitifs
**Observation** : header (logo + cartouche) et footer (liens essentiels + légales) en structure minimale.
**Pourquoi c'est important** : cadre permanent de toutes les pages.
**Impact** : global.
**Décision requise plus tard** : layout, contenu éditorial, sticky, colonnes footer.
**Statut** : en attente Manus

### [UX-009] Palette & typographie finales
**Observation** : libérées par Alain (sauf logo + cartouche 鯉のぼり fixes) → Manus explore librement ; défauts neutres en attendant.
**Pourquoi c'est important** : identité visuelle ; doivent coexister avec le tampon rouge fixe.
**Impact** : design tokens, tout le thème (Lot 6).
**Décision requise plus tard** : tokens définitifs (hex, font families/weights, spacing) issus de Manus.
**Statut** : en attente Manus

### [UX-010] Parcours utilisateur global
**Observation** : parcours WooCommerce standard (browse → cart → checkout) fonctionnel.
**Pourquoi c'est important** : le brief Manus invite à challenger les conventions e-commerce.
**Impact** : navigation, tunnel, B2C vs captation B2B/B2G.
**Décision requise plus tard** : réagencements proposés par Manus, dans la limite buildabilité WP/WC/Kadence.
**Statut** : en attente Manus

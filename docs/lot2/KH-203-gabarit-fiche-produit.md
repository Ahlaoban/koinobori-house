# KH-203 — Gabarit fiche produit FR/EN (Lot 2)

- **Date** : 2026-06-15
- **Statut** : 🟢 **gabarit VALIDÉ (Alain, 2026-06-15)** — ajustements intégrés (inspiration japonaise, retrait « chambre d'enfant », rouge profond, pilote 75 cm). Aucune implémentation / aucun produit créé.
- **Pilote** : Sakura Rouge (Hanami). Gabarit **généralisable** aux ~50 produits.
- **Contraintes** : contenu seulement · pas de visuel · pas de rendu final · compatible évolutions UX Manus · doctrine stricte.

---

## A. Structure du gabarit (champs, généralisable)

| Champ | Rôle | Règle doctrine |
|---|---|---|
| **Nom produit** | titre public | `<Design> Koinobori — by BCDG` (signature BCDG dans le nom) |
| **SKU** | identifiant interne | convention `KH-<COLL>-<NNN>-<TAILLE>` (voir §D) |
| **Type** | WooCommerce | **variable + variation(s)** (attribut Taille) — KH-010 |
| **Prix** | EUR only | par variation, pas de multi-devise |
| **Stock** | par produit/variation | **jamais** de volume global (doctrine stock) |
| **Description courte** | commerciale, désirable | **AUCUNE** mention atelier partenaire / partner workshop |
| **Description longue** | 3 blocs Gutenberg séparés | bloc1 design/usage · bloc2 signature BCDG · bloc3 transparence (bas) |
| **Collection (catégorie)** | rangement | 1 des 6 collections, bilingue |
| **Attribut Taille** | variations | 50/75/100 (extensible) |
| **Métadonnées SEO** | SEOPress | meta title, meta desc, slug, canonical auto, hreflang auto |
| **Visuels** | **emplacements réservés** | image principale + galerie = **placeholders** (contenu à venir, hors scope) |

---

## B. Pilote rempli — **Sakura Rouge** (FR)

**Nom** : Sakura Rouge Koinobori — by BCDG
**SKU** : `KH-HAN-001-075` · **Type** : variable · **Taille** : 75 cm *(pilote : 1 seule variation)* · **Prix** : 29 € · **Stock** : par variation (ex. 50) · **Collection** : Hanami

**Description courte (FR)** — *commerciale, sans atelier* :
> Une carpe volante semée de pétales de sakura, rouge profond sur fond clair. Pièce décorative inspirée du hanami, à suspendre en intérieur comme au jardin pour une touche poétique d'inspiration japonaise.

**Description longue (FR) — 3 blocs** :

*Bloc 1 — Design / usage / atmosphère*
> Le Sakura Rouge décline le motif des cerisiers en fleur sur la silhouette traditionnelle du koinobori. Ses pétales graphiques et son rouge profond captent la lumière et ondulent au moindre souffle d'air. Suspendu sur une terrasse, dans une entrée, un jardin, un espace de vie ou lors d'un événement, il apporte une atmosphère de hanami — ce moment japonais de contemplation des fleurs — tout au long de l'année.

*Bloc 2 — Signature BCDG + série*
> Design original BCDG, signé et édité en petite série.

*Bloc 3 — Transparence production (bas de fiche)*
> Produit par un atelier partenaire selon les fichiers et spécifications BCDG.

**Métadonnées SEO (FR)** :
- Meta title : `Sakura Rouge Koinobori — by BCDG | Koinobori House`
- Meta description : `Koinobori décoratif Sakura Rouge, design original signé BCDG, édité en petite série. 75 cm, à suspendre intérieur ou extérieur.`
- Slug : `/fr/produit/sakura-rouge/`

**Visuels (emplacements réservés)** : image principale `[À VENIR]` · galerie `[À VENIR ×N]` — *aucune production visuelle à ce stade.*

---

## C. Pilote rempli — **Sakura Rouge** (EN)

> Adaptation **naturelle**, pas de traduction mot-à-mot. Nom de l'œuvre « Sakura Rouge » **conservé en FR et EN** (nom propre de la pièce) — validé Alain.

**Name** : Sakura Rouge Koinobori — by BCDG
**SKU** : `KH-HAN-001-075` (même SKU — voir §risques) · **Size** : 75 cm · **Price** : 29 € · **Collection** : Hanami

**Short description (EN)** — *commercial, no workshop mention* :
> A flying carp scattered with sakura petals, deep red on a soft ground. A decorative piece inspired by hanami, to hang indoors or in the garden for a Japanese-inspired poetic touch.

**Long description (EN) — 3 blocks** :

*Block 1 — Design / use / atmosphere*
> Sakura Rouge brings the cherry-blossom motif to the traditional koinobori silhouette. Its graphic petals and deep red catch the light and sway with the faintest breeze. On a terrace, in an entrance, a garden, a living space or at an event, it carries the spirit of hanami — the Japanese moment of contemplating blossoms — all year round.

*Block 2 — BCDG signature + series*
> An original BCDG design, signed and released in a small series.

*Block 3 — Production transparency (bottom)*
> Produced by a partner workshop according to BCDG files and specifications.

**SEO metadata (EN)** :
- Meta title : `Sakura Rouge Koinobori — by BCDG | Koinobori House`
- Meta description : `Decorative Sakura Rouge koinobori, an original BCDG design released in a small series. 75 cm, to hang indoors or outdoors.`
- Slug : `/en/produit/sakura-rouge/` (base `produit` conservée — limitation Pro skip acceptée)

---

## D. Convention SKU (généralisable)
`KH-<COLL>-<NNN>-<TAILLE>`
- `KH` = Koinobori House
- `<COLL>` : KAI (Kaïro) · MER (La Mer) · OKU (OKUSAI) · BRE (Bretagne) · EDS (Éditions spéciales) · HAN (Hanami)
- `<NNN>` : numéro modèle dans la collection (001…)
- `<TAILLE>` : 050 / 075 / 100
- Ex. `KH-HAN-001-075` · `KH-EDS-001-100` (Stars & Stripes) · `KH-KAI-001-075`

---

## E. Validation doctrine (checklist)

| Décision | Respectée ? | Comment |
|---|---|---|
| **BCDG** signature | ✅ | `— by BCDG` dans le nom + bloc 2 |
| **Koinobori House** ≠ BCDG | ✅ | marque = site ; BCDG = signature produit (pas confondus) |
| **Atelier partenaire** (placement) | ✅ | uniquement bloc 3 (bas), **absent** du short_desc |
| **Wording transparence FR/EN** | ✅ | formulations privilégiées CLAUDE.md exactes |
| **Jamais Chine / origine / fabrication** | ✅ | aucune mention |
| **Jamais stock global** | ✅ | stock par variation uniquement |
| **Jamais « fabriqué en France »** | ✅ | aucune mention |
| **KH-010 (stock/variations sync)** | ✅ | variable + Taille, stock/variation, sync Polylang FR↔EN |
| **KH-017 (légal)** | ✅ | aucune allégation produit contraire ; livraison/retours = checkout (hors fiche) |
| **EN naturel** | ✅ | adaptation, pas mot-à-mot |

# Catalog Koinobori House — master.csv

Source de vérité produits MVP, hors WordPress. Format CSV UTF-8, séparateur virgule, séparateur intra-cellule `|` pour `gallery_paths`. Versionné repo.

## Fichiers

- `master.csv` — données réelles produits MVP. Source de vérité.
- `examples.csv` — exemples de référence pédagogique (variable parent, variation, simple). **Ne jamais importer. Ne jamais reprendre les lignes telles quelles dans master.csv.** Les lignes utilisent un préfixe SKU placeholder `KH-DEMO-*` qui n'appartient à aucune collection réelle.
- `README.md` — ce document.

### Statut documentaire `examples.csv`

- `examples.csv` est un fichier **documentaire non importable**.
- Il n'est **pas soumis à la validation stricte** master.csv (gating `export_to_wc`, contraintes champs obligatoires, doctrine Kaïro, etc.).
- Le préfixe SKU `KH-DEMO-*` et la valeur `collection=demo` sont **autorisés uniquement dans `examples.csv`**.
- Ils sont **interdits dans `master.csv`** et dans tout export WooCommerce (script de génération CSV WC doit rejeter toute ligne master.csv où `sku` commence par `KH-DEMO-` ou `collection=demo`).

## Convention SKU

Pattern fixe : `KH-{COL}-{SEQ}-{SIZE}`

- `KH` — préfixe marque, immuable
- `COL` — code collection 3 lettres :
  - `KAI` Kaïro
  - `MER` La Mer
  - `OKU` OKUSAI
  - `BRE` Bretagne
  - `EDS` Éditions spéciales
- `SEQ` — 3 chiffres, séquence interne collection, jamais réattribué
- `SIZE` — 3 chiffres taille cm : `050`, `075`, `100`

Variantes par `product_type` :

| product_type | SKU forme | exemple |
|--------------|-----------|---------|
| `variable` (parent) | 3 segments sans SIZE | `KH-DEMO-001` (doc placeholder) |
| `variation` | 4 segments | `KH-DEMO-001-075` (doc placeholder) |
| `simple` | 4 segments | `KH-EDS-001-100` (réel Stars & Stripes) |

`DEMO` = préfixe collection réservé documentation uniquement, jamais utilisé en master.csv. Les collections réelles utilisent `KAI`, `MER`, `OKU`, `BRE`, `EDS`.

Règles dures :
- SEQ jamais réutilisé même produit retiré
- SKU jamais modifié après publication WP
- Pas de nom modèle dans SKU
- Pas d'année / millésime
- Sous-catégories EDS (Americana, Nations, Saisons, Événements, Collaborations, Séries limitées) portées par taxonomie WP, jamais dans SKU

## Règles `product_type`

Valeurs autorisées : `variable` | `variation` | `simple`

| Colonne | variable (parent) | variation | simple |
|---------|-------------------|-----------|--------|
| `parent_sku` | vide | SKU parent | vide |
| `sku` | SKU parent | SKU variation | SKU simple |
| `size_cm` | vide | rempli | rempli |
| `price_eur` | vide | rempli | rempli |
| `stock_qty` | vide | rempli | rempli |
| `attr_taille_values` | rempli `"50 cm, 75 cm, 100 cm"` | vide | vide |
| `attr_taille_default` | vide ou taille valide subset de `attr_taille_values` | vide | vide |

Validation pré-export :
- Si `variable` : trois champs taille/prix/stock vides obligatoires
- Si `variation` ou `simple` : trois champs taille/prix/stock remplis obligatoires
- Si `variation` : `parent_sku` doit pointer un `variable` existant
- `attr_taille_default` parent : doit appartenir à `attr_taille_values`

## Règle simple vs variable

- **Variable par défaut** si modèle existe ou pourra exister en plus d'une taille (lignes Mer / OKUSAI / Bretagne standard, modèles génériques multi-tailles)
- **Simple** dans deux cas :
  - série fermée explicite, taille unique (cas Stars & Stripes 100 cm × 50 pièces)
  - **collection Kaïro** : chaque épisode = produit simple à taille unique propre, jamais parent variable cross-épisodes (cf section *Collection Kaïro — doctrine narrative*)
- En cas de doute (hors Kaïro) : variable mono-variation. Migration plus tard sans coût schéma.

## Règle `export_to_wc`

Valeurs : `yes` | `no`

- `yes` : ligne incluse dans génération CSV WooCommerce. **Requiert tous les champs obligatoires export remplis** (gating strict).
- `no` : ligne ignorée (drafts incomplets, modèles retirés, tests). **Statut par défaut tant que prix / stock / image principale ne sont pas confirmés**, même si `status=draft`.

### Champs obligatoires pour `export_to_wc=yes`

**Tous types** : `product_type`, `sku`, `model_name_fr`, `short_desc_fr`, `long_desc_fr`, `main_image_path`, `status`.

**Type `simple`** ajoute : `size_cm`, `price_eur`, `stock_qty`.

**Type `variation`** ajoute : `parent_sku` (pointant un parent `variable` existant et lui-même `export_to_wc=yes`), `size_cm`, `price_eur`, `stock_qty`.

**Type `variable` (parent)** ajoute : `attr_taille_values`. `attr_taille_default` reste optionnel mais doit appartenir à `attr_taille_values` si rempli.

**`main_image_path` est bloquant** : aucun produit exportable sans image principale.

**`gallery_paths` est recommandé mais non bloquant** : un produit peut passer en `export_to_wc=yes` avec **une seule image principale** si `price_eur`, `stock_qty` et `main_image_path` sont validés. Galerie ≥ 2 visuels reste fortement conseillée pour fiches premium / éditions spéciales.

Si l'un des champs **obligatoires** ci-dessus manque → `export_to_wc=no` obligatoire.

### Cas particulier — brouillons incomplets

Les lignes en cours de finalisation (prix non validé, images non livrées, etc.) **restent autorisées avec `export_to_wc=no`** et `status=draft`. Elles documentent la roadmap catalogue sans risquer d'apparaître côté boutique. Bascule vers `yes` uniquement quand tous champs obligatoires sont remplis.

Filtrage appliqué par script export master → CSV WC à venir.

## Convention chemins images

**Pattern repo-relative** : `images/{collection}/{seq3}-{slug}/{filename}`

- `collection` : code 5 collections (`kairo`, `mer`, `okusai`, `bretagne`, `eds`) — matches `collection` enum master.csv
- `seq3` : SEQ du SKU à 3 chiffres (`001`, `002`, …) — synchronise avec SKU
- `slug` : nom court kebab-case ASCII, sans accents, sans apostrophes
- `filename` :
  - `main.jpg` — image principale (toujours ce nom)
  - `g1.jpg`, `g2.jpg`, … — galerie séquentielle
- Format : JPG (ShortPixel/Imagify gère conversion WebP côté WP)
- Résolution recommandée : ≥ 2000 px côté long (zoom WC + retina)
- Racine physique : `catalog/images/` dans le repo

**Stockage master.csv** : chemins repo-relative uniquement (`images/eds/001-stars-stripes/main.jpg`). Pas d'URL absolue dans master.csv (portabilité staging/prod).

**Transformation export WC** : script génération CSV WC transforme chemin repo-relative → URL absolue WP media (`https://koinoborihouse.com/wp-content/uploads/...`) après upload média library.

### Scope strict — images e-commerce uniquement

La convention `catalog/images/...` concerne **uniquement** les visuels destinés au site WooCommerce :

- image principale fiche produit (`main.jpg`)
- galerie produit (`g1.jpg`, `g2.jpg`, …)
- visuels optimisés web (JPG ≥ 2000 px)

**Elle ne concerne pas** :
- les fichiers sources impression / fournisseur (haute résolution, formats vectoriels, CMJN, fichiers calques, BAT, gabarits techniques)
- les visuels internes BCDG (planches design, recherches, variantes non publiées)
- les assets marketing hors site (réseaux sociaux, print, lookbooks)

Ces fichiers doivent être **stockés séparément** (hors `catalog/images/`, hors repo ou dans un dossier dédié non versionné si poids excessif) et **ne doivent jamais être référencés dans master.csv** comme images WooCommerce.

Aucun chemin pointant vers fichiers sources impression ne doit apparaître dans `main_image_path` ou `gallery_paths`.

## Colonnes privées — JAMAIS exportées vers WooCommerce

Le script de génération CSV WC utilise une **liste blanche** stricte. Toute colonne non listée ci-dessous reste locale :

**Exportées** :
- `product_type` → `Type`
- `sku` → `SKU`
- `parent_sku` → `Parent`
- `model_name_fr` → `Name` (langue par défaut FR)
- `short_desc_fr` → `Short description`
- `long_desc_fr` → `Description`
- `price_eur` → `Regular price`
- `stock_qty` → `Stock`
- `attr_taille_values` → `Attribute 1 value(s)` (parent only)
- `attr_taille_default` → `Attribute 1 default` (parent only)
- `size_cm` → `Attribute 1 value(s)` formaté `"{size_cm} cm"` (variation/simple)
- `main_image_path` + `gallery_paths` → `Images` (concat)
- `status` → `Published` (1/0)
- constantes injectées : `Attribute 1 name=Taille`, `Attribute 1 visible=1`, `Attribute 1 global=1`

**Jamais exportées (privées)** :
- `internal_id`
- `wp_post_id` (rempli après import, mapping retour local)
- `supplier_internal_name` — contient noms internes fournisseur (ex `US FLAG`). Fuite publique = bris doctrine éditoriale.
- `bcdg_notes`
- `collection` — porté par taxonomie WC créée manuellement
- `eds_subcategory` — idem
- `model_name_en`, `short_desc_en`, `long_desc_en` — réservés étape Polylang (voir workflow)

**Audit pré-export obligatoire** : grep automatique sur CSV WC généré pour valeur `supplier_internal_name` (notamment `US FLAG`) → fail si présent.

## Workflow import FR puis EN Polylang

Le CSV WooCommerce natif n'importe **pas** automatiquement les traductions EN. Process en 2 étapes obligatoire.

### Pré-requis bloquants avant toute étape

1. Attribut global `pa_taille` créé dans WP (Products → Attributes)
2. Termes FR créés : `50 cm`, `75 cm`, `100 cm`
3. Polylang langue EN activée
4. Chaque terme Taille traduit en EN via icône Polylang (libellés identiques `50 cm` mais traduction enregistrée — sinon désynchronisation Polylang)
5. Taxonomies collections + sous-catégories EDS créées et traduites

Citation Polylang : *"You must translate the attributes and their terms before using them in your products. Otherwise you will have some synchronization issues between the product translations."*

### Étape A — Import langue par défaut FR

1. Générer CSV WC depuis master.csv (script à venir, liste blanche colonnes)
2. Filtrer lignes `export_to_wc=no`
3. Audit grep `supplier_internal_name` valeurs → fail si fuite
4. Import WP via Products → Import (WC Product CSV Importer natif)
5. Produits créés en FR uniquement

### Étape B — Traductions EN via Polylang

Pour chaque produit FR créé :
1. WP Products → Edit produit FR → drapeau Polylang EN → Create translation
2. Copier-coller `model_name_en`, `short_desc_en`, `long_desc_en` depuis master.csv
3. Polylang duplique automatiquement variations + synchronise SKU + stock + prix
4. Pas besoin de re-saisir variations EN : héritage automatique du parent traduit

**Coût temps estimé** : ~5-10 min par produit pour traduction manuelle interface. ~50 produits MVP ≈ 4-8h supplémentaires hors import CSV initial.

**Méthode 2 semi-auto** (WP-CLI ou plugin import dédié) à évaluer à l'usage, hors gel KH-010.

## Doctrine éditoriale rappel

- ❌ Jamais Chine publique
- ❌ Jamais "atelier chinois"
- ✅ "Atelier partenaire" ou "atelier confidentiel"
- ❌ Jamais "fabriqué en France"
- ❌ Jamais "US Flag" public — utiliser `supplier_internal_name` colonne privée uniquement
- ❌ Jamais référence volume global stock — stock par variation seul

## Wording fiche produit — règle de placement

**Description courte** (`short_desc_fr` / `short_desc_en`) : commerciale, claire, désirable. **Jamais** mention atelier partenaire / partner workshop.

**Description longue** (`long_desc_fr` / `long_desc_en`) : structure 3 blocs ordonnés (séparés par double saut de ligne dans la cellule CSV → paragraphes Gutenberg distincts) :

1. **Bloc principal** — design, usage, contexte d'installation, atmosphère
2. **Bloc signature / édition** :
   - Petite série standard : `Design original BCDG, signé et édité en petite série.`
   - Édition spéciale série test : `Design original BCDG, signé et édité en série test de N pièces.`
   - Édition spéciale série limitée numérotée : `Design original BCDG, signé et édité en série limitée numérotée de N pièces.`
3. **Bloc fabrication** (bas de fiche) :
   - FR : `Produit par un atelier partenaire selon les fichiers et spécifications BCDG.`
   - EN : `Produced by a partner workshop according to BCDG files and specifications.`

Bloc 3 idéalement rendu visuellement séparé côté thème WP (sous-titre "Détails / Fabrication" ou bloc accordéon). Pas un argument commercial : transparence factuelle uniquement.

Audit pré-publish (KH-208-213 + KH-707) : grep `short_desc_fr` / `short_desc_en` pour "atelier partenaire" ou "partner workshop" → fail si présent.

## Collection Kaïro — doctrine narrative

**Kaïro** n'est pas un modèle unique ni un simple style graphique. **Kaïro est un personnage original BCDG**, créé par Alain / BCDG, susceptible de vivre plusieurs aventures ou cycles narratifs successifs.

**Première aventure** : *Kaïro des Vents Rouges — Le Navire Sans Nom*. Cycle narratif en **15 koinobori**, déployé sur 4 à 5 ans. **Chaque koinobori = un épisode narratif = un produit distinct**, avec son propre SKU, sa propre fiche, sa propre taille.

**MVP test marché** : **4 premiers épisodes** de *Kaïro des Vents Rouges — Le Navire Sans Nom*. Autres aventures de Kaïro pourront suivre ensuite, avec SEQ continu dans la collection `KAI`.

### Règles dures Kaïro

- ❌ **Jamais** créer un produit générique « Kaïro » sans épisode rattaché.
- ❌ **Jamais** créer de parent variable Kaïro cross-épisodes. Chaque épisode = `simple`, taille unique propre.
- ❌ **Jamais** réutiliser le placeholder *« Koi Kaïro Aurore »* (présent uniquement comme exemple historique dans `examples.csv`, désormais renommé `KH-DEMO-001` pour éviter toute confusion).
- ✅ SKU épisode Kaïro = `KH-KAI-{SEQ}-{SIZE}` où SEQ est unique cross-aventures (épisode 1 première aventure = `001`, épisode 15 = `015`, épisode 1 deuxième aventure = `016`, etc.).
- ✅ `bcdg_notes` doit documenter : numéro épisode `N/M`, titre aventure, couleur fond + hex, émotion, mentions impression spécifiques.
- ✅ Description longue épisode = règle 3 blocs standard, §1 mentionne explicitement `Épisode N/M du cycle [titre aventure]`.

### Mapping aventure → SEQ (en cours)

| SEQ | Aventure | Épisode | Titre FR | Taille |
|-----|----------|---------|----------|--------|
| 001 | Le Navire Sans Nom | 1/15 | La Promesse de la Mer | 75 cm |
| 002 | Le Navire Sans Nom | 2/15 | L'Ombre sur les Flots | 75 cm |
| 003 | Le Navire Sans Nom | 3/15 | Le Seuil Interdit | 75 cm (test impression fond noir requis) |
| 004 | Le Navire Sans Nom | 4/15 | Les Voix du Pont | 75 cm |
| 005-015 | Le Navire Sans Nom | 5-15/15 | à venir | à définir |

**Format unique Kaïro MVP** : 75 cm pour les 4 épisodes commercialisés. Aucun Kaïro 100 cm prévu au MVP. Extension tailles éventuelle post-test marché.

## Tarification et TVA

### `price_eur` — définition

- `price_eur` = **prix produit en EUR TTC affiché boutique**.
- N'inclut **pas** : frais de livraison, droits de douane, taxes d'importation, surcharges destination.
- Pas de multi-devise au MVP (EUR uniquement, cf CLAUDE.md cadrage stratégique).

### Prix MVP figés

| SKU | price_eur | Note |
|-----|-----------|------|
| KH-EDS-001-100 | 49 € | Stars & Stripes 100 cm, série test 50 pièces |
| KH-KAI-001-075 | 35 € | Kaïro épisode 1, 75 cm |
| KH-KAI-002-075 | 35 € | Kaïro épisode 2, 75 cm |
| KH-KAI-003-075 | 35 € | Kaïro épisode 3, 75 cm (test impression fond noir requis) |
| KH-KAI-004-075 | 35 € | Kaïro épisode 4, 75 cm |

Tarifs **modifiables après test marché** (ajustement possible avant scale).

### Livraison

- **France métropolitaine** : livraison **gratuite** (traitement plugin WC Shipping côté Lot 1+, hors `price_eur`).
- **International** (UE / hors UE) : frais réels + droits de douane / taxes d'importation **hors `price_eur`**, traitement KH-012 et KH-015 Lot 0.
- Aucune incorporation duties dans `price_eur`. Approche DDP/upfront décidée séparément (cf CLAUDE.md USA / douanes critique).

### TVA — franchise en base

- Régime BCDG : **franchise en base de TVA** (article 293 B du CGI).
- **TVA non applicable**, art. 293 B du CGI.
- Mention obligatoire factures + CGV + pied de page boutique : *"TVA non applicable, art. 293 B du CGI."*
- WooCommerce : configurer **prix TTC sans TVA collectée** (taux 0 % ou désactivation calcul TVA). Préciser config exacte Lot 1 (KH-100+).

## Bilingue scope MVP

FR + EN uniquement. Pas `/de/`, `/es/`, `/it/` au MVP. Racine `/` fallback `/en/` pour navigateurs non-FR.

## Statut document

Schéma KH-010 gelé 2026-05-28. Toute modification structure (colonnes, convention SKU, règles product_type, workflow Polylang) requiert nouveau ticket de revue avant import production.

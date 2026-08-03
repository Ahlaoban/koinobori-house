# Lot 2 — Catalogue & fiches produits (plan d'ouverture)

> ## État au 2026-07-27 — catalogue bilingue complet sur staging
>
> **34 produits publiés : 17 FR + 17 EN.** Les 17 paires FR↔EN sont liées dans Polylang.
>
> Travail réalisé ce jour :
> - **7 traductions EN créées** (ID 220-226) pour les produits qui n'en avaient pas : Kaïro ép. 1-4, Stars & Stripes, Breton, Bigouden.
> - **10 traductions EN enrichies** (113, 119, 142, 145, 148, 151, 154, 167, 170, 175) : paragraphe matières remplacé, phrase Kodomo no Hi ajoutée.
> - **Markup normalisé** : les 17 fiches EN sont désormais en blocs `wp:paragraph`, comme les FR. Trois formats coexistaient — blocs, texte brut, et `<div>` imbriqués avec des `<div></div>` vides issus d'un collage. Les `<div>` ont été supprimés.
>
> Conventions relevées sur le publié, à respecter pour toute fiche future :
> - **FR et EN partagent le même SKU** (ex. 116 et 119 = `KH-MER-001`). Ce n'est pas un doublon à corriger.
> - Le **nom d'œuvre reste en français** dans le titre EN, avec un gloss anglais entre parenthèses au premier paragraphe.
> - La **phrase symbolique se place à la fin du paragraphe 1**, dans le même paragraphe, jamais en paragraphe séparé.
> - **Polylang for WooCommerce synchronise seul** le type de produit, les catégories, les attributs, le prix, le stock et les variations. Breton et Bigouden ont récupéré leurs 2 variations 20/25 € sans intervention.
>
> Audit final : **zéro anomalie** sur les 17 fiches EN — aucun mot interdit par la doctrine, aucun tiret cadratin, aucun `<div>` résiduel, aucun ancien paragraphe matières, aucun « bestseller ».
>
> ⚠️ **Deux points ouverts, arbitrage Alain** :
> 1. **Incohérence d'accord en FR** : 116 La Vague Bleue est au féminin (« Suspendue… elle s'anime »), 127 La Vague Brune au masculin (« Suspendu… il s'anime »), sur une phrase structurellement identique. 116 est seule contre 16.
> 2. **Brouillon 200** « Écailles Bleues et Oranges (Copier) », en anglais : c'était la base conservée pour traduire le Breton. Le Breton EN existe désormais proprement (225), ce brouillon est devenu inutile. Non supprimé.

- **Date** : 2026-06-15
- **Statut** : 🟢 **Catalogue bilingue livré sur staging** (cf. encadré ci-dessus). Le plan ci-dessous reste la référence de décomposition.
- **Amont** : Lot 1 GO (WP/WC/Polylang/SEOPress, KH-104b-full). Produit test supprimé.
- **Environnement** : **staging uniquement** · pas de prod · PR #1 non mergée.
- **Gouvernance** : structure + contenu seulement. **Présentation fiche = NEUTRE** (visuel réservé Alain/Manus → `ux-backlog.md`). Conventions WC/Kadence = défauts réversibles.
- **Doctrine appliquée** : **2 blocs** long_desc (design/usage · signature BCDG) · short_desc **sans atelier** · jamais Chine/origine · jamais stock global · `- by BCDG` (tiret simple) · **aucune mention atelier/production sur les fiches** (FR+EN, rév. 2026-06-18).

## Décomposition (tickets indicatifs KH-2xx)

| # | Ticket | Objet | Dépend de |
|---|---|---|---|
| KH-201 | Taxonomie collections | **5 collections** bilingues (Mer·Motifs·Hanami·Kaïro·Territoires) + slugs FR/EN — figé 2026-06-18 | Lot 1 |
| KH-202 | Attribut global Taille | 50/75/100 cm (extensible) + termes bilingues | Lot 1 |
| KH-203 | Gabarit fiche produit | Modèle de référence : type **variable+variations**, structure **2 blocs**, convention **SKU**, wording FR/EN doctrine, **stock par produit** | — |
| KH-204 | Méthode de saisie | Manuel WC **ou** import CSV + sync traduction Polylang produit | KH-203 |
| KH-205 | Catégories produit ↔ langues | Assignation langue par produit, base `/produit/` (`/en/produit/` accepté), catégories-produit bilingues | KH-201/202 |
| KH-208 | Collection **Kaïro** | Saisie produits + traduction EN + audit doctrine | 201-205 + images/textes |
| KH-209 | Collection **Mer** | idem · absorbe ex-**OKUSAI** (vague Hokusai) | idem |
| KH-210 | Collection **Motifs** | idem · ex-« divers » | idem |
| KH-211 | Collection **Territoires** | régions/drapeaux : **Stars & Stripes** (`KH-TER-001-100`, 49 €, série limitée) + Bretons | idem |
| KH-212 | *(fusionné)* | ex-« Éditions spéciales » → « série limitée » = tag/bloc 2, plus une collection ; Stars & Stripes rangé en Territoires (KH-211) | — |
| KH-213 | Collection **Hanami** | inclut **Sakura Rouge** (`KH-HAN-001-075`, 29 €) | idem + mockup |
| KH-214 | **Audit doctrine global** | grep pré-publish FR+EN : Chine/origine, stock global, « atelier partenaire »/« partner workshop » (short_desc **ET** long_desc) | KH-208-213 |
| KH-215 | **Catalogue indexable** | sitemaps produits FR+EN, hreflang/canonical par produit, structure neutre | KH-208-213 + SEOPress (GO) |

## Séquence recommandée
1. **Socle structure** : KH-201 → KH-202 → KH-205 (taxonomie + attribut + catégories/langues).
2. **Conventions** : KH-203 (gabarit) → KH-204 (méthode saisie).
3. **Contenu** : KH-208→213 (par collection, **parallélisable**). Commencer par **1 collection pilote** (ex. Kaïro ou Hanami) pour valider le gabarit avant d'industrialiser.
4. **Gates de sortie** : KH-214 (audit doctrine) + KH-215 (indexabilité).

## Critères de sortie Lot 2
- ~17 produits **publiés bilingues** FR+EN (nom, SKU, prix EUR, stock/produit, images, short_desc, long_desc 2 blocs).
- Structure **neutre + réversible** (pas de CSS lourd, pas de mise en page figée).
- **KH-214 audit doctrine = PASS** (0 fuite Chine/origine/stock global ; short_desc propre).
- **KH-215 = catalogue indexable** bilingue (produits dans sitemaps FR+EN, canonical self, hreflang).

## Prérequis externes (gates de démarrage du contenu)
1. **Images produits** prêtes par collection ? (mockups — Hanami « attendu juin 2026 » selon mémoire). → bloque KH-208-213 de la collection concernée.
2. **Textes FR + EN** : qui rédige ? (toi / moi sur brief / mixte). → bloque la saisie.
3. **Méthode de saisie** (KH-204) : manuel WC admin **vs** import CSV.

## Hors scope Lot 2
Paiement/checkout/livraison (Lot 4), pages éditoriales (Lot 3), forms/emails (Lot 5), **thème/visuel fiche** (Lot 6-7). Pas d'import catalogue « réel » au sens prod tant que staging — saisie sur staging OK.

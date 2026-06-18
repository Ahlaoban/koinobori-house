# Lot 2 — Catalogue & fiches produits (plan d'ouverture)

- **Date** : 2026-06-15
- **Statut** : 🟡 **OUVERT — plan à valider (Alain)**. Aucune implémentation avant GO.
- **Amont** : Lot 1 GO (WP/WC/Polylang/SEOPress, KH-104b-full). Produit test supprimé.
- **Environnement** : **staging uniquement** · pas de prod · PR #1 non mergée.
- **Gouvernance** : structure + contenu seulement. **Présentation fiche = NEUTRE** (visuel réservé Alain/Manus → `ux-backlog.md`). Conventions WC/Kadence = défauts réversibles.
- **Doctrine appliquée** : 3 blocs long_desc · short_desc **sans atelier** · jamais Chine/origine · jamais stock global · `— by BCDG` · transparence « atelier partenaire » bas de fiche.

## Décomposition (tickets indicatifs KH-2xx)

| # | Ticket | Objet | Dépend de |
|---|---|---|---|
| KH-201 | Taxonomie collections | 6 collections bilingues (Kaïro·La Mer·OKUSAI·Bretagne·Éditions spéciales·Hanami) + slugs FR/EN | Lot 1 |
| KH-202 | Attribut global Taille | 50/75/100 cm (extensible) + termes bilingues | Lot 1 |
| KH-203 | Gabarit fiche produit | Modèle de référence : type **variable+variations**, structure **3 blocs**, convention **SKU**, wording FR/EN doctrine, **stock par produit** | — |
| KH-204 | Méthode de saisie | Manuel WC **ou** import CSV + sync traduction Polylang produit | KH-203 |
| KH-205 | Catégories produit ↔ langues | Assignation langue par produit, base `/produit/` (`/en/produit/` accepté), catégories-produit bilingues | KH-201/202 |
| KH-208 | Collection **Kaïro** | Saisie produits + traduction EN + audit doctrine | 201-205 + images/textes |
| KH-209 | Collection **La Mer** | idem | idem |
| KH-210 | Collection **OKUSAI** | idem | idem |
| KH-211 | Collection **Bretagne** | idem | idem |
| KH-212 | Collection **Éditions spéciales** | inclut **Stars & Stripes** (49 €, série 25) | idem |
| KH-213 | Collection **Hanami** | inclut **Sakura Rouge** (KH-HAN-001-075, 29 €) | idem + mockup |
| KH-214 | **Audit doctrine global** | grep pré-publish FR+EN : Chine/origine, stock global, « atelier partenaire » en short_desc | KH-208-213 |
| KH-215 | **Catalogue indexable** | sitemaps produits FR+EN, hreflang/canonical par produit, structure neutre | KH-208-213 + SEOPress (GO) |

## Séquence recommandée
1. **Socle structure** : KH-201 → KH-202 → KH-205 (taxonomie + attribut + catégories/langues).
2. **Conventions** : KH-203 (gabarit) → KH-204 (méthode saisie).
3. **Contenu** : KH-208→213 (par collection, **parallélisable**). Commencer par **1 collection pilote** (ex. Kaïro ou Hanami) pour valider le gabarit avant d'industrialiser.
4. **Gates de sortie** : KH-214 (audit doctrine) + KH-215 (indexabilité).

## Critères de sortie Lot 2
- ~50 produits **publiés bilingues** FR+EN (nom, SKU, prix EUR, stock/produit, images, short_desc, long_desc 3 blocs).
- Structure **neutre + réversible** (pas de CSS lourd, pas de mise en page figée).
- **KH-214 audit doctrine = PASS** (0 fuite Chine/origine/stock global ; short_desc propre).
- **KH-215 = catalogue indexable** bilingue (produits dans sitemaps FR+EN, canonical self, hreflang).

## Prérequis externes (gates de démarrage du contenu)
1. **Images produits** prêtes par collection ? (mockups — Hanami « attendu juin 2026 » selon mémoire). → bloque KH-208-213 de la collection concernée.
2. **Textes FR + EN** : qui rédige ? (toi / moi sur brief / mixte). → bloque la saisie.
3. **Méthode de saisie** (KH-204) : manuel WC admin **vs** import CSV.

## Hors scope Lot 2
Paiement/checkout/livraison (Lot 4), pages éditoriales (Lot 3), forms/emails (Lot 5), **thème/visuel fiche** (Lot 6-7). Pas d'import catalogue « réel » au sens prod tant que staging — saisie sur staging OK.

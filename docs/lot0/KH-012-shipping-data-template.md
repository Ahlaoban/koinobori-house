# KH-012 — Template collecte données shipping

Companion document pour `KH-012-shipping-data-template.csv`. Structure 18 colonnes par (SKU × carrier × zone). Intègre formule marge international 6 % et doctrine tarifs MVP manuel.

## 1. Structure CSV (20 colonnes)

| # | Colonne | Type | Pré-rempli ? |
|---|---------|------|--------------|
| 1 | `sku` | string | ✅ master.csv |
| 2 | `size_cm` | int | ✅ master.csv |
| 3 | `product_weight_g` | int (g) | ✅ Alain (18/30/45) |
| 4 | `packaging_type` | enum | ✅ `E2_amazon_23x29` (1 unité par colis) |
| 5 | `packaging_weight_g` | int (g) | ✅ 47 (E2) |
| 6 | `parcel_total_weight_g` | int (g) | ✅ calculé |
| 7 | `carrier` | string | ✅ service précis incluant rôle (PRINCIPAL / fallback) |
| 8 | `zone` | enum | ✅ `France` \| `UE` \| `USA` |
| 9 | `carrier_cost_eur` | decimal € | ✅ pré-rempli FR (3,60) / UE (7,65) / USA vide (devis requis) |
| 10 | `laposte_management_fees_eur` | decimal € | ❌ Alain (frais gestion La Poste éventuels Colissimo USA en ligne, 0 autres) |
| 11 | `us_duties_taxes_eur` | decimal € | ❌ Alain (droits + taxes US payés upfront expéditeur — uniquement USA Colissimo en ligne ou fallback DDP, 0 sinon) |
| 12 | `packaging_handling_cost_eur` | decimal € | ❌ Alain (coût enveloppe + ruban + temps préparation) |
| 13 | `international_overhead_rate` | decimal | ✅ pré-rempli `0` (France) ou `0.06` (international) |
| 14 | `displayed_shipping_price_eur` | decimal € | ❌ calculé via formule §3 (France = 0 gratuit) |
| 15 | `source_url` | URL | ✅ source officielle ou agrégateur tarif |
| 16 | `source_checked_at` | date YYYY-MM-DD | ✅ `2026-05-29` |
| 17 | `tracking_yn` | enum | ✅ `oui` / `oui partiel` |
| 18 | `insurance_yn` | enum | ✅ `non` / `oui` / `oui (R+ option)` |
| 19 | `delivery_eta` | string | ✅ délai indicatif |
| 20 | `remarques` | string | ✅ note free-text (PRINCIPAL vs fallback notamment) |

## 2. Hypothèse de base CSV

- **1 unité par colis** = E2 (enveloppe Amazon 23×29 cm, 47 g) pour les 5 SKUs pilotes
- 3 zones testées : France, UE, USA
- France + UE = 1 service par zone (La Poste Lettre verte suivie / Lettre Suivie Internationale)
- **USA actualisé §1ter 2026-05-29** :
  - **Colissimo USA en ligne** = service PRINCIPAL (réouvert sous conditions, droits payés upfront expéditeur, valeur < 650 €)
  - **Chronopost / UPS / DHL / FedEx** = fallback uniquement si Colissimo inadapté
- 5 SKUs × (1 FR + 1 UE + 5 USA = 1 principal Colissimo + 4 fallback) = **35 rows**

Multi-unité / multi-format = cas additionnels à ajouter en lignes supplémentaires si volume justifie. Cf cas couverts §3.3 note KH-012/KH-015 (9 configurations B2C documentées).

## 3. Formule calcul `displayed_shipping_price_eur`

### France

**Livraison gratuite.** `displayed_shipping_price_eur = 0`. Marge absorbée par marge produit. `international_overhead_rate = 0`.

### International UE (formule générale)

```
displayed_shipping_price_eur =
    carrier_cost_eur
  + packaging_handling_cost_eur
  + 0.06 × (price_eur_produit + carrier_cost_eur)
```

### International USA (formule étendue DDP partiel Colissimo)

```
displayed_shipping_price_eur =
    carrier_cost_eur
  + laposte_management_fees_eur
  + us_duties_taxes_eur
  + packaging_handling_cost_eur
  + 0.06 × (price_eur_produit + carrier_cost_eur)
```

**Variables** :
- `carrier_cost_eur` = tarif transporteur réel (Colissimo USA en ligne affranchissement ou fallback)
- `laposte_management_fees_eur` = frais gestion La Poste éventuels (Colissimo USA en ligne seulement, 0 ailleurs)
- `us_duties_taxes_eur` = droits + taxes US payés upfront par expéditeur (Colissimo USA en ligne ou fallback en DDP, 0 si DAP pur)
- `packaging_handling_cost_eur` = coût emballage + temps préparation
- `0.06` = taux marge international MVP (cf §5bis note KH-012/KH-015)
- `price_eur_produit` = prix produit master.csv (49 € Stars & Stripes, 35 € Kaïro)

**Note** : la marge 6 % reste calculée sur `price + carrier_cost` (pas sur droits/taxes), justifiée par fait que droits/taxes sont des pass-through réglementaires, pas du coût opérationnel à absorber.

### Exemple Kaïro 1 unité → UE

- `carrier_cost_eur` = 7,65 € (Lettre Suivie Internationale ≤100g)
- `packaging_handling_cost_eur` = hypothèse 1,00 € (à valider Alain — coût enveloppe E2 ~0,30 € + handling ~0,70 €)
- `0.06 × (35 + 7,65)` = 0,06 × 42,65 = 2,56 €
- `displayed_shipping_price_eur` = 7,65 + 1,00 + 2,56 = **11,21 €**
- Arrondi commercial possible : **11 €** ou **11,50 €**

### Exemple Stars & Stripes 1 unité → UE

- `carrier_cost_eur` = 7,65 € (idem)
- `packaging_handling_cost_eur` = 1,00 € hypothèse
- `0.06 × (49 + 7,65)` = 0,06 × 56,65 = 3,40 €
- `displayed_shipping_price_eur` = 7,65 + 1,00 + 3,40 = **12,05 €**
- Arrondi commercial : **12 €**

### Exemple Kaïro 1 unité → USA (Colissimo en ligne, illustration)

Toutes valeurs USA = **à obtenir test réel Alain**. Illustration formule uniquement :

- `carrier_cost_eur` = X € (Colissimo USA en ligne affranchissement)
- `laposte_management_fees_eur` = Y € (frais gestion La Poste éventuels)
- `us_duties_taxes_eur` = Z € (droits + taxes US, fonction valeur produit et code HTSUS)
- `packaging_handling_cost_eur` = 1,00 € hypothèse
- `0.06 × (35 + X)` = marge international
- `displayed_shipping_price_eur` = X + Y + Z + 1,00 + 0,06 × (35 + X)

**Aucun chiffre USA inventé**. Test Colissimo USA en ligne requis pour collecter X, Y, Z réels.

## 4. Doctrine MVP — tarifs manuels

- WC configuré **manuellement** par zone × tranche poids × service
- **Pas de mise à jour temps réel** au MVP
- **Pas de plugin transporteur** au MVP (API DHL/Colissimo/MR → phase 2)
- **Exception Lot 1 envisageable** : plugin Mondial Relay (sélection point relais front + étiquettes) ou plugin Colissimo (étiquettes Colissimo Box). À évaluer Lot 1 selon volume prévu.
- **Tarifs site arrondis commercialement** (ex 11,21 € → 11 € ou 11,50 €) pour lisibilité + absorption variations
- **Tarifs modifiables** après observation réelle des coûts post-launch (taux marge 6 % notamment)

### Tarifs dynamiques / DDP — phase 2

- DHL Express API + DDP
- Sendcloud Cross-Border
- Boxtal Pro Volume B2B/B2G
- Zonos DDP USA (si réactivé post-arbitrage §1ter)
- Mondial Relay API

## 5. Wording client INTERDIT

❌ **JAMAIS** publiquement :
- "frais Stripe"
- "frais PayPal"
- "frais de moyen de paiement"
- "commission carte bancaire"
- "marge handling"

✅ **Wording autorisé client** :
- "Frais de livraison internationale"
- "Frais d'expédition"
- "Shipping and handling"
- "International shipping"

Audit pré-publish KH-707 : grep `Stripe` / `PayPal` / `commission` dans textes shipping public → fail si présent.

## 6. France livraison gratuite — rappel

- France métropolitaine : **gratuite** au client, marge produit absorbe coût transporteur réel
- France DROM-COM : **à statuer** (frais réels possibles ou différé)
- Aucune marge 6 % appliquée à France

## 7. Tarifs sources publiques 2026 — référence

| Service | Tranche | Tarif | Source URL | Date |
|---------|---------|------:|------------|------|
| La Poste Lettre verte suivie France | ≤ 20 g | 2,02 € | [sfpf.fr](https://www.sfpf.fr/actu/tarif-timbre-2026) | 2026 |
| La Poste Lettre verte suivie France | ≤ 100 g | 3,60 € | idem | 2026 |
| La Poste Lettre Suivie Internationale Monde | ≤ 20 g | 5,05 € | [tarif-lettre.com](https://www.tarif-lettre.com/tarif-lettre-internationale-2026) | 2026-05-29 |
| La Poste Lettre Suivie Internationale Monde | ≤ 100 g | 7,65 € | idem | 2026-05-29 |
| La Poste Lettre Suivie Internationale Monde | ≤ 250 g | 14,45 € | idem | 2026-05-29 |
| La Poste Lettre Suivie Internationale Monde | ≤ 500 g | 19,40 € | idem | 2026-05-29 |
| La Poste Lettre Suivie Internationale Monde | ≤ 2 kg | 34,50 € | idem | 2026-05-29 |
| Colissimo France ≤ 250 g | domicile / point retrait | 5,49 € / 4,79 € | [tarif-lettre.com](https://www.tarif-lettre.com/tarif-colissimo-2026) | 2026-05-29 |
| Colissimo France ≤ 500 g | dom / retrait | 7,59 € / 6,89 € | idem | 2026-05-29 |
| Colissimo France ≤ 1 kg | dom / retrait | 9,59 € / 8,89 € | idem | 2026-05-29 |
| Colissimo France ≤ 2 kg | dom / retrait | 11,19 € / 10,49 € | idem | 2026-05-29 |
| Colissimo International Zone A (UE+CH+UK) | ≤ 500 g | 14,99 € | [tarifs-postaux.fr](https://tarifs-postaux.fr/tarif-colissimo.htm) | 2026-05-29 |
| Colissimo International Zone A | ≤ 1 kg | 19,39 € | idem | 2026-05-29 |
| Colissimo International Zone A | ≤ 2 kg | 22,19 € | idem | 2026-05-29 |
| Colissimo International Zone B | ≤ 500 g / 1 kg / 2 kg | 23,79 € / 28,39 € / 31,09 € | idem | 2026-05-29 |
| Colissimo International Zone C | ≤ 500 g / 1 kg / 2 kg | 35,19 € / 39,19 € / 53,99 € | idem | 2026-05-29 |
| **Colissimo USA en ligne** (réouvert sous conditions 2026) | toutes tranches < 650 € valeur | **devis requis** test Colissimo en ligne | [laposte.fr — page Envoyer colis USA](https://www.laposte.fr/) | 2026-05-29 |
| Mondial Relay France ≤ 500 g | point relais | 4,10 € | [mondialrelay.fr](https://www.mondialrelay.fr/envoi-de-colis/tarifs-expeditions/) | 2026 |
| Mondial Relay autres tranches/pays | toutes | non collecté | à compléter Alain | — |

## 8. Bloquants Lot 0 actuels

| Bloquant | Owner | Statut |
|----------|-------|--------|
| **Test Colissimo USA en ligne** sur 3 paniers réels (1×Kaïro 35€ / 1×Stars & Stripes 49€ / 3×Kaïro 105€) | Alain (laposte.fr) | **Bloquant USA actif** |
| **Décision USA actif (Colissimo) vs "nous contacter"** post-test Colissimo | Alain | **Bloquant zone USA WC** |
| Vérification statut territoires associés US (Porto Rico, Guam, etc.) suspendus | Alain (La Poste) | Bloquant carve-out destinations USA |
| Devis fallback Chronopost / UPS / DHL / FedEx (uniquement si Colissimo inadapté) | Alain | Bloquant fallback uniquement |
| `packaging_handling_cost_eur` réel par colis E2 (enveloppe + ruban + temps préparation) | Alain | Bloquant calcul displayed_shipping_price |
| Tarif Lettre verte suivie tranche ≤ 250 g France (pour cas 5 = 137 g) | Alain | Bloquant cas 5 France |
| Tarifs Mondial Relay France tranches > 500 g + pays UE desservis | Alain | Bloquant option MR |
| Tarifs Colissimo Zone A tranches 250 g + 750 g (visibilité réduite) | Alain | Validation complétude grille |
| Vérification code HTSUS source officielle | Alain ou conseil douanier | Ouvert (non-bloquant tarifs, bloquant page USA publique) |
| Caractéristiques futures enveloppes (à requalifier au cas par cas) | Alain | Non-bloquant tant que E1/E2 actuelles couvrent volume |
| Caractéristiques petites boîtes carton (au-delà E2) | Alain | Non-bloquant tant que volume B2C unité par commande prévalent |

Voir aussi grille prioritaire 5 cas : [KH-012-tarifs-collecte-prioritaire.csv](KH-012-tarifs-collecte-prioritaire.csv) + [companion MD](KH-012-tarifs-collecte-prioritaire.md).

## 9. Garde-fous doctrine

- ❌ Pas d'invention tarifs
- ❌ Pas d'autre transporteur MVP B2C **pour France et UE** (DHL/UPS/FedEx/Chronopost express = phase 2 pour ces zones)
- ✅ **Exception bornée USA marchandise** (§1ter) : Chronopost/UPS/DHL/FedEx autorisés exceptionnellement, un seul retenu MVP à la fois, pas extension France/UE
- ❌ Pas de chantier B2B/B2G dans ce fichier (chantier séparé)
- ❌ Pas de wording client mentionnant Stripe/PayPal/commission
- ✅ Sources URL + date renseignées systématiquement
- ✅ Captures écran devis horodatées hors-repo
- ✅ Documentation futures enveloppes au fur et à mesure

## 10. Livrables attendus post-collecte

1. Calculer marge nette par cas par zone après application formule
2. Valider taux marge international 6 % post-données réelles
3. Arrondir tarifs site (politique commerciale)
4. Configurer WC manuellement par zone × tranche poids × service Lot 1
5. Drafter wording page Livraison + checkout + CGV avec formulation prudente §1bis pour USA
6. Trancher arbitrage USA Options §1ter
7. Ouvrir chantier B2B/B2G shipping séparé si premier projet concret

## 11. Liens

- Cadrage parent : [KH-012-KH-015-shipping-usa-seo.md](KH-012-KH-015-shipping-usa-seo.md)
- Catalogue produits : [../../catalog/README.md](../../catalog/README.md)
- CSV data entry : [KH-012-shipping-data-template.csv](KH-012-shipping-data-template.csv)
- Grille prioritaire 5 cas : [KH-012-tarifs-collecte-prioritaire.csv](KH-012-tarifs-collecte-prioritaire.csv) + [.md](KH-012-tarifs-collecte-prioritaire.md)

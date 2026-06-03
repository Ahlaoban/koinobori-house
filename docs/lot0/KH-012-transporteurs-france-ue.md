# KH-012 — Transporteurs France / UE — Modèle livraison B2C

Cadrage Lot 0 dédié au modèle livraison B2C France + UE pour WooCommerce, sans configuration WC effective. Complète la note parent [KH-012-KH-015-shipping-usa-seo.md](KH-012-KH-015-shipping-usa-seo.md).

- **Ticket** : KH-012
- **Lot** : 0
- **Date ouverture chantier dédié** : 2026-05-30
- **Statut** : 🔄 **EN COURS** — en attente devis réels Alain
- **Dépendances** : KH-010 (catalogue/SKU), KH-017 (CGV §7 délais), KH-015 (USA séparé)
- **Hors scope** : USA (KH-015), UK (non activé), DROM-COM (à statuer), B2B/B2G (chantier séparé)

## 1. Matrice poids produit + emballage

### 1.1 Poids unitaires (rappel KH-010)

| Taille | Poids unitaire |
|--------|---------------:|
| 50 cm | 18 g |
| 75 cm | 30 g |
| 100 cm | 45 g |

### 1.2 Emballages B2C disponibles

| Code | Format | Poids vide | Capacité documentée | Objectif BAL (≤ 3 cm) |
|------|--------|-----------:|---------------------|:---------------------:|
| E1 | 18 × 23 cm cartonnée | 30 g | 1 à 2 × 50 cm | ✅ confirmé |
| E2 | 23 × 29 cm cartonnée type Amazon | 47 g | 3-4 × 50 cm / 1-3 × 75 cm / 1-2 × 100 cm | ✅ confirmé |
| BC1 | Petite boîte carton (au-delà capacité E2) | **inconnu** | à caractériser | **non confirmé** |

### 1.3 Combinaisons E1 / E2 — couvertes en BAL ≤ 3 cm

| Scénario | Composition | Poids produits | Emballage | Poids total |
|----------|-------------|---------------:|-----------|------------:|
| S1 | 1 × 50 cm | 18 g | E1 (30 g) | **48 g** |
| S2 | 2 × 50 cm | 36 g | E1 (30 g) | **66 g** |
| S3 | 3 × 50 cm | 54 g | E2 (47 g) | **101 g** |
| S4 | 4 × 50 cm | 72 g | E2 (47 g) | **119 g** |
| S5 | 1 × 75 cm | 30 g | E2 (47 g) | **77 g** |
| S6 | 2 × 75 cm | 60 g | E2 (47 g) | **107 g** |
| S7 | 3 × 75 cm | 90 g | E2 (47 g) | **137 g** |
| S8 | 1 × 100 cm | 45 g | E2 (47 g) | **92 g** |
| S9 | 2 × 100 cm | 90 g | E2 (47 g) | **137 g** |
| S10 | mixte 1 × 75 + 1 × 100 | 75 g | E2 (47 g) | **122 g** |

**Lecture** : tous les scénarios couverts par E1/E2 restent ≤ 137 g, BAL ≤ 3 cm confirmé Alain. Levier tarif = format BAL vs Colissimo, pas le poids.

### 1.4 Combinaisons au-delà E2 — BC1 à caractériser

| Scénario | Composition (exemples) | Poids produits | Emballage | Poids total |
|----------|------------------------|---------------:|-----------|-------------|
| S11 | 5+ × 50 cm OU 4+ × 75 cm OU 3+ × 100 cm OU panier composite volumineux | variable | BC1 (inconnu) | à calculer |

**Caractéristiques BC1** : inconnues à ce stade (dim, poids vide, coût, disponibilité fournisseur). À documenter dès première commande dépassant capacité E2.

## 2. Scénarios d'expédition B2C France

### 2.1 Règle de routage La Poste

| Critère | Service retenu |
|---------|----------------|
| Poids ≤ 100 g + épaisseur ≤ 3 cm | **Lettre verte suivie ≤ 100 g** (3,60 €) |
| Poids 100-250 g + épaisseur ≤ 3 cm | **Lettre verte suivie ≤ 250 g** (5,74 € relevé Alain 2026-05-30) |
| Poids 250-500 g + épaisseur ≤ 3 cm | **Lettre verte suivie ≤ 500 g** (7,91 € relevé Alain 2026-05-30) |
| Poids > 500 g OU épaisseur > 3 cm OU format hors BAL | **Colissimo France domicile ou point retrait** |
| Option client point relais | **Mondial Relay France** (4,40 / 4,90 / 6,30 € selon tranche) |

### 2.2 Scénarios France à tarifer

| # | Scénario | Poids total | Service La Poste candidat | Service Colissimo fallback | Service MR option |
|---|----------|------------:|---------------------------|----------------------------|-------------------|
| F-S1 | 1 × 50 cm | 48 g | Lettre suivie ≤ 100 g (3,60 €) | Colissimo ≤ 250 g (5,49 € dom / 4,79 € retrait) | MR ≤ 500 g (4,40 €) |
| F-S2 | 2 × 50 cm | 66 g | idem ≤ 100 g | idem | idem |
| F-S3 | 1 × 75 cm | 77 g | idem ≤ 100 g | idem | idem |
| F-S4 | 3 × 75 cm | 137 g | Lettre suivie ≤ 250 g (5,74 €) | idem | idem |
| F-S5 | 1 × 100 cm | 92 g | Lettre suivie ≤ 100 g | idem | idem |
| F-S6 | 2 × 100 cm | 137 g | Lettre suivie ≤ 250 g (5,74 €) | idem | idem |
| F-S7 | mixte 1 × 75 + 1 × 100 | 122 g | Lettre suivie ≤ 250 g (5,74 €) | idem | idem |
| F-S8 | panier supérieur (BC1) | variable | Lettre suivie ≤ 500 g (7,91 €) si BAL OK | Colissimo selon tranche réelle | MR > 500 g (4,90 €) ou ≤ 2 kg (6,30 €) selon poids |

### 2.3 Tarification client France

- **Livraison France métropolitaine : offerte à partir de 55 € d'achat** (décision Alain 2026-05-30)
- Formulation publique validée :
  > Livraison offerte en France métropolitaine à partir de 55 € d'achat.
  > Les DROM-COM, l'Union européenne et l'international ne sont pas concernés par cette gratuité.
- ❌ Ne pas ajouter wording public type "En dessous de 55 €, une participation aux frais de livraison est appliquée" — information implicite checkout WC via frais affichés
- En dessous du seuil 55 € : frais de livraison France affichés au client (coût transporteur + marge à définir)
- Coût transporteur > seuil : absorbé par marge produit
- Mondial Relay = option client si choisi, peut être proposé en option point relais sans surcoût ou avec léger surcoût symbolique
- **DROM-COM** : **activés dès le MVP, hors gratuité** (frais réels affichés au client, surcoût postal îles)

## 3. Scénarios d'expédition B2C UE

### 3.1 Pays prioritaires à tarifer

4 pays représentatifs marché UE :
- **Allemagne** (DE) — grand marché européen
- **Belgique** (BE) — pays voisin, MR desservi
- **Espagne** (ES) — pays sud-européen, MR desservi
- **Italie** (IT) — pays sud-européen, MR desservi

Autres pays UE 27 : couverts par même tarif Colissimo International Zone A en général (à valider).

### 3.2 Service principal UE

- **Colissimo International Zone A** (UE + CH + UK, tarifs collectés §3.7 note parent)
  - ≤ 500 g : 14,99 €
  - ≤ 1 kg : 19,39 € (utilisée pour paniers > 500 g car pas de tranche 750 g identifiée)
  - ≤ 2 kg : 22,19 €
- **Mondial Relay International** (relevés Alain 2026-05-30) :
  - Belgique / Luxembourg ≤ 500 g : **4,55 €** (3-4 j)
  - Espagne ≤ 500 g : **6,80 €** (3-4 j)
  - Italie ≤ 500 g : **11,20 €** (3-6 j, livraison à domicile, pas point relais standard)
  - NL / DE / PT / PL : **non collecté**, ne pas élargir auto sans données fiables

### 3.3 Scénarios UE à tarifer (sous-ensemble représentatif)

Plutôt que tester 8 scénarios × 4 pays = 32 lignes, retenir 3 scénarios poids représentatifs × 4 pays = 12 lignes :

| Scénario poids | Cas catalogue typique | Poids total |
|----------------|----------------------|------------:|
| UE-W1 | 1 × 75 cm OR 1 × 100 cm | 77-92 g (≤ 100 g) |
| UE-W2 | 3 × 75 cm OR 2 × 100 cm | 137 g (≤ 250 g, ≤ 500 g Colissimo) |
| UE-W3 | mixte multi-unités E2 max | ~150 g (≤ 500 g) |

**Tous ces scénarios tiennent dans le palier ≤ 500 g Colissimo Zone A.** Tarif unique zone A 14,99 € applicable.

→ Devis UE prioritaire = **1 ligne Colissimo Zone A ≤ 500 g par pays** + Mondial Relay par pays desservi.

### 3.4 Tarification client UE

Formule §5bis note parent :
```
displayed_shipping = carrier_cost + packaging_handling + 0.06 × (price_produit + carrier_cost)
```

- `carrier_cost` : tarif Colissimo Zone A 14,99 € (≤ 500 g) / 19,39 € (≤ 1 kg) ou tarif MR international relevé
- `packaging_handling` : **à fournir Alain** (inconnu — coût enveloppe E2 + ruban + temps + stickers + flyers + inserts éventuels). **Ne pas inventer.**
- `0.06 × (price + carrier)` : marge international 6 %
- Wording client autorisé : « Frais de livraison internationale » / « Shipping and handling » (interdit Stripe/PayPal/commission)

⚠️ **Calcul prix affichés UE non finalisable** tant que `packaging_handling_cost_eur` non confirmé Alain. Champ CSV laissé vide ou marqué « à calculer après coût packaging/handling ».

## 4. Devis réels à relever par Alain

### 4.1 Source officielle La Poste — France métropolitaine

| Service | Tranche | À relever | Statut |
|---------|---------|-----------|--------|
| Lettre verte suivie | ≤ 20 g | 2,02 € | ✅ collecté |
| Lettre verte suivie | ≤ 100 g | 3,60 € | ✅ collecté |
| Lettre verte suivie | ≤ 250 g | **5,74 €** | ✅ relevé Alain 2026-05-30 |
| Lettre verte suivie | ≤ 500 g | **7,91 €** | ✅ relevé Alain 2026-05-30 |
| Colissimo France domicile | ≤ 250 g / ≤ 500 g / ≤ 1 kg / ≤ 2 kg | 5,49 / 7,59 / 9,59 / 11,19 € | ✅ collecté |
| Colissimo France point retrait | ≤ 250 g / ≤ 500 g / ≤ 1 kg / ≤ 2 kg | 4,79 / 6,89 / 8,89 / 10,49 € | ✅ collecté |
| Colissimo France | ≤ 5 kg / ≤ 10 kg | 17,39 / 25,29 € | ✅ collecté |

Source : [laposte.fr](https://www.laposte.fr/) + agrégateurs ([tarif-lettre.com](https://www.tarif-lettre.com/tarif-colissimo-2026), [tarifs-postaux.fr](https://tarifs-postaux.fr/tarif-colissimo.htm)).

### 4.2 Source officielle La Poste — UE Zone A

| Service | Tranche | À relever | Statut |
|---------|---------|-----------|--------|
| Colissimo International Zone A | ≤ 500 g | 14,99 € | ✅ collecté |
| Colissimo International Zone A | ≤ 1 kg | 19,39 € | ✅ collecté |
| Colissimo International Zone A | ≤ 2 kg | 22,19 € | ✅ collecté |
| Colissimo International Zone A | ≤ 250 g | **non visible** sur sources | ❌ |
| Colissimo International Zone A | ≤ 750 g | **pas de tranche 750 g identifiée — utiliser ≤ 1 kg (19,39 €)** | ⚠️ doctrine |

⚠️ Tranche 250 g UE Colissimo non disponible sur sources publiques. À vérifier compte Colissimo Pro ou Boxtal.

**Doctrine Colissimo UE Zone A tranche 750 g** : pas de tranche spécifique 750 g identifiée chez La Poste. Pour les paniers UE au-delà 500 g, **utiliser la tranche ≤ 1 kg à 19,39 €**. Ne pas inventer de tranche intermédiaire.

### 4.3 Source officielle Mondial Relay — relevés Alain 2026-05-30

| Service | Tranche | Zone | Tarif relevé | Statut | Délai indicatif | Note |
|---------|---------|------|-------------:|--------|-----------------|------|
| MR France Point Relais | ≤ 500 g | France | **4,40 €** | ✅ relevé Alain (remplace ancien 4,10 €) | — | À revalider avant config WC Lot 1 |
| MR France Point Relais | > 500 g à 1 kg | France | **4,90 €** | ✅ relevé Alain | — | À revalider avant Lot 1 |
| MR France Point Relais | jusqu'à 2 kg | France | **6,30 €** | ✅ relevé Alain | — | À revalider avant Lot 1 |
| MR International | ≤ 500 g | Belgique / Luxembourg | **4,55 €** | ✅ relevé Alain | 3 à 4 j | |
| MR International | ≤ 500 g | Espagne | **6,80 €** | ✅ relevé Alain | 3 à 4 j | |
| MR International ou livraison à domicile | ≤ 500 g | Italie | **11,20 €** | ✅ relevé Alain | 3 à 6 j | ⚠️ Italie = livraison à domicile si point relais non standard, ne pas traiter comme MR point relais standard |
| MR Pays-Bas | toutes tranches | NL | **non collecté** | ❌ | — | Ne pas élargir auto si non fourni |
| MR Allemagne | toutes tranches | DE | **non collecté** | ❌ | — | Ne pas élargir auto si non fourni |
| MR Portugal | toutes tranches | PT | **non collecté** | ❌ | — | Ne pas élargir auto si non fourni |
| MR Pologne | toutes tranches | PL | **non collecté** | ❌ | — | Ne pas élargir auto si non fourni |

Source : [mondialrelay.fr](https://www.mondialrelay.fr/envoi-de-colis/tarifs-expeditions/) ou compte MR Pro.

**Note Italie** : tarif 11,20 € correspond à livraison à domicile si le point relais n'est pas standard. À traiter spécifiquement en config WC (zone livraison domicile dédiée, pas confondre avec MR point relais France/BE/LU/ES).

### 4.4 Méthode alternative — Boxtal courtier

[boxtal.com](https://boxtal.com) compte Pro gratuit → multi-devis Colissimo + MR + autres en une requête. Permet validation croisée tarifs direct.

## 5. Tableau à compléter par Alain (gabarit)

Fichier CSV companion à remplir : [KH-012-transporteurs-devis-template.csv](KH-012-transporteurs-devis-template.csv).

Colonnes :

| Col | Nom | Type | Pré-rempli ? |
|-----|------|------|--------------|
| 1 | `zone` | enum (France / UE / Mondial_Relay) | ✅ |
| 2 | `pays` | string (France, DE, BE, ES, IT, ...) | ✅ |
| 3 | `scenario` | code (F-S1, UE-W1, MR-W1, ...) | ✅ |
| 4 | `produits` | string (ex `1 × 75 cm`) | ✅ |
| 5 | `poids_produits_g` | int | ✅ |
| 6 | `emballage` | enum (E1 / E2 / BC1) | ✅ |
| 7 | `poids_total_g` | int (calculé) | ✅ |
| 8 | `format` | enum (BAL ≤ 3 cm / colis) | ✅ |
| 9 | `transporteur` | enum (La Poste / Colissimo / Mondial Relay) | ✅ |
| 10 | `service` | string (Lettre verte suivie ≤ 100 g, Colissimo Zone A ≤ 500 g, MR Point Relais ≤ 500 g, ...) | ✅ |
| 11 | `prix_transporteur_reel_eur` | decimal € | ❌ Alain |
| 12 | `prix_affiche_client_propose_eur` | decimal € (calculé via formule §3.4 ou 0 si France) | ❌ Alain calcule |
| 13 | `marge_interne_eur` | decimal € (= 0.06 × (price + carrier) pour international) | ❌ Alain calcule |
| 14 | `source_url` | URL | ❌ Alain |
| 15 | `source_checked_at` | date YYYY-MM-DD | ❌ Alain |
| 16 | `notes` | text | ❌ Alain |

## 6. Recommandation provisoire WooCommerce Lot 1

⚠️ **Pas de configuration WC effectuée à ce stade**. Recommandations cadrage Lot 1 uniquement.

### 6.1 Zones de livraison WC à créer

- **Zone France métropolitaine** (FR métropole uniquement) — gratuité conditionnée seuil 55 €
- **Zone DROM-COM** (activée MVP, hors gratuité, frais réels affichés)
- **Zone Union européenne** (DE, BE, NL, LU, ES, IT, PT, AT, IE, FI, SE, DK, EE, LV, LT, PL, CZ, SK, HU, SI, HR, RO, BG, EL, MT, CY) — hors gratuité
- **Zone Mondial Relay France métropolitaine** (option client, sous-ensemble France métropole)
- **Zone Mondial Relay UE** (BE, LU, NL, ES, IT, DE, PT, PL — pays desservis confirmés) — hors gratuité
- **Zone "Reste du monde — nous contacter"** (formulaire devis, pas checkout direct) — hors gratuité
- USA : zone **désactivée** par défaut MVP (cf KH-015), réactivable post-tests Colissimo USA en ligne

### 6.2 Méthode tarification WC

**Option A — Flat rate par tranche poids (recommandée MVP)** :
- Tarifs configurés manuellement par WC Shipping Zone × Class × tranche poids
- Tranches alignées sur paliers transporteurs (≤ 100 g, ≤ 250 g, ≤ 500 g, ≤ 1 kg, ≤ 2 kg)
- France métropolitaine : **règle WC `Free Shipping` conditionnée min_amount = 55 €** ; en dessous du seuil, tarif transporteur affiché (à définir Lot 1 selon marge)
- DROM-COM : frais réels affichés (tarif Colissimo Outre-Mer à fournir)
- UE : tarif Colissimo Zone A par tranche + marge 6 % + packaging_handling
- MR : tarif MR par zone + tranche + marge 6 % + handling
- Avantage : zéro plugin, contrôle total
- Inconvénient : maintenance manuelle si tarifs transporteurs changent

**Option B — Plugin Colissimo officiel** (à évaluer Lot 1) :
- Génération étiquettes Colissimo Box (utilité opérationnelle)
- Tarifs API temps réel (optionnel)
- Inconvénient : dépendance plugin + setup

**Option C — Plugin Mondial Relay officiel** (à évaluer Lot 1) :
- Sélection point relais front WC
- Génération étiquettes
- Utile si MR retenu comme option

**Décision MVP recommandée** : Option A flat rate manuel + envisager Lot 1 plugins MR/Colissimo pour étiquettes/points relais uniquement (pas tarifs temps réel).

### 6.3 Wording front

- France métropolitaine : « Livraison offerte en France métropolitaine à partir de 55 € d'achat. Les DROM-COM, l'Union européenne et l'international ne sont pas concernés par cette gratuité. » Sous le seuil, frais de livraison France affichés au panier (information implicite via frais checkout WC, **pas de wording explicite type "En dessous de 55 €, une participation aux frais..."**)
- DROM-COM : « Frais de livraison » + montant réel
- UE : « Frais de livraison internationale » + montant calculé
- Option MR : « Livraison en point relais » + montant
- USA : « Nous contacter » (formulaire dédié, pas checkout direct)
- Reste du monde : idem « Nous contacter »

### 6.4 Délais affichés (déjà figés CGV §7)

- France métropolitaine / Monaco : 2 jours ouvrables après expédition
- UE / Europe : 3 à 8 jours ouvrés après expédition selon pays
- USA : 5 à 10 jours ouvrés hors douane (uniquement si checkout USA activé post-KH-015)

## 7. Décisions restantes

| Décision | Owner | Priorité |
|----------|-------|----------|
| ~~Devis La Poste Lettre verte suivie ≤ 250 g et ≤ 500 g France~~ | ✅ relevé 2026-05-30 (5,74 € / 7,91 €) | Résolu |
| ~~Devis Mondial Relay France ≤ 500 g, > 500 g-1 kg, ≤ 2 kg~~ | ✅ relevé 2026-05-30 (4,40 / 4,90 / 6,30 €) | Résolu (à revalider avant Lot 1) |
| ~~Devis Mondial Relay International BE/LU/ES/IT ≤ 500 g~~ | ✅ relevé 2026-05-30 (4,55 / 6,80 / 11,20 €) | Résolu pour BE/LU/ES/IT |
| Devis Mondial Relay NL / DE / PT / PL | Alain | Non bloquant si zones non activées |
| Devis Colissimo Zone A tranches 250 g et 750 g | Alain | Non bloquant — doctrine = utiliser ≤ 500 g ou ≤ 1 kg |
| **`packaging_handling_cost_eur` réel par colis E2** (enveloppe + ruban + stickers + flyers + inserts) | Alain | **Bloquant calcul displayed_shipping_eur UE/MR** |
| Choix fournisseur stickers / flyers / inserts | Alain | Bloquant packaging_handling final |
| Caractéristiques BC1 (petite boîte carton dim/poids/coût) | Alain | Bloquant scénario S11 |
| ~~Activation DROM-COM~~ | ✅ activé MVP 2026-05-30, hors gratuité, frais réels affichés | Résolu (devis Colissimo Outre-Mer à fournir) |
| Devis Colissimo Outre-Mer (DROM-COM) par destination | Alain | Bloquant config WC DROM-COM |
| Seuil gratuité France 55 € — paramétrage WC `min_amount` | Alain (Lot 1) | Différé Lot 1 |
| Italie : confirmer mode livraison domicile vs MR point relais | Alain | Bloquant config WC Italie |
| Option MR proposée client : surcoût symbolique ou même tarif que Colissimo ? | Alain | Bloquant config WC |
| Validation marge 6 % post-données réelles | Alain (post-launch observation) | Non-bloquant lancement |
| Choix plugin Colissimo officiel oui/non Lot 1 | Alain (Lot 1) | Différé Lot 1 |
| Choix plugin Mondial Relay officiel oui/non Lot 1 | Alain (Lot 1) | Différé Lot 1 |

## 8. Hors scope KH-012

- ❌ USA = KH-015 séparé
- ❌ UK = non activé MVP
- ❌ B2B/B2G shipping = chantier séparé palette/fret
- ❌ Configuration WordPress / WooCommerce = Lot 1
- ❌ Plugins effectivement installés = Lot 1
- ❌ DHL / UPS / FedEx / Chronopost express = phase 2 (sauf exception USA fallback bornée KH-015)
- ❌ Zonos DDP = phase 2

## 9. Liens

- Note cadrage parent : [KH-012-KH-015-shipping-usa-seo.md](KH-012-KH-015-shipping-usa-seo.md)
- Template shipping SKU complet : [KH-012-shipping-data-template.csv](KH-012-shipping-data-template.csv) + [.md](KH-012-shipping-data-template.md)
- Grille prioritaire 5 cas pilotes : [KH-012-tarifs-collecte-prioritaire.csv](KH-012-tarifs-collecte-prioritaire.csv)
- Tests Colissimo USA : [KH-015-tests-colissimo-usa.csv](KH-015-tests-colissimo-usa.csv) + [.md](KH-015-tests-colissimo-usa.md)
- Catalogue : [../../catalog/README.md](../../catalog/README.md)
- KH-017 CGV §7 délais : [KH-017-documents-legaux/03-CGV-B2C-FR.md](KH-017-documents-legaux/03-CGV-B2C-FR.md)

## 10. Statut

🔄 **EN COURS AVANCÉ** — tarifs transporteurs principaux intégrés 2026-05-30 + politique livraison France 55 € intégrée 2026-05-30.

### Politique livraison France

**Formulation publique validée (verrouillée 2026-05-30)** :

> Livraison offerte en France métropolitaine à partir de 55 € d'achat.
> Les DROM-COM, l'Union européenne et l'international ne sont pas concernés par cette gratuité.

❌ Ne pas ajouter publiquement "En dessous de 55 €, une participation aux frais de livraison est appliquée." Information implicite via frais checkout WC.

### Cohérence KH-017

KH-017 CGV §3 mentionne *"Pour les livraisons en France métropolitaine, la livraison est gratuite si cette option est maintenue au moment de la commande."* — wording **générique compatible** avec seuil 55 € (option = au-delà seuil). Pas de modif KH-017 nécessaire MVP. À préciser Lot 1 si Alain souhaite expliciter seuil dans CGV publiquement.

**Acquis** :
- Lettre verte suivie France ≤ 20 g / ≤ 100 g / ≤ 250 g / ≤ 500 g ✅
- Colissimo France domicile + point retrait toutes tranches ✅
- Colissimo International Zone A ≤ 500 g / ≤ 1 kg / ≤ 2 kg ✅ (tranche 750 g = doctrine utiliser ≤ 1 kg)
- Mondial Relay France 3 tranches ✅
- Mondial Relay International BE/LU/ES/IT ≤ 500 g ✅

**Restant** :
- `packaging_handling_cost_eur` (E2 + stickers + flyers + inserts) — choix fournisseurs Alain
- Caractéristiques BC1 (petite boîte au-delà E2)
- MR International NL/DE/PT/PL si zones activées plus tard
- Mode livraison Italie (domicile vs point relais)
- Arbitrage final UE (Colissimo vs MR par pays) avant config WC

Bascule **PRÊT CONFIG WC Lot 1** dès que packaging/handling confirmé + BC1 caractérisé + arbitrage UE final.

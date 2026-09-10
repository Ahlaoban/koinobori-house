# KH-012 + KH-015 — Cadrage shipping, USA landed cost, doctrine Etsy / SEO

Document de cadrage Lot 0. **Aucune décision opérationnelle prise ici** ne se concrétise tant que les données manquantes (tarifs transporteurs réels, poids/format colis, intégration plugin) ne sont pas saisies par Alain. Note documentaire structurée.

- **Ticket** : KH-012 (Transporteurs + grilles tarifaires) + KH-015 (USA shipping, duties & landed cost)
- **Date** : 2026-05-28
- **Auteur** : session Claude / Alain
- **Statut** : ouvert, en attente collecte données

## 1. Contexte verrouillé

- ~25 % clients Etsy actuels = US. Marché clé.
- Sur Etsy US : produit + frais postaux + droits **réglés upfront**, expérience fluide.
- Koinobori House ne doit pas **cannibaliser** Etsy brutalement mais **augmenter revenus US via canal propriétaire**.
- Etsy = marketplace découverte (reste actif).
- Koinobori House = canal propriétaire (marque, collection, histoire, confiance, relation BCDG).
- Devise MVP : **EUR uniquement**, pas multi-devise.
- Livraison France métropolitaine offerte à partir de 55 € d'achat (décision verrouillée 2026-05-30 ; sous le seuil, frais réels).
- Prix produit `price_eur` **hors** livraison internationale et **hors** droits/taxes import.

## 1bis. Réserve douanière (verrouillée)

**HTSUS code 6307.90 et barème indicatif ~10–15 % restent des hypothèses internes** utilisées uniquement pour dimensionnement opérationnel, devis transporteur, prévision marge.

❌ **Ne JAMAIS exposer ces valeurs comme texte public client** (checkout, FAQ, page USA, emails, CGV) tant qu'elles ne sont pas vérifiées par source officielle (base ITC USITC ou conseil douanier).

✅ **Formulation publique obligatoire (prudente)** à utiliser dans tout texte client-facing :

> *"Des droits, taxes ou frais d'importation peuvent être dus selon la réglementation américaine et restent à la charge du client, sauf indication contraire explicite au moment de la commande."*

Variante EN équivalente à drafter :

> *"Import duties, taxes or fees may apply under U.S. regulations and remain the responsibility of the customer, unless explicitly stated otherwise at the time of order."*

**Garde-fou audit pré-publish** (KH-707) :
- grep `6307` dans corpus public → fail si présent
- grep `10-15 %` / `10–15%` dans corpus public → fail si présent
- Vérifier que toute page parlant douane US utilise formulation prudente sans pourcentage ni code HTSUS

## 1ter. Statut USA marchandise — Colissimo en ligne réouvert sous conditions

**Constat factuel actualisé 2026-05-29 (sources officielles La Poste + Service-public)** :

> La page officielle La Poste "Envoyer un colis — États-Unis" indique : *"Les envois de marchandises à destination des États-Unis sont de nouveau possibles via Colissimo sur laposte.fr. Achetez votre affranchissement en ligne et payez vos frais de douanes en quelques clics ici."*

**Conditions actuelles Colissimo USA en ligne** :
- ✅ **Marchandise commerciale USA autorisée** via affranchissement en ligne laposte.fr
- ✅ **Valeur produit < 650 €** par envoi (limite Colissimo en ligne USA)
- ✅ **Droits de douane US payés par l'expéditeur** (Koinobori House) avant entrée territoire américain
- ⚠️ **Territoires associés US** (Porto Rico, Guam, Samoa, Îles Vierges, Mariannes du Nord) restent **temporairement suspendus** si confirmé par La Poste

**Sources** :
- [La Poste — Envoyer un colis aux États-Unis](https://www.laposte.fr/) (page Colissimo USA en ligne)
- [Service Public — réglementation envois postaux USA](https://www.service-public.gouv.fr/particuliers/actualites/A18463)
- [aide.laposte.fr — réglementation envois USA](https://aide.laposte.fr/contenu/quelle-est-la-nouvelle-reglementation-pour-les-envois-vers-les-etats-unis)

### Historique de vigilance (note documentaire)

- 25/08/2025 : suspension initiale Colissimo USA marchandise (réseau postal classique)
- 2026 (date précise à confirmer) : réouverture via Colissimo en ligne avec paiement upfront des droits de douane par expéditeur
- Vigilance : statut réglementaire USA peut évoluer (réglementation EEI, valeurs seuil, taux duties), revérifier source officielle avant chaque campagne d'expédition

### Doctrine USA retenue Alain 2026-05-29 (actualisée)

**Service prioritaire USA** : **Colissimo USA en ligne** (laposte.fr affranchissement en ligne avec paiement droits/taxes upfront).

**Fallback autorisés** uniquement si Colissimo USA en ligne est **trop cher, impossible, instable ou non adapté** :
- Chronopost International USA
- UPS Worldwide Express Saver
- DHL Express Worldwide
- FedEx International Priority / Economy

**Règles d'application** :
- ✅ Tester Colissimo USA en ligne **en premier** sur paniers tests réels
- ✅ Si Colissimo OK → service principal MVP USA
- ✅ Si Colissimo refuse, indisponible ou tarif inadapté → bascule fallback express (un seul retenu cas par cas)
- ❌ Ne plus présenter UPS / DHL / FedEx / Chronopost comme **option première** USA si Colissimo fonctionne
- ❌ Lettre Suivie Internationale **non utilisable** pour marchandise commerciale USA (cf source : courrier international = documents sans valeur marchande uniquement)

### Modèle d'expédition USA — DAP → DDP partiel

Le modèle DAP pur n'est plus applicable côté Colissimo USA : **les droits de douane sont payés par l'expéditeur en avance** lors de l'affranchissement. C'est un modèle **DDP partiel** (Delivered Duty Paid pour le client).

**Implications calcul** :
- `displayed_shipping_price_eur` USA doit inclure : coût Colissimo + frais gestion La Poste + droits/taxes US payés upfront + emballage handling + marge 6 %
- Le client US ne reçoit **pas** de facture customs à l'arrivée (sauf cas exceptionnel)
- Doctrine §1bis formulation prudente "droits à charge client" → **à reformuler** pour USA Colissimo (cf §1bis-USA ci-dessous)

### Décision temporaire USA au lancement

**Statut Alain 2026-05-29** : **Tests Colissimo USA différés**, non bloquants Lot 0.

**Mode par défaut MVP : USA "nous contacter"** tant que tests Colissimo USA en ligne pas réalisés. Site continue avancer autres tickets Lot 0 sans dépendre des tests USA.

| Mode | Condition | Affichage front | Wording USA |
|------|-----------|-----------------|-------------|
| **USA "nous contacter"** (DÉFAUT MVP actuel) | Tests Colissimo USA différés OU coûts Colissimo trop élevés post-test OU paramétrage WC trop complexe OU instabilité réglementaire | Zone USA **désactivée WC**, formulaire contact `/fr/livraison-usa/` + `/en/shipping-to-usa/` | Devis manuel offline au cas par cas |
| **USA actif** (à activer post-tests Alain) | Test réel Colissimo USA en ligne sur 3 paniers (1 × Kaïro 35€ / 1 × Stars & Stripes 49€ / 3 × Kaïro 105€) **stables et acceptables** côté coût + marge | Zone USA cochée WC, Colissimo USA en ligne service principal, tarif manuel saisi par tranche/cas | Page Livraison USA publiée, wording inclusion droits/taxes (§1bis-USA) |

Décision binaire Alain post-tests Colissimo USA en ligne (quand réalisés), modifiable post-launch.

**Conséquence Lot 0** : tests Colissimo USA bloquants **uniquement** pour bascule "USA actif". Pas bloquants pour KH-100+, KH-200+, autres tickets Lot 0 ou Lot 1 ultérieur. Page `/fr/livraison-usa/` + `/en/shipping-to-usa/` peut être draftée immédiatement avec wording "nous contacter".

### §1bis-USA — Formulation publique adaptée DDP partiel

Pour les pages publiques **USA spécifiquement** (Colissimo USA en ligne avec droits upfront) :

✅ **Formulation publique autorisée USA** :

> *"Pour les livraisons vers les États-Unis, les frais d'expédition peuvent inclure les formalités, droits ou frais exigés avant l'entrée sur le territoire américain."*

Variante EN à drafter :

> *"For shipments to the United States, shipping fees may include the formalities, duties or fees required prior to entry into U.S. territory."*

Cette formulation reste **compatible §1bis garde-fou général** (pas de pourcentage public, pas de code HTSUS public). Elle complète sans contredire.

❌ Formulation §1bis DAP générale ("Des droits, taxes ou frais d'importation peuvent être dus...") **non utilisée si Colissimo USA en ligne en mode DDP partiel**. Conservée pour fallback Chronopost/UPS/DHL/FedEx si ces carriers ne sont pas en mode duties paid upfront.

## 2. Zones de livraison MVP

| Zone | Statut MVP | Raison |
|------|------------|--------|
| France métropolitaine | **Actif J0**, livraison **offerte dès 55 € d'achat** (sous le seuil : frais réels) | Décision tarification verrouillée 2026-05-30 |
| France DROM-COM | **À statuer** (surcoût postal îles) | Décision Alain requise — proposer "frais réels" ou différé |
| Union européenne (UE 27) | **Actif J0**, frais réels | Marché secondaire significatif, complexité TVA absorbée par franchise base (art. 293 B CGI) |
| Royaume-Uni | **À statuer** | Post-Brexit : TVA UK + customs (seuil 135 GBP). Si activé : DAP avec mention claire. Recommandation : différé Lot 2+ sauf demande client volume |
| États-Unis | **Mode « nous contacter » par défaut MVP** (zone désactivée WC) ; bascule « USA actif » conditionnée tests Colissimo USA en ligne KH-015 (différés) | Marché clé 25 % CA Etsy actuel |
| Reste du monde (Canada, Australie, Japon, Suisse, etc.) | **Désactivé MVP**, formulaire contact pour devis manuel | Évite complexité douanière + opérationnelle au lancement. Réactivable post-test marché |

**Recommandation MVP** : ouvrir **France + UE 27** dès J0. **USA en mode « nous contacter »** (zone désactivée WC) jusqu'aux tests Colissimo USA en ligne KH-015. UK et reste du monde différés ou via contact manuel.

## 3. Données à collecter (à compléter par Alain — aucune invention)

### 3.1 Poids unitaire koinobori (données réelles Alain)

| Taille | Poids unitaire |
|--------|---------------:|
| 50 cm | **18 g** |
| 75 cm | **30 g** |
| 100 cm | **45 g** |

### 3.2 Emballage B2C — version actuelle provisoire

**Objectifs** :
- Rester sous **3 cm d'épaisseur** lorsque possible
- Permettre livraison en **boîte aux lettres** (économies tarifaires + délais)
- Documentation des dimensions/poids = **version actuelle**, futures enveloppes pourront avoir caractéristiques différentes (à requalifier au cas par cas)

**Enveloppe E1 — petits envois** :
- Format : **18 × 23 cm**, cartonnée
- Poids vide : **30 g**
- Capacité : **1 à 2 koinobori 50 cm**

**Enveloppe E2 — envois moyens (type Amazon)** :
- Format : **23 × 29 cm**, cartonnée
- Poids vide : **47 g**
- Capacité :
  - **3 à 4 koinobori 50 cm**
  - **1 à 3 koinobori 75 cm**
  - **1 à 2 koinobori 100 cm**

**Au-delà de ces quantités** :
- Bascule sur **petites boîtes carton** (à dimensionner)
- Caractéristiques (dim, poids, coût) **inconnues à ce stade** — à compléter ultérieurement, **pas d'invention**

### 3.3 Tableau poids colis B2C couverts (calculé)

Poids total colis = poids koinobori × n + poids enveloppe.

| Cas | Quantité × taille | Enveloppe | Calcul | Poids total |
|-----|-------------------|-----------|--------|------------:|
| 1 | 1 × 50 cm | E1 (30 g) | 18 + 30 | **48 g** |
| 2 | 2 × 50 cm | E1 (30 g) | 36 + 30 | **66 g** |
| 3 | 3 × 50 cm | E2 (47 g) | 54 + 47 | **101 g** |
| 4 | 4 × 50 cm | E2 (47 g) | 72 + 47 | **119 g** |
| 5 | 1 × 75 cm | E2 (47 g) | 30 + 47 | **77 g** |
| 6 | 2 × 75 cm | E2 (47 g) | 60 + 47 | **107 g** |
| 7 | 3 × 75 cm | E2 (47 g) | 90 + 47 | **137 g** |
| 8 | 1 × 100 cm | E2 (47 g) | 45 + 47 | **92 g** |
| 9 | 2 × 100 cm | E2 (47 g) | 90 + 47 | **137 g** |

**Lecture** : tous les cas B2C couverts MVP restent **≤ 137 g**, très inférieur aux paliers tarifaires postaux courants.

**Épaisseur ≤ 3 cm confirmée Alain** pour les 9 cas ci-dessus → **compatibilité boîte aux lettres acquise**. Lettre suivie La Poste accessible sur tous ces cas (sous réserve grammage transporteur).

**Bornes utiles** :
- 1 × 100 cm (Stars & Stripes solo) = 92 g, E2, ≤ 3 cm BAL
- 1 × 75 cm (Kaïro solo) = 77 g, E2, ≤ 3 cm BAL
- Mix maximal couvert : 4 × 50 ou 3 × 75 ou 2 × 100, tous < 150 g, ≤ 3 cm BAL

### 3.4 Transporteurs B2C MVP — verrouillage

**Périmètre verrouillé MVP B2C** : La Poste (principal) + Mondial Relay (point relais France/UE limité). Pas d'express premium.

**Délais estimés d'acheminement après expédition** — formulation publique prudente (à intégrer CGV §7) :

| Zone | Transporteur pressenti | Délai indicatif après expédition | Statut |
|------|------------------------|----------------------------------|--------|
| France métropolitaine / Monaco | Colissimo | 2 jours ouvrables / 48 h | Estimation La Poste, à confirmer config WC |
| Union européenne / Europe | Colissimo International | 3 à 8 jours ouvrés | Estimation prudente La Poste (souvent 3-4 j la plupart pays UE, retenu 3-8 j prudent pour CGV) |
| États-Unis | Colissimo International / offre USA applicable | 5 à 10 jours ouvrés hors douane | Estimation prudente (page La Poste mentionne aussi 2-5 j selon destination, retenu 5-10 j prudent). **Checkout USA non activable avant tests KH-015** |
| International hors UE / hors USA | Colissimo International | 3 à 15 jours ouvrables hors douane | À cadrer si nouvelle zone activée |

**Doctrine délais** :
- ❌ Pas de délai garanti
- ❌ Pas de mention DDP / droits inclus / taxes upfront / absence frais arrivée pour USA tant que tests KH-015 non faits
- ✅ Délais = estimés d'acheminement après expédition uniquement
- ✅ Délai préparation commande = distinct du délai transporteur, mentionné séparément
- ✅ Mention prolongation possible : périodes activité, formalités douanières, perturbations transporteur, événements externes

| Zone | Transporteurs B2C MVP | Règle de routage | Suivi | Délai indicatif après expédition |
|------|----------------------|------------------|-------|--------------------------------|
| France | La Poste **lettre suivie** si épaisseur ≤ 3 cm (boîte aux lettres) ; **Colissimo** si > 3 cm ou hors gabarit BAL ; **Mondial Relay** point relais (option client) | Épaisseur 3 cm + format | La Poste suivie / Colissimo standard / MR point relais | 2 j ouvrables Colissimo (estimation) |
| UE | **Colissimo international** principal ; **Mondial Relay** sur pays desservis (BE, LU, NL, ES, IT, DE notamment) | Idem épaisseur + format | Colissimo suivi / MR suivi point relais | 3 à 8 j ouvrés selon pays |
| USA | **Colissimo international USA** uniquement MVP B2C (cf §1ter) | Pas d'autre transporteur MVP | Colissimo suivi (limité côté US, dépend USPS partenaire) | 5 à 10 j ouvrés hors douane |

**Routage simple boîte aux lettres vs colis** :
- **Cas 1-9 tous éligibles BAL** (épaisseur ≤ 3 cm confirmée Alain pour E1 et E2 dans les configurations couvertes) → La Poste lettre suivie possible côté FR/UE selon grammage transporteur
- Bascule Colissimo uniquement si :
  - Volume / quantité au-delà capacité E1/E2 (petites boîtes carton futures, non MVP)
  - Future enveloppe à dimensions/poids différents (à requalifier)
  - Destination ou option choisie par client impose Colissimo

**Hors MVP B2C — règle générale France + UE** :
- ❌ DHL Express, UPS, FedEx, Chronopost — **non retenus MVP B2C pour France et UE**
- ❌ Zonos DDP — **non retenu MVP**
- Tous candidats express ou DDP → **phase 2 uniquement** si volume justifie post-test marché

**Doctrine USA marchandise** (cf §1ter actualisé) :
- **Service prioritaire** : **Colissimo USA en ligne** (laposte.fr affranchissement en ligne, valeur < 650 €, droits payés upfront expéditeur)
- **Fallback autorisés** uniquement si Colissimo trop cher / impossible / instable / inadapté : Chronopost / UPS / DHL / FedEx
- Choix fallback au cas par cas, coût réel le plus bas + suivi + faisabilité douanière
- Un seul transporteur USA actif à la fois MVP
- Pas extension France/UE, pas affichage standard global
- ❌ Lettre Suivie Internationale non utilisable USA marchandise commerciale

**Données à collecter** auprès de chaque transporteur retenu (La Poste / Colissimo / Mondial Relay) :
- Tarif réel selon poids/dim/épaisseur exacts (post-mesure)
- Frais carburant + surcharges éventuelles
- Frais douane / formalités (USA notamment Colissimo international)
- Assurance incluse / option (Colissimo R+ jusqu'à 500 €)
- Couverture suivi (USA notamment, limites Colissimo / USPS)
- Modalités retour (essentiel pour USA)

**Recommandation méthode** : utiliser **courtier Boxtal** pour comparer tarifs Colissimo / La Poste / MR en une requête + récupérer captures écran horodatées. Boxtal = français + intégration WC native + permet comparaison sans engagement.

### 3.5 B2B / B2G shipping — hors scope MVP B2C

⚠️ **Ne pas appliquer mécaniquement le modèle B2C** aux commandes B2B/B2G.

- Commandes de **plusieurs centaines de koinobori** → besoins distincts (palette ? carton groupé ? expédition fret ?)
- Tests transporteurs **dédiés France + international** nécessaires (Geodis, DHL Freight, Boxtal Pro Volume, transporteurs spécialisés palette)
- Pas de tarification automatique B2B/B2G affichée site
- Devis manuel offline au cas par cas via formulaires captation `/fr/entreprises/` + `/fr/collectivites/` (cf doctrine B2B/B2G captation qualifiée)

**Statut** : chantier shipping B2B/B2G **séparé**, à ouvrir post-MVP B2C ou post-premier projet B2G/B2B concret. Pas de pré-cadrage opérationnel ici.

### 3.6 Coût livraison par zone — placeholders

| Zone | Coût indicatif (à confirmer) | Modèle |
|------|------------------------------|--------|
| France | 0 € si commande ≥ 55 €, sinon frais réels | Offert dès 55 € (verrouillé 2026-05-30) |
| UE | **à fournir** | Forfait par tranche poids/épaisseur ou réel transporteur |
| USA | **à fournir** ⚠️ cf §1ter Colissimo USA suspendu, alternatives à arbitrer | Forfait par tranche poids/épaisseur ou réel transporteur |
| UK | **à fournir** si activé | Forfait par tranche poids/épaisseur |

### 3.7 Tarifs publics 2026 collectés (sources officielles ou agrégateurs)

Données récupérées 2026-05-29 depuis sources publiques. Tous tarifs **à valider Alain** par capture écran horodatée au moment de la commande réelle (variations possibles selon options et compte Pro).

#### La Poste — Lettre verte suivie (France métropole, 2026)

Tranches utiles pour cas B2C couverts :

| Poids | Tarif (Lettre verte suivie) | Source |
|-------|----------------------------:|--------|
| ≤ 20 g | 2,02 € | [SFPF/mysendingbox](https://www.sfpf.fr/actu/tarif-timbre-2026) |
| ≤ 100 g | 3,60 € | idem |
| ≤ 250 g | **non collecté** | à compléter |

Suivi unique +0,50 € déjà inclus tarifs Lettre Suivie. Format BAL ≤ 3 cm + ≤ 2 kg.

#### La Poste — Lettre Suivie Internationale (Monde entier, tarif unique 2026)

⚠️ La Poste a **consolidé en tarif unique monde entier** sans zonage géographique pour Lettre Suivie Internationale (constat 2026).

| Poids | Tarif Monde entier | Source |
|-------|-------------------:|--------|
| ≤ 20 g | 5,05 € | [tarif-lettre.com maj 2026-05-29](https://www.tarif-lettre.com/tarif-lettre-internationale-2026) |
| ≤ 100 g | 7,65 € | idem |
| ≤ 250 g | 14,45 € | idem |
| ≤ 500 g | 19,40 € | idem |
| ≤ 2 kg | 34,50 € | idem |

⚠️ Éligibilité Lettre Suivie Internationale USA pour **marchandise commerciale** depuis suspension Colissimo 25/08/2025 — **à vérifier explicitement** auprès La Poste avant publication (risque que restrictions douanières s'appliquent aussi à Lettre Suivie).

#### Colissimo France (Standard Suivi 2026)

| Poids | Domicile | Point retrait | Source |
|-------|---------:|--------------:|--------|
| ≤ 250 g | 5,49 € | 4,79 € | [tarif-lettre.com maj 2026-05-29](https://www.tarif-lettre.com/tarif-colissimo-2026) |
| ≤ 500 g | 7,59 € | 6,89 € | idem |
| ≤ 750 g | 9,29 € | 8,59 € | idem |
| ≤ 1 kg | 9,59 € | 8,89 € | idem |
| ≤ 2 kg | 11,19 € | 10,49 € | idem |
| ≤ 5 kg | 17,39 € | non collecté | idem |
| ≤ 10 kg | 25,29 € | non collecté | idem |

#### Colissimo International Zone A (UE 27 + Suisse + Royaume-Uni, 2026)

| Poids | Tarif | Source |
|-------|------:|--------|
| ≤ 500 g | 14,99 € | [tarifs-postaux.fr](https://tarifs-postaux.fr/tarif-colissimo.htm) + [tarif-lettre.com maj 2026-05-29](https://www.tarif-lettre.com/tarif-colissimo-2026) |
| ≤ 1 kg | 19,39 € | idem |
| ≤ 2 kg | 22,19 € | idem |
| ≤ 5 kg | 28,59 € | idem |
| ≤ 10 kg | 46,99 € | idem |

Note : tranche 250 g et 750 g international **non visible** sur sources consultées.

#### Colissimo International Zone B (Europe de l'Est, Norvège, Maghreb)

| Poids | Tarif | Source |
|-------|------:|--------|
| ≤ 500 g | 23,79 € | [tarif-lettre.com maj 2026-05-29](https://www.tarif-lettre.com/tarif-colissimo-2026) |
| ≤ 1 kg | 28,39 € | idem |
| ≤ 2 kg | 31,09 € | idem |

#### Colissimo International Zone C (reste du monde — hors USA marchandise suspendue)

| Poids | Tarif | Source |
|-------|------:|--------|
| ≤ 500 g | 35,19 € | [tarif-lettre.com maj 2026-05-29](https://www.tarif-lettre.com/tarif-colissimo-2026) |
| ≤ 1 kg | 39,19 € | idem |
| ≤ 2 kg | 53,99 € | idem |

#### Colissimo USA en ligne — service prioritaire actualisé

✅ **Réouvert sous conditions** (cf §1ter actualisé 2026-05-29) :
- Affranchissement en ligne laposte.fr obligatoire
- Valeur produit < 650 € par envoi
- Droits de douane US payés upfront par expéditeur
- Territoires associés US (Porto Rico, Guam, etc.) restent **suspendus temporairement** si confirmé

| Service | Tarif | Source | Note |
|---------|------:|--------|------|
| Colissimo USA en ligne ≤ 500 g | **devis requis** | [laposte.fr](https://www.laposte.fr/) | Service principal USA marchandise, paiement droits/taxes à l'affranchissement |
| Colissimo USA en ligne ≤ 1 kg | **devis requis** | idem | idem |
| Colissimo USA en ligne ≤ 2 kg | **devis requis** | idem | idem |

⚠️ Tarif Colissimo USA en ligne **affranchissement seul** ≠ tarif total. Total = affranchissement + frais gestion La Poste éventuels + **droits/taxes US payés upfront** (variable selon valeur produit et HTSUS, à observer lors test réel).

#### Alternatives USA fallback (cf §1ter actualisé)

À utiliser **uniquement si Colissimo USA en ligne** est trop cher, impossible, instable ou inadapté. Devis requis :

| Carrier | Service candidat USA | Tarif | Source | Note |
|---------|----------------------|------:|--------|------|
| Chronopost | Chronopost International USA | **devis requis** | [chronopost.fr](https://www.chronopost.fr) | Express La Poste, opérationnel USA marchandise |
| UPS | UPS Worldwide Express Saver | **devis requis** | [ups.com](https://www.ups.com) | Express international, dédouanement complet |
| DHL | DHL Express Worldwide | **devis requis** | [dhl.com](https://www.dhl.com) | Express premium, duties payables dès $1 |
| FedEx | FedEx International Priority / Economy | **devis requis** | [fedex.com](https://www.fedex.com) | Express international |

**Critère choix fallback MVP** : moins cher + fiable + faisabilité douanière. Un seul retenu à la fois.

#### Mondial Relay (particulier, Point Relais → Point Relais, 2026)

| Zone | Tranche poids | Tarif | Source |
|------|---------------|------:|--------|
| France | ≤ 500 g | 4,10 € | [mondialrelay.fr](https://www.mondialrelay.fr/envoi-de-colis/tarifs-expeditions/) |
| France | 500 g – 1 kg, 1-2 kg, etc. | **non collecté** | à compléter Alain |
| Belgique | toutes tranches | **non collecté** | à compléter Alain |
| Luxembourg | toutes tranches | **non collecté** | à compléter Alain |
| Espagne | toutes tranches | **non collecté** | à compléter Alain |
| Pays-Bas | toutes tranches | **non collecté** | à compléter Alain |
| Italie | toutes tranches | **non collecté** | à compléter Alain |
| Portugal | toutes tranches | **non collecté** | à compléter Alain |
| Pologne | toutes tranches | **non collecté** | à compléter Alain |
| USA | — | ❌ non desservi | — |

#### Tarifs applicables aux 5 cas B2C prioritaires (mapping)

| Cas | Poids total | Service France recommandé | Tarif France | Service UE | Tarif UE | Service USA prioritaire (§1ter) | Tarif USA |
|-----|------------:|---------------------------|-------------:|------------|---------:|---------------------------------|----------:|
| 1 — 1×50 cm | 48 g | Lettre verte suivie ≤100g | 3,60 € | Lettre Suivie Internationale ≤100g | 7,65 € | Colissimo USA en ligne — devis | à collecter |
| 2 — 2×50 cm | 66 g | idem | 3,60 € | idem | 7,65 € | idem | à collecter |
| 3 — 1×75 cm | 77 g | idem | 3,60 € | idem | 7,65 € | idem | à collecter |
| 4 — 1×100 cm | 92 g | idem | 3,60 € | idem | 7,65 € | idem | à collecter |
| 5 — 3×75 cm | 137 g | Lettre verte suivie ≤250g (non collecté) ou Colissimo ≤250g 5,49 € | 5,49 € (Colissimo fallback) | Lettre Suivie Internationale ≤250g | 14,45 € | idem | à collecter |

⚠️ Lettre Suivie Internationale **NON applicable USA marchandise commerciale** (réservé documents sans valeur marchande). USA = Colissimo en ligne uniquement (fallback Chronopost/UPS/DHL/FedEx si nécessaire).

**Lecture** :
- Côté France : poids très faibles → Lettre verte suivie domine côté tarif. Colissimo seulement si client préfère réseau colis suivi avancé.
- Côté UE : Lettre Suivie Internationale tarif unique monde compétitif jusqu'à 250 g.
- Côté USA : doctrine actuelle (Colissimo) hors service, choix Alain requis.

### 3.8 Doctrine tarifs MVP — manuel par zone, pas temps réel

**Principe verrouillé MVP** :
- WooCommerce **configuré manuellement** par zone × tranche poids × service
- **Pas de mise à jour temps réel** au MVP
- **Pas de plugin transporteur** au MVP (DHL/UPS/FedEx API, Colissimo API, MR API → toutes phase 2 sauf besoin spécifique)
- Tarifs site **arrondis commercialement** (ex 7,65 € → 7,90 € ou 8 €) pour lisibilité + absorption marge §5bis
- Tarifs **modifiables après observation réelle** des coûts post-launch

**Exception MVP envisageable Lot 1** :
- Plugin Mondial Relay (génération étiquettes + sélection point relais front) — utilité opérationnelle directe
- Plugin Colissimo officiel (génération étiquettes Colissimo Box) — utilité opérationnelle directe

À évaluer Lot 1 selon volume prévu, **pas obligation Lot 0**. Tarifs dynamiques temps réel reste **phase 2** quelle que soit la décision.

**API / plugins transporteurs phase 2 candidats** :
- DHL Express WC plugin (DDP intégré)
- Sendcloud Cross-Border (multi-transporteurs + DDP)
- Boxtal Pro Volume (multi-transporteurs B2B/B2G)
- Zonos (DDP USA si réactivé)

## 4. Options USA landed cost (KH-015)

### Option A — DAP simple (Delivered At Place)

- Client paie produit + frais shipping site
- Droits + taxes à régler à l'arrivée (collectés transporteur ou douane US)
- **Pro** : zéro intégration plugin, zéro coût mensuel, simplicité ops
- **Contre** : risque mauvaise surprise client US → CSAT inférieur à Etsy. Risque "package abandoned" si client refuse customs

### Option B — DDP intégré (Delivered Duty Paid) — préférée si faisable MVP

- Estimation droits + taxes affichée checkout, réglée par client upfront
- Solutions techniques : **Zonos** (plugin WC dédié), Easyship, Sendcloud Cross-Border, Boxtal Pro DDP
- **Pro** : expérience Etsy-équivalente, CSAT US préservé, pas de surprise arrivée
- **Contre** : intégration plugin requise, coût mensuel (Zonos ~$50-200/mois selon plan), calibration HTSUS codes, complexité ops retour customs déclaration
- **Faisabilité MVP solo Alain** : moyen — installation Zonos faisable mais setup HTSUS code + tax mapping = 0,5 à 1 j travail + monitoring continu

### Option C — Estimation manuelle pré-checkout (compromis MVP)

- Page `/livraison-usa/` avec **mention prudente droits/taxes import** (formulation §1bis)
- ⚠️ HTSUS 6307.90 + barème ~10-15 % = **hypothèse interne uniquement**, pas affichée publiquement tant que non vérifiée source officielle
- Pas d'intégration plugin
- Mention checkout : formulation prudente §1bis (pas de pourcentage public)
- **Pro** : transparence sans engagement chiffré erroné, zéro intégration, zéro coût mensuel
- **Contre** : moins fluide que Etsy, charge mentale client US déplacée

### Option D — DDP différé phase 2

- MVP en DAP transparence radicale (= option C)
- Phase 2 (post-validation marché US) intègre Zonos ou équivalent
- **Pro** : MVP simple, capex zéro
- **Contre** : période MVP avec CSAT potentiellement dégradé

## 5. Recommandation MVP (proposition Claude — à valider Alain)

### Solution recommandée : **Option C + planification Option B post-test**

**Pour J0** : DDP partiel via Colissimo USA en ligne, formulation publique adaptée (cf §1bis-USA)
- Page dédiée USA `/fr/livraison-usa/` + `/en/shipping-to-usa/`
- Texte public USA : **formulation §1bis-USA** "frais d'expédition peuvent inclure formalités/droits/frais avant entrée territoire US" (pas de pourcentage public, pas de code HTSUS public)
- Travail interne : HTSUS 6307.90 candidat conservé pour estimation devis transporteur + paiement Colissimo en ligne droits upfront
- Mention checkout + emails de confirmation utilisant formulation §1bis-USA (inclusion droits)
- **Service USA prioritaire** : **Colissimo USA en ligne** (valeur < 650 €, droits/taxes payés upfront par expéditeur)
- **Fallback USA** : Chronopost / UPS / DHL / FedEx si Colissimo trop cher/impossible/instable/inadapté (un seul retenu à la fois selon coût/fiabilité)
- Suivi inclus Colissimo + transporteurs fallback
- Assurance Colissimo R+ recommandée (valeur produit ≥ 35 €, fragile décoratif)
- ❌ Doctrine USA **ne s'étend pas à France ni UE** : doctrine générale "pas d'express international par défaut" maintenue France/UE
- ❌ Zonos DDP → phase 2 uniquement
- ❌ Lettre Suivie Internationale → **non utilisable USA marchandise commerciale**

**Pour Lot 2+ post-test marché (3-6 mois post-launch)** :
- Évaluer volume USA réel
- Si volume justifie : intégrer **Zonos** (Option B) pour expérience DDP Etsy-équivalente
- Garder Option C en fallback pour autres zones

### Textes à afficher (drafts indicatifs, à finaliser KH-204 wording)

**Checkout (US selected) — Colissimo USA en ligne (mode DDP partiel)** — formulation §1bis-USA :
> *"Pour les livraisons vers les États-Unis, les frais d'expédition peuvent inclure les formalités, droits ou frais exigés avant l'entrée sur le territoire américain."*

EN :
> *"For shipments to the United States, shipping fees may include the formalities, duties or fees required prior to entry into U.S. territory."*

**Checkout (UK / autres zones hors UE)** — formulation §1bis DAP générale :
> *"Pour les livraisons hors Union européenne (notamment Royaume-Uni), des droits, taxes ou frais d'importation peuvent s'appliquer à l'arrivée. Ils restent à la charge du destinataire et ne sont pas inclus dans le prix produit ni les frais de livraison affichés sur le site, sauf indication contraire explicite au moment de la commande."*

**CGV section livraison internationale** :
> *"Pour les livraisons hors Union européenne : (a) vers les États-Unis, les frais d'expédition affichés peuvent inclure les formalités, droits ou frais exigés avant l'entrée sur le territoire américain ; (b) vers les autres destinations (Royaume-Uni, etc.), des droits, taxes ou frais d'importation peuvent s'appliquer à l'arrivée et restent à la charge du destinataire. Sauf indication contraire explicite au moment de la commande."*

**Variantes EN** à drafter KH-204 sur même séparation USA (DDP partiel) vs autres (DAP).

**Page Livraison USA** (FAQ structure) : voir §6 ci-dessous.

❌ Aucun pourcentage public. ❌ Aucun code HTSUS public. Tant que non vérifiés source officielle.

### Risques résiduels Option C

| Risque | Impact | Mitigation |
|--------|--------|------------|
| CSAT US dégradé vs Etsy | Moyen | Transparence radicale + mention claire checkout + suivi soigné |
| Package abandoned à douane | Faible volume mais coûteux | Politique retour explicite + assurance Colissimo R+ |
| Retours US complexes / coûteux | Élevé si volume | Politique retour MVP : retour à charge client OU non accepté hors défaut produit (à trancher KH-013) |
| Confusion prix Etsy vs site | Moyen | Pas de comparaison frontale, doctrine Etsy intégrée (§7) |
| Volume USA insuffisant pour justifier Zonos plus tard | Risque business | Suivre indicateurs : nb commandes US/mois, taux retour, CSAT |
| ⚠️ Colissimo USA suspendu (cf §1ter) | **Bloquant ops USA** | Arbitrage Alain options A/B/C/D §1ter |

## 5bis. Marge de traitement international (formule interne)

**Décision Alain 2026-05-29** :

Pour les livraisons **hors France**, les frais d'expédition affichés au client doivent intégrer une marge couvrant les coûts indirects internationaux :
- Emballage (au-delà coût brut enveloppe)
- Traitement manuel commande
- Risque international (perte, retour, litige)
- Coût administratif (déclarations douanières, suivi exceptions)
- Frais moyens de transaction Stripe / PayPal (impact marge)

### Formule interne (non affichée au client)

```
frais_livraison_internationale_affichés =
    coût_transporteur
  + coût_emballage_traitement
  + 6 % × (prix_produits + coût_transporteur)
```

**Taux 6 %** retenu MVP, plus prudent que hypothèse 4 % envisagée initialement. Justifié par cumul Stripe/PayPal (~2-3 %) + change EUR/USD éventuel + risque international + administratif. **Modifiable après observation réelle** des coûts post-launch.

### Exemple interne (illustration)

- Produits commandés : 105 € (ex 3 × Kaïro 75 cm + 1 × Stars & Stripes 100 cm hypothétique)
- Coût transporteur réel : 14 € (ex Lettre Suivie Internationale ≤250g + supplément emballage handling)
- Base de calcul marge : 105 + 14 = 119 €
- Marge 6 % : 119 × 0,06 = 7,14 €
- **Livraison affichée** : 14 + 7,14 = 21,14 € → arrondi commercial **22 €** ou **21 €** selon politique

### France métropolitaine

- **Livraison offerte à partir de 55 € d'achat** (décision verrouillée Alain 2026-05-30, cf [KH-012-incentives-france-europe.md](KH-012-incentives-france-europe.md))
- Marge 6 % **non appliquée** à la France métropolitaine
- Coûts indirects France absorbés par marge produit globale (COGS ~3,5 € sur Kaïro 35 € en commandes 200 pièces)
- DROM-COM **non concernés** par la gratuité — frais réels affichés

### Incentive USA verrouillée (cf §1ter + KH-012-incentives-france-europe.md §1)

- Contribution Koinobori House **à partir de 2 koinobori**, règle `contribution = min(grille_quantité, grille_montant)`
- Activation conditionnée tests Colissimo USA en ligne KH-015
- Contribution USA **ne dispense pas** de la formule marge 6 % sur frais transporteur, mais réduit le montant net facturé client

### DROM-COM / UE / UK / Suisse / Europe hors UE — report explicite

- Pas d'incentive automatique MVP (décision Alain via Phronesis 2026-05-30)
- Formule marge 6 % appliquée aux zones activées (DROM-COM, UE) sans contribution additionnelle
- UK/Suisse/Europe hors UE = mode « nous contacter »
- Horizon révision : T+12 à T+18 mois post-launch (cf [KH-012-incentives-france-europe.md §11](KH-012-incentives-france-europe.md))

### Wording client — INTERDIT explicite

❌ **JAMAIS** afficher publiquement cette marge comme :
- "frais Stripe"
- "frais PayPal"
- "frais de moyen de paiement"
- "commission carte bancaire"
- "marge handling"
- Toute formulation laissant deviner la décomposition interne

✅ **Wording autorisé client** :
- "Frais de livraison internationale" (FR)
- "Frais d'expédition" (FR)
- "Shipping and handling" (EN)
- "International shipping" (EN)

Le client voit **un seul tarif consolidé**, jamais détaillé. Interdiction figure dans audit pré-publish KH-707 :
- grep `Stripe` / `PayPal` / `commission` dans textes shipping public → fail si présent

### Garde-fou Stripe interdiction

- ❌ Aucun texte boutique / checkout / CGV / FAQ / email ne doit mentionner Stripe ou PayPal comme composant des frais de livraison
- ✅ Stripe et PayPal restent visibles uniquement comme **moyens de paiement** à l'étape paiement, jamais associés au calcul shipping

## 6. Page USA à prévoir (Lot 1+)

**URL** :
- `/fr/livraison-usa/`
- `/en/shipping-to-usa/`

**Sections obligatoires** :

1. **Délais estimatifs** : Colissimo international USA 7-15 j ouvrés (transporteur MVP B2C unique USA)
2. **Frais de livraison** : forfait ou barème par tranche poids/zone (à compléter)
3. **Droits, taxes et frais d'importation** :
   - **Formulation publique prudente** (§1bis) : *"Des droits, taxes ou frais d'importation peuvent être dus selon la réglementation américaine et restent à la charge du client, sauf indication contraire explicite au moment de la commande."*
   - Renvoi générique aux règles US Customs and Border Protection (CBP) — pas de pourcentage ni code HTSUS public
   - Lien officiel CBP (à fournir Lot 1+ après vérification)
   - Charge destinataire à l'arrivée
   - ⚠️ Internal note : HTSUS 6307.90 candidat + barème ~10-15 % conservés en hypothèse interne uniquement pour devis transporteur, jamais publiés tant que non vérifiés
4. **Retours** : politique retour US, coût retour, modalités
5. **Suivi** : explication numéro tracking transporteur
6. **FAQ** : questions fréquentes (différence Etsy/site, paiement, devise, etc.)

**Doctrine wording** :
- **Aucune comparaison frontale avec Etsy** (cf §7 doctrine Etsy)
- Voix éditoriale marque Koinobori House : honnêteté, transparence, soin
- Pas de mention publique de la Chine, dans aucun contexte (cf doctrine éditoriale impérative ; l'exception bornée B2G est levée depuis le 2026-09-08)

## 7. Doctrine Etsy / SEO intégrée

### 7.1 Principes verrouillés

**Etsy** = canal marketplace découverte, reste actif. Trafic Etsy → Etsy.
**Koinobori House** = canal propriétaire marque. Trafic koinoborihouse.com → expérience marque.

Les deux **coexistent**, ne se cannibalisent pas frontalement.

### 7.2 Interdits absolus

- ❌ **Jamais** reprendre mot pour mot les titres Etsy sur fiches Koinobori House
- ❌ **Jamais** reprendre mot pour mot les descriptions Etsy
- ❌ **Jamais** copier les tags Etsy identiques
- ❌ **Jamais** reproduire la structure complète des fiches Etsy
- ❌ **Jamais** comparaison frontale prix Etsy vs site
- ❌ **Jamais** encourager détournement Etsy → site explicite (style "moins cher ici")

### 7.3 Voix Koinobori House — fiches "maison de marque"

Chaque fiche Koinobori House doit articuler 7 dimensions différenciantes vs Etsy :

1. **Collection** — appartenance à Mer / Motifs / Hanami / Kaïro / Territoires
2. **Histoire** — narratif marque, personnage Kaïro (cf doctrine Kaïro README catalog), origine BCDG
3. **Usage** — contexte décoratif détaillé (jardin, intérieur, événement, installation)
4. **Confiance** — signature BCDG, série / édition (2 blocs wording ; plus de bloc transparence/atelier)
5. **BCDG** — créateur, démarche artistique, processus
6. **Contexte décoratif** — atmosphère, émotion, esthétique
7. **Livraison + retours** — clarté absolue, transparence US notamment

### 7.4 SEO Lot 1+ — données structurées Product

Vérifications à faire lors implémentation RankMath + WC (Lot 1+) :

**Schema.org/Product (RankMath auto-génère partiellement)** :
- `name` — model_name_fr / model_name_en
- `sku` — SKU master.csv
- `brand` — Koinobori House + créateur BCDG (Person schema imbriqué)
- `image` — main_image_path + gallery_paths
- `description` — long_desc_fr / long_desc_en (2 blocs)
- `offers` :
  - `price` — price_eur
  - `priceCurrency` — `EUR`
  - `availability` — `InStock` si stock_qty > 0
  - `url` — URL canonique fiche FR ou EN
  - `priceValidUntil` — date future raisonnable
- `shippingDetails` (Google encourage 2023+) : zones + délais + frais
- `hasMerchantReturnPolicy` : politique retour structurée

**Indexation bilingue** :
- Hreflang FR-FR + EN + x-default=EN (cf project_koinobori_i18n_mvp_scope)
- Sitemap séparé par langue
- Canonical par langue, jamais cross-lang

**Merchant Center / Google Shopping** (Lot 5+ probable) :
- Flux produit XML ou CSV via RankMath Pro ou Product Feed Pro
- Champs requis : id, title, description, link, image_link, availability, price, brand
- Compte Google Merchant Center à créer si décision activée
- Cohérence prix Etsy / site à vérifier (politique Google : pas d'écart trompeur)

### 7.5 SEO concurrentiel — précautions

- **Ne pas viser exact-match queries Etsy** (risque cannibalisation et duplicate content perçu)
- **Cibler queries marque + lifestyle** : "koinobori décoratif design", "carpe drapeau original signé", "art textile japon collectivité"
- **Long-tail unique** : "koinobori jumelage France Japon collectivité", "édition spéciale Americana décoration"
- **Backlinks** : viser presse design, lifestyle, médias culturels France/Japon — pas marketplaces concurrentes

## 8. Risques ouverts (synthèse)

| Risque | Probabilité | Impact | Statut |
|--------|-------------|--------|--------|
| CSAT US dégradé MVP DAP | Moyen | Moyen | Mitigé par transparence radicale Option C |
| Cannibalization Etsy | Faible-Moyen | Moyen | Mitigé par doctrine §7 + queries différenciées |
| Volume USA insuffisant Lot 2 Zonos | Moyen | Faible | Suivi indicateurs post-launch |
| Retours US coûteux | Moyen | Élevé unitaire | À trancher KH-013 politique retour |
| Confusion prix Etsy ↔ site | Moyen | Faible | Pas d'affichage comparatif |
| Customs package abandoned | Faible | Élevé unitaire | Assurance + suivi |
| Doctrine wording erronée (Chine production fuite) | Faible | Élevé réputationnel | Audit grep KH-707 + relecture humaine |

## 9. Données manquantes (à compléter avant KH-012/KH-015 finalisables)

**Acquis** :
- Poids unitaire koinobori : 18/30/45 g (50/75/100 cm)
- Emballages B2C actuels : E1 18×23 (30 g), E2 23×29 type Amazon (47 g)
- Capacité par enveloppe documentée §3.2
- Poids colis calculés 9 cas couverts (48 g à 137 g)
- **Épaisseur ≤ 3 cm confirmée Alain** pour les 9 cas → compatibilité BAL acquise, plus de mesure à faire sur configurations actuelles

**Restant à obtenir** :

| Item | Owner | Priorité |
|------|-------|----------|
| **Test Colissimo USA en ligne** sur 3 paniers (1×Kaïro 35€ / 1×Stars & Stripes 49€ / 3×Kaïro 105€) — affranchissement + droits/taxes simulés | Alain (laposte.fr) | **Bloquant USA actif vs nous contacter** |
| **Décision USA actif vs "nous contacter"** post-test Colissimo en ligne | Alain | Bloquant config WC USA |
| Vérification statut territoires associés US (Porto Rico, Guam, etc.) suspendus ou non | Alain (La Poste) | Bloquant carve-out destinations USA |
| Devis fallback Chronopost International USA pour 3 cas | Alain (chronopost.fr ou Pro) | Bloquant fallback si Colissimo inadapté |
| Devis fallback UPS Worldwide pour 3 cas | Alain (ups.com ou Pro) | Bloquant fallback |
| Devis fallback DHL Express pour 3 cas | Alain (dhl.com ou Pro) | Bloquant fallback |
| Devis fallback FedEx International pour 3 cas | Alain (fedex.com ou Pro) | Bloquant fallback |
| Tarif La Poste Lettre verte suivie tranche ≤ 250 g France | Alain (web ou capture) | Bloquant cas 5 France |
| Tarifs Mondial Relay France tranches 500g-1kg-2kg | Alain (devis web) | Bloquant si MR retenu |
| Tarifs Mondial Relay par pays UE (BE, LU, ES, PT, NL, IT, PL) | Alain (devis web) | Bloquant option MR UE |
| Tarifs Colissimo International Zone A pays référence détaillés (UE 27 + UK + CH) | Alain (devis Colissimo Pro) | Validation des prix tabulés §3.7 |
| Tarifs Chronopost International USA si Option B §1ter retenue | Alain (devis Chronopost) | Bloquant si Option B |
| Tarifs DHL Express USA si Option C §1ter retenue | Alain (devis DHL) | Bloquant si Option C |
| Code HTSUS exact textile décoratif (chapitre 6307 candidat) | Alain ou conseil douanier | Bloquant page USA publique (formulation prudente §1bis garde-fou jusque-là) |
| Politique retour internationale | Alain (KH-013) | Bloquant CGV |
| Décision DROM-COM activé MVP ou non | Alain | Bloquant zones |
| Décision UK activé MVP ou différé | Alain | Bloquant zones |
| Validation taux marge international (6 % retenu MVP — observer réel post-launch) | Alain (post-données réelles) | Non-bloquant lancement |
| Caractéristiques futures enveloppes provisoires (à requalifier au cas par cas) | Alain | Non-bloquant tant que E1/E2 actuelles couvrent volume |
| Caractéristiques petites boîtes carton (au-delà capacité E2) | Alain | Bloquant si volume commande dépasse limites E2 |
| Chantier shipping B2B/B2G (palette/fret) | Alain post-projet B2B/B2G concret | Hors scope MVP B2C |

## 10. Prochaines actions

1. **Tester Colissimo USA en ligne** sur 3 paniers réels (1×Kaïro 35€ / 1×Stars & Stripes 49€ / 3×Kaïro 105€) — simuler affranchissement laposte.fr + paiement droits/taxes upfront, collecter coût total réel par cas
2. **Décider USA actif vs USA "nous contacter"** post-test Colissimo : si Colissimo stable + acceptable → USA actif WC avec Colissimo USA en ligne service principal ; sinon zone USA désactivée + formulaire contact
3. **Demander devis fallback Chronopost / UPS / DHL / FedEx** uniquement si Colissimo USA en ligne inadapté (trop cher / impossible / instable)
4. **Vérifier statut territoires associés US** (Porto Rico, Guam, Samoa, Îles Vierges, Mariannes du Nord) auprès La Poste — carve-out destinations potentiel
4. **Compléter tranches manquantes** §3.7 (Lettre verte suivie ≤ 250 g France, Mondial Relay tranches & pays, Colissimo Zone A 250 g/750 g si dispo)
5. **Saisir tarifs réels validés** dans [KH-012-tarifs-collecte-prioritaire.csv](KH-012-tarifs-collecte-prioritaire.csv) + [KH-012-shipping-data-template.csv](KH-012-shipping-data-template.csv) (captures écran horodatées hors-repo)
6. **Vérifier code HTSUS** : base ITC USITC ou conseil douanier (HTSUS 6307.90 candidat probable) → pas de publication publique sans vérification source officielle
7. **Drafter wording page Livraison USA** FR + EN avec formulation prudente §1bis (KH-204 wording) — variantes "USA actif" vs "USA nous contacter" selon décision Alain
8. **Configurer WC manuellement Lot 1** : zones × tranches poids × services × tarifs incluant marge 6 % (cf §5bis)
9. **Trancher politique retour** internationale (KH-013)
10. **Trancher activation UK + DROM-COM**
11. **Documenter caractéristiques futures enveloppes** au fur et à mesure (versions provisoires successives)
12. **Documenter B2B/B2G shipping** comme chantier séparé futur (post-MVP ou post-premier projet concret)

Aucune action engagée hors collecte. Aucun plugin installé. Aucun code écrit. Aucune ligne master.csv modifiée.

## 11. Liens / dépendances

- [[project_koinobori_house_mvp_2026-05-27]] cadrage général
- [[project_koinobori_tarification_tva_mvp]] tarifs + TVA + livraison France offerte dès 55 €
- [[project_koinobori_i18n_mvp_scope]] i18n FR+EN fallback EN
- [[feedback_wording_fiche_produit]] règle 2 blocs description
- CLAUDE.md §USA / douanes critique
- CLAUDE.md §Doctrine éditoriale impérative
- [catalog/README.md](../../catalog/README.md) §Tarification et TVA

# KH-012 — Politique d'incentive shipping MVP (décision verrouillée)

Note Lot 0 — politique d'incentive shipping MVP, **décision Alain arbitrée 2026-05-30 via protocole Phronesis**. Seules les politiques France métropolitaine et USA sont actives. DROM-COM / UE / UK / Suisse / Europe hors UE → **report explicite instrumenté** jusqu'à T+12 à T+18 mois.

- **Ticket** : KH-012 (chantier incentives)
- **Lot** : 0
- **Date arbitrage** : 2026-05-30
- **Statut** : ✅ **DÉCISION VERROUILLÉE MVP**
- **Hors scope** : config WC (Lot 1), code, plugins, master.csv, prix produits

## 1. Politique USA — verrouillée (ne pas rouvrir)

Contribution Koinobori House aux frais d'expédition USA **uniquement à partir de 2 koinobori**.

Règle générale : `contribution_USA = min(grille_quantité, grille_montant_produits)`

**Grille quantité** :
| Quantité | Contribution |
|---------:|-------------:|
| 1 koi | 0 € |
| 2 koi | 15 € |
| 3 koi | 20 € |
| ≥ 4 koi | 30 € |

**Grille montant produits hors livraison** :
| Montant | Contribution |
|---------|-------------:|
| < 70 € | 0 € |
| 70 – 99 € | 15 € |
| 100 – 129 € | 20 € |
| ≥ 130 € | 30 € |

### 1.1 Exemples d'application

**Exemple standard** : panier USA = 1 Stars & Stripes 49 € + 1 Kaïro 35 € + 1 koi simple 20 € = **104 €**, 3 koi.
- Grille quantité (3 koi) = 20 €
- Grille montant (100-129 €) = 20 €
- **Contribution appliquée = min(20, 20) = 20 €**

**Exemple garde-fou (anti-abus volume bas-prix)** : panier USA = 4 koi simples 20 € = **80 €**, 4 koi.
- Grille quantité (≥ 4 koi) = 30 €
- Grille montant (70-99 €) = 15 €
- **Contribution appliquée = min(30, 15) = 15 €**
- ✅ Évite prise en charge 30 € sur panier produit faible

### 1.2 Wording USA (verrouillé)

- ✅ « contribution aux frais d'expédition internationale » / « US shipping contribution »
- ❌ jamais « frais Stripe / PayPal »
- ❌ jamais « droits de douane offerts »
- ❌ jamais « livraison gratuite USA »

### 1.3 Activation USA conditionnée KH-015

Politique USA contribution **activable uniquement après réalisation des 3 tests Colissimo USA en ligne** (T1/T2/T3 KH-015). Tant que tests non faits : USA reste mode « nous contacter ».

## 2. Politique France métropolitaine — verrouillée 2026-05-30

**Décision Alain (verrouillée)** :

> Livraison offerte en France métropolitaine à partir de **55 € d'achat**.
> Les DROM-COM, l'Union européenne et l'international ne sont pas concernés par cette gratuité.

❌ Ne pas utiliser 70 € comme seuil.
❌ Ne pas ajouter publiquement « En dessous de 55 €, une participation aux frais de livraison est appliquée. ». Information implicite via frais checkout WC.

### 2.1 Analyse marge seuil 55 €

Hypothèse marge produit brute typique (à valider Alain) :
- Kaïro 35 € : COGS estim ~14 € (~40 %), marge brute ~21 €
- Stars & Stripes 49 € : COGS estim ~20 €, marge brute ~29 €
- Autres koi 20 / 30 / 35 / 49 € : COGS proportionnel

Coût transporteur France (relevés Alain) :
- Lettre suivie ≤ 100 g : 3,60 €
- Lettre suivie ≤ 250 g : 5,74 €
- Lettre suivie ≤ 500 g : 7,91 €
- Colissimo France ≤ 500 g : 7,59 € (domicile) / 6,89 € (retrait)
- Mondial Relay France ≤ 500 g : 4,40 €

**Cas marge minimum panier 55 € (mono-produit + complément 20 €)** :
- 1 Kaïro 35 € + 1 koi 20 € : poids 30 + 18 + 30 (E1) = 78 g → Lettre suivie ≤ 100 g 3,60 €
- Marge brute panier ~21 + 8 = 29 € (hypothèse marge ~14 € sur koi 20 €)
- Marge nette après shipping : 29 - 3,60 = **25,40 €** ≈ 46 % du PA
- ✅ Acceptable

**Cas marge plus tendu panier 55 € (multi-unités lourd)** :
- Ex 2 Kaïro 35 € = 70 € → > seuil. Pas critique.
- Ex 1 S&S 49 € seul = 49 € → sous seuil, frais affichés client.
- Ex 1 S&S 49 € + 1 koi 20 € = 69 € → gratuit. Poids 45 + 18 + 47 (E2) = 110 g → Lettre suivie ≤ 250 g 5,74 €. Marge brute ~37 €, nette 31 €. ✅

**Risque** : panier exactement 55 € avec 4 × 50 cm = 4 × 20 € = 80 € (au-dessus seuil) ou 2 × 30 € = 60 € (au-dessus). Très peu de cas où panier exactement 55 € + colis lourd → marge dégradée. **Acceptable au MVP**.

### 2.2 Vérification panier minimum gratuité (seuil bien calibré ?)

- Panier 1 Kaïro 35 € : sous seuil → frais affichés → **incentive upsell vers 55 €** = ajouter 20 €
- Panier 1 S&S 49 € : sous seuil → upsell léger (+6 € minimum pour atteindre 55 €) → ajouter 1 koi 20 € → 69 € (au-dessus seuil)
- Panier 2 Kaïro 70 € : déjà gratuit, pas d'effet upsell perdu
- Panier 1 koi 20 € : très en-dessous, upsell important (+35 €)

**Conclusion** : seuil 55 € **pertinent** comme incentive sans dégrader marge significativement. Pousse essentiellement vers panier 2 unités, alignement bon avec catalogue.

**Alerte légère** : si Kaïro 35 € reste produit dominant, seuil 55 € incite +1 koi 20 € (rare au catalogue actuel). Si pas de koi 20 € disponible, seuil incite vers +1 Kaïro 35 € = panier 70 € (overshoot bénéfique).

### 2.3 Confirmation

✅ **Seuil 55 € maintenu et confirmé** sauf alerte forte ultérieure (panier moyen réel post-launch < 55 € avec faible % atteinte seuil).

Réévaluation post-launch après 3-6 mois données réelles.

## 3. Politique DROM-COM / UE / UK / Suisse / Europe hors UE — report explicite verrouillé

### 3.1 Décision Alain 2026-05-30

**Aucune incitation automatique au MVP pour DROM-COM, UE, UK, Suisse, Europe hors UE.**

- ❌ Pas de seuil gratuité UE
- ❌ Pas de contribution DROM-COM
- ❌ Pas de contribution UK / Suisse / hors UE
- ❌ Pas généralisation prématurée à zones non documentées

Ces zones restent en **frais réels** (DROM-COM, UE activée), **devis manuel** ou **politique standard** selon configuration shipping, sans incentive ajoutée.

### 3.2 Posture Phronesis retenue

> Agir dans la direction du report explicite, à condition que les politiques France métropolitaine (55 €) et USA (grille min(quantité, montant)) restent verrouillées, en évitant toute généralisation prématurée d'incitations à des zones non documentées.

### 3.3 Justification

- Décider sans données réelles sur 5 zones simultanément multiplierait les hypothèses fragiles
- Asymétrie coûts erreur : retirer une gratuité annoncée coûte plus cher en réputation que l'introduire plus tard
- Statu quo « frais réels affichés » est révisable à la hausse (incentive), pas l'inverse
- Capacité solo Alain préservée — zéro charge cognitive supplémentaire sur barèmes multi-zones MVP
- **Donnée structurante** : COGS Kaïro 35 € < 3,5 € unitaire (commande 200 pièces, Alain 2026-05-30). Marge brute ≈ 90 %. La contrainte n'est pas marge, c'est capacité opérationnelle solo + risque douanier/réputationnel + lisibilité commerciale

### 3.4 Interdits dérivés (2026-05-30 → révision T+12 à T+18 mois)

- ❌ Pas verrouiller publiquement de seuil DROM-COM / UE / UK / Suisse / Europe hors UE
- ❌ Pas annoncer en com (newsletter, accueil, fiches produits) « livraison offerte Europe » ou équivalent
- ❌ Pas configurer WC Lot 1 règles gratuité conditionnelle hors France métropolitaine
- ❌ Pas ouvrir UK / Suisse / Europe hors UE au checkout MVP — rester « nous contacter »

### 3.5 Tarifs transporteurs zones reportées (référence config WC Lot 1 frais réels)

Pour rappel — tarifs déjà collectés ou relevés Alain, utilisés sans modification ni incitation :

- DROM-COM : Colissimo Outre-Mer par destination → **devis Alain à fournir** (config WC frais réels)
- Colissimo Zone A UE (UE + CH + UK) : ≤ 500 g = 14,99 € / ≤ 1 kg = 19,39 € / ≤ 2 kg = 22,19 €
- Mondial Relay BE/LU ≤ 500 g : 4,55 € ; ES : 6,80 € ; IT : 11,20 € (livraison domicile)
- Colissimo Zone B (Europe Est, Norvège, Maghreb) : ≤ 500 g = 23,79 € / ≤ 1 kg = 28,39 € / ≤ 2 kg = 31,09 €

### 3.6 Risque résiduel non couvrable

Si concurrent direct (Etsy compris) installe politique d'incitation visible et clients européens annulent commandes Koinobori House pour ce motif, écart se mesure difficilement (clients qui partent ne se manifestent pas). **Non couvrable au MVP sans données.**

## 4. (section absorbée dans §3) — Union européenne

Voir §3 : UE inclus dans le report explicite verrouillé, pas de politique d'incentive automatique MVP. Frais réels Colissimo Zone A + Mondial Relay BE/LU/ES/IT + marge 6 % via formule §5bis note parent KH-012-KH-015.

**Justification report (rappel)** :
- Tarifs Colissimo Zone A déjà élevés (14,99 €), gratuité ou contribution dégrade trop la marge produit
- Comportement client UE moins déclencheur shipping vs US (habitué frais international)
- Simplicité config WC MVP
- Réévaluable post-launch si conversion UE faible

**Réserve** : si Alain souhaite tester incentive UE → **Hypothèse (b) ≥ 90 € contribution 5-7 €** plutôt qu'(c)/(d) (plus simple à expliquer, plus efficace upsell).

Décision finale UE = à valider Alain post-données réelles + packaging_handling confirmé.

## 5. Politique UK / Suisse / Europe hors UE — prudence

### 5.1 Particularités UK

- Post-Brexit : TVA UK applicable (seuil IOSS 135 GBP)
- Customs déclaration obligatoire
- Mode DAP possible (droits/taxes arrivée à charge client) ou IOSS (TVA collectée vendeur)
- Tarif Colissimo Zone A UK = 14,99 € (≤ 500 g) — UK reste Zone A Colissimo

### 5.2 Particularités Suisse

- Hors UE : TVA CH 8,1 % standard, IVA différent
- Customs déclaration obligatoire
- DAP courant
- Tarif Colissimo Zone A Suisse = 14,99 € (Zone A inclut CH)

### 5.3 Particularités Europe hors UE (Norvège, Islande, Liechtenstein, Albanie, Serbie, etc.)

- Hors UE : TVA destination + customs
- Colissimo : selon pays, Zone A ou Zone B (Zone B ≤ 500 g = 23,79 €)
- DAP par défaut

### 5.4 Hypothèses UK / Suisse / Europe hors UE

| Hypothèse | Pro | Contre |
|-----------|-----|--------|
| (a) **Non activé MVP**, formulaire « nous contacter » | Aucun risque douane / TVA / retours | Conversion nulle |
| (b) Frais réels affichés DAP + mention droits arrivée | Possible si client averti | Complexité douane, risque CSAT (mauvaise surprise) |
| (c) Mode IOSS UK (TVA collectée vendeur) | Conforme UK post-Brexit | Inscription IOSS UK + déclarations + plugin |

### 5.5 Recommandation MVP UK / Suisse / Europe hors UE

**Hypothèse (a)** : **non activés au MVP**, formulaire « nous contacter » dédié.

Justification :
- Évite complexité douane/TVA MVP
- Pas de risque CSAT mauvais douane arrivée
- Pas obligation IOSS MVP
- Volume probable faible justifie devis manuel

Réévaluation post-launch si demande UK/Suisse récurrente.

## 6. Tableau récapitulatif décision MVP par zone (verrouillé 2026-05-30)

| Zone | Politique MVP verrouillée | Statut | Source décision |
|------|---------------------------|--------|-----------------|
| **France métropolitaine** | Livraison offerte à partir de **55 €** d'achat | ✅ verrouillé | §2 |
| **USA** | Contribution `min(grille_quantité, grille_montant)` activable post-tests Colissimo USA KH-015 | ✅ verrouillé | §1 |
| **DROM-COM** | Report explicite. Frais réels affichés sans incentive. | 🔒 report MVP | §3 |
| **Union européenne** | Report explicite. Frais réels Colissimo Zone A + MR pays desservis + marge 6 %, sans contribution auto. | 🔒 report MVP | §3 |
| **Royaume-Uni** | Report explicite. Non activé checkout MVP, mode « nous contacter ». | 🔒 report MVP | §3, §5 |
| **Suisse** | Report explicite. Non activé checkout MVP, mode « nous contacter ». | 🔒 report MVP | §3, §5 |
| **Europe hors UE** (Norvège, Islande, etc.) | Report explicite. Non activé checkout MVP, mode « nous contacter ». | 🔒 report MVP | §3, §5 |

**Horizon de révision report** : T+12 à T+18 mois après ouverture checkout MVP (cf §11.4).

## 7. Analyse paniers types

Hypothèses prix produits (rappel) :
- Koi 20 € (gamme basse hypothèse)
- Koi 30 € (gamme intermédiaire)
- Kaïro 35 €
- Stars & Stripes 49 €

| Panier | Composition exemple | Total produits | Atteinte seuil FR 55 € | Atteinte seuils USA |
|--------|---------------------|---------------:|-----------------------|---------------------|
| 1 koi 20 € | 1 koi gamme basse | 20 € | ❌ (–35 €) | 1 koi → 0 € contribution USA |
| 1 koi 30 € | 1 koi gamme intermédiaire | 30 € | ❌ (–25 €) | 1 koi → 0 € |
| 1 Kaïro | 1 × Kaïro 35 € | 35 € | ❌ (–20 €) | 1 koi → 0 € |
| 1 Stars & Stripes | 1 × S&S 49 € | 49 € | ❌ (–6 €) | 1 koi → 0 € |
| ~55 € | 1 Kaïro + 1 koi 20 € OU 1 S&S + 1 koi ~10 (n/a) | 55 € | ✅ seuil | 2 koi → 15 € (quantité) vs 0 € (montant < 70 €) → **min = 0 €** |
| ~70 € | 2 Kaïro = 70 € | 70 € | ✅ | 2 koi → 15 € vs 15 € (70-99) → **min = 15 €** |
| ~90 € | 1 S&S + 1 Kaïro + 1 koi 20 = 104 € OU 3 koi 30 = 90 € | 90 € | ✅ | 3 koi → 20 € vs 15 € (70-99) → **min = 15 €** |
| ~100 € | 2 S&S = 98 € OU 3 Kaïro = 105 € | 100 € | ✅ | 3 koi → 20 € vs 20 € (100-129) → **min = 20 €** |
| ~130 € | 3 Kaïro + 1 koi 25 = 130 € OU 3 S&S = 147 € | 130 € | ✅ | 4 koi → 30 € vs 30 € (≥130) → **min = 30 €** |

**Lecture USA** : règle `min(quantité, montant)` cohérente — couvre les paniers gros volume montant faible OU petit volume montant élevé sans surcontribuer.

**Lecture France** : seuil 55 € atteint dès 2 koi standard ; mono Kaïro ou S&S sous seuil → frais affichés (Lettre suivie 3,60 € ≤ 100 g, Lettre suivie 5,74 € ≤ 250 g, Lettre suivie 7,91 € ≤ 500 g, MR 4,40 €, Colissimo France 5,49 € retrait / 6,89 € retrait > 250 g).

## 8. Tarifs encore nécessaires

| Tarif | Statut | Owner |
|-------|--------|-------|
| Lettre verte suivie France ≤ 20/100/250/500 g | ✅ collecté | — |
| Colissimo France toutes tranches | ✅ collecté | — |
| Mondial Relay France 3 tranches | ✅ relevé Alain 2026-05-30 | — |
| Mondial Relay BE / LU ≤ 500 g | ✅ relevé Alain | — |
| Mondial Relay ES ≤ 500 g | ✅ relevé Alain | — |
| Mondial Relay IT ≤ 500 g | ✅ relevé Alain (livraison domicile) | — |
| Mondial Relay DE / NL / PT / PL | ❌ non collecté | Alain (ne pas élargir auto) |
| Colissimo Zone A UE ≤ 500 g / 1 kg / 2 kg | ✅ collecté | — |
| **Colissimo Outre-Mer (DROM-COM)** par destination | ❌ non collecté | **Alain bloquant DROM-COM** |
| Colissimo UK Zone A | ✅ tarif Zone A 14,99 € applicable | UK non activé MVP |
| Colissimo Suisse Zone A | ✅ tarif Zone A applicable | Suisse non activée MVP |
| Colissimo Zone B (Europe Est, Norvège, Maghreb) | ✅ collecté | Europe hors UE non activée MVP |
| Packaging / handling / stickers / flyers / inserts | ❌ inconnu | **Alain bloquant calcul UE** |

## 9. Wording public proposé FR / EN

### 9.1 France métropolitaine

**FR** :
> Livraison offerte en France métropolitaine à partir de 55 € d'achat.
> Les DROM-COM, l'Union européenne et l'international ne sont pas concernés par cette gratuité.

**EN** :
> Free shipping to mainland France on orders from €55.
> French overseas territories, the European Union and international destinations are not included in this free shipping offer.

### 9.2 DROM-COM

**FR** :
> Livraison vers les DROM-COM : frais d'expédition réels appliqués selon la destination et le poids du colis.

**EN** :
> Shipping to French overseas territories: actual shipping fees apply depending on destination and parcel weight.

### 9.3 Union européenne

**FR** :
> Livraison vers l'Union européenne : frais d'expédition affichés au panier selon la destination et le poids.

**EN** :
> Shipping to the European Union: shipping fees displayed at checkout based on destination and weight.

### 9.4 UK / Suisse / Europe hors UE (mode « nous contacter »)

**FR** :
> Royaume-Uni, Suisse et autres destinations européennes hors Union européenne : merci de nous contacter à contact@koinoborihouse.com pour obtenir un devis personnalisé.

**EN** :
> United Kingdom, Switzerland and other non-EU European destinations: please contact us at contact@koinoborihouse.com for a personalized quote.

### 9.5 USA (rappel validé — ne pas modifier)

**FR** :
> Livraison vers les États-Unis : contribution Koinobori House aux frais d'expédition internationale à partir de 2 koinobori. Détails au panier.

**EN** :
> Shipping to the United States: Koinobori House contribution to international shipping fees from 2 koinobori. Details at checkout.

⚠️ Wording USA conditionné activation checkout USA post-tests KH-015. Tant que checkout USA non activé : mode « nous contacter ».

## 10. Garde-fous transverses

- ❌ Jamais « frais Stripe » / « frais PayPal » / « commission carte bancaire » / « marge handling » publics
- ❌ Jamais « droits de douane offerts » pour USA, UE ou autre
- ❌ Jamais promettre gratuité hors France métropolitaine sans validation Alain explicite
- ❌ DROM-COM **séparés** de France métropolitaine, jamais confondus
- ❌ UK / Suisse / Europe hors UE **séparés** de UE 27
- ❌ Pas de plugin tarifs temps réel API au MVP
- ❌ Pas de prix transporteurs inventés
- ❌ Pas de modif politique USA validée (KH-012 USA + KH-015)
- ✅ Wording autorisé : « Frais de livraison » / « Frais d'expédition » / « Shipping and handling » / « International shipping »
- ✅ DROM-COM séparés via zone WC dédiée
- ✅ UK / Suisse / Europe hors UE séparés via zone WC « nous contacter » dédiée

## 11. Recommandations MVP synthèse

| Zone | Décision MVP | Action Alain |
|------|--------------|--------------|
| France métropolitaine | ✅ Seuil 55 € confirmé verrouillé | Aucune (paramétrage WC Lot 1) |
| DROM-COM | 🟡 Hypothèse (a) frais réels affichés sans gratuité | Devis Colissimo Outre-Mer à fournir |
| UE | 🟡 Hypothèse (a) MVP frais réels + marge 6 %, pas contribution auto | Validation Alain + packaging_handling à fournir |
| UK | 🔴 Non activé MVP, formulaire contact | Aucune |
| Suisse | 🔴 Non activé MVP, formulaire contact | Aucune |
| Europe hors UE | 🔴 Non activé MVP, formulaire contact | Aucune |
| USA | ✅ Verrouillé (KH-012 USA + KH-015) | Tests Colissimo USA en ligne T1/T2/T3 |

### 11.1 Posture Phronesis sur incitations DROM-COM / UE / UK / Suisse / Europe hors UE — délibération 2026-05-30

**Posture retenue Alain (verrouillée Phronesis Temps 3)** :

> Agir dans la direction du report explicite, à condition que les politiques France métropolitaine (55 €) et USA (grille min(quantité, montant)) restent verrouillées, en évitant toute généralisation prématurée d'incitations à des zones non documentées.

**Justification doctrine Phronesis** :
- Décider sans données réelles sur 5 zones simultanément multiplierait les hypothèses fragiles
- Asymétrie coûts erreur : retirer une gratuité annoncée coûte plus cher en réputation que l'introduire plus tard
- Statu quo « frais réels affichés » est révisable à la hausse (incentive), pas l'inverse
- Capacité solo Alain préservée — zéro charge cognitive supplémentaire sur barèmes multi-zones à maintenir manuellement au lancement

**Donnée structurante intégrée à la délibération** : COGS Kaïro 35 € < 3,5 € unitaire en commandes de 200 pièces (Alain, 2026-05-30). Marge brute ≈ 90 %. Élimine la contrainte « marge fragile » de l'arbitrage. Contrainte réelle = capacité opérationnelle solo + risque douanier / réputationnel + lisibilité commerciale.

**Interdits dérivés de la posture** (2026-05-30 → révision T+12 à T+18 mois) :
- ❌ Pas verrouiller publiquement de seuil DROM-COM / UE / UK / Suisse / Europe hors UE
- ❌ Pas annoncer en com (newsletter, accueil, fiches produits) « livraison offerte Europe » ou équivalent
- ❌ Pas configurer WC Lot 1 règles gratuité conditionnelle hors France métropolitaine
- ❌ Pas ouvrir UK / Suisse / Europe hors UE au checkout MVP — rester « nous contacter »

**Risque résiduel non couvrable** : si concurrent direct (Etsy compris) installe politique d'incitation visible et clients européens annulent commandes Koinobori House pour ce motif, écart se mesure difficilement (clients qui partent ne se manifestent pas). Non couvrable au MVP sans données.

### 11.2 Conditions de révision instrumentées — révision cible T+12 à T+18 mois

Cinq signaux à instrumenter dès l'ouverture du checkout (Lot 1+) et à suivre jusqu'à révision formelle.

**Signal 1 — Abandon panier zone UE (UE 27 hors France)**
- *Signal observable* : taux d'abandon panier après affichage frais shipping sur zone UE
- *Ce que ça signifie* : élasticité prix shipping client UE
- *Action associée* : étudier hypothèse contribution UE à partir d'un seuil
- *Déclencheur* : taux d'abandon UE > 60 % observé sur 50 paniers UE consécutifs OU > 3 mois

**Signal 2 — Volume commandes UE**
- *Signal observable* : nombre de commandes UE effectivement payées par mois
- *Ce que ça signifie* : volume justifie ou non coût étude approfondie + maintenance barème
- *Action associée* : ouvrir chantier incentive UE en mode étude formelle
- *Déclencheur* : ≥ 30 commandes UE/mois sur 2 mois consécutifs

**Signal 3 — Demandes devis manuel zones non activées**
- *Signal observable* : nombre demandes via formulaire « nous contacter » UK + Suisse + Europe hors UE
- *Ce que ça signifie* : demande latente sur zones non activées
- *Action associée* : ouvrir étude activation checkout par zone demandeuse
- *Déclencheur* : ≥ 10 demandes/mois sur 2 mois consécutifs pour une même zone

**Signal 4 — Sinistralité DROM-COM**
- *Signal observable* : taux retour / perte / litige DROM-COM
- *Ce que ça signifie* : politique frais réels DROM-COM est tenable ou non
- *Action associée* : étudier seuil très élevé ou exclusion DROM-COM du checkout
- *Déclencheur* : > 15 % retour/perte/litige sur 20 commandes DROM-COM consécutives

**Signal 5 — Abandon panier France métropolitaine (contexte macro crise 2026)**
- *Signal observable* : taux abandon panier sur zone France métropolitaine après affichage frais shipping (paniers < seuil 55 €)
- *Ce que ça signifie* : sensibilité prix client français MVP. Contexte macro 2026 : pouvoir d'achat dégradé, propension consommation déclinante → asymétrie spécifique marché domestique distincte de signal UE
- *Action associée* : étudier abaissement seuil 55 € (ex. 45 €) OU contribution partielle au-dessous du seuil OU repenser tarification produit
- *Déclencheur* : taux abandon France < 55 € > 50 % sur 100 paniers France consécutifs OU > 3 mois ; OU panier moyen France < 40 € sur 6 mois consécutifs

### 11.3 Hypothèse à revérifier — COGS

Donnée structurante 2026-05-30 : COGS Kaïro < 3,5 € (commande 200 pièces). À revalider explicitement si :
- Volume commande grossit (1 000+ pièces) → COGS attendu encore plus bas
- Volume commande chute (< 100 pièces / fragmentation fournisseur) → COGS attendu remonte
- Changement qualité / contrôle / certification produit → impact COGS direct
- Si COGS remontait à 8-10 €/unité, arbitrage shipping changerait : signaux 1-4 deviendraient plus urgents, posture report explicite plus difficile à tenir

Réévaluation COGS = condition préalable à toute relecture de la posture report.

### 11.4 Horizon de révision

**T+12 à T+18 mois** après ouverture checkout MVP (selon stabilité des signaux). Pas avant, pas plus tard sans justification explicite.

## 12. Alertes marge France métropolitaine 55 €

Aucune alerte forte. Seuil 55 € :
- N'incite pas overshoot panier dégradant marge
- Atteint typiquement à 2 koi standard (Kaïro + koi 20 €, ou 2 Kaïro)
- Mono Kaïro 35 € reste sous seuil → upsell sain (+20 €)
- Mono S&S 49 € reste sous seuil → upsell léger (+6 €) → reste rare car +6 € peu déclencheur

Réévaluation post-launch après 3-6 mois données réelles (panier moyen + % atteinte seuil + impact marge produit).

## 13. Hors scope confirmé

- ❌ Politique USA non rouverte (cf §1 référence)
- ❌ Pas de modif master.csv
- ❌ Pas de modif prix produits
- ❌ Pas de config WooCommerce
- ❌ Pas de plugin installé
- ❌ Pas d'automatisation tarifs temps réel
- ❌ Pas de modif Stripe / PayPal / Brevo / WP / WC
- ❌ Pas d'ouverture Lot 1

## 14. Liens

- Note parent : [KH-012-transporteurs-france-ue.md](KH-012-transporteurs-france-ue.md)
- Cadrage shipping global + USA : [KH-012-KH-015-shipping-usa-seo.md](KH-012-KH-015-shipping-usa-seo.md)
- Tests Colissimo USA : [KH-015-tests-colissimo-usa.md](KH-015-tests-colissimo-usa.md)
- CSV devis transporteurs : [KH-012-transporteurs-devis-template.csv](KH-012-transporteurs-devis-template.csv)
- Grille prioritaire 5 cas pilotes : [KH-012-tarifs-collecte-prioritaire.csv](KH-012-tarifs-collecte-prioritaire.csv)
- Catalogue : [../../catalog/README.md](../../catalog/README.md)
- CGV §3 + §7 : [KH-017-documents-legaux/03-CGV-B2C-FR.md](KH-017-documents-legaux/03-CGV-B2C-FR.md)

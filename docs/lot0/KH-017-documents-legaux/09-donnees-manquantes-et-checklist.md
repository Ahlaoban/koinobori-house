# KH-017 — Données manquantes et checklist avant publication

## A. Données légales vendeur

Identification vendeur acquise — formulation obligatoire intégrée dans tous les documents publics :

> Koinobori House propose des koinobori originaux conçus par BCDG, l'entreprise créative créée par Alain Herbinière.
> SIREN / RCS : 945 241 545 R.C.S. Quimper
> SIRET (siège) : 945 241 545 00017
> Adresse : 1 rue du marais, 29730 Treffiagat
> Email : contact@koinoborihouse.com
> Directrice de la publication : Else Smakalova

Restant :

- [x] Adresse de retour produits : **deux adresses listées publiquement dans la CGV §8.2** (1 rue du Marais, 29730 Treffiagat ; 46 ruelle de l'Église, 69620 Ternand). Le client contacte BCDG au préalable par email pour recevoir l'adresse applicable, choisie au cas par cas. Politique intégrée CGV §8.2.
- [x] Téléphone public : pas de numéro de téléphone, uniquement contact@koinoborihouse.com.

## B. CM2C

Coordonnées intégrées :

- CM2C — Centre de la Médiation de la Consommation de Conciliateurs de Justice
- 49 rue de Ponthieu, 75008 Paris
- Tél. : 01 89 47 00 14
- Email : litiges@cm2c.net
- Site de saisine : www.cm2c.net/declarer-un-litige.php
- Validité : 29/05/2029

À faire :

- [ ] Coller dans les CGV FR la clause officielle exacte fournie par le dashboard CM2C si elle existe.
- [ ] Garder la version EN comme traduction informative ; le FR fait foi.

## C. TVA / facturation

- [x] Mention actuelle intégrée : `TVA non applicable, art. 293 B du CGI`.
- [ ] Surveiller la bascule documentaire prévue à partir du 01/09/2026 vers la nouvelle référence CIBS : `TVA non applicable, art. L. 223 et s. du code des impositions sur les biens et services (CIBS)`.
- [ ] Vérifier modèles de facture WooCommerce PDF si plugin utilisé.
- [ ] Vérifier les mentions de facture B2B/B2G si devis/commandes hors checkout.

## D. Rétractation et retours

- [x] Frais de retour à la charge du client sauf erreur de BCDG / produit défectueux ou non conforme. Intégré CGV §8.2 et §8.6 (international).
- [x] Adresse de retour : **deux adresses listées publiquement CGV §8.2** (Treffiagat ou Ternand) ; le client contacte BCDG au préalable pour recevoir l'adresse applicable, choisie au cas par cas.
- [x] Mode de demande de rétractation : déclaration par email à contact@koinoborihouse.com, formulaire type fourni CGV §8.1.
- [ ] À partir du 19/06/2026 : prévoir une fonctionnalité en ligne permettant l'exercice du droit de rétractation directement en ligne si le site conclut des contrats à distance via interface en ligne.
- [x] Exclusion produits personnalisés / sur mesure : intégrée CGV §8.4 avec mention « indiquée clairement avant la validation de toute commande personnalisée ».

## E. Livraison

- [x] Délais indicatifs France : 2 jours ouvrables / 48 h après expédition (Colissimo, estimation La Poste). Intégré CGV §7.
- [x] Délais indicatifs UE / Europe : 3 à 8 jours ouvrés après expédition selon pays. Intégré CGV §7.
- [x] Délais indicatifs USA : 5 à 10 jours ouvrés après expédition, hors traitement douanier. Intégré CGV §7. **Checkout USA non activable avant tests KH-015**.
- [ ] Délais indicatifs UK si activé.
- [ ] Clarifier droits/taxes import : DAP transparence radicale ou landed cost si activé plus tard (Colissimo USA en ligne ⇒ duties payés upfront expéditeur, à confirmer avant activation checkout USA).
- [ ] Transporteurs réellement activés : La Poste / Colissimo / Mondial Relay (à confirmer config WC Lot 1).

## F. Données personnelles / RGPD

- [ ] Liste réelle des plugins WordPress/WooCommerce.
- [ ] Plugin de paiement Stripe exact.
- [ ] Plugin PayPal exact.
- [ ] Plugin newsletter / Brevo exact.
- [ ] Plugin abandoned cart exact — vérifier base légale et mécanisme d’opposition/consentement.
- [ ] Plugin avis clients exact.
- [ ] Plugin cookies/consentement exact.
- [ ] Activation ou non de compte client WooCommerce.
- [ ] Durée de conservation des comptes inactifs.
- [ ] Politique de sauvegarde o2switch / WordPress.
- [ ] Procédure incident données personnelles.

## G. Cookies

- [ ] Audit cookies réel après installation WordPress/WooCommerce/Polylang/Stripe/PayPal/Brevo/Plausible.
- [ ] Compléter tableau cookies avec noms, durées, finalités, éditeurs.
- [ ] Vérifier si Plausible est configuré sans cookie / exempté ; sinon bannière consentement.
- [ ] Ne pas activer pixel publicitaire ou retargeting sans consentement préalable.

## H. REP / IDU — point réglementaire à ouvrir séparément

Point non tranché dans KH-017 : les koinobori étant des produits textiles décoratifs et les commandes utilisant des emballages, il faut vérifier si l’activité est soumise à une ou plusieurs filières REP : emballages ménagers, textiles / éléments de décoration textile / articles de loisirs selon qualification exacte du produit.

À faire :

- [ ] Déterminer la qualification REP exacte des koinobori.
- [ ] Vérifier besoin d’adhésion éco-organisme / IDU.
- [ ] Si IDU requis, l’ajouter dans les CGV, mentions légales ou documents contractuels.
- [ ] Ne pas faire d’allégation environnementale non justifiée.

## I. Pages WordPress recommandées

FR :

- `/fr/mentions-legales/`
- `/fr/conditions-generales-de-vente/`
- `/fr/politique-de-confidentialite/`
- `/fr/politique-cookies/`

EN :

- `/en/legal-notice/`
- `/en/terms-and-conditions/`
- `/en/privacy-policy/`
- `/en/cookie-policy/`

## J. Verdict KH-017

Statut : **préparé mais non publiable tel quel**.

Raison : les structures et clauses principales sont prêtes, mais les données légales personnelles, retours, plugins, cookies réels et éventuelles obligations REP doivent être complétés avant publication.

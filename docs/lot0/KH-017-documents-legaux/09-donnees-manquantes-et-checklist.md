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
- [ ] Téléphone public : la décision du 2026-07-24 était « pas de numéro ». La CGV v2026-09-07 (rédigée avec ChatGPT, adoptée par Alain) publie **+33 6 07 79 33 03** aux articles 1, 10, 15, 22. **Alain confirme ou retire** ; les mentions légales restent sans téléphone tant que non tranché.

## B. CM2C

Coordonnées intégrées :

- CM2C — Centre de la Médiation de la Consommation de Conciliateurs de Justice
- 49 rue de Ponthieu, 75008 Paris
- Tél. : 01 89 47 00 14
- Email : declarer-un-litige@cm2c.net (adresse publiée sur cm2c.net/comment-nous-saisir.php, vérifiée 2026-09-07 ; l'ancienne adresse « litiges@ » a été remplacée dans tous les documents)
- Site de saisine : www.cm2c.net/declarer-un-litige.php
- Validité : 29/05/2029 (donnée interne, ne figure plus dans les CGV publiques)

À faire :

- [x] Clause CM2C intégrée à l'article 23 des CGV v2026-09-07.
- [ ] Garder la version EN comme traduction informative ; le FR fait foi.

## C. TVA / facturation

- [x] Mention actuelle intégrée : `TVA non applicable, art. 293 B du CGI`.
- [x] **Bascule CIBS reportée, aucune action avant 2027. Vérifié à la source le 2026-09-08.** La recodification de la TVA du CGI vers le CIBS devait entrer en vigueur le 01/09/2026 (ordonnance n° 2025-1247 du 17 décembre 2025). L'**ordonnance n° 2026-671 du 27 juillet 2026** l'a reportée au **1ᵉʳ janvier 2027** pour ne pas la faire coïncider avec la première échéance de la facturation électronique, et a repoussé la fin de la tolérance sur les anciennes références du CGI du 31/12/2027 au **30/06/2028**. Source : [Rapport au Président de la République, JORFTEXT000054497139](https://www.legifrance.gouv.fr/jorf/id/JORFTEXT000054497139).
  - **Conséquence pour le lancement** : `TVA non applicable, art. 293 B du CGI` reste la mention correcte aujourd'hui et le restera au 30/09/2026. Rien à modifier sur les mentions légales, les CGV ni les factures.
  - ⚠️ **À rouvrir fin 2026** : le numéro d'article CIBS applicable au 01/01/2027 n'est pas stable dans les sources secondaires (`L. 223-3` contre `L. 233-3`). Ne pas retenir de numéro sans vérification Légifrance à ce moment-là, idéalement confirmée par l'expert-comptable.
- [ ] Vérifier modèles de facture WooCommerce PDF si plugin utilisé.
- [ ] Vérifier les mentions de facture B2B/B2G si devis/commandes hors checkout.

## D. Rétractation et retours

- [x] Frais de retour à la charge du client sauf erreur de BCDG / produit défectueux ou non conforme. Intégré CGV §8.2 et §8.6 (international).
- [x] Adresse de retour : **deux adresses listées publiquement CGV §8.2** (Treffiagat ou Ternand) ; le client contacte BCDG au préalable pour recevoir l'adresse applicable, choisie au cas par cas.
- [x] Mode de demande de rétractation : fonctionnalité en ligne, formulaire type en annexe 1, ou déclaration claire (CGV v2026-09-07 art. 10.2).
- [ ] **Fonction de rétractation en ligne (obligatoire pour les contrats conclus depuis le 19/06/2026, art. L. 221-21 C. conso.)** : décrite à l'article 10.3 des CGV. À construire = **P0 avant lancement** : page « Retours et rétractation » avec formulaire Fluent Forms (identité, n° de commande, produits, décision), lien visible et accessible pendant tout le délai avec un libellé sans ambiguïté du type « Renoncer au contrat ici », étape de confirmation explicite, accusé de réception automatique par email (support durable). Lien depuis le compte client, la confirmation de commande et le footer.
- [x] Exclusion produits personnalisés / sur mesure : CGV art. 14, information avant confirmation de commande.

## E. Livraison

- [ ] Les délais indicatifs (France 2 jours ouvrables, UE 3 à 8 jours ouvrés, USA 5 à 10 jours ouvrés hors douane) **ne figurent plus dans les CGV v2026-09-07** (art. 8.4 renvoie à l'information donnée avant la commande). Ils doivent donc être affichés sur la **page Livraison** et au checkout (obligation d'information, art. L. 111-1 C. conso.). Texte prêt : `docs/lot3/pages-confiance-FR-EN.md` §1.
- [x] **USA = « nous contacter » au lancement** (arbitrage Alain 2026-09-07). Zone USA désactivée dans WooCommerce, formulaire de contact dédié.
- [ ] Délais indicatifs UK si activé.
- [ ] Clarifier droits/taxes import : DAP transparence radicale ou landed cost si activé plus tard (Colissimo USA en ligne ⇒ duties payés upfront expéditeur, à confirmer avant activation checkout USA).
- [ ] Transporteurs réellement activés : La Poste / Colissimo / Mondial Relay (à confirmer config WC Lot 1).

## F. Données personnelles / RGPD

- [x] Liste réelle des plugins WordPress/WooCommerce : **intégrée politique §5 FR+EN (2026-07-24, stack verrouillée CLAUDE.md)**.
- [x] Plugin de paiement Stripe : WooCommerce Stripe Gateway (officiel).
- [x] Plugin PayPal : WooCommerce PayPal Payments (officiel).
- [x] Plugin newsletter / Brevo : FluentSMTP + Brevo.
- [x] Abandoned cart : **aucun outil au lancement** (décision Alain 2026-07-24) — mention intégrée §5.
- [x] Avis clients : **aucun outil au lancement** (décision Alain 2026-07-24) — mentions retirées des politiques.
- [x] Plugin cookies/consentement : Complianz — intégré politiques cookies FR+EN.
- [x] Compte client WooCommerce : **activé, création facultative au checkout** (décision Alain 2026-07-24).
- [x] Durée de conservation des comptes inactifs : **3 ans** (décision Alain 2026-07-24) — intégrée §7.
- [x] Politique de sauvegarde : o2switch + UpdraftPlus/Google Drive — intégrée §9.
- [x] Procédure incident données personnelles : clause notification CNIL intégrée §9 FR+EN.

## G. Cookies

- [ ] Audit cookies réel après installation complète (Bloc 2 du backlog) — confirme le tableau prérempli.
- [x] Tableau cookies prérempli (noms, durées, finalités, éditeurs) sur la base de la stack retenue — 2026-07-24. Confirmation finale = audit ci-dessus.
- [x] Plausible : configuré sans cookie, exempté de consentement — intégré politiques FR+EN.
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

## J. CGV v2026-09-07 : ce qui a changé et ce qui reste

La CGV FR de référence est désormais la **version du 7 septembre 2026** (26 articles + annexe, rédigée avec ChatGPT, adoptée par Alain le 2026-09-07). Le fichier `03-CGV-B2C-FR.md` la reprend **à l'identique**, avec deux insertions de Claude :

- [x] **Encadré D. 211-2** inséré à l'article 15, texte officiel de l'annexe (version en vigueur depuis le 01/10/2022, relevé sur Légifrance le 2026-09-07). À afficher dans un cadre distinct dans WordPress.
- [x] **« (RCS Quimper) »** ajouté après le SIREN à l'article 1, par cohérence avec les mentions légales.

Retiré par rapport à la version du 03/08, volontairement :

- [x] Clauses contenu numérique (BD Kaïro) → `10-clauses-contenu-numerique-BD.md`, à réinsérer à la sortie de la BD (automne 2026).
- [x] Mention interne « Validité de l'adhésion communiquée : 29/05/2029 ».
- [x] Le vendeur n'est plus désigné « BCDG » mais « Koinobori House / Alain Herbinière EI » : conforme à la doctrine (BCDG = signature, pas l'entité).

Propositions **non intégrées**, à trancher par Alain, une phrase chacune :

- [ ] Réintroduire « Les koinobori sont des objets décoratifs. Sauf indication contraire, ils ne sont pas des jouets » (art. 3 ou 17), utile face à un usage enfant.
- [ ] Réintroduire le droit de refuser une commande en cas de fraude suspectée ou d'erreur manifeste de prix (art. 6).
- [ ] Réintroduire une clause archivage / preuve (anciennement art. 16).

À faire :

- [ ] **Version EN** `04-terms-conditions-B2C-EN.md` à réaligner sur les 26 articles (traduction informative, le FR fait foi, art. 25).
- [ ] Publier sur staging la page 238 avec le texte définitif FR, créer la page EN liée.
- [ ] Contrôle avocat = complémentaire, non bloquant (décision Alain 2026-07-27).

## K. Verdict KH-017

Statut : **préparé mais non publiable tel quel**.

Raison : les structures et clauses principales sont prêtes, mais les données légales personnelles, retours, plugins, cookies réels et éventuelles obligations REP doivent être complétés avant publication.

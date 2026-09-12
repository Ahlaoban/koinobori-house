# Jalon d’architecture — décision proposée à Alain

10 septembre 2026. Fondé sur le [rapport v0.5](RAPPORT-AUDIT-KH2027.md), la [matrice des 57 composants](MATRICE-REPRISE.md), les [58 exigences](COUVERTURE-EXIGENCES.md) et le [complément serveur S19](evidence/serveur-cpanel.md). **A01 approuvée par Alain le10/09/2026, réponse explicite « je valide » à la proposition d’architecture.** Le texte conserve la proposition approuvée et ses réserves. Aucun accord de production ni de dépense externe n’en découle.

## Une décision structurante à valider maintenant

**A01 — Conserver WordPress, WooCommerce, Kadence et Polylang ; développer Custom/Projects dans un module KH dédié au sein de cette plateforme, avec le même compte client.** WooCommerce reste l’autorité pour commandes, prix, stocks et paiements. Le module KH porte dossiers, organisations, configurations, versions, devis, BAT et production. Les fichiers sensibles sont privés ; les travaux d’image sont asynchrones ; une interface permet de changer ultérieurement le traitement d’image sans refaire les dossiers clients.

| Point à arbitrer | Recommandation et conséquences |
|---|---|
| Pourquoi ce choix | Le staging contient déjà le catalogue, les contenus bilingues, formulaires et intégrations. Les défauts identifiés concernent déploiement, réglages, données et parcours ; aucun blocage mesuré n’impose de remplacer le commerce. La proposition suit ARC-01/ORD-01, sous réserve de faisabilité vérifiée. |
| Ce que cela permet | Préserver les identifiants et le travail existant, offrir une identité commune et administrer commerce et dossiers dans un même environnement. Le code métier est séparé du thème afin de pouvoir faire évoluer la présentation. |
| Conséquences durables | Dépendance maintenue à l’écosystème WP/WC/Polylang ; maintenance régulière, migrations versionnées et contrôle des permissions obligatoires. Une plateforme commune signifie aussi qu’une défaillance applicative peut toucher plusieurs parcours. |
| Risques à éprouver | Isolement des photos et BAT, droits entre organisations, concurrence/idempotence, fiabilité des tâches et capacité réelle de l’hébergement pour la visualisation. Des sous-comptes gratuits sont disponibles, mais quota, sorties réseau et reprise ne sont pas encore démontrés. |
| Coût estimé | Prototype de faisabilité F2 : **3–7 jours-personne de6h (18–42h)**. Construction/pilote F2 complet : **35–80 jours-personne (210–480h)**, estimation initiale distincte du prototype, recouvrant les sous-composants de la matrice. Charges F0/F1 au rapport§10 ; ne pas les confondre avec cette enveloppe F2. |
| Coût monétaire | Aucun tarif de prestation applicable fourni : charge × tarif éventuel, sans devis accepté. Ce ne sont pas des jours de fonctionnement de Codex ni un délai calendaire. L’emplacement de test proposé est affiché gratuit dans l’abonnement actuel ; factures, renouvellements, services d’envoi/stockage et coût éventuel de génération restent à vérifier avant toute dépense. Aucun achat autorisé implicitement. |
| Alternative | Service KH indépendant : peut mieux isoler calcul et stockage, mais ajoute authentification, échanges de données, exploitation et maintenance. Pas de bénéfice actuellement mesuré justifiant ce surcroît ; coût comparatif précis non établi. À vous resoumettre seulement si le prototype révèle une limite significative. |
| Réversibilité | Modèle métier et API versionnés, migrations additives, fichiers privés et interface d’image remplaçable. Cette séparation prépare une extraction future ; elle ne la rend pas gratuite et n’autorise aucune perte d’historique. |

## Portée précise de votre validation

Votre accord valide cette architecture de départ pour le périmètre F1 puis F2 que vous avez déjà demandé. Il permet de poursuivre les choix techniques ordinaires, les prototypes, corrections, tests et documentation sans nouveaux GO par sous-lot. Il ne réduit ni les cinq collections, ni FR/EN, ni les parcours Custom/Projects/devis/BAT/production/back-office.

La restauration isolée, le rapprochement des fichiers distants et les protections restent des **conditions techniques à remplir avant les transformations dépendantes**. L’accord d’architecture n’est ni une déclaration de restauration réussie, ni une autorisation d’écraser le staging, ni une autorisation de publication. Une commande HPOS terminée a été constatée ; sa nature réelle/test n’étant pas établie, elle est à préserver avec ses relations.

## Choix ordinaires pris en charge sans autre arbitrage

- Préparer la copie de travail dans un sous-compte libre de l’hébergement existant, après contrôle de capacité, droits et sorties ; conserver les sites actuels.
- Éprouver d’abord une composition contrôlée des designs sur photo pour VIS-01. Le prototype doit montrer comparaison des variantes, fidélité aux motifs, rendu mobile et limites. Un recours payant à la génération ou un changement sensible du rendu/traitement des photos serait soumis séparément avec preuve et budget ; aucun fournisseur n’est retenu aujourd’hui.
- Réconcilier Git, fichiers distants et données avant déploiement ; corriger accueil, langues, CSS, livraison, messages et permissions dans l’environnement isolé, avec recette.
- Conserver les options F3/F4 hors du chantier engagé, conformément au périmètre déjà demandé.

Les données créatives, tarifs et engagements de production manquants seront demandés au moment où ils bloquent un lot concret. Ils ne constituent pas des décisions d’architecture à vous faire répéter aujourd’hui.

## Réserves d’audit conservées

19 composants restent sans classement effectif dans la matrice, faute de preuve suffisante, notamment services métier externes et options futures. La couverture documentaire des 57 composants et58 exigences est complète ; elle ne transforme pas ces inconnues en BUILD ou en conformité. La restauration, les transactions, la charge, les permissions métier et les tests sur appareils réels restent à exécuter. **G0 opérationnel n’est pas acquis ; l’objectif global de développement n’est pas terminé.**

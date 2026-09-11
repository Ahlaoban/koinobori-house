# Reprise KH2027 — cadre de travail et jalon utilisateur

10 septembre 2026. Source d’autorité : demande explicite utilisateur `/goal` reçue après l’audit initial et la préparation de restauration. L’objectif est enregistré dans la tâche Codex. Aucun budget de tokens n’a été demandé ni fixé.

## Objectif et fin du travail

Reprendre et développer Koinobori House selon KH2027-REF v3.0 jusqu’à ce que le périmètre approuvé soit développé, testé, documenté, présenté dans un environnement de test exploitable et prêt pour une décision de mise en production. La production demeure soumise à une autorisation finale distincte, à une version test, à une sauvegarde vérifiée et à un retour préparé.

## État du jalon

| Condition | État |
|---|---|
| Audit sourcé | Rapport v0.5 produit pour le périmètre accessible ; compléments F0 nécessaires |
| Matrice autonome | 57 composants, 58 exigences couvertes ; 19 décisions encore réservées |
| Préservation locale | 165 fichiers initialement suivis inchangés, HEAD d3073da ; audit non commité |
| Staging WordPress | Session inspectée en lecture seule ; faits S16–S18 |
| Accès cPanel | Session ciblée exploitée ; inventaire serveur S19, sans URL de session dans les preuves |
| Inventaire serveur, intégrité archives, restauration isolée | S21 : 20598 fichiers extraits et vérifiés, 94 tables importées et rapprochées, retour SQL réussi ; cœur WP, 34 produits, 46 variations, 17 paires FR/EN et 5 commandes HPOS chargés sous garde hors ligne ; recette Web et copie hors hébergement encore à vérifier |
| Arbitrages définitifs d’architecture et de périmètre | A01 approuvée ; portée et réserves dans DECISIONS-JALON.md |
| Validation utilisateur du jalon | **Reçue le 10/09/2026 : « je valide », en réponse à A01 ; portée et réserves dans DECISIONS-JALON.md** |
| Développement structurel | Autorisé dans le périmètre approuvé ; transformations dépendantes après isolement, préservation et preuves de restauration |

## Autonomie autorisée

Avant le jalon : poursuivre les lectures, inventaires et travaux d’audit, préparer et exécuter les vérifications isolées dont la destination, les données et les effets sont maîtrisés. Ne pas convertir la préparation d’un clone en migration ou refonte du staging. Réévaluer toute action irréversible ou toute dépense avant exécution.

Après le jalon : enchaîner les phases approuvées, les corrections réversibles, les choix techniques ordinaires, tests et refactorisations validées sans demander une validation entre chaque sous-lot. Une recette réussie autorise la poursuite du travail dans le périmètre approuvé ; elle n’autorise pas un déploiement de production.

Une décision utilisateur n’est requise que pour : modification significative produit/budget/architecture/sécurité, accès ou secret manquant, dépense externe, action irréversible ou mise en production. Les communications commerciales réelles ne sont pas déduites d’une autorisation de développer leur fonctionnalité ; les essais utilisent des destinataires autorisés et un environnement neutralisé.

## Périmètre déjà demandé, à ne pas redemander point par point

- Socle responsive mobile, cinq collections issues des actifs KH, FR/EN, catalogue fiable et compte unique.
- Custom, Projects, visualisation, devis, BAT, production et back-office, conformément aux exigences et limites du référentiel.
- Préservation des identités, commandes, fichiers et changements ; travail isolé, tests et documentation par lot.
- Phasage F0 puis F1 puis F2. Les options Passport/Gallery, extensions, kits, application et AR ne deviennent pas des chantiers engagés du seul fait de leur mention dans le référentiel.
- Les règles déjà explicites — notamment les minimums Custom, la séparation simulation/BAT, les versions contractuelles et l’absence de pose KH — servent de base ; elles ne sont pas des questions techniques à répéter.

## Orientations étudiées — arbitrage actuel dans DECISIONS-JALON.md

| Sujet | Orientation de travail | Preuve encore nécessaire | Ce qui serait soumis à Alain |
|---|---|---|---|
| Implantation des fonctions métier | Conserver WP/WC ; examiner en premier un module KH dans le même système, avec séparation claire des données, fichiers privés et traitements | Capacité de l’hébergement, isolation, file de tâches, exploitation et prototype | Choix final module WP/service distinct, conséquences, réversibilité et coût total ; aucune microarchitecture à faire valider |
| Visualisation Projects | Comparer en prototype une composition contrôlée sur photo et, seulement si nécessaire, un service génératif | Reconnaissance des designs, comparaison de densité, résultat sur mobile, coût et données envoyées | Choix seulement s’il change sensiblement le rendu produit, le budget ou le traitement de photos clients |
| Capacité et dépenses d’exploitation | Réutiliser les services existants lorsque leurs capacités sont prouvées ; plafonds explicites | Licences, quotas, stockage, service d’images éventuel, restauration, disponibilité humaine | Dépenses externes et compromis significatifs ; aucun achat automatique |
| Étendue du pilote et calendrier | Livrer le périmètre F1/F2 demandé avec contrôle humain métier et recette sur les trois segments | Découpage après inventaire, dépendances de contenu et de tarification, capacité de test | Arbitrer uniquement si coût/capacité imposent une réduction, un report ou une modification de l’objectif |

L’orientation module WP décrite dans [DECISIONS-JALON.md](DECISIONS-JALON.md) est approuvée par Alain. La possibilité de compositing est explicitement ouverte par VIS-01 p.16 ; le prototype est un choix technique ordinaire, aucun fournisseur payant n’est présumé nécessaire. Aucune promesse de rendu ni d’échelle n’est inventée.

## Coûts : discipline de présentation au jalon

Le rapport contient des fourchettes préliminaires en jours-personne de six heures : prototype d’architecture F2 3–7 jours ; réalisation/pilote F2 35–80 jours indicatifs ; autres lots détaillés au §10. Ce sont des estimations de charge d’ingénierie, **pas des jours de fonctionnement de Codex, une facture, un délai calendaire ou un devis accepté**. Les sous-lignes se recouvrent et ne sont pas additionnées aveuglément.

Le dossier d’arbitrage distinguera charge, coût monétaire externe, coût récurrent et travail humain d’exploitation. Aucun tarif journalier d’un prestataire, prix de licence ou budget de génération n’est inventé. À défaut d’un tarif applicable, la conversion monétaire restera explicitement non chiffrée ou présentée comme formule, avec les prix fournisseurs vérifiés avant décision de dépense.

## Prochaine action

La validation A01 est reçue. Achever les preuves de restauration et de réconciliation requises avant les transformations ; poursuivre ensuite les lots approuvés. Les URLs de session, clés et données clients restent hors des documents versionnés. Conserver les constats d’audit précédents et compléter leurs limites au fur et à mesure, sans déclarer G0 opérationnel acquis avant ses preuves.

État détaillé de la reprise serveur : [S21 — restauration](evidence/reprise-restauration.md).
L'accès opérationnel est la session Chrome du compte principal puis du sous-compte,
pas la session non connectée du navigateur intégré. Ne pas répéter le transfert
SSH déjà effectué ni les imports réussis sans nouveau motif ; les copies de travail
et les essais interrompus sont conservés dans le compte isolé.

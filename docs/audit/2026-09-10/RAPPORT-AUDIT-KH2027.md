# Audit de reprise Koinobori House — KH2027-REF v3.0

**Document : KH2027-AUD-20260910 — version 0.5, audit initial approfondi du périmètre accessible.**

Date : 10 septembre 2026, Europe/Paris. Base de code : `d3073dafb0b11c9866e23a02ecc357e0b7ca0cdd`, branche `main`. Les preuves HTTP portent leur heure UTC exacte ; collecte principale du 10/09 à 09:12 UTC. Auteur : Codex. **G0 non acquis. Aucune transformation structurelle, publication, installation, migration, purge, transaction ou modification WordPress effectuée.**

Ce dossier contient le rapport, la [matrice autonome](MATRICE-REPRISE.md), ses données complètes [JSON](MATRICE-REPRISE.json), la [couverture des exigences et de la recette](COUVERTURE-EXIGENCES.md), et les preuves reproductibles dans `evidence/`. Il est rédigé dans le dépôt, sans commit ni push. « Complet » qualifie la couverture des composants identifiés et des 58 exigences, **pas la vérification de services inaccessibles**. Les décisions de reprise sont des recommandations ; leurs réserves sont explicites.

**57 composants :** KEEP 4, IMPROVE 27, REFACTOR 2, REPLACE 0, REMOVE 1, BUILD 4 ; 19 décisions non attribuées faute de preuve suffisante. Une inconnue n'est pas assimilée à une absence. Le diagnostic classé REMOVE est déjà absent du staging observé : la recommandation porte sur son exclusion d'un déploiement normal futur, pas sur la suppression de sa source.

## 1. Conclusion de reprise

Le travail existant est récupérable et mérite d'être préservé. Il comprend un thème enfant Kadence, trois MU-plugins, une extension WooCommerce spécifique, des actifs visuels, un catalogue partiel et un corpus documentaire important. Rien dans les preuves recueillies ne justifie de remplacer WordPress, WooCommerce, Kadence ou Polylang aujourd'hui.

La production publique ne présente pas encore le socle marchand bilingue décrit dans la documentation : racine `200` avec message de recherche vide, `/fr/` et `/en/` en `404`, aucune page publiée exposée par l'API consultée, thème enfant ancien et `noindex`. Le staging a ensuite été inspecté dans la session Chrome authentifiée : WordPress 7.1, WooCommerce 11.1.0, 34 produits publiés, huit formulaires actifs et thème enfant 1.1.0 sont confirmés. Le défaut des racines FR/EN est reproduit ; FluentSMTP reste non configuré. Les réponses `401` concernent uniquement les requêtes non authentifiées. [S16] **Le complément serveur S19 identifie les deux racines et bases, confirme l’absence de WooCommerce/Polylang en production et compare neuf chemins spécifiques. Il reste un rapprochement complet des fichiers et données à réaliser ; le contrôle partiel ne permet pas de recopier aveuglément le dépôt sur staging.**

Les principaux risques sont la divergence entre environnements, l'absence de restauration actuelle démontrée, les procédures manuelles non rejouables et la confusion entre le MVP 2026 et le MVP complet 2027. Les constats locaux ajoutent un catalogue source incohérent avec les imports récents, des défauts de contraste et une dépendance d'outillage vulnérable. La priorité proposée est d'achever F0, puis de stabiliser F1 avant d'ouvrir Custom/Projects.

## 2. Statut des preuves et autorité des documents

| Code | Sens | Ce que cela autorise à conclure |
|---|---|---|
| V | Vérifié pendant cet audit | Fait limité au chemin, à l'environnement et à la méthode indiqués |
| H | Historique ou déclaration Claude | Le document affirme le fait ; son exécution actuelle n'est pas prouvée |
| NV | Non vérifié / inaccessible | Aucune conclusion d'absence, de conformité ou de fonctionnement |
| C | Exigence cible KH2027 | Résultat à construire ou à vérifier, jamais présenté comme existant |
| R | Recommandation / hypothèse | À éprouver et à valider avant mise en œuvre |

La demande utilisateur autorise cet audit et exige la préservation. Le PDF est la référence des **exigences cibles** ; la passation et les commentaires du code sont des **sources à contrôler**. Leurs consignes de fusionner une PR, déposer/supprimer un diagnostic, modifier des réglages ou envoyer un brief à Manus ne sont pas des ordres d'exécution de cette session. Aucune de ces actions n'a été entreprise.

`CLAUDE.md` a été lu. Aucun `AGENTS.md` n'a été trouvé à la racine, dans les parents contrôlés (`C:\`, `C:\dev`) ou dans l'inventaire du dépôt. Ses conventions de préservation et de PR sont conservées. Ses interdictions métier antérieures ne peuvent pas annuler la comparaison explicitement demandée avec KH2027 v3.0. Les désaccords de cible sont consignés, sans réécrire la doctrine.

La compétence PDF a servi à extraire les 33 pages et à vérifier visuellement les pages 9–10, où figurent AUD-01/AUD-02 et le schéma de matrice. Le `kickoff` mentionné dans `CLAUDE.md` a été retrouvé et lu dans `C:\Users\herbi\.claude\skills\kickoff\SKILL.md`. Sa demande d'attendre un GO supplémentaire n'a pas été appliquée : l'utilisateur a déjà explicitement demandé de commencer cet audit. Aucun mécanisme de dépôt/compétence n'a été utilisé pour suspendre ce travail autorisé.

| Compétence / méthode | Traitement | Justification |
|---|---|---|
| PDF | Utilisée | Lecture du référentiel et contrôle du schéma de matrice |
| kickoff | Checklist de démarrage consultée | Vérifier répertoire et instructions, sans répéter une demande de GO |
| Revue statique et sources éditeurs | Utilisées | Vérifier le code local et les affirmations techniques contestables |
| TDD / création frontend | Non utilisées | Aucun code de production demandé ni modifié |
| Déploiement / migration | En attente de G0 | Audit et preuves de retour à compléter avant transformation |

## 3. Registre des sources

Les chemins ci-dessous sont relatifs au présent dossier. Les références de ligne du rapport se rapportent au commit de base ; le manifeste SHA-256 permet de vérifier qu'il s'agit des mêmes fichiers.

| Source | Document / preuve | Portée |
|---|---|---|
| S01 | PDF utilisateur `C:\Users\herbi\Downloads\Koinobori_House_2027_Referentiel_v3.0.pdf` ; [extraction paginée](evidence/referentiel-extrait.txt) | Exigences, 33 pages ; SHA-256 `170FCDDBAC2B6EEB01C17D626892E917A6BF68EE79B5B8AC5D2F8D47012EDC2F` |
| S02 | [CLAUDE.md](../../../CLAUDE.md), [README](../../../README.md), [.gitignore](../../../.gitignore) | Instructions et état déclaré du projet |
| S03 | [Passation du dépôt](../../handoff/PASSATION-CHATGPT-2026-09-10.md), pièce utilisateur `PASSATIONCHATGPT20260910.md` | Contenus identiques après normalisation des fins de ligne et espaces aux extrémités ; empreintes binaires différentes |
| S04 | [Git baseline](evidence/git-baseline.json), [branches distantes](evidence/remote-heads.txt), [PR12](evidence/pr12.json), [PR13](evidence/pr13.json) | Versions, historique, branches, fusion réellement confirmée |
| S05 | [Inventaire suivi](evidence/tracked-inventory.json), [fichiers ignorés](evidence/ignored-inventory.json), [contrôles locaux](evidence/local-checks.json), [hooks](evidence/hooks.json) | Chemins, tailles, SHA-256, code spécifique, mesures |
| S06 | [Collecte HTTP](evidence/http-public.json) et corps `prod-*.txt` / `staging-*.txt` | Requêtes GET publiques horodatées, sans cookie ni authentification |
| S07 | [Inventaire staging Claude](../../lot3/staging-inventaire-2026-09-07.md) | Observations historiques des 7–9 septembre ; pas une interrogation actuelle de la base |
| S08 | [Sauvegardes KH-102](../../lot1/KH-102-sauvegardes.md), [thème KH-103](../../lot1/KH-103-theme.md) | Procédures et drills historiques |
| S09 | [Plan catalogue](../../lot2/lot2-catalogue-plan.md), [schéma CSV](../../lot2/KH-204-schema-csv-bilingue.md), [contrôles CSV](evidence/catalog-checks.json) | Données sources et divergences |
| S10 | [Notes d'intégration](../../charte-graphique/reference-v3/NOTES-INTEGRATION-CLAUDE.md), [répartition](../../handoff/REPARTITION-MANUS-CLAUDE.md), [index charte](../../charte-graphique/README.md) | Référence visuelle, corrections et responsabilités historiques |
| S11 | [Checklist juridique](../../lot0/KH-017-documents-legaux/09-donnees-manquantes-et-checklist.md), corpus FR/EN du même dossier | Promesses et points restant à vérifier |
| S12 | [Audit npm](evidence/npm-audit.json), [scan limité de secrets](evidence/secret-pattern-scan.json) | Outillage local ; aucune attestation globale de sécurité |
| S13 | [Branche GitHub](evidence/github-branch.json), [workflows GitHub](evidence/github-workflows.json) | `protected: false`, aucun workflow Actions |
| S14 | Mémoire Claude `C:\Users\herbi\.claude\projects\C--dev-Koinobori\memory\MEMORY.md` | Index consulté, historique contradictoire par endroits ; pas recopié dans le dépôt |
| S16 | [Relevé staging authentifié](evidence/staging-authentifie.md) | Versions, réglages, jobs, sauvegardes, pages, plugins et formulaires lus dans Chrome le 10/09 vers 11:28–11:34 Paris |
| S17 | [Complément staging](evidence/staging-complement.md) | Liaisons Polylang, formulaires publics FR/EN et panier anglais |
| S19 | [Audit cPanel et serveur](evidence/serveur-cpanel.md) | Racines/bases, sauvegardes JetBackup, neuf comparaisons de fichiers, commandes HPOS, permissions, lint et checksums cœur |
| S18 | [Réglages commerce](evidence/staging-commerce.md) | Taxes, zones/tarifs de livraison, comptes, conservation et états des paiements |
| S15 | [Kaïro](../../kairo-bd-recit-et-produit.md), pages du [Lot 3](../../lot3/) | Intentions et textes ; publication non déduite de leur présence |

Les vérifications externes ponctuelles sont citées au plus près du constat. Les sources privées n'ont été transmises à aucun moteur de recherche. La recherche Web portait uniquement sur des fonctions techniques et des normes publiques.

## 4. Git, branches et travail de Claude

**V — État initial.** `C:\dev\Koinobori`, `main`, HEAD `d3073da`. Avant la création du dossier d'audit : sortie porcelain vide, diff index et diff de travail vides. Les avertissements Git signalent l'impossibilité de lire le fichier global `C:\Users\herbi\.config\git\ignore` dans le bac à sable ; les règles du dépôt et l'inventaire local ont été lus. Le constat de propreté ne signifie pas absence de fichiers ignorés. [S04/S05]

**V — Distant.** `git ls-remote --heads origin` confirme le même HEAD distant sans fetch ni déplacement de référence. GitHub confirme PR #13 fusionnée le 10/09/2026 à 08:32:49 UTC ; aucune PR ouverte lors de la collecte. Le conseil « merger #13 » de la passation est périmé. `design/reference-v3-manus` est conservée à `ebf24e7` et `docs/reprise-2026-09-07` à `83d9682`. Ne pas interpréter une comparaison naïve des commits après squash comme du travail perdu. [S04]

**V — Autre worktree.** `C:\Users\herbi\.codex\worktrees\edac\Koinobori`, HEAD détachée `dbf8a53`, sortie `git status --short --branch` propre. L'erreur initiale de propriété Git a été résolue pour la seule lecture via `git -c safe.directory=...`, sans configuration globale. Ce checkout est plus ancien ; il n'a pas été modifié.

**V — Fichiers hors suivi.** Pack Manus, prototypes/backup HTML, archive de thème, logs de tests et dépendances Node sont présents hors suivi. Ils ont été inventoriés et préservés. Aucun nettoyage, stash, reset, checkout ou suppression de branche effectué. Les incidents de suppression d'actifs et de CSV étranger rapportés dans la passation restent **H** quant à leur déroulement ; l'existence actuelle des actifs suivis est vérifiée par les empreintes. [S03/S05]

**V — Gouvernance.** `main` n'est pas protégée selon l'API GitHub ; aucun workflow Actions n'est configuré. La convention de PR et de revue existe, mais il n'y a pas de contrôle CI automatique observé. Le motif d'abonnement avancé par Claude n'a pas été validé. Une session Claude encore en cours n'est ni identifiée ni présumée arrêtée : l'audit porte sur ses livrables, commits et mémoire accessibles. [S02/S13]

| Contribution identifiable | Demande / rattachement | Preuve locale | Limite actuelle |
|---|---|---|---|
| Thème enfant initial | KH-103 | `ff888aa`, `b704e31`, thème `1.0.1` | Ancienne génération encore servie en production |
| Routage racine bilingue | KH-107, gate KH-104b | `198a803`, `0956281`, MU-plugin `1.1.0` | Chargement en production non démontré ; comportement attendu non observé |
| Fondations et palette éditeur | Lot 6 / PR1 | `85ca86c`, `72a6a0e`, `functions.php`, CSS, WOFF2 | Fondations chargées sur staging ; charte v3 non liée ; checkout NV |
| Taille unique WooCommerce | UX catalogue du 25/06 | `25e789b`, plugin `1.0.0` | Tests attributs multiples et rupture NV |
| Portage de la référence Manus | PR #12 | `509a20b`, `076bbdf`, `8765d94`, `ebf24e7`, squash `a0c6072` | CSS et actifs réels ; aucun assemblage de page versionné |
| Correction BCDG | Doctrine titre et revue | `253ce61`, `b818deb`, MU-plugin `1.1.0` | Absent de la liste MU actuelle ; rendu produit non recetté |
| Diagnostic accueil | Investigation Lot 3 | `541740e`, MU-plugin `1.0.0` | Outil livré, résultat d'exécution absent |
| Pages, menus, formulaires, SEO | Lot 3 | Sources FR/EN, IDs dans S07, commits PR #13 | 40 pages publiées et huit formulaires confirmés ; définitions/export complets NV |
| Corpus légal et doctrine | Arbitrages 7–9/09 | Fichiers, historique PR #13, checklist | Cohérence et fonctionnement de toutes les promesses non validés |

Les mentions de coauteur établissent une contribution assistée déclarée ; elles ne permettent pas d'attribuer chaque ligne exclusivement à Claude ou à Manus.

## 5. Cartographie technique réelle et accessible

```text
Dépôt (165 fichiers suivis)
  wp/ : 3 MU-plugins + 1 plugin spécifique + thème enfant Kadence
  catalog/ : master partiel + exemples + images
  docs/ : 91 fichiers de plans, contenus, charte et passation
  brand/ : 9 actifs ; tools/ : conversion images et préparation polices
        | déploiement manuel documenté, correspondance partielle seulement
Production publique               Staging authentifié (Basic hors session)
  WP/Kadence/enfant observables      WP7.1/WC11.1/Polylang vérifiés en UI
  /fr/ et /en/ : 404                /fr/ et /en/ : blog vide, pages longues présentes
  enfant1.0.1                      enfant1.1.0, charte v3 non liée
                                    9 sauvegardes listées, restauration NV
        |                           |
Services prévus ou déclarés : Stripe, PayPal, Brevo, Fluent Forms,
Complianz, LiteSpeed, SEOPress, Wordfence, Drive, JetBackup, Plausible
```

Le cœur WordPress, WooCommerce, Kadence parent et les plugins tiers ne sont pas versionnés dans ce dépôt. Pas de `wp-config.php`, schéma de base, dump exploitable, manifeste des versions installées, environnement local WP, dépendances Composer ou contrat d'API métier KH dans l'inventaire suivi. Cela ne prouve pas leur absence sur le serveur. [S05]

| Élément | Dépôt | Production observée | Staging |
|---|---|---|---|
| WordPress | Pas de cœur | **7.1** confirmé par WP-CLI ; checksums cœur réussis avec avertissements logs [S19] | **7.1**, checksums cœur réussis avec avertissement log [S19] |
| Kadence parent | Dépendance déclarée | Classes et URLs d'actifs annoncent **1.5.0** | **1.5.2**, thème classique [S16] |
| Thème enfant | Header **1.1.0**, CSS charte v3 | `style.css` **1.0.1**, 1 480 octets ; anciennes couleurs/radius 8px | **1.1.0** annoncé, foundations chargée, charte v3 non liée [S16] |
| WooCommerce | Hooks d'un plugin spécifique | **Absent** de l'inventaire des plugins WP-CLI [S19] | **11.1.0**, HPOS actif, synchronisation HPOS désactivée [S16] ; 4 brouillons checkout et1 commande terminée [S19] |
| Wordfence | Choix documentaire | **8.2.2** actif via WP-CLI [S19] | **9.0.1** active ; intégration Login Security WC inactive ; licence/MFA NV |
| Hébergement | o2switch déclaré | En-tête `o2switch-PowerBoost-v3` | Même en-tête sur 401 |
| PHP/SQL/cron/cache/HPOS | Non fournis | NV | PHP8.3.33, MariaDB11.4.13, WP-Cron actif, pas de cache objet externe déclaré ; LSCache page indisponible [S16] |

Un en-tête ou namespace ne suffisait pas à inventorier production. **S19 apporte désormais la preuve WP-CLI : seules UpdraftPlus1.26.5 et Wordfence8.2.2 sont installées, sans WooCommerce ni Polylang.** WP7.1 et Kadence1.5.0 sont confirmés directement. Les checksums du cœur réussissent dans les deux environnements, avec avertissements de fichiers error_log supplémentaires. Les racines et bases distinctes restent sous le même compte système.

Le relevé [hooks.json](evidence/hooks.json) couvre les cinq fichiers PHP : chargement CSS, preloads, palette éditeur, filtres de titres/contenu, filtre de listes de variations, JS en footer, diagnostic en footer et routage exécuté au chargement. Aucun `register_post_type`, `register_rest_route`, shortcode propre ou tâche cron KH trouvé. Les shortcodes Fluent Forms dans les contenus pointent des IDs de base : ils ne contiennent pas les définitions des formulaires.

## 6. Constats détaillés et risques

### F01 — Production éloignée du socle F1 — P0 avant ouverture

**V.** GET racine avec deux préférences de langue : `200`, corps identiques (49 320 octets), aucun cookie posé ni redirection linguistique. GET `/fr/` et `/en/` : `404`. L'API des pages publiées répond `[]`, `X-WP-Total: 0`. Cela ne compte ni brouillons ni produits ni pages privées. Chrome affiche « Il semble que rien ne soit trouvé pour votre recherche », un champ de recherche et le crédit Kadence, sans navigation commerciale visible. [S06, `prod-root-fr/en`, `prod-fr/en`, `prod-pages`]

**V.** La racine porte `noindex, nofollow`. `robots.txt` n'interdit que `/wp-admin/` ; ne pas confondre ces mécanismes. Aucun canonical/hreflang dans les balises relevées sur les pages testées. **C :** COM-01, SEO-01, INT-01. **R :** préserver le noindex jusqu'à la recette ; compléter F1 et vérifier chaque gabarit FR/EN avant ouverture. Aucun incident de commandes réelles n'est démontré.

### F02 — Écart de déploiement prouvé, identité de release inconnue — P0

**V.** Production sert l'enfant `1.0.1` et retourne `404` sur `assets/css/kh-foundations.css` et `kh-charte-v3.css`. Le dépôt contient ces fichiers et un header `1.1.0`. Le HTML public ne les charge pas. Ce sont des différences effectives, indépendantes du diagnostic Polylang. [S06 ; `wp/themes/koinobori-child/style.css:7`, `functions.php:26`]

**V.** Staging annonce le thème enfant1.1.0, mais ne lie pas `kh-charte-v3.css` sur l’accueil ; le dépôt la charge explicitement. Le numéro1.1.0 ne suffit donc pas à identifier une release. [S16]

**V S19.** Neuf chemins comparés : staging comporte quatre correspondances après normalisation CRLF/LF, deux différences de contenu (functions.php, theme.json), trois absences (charte v3, BCDG, diagnostic). Les trois PHP spécifiques présents passent le lint. Production comporte sept absences et deux différences. **NV.** Commit exact du staging, rapprochement ligne par ligne et inventaire comparatif exhaustif. Aucun export complet pages/menus/formulaires/Polylang/Customizer/SEO dans le dépôt suivi. Une sauvegarde complète pourrait permettre une reprise, mais n'est pas un manifeste de déploiement ni une migration vérifiée. **R :** inventaire distant, exports privés, correspondance IDs, déploiement répétable et contrôle de release. Ne pas recopier une base sur une autre avant d'avoir identifié les commandes et données à préserver.

### F03 — Restauration actuelle non démontrée — P0, G0 bloqué

**H.** S08 décrit un drill réussi le 12 juin sur une production alors vide. S07/S08 annoncent une sauvegarde staging complète du 09/09 à 17h57 sous rétention manuelle et des copies Drive. **V.** S16 confirme neuf ensembles listés, dont celui du09/09 17h57 ; base quotidienne rétention7, fichiers hebdomadaires rétention4, Google Drive sélectionné et déclaré connecté. La base du10/09 09h30 est listée. Rapport e-mail non coché. Couverture standard wp-content et base, pas wp-config.php ni cœur. **V S19.** JetBackup liste 32 sauvegardes de chacune des deux bases KH identifiées par WP-CLI, dernières affichées le09/09. Le dossier updraft staging ne contient aucun fichier direct backup_. **NV.** Contenu et intégrité des archives, récupération des objets Drive/JetBackup, restauration, neutralisation des sorties et durée réelle de reprise.

**V, documentation.** La description « base quotidienne » coexiste avec « planification active : base le jeudi ». La configuration effective est désormais relevée : base quotidienne ; la phrase « base le jeudi » est périmée. [S16] KH-103 §7 conserve une instruction de reclonage production vers staging, incompatible avec l'unicité des contenus staging annoncée depuis septembre. La correction dans KH-102 ne neutralise pas cette autre procédure. [S07/S08]

**C :** AUD-01, SEC-04, GOV-02, OPS-01/02. RPO ≤ 1h et RTO ≤ 4h sont des **propositions du référentiel p.27**, pas des mesures ni des engagements actuels. Une copie quotidienne seule ne démontrerait pas RPO ≤ 1h. Voir le protocole §9.

### F04 — Catalogue : plusieurs sources incompatibles — P1, P0 pour tout import

**V.** `catalog/master.csv` comporte 7 lignes, 24 colonnes, aucun SKU dupliqué dans ce fichier et aucun champ obligatoire commun vide parmi ses lignes exportables. Les chemins d'images sont résolus **par rapport à `catalog/`** : les fichiers référencés existent. Les 7 lignes ne représentent pas 7 modèles ; une paire parent/variation existe. Aucun total de stock n'a été calculé ni reproduit. [S09]

**V.** Le master utilise `eds`, `kairo`, `hanami` et `KH-EDS-001-100`, tandis que le plan Lot 2 et `territoires-collection-FR.csv` portent la classification Mer/Motifs/Hanami/Kaïro/Territoires et `KH-TER-001-100`. Les imports Lot 2 sont distincts du master. `catalog/README.md` garde aussi les anciens codes OKU/BRE/EDS. Le master n'a pas de colonnes dédiées design/famille ni de poids d'expédition. Il ne peut pas, en l'état, reconstituer le catalogue 2027 ou représenter fidèlement le catalogue staging annoncé.

**H.** Le plan catalogue annonce 17 paires FR/EN, dont prix/variations/stock synchronisés ; S03 annonce encore neuf modèles sans photo. **V.** La liste actuelle montre34 produits publiés et1 brouillon ; l’état WC compte46 objets variation tous statuts. **NV.** Réconciliation exhaustive des17 paires, données par SKU, médias manquants et synchronisation effective. [S16] **R :** export WC/Polylang, inventaire SKU/ID/langue/variation, rapprochement ligne par ligne et registre de source autoritaire. Ne pas renommer un SKU vendu ni importer le master pour « compléter » automatiquement : risque de doublon et de rupture de liens historiques. Distinguer données commerciales publiques et colonnes fournisseur internes.

### F05 — Accueil Polylang : symptôme reproduit, cause non prouvée — P0 F1

**V.** En session administrateur, `/fr/` et `/en/` rendent le blog vide ; `/fr/accueil/` et `/en/home/` affichent les contenus FR/EN attendus. Réglages lus : `show_on_front=page`, `page_on_front=318`, `page_for_posts=0`. Le menu Accueil pointe `/fr/`, le logo `/fr/accueil/`. Sur cette dernière, canonical pointe `/`, hreflang fr/en pointent les pages longues, x-default `/`. Le dysfonctionnement signalé par Claude est donc reproduit et comporte une incohérence navigation/canonical. Le contexte anonyme avec cache reste à comparer. [S16]

**NV.** Cause exacte dans le cycle de requête, filtres et fichiers réellement chargés. Diagnostic absent de la liste MU ; pas de résultat d’exécution. Un callback ou une classe CSS ne suffit pas à prouver la causalité. Aucun plugin désactivé pour expérimenter.

**V, code.** KH-107 n'enregistre effectivement aucun hook et n'émet sa redirection que lorsque les chemins normalisés correspondent à la racine. Il appelle toutefois `home_url()` avant ce contrôle : l'exclusion absolue de tout effet de chargement/interférence serait trop forte. Ni remplacement de Polylang, ni nouvelle manipulation de cache n'est justifié par cette lecture seule. [MU-plugin `koino-lang-redirect.php:83–110`]

**R.** Après snapshot cohérent : vérifier la réponse réelle des quatre URLs racines/pages, la requête et les options avant/après filtres, les liaisons de langues, les versions installées, les snippets et templates réellement déployés. Expérimenter les désactivations uniquement sur clone isolé avec test A/B. Ne pas déployer le diagnostic de façon permanente.

### F06 — Slugs : correction nécessaire de la passation — P1 documentaire

**V.** S03 affirme que Polylang Pro ne lèverait pas la contrainte des slugs identiques entre langues. La documentation officielle décrit précisément cette capacité pour articles, pages et termes. La conclusion technique de la passation est donc incorrecte. Les URLs `contact-us` et `lifestyle-koi` restent des choix valides ; aucun achat de licence ni migration de slugs ne découle de ce constat. [Documentation Polylang](https://polylang.pro/documentation/support/guides/share-the-same-posts-or-terms-url-slugs-across-translations/)

**V.** Contrairement à une autre affirmation de S03, la liste actuelle présente un lien Désactiver pour Polylang for WooCommerce ; le plugin de base Polylang est requis par cette extension. S16 confirme aussi que le partage des slugs demande Pro. Aucun achat ni changement effectué.

### F07 — MU-plugin de routage : tests d'exclusion incomplets — P1

**V, code.** Méthodes GET/HEAD seulement, cookie validé `fr/en`, 302, cache interdit, conservation query string et exclusions admin/cron/AJAX/CLI/XML-RPC sont présents. `HttpOnly=false` est documenté pour un sélecteur JS ; il ne s'agit pas d'un cookie d'authentification. Aucun écrivain JS de `koino_lang_pref` n'a été trouvé dans `wp/`, et le prototype contient des liens `#`. Le choix manuel durable reste à tester dans la vraie navigation. [S05 ; `koino-lang-redirect.php:90–162`]

**R, risque à reproduire.** Sur une route REST utilisant `/?rest_route=...`, le contrôle de `REST_REQUEST` au chargement d'un MU-plugin peut être trop précoce : WordPress définit cette constante dans `rest_api_loaded()`. Le chemin reste `/`, donc il faut tester la route query-string, les previews, recherches, callbacks GET et paramètres WooCommerce avant de conclure à une exclusion correcte. Ce n'est pas une panne de paiement observée. [Source WordPress](https://developer.wordpress.org/reference/functions/rest_api_loaded/)

### F08 — Thème : ressource utile, assemblage et dette visuelle — P1

**V.** Les cinq PHP ont été lus intégralement. `functions.php` charge trois feuilles ordonnées, trois preloads WOFF2, les styles éditeur et une palette avec repli. Les sorties URL sont échappées. Aucun template `front-page.php`, pattern ou classe `body_class` ajoutant `kh-page` dans le code versionné. Le CSS comporte des styles de composants mais ne crée pas leur HTML. Cela ne prouve pas qu'aucun bloc n'a été saisi en base. [S05 ; `functions.php:26–90`]

**V.** `styles.css` de référence et `kh-charte-v3.css` deviennent identiques après neutralisation des `url()`. Deux sources à synchroniser pour chaque correction constituent une dette démontrée. Les fondations gardent des tokens des anciennes palettes ; `theme.json` n'expose pas `kh-white`. La présence de `html { scroll-behavior: smooth }` est globale ; la règle reduced-motion sur `html` existe aussi en fin de feuille. Ne pas reprendre une accusation d'absence totale de reduced-motion. [S05/S10]

**R.** Conserver le thème enfant et la référence créative, construire l'assemblage versionné seulement après arbitrage de navigation cible ; isoler les styles des pages éditoriales du panier/checkout. Ajouter `kh-page` globalement activerait aussi la suppression du soulignement des liens (`.kh-page a`), à recetter avant toute modification.

### F09 — Contrastes et médias — P1

**V, mesure locale.** Or `#B8860B` : 2,9701:1 sur washi, 3,3605:1 sur indigo, 3,2090:1 sur blanc, 2,5666:1 sur brume. Le seuil courant AA est 4,5:1, ou 3:1 pour le grand texte répondant à la définition WCAG. Les usages textuels concernés nécessitent une correction ; le défaut du couple or/washi existe même au seuil grand texte. Il s'agit d'un calcul de palette, pas d'un audit exhaustif du rendu accessible. [S05 ; WCAG 2.2](https://www.w3.org/TR/WCAG22/#contrast-minimum)

**V.** 21 WOFF2 totalisent 849 024 octets sur disque ; ce total n'est pas le poids téléchargé par page. 13 images existent dans le thème ; hero 528 152 octets, arts-textile 734 746 octets. Toutes les références relatives des CSS vers des actifs locaux se résolvent. Les droits des images, l'intégrité du pack externe et les notices de licences de polices restent à contrôler ; aucun fichier de licence des polices n'apparaît dans l'inventaire suivi. [S05]

**NV.** LCP/INP/CLS terrain, Lighthouse mobile, compression réelle des médias staging, cache hit/miss, charge SQL, images responsives et lecteurs d'écran. Les GET observés à la racine ont pris 0,466 et 0,574 seconde sur ce poste ; ce sont des temps de réponse totale ponctuels, **pas un TTFB ni un Core Web Vital**, et le contenu marchand n'est pas présent. Les cibles p.27 ne sont pas déclarées atteintes.

### F10 — Correctif BCDG et taille unique — P1, revue d'intégration requise

**V, BCDG.** Version 1.1.0 : correction ciblée, conservation de la casse et repli sur l'entrée en cas d'échec PCRE ; cinq filtres nommés en priorité 20. La fonction de parties du titre subsiste sans hook. Ce code n'écrit pas dans les produits. Tests réels de `document_title`, SEOPress, HTML, UTF-8 invalide et panier NV. Ne pas corriger les données en masse sur la seule base du rendu typographique. [`koino-bcdg-hyphen.php`](../../../wp/mu-plugins/koino-bcdg-hyphen.php)

**V, taille unique.** Le plugin masque tout attribut possédant une option, pas exclusivement la taille, conserve le select puis l'initialise avec jQuery en footer. La classe globale de formulaire masque le lien « Effacer » dès qu'un attribut mono-option est sélectionné. Avec plusieurs attributs, un autre attribut peut encore nécessiter une réinitialisation ; avec variations indisponibles, AJAX ou JS différé, le comportement est à vérifier. `setTimeout(60)` n'est pas une preuve de synchronisation avec tous les modes WC. [`kh-single-variation-display.php:41–108`](../../../wp/plugins/kh-single-variation-display/kh-single-variation-display.php)

PHP et WP-CLI ne sont pas disponibles dans le PATH de cet environnement ; aucun test PHP/WooCommerce exécuté. Il n'y a pas de suite automatisée versionnée pour ces fonctions. La matrice recommande des améliorations et une recette ciblée, sans présenter cette lecture comme un PASS fonctionnel.

### F11 — Sécurité des dépendances et des tests — P1, P0 avant uploads non fiables

**V.** `tools/package-lock.json` fixe sharp 0.33.5. `npm audit --package-lock-only --ignore-scripts --json` signale **une dépendance de sévérité high**, avec deux avis (libvips/libheif), et propose 0.35.4. Le script est un outil local, aucune preuve de déploiement Web. Aucun fichier non fiable n'a été converti durant l'audit ; aucun `npm audit fix` exécuté. **R :** mettre à jour et recetter l'outillage avant de traiter des images non fiables, sans extrapoler ces avis à une compromission WordPress. [S12 ; avis libvips](https://github.com/advisories/GHSA-f88m-g3jw-g9cj), [avis libheif](https://github.com/advisories/GHSA-rgj7-g3m4-5g8c)

**V.** Les scripts KH-104b/KH-109 contiennent `TrustAllPolicy` ou `curl -k` : ils ne constituent pas un test valide de confiance TLS. Plusieurs acceptent un mot de passe par argument ; ne pas recopier d'identifiants dans commandes, logs ou rapports. Ils conservent néanmoins des scénarios utiles (statuts, canonical, hreflang, racine) et des codes de sortie d'échec. Amélioration ciblée proposée ; aucun script historique rejoué contre le serveur.

**V, portée limitée.** Scan par cinq familles de motifs sur 105 fichiers texte suivis : aucun secret détecté. Cela exclut binaires, fichiers ignorés et blobs historiques ; aucune déclaration « dépôt sans secret » ne peut en être déduite. Les exclusions Git sont préventives, pas une preuve d'absence de fuite. [S12]

**NV.** MFA, rôles, permissions système, isolement des domaines, webhooks, signatures, limites, fichiers privés, vulnérabilités des plugins exacts et règles Wordfence. HTTPS a été vérifié par les clients utilisés ; aucune inspection TLS exhaustive. HSTS/CSP/X-Frame-Options absents des réponses HTML échantillonnées : point de durcissement à contextualiser, pas preuve d'exploitation. `nosniff` présent sur REST. Aucun test intrusif ni tentative d'accès à des dossiers clients.

### F12 — Juridique, confidentialité et correspondance au fonctionnement — P0 avant ouverture concernée

**V, documents.** Les CGV FR art.10.3 déclarent disponible la rétractation en ligne, tandis que la checklist garde six contrôles ouverts. Les mentions légales et la politique de confidentialité qualifient BCDG d'entreprise créative ; les CGV précisent l'absence d'entité distincte du vendeur. C'est une ambiguïté documentaire à harmoniser, pas une qualification juridique inventée. [S11 ; CGV FR:28,281 ; mentions FR:5]

**C / vérification externe ciblée.** D221-5 demande une fonction visible, disponible pendant le délai, permettant d'identifier la personne, le contrat et le canal d'accusé, une confirmation explicite et un accusé durable horodaté. **V :** formulaires11/12 actifs dans Fluent Forms, chacun affiche0 entrée. **NV :** définition complète, fonctionnement FR/EN, réception réelle et contenu de l’accusé. [S16] Pas de certification juridique globale dans cet audit. [Texte officiel](https://www.legifrance.gouv.fr/codes/article_lc/LEGIARTI000053303365/2026-07-27)

**NV.** Registre RGPD, contrats de sous-traitance, durées effectives, export/suppression de compte, consentements réels et purges. Les textes existent, l'implémentation ne s'en déduit pas. Pour F2, les fichiers privés, la séparation des droits et les versions BAT doivent précéder les uploads ; `.gitignore` n'est pas un contrôle de stockage privé. Les 24h/90j/12 mois de la p.24 sont des propositions à valider par finalité.

### F13 — Exploitation staging : configuration inachevée — P0/P1 F1

**V.** FluentSMTP affiche une demande de configuration ; Stripe est en test avec OAuth non connecté et compte vide dans l’état WC ; PayPal est déclaré intégré mais sans réception de webhook attestée par cet écran. Les huit formulaires sont actifs, sans recette des notifications. PDF Invoices5.16.1 est actif, configuration fiscale/numérotation et factures non vérifiées. **R.** Recetter sur environnement neutralisé achat FR/EN, webhooks, remboursement, facture et accusés ; aucune transaction ou émission réelle n’a été faite. [S16]

**V.** Quatorze plugins actifs, aucun inactif, six mises à jour proposées, auto-update désactivé pour les quatorze. Il s’agit d’un retard de versions observé, pas de six vulnérabilités démontrées. Wordfence signale l’intégration Login Security WooCommerce inactive. Prioriser compatibilité HPOS/blocs checkout et tests après sauvegarde, sans mise à jour en masse. [S16]

**V.** Action Scheduler compte sept échecs, tous `fetch_patterns` sans callback enregistré, du3 au9 septembre ; aucun incident de paiement ne s’en déduit. LSCache annonce ses fonctions de cache de pages indisponibles, tandis que le serveur public expose PowerBoost et l’état WC Apache. **R.** Clarifier les couches de cache et la responsabilité des jobs avant tuning ; ne pas purger/supprimer les traces pour masquer les symptômes. WP-Cron actif et698 tâches terminées ne démontrent pas une supervision opérationnelle. [S16]

### F14 — Traductions non liées et panier anglais partiellement français — P1 avant ouverture

**V.** Les paires Mentions légales232/234, Cookies235/236 et Livraison USA230/231 existent dans les deux langues, mais leurs lignes proposent Ajouter une traduction. Aucun hreflang dans le DOM des mentions FR. Les paires accueil, compte, panier, confidentialité, retours, livraison générale et CGV sont liées dans les lignes inspectées. **R.** Réconcilier les relations en préservant IDs et URLs, sans recréer les contenus. [S17]

**V.** `/en/cart/`, titre Cart et `lang=en-US`, affiche « Votre panier est actuellement vide ! » et « Nouveau dans la boutique » avec des recommandations anglaises. **NV.** Origine exacte des chaînes ; panier rempli et checkout. **R.** Contrôler les blocs sauvegardés et traductions, et recetter chaque état marchand. L’indicateur favorable des traductions WC ne certifie pas leur contenu. [S17]

**V, complément F12.** Les formulaires11/12 sont effectivement rendus sur `/fr/retours/` et `/en/returns/`, avec identification de commande, déclaration et bouton de confirmation dans chaque langue. Leur présence publique est désormais établie ; validation serveur et accusé durable restent non testés. Aucun formulaire soumis. [S17]

### F15 — Livraison internationale annoncée mais non configurée ; conservation à réconcilier — P0/P1 avant ouverture

**V.** Les réglages généraux permettent tous les pays, mais seule la zone France possède des méthodes : forfait5 EUR et gratuité dès55 EUR. La zone Reste du monde n’en a aucune. La page Livraison annonce pourtant un calcul au panier pour UE et DROM-COM. USA, Royaume-Uni et Suisse sont annoncés sur demande : l’absence de méthode ne contredit pas cette procédure manuelle. **R.** Réconcilier les destinations réellement offertes et les grilles de transport validées avant recette ; ne pas inventer de tarifs ni restreindre silencieusement le périmètre international. **NV.** Checkout par adresse, poids, remises et mode relais. [S18]

**V.** Achat invité et inscriptions activés. Tous les champs de durée de conservation WC inspectés sont vides, dont comptes inactifs, tandis que la politique KH prévoit3 ans d’inactivité. **NV.** Procédure humaine ou mécanisme extérieur à cet écran. **R.** Vérifier le traitement effectif et ses exceptions avant une purge ; aucune suppression de compte/commande autorisée par ce constat. [S18]

**V, complément F13.** PayPal affiche Compte de test et Stripe Action requise / Terminer la configuration. Le calcul des taxes est désactivé ; ce paramètre correspond au régime déclaré dans les documents sans en certifier la validité juridique. Aucune transaction ni modification fiscale effectuée. [S18]

### F16 — Isolation, permissions et fichiers de logs à renforcer — P1, P0 avant copie de données privées

**V S19.** Production et staging sont sous le même compte d'hébergement, partagé avec d'autres sites hors périmètre. Les deux dossiers uploads sont en0777 ; wp-config.php en0644. Le staging comporte une protection Basic au niveau .htaccess. Les fichiers du cœur vérifiés correspondent aux checksums officiels, mais des error_log supplémentaires sont signalés. **NV.** Propriétaires effectifs, ACL, cloisonnement hébergeur, exposition HTTP des logs, quota et contrôle des sorties du clone ; aucune compromission démontrée.

**R.** Utiliser un sous-compte indépendant pour la copie de test ; six emplacements gratuits sont disponibles dans cPanel. Vérifier ses droits/base, capacité, accès et neutralisation des sorties avant le premier démarrage de la copie. Tester la réduction des permissions et le stockage privé des logs sur clone avant application aux sources. Ne pas confondre espace libre du système de fichiers avec quota du compte. Aucun sous-compte activé, aucune dépense, aucun chmod ou déplacement réalisé. [Principes WordPress](https://developer.wordpress.org/advanced-administration/server/file-permissions/), [isolation o2switch](https://faq.o2switch.fr/cpanel/o2switch/univers-web-sous-comptes/).

## 7. Écarts de cible 2026 / KH2027

| Sujet | Existant documentaire 2026 | Cible v3.0 | Conséquence d'audit |
|---|---|---|---|
| Livraison | Site opérationnel 30/09/2026 | F0–F4 proposés fin 2026–2027 ; MVP complet = F1 + pilote F2 | Ne pas qualifier le seul commerce de MVP complet 2027 |
| Navigation | 7 entrées, Kaïro sans entrée propre | Collections / Kaïro / Custom / Projects, compte KH | Arbitrage d'architecture d'information avant assemblage, sans modifier aujourd'hui |
| B2B/B2G | Captation par formulaires, espaces privés hors MVP | Compte unique, organisations, devis/BAT et suivi | Extension métier ; les formulaires existants peuvent être réutilisés |
| Custom | Page et contact manuel | 50 B2C / 150 B2B-B2G par design/taille, contrôle serveur versionné | Règle non trouvée dans le code local ; présence hors dépôt NV |
| Projects | Pas de module versionné | Composition photo et variantes ; tâches asynchrones, quotas | Construction locale à prévoir sous réserve d'inventaire distant |
| Origine / discours | Interdiction publique absolue de Chine | Référence culturelle et origine réelle exactes, sourcing Chine admis, pas d'obligation Japon | Tension à résoudre explicitement ; aucune suppression de pays ou modification publique maintenant |
| Pose | Évocations d'installations / conseils | KH ne réalise aucune pose ; mention durable dans simulations/devis | Vérifier les promesses ; un conseil d'usage n'est pas automatiquement une prestation de pose |
| Catalogue | Catégories servant de collections | Collections distinctes de familles et designs distincts des SKU | Modèle relationnel à instruire, pas de remappage automatique |
| Kaïro | 4 produits décrits ; ancienne piste PDF téléchargeable | 15 chapitres, éditions FR/EN, QR durables, épreuve/distributeur ; 6,90 € cible | Préserver récit ; arbitrer format et données ; aucun prix/QR créé |
| Images | Pack créatif et mockups | Images authentiques, illustrations distinguées si confusion possible | Dossier de droits et représentativité avant publication |
| Options | Wishlist / contenus futurs | Passport/Gallery P2 ; DIY/app/AR conditionnels | Aucun BUILD immédiat ni remplacement du commerce motivé par ces options |

Les noms des cinq collections sont identifiés dans les actifs KH : Mer, Motifs, Hanami, Kaïro, Territoires. Leur reprise est proposée, sans inventer les quatre univers non nommés dans le PDF. Les détails économiques, coûts, licences et capacité humaine doivent être consolidés : aucun CA, marge ou calendrier ferme n'est déduit des anciens objectifs.

## 8. Architecture recommandée à instruire, sans migration engagée

**R — Continuité privilégiée :** WordPress pour contenu et identité, WooCommerce comme autorité commandes/prix/stock/paiements, thème enfant Kadence pour la présentation, module KH séparé logiquement pour projets/versions/devis/BAT, stockage privé et traitements asynchrones. Cela reprend ARC-01 sans prétendre valider la capacité de l'hébergement.

| Option F2 | Avantage à tester | Coût / risque | État de décision |
|---|---|---|---|
| Extension métier WP et tables adaptées | Identité et exploitation communes, intégration WC directe | Qualité du modèle, migrations, droits et capacité queue à démontrer | Première candidate, prototype requis |
| Service métier distinct avec identité fédérée | Isolation du calcul et du stockage, montée en charge | Authentification distribuée, synchronisation, observabilité et maintenance supplémentaires | Alternative si limites prouvées |
| Refonte headless / remplacement commerce | À comparer seulement face à un blocage mesuré | Migration SEO/commandes, double exploitation, reprise des intégrations | Aucun motif établi ; non recommandé actuellement |

ADR à ouvrir après F0 : A01 autorité des données et identité ; A02 module WP/service et stockage privé ; A03 traitement d'image/compositing, queue/coût/repli ; A04 déploiement/retour et commandes ; A05 URLs, taxonomies et QR ; A06 versions immuables et libération production. Chaque ADR devra comparer coûts récurrents, responsable réellement disponible, tests et retour. **Aucun ADR de choix définitif n'est réputé approuvé.**

## 9. Plan de reprise et de retour — protocole proposé, pas exécuté

La poursuite demandée après l’audit dispose maintenant d’un [dossier de restauration G0](RESTAURATION-G0.md) : précontrôles, étapes R01–R10, conditions d’arrêt et procès-verbal à remplir. La destination indépendante et les archives restent à vérifier ; aucune restauration n’a été lancée.

1. Identifier les accès serveur/cPanel, WP admin, bases, stockage et prestataires ; relever versions WP/PHP/SQL/plugins/thèmes/MU/drop-ins, rôles, cron et fichiers hors dépôt. Utiliser la session authentifiée de l'utilisateur, pas des secrets dans le chat.
2. Produire des sauvegardes cohérentes séparées de production et staging : base, fichiers publics/privés, configuration, jobs et inventaire ; dater, chiffrer, empreinter et vérifier une copie hors hébergement. Stockage privé hors Git, responsable et accès de secours identifiés.
3. Restaurer **une copie isolée**, jamais staging depuis production. Neutraliser paiements, webhooks sortants, SMTP, relances et tâches qui contactent des clients/fournisseurs ; anonymiser les données pour les tests. Tester aussi le rétablissement des protections après restauration.
4. Comparer les comptes de données par type/langue, liens Polylang, SKU/variations, prix et stock par référence, pages/menus/formulaires/options, fichiers et droits. Vérifier commande → paiement → e-mail et dossier → document. Chronométrer le drill et mesurer les pertes éventuelles.
5. Préparer la release applicative identifiée par commit + manifeste, avec migration additive et essai à blanc. Les exports WXR seuls ne suffisent pas aux options, réglages WooCommerce/Polylang/SEOPress et notifications Fluent Forms : vérifier chaque relation/import.
6. Faire une répétition staging → clone de production. Exécuter la recette pertinente avant/après ; désigner décideur, opérateur et fenêtre. Une indisponibilité paiement, accès croisé, incohérence stock/prix ou rupture langue est un motif d'arrêt.
7. Retour prioritaire par réactivation de la version applicative antérieure compatible. Si un retour de base devient nécessaire, préserver et rapprocher **toutes les commandes/paiements reçus après le point de sauvegarde** ; ne pas écraser aveuglément la base commerciale. Conserver liens d'ID et journal de réconciliation.

**Résultat actuel :** aucune nouvelle sauvegarde serveur ni restauration exécutée. Updraft et JetBackup sont inventoriés ; aucune archive décompressée ou vérifiée. Un sous-compte gratuit est une destination candidate, pas encore un environnement isolé opérationnel. Le protocole n'est pas un plan de retour « vérifié ». AUD-01/G0 restent partiels.

## 10. Backlog priorisé et estimations préliminaires

Les fourchettes sont des **jours-personne de 6 heures**, à réestimer après accès ; elles incluent préparation et contrôles techniques, pas attente de photos, décisions, devis prestataires, conseil juridique ou fabrication. Elles ne sont pas additionnables mécaniquement aux lignes de matrice qui recouvrent les mêmes tâches. Aucun engagement de calendrier ou budget.

| Ordre | Travail et critères de sortie | Charge basse/haute | Dépendances / décision |
|---|---|---|---|
| 0 — F0/P0 | Accès et inventaire distant ; versions, hashes, données, settings ; écarts réconciliés | 1–3 j | Alain + responsable technique à désigner |
| 1 — F0/P0 | Sauvegarde cohérente et drill isolé ; RPO/RTO mesurés, commandes préservées | 1–3 j | Accès archives et environnement neutralisé |
| 2 — F0/P0 | Diagnostic accueil, versions Polylang, tests de routage ; cause reproduite sur clone | 0,5–2 j | Snapshot et clone ; pas de remplacement par défaut |
| 3 — F0/P0 | Réconcilier catalogue/IDs/imports, exports et procédure de release | 2–5 j | Données serveur, règles de source autoritaire |
| 4 — F1/P0 | Paiement test FR/EN, webhooks doublés, stock/taxes/livraison, remboursement, accusés | 2–5 j | Prestataires en sandbox, destinataires de test autorisés |
| 5 — F1/P1 | Assemblage pages et navigation, palette/contraste, médias et recette mobile | 4–10 j | Arbitrage UX 2027, photos/droits, parcours marchand |
| 6 — F1/P0/P1 | Sécurité, RGPD, rétractation, SEO et exploitation ; preuves et alertes | 2–5 j | Versions/services connus, validation des textes |
| 7 — F2 | Spike architecture, données, stockage privé et queue ; ADR et budget complet | 3–7 j | G0, capacité de l'équipe et hypothèses de charge |
| 8 — F2 | Comptes/organisations, Custom, Projects, devis/BAT/production, suivi, pilote | 35–80 j indicatifs | Spike et découpage requis ; pas une estimation validée |
| 9 — F3/F4 | Options Passport/Gallery/produits/DIY/app | Non engagée | GO distinct fondé sur usage, coût et sécurité |

Risque planning : la date du 30 septembre 2026 n'est pas validée par cet audit. Les fonctionnalités F2 n'ont pas vocation à être ajoutées implicitement à cette échéance. Le MVP complet 2027 doit garder sa recette propre.

## 11. Vérifications exécutées, non exécutées et limites

**Exécuté :** état Git de deux worktrees, branches locales/distantes et PR, protection/workflows GitHub, inventaire de 165 fichiers suivis avec SHA-256, inventaire des ignorés, lecture intégrale des cinq PHP, extraction des 33 pages du référentiel et contrôle visuel des pages d'audit, rapprochement des deux passations, parsage des CSV catalogue/Lot2, résolution des actifs CSS, calcul des contrastes, comparaison des CSS, scan limité de motifs de secrets, audit npm sans installation, 13 GET publics avec TLS validé, inspection Chrome de la racine production et audit authentifié staging : versions/plugins/MU, état WC/HPOS, lecture WP, accueil FR/EN, Polylang, formulaires, sauvegardes/rétention et échecs Action Scheduler/cache. [S16]

**Non exécuté :** tests fonctionnels automatisés PHP/WP, transactions, remboursements, envois d'e-mails, connexions client, essais d'accès croisé, uploads malveillants, scans d'intrusion, audit exhaustif des secrets/historique, restauration/retour, tests de charge, Core Web Vitals terrain, vrais appareils iPhone/Android, audit clavier/lecteur d'écran du commerce, rapprochement ligne par ligne des PHP distants et comparaison complète de tous les fichiers/bases, audit intégral des réglages et extensions serveur.

**Complément exécuté S19 :** lecture ciblée cPanel, associations domaine/racine/base, plugins/thèmes production, neuf comparaisons SHA-256 normalisées, lint de trois PHP staging, SELECT agrégés incluant HPOS (quatre brouillons checkout et une commande terminée), permissions de quatre chemins, inventaire Updraft local et JetBackup, sous-comptes disponibles, checksums des deux cœurs avec avertissements logs.

**Préservation vérifiée :** les SHA-256 des 165 fichiers initialement suivis sont inchangés ; HEAD reste `d3073da`, diff suivi vide, seul `docs/audit/` est ajouté sans suivi Git. Les liens documentaires ont été contrôlés et les 58 exigences sont toutes rattachées à au moins un composant. [Contrôle final](evidence/final-validation.json)

L'échec initial des commandes réseau venait du bac à sable local. Les mêmes lectures ont ensuite réussi avec l'accès réseau autorisé ; **ce n'était pas une panne du site**. Le `401` staging est un résultat serveur observé et persiste dans la collecte non authentifiée. La session Chrome ouverte par l’utilisateur a ensuite permis les lectures authentifiées S16, sans transfert de mot de passe.

Les sources historiques sont utiles mais souvent datées : README « Lot 0 », mémoire comportant plusieurs anciens lancements, charte v2 et visuel Manus v3, sauvegardes quotidiennes/hebdomadaires, chiffres de pages différents selon les sections. Aucun chiffre historique n'est devenu un compteur actuel par simple recopie.

## 12. Décision G0 et prochaine preuve indispensable

| Condition AUD-01 / AUD-02 | État |
|---|---|
| Rapport sourcé et périmètre explicite | Produit pour le périmètre accessible |
| Matrice autonome couvrant composants et changements Claude | Produite ; décisions distantes inconnues non attribuées |
| Référentiel entièrement relié aux composants et tests | 58 exigences et T01–T14 cartographiés |
| Comparaison complète dépôt/staging/production | Partielle ; S19 ajoute racines/bases, neuf hashes normalisés et compteurs HPOS ; comparaison exhaustive non réalisée |
| Sauvegarde cohérente actuelle et restauration isolée | Non réalisée |
| Retour vérifié sans perte de commandes récentes | Non vérifié |
| Validation métier Alain et faisabilité technique | Non demandées comme acquises ; responsable technique à désigner |

**G0 reste ouvert.** Les accès staging et serveur ont été obtenus et exploités. Les preuves restantes sont la récupération privée d’un ensemble cohérent, son intégrité, puis une restauration isolée avec retour mesuré et le rapprochement des écarts. La validation du jalon d’architecture ne vaut pas réussite de ces contrôles. Le [dossier des décisions](DECISIONS-JALON.md) précise les seuls arbitrages proposés ; aucune transformation structurelle avant validation utilisateur.

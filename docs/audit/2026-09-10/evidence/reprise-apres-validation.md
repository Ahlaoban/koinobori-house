# S20 — reprise après validation A01

10 septembre 2026. Alain a répondu « je valide » à la proposition d’architecture A01. L’accord autorise la suite F0/F1/F2 décrite au jalon ; il n’autorise ni achat externe, ni écrasement des sources, ni publication en production.

## Travail isolé

Checkout créé : `C:/dev/Koinobori/.worktrees/kh2027-reprise`, branche `codex/kh2027-reprise`, départ d3073da. Le checkout principal reste sur main ; ses fichiers applicatifs sont préservés. Aucun déploiement ni changement de réglage WP effectué.

L’ancien onglet cPanel n’existait plus ; une nouvelle ouverture ciblée a retrouvé la session connue. Aucun inventaire global des onglets exécuté. Le formulaire d’activation du premier sous-compte libre, sc3heal3867, a été ouvert. Il demande un mot de passe et sa confirmation. Demande envoyée à Alain de les saisir directement dans cPanel, de conserver le secret, puis de cliquer Continuer. **Activation non confirmée à la date de ce relevé ; aucun mot de passe demandé dans le chat.**

## Nouvelle capture privée du staging

Source réelle : `/home3/heal3867/staging.koinoborihouse.com`, propriétaire UID1067, identique à l’opérateur terminal. Précontrôle : 20598 fichiers réguliers, 378364732 octets, aucun lien symbolique. Le répertoire privé proposé n’existait pas.

Inventaire des moteurs SQL : 93 tables InnoDB et une table MEMORY, `wprs_wfls_role_counts`. La capture a donc utilisé `wp db export` avec `--skip-single-transaction --lock-tables --quick`, limité à la base staging configurée, plutôt qu’une transaction seule. Des verrous de lecture temporaires peuvent retarder les écritures de cette base pendant l’export. Aucun verrou global ni commande d’écriture de données métier lancé.

Ensemble créé, jamais utilisé pour restaurer à ce stade :

`/home3/heal3867/kh2027-private/staging-20260910T103311Z`

| Contrôle | Résultat constaté dans le terminal |
|---|---|
| Début UTC | 2026-09-10 10:33:11 |
| Fin UTC | 2026-09-10 10:34:20.452304 |
| Répertoire privé | Mode0700 ; hors des racines Web identifiées |
| Archives | Mode0600 chacune |
| Source avant/après | 20598 fichiers ; dictionnaires de taille, permissions et SHA-256 identiques |
| Archive fichiers | Tous les membres réguliers relus ; empreintes et tailles conformes au manifeste ; pas de membre inattendu ou manquant |
| Base compressée | Décompression complète et SHA-256 identique au SQL exporté |
| Restauration SQL / WordPress | **Non exécutée** |
| Copie hors hébergement | **Non vérifiée** |
| Retour sur clone / temps de reprise | **Non exécuté / non mesuré** |

| Archive | Octets | SHA-256 |
|---|---:|---|
| database.sql.gz | 1629999 | `39ba233cc6b4546a822cd113f9350932e3ee5918e861f92d924b7637d07fe020` |
| site.tar.gz | 122565896 | `1004b74faf05ef2d6236e4e24d394885d4dc783ed2592739e8cd6c8400203fd4` |

Le répertoire contient également le SQL non compressé, son journal d’export, les manifestes avant/après, capture-summary.json et capture-driver.py (version exacte du script exécuté depuis le terminal). Aucun de ces fichiers privés n’a été téléchargé ou copié dans Git. Les tailles et empreintes ci-dessus ne révèlent pas les données clients ou secrets qu’ils protègent. Les archives historiques Updraft/JetBackup sont conservées et n’ont pas été remplacées.

La vérification des fichiers avant/après encadre la capture ; elle ne constitue pas un snapshot atomique du système de fichiers. La base n’a pas encore été importée pour vérifier ses relations et sa compatibilité de restauration. La durée d’environ69 secondes est celle de la capture et de ses contrôles, **pas un RTO**.

## Outillage préparé et tests

Dans le checkout isolé, tools/recovery contient le script de capture maintenable, son mode d’emploi et quatre tests sur fixtures sans données KH. Succès : archive fidèle, rejet d’un contenu altéré, rejet d’un fichier manquant, rejet d’un chemin sortant. Ces tests ne valident pas MariaDB ou un clone WordPress. Le script exécuté dans le terminal est une version compacte conservée dans l’ensemble privé ; le script maintenable du dépôt n’a pas lui-même été lancé sur le serveur.

Le contrôle binaire initial dans le nouveau checkout a signalé13 différences : elles proviennent toutes des conversions CRLF/LF de Git. Une comparaison directe avec le checkout source a confirmé l'identité du contenu après normalisation, sans modifier les fichiers. `initial-isolated-byte-check.json` conserve le contrôle initial et `isolated-checkout-validation.json` son explication vérifiée. `final-validation.json` conserve le contrôle du checkout principal, dont les165 empreintes d'origine restent strictement identiques ; son champ checked_repository précise la portée.

## Suite nécessaire

Après activation confirmée du sous-compte : vérifier racine, quota, droits SQL et protections ; neutraliser les sorties et interdire l’accès Web avant toute importation ; transférer une copie par un canal privé ; restaurer puis comparer le snapshot, tester les parcours et le retour. Aucun changement structurel dépendant ne doit précéder ces preuves. **A01 est approuvée ; G0 opérationnel reste ouvert.**

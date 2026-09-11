# Reprise serveur après réinitialisation — S21

10 et 11 septembre 2026. Ces constats complètent S20 et
TRANSFERT-ISOLE.md. Ils prouvent le démarrage contrôlé du socle WordPress et du
commerce dans une copie privée ; ils ne constituent pas encore une recette Web
complète ni une validation de G0.

## Accès retrouvé

La session connectée était dans Chrome, et non dans le navigateur intégré.
L'onglet correspondant exactement au lien fourni par Alain a permis de retrouver
le compte principal `heal3867`, UID 1067, `/home3/heal3867`. Un nouvel onglet de
gestion des lunes a permis d'accéder à `sc3heal3867`, UID 1473,
`/home3/sc3heal3867`. Les deux anciens terminaux appartiennent encore à l'ancienne
tâche Codex ; aucun contrôle concurrent de ces onglets n'a été forcé. Les URL de
session et secrets restent hors de ce dossier.

## État constaté, plus avancé que le dernier journal local

- L'archive de transport existe déjà dans
  `/home3/sc3heal3867/kh2027-private/incoming/transfer.tar` : 132904960 octets,
  SHA-256 `5dfb810ad5a772437cfd894e3300152a67bd5e4e341cb991839e064df5c8e60a`.
- L'extraction `snapshot-20260910T103311Z` contient 17252 fichiers sur les 20598
  attendus : 3346 absents, aucun fichier inattendu, aucune différence de taille
  ou de SHA-256 parmi les fichiers présents. Les 17252 fichiers sont en mode 0600.
  Aucun `stage-result.json` de réussite n'existe dans ce dossier.
- Les archives reçues correspondent aux empreintes relevées sur la source :
  `site.tar.gz`, 122565896 octets,
  `1004b74faf05ef2d6236e4e24d394885d4dc783ed2592739e8cd6c8400203fd4` ;
  `database.sql.gz`, 1629999 octets,
  `39ba233cc6b4546a822cd113f9350932e3ee5918e861f92d924b7637d07fe020`.
- Les manifestes avant/après reçus sont identiques.
- Zéro ligne dans les clés SSH autorisées ; clé `kh2027-transfer-sc3` absente.
  Aucune nouvelle clé activée lors de cette reprise.
- `.htaccess` de la racine Web cible contient `Require all denied` et
  `Options -Indexes`. Aucune tâche cron active relevée. Lecture de la
  configuration staging source impossible depuis le compte cible.
- cPanel affiche zéro base sur le sous-compte avant le nouvel import.

La présence des archives prouve une réception antérieure à cette reprise ; elle
ne permet pas à elle seule de reconstituer le détail de la session de transfert.
Les mentions antérieures « aucune archive transférée » sont donc historiques.

## Cause de l'extraction interrompue

Une nouvelle tentative privée dans `verified-20260910-reprise`, avec journal
`stage-verified.log`, a refusé deux noms contenant un antislash littéral :

- `site/wp-content/upgrade/koinobori-child/koinobori-child\style.css` (963 octets) ;
- `site/wp-content/upgrade/koinobori-child/koinobori-child\functions.php` (447 octets).

Le contrôle de l'archive complète compte 23820 membres et exactement ces deux
noms refusés. Leur présence et leur taille ont également été confirmées en
lecture seule sur le staging source. Il s'agit de noms POSIX à conserver tels
quels ; convertir l'antislash en séparateur changerait les chemins.

L'outil local accepte désormais l'antislash littéral sur POSIX uniquement et le
refuse sur Windows. Les contrôles de racine, traversée `..`, membres réguliers,
doublons, tailles, empreintes et destination finale restent appliqués. **21 tests
locaux passent.** Une version compacte correspondante a été déposée dans
`stage-posix.py` puis lancée, avec délai maximal 900 secondes, dans le nouveau
dossier privé `verified-posix-20260910`. Les deux extractions partielles sont
conservées ; aucun fichier existant ni site source n'a été écrasé.

Le résultat de cette dernière exécution et les contrôles SQL doivent être
consignés ci-dessous avant de déclarer l'étape réussie.

## Extraction complète et import SQL vérifiés

`verified-posix-20260910/stage-result.json` confirme : 20598 fichiers,
378364732 octets vérifiés ; décompression complète du SQL, 7203658 octets.
L'extraction n'a démarré aucun code PHP du site et n'a importé aucune base.

L'API cPanel `Mysql/list_databases` et `Mysql/list_users` a ensuite confirmé deux
listes vides sur le compte cible. La nouvelle base
`sc3heal3867_kh2027drill` et son utilisateur dédié ont été créés dans ce seul
compte. Les identifiants sont générés et conservés exclusivement sous
`kh2027-private/sql-drill-20260910`, en permissions privées, jamais affichés.
Le dump original a été importé par le client MySQL, sans exécuter WordPress.

Les fichiers `import-result.json`, `initial-counts.tsv`, `expected-counts.json`
et `count-comparison.json` restent dans ce répertoire privé. Résultats :

- 94 tables importées ; nombre de lignes identique au dump pour chacune.
- 40 pages publiées, 3 brouillons, 1 page à la corbeille.
- 34 produits publiés, 1 brouillon ; 46 variations publiées.
- HPOS : 4 `wc-checkout-draft` et 1 `wc-completed`.

Le dump contient des INSERT dont les valeurs se poursuivent sur les lignes
suivantes. Le premier outil de rapprochement a refusé ce format sans modifier la
base. La version adaptée traite les tuples et les caractères cités jusqu'au
point-virgule SQL réel ; **29 tests locaux passent**, dont les fichiers POSIX,
les données SQL contenant ponctuation/apostrophes et les INSERT multilignes.
Le rapprochement serveur termine sur 94 tables et zéro différence de compteur.

## Retour SQL prouvé sur le clone uniquement

Le test a créé une option `kh2027_restore_probe_20260910` dans la base de test,
vérifié sa présence, puis réimporté le dump vérifié dans cette même base. Il n'a
effectué aucune opération sur les bases sources. Le marqueur a disparu par
restauration de la table d'options.

`return-result.json` et les sommes de contrôle avant/après restent privés :

| Contrôle | Résultat |
|---|---|
| Début UTC | 2026-09-10T18:41:40.310493Z |
| Fin UTC | 2026-09-10T18:41:57.159991Z |
| Durée du test SQL | 16,850 secondes |
| Nombre de tables vérifiées | 94 |
| Compteurs après retour identiques au dump | Oui, toutes les tables |
| CHECKSUM TABLE EXTENDED avant/après identiques | Oui, toutes les tables ; aucun résultat NULL |
| Marqueur éliminé par restauration | Oui |
| WordPress démarré | Non |

Cette durée mesure le test SQL local à l'hébergement, **pas le RTO complet du
site**.

## Démarrage contrôlé de WordPress et du commerce

Une copie d'exécution distincte a été créée dans
`/home3/sc3heal3867/kh2027-private/runtime-20260910`, toujours hors racine Web.
Elle utilise la seule base de drill et un nouveau `wp-config.php` privé : aucune
configuration ni aucun secret du site source n'y a été repris. Les URL WordPress
et site sont fixées à `https://kh2027-test.invalid`, le cron est désactivé et les
modifications automatiques de fichiers sont interdites.

Avant le premier démarrage, un MU-plugin de garde a été ajouté à cette copie. Il
intercepte les requêtes HTTP WordPress et les courriels, retire les moyens de
paiement disponibles et désactive le lanceur asynchrone Action Scheduler. Le PHP
CLI est exécuté sans configuration héritée, avec `allow_url_fopen=0`, un
`open_basedir` privé et les fonctions de réseau, courriel, shell et processus
désactivées. La sonde de protection a confirmé PHP 8.1.34, l'absence de cURL et
de sockets disponibles, puis le code d'erreur `kh2027_offline` sur une requête
HTTP WordPress et l'interception du courriel.

Le chargement du cœur avec `wp core is-installed` réussit. L'inventaire des
extensions retrouve notamment WooCommerce 11.1, Polylang 3.8.7, Polylang for
WooCommerce 2.2.4, Stripe 10.9.1 et PayPal Payments 4.1.2. Les extensions de
paiement, SMTP, cache, sauvegarde, sécurité, facture et SEO sont volontairement
écartées de la sonde commerciale initiale ; elles restent présentes dans la
copie. La sonde charge WooCommerce, Polylang, Polylang for WooCommerce et le
plugin KH de variation unique, avec les résultats suivants :

| Contrôle privé | Résultat |
|---|---|
| Code retour / erreurs fatales | 0 / aucune |
| Produits publiés chargés par WooCommerce | 34 sur 34 |
| Langues des produits | 17 FR, 17 EN ; aucun produit sans langue |
| Groupes de traduction | 17 ; aucun manque FR ou EN |
| Variations publiées chargées | 46 sur 46 ; aucun parent orphelin |
| Commandes HPOS chargées | 5 sur 5 |
| Moyens de paiement disponibles | 0 |
| Requête HTTP de contrôle | bloquée par `kh2027_offline` |
| Courriel de contrôle | intercepté |

Le premier passage de cette sonde a échoué parce que son propre code n'avait pas
déclaré `$wpdb` dans la portée globale de WP-CLI. Le journal a identifié
`Undefined variable $wpdb`, puis `get_col()` sur `null`. La correction limitée à
la sonde a produit les résultats ci-dessus ; aucune correction WordPress ou donnée
métier n'a été nécessaire pour ce défaut de test.

## Intégrité source après le drill

Une comparaison en lecture seule avec le manifeste de capture retrouve 20598
fichiers courants sur 20598 attendus, sans ajout ni suppression. Un fichier a
changé depuis la capture ; son chemin exact n'a pas encore été récupéré avant
l'expiration de la session cPanel source. Ce résultat ne prouve donc pas encore
une identité complète des sources après drill. Aucune commande du drill n'avait
de droit d'écriture sur la racine source, mais cette explication technique ne
remplace pas l'identification de l'écart.

L'anonymisation de la base de test, la recette HTTP/FR/EN/panier/formulaires, un
certificat valide et la vérification d'une copie hors hébergement restent à
réaliser. G0 reste ouvert.

Sources des opérations cPanel : [création de base](https://api.docs.cpanel.net/specifications/cpanel.openapi/database-management/mysql-create_database),
[création d'utilisateur](https://api.docs.cpanel.net/specifications/cpanel.openapi/user-management/mysql-create_user),
[droits sur une base](https://api.docs.cpanel.net/specifications/cpanel.openapi/user-management/mysql-set_privileges_on_database).

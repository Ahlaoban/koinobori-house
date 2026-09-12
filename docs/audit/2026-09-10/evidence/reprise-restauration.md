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
changé depuis la capture. La lecture du rapport privé le 11 septembre identifie
le journal `wp-content/uploads/wc-logs/wc-analytics-order-import-2026-09-10-*.log`.
Les 20597 autres fichiers sont inchangés dans cette comparaison. Le contenu du
journal n'a pas été extrait ; l'origine exacte de son écriture n'est pas attribuée.
Ce constat porte sur la comparaison après drill enregistrée le 10 septembre,
et ne constitue pas une nouvelle empreinte de la source au 11 septembre.

## Reprise du 11 septembre : dépôt et diagnostic accueil

Après autorisation explicite de l'envoi, les commits `e3243e0` et `1002681` sont
publiés sur `codex/kh2027-reprise` ; la branche distante a été vérifiée au commit
`1002681ced15f91f67cb1b753a77e347d2484418`. La [PR 14](https://github.com/Ahlaoban/koinobori-house/pull/14)
est en brouillon, sans fusion ni déploiement.

La sonde `probe_frontpage.php`, contrôlée par PHP puis exécutée dans le runtime
privé, confirme les options brutes `show_on_front=page`, `page_on_front=318` et
`page_for_posts=0`. Les pages 318 FR et 319 EN sont publiées, liées dans les deux
sens ; le modèle Polylang calcule respectivement 318 et 319 comme accueils.

Un premier rendu PHP des routes `/fr/` et `/en/`, avec le thème enfant restauré
et une sélection réduite d'extensions, reproduit pourtant le blog vide :
`front_page=true`, `blog_home=true`, `queried_id=0`, bonne langue courante,
aucune redirection et aucune erreur fatale. HTML généré : 45884 et 45845 octets.
Le rendu reste privé, sous PHP avec réseau/courriel désactivés. Ce test du
bootstrap PHP ne valide ni le serveur HTTP, ni TLS, ni l'affichage navigateur.
La trace relève `show_on_front=posts` après filtrage, avec le callback
`PLL_Choose_Lang_Url::filter_option_show_on_front`. Dans le code installé de
Polylang 3.8.7, `src/frontend/frontend-static-pages.php:173` réserve la résolution
de l'accueil racine aux options `redirect_lang` ou `hide_default`. Ici,
`force_lang=1`, `rewrite=true`, `hide_default=false`, `redirect_lang=false`.
Les pages traduites existent ; c'est le choix d'URL qui laisse les racines sur le
blog. Ce résultat remplace l'hypothèse précédente d'un défaut de traduction/cache.

Le test limité à une requête avec `redirect_lang=true` retrouve les pages 318/319,
mais réclame une redirection tant que les URLs de langues en cache ne sont pas
recalculées. `set_clone_frontpage.php` a ensuite sauvegardé les options dans un
fichier privé exclusif, modifié seulement `redirect_lang` de `false` à `true`,
vérifié la valeur enregistrée puis vidé le cache par
`PLL()->model->clean_languages_cache()`.

La sonde `probe_render.php` versionnée a été transférée avec une empreinte SHA-256
identique (`19d3731273052cbae7421964a69b9828f93b159941a72cefee4e78da6b0f9b11`),
validée par `php -l`, puis exécutée sans forçage d'option :

| Contrôle | FR | EN |
|---|---|---|
| Page interrogée | 318 | 319 |
| Accueil statique / blog | vrai / faux | vrai / faux |
| Langue | fr | en |
| Erreur fatale / 404 / redirection | aucune / non / 0 | aucune / non / 0 |
| HTML généré | 50009 octets | 49773 octets |
| Classe de page attendue | `page-id-318` | `page-id-319` |
| Message de blog vide | absent | absent |
| Canonique sur l'hôte privé | `/fr/` | `/en/` |
| Alternatives de langues sur l'hôte privé | fr `/fr/`, en `/en/`, x-default `/` | identiques |

Les sondes de lecture et de correction ont également passé `php -l`. La correction
concerne exclusivement le clone privé ; aucun réglage du staging ou de production
n'a été changé. Le rendu avec les extensions écartées, les cookies, formulaires,
paiements de test et parcours navigateur reste à recetter.

La sonde commerciale relancée après correction retrouve 34 produits chargés,
46 variations sans orphelin, 17 paires FR/EN complètes et 5 commandes HPOS chargées.
Paiements disponibles : 0 ; HTTP : `kh2027_offline` ; courriel intercepté : vrai.
La racine publique conserve `Require all denied` et le fichier de retour des
options a les permissions `0600`. Les empreintes des deux autres scripts
exécutés correspondent aussi aux fichiers locaux : lecture
`9b089950afada0c2ffe4691d6921dcde317bf711e51f67c80f0a6592ff94ab45`, correction
`6616fe87dfb1364f28a42c9339ea07345d3748c48a0a6f67fc10e5f84fde36ae`.

L'anonymisation de la base de test, la recette HTTP/FR/EN/panier/formulaires, un
certificat valide et la vérification d'une copie hors hébergement restent à
réaliser. G0 reste ouvert.

Sources des opérations cPanel : [création de base](https://api.docs.cpanel.net/specifications/cpanel.openapi/database-management/mysql-create_database),
[création d'utilisateur](https://api.docs.cpanel.net/specifications/cpanel.openapi/user-management/mysql-create_user),
[droits sur une base](https://api.docs.cpanel.net/specifications/cpanel.openapi/user-management/mysql-set_privileges_on_database).

## Reprise du 12 septembre : assainissement et contrôles

L'autorisation de poursuivre a été suivie d'un inventaire des colonnes, clés de
métadonnées et familles d'options, sans extraction des coordonnées dans les
preuves publiques. Le premier prototype d'anonymisation reste désactivé. Le
nouveau `tools/recovery/sanitize_clone_db.php` charge seulement mysqli sous PHP
restreint, contrôle le compte/base/répertoire cible et refuse les tables absentes
ou non transactionnelles. Les noms réels `fsmpt_email_logs` et
`wc_email_unsubscribes` ont été rapprochés du schéma avant toute mutation.

Une sauvegarde privée de l'état courant, incluant la correction Polylang, a été
exportée et relue : 94 tables, 8099578 octets SQL, 1738844 octets gzip,
SHA-256 `2944efcab4e1dae9829ea5482f365398e738c9dcd581cf29df9e58a915dcc24c`.
L'empreinte de la base avant/après export est identique. Le nettoyage a d'abord
été exécuté dans une transaction annulée : les 40 invariants métier passent,
puis les empreintes des 94 tables correspondent exactement à l'état initial.
L'application a ensuite réussi avec les mêmes invariants.

Les contrôles résiduels ont identifié des familles supplémentaires, notamment
`woocommerce-ppcp-*` (tirets), `wc_stripe*`, `cmplz_wsc*`, les réglages PDF et un
historique de notification WordPress. La deuxième passe dispose de sa propre
sauvegarde vérifiée de l'état intermédiaire et d'une répétition avec annulation
exacte réussie. Elle retire 39 options supplémentaires et remplace une adresse
de courriel de réglage. Le script final exécuté et local a le SHA-256
`658fd64275991aab9e2a1c9c34e49e7f7d3755386f5802a0cb8093a5d27a5161`.

Le traitement conserve les IDs et relations, les états/dates/montants des
commandes, les produits et variations, les traductions et les réglages de
livraison/taxe. Les profils, adresses et commentaires sont factices ; anciens
mots de passe, sessions, données de formulaires, traces, clés et connexions
d'intégration sont neutralisés dans le clone. Les archives et mappings privés
permettent le retour : il s'agit d'une pseudonymisation de test, pas d'une
affirmation d'anonymat irréversible.

95 fichiers de logs, caches et anciennes configurations ont été déplacés vers
`kh2027-private/quarantine-20260912`, hors runtime et hors Web. Le manifeste relu
confirme leurs empreintes et celles des 20501 fichiers conservés. Cela comprend
les traces Wordfence, Updraft, LiteSpeed, imports WooCommerce, caches PDF et
anciens fichiers `.htaccess`/WAF. Le runtime reçoit une nouvelle interdiction
Web ; la racine `public_html` reste elle aussi interdite. Aucun fichier source
du staging ou de production n'est concerné.

### Recette après assainissement

| Contrôle | Résultat |
|---|---|
| Produits publiés chargés | 34/34 |
| Variations chargées / orphelines | 46/46 ; 0 orpheline |
| Paires de traductions | 17 complètes FR/EN |
| Commandes HPOS chargées | 5/5 |
| Paiements disponibles | 0 |
| Requête HTTP / courriel de contrôle | `kh2027_offline` / intercepté |
| Accueils FR / EN | pages 318 / 319 ; bonnes langues |
| Erreurs fatales / 404 / redirections | aucune / aucune / 0 |
| HTML FR / EN | 50009 / 49773 octets |

La sonde de rendu est la version précédente avec seulement les noms de fichiers
de sortie datés du 12 septembre. Les processus PHP ont tous terminé avec le
code 0. Ces tests ne remplacent pas une recette navigateur avec JavaScript,
formulaires, panier et cookies.

Après cette recette, le scan des colonnes textuelles ne retrouve aucun des
marqueurs personnels collectés avant nettoyage (valeurs de six caractères ou
plus dans profils et coordonnées). Il retrouve seulement des courriels dans
45 contenus : 23 pages publiées et 22 révisions, contacts professionnels KH et
CN2C déjà présents dans le contenu public ; aucun marqueur client n'y apparaît.
Ce scan par marqueurs et courriels ne constitue pas un détecteur universel de
données personnelles ou de secrets inconnus.

Rapports conservés dans `kh2027-private/runtime-control` :

- reçus et dumps `before-sanitize-20260912*`, `before-sanitize-v2-20260912*` ;
- plans et journaux `sanitize-plan*`, `sanitize-rehearse*`, `sanitize-apply*` ;
- manifestes `quarantine-before-20260912.json`, `quarantine-result-20260912.json` ;
- `commerce-after-sanitize-20260912.json`, `render-fr-20260912.json`, `render-en-20260912.json` ;
- `scan-residuals-final-20260912.py`, `sanitize-residuals-final-20260912.json`.

### Blocages encore ouverts

La simulation Let's Encrypt `http-01`, limitée au seul domaine de test, échoue
de nouveau sur `SERVFAIL looking up CAA for universe.wf`. Aucun certificat de
production n'a été demandé et aucune alerte TLS n'a été contournée. La possibilité
d'un CAA propre au sous-domaine reste à vérifier : la recherche CAA s'arrête au
premier jeu d'enregistrements trouvé selon la
[RFC 8659, section 3](https://www.rfc-editor.org/rfc/rfc8659.html#section-3).
La session cPanel a expiré avant cette vérification ; aucune zone DNS n'a changé.

Le Google Drive connecté contient un dossier UpdraftPlus, mais sa liste de
fichiers est vide. La recherche d'archives `backup_` et `KH2027` ne renvoie aucun
résultat. La configuration copiée nomme bien le dossier UpdraftPlus et une
instance activée, mais cela ne prouve ni la présence des archives dans le bon
compte ni leur intégrité. Aucun jeton copié n'a été réutilisé pour contacter
Google. Le compte destinataire doit être rapproché de la connexion disponible.

G0 reste ouvert pour TLS, l'accès de test protégé, la recette navigateur et la
preuve hors hébergement. Les opérations privées de cette journée ne changent
pas le staging ou la production. La PR 14 reste en brouillon, sans fusion.

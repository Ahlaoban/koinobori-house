# S19 — audit serveur cPanel en lecture seule

10 septembre 2026, session Chrome fournie par Alain, serveur o2switch cow. Relevé manuel des écrans cPanel/JetBackup et sorties visibles du terminal. Les résultats ci-dessous sont des transcriptions de lecture, pas un export exhaustif du serveur. Aucun mot de passe, jeton de session, contenu de commande client ou site tiers n'est conservé. Aucun réglage, fichier applicatif, compte ou base créé/modifié par une commande d'audit. Les lectures WordPress peuvent produire des journaux techniques ordinaires ; elles ne constituent pas une preuve de stabilité binaire intégrale du serveur.

## Racines, bases et versions

`realpath`, `wp core version`, `wp config get DB_NAME`, `wp config get table_prefix` et inventaires WP-CLI avec `--skip-plugins --skip-themes` :

| Environnement | Racine réelle | Base | Préfixe | WordPress |
|---|---|---|---|---|
| Production | `/home3/heal3867/koinoborihouse.com` | `heal3867_wp354` | `wprs_` | 7.1 |
| Staging | `/home3/heal3867/staging.koinoborihouse.com` | `heal3867_wp551` | `wprs_` | 7.1 |

Le compte principal est partagé avec d'autres sites, hors périmètre de cet audit. Production et staging KH ont des racines/bases distinctes mais appartiennent au même compte d'hébergement : cette séparation ne fournit pas un isolement de comptes système.

Production, résultat `wp plugin list --fields=name,status,version --format=csv` : UpdraftPlus actif 1.26.5 et Wordfence actif 8.2.2 seulement. WooCommerce et Polylang ne sont pas installés dans cet inventaire. Thèmes : Kadence parent 1.5.0, koinobori-child actif 1.0.1, Twenty Twenty-Five inactif 1.5.

Staging : inventaire UI des 14 extensions dans S16. Inventaire direct de `wp-content` : seul PHP à la racine `index.php`, aucun drop-in PHP de cache observé ; répertoire MU contenant seulement `koino-lang-redirect.php`.

Outils serveur disponibles : `/usr/local/bin/wp`, `/usr/local/bin/php`, `/bin/python3`. Python serveur 3.6 selon traceback ; un premier script de lint a échoué parce que `capture_output` n'y est pas accepté. Relance avec `check_output`, résultat « No syntax errors detected » pour les trois fichiers : thème enfant `functions.php`, MU routage, extension `kh-single-variation-display.php`. Cette vérification n'exécute pas les parcours WordPress et n'atteste pas leur correction fonctionnelle.

## Comparaison de neuf chemins spécifiques avec le dépôt

Source locale : baseline d3073da, fichiers sous `wp/`. Destination : mêmes chemins sous `wp-content/`. SHA-256 calculés sur les fichiers complets. La première comparaison binaire brute donnait des différences sur tous les fichiers présents ; contrôle répété après conversion CRLF en LF des deux côtés. Le tableau rapporte uniquement cette seconde comparaison, pour ne pas confondre fins de ligne et changements de contenu.

| Chemin relatif à wp-content | Production | Staging |
|---|---|---|
| mu-plugins/koino-bcdg-hyphen.php | Absent | Absent |
| mu-plugins/koino-diag-frontpage.php | Absent | Absent |
| mu-plugins/koino-lang-redirect.php | Absent | Identique après normalisation |
| plugins/kh-single-variation-display/kh-single-variation-display.php | Absent | Identique après normalisation |
| themes/koinobori-child/assets/css/kh-charte-v3.css | Absent | Absent |
| themes/koinobori-child/assets/css/kh-foundations.css | Absent | Identique après normalisation |
| themes/koinobori-child/functions.php | Différent | Différent |
| themes/koinobori-child/style.css | Différent | Identique après normalisation |
| themes/koinobori-child/theme.json | Absent | Différent |

Ainsi staging : 4 correspondances, 2 différences de contenu, 3 absences. Production : 2 différences et 7 absences. L'absence de charte v3 est désormais vérifiée dans le système de fichiers. Ce contrôle n'identifie pas le commit exact déployé, la différence ligne par ligne, ni les éventuels fichiers additionnels. Ne pas écraser functions.php/theme.json avant récupération et rapprochement de leur contenu réel.

## Compteurs actuels de données staging

Requête SELECT, aucun contenu de client lu :

```sql
SELECT post_type, post_status, COUNT(*) AS n
FROM wprs_posts GROUP BY post_type, post_status;
SELECT status, COUNT(*) AS n FROM wprs_wc_orders GROUP BY status;
```

| Objet | Statut | Nombre |
|---|---|---:|
| attachment | inherit | 19 |
| customize_changeset | trash | 2 |
| nav_menu_item | publish | 14 |
| page | draft / publish / trash | 3 / 40 / 1 |
| post | auto-draft | 1 |
| product | draft / publish | 1 / 34 |
| product_variation | publish | 46 |
| revision | inherit | 57 |
| shop_order_placehold | draft | 5 |
| wp_global_styles | publish | 1 |
| wp_navigation | publish | 1 |
| HPOS wc_orders | wc-checkout-draft | 4 |
| HPOS wc_orders | wc-completed | 1 |

La commande terminée est un fait de base ; son caractère réel ou de test n'est pas établi. Préserver les cinq enregistrements HPOS, leurs dépendances et leur historique. Aucun total de stock, nom, adresse, e-mail, montant ou référence personnelle extrait. Ces compteurs sont actuels et ne décrivent pas automatiquement une archive du 09/09.

## Sauvegardes et capacité

JetBackup 5.4.1.4, cPanel 134.0.55. Tableau Bases de données :

| Base confirmée par wp-config | Sauvegardes listées | Dernière date affichée | Type / destination | Taille affichée |
|---|---:|---|---|---:|
| Production heal3867_wp354 | 32 | 09/09/2026 01:20 PM | Daily, Incremental, Journalier-Externe | 1,11 MB |
| Staging heal3867_wp551 | 32 | 09/09/2026 01:20 PM | Daily, Incremental, Journalier-Externe | 1,49 MB |

Le tableau de bord annonce 67 sauvegardes au niveau du compte : ne pas interpréter ce chiffre comme 67 copies complètes de KH. Aucun Restore/Download lancé, aucun ensemble extrait ni décompressé. Le nom de destination « Externe » n'est pas une preuve indépendante de disponibilité hors incident hébergeur.

Le répertoire staging `wp-content/updraft` existe, mais contient **0 fichier direct dont le nom commence par `backup_`** lors du contrôle Python. La présence dans l'interface Updraft (S16) n'implique pas la présence d'une archive locale. Ne pas extrapoler cette vérification à un répertoire personnalisé, à des sous-dossiers ou à Drive. Intégrité de la copie externe et restauration toujours non vérifiées.

`shutil.disk_usage` sur ce chemin donne total 3 937 625 169 920 octets, utilisés 2 391 141 527 552, libres 1 346 434 375 680. C'est la capacité du système de fichiers visible, **pas le quota réellement alloué au compte ou au clone**. Aucune assurance de capacité dédiée n'en découle.

Mon Univers Web affiche 8 lunes gratuites au total, 2 actives et **6 restantes**, avec des emplacements inactifs « Activer la lune ». Aucune activation ni commande effectuée. Les sous-comptes déjà actifs sont hors périmètre. L'interface propose une séparation de comptes ; quota, contrôle des sorties, accès de secours et destination définitive restent à vérifier avant import. [Documentation officielle o2switch](https://faq.o2switch.fr/cpanel/o2switch/univers-web-sous-comptes/).

## Permissions et protection Web

Lecture `stat.S_IMODE`, limitée aux chemins nommés :

| Chemin | Production | Staging |
|---|---|---|
| wp-config.php | 0644 | 0644 |
| .htaccess | 0644 | 0644 |
| wp-content | 0755 | 0755 |
| wp-content/uploads | **0777** | **0777** |

Détection de directives dans le seul `.htaccess` racine, sans afficher leur contenu : staging contient `AuthType` et `Require valid-user` ; production non. Aucun `Options … -Indexes` détecté dans ces deux fichiers. Cela ne prouve pas qu'un listing est publiquement accessible : configuration héritée, index et règles serveur non inventoriés. La protection staging concorde avec le 401 public S06.

**R — Corriger les permissions trop larges après vérification du propriétaire, de l'utilisateur PHP et essais d'upload sur clone ; renforcer l'accès à wp-config.** Ne pas faire de chmod récursif aveugle. L'exploitation des permissions 777 n'a pas été testée ; aucune compromission affirmée. [Principes officiels WordPress](https://developer.wordpress.org/advanced-administration/server/file-permissions/).

## Empreintes du cœur WordPress

`wp core verify-checksums` exécuté pour chacune des deux racines, avec timeout de 45 secondes et affichage des 900 derniers caractères par site. Les deux commandes ont terminé et affiché **Success: WordPress installation verifies against checksums.** Des avertissements signalent toutefois des fichiers supplémentaires `error_log` : la fin de sortie production montre `wp-includes/block-supports/`, `widgets/`, `IXR/`, `PHPMailer/` et `wp-includes/` ; staging montre `wp-admin/error_log`. La sortie production étant tronquée, cette liste n'est pas exhaustive. Aucun contenu de log lu ni supprimé ; exposition HTTP non testée. Le succès porte sur les fichiers du cœur couverts par le manifeste officiel, pas sur l'innocuité des fichiers supplémentaires, des extensions ou de la base.

## Limites finales

Archives et copie Drive non récupérées ; restauration/retour non exécutés ; quota et trafic sortant du futur environnement non démontrés ; fichiers PHP distants non rapprochés ligne par ligne ; droits SQL, comptes/MFA, tâches système et configurations héritées non exhaustifs ; contrats/licences et capacité humaine non fournis. Les faits cPanel remplacent les anciennes mentions d'accès serveur manquant, sans effacer ces limites.

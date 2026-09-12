# Reprise isolée KH2027

Outils d’exploitation, exclus du répertoire `wp/` : ils ne sont pas destinés au déploiement du site. A01 approuvée par Alain le10/09/2026. Production et staging restent des sources à préserver.

## Capture privée

`capture_snapshot.py` est prévu pour Python3.6+ sur le compte source cPanel. Racine source et destination sont explicites dans le fichier ; toute adaptation de chemin exige une vérification des domaines et racines réels. Il ne restaure rien.

- Crée un nouveau répertoire daté, permissions0700, sous `/home3/heal3867/kh2027-private`, hors des racines Web identifiées. Refuse une collision, un lien symbolique ou des permissions privées incorrectes.
- Calcule les empreintes de chaque fichier source avant et après la capture. Sauvegarde tout le répertoire, notamment configuration, fichiers cachés et médias. Aucun contenu privé n’est envoyé dans Git ou dans les résultats affichés.
- Exporte seulement la base configurée du staging. Utilise une transaction pour une base entièrement InnoDB ; la table mémoire `wprs_wfls_role_counts` constatée impose ici les verrous de lecture de tables de cette seule base, le temps de l’export. Aucun verrou global du serveur et aucune suppression.
- Relit le SQL compressé et tous les membres de l’archive, compare leurs empreintes et leur exhaustivité. Conserve les résultats et les journaux privés même en cas d’échec ; aucun nettoyage automatique.

Les verrous de lecture peuvent retarder brièvement des écritures staging pendant l’export. Ils ne protègent pas les fichiers : le manifeste avant/après sert à détecter leur dérive. Ne pas lancer de modification de code ou d’upload pendant la capture. En cas d’écart, conserver l’ensemble comme non validé et réévaluer la fenêtre de capture.

Référence : [mariadb-dump](https://mariadb.com/docs/server/clients-and-utilities/backup-restore-and-import-clients/mariadb-dump). Une transaction seule ne rend pas les tables MEMORY cohérentes ; `--lock-tables` porte sur la base exportée et ne garantit pas la cohérence entre plusieurs bases.

### Vérifications locales

```text
python -m unittest discover -s tools/recovery -p "test_*.py" -v
```

Les tests couvrent lecture fidèle, contenu altéré, archive incomplète et chemin sortant. Ils ne simulent pas MariaDB, les verrous, l’hébergement ou la restauration WordPress. L’exécution serveur doit conserver son propre procès-verbal, distinct des résultats locaux.

## Avant de restaurer

1. Sous-compte indépendant actif, espace réel suffisant, droits SQL limités à une nouvelle base, accès d’administration récupérable par Alain.
2. Destination vérifiée : aucune racine, base ou compte partagé avec production/staging. Répertoire Web interdit d’accès avant toute copie de configuration ou de données.
3. Neutralisation avant premier démarrage : tâches système absentes, WP-Cron arrêté, SMTP/paiements/webhooks désactivés pour le clone, sorties HTTP et connexions directes contrôlées. Les filtres WordPress seuls ne constituent pas un pare-feu réseau ; vérifier aussi les capacités PHP/hébergeur.
4. Configuration neuve spécifique au clone. Garder la configuration source uniquement dans l’archive privée ; ne jamais démarrer le clone avec les identifiants SQL sources ou les clés réelles.
5. Restaurer et comparer les données du snapshot, y compris relations Polylang et commandes HPOS ; traiter les données personnelles avant ouverture aux tests. Les compteurs actuels ne sont pas automatiquement ceux de l’archive.
6. Tester les parcours et un retour sur le clone, puis contrôler l’intégrité des sources. Conserver dates, résultats et durée réelle.

**Une archive lisible n’est pas une restauration réussie.** La copie hors hébergement, le drill et le retour restent des preuves distinctes. Ne pas utiliser ces outils pour une copie production vers staging ou un déploiement public.

## Réception et extraction privée

`receive_snapshot.py` reçoit un seul flux dans un fichier privé créé exclusivement,
avec une limite de 256 MiB et un délai de 180 secondes au point d'entrée serveur.
Les restrictions SSH et les permissions du compte sont des contrôles distincts à
vérifier sur le serveur. L'autorisation de transfert figure dans
`docs/audit/2026-09-10/TRANSFERT-ISOLE.md`.

`stage_snapshot.stage(bundle, expected_hash, destination)` vérifie le SHA-256 de
transport relevé sur la source, les cinq membres attendus, les empreintes des
archives et l'identité des manifestes avant/après. Il relit entièrement le gzip
SQL (limite décompressée : 1 GiB), puis extrait les fichiers dans une nouvelle
destination privée hors Web. L'appelant doit vérifier cet emplacement sur le
serveur ; la fonction n'inventorie pas les racines des domaines. Un dossier
existant n'est jamais réutilisé. Un échec conserve les fichiers partiels, sans
produire de `stage-result.json` de succès.

Les tests locaux couvrent capture, réception et extraction, dont un transfert
complet sur données fictives, la corruption du transport ou du SQL, les archives
altérées, les manifestes divergents et la préservation d'une destination existante.
Ils n'exécutent ni PHP ni import SQL. Les permissions POSIX, l'isolation réseau,
MariaDB et les parcours WordPress restent à vérifier dans l'environnement cible.

Les noms contenant un antislash littéral sont préservés sur POSIX uniquement.
Ils sont refusés sur Windows, où leur interprétation comme séparateur modifierait
le chemin. Deux fichiers de l'ancien dossier `wp-content/upgrade` ont révélé ce
cas pendant le drill serveur.

`dump_counts.py` compte les tuples du format INSERT émis par le dump du staging,
y compris les valeurs sur plusieurs lignes et la ponctuation dans les chaînes.
Il n'exécute pas de SQL et refuse les formes non prises en charge. Son résultat
doit être comparé aux comptes SQL réels, pas interprété comme une validation des
valeurs métier ou des relations. La suite locale comprend désormais **29 tests**.

Le procès-verbal serveur, distinct des tests locaux, est conservé dans
`docs/audit/2026-09-10/evidence/reprise-restauration.md`. Les scripts opérationnels,
les archives, les identifiants et les journaux SQL détaillés restent dans le
répertoire privé du compte de test. Aucun de ces secrets ou dumps ne doit être
copié dans ce dépôt.

## Diagnostic et correction de l'accueil privé

`probe_frontpage.php` lit uniquement les trois options WordPress de lecture, les
identifiants/statuts/langues de la paire d'accueil et les propriétés de pages
statiques calculées par Polylang. Les gardes `KH2027_SANDBOX`, environnement
`local` et hôte `.invalid` empêchent son emploi involontaire sur les sites source.
Cette sonde permet de distinguer option incorrecte, liaison de traduction absente
et cache de langues incohérent avant d'essayer une correction sur le clone.

`probe_render.php` se lance directement avec le PHP restreint du runtime et
un seul argument `/fr/` ou `/en/`. Il charge WordPress et le thème restauré,
limite les extensions à WooCommerce/Polylang/PLLWC/variation KH, contrôle les
gardes puis enregistre HTML et bilan dans le répertoire privé. Les redirections
sont comptées et interceptées. La sonde n'ouvre aucun serveur Web : elle vérifie
la requête PHP, pas TLS, JavaScript, les interactions ou l'affichage navigateur.

`set_clone_frontpage.php` corrige uniquement `polylang.redirect_lang` de `false`
à `true` dans la base isolée nommément contrôlée. Il vérifie la paire publiée
318 FR / 319 EN et les protections HTTP/courriel, sauvegarde les options dans un
fichier privé créé sans écrasement, puis vide le cache des langues. Le dump
restaurable et ce fichier permettent le retour. Le script se lance par WP-CLI
dans le runtime privé, sous le même PHP restreint que les sondes précédentes.

Les scripts de cette section sont propres au clone du drill. Ils ne constituent
pas une procédure de déploiement de staging ou production. L'anonymisation, TLS
et la recette navigateur restent nécessaires avant de rendre le clone accessible.

## Assainissement de la base privée

`sanitize_clone_db.php` s'exécute directement avec le PHP restreint, sans charger
WordPress ni les extensions. Il exige le répertoire de contrôle privé, la base
`sc3heal3867_kh2027drill`, son utilisateur SQL dédié, 94 tables et une racine Web
encore interdite. Chaque table modifiée doit être InnoDB ; une table ou colonne
manquante arrête le traitement. La table MEMORY de compteurs de rôles reste intacte.

Les modes sont `plan` (lecture seule), `rehearse` (modifications puis annulation
vérifiée) et `apply` (validation de la transaction). Les deux derniers exigent en
second argument un reçu JSON privé contenant `database`, `fingerprint`, `file`,
`sha256` et `gzip_verified`. Le reçu est créé seulement après un export SQL du
clone courant, la relecture intégrale du gzip et la comparaison des empreintes
de la base avant/après export. Ne pas fabriquer ce reçu depuis un ancien dump.
Les sauvegardes contiennent des données privées et restent hors Web et hors Git.

Le script remplace les profils et coordonnées par des valeurs de test, invalide
les anciens mots de passe, vide sessions, journaux, soumissions et clés copiées,
retire les intégrations de paiement/courriel/sauvegarde et les notifications de
formulaires. Les anciennes clés de commandes deviennent des clés de test ; elles
ne constituent pas une protection d'accès. Les IDs, liens clients/commandes,
montants, états, pays, produits, pages, traductions et configurations de
livraison/taxe sont conservés par 40 contrôles d'empreintes. La répétition compare
également les 94 tables après `ROLLBACK` à leur état initial.

Ce traitement est spécifique au schéma inspecté, pas un anonymiseur universel.
La conservation d'IDs, de dates et des archives de retour justifie le terme
« données de test pseudonymisées ». Un contrôle des traces résiduelles et des
fichiers reste nécessaire. Les contacts professionnels déjà présents dans les
pages publiques ne sont pas remplacés. Les fichiers de logs, caches et anciennes
configurations ont été déplacés séparément en quarantaine privée avec manifeste
et vérification des empreintes, puis les sondes WordPress ont été relancées.

Le reçu, les rapports des transactions, la quarantaine, le scan et les résultats
de recette du 12 septembre sont référencés dans le procès-verbal S21. Ils ne
valident ni TLS, ni les parcours interactifs, ni la copie hors hébergement.

## Contrôle PHP avant recette Web

`probe_web_runtime.php` est une page temporaire de diagnostic autonome, à placer
uniquement derrière une authentification Apache et HTTPS. Elle ne charge pas
WordPress, ne lit aucun identifiant, ne contacte aucun service et ne tente aucun
envoi. Elle contrôle les capacités PHP effectives plutôt que les seuls réglages
affichés dans le panneau d'hébergement. Sans identité Apache ou HTTPS, elle refuse
la requête Web. Les sorties sont non indexables, sans cache ni ressource externe.

La sonde distingue mail, HTTP direct, sockets/FTP, exécution de commandes,
lecture d'URL, restriction de chemins et affichage d'erreurs. Un chemin restreint
non vide ne prouve pas que sa liste est correcte : la contrôler séparément. Ces
contrôles ne constituent pas un pare-feu universel et ne couvrent pas toutes les
extensions pouvant ouvrir une connexion. Les extensions autorisées, la base
locale et les gardes WordPress restent des contrôles distincts.

Vérification locale : syntaxe PHP valide ; configuration PHP sans restrictions
refusée par le bilan ; mêmes vérifications satisfaites avec les fonctions
désactivées et les options restrictives. Ces essais CLI n'attestent pas les
réglages du PHP Web : le bilan du navigateur authentifié reste indispensable.

Ne pas confondre l'ajout d'un `Require all denied` au même niveau que
`Require valid-user` avec une interdiction absolue : les directives peuvent se
combiner par un OU implicite. Limiter explicitement les fichiers accessibles
pendant la préparation et tester les réponses sans authentification.
Références : [autorisation Apache](https://httpd.apache.org/docs/2.4/mod/mod_authz_core.html),
[HTTPS obligatoire](https://httpd.apache.org/docs/2.4/mod/mod_ssl.html#sslrequiressl).
Retirer la sonde Web après la recette ; conserver son code dans les outils.

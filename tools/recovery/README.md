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

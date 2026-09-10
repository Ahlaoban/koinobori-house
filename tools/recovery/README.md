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

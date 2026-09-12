# G0 — dossier de préparation d’une restauration isolée

Statut actualisé après S21 : capture, transfert, extraction, import, rapprochement,
retour SQL et démarrage privé du socle WordPress/WooCommerce réussis. Les preuves
et limites sont dans [S20](evidence/reprise-apres-validation.md) puis
[S21](evidence/reprise-restauration.md), qui font autorité sur l'avancement. La
recette Web, l'anonymisation, l'écart d'un fichier source et la copie hors
hébergement maintiennent G0 ouvert. Auteur : Codex. Ce document précise le
protocole du [rapport §9](RAPPORT-AUDIT-KH2027.md#9-plan-de-reprise-et-de-retour--protocole-proposé-pas-exécuté).

## Résultat attendu

Pouvoir reconstituer un état identifié du site sur une destination indépendante, montrer que ses données et fonctions ont été reprises, puis mesurer le temps de reprise. Production et staging doivent conserver leurs données et réglages. Une restauration fidèle peut reproduire les défauts d’accueil et de traduction : les corriger ne fait pas partie du test de restauration.

Le choix de destination sera arrêté après inventaire cPanel. Aucune URL de clone, base ou arborescence n’est présumée disponible. Pas de création d’infrastructure, installation locale ni achat lancé.

## Ce qui est établi et ce qui manque

| Élément | Preuve actuelle | État / suite |
|---|---|---|
| Dépôt | HEAD d3073da ; 165 fichiers suivis inchangés ; seul dossier d’audit ajouté | Vérifié ; conserver ce point de référence |
| Updraft staging | Neuf ensembles listés ; ensemble complet du 09/09 17h57 ; base du 10/09 09h30 | Présence dans l’interface vérifiée, contenu des archives non vérifié |
| Couverture | Base et wp-content ; fichiers hebdomadaires rétention4, base quotidienne rétention7 | wp-config.php, configuration serveur, protections et fichiers hors wp-content à inventorier séparément |
| Copie externe | Google Drive sélectionné et déclaré connecté | Présence, taille, intégrité et accès aux objets distants à vérifier |
| JetBackup | S19 : 32 copies par base KH ; dernières affichées09/09 ; bases directement associées aux sites | Contenu, cohérence fichiers/base et restauration non vérifiés |
| Ancien drill | Réussite déclarée en juin sur production alors vide | Ne démontre pas la reprise du staging actuel ; ne pas répéter sur les sites existants |
| Destination isolée | S21 : sous-compte `sc3heal3867`, racine privée hors Web, UID et base dédiés | Extraction, SQL et runtime privés vérifiés ; racine Web toujours fermée |
| Runtime local | docker/php/wp/mysql/mariadb non trouvés dans PATH ; WSL indique ne pas être installé | Aucun environnement WordPress local prêt n’a été identifié ; aucun logiciel installé |
| Sorties du clone | S21 : garde MU, HTTP/courriel/paiement/Action Scheduler bloqués ; PHP CLI sans cURL, sockets, mail ni processus | Prouvé sur le socle commercial privé ; à maintenir et éprouver pendant la recette Web |

Sources : [S19](evidence/serveur-cpanel.md), [S16](evidence/staging-authentifie.md), [S17](evidence/staging-complement.md), [KH-102 historique](../../lot1/KH-102-sauvegardes.md), [contrôle des fichiers](evidence/final-validation.json). Le précontrôle local du présent tour ne certifie pas l’absence de logiciels hors PATH.

## Lecture cPanel — réalisée partiellement dans S19, contrôles restants

1. Identifier les domaines et leurs racines réelles, les bases associées et les utilisateurs SQL. Dans les preuves versionnées, conserver seulement les informations techniques nécessaires ; jamais les mots de passe, jetons ou URLs de session cPanel.
2. Confirmer quelles sauvegardes JetBackup couvrent chaque base et chaque arborescence. Vérifier leurs dates et distinguer une restauration dans l’emplacement d’origine d’une extraction vers une destination indépendante.
3. Relever versions PHP, espace disque, quota disponible et possibilités d’isoler fichiers, base, tâches et sorties réseau. Ne pas conclure qu’un simple sous-domaine ou une protection Basic suffit à l’isolation.
4. Identifier une destination existante réellement libre ou préparer une destination distincte. Vérifier ses chemins absolus, absence de liens vers les racines existantes et utilisateur SQL sans droits sur les bases sources. Si l’hébergement ne permet pas cette séparation et le contrôle des sorties, retenir un autre environnement de test avant d’importer.
5. Identifier le stockage privé des archives, accessible uniquement à l’opérateur. Ne pas déposer base, wp-config.php, clés, données personnelles ou archive complète dans Git, un répertoire Web ou ce dossier d’audit.

## Séquence d’exécution à compléter avec les chemins réels

| Étape | Action | Preuve de sortie obligatoire |
|---|---|---|
| R01 — Périmètre | Identifier source, destination et opérateur ; vérifier les travaux Claude ou autres écritures en cours | Correspondance domaine/racine/base ; fenêtre de capture sans écriture concurrente, ou mécanisme de snapshot cohérent documenté |
| R02 — Point de reprise | Conserver l’ensemble historique protégé ; choisir un ensemble cohérent pour le drill actuel, avec configuration nécessaire | Date, identifiant de lot, liste de toutes les parties, tailles et SHA-256, versions correspondantes |
| R03 — Intégrité | Vérifier archive par archive et toutes les parties fractionnées ; vérifier aussi la copie externe | Archive lisible, pas de partie manquante ; résultat d’intégrité consigné sans contenu privé |
| R04 — Référence des données | Relever un inventaire correspondant au moment exact du snapshot | Comptes par type/statut/langue, IDs et liens, variantes, formulaires, options critiques et commandes HPOS ; aucun total de stock |
| R05 — Isolement | Préparer destination privée, base indépendante et contrôle des sorties avant premier démarrage | Aucun chemin/compte SQL partagé ; paiements, mails, webhooks, relances et jobs sortants neutralisés ; accès opérateur restreint |
| R06 — Restauration | Restaurer vers la destination identifiée et adapter seulement les paramètres du clone | Journal et durée ; transformation des URLs compatible avec données sérialisées ; références originales sauvegardées |
| R07 — Comparaison | Contrôler l’état repris avant modifications métier ou anonymisation des fixtures | Écarts expliqués entre snapshot et clone ; aucun fichier, lien ou donnée métier perdu |
| R08 — Tests | Contrôler administration, pages FR/EN, produits/variantes, panier, formulaires et protections | Résultats datés par scénario ; aucune émission vers client/fournisseur ; défauts initiaux distingués des régressions |
| R09 — Retour | Sur le clone seulement, créer un marqueur de test, puis rétablir le point précédent | Marqueur absent après retour ; données de référence inchangées ; temps de reprise mesuré |
| R10 — Clôture | Vérifier sources intactes, documenter les limites et la conservation de l’environnement | Procès-verbal signé par opérateur ; décision G0 explicite, jamais déduite d’un bouton « succès » |

L’ensemble du 09/09 à 17h57 et la base du 10/09 à 09h30 sont deux points distincts. Ne pas les mélanger automatiquement. Si l’ensemble ancien est choisi, le comparer à son propre état historique ; les compteurs actuels ne sont pas des assertions exactes sur ce snapshot. Si la cohérence temporelle n’est pas démontrable, produire une nouvelle capture cohérente avant le drill, sans remplacer l’archive historique protégée.

Les 40 pages publiées, 34 produits publiés, 46 objets variation et huit formulaires relevés pendant l’audit constituent des repères datés, pas un manifeste de base complet. S19 complète les placeholders par un SELECT HPOS : quatre wc-checkout-draft et une wc-completed. La nature réelle/test de la commande terminée reste inconnue. Préserver chaque enregistrement et rapprocher ses relations au moment du snapshot, sans publier de données client.

La validation d’archive démontre son intégrité de lecture, pas la conformité métier ni l’absence de logiciel malveillant. Une copie hors hébergement doit être testée comme source de reprise, pas seulement apparaître dans un réglage.

## Conditions d’arrêt

- Destination correspondant à production/staging, base partagée ou droit d’écriture source non maîtrisé.
- Archive partielle, identité du lot incertaine, divergence inexpliquée ou version incompatible.
- Sorties réseau/SMTP/paiements non maîtrisées avant démarrage du clone.
- Capacité disque insuffisante, exposition Web d’une archive ou collecte non maîtrisée de secrets.
- Données commerciales ou formulaires absents après reprise, ou comparaison impossible faute d’inventaire du snapshot.

À l’arrêt : préserver les archives et journaux, ne pas relancer aveuglément une restauration et ne pas nettoyer les sources. Un échec documenté est un résultat de test, pas une raison d’effacer les preuves.

## Procès-verbal à remplir après exécution

| Champ | Valeur actuelle |
|---|---|
| Source / snapshot / opérateur | Racine staging et capture privée cohérente du 10/09/2026 identifiées S20 |
| Destination réelle / base indépendante | Sous-compte `sc3heal3867`, répertoires privés et base `sc3heal3867_kh2027drill` dédiés |
| Manifestes et empreintes | 20598 fichiers, 378364732 octets ; empreintes transport/site/base consignées en S20/S21 |
| Début de reprise / fin des vérifications | Capture 10/09/2026 10:33:11 UTC ; contrôles privés poursuivis le 11/09/2026 |
| Durée mesurée de reprise | Retour SQL du clone : 16,850 s ; RTO complet non mesuré |
| Point temporel récupéré / données postérieures à rapprocher | Capture cohérente S20 ; un fichier source courant modifié depuis, chemin à identifier |
| Résultat archive locale / copie externe | 0 fichier direct backup_ dans updraft staging ; copies externes listées, intégrité non vérifiée |
| Résultat pages, langues, produits, formulaires, comptes, commandes | 34 produits, 46 variations, 17 paires FR/EN et 5 commandes HPOS chargés ; pages Web, formulaires et comptes non recettés |
| Neutralisation des sorties / protection des données | Garde applicative et restrictions PHP prouvées en CLI ; anonymisation et recette HTTP non réalisées |
| Retour sur clone et intégrité des sources | Retour SQL fidèle sur 94 tables ; source : 20598 fichiers, 1 modifié à identifier |
| Écarts restants / décision G0 | Copie hors hébergement, anonymisation, TLS et recette Web manquent ; G0 ouvert |

Les propositions RPO ≤1h et RTO ≤4h du référentiel ne deviennent pas des engagements par ce document. Mesurer la fenêtre de perte et la durée réelle, puis décider si elles conviennent à l’activité. Une répétition sur clone ne prouve pas à elle seule le retour d’une production recevant des commandes : leur préservation et rapprochement devront être éprouvés avant toute future migration commerciale.

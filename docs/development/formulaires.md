# Formulaires Contact, Entreprises et Collectivités

## État au 16 septembre 2026

Intégration déployée dans le clone privé et rendu des six formulaires contrôlé
dans WordPress via WP-CLI et dans Chrome après rétablissement de l’accès privé.
Les six pages ont été vérifiées à 360 px ; les trois familles de formulaires ont
aussi été examinées sur ordinateur. Les parcours Entreprises et Collectivités
restent distincts. Un test du transport SMTP a été reçu, mais aucun formulaire
n’a encore été soumis de bout en bout. Aucun déploiement du site en production
n’a été réalisé.

| Parcours | FR | EN | Formulaires relevés dans le clone |
|---|---|---|---|
| Contact | `/fr/contact/` | `/en/contact-us/` | 5 / 6 |
| Entreprises | `/fr/entreprises/` | `/en/business/` | 7 / 8 |
| Collectivités | `/fr/collectivites/` | `/en/institutions/` | 9 / 10 |

Les identifiants ci-dessus sont un inventaire, à vérifier avant intervention
sur un autre environnement. Les demandes de rétractation ne font pas partie
de cette modification.

## Intégration au thème enfant

- `inc/enquiries.php` : repère les six pages par leurs slugs, charge la feuille
  de style et ajoute les attributs d’autoremplissage pour nom, prénom, email,
  organisation et pays. Aucun champ ni envoi n’est remplacé par un moteur maison.
- `assets/css/enquiries.css` : papier washi, titres Cormorant Garamond, interface
  DM Sans à 16 px, champs et boutons rectangulaires, focus visible, champs empilés
  sous 768 px. Marges de 20 px sur mobile, y compris avec les marges négatives
  appliquées par Kadence aux articles. Le sélecteur multiple Choices reprend
  le contraste, la hauteur et le focus des autres champs. Pas de nouvelle police
  ni de script tiers.
- `assets/js/enquiries.js` : reporte le libellé natif du champ sur le contrôle
  multiple créé par Choices.js et relie ce contrôle à sa liste d’options.
- `functions.php` : charge le module après le header et le footer.

Les hooks et classes ont été vérifiés dans le code officiel de Fluent Forms
6.2.13 : `FormBuilder.php`, `Components/Text.php`, `Container.php`,
`TermsAndConditions.php` et `SubmitButton.php`.
Référence : [hooks de formulaire Fluent Forms](https://developers.fluentforms.com/hooks/filters/form/).

## Aperçu privé

L’inventaire du clone a trouvé les définitions des six formulaires, mais pas
les fichiers de Fluent Forms ni les notifications, supprimées lors de la
préparation du clone. L’affichage actuel du shortcode ne prouve donc pas
que sa syntaxe enregistrée est incorrecte.

Fluent Forms 6.2.13 a été installé depuis l’archive officielle WordPress.org,
dont l’empreinte SHA-256 a été vérifiée avant extraction. Son état actif était
déjà présent dans la base. Les shortcodes enregistrés étaient corrects.

Le garde de revue déployé autorise le chargement de Fluent Forms
s’il figure déjà dans les extensions actives. Il charge le module de présentation,
masque les boutons d’envoi standard des formulaires 5 à 10 et affiche une note
bilingue expliquant l’absence d’envoi. Les interdictions POST, AJAX, REST, mail
et réseau demeurent en place. Ce garde est réservé au clone privé.

Pour reproduire le déploiement de la présentation :

1. Confirmer le compte et le clone, sauvegarder les fichiers remplacés hors
   de la racine web et relever leurs empreintes.
2. Installer Fluent Forms depuis WordPress.org, vérifier sa version et son
   empreinte, puis contrôler son état d’activation sans importer de secrets.
3. Déployer `inc/enquiries.php` et `assets/css/enquiries.css`, vérifier la syntaxe
   PHP avec le runtime du serveur, puis charger le garde en dernier.
   Le `functions.php` ancien du clone reste intact : le garde charge le module.
4. Vérifier les six pages et le maintien de tous les blocages de l’aperçu.

## Champs corrigés et contrôles de rendu

`tools/recovery/prepare_enquiry_fields.php` prépare deux corrections dans les
formulaires professionnels : pays en saisie libre pour Entreprises FR/EN,
quantité minimale de 1 pour les quatre formulaires Entreprises/Collectivités.
Les obligations de saisie et les autres champs sont conservés.

Le script exige le clone privé, une table InnoDB et les associations page/formulaire
attendues. Il fonctionne en simulation par défaut. L’application crée une sauvegarde
JSON hors de la racine web, verrouille les lignes dans une transaction et refuse
une modification concurrente. La simulation puis l’application ont réussi ; une
nouvelle simulation ne trouve plus de changement à faire.

Le rendu WordPress des six formulaires a été analysé avec DOMDocument : un
formulaire stylé par page, aucun bouton d’envoi dans l’aperçu, lien de confidentialité
dans la bonne langue, champs pays texte et minima attendus. Aucun avertissement
ou erreur PHP lors de ce contrôle. Les libellés de projet EN étaient déjà corrects
lors du contrôle complet ; aucune traduction de ces libellés n’a été nécessaire.

## Recette navigateur des 14 et 15 septembre

- Six formulaires présents, chacun dans la langue de la page, avec le lien de
  confidentialité correspondant et sans bouton d’envoi dans l’aperçu.
- À 360 px réels : aucun champ visible hors du contenu, formulaire à 20 px du
  bord, saisies à 16 px. Contrôles desktop sur Contact, Business et Collectivités.
- Navigation Tab entre prénom et nom vérifiée sur Contact FR/EN ; focus visible.
- Institutions EN : liste multiple ouverte, navigation avec flèche puis Entrée
  sélectionnant « School », sans soumission. Sélection de test abandonnée en
  quittant la page. Contraste et rendu de la liste vérifiés après correction.
- Le pays en saisie libre n’affiche plus l’ancienne invite de sélection.
- Calendriers Entreprises FR/EN : mois, jours, année, aide accessible et ordre
  lundi-dimanche contrôlés dans les deux langues. Navigation par flèches et
  sélection par Entrée réussies sans soumission.
- Les dimensions temporaires du navigateur ont été réinitialisées.

Ces vérifications ne constituent pas un audit complet d’accessibilité ni un
test de soumission. Les indications clavier et la locale du calendrier français
ont ensuite été corrigées ; voir le contrôle serveur ci-dessous.

## Validations et antispam du 14 septembre

Le module `inc/enquiries.php` active le honeypot natif uniquement pour les
formulaires associés aux six pages. Il résout les associations enregistrées
plutôt que de dépendre de `is_page()` pendant une requête AJAX. Les réglages des
autres formulaires ne changent pas. Aucun service antispam externe n’est ajouté.

La date souhaitée reste facultative. Si elle est renseignée, le serveur exige
une date réelle dans le format enregistré par Fluent Forms. Les dates impossibles,
valeurs non textuelles et caractères nuls sont rejetés. Les règles de calendrier
ne limitent pas arbitrairement la demande à une date future.

`tools/recovery/check_enquiry_validation.php` a été exécuté dans le clone avec
le code déployé : **124 contrôles, zéro échec**. Il utilise les validateurs de
champs et le honeypot natifs de Fluent Forms, sans insertion de soumission :

- valeurs valides et date facultative vide ; chaque champ obligatoire absent ;
- email invalide, quantité nulle, négative ou non numérique, choix inconnu ;
- jour bissextile valide, date impossible, texte invalide, tableau et caractère nul ;
- honeypot présent/vide accepté ; absent, rempli ou rempli avec un indicateur
  conversationnel falsifié rejeté.

Le script intercepte la terminaison JSON du honeypot uniquement dans son processus
CLI. Il ne teste ni le nonce ni une soumission HTTP de bout en bout. Il ne change
aucune protection du site web et n’envoie aucun email.

Le rendu serveur Entreprises/Business confirme : instructions de calendrier FR/EN,
année « Année »/« Year », début de semaine lundi en français, format accessible
français et présence du honeypot. Le calendrier interactif a ensuite été contrôlé
dans Chrome en français et en anglais, au clavier, sans soumettre de formulaire.

## Emails préparés, non activés

`tools/recovery/enquiry_notification_drafts.php` retourne des données sans écrire
en base ni envoyer de message. `import_enquiry_notification_drafts.php` contrôle
les associations page/formulaire, la table InnoDB et l’absence de notifications
existantes, sauvegarde les réglages hors de la racine web, puis utilise le service
natif de Fluent Forms. Il fonctionne en simulation par défaut et refuse d’écraser
une configuration différente. Les six parcours disposent de deux modèles natifs
Fluent Forms chacun : notification à l’équipe et accusé de réception dans la
langue du formulaire. Les **12 modèles sont désactivés** par défaut. Le fichier
propose aussi les six textes de confirmation à l’écran.

Expéditeur : `Koinobori House <contact@koinoborihouse.com>`. La notification équipe
contient les champs de la demande et utilise l’email du demandeur en Reply-To.
L’accusé de réception reste sobre, sans reproduire un message libre fourni par
le visiteur. Il annonce une réponse sous 1 à 2 jours ouvrés, délai déjà affiché
sur Contact. Aucun destinataire en copie, aucune pièce jointe, aucune newsletter.

La structure suit `NotificationTools.php`, `EmailNotification.php` et les
shortcodes natifs de Fluent Forms 6.2.13. La simulation, l’import transactionnel
et le second passage sans réécriture ont réussi dans le clone : 12 notifications
désactivées et 6 confirmations. Cet import n’a déclenché aucun envoi.

### Protocole du premier test de réception

1. Préparer un processus de recette distinct du runtime web de consultation,
   à partir des données assainies. Maintenir l’authentification HTTP, le blocage
   des achats, des tâches planifiées et des intégrations tierces du clone.
2. Installer FluentSMTP depuis WordPress.org. Choisir la connexion SMTP Brevo et
   stocker le login et la clé directement dans la configuration privée, hors du
   dépôt et de la base WordPress. Vérifier l’expéditeur côté Brevo.
3. Préparer l’exception réseau limitée au relais Brevo et le filtrage final des
   destinataires vers la seule adresse de test autorisée, conservée hors dépôt.
   Faire approuver cette configuration concrète avant d’assouplir l’isolation.
4. Premier lot : un message de transport, puis deux accusés FR/EN portant un
   préfixe de recette. Aucun destinataire réel provenant d’une demande client.
5. Contrôler l’acceptation par Brevo et la réception réelle, puis le dossier spam
   et les résultats d’authentification dans les en-têtes. Un retour PHP positif
   ne suffit pas pour conclure à la délivrabilité.
6. Préparer ensuite la recette HTTP complète avec nonce, validations, enregistrement,
   messages de succès et notifications. Les brouillons ne seront activés que
   dans le périmètre de test prévu avant leur mise en service définitive.

Références : [SMTP Brevo](https://help.brevo.com/hc/en-us/articles/7924908994450-Send-transactional-emails-using-Brevo-SMTP),
[connexion SMTP FluentSMTP](https://fluentsmtp.com/docs/set-up-fluent-smtp-with-any-host-or-mailer/),
[commandes de test FluentSMTP](https://docs.fluentsmtp.com/wp-cli-commands).

État mesuré avant intervention : aucune définition de notification pour les six
formulaires. L’archive officielle FluentSMTP 2.4.0 a été téléchargée
hors de la racine web dans le dossier privé de recette, puis contrôlée avant toute
installation : SHA-256
`ded5a19a40bfbff92e5caf2fe41d236a02892b7cd2252a436e766ed7450d609a`,
archive ZIP valide. Le clone utilise WordPress 7.1 et PHP 8.1, versions compatibles
avec les prérequis publiés pour FluentSMTP 2.4.0. L’extension est installée et
active. Le clone possède une connexion SMTP Brevo sur le port 587 avec TLS et
un expéditeur déjà vérifié dans Brevo. Le login et la clé dédiée restent dans un
fichier privé de mode `0600`, hors racine web ; les champs correspondants sont
vides dans la base WordPress.

Un runtime CLI séparé conserve le blocage HTTP externe et n’autorise les sockets
SMTP que pour le test. Son garde force l’adresse de test définie dans la
configuration privée, supprime les copies et pièces jointes, et n’accepte qu’un
sujet et un corps fixes. Le contrôle
d’isolation et l’authentification SMTP sans commande `MAIL FROM`, `RCPT TO` ou
`DATA` ont réussi. Le 15 septembre 2026 à 21:54, un unique message de transport
portant le sujet `[KH2027 SMTP TEST] Koinobori House` a été accepté, puis marqué
**Delivered** dans les journaux transactionnels Brevo. La réception a ensuite été
contrôlée le 16 septembre ; voir le diagnostic ci-dessous. La première clé créée pour le clone, inutilisable après une
saisie erronée, a ensuite été désactivée ; seule la clé corrigée reste active.

### Réception et authentification du domaine, 16 septembre

Le message de transport a été retrouvé dans le dossier Spam de la boîte Gmail
accessible. Ses détails confirment le destinataire de test autorisé. L’affichage
de cette boîte sous une autre adresse ne signifiait donc pas que le message était
inaccessible. Le contenu reçu correspond au test technique, sans donnée client.

Les en-têtes indiquent SPF, DKIM et DMARC **PASS**, mais la signature DKIM porte
sur le domaine technique Brevo et l’adresse d’expéditeur a été réécrite en
`brevosend.com`. Le Reply-To conserve l’adresse provisoire. Ces résultats valident
le transport, pas encore l’identité de marque ni le placement en boîte de réception.
L’authentification du domaine de marque doit être contrôlée sur un nouvel envoi ;
elle ne garantit pas à elle seule l’absence de classement en spam.

Le compte Brevo actuellement connecté ne contenait que le domaine non authentifié
de l’expéditeur provisoire. `koinoborihouse.com` y a été ajouté. La documentation
historique KH-005 décrivait une autre configuration déjà validée en mai ; ses
enregistrements DNS étaient toujours présents. Le compte principal o2switch gère
la zone de marque, alors que le compte du clone ne gère que son domaine de test.

Les deux CNAME DKIM et l’unique TXT DMARC correspondent déjà aux valeurs demandées
par Brevo. Un TXT supplémentaire de preuve de domaine a été enregistré pour le
compte Brevo courant, en conservant l’ancien TXT. Aucun A, MX, SPF, DKIM ou DMARC
existant n’a été modifié. Les valeurs de preuve restent dans les consoles concernées.

Le contrôle Brevo confirme la correspondance des **quatre enregistrements**. Après
propagation du nouveau TXT sur les deux serveurs o2switch, Brevo a confirmé
« Your domain has been authenticated » pour `koinoborihouse.com`.

La boîte `contact@koinoborihouse.com` existe chez o2switch, sans restriction et
avec de l’espace disponible. L’expéditeur `Koinobori House
<contact@koinoborihouse.com>` a été ajouté au compte Brevo courant et validé par
le code à usage unique reçu dans cette boîte.

La connexion FluentSMTP du clone privé utilise maintenant cet expéditeur. Avant
la modification, l’option sérialisée a été copiée dans
`kh_backup_fluentmail_settings_20260916_brand_sender`, sans chargement automatique,
et son empreinte MD5 a été comparée à l’original. La mise à jour conditionnelle
n’a touché qu’une ligne. Le contrôle après application confirme une structure de
986 octets, deux occurrences de l’adresse définitive, trois occurrences de sa clé
de connexion et aucune occurrence des anciennes valeurs. L’hôte Brevo, les champs
d’identifiants vides en base et `key_store=wp_config` sont inchangés. La sauvegarde
conserve ses 978 octets et son empreinte d’origine. Aucun nouvel email de test n’a
été envoyé pendant cette bascule.

## Travail restant avant recette fonctionnelle

- Vérifier le parcours HTTP complet : nonce, insertion, erreurs et confirmation.
- Vérifier l’expéditeur définitif, ses en-têtes et son placement dans la boîte
  cible sur un nouvel envoi autorisé.
- Recetter les notifications administrateur et accusés FR/EN déjà importés mais
  désactivés, avec des demandes synthétiques et le même destinataire autorisé.

## Vérifications

Syntaxe PHP serveur, contrôle du diff, simulation du script, relecture
après application et rendu WordPress des six formulaires réussis. Contrôles visuels
et clavier détaillés ci-dessus ; 124 contrôles des validateurs de champs et du
honeypot réussis. Installation et configuration isolées de FluentSMTP 2.4.0,
authentification Brevo et délivraison du message de transport vérifiées.
Le sélecteur multiple Institutions annonce son libellé FR/EN et reste utilisable
avec flèches et Entrée. L’import des 12 notifications désactivées et des six
confirmations est vérifié et idempotent ; une sauvegarde privée le précède.
La réception du premier message est vérifiée, dans Spam avec une identité Brevo
technique. Le domaine de marque et son expéditeur sont maintenant authentifiés
dans Brevo, et la configuration du clone utilise l’expéditeur définitif sans
conserver les identifiants SMTP en base. La soumission HTTP, les notifications
FR/EN et un envoi avec l’identité de marque restent à effectuer ; les protections
de consultation seule restent actives.

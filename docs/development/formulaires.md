# Formulaires Contact, Entreprises et Collectivités

## État au 14 septembre 2026

Intégration déployée dans le clone privé et rendu des six formulaires contrôlé
dans WordPress via WP-CLI et dans Chrome après rétablissement de l’accès privé.
Les six pages ont été vérifiées à 360 px ; les trois familles de formulaires ont
aussi été examinées sur ordinateur. Les parcours Entreprises et Collectivités
restent distincts. Aucun test d’envoi ni déploiement en production n’a été réalisé.

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

## Recette navigateur du 14 septembre

- Six formulaires présents, chacun dans la langue de la page, avec le lien de
  confidentialité correspondant et sans bouton d’envoi dans l’aperçu.
- À 360 px réels : aucun champ visible hors du contenu, formulaire à 20 px du
  bord, saisies à 16 px. Contrôles desktop sur Contact, Business et Collectivités.
- Navigation Tab entre prénom et nom vérifiée sur Contact FR/EN ; focus visible.
- Institutions EN : liste multiple ouverte, navigation avec flèche puis Entrée
  sélectionnant « School », sans soumission. Sélection de test abandonnée en
  quittant la page. Contraste et rendu de la liste vérifiés après correction.
- Le pays en saisie libre n’affiche plus l’ancienne invite de sélection.
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
français et présence du honeypot. La vérification du calendrier interactif dans
Chrome reste à faire après renouvellement de l’authentification privée.

## Emails préparés, non activés

`tools/recovery/enquiry_notification_drafts.php` retourne des données sans écrire
en base ni envoyer de message. Les six parcours disposent de deux modèles natifs
Fluent Forms chacun : notification à l’équipe et accusé de réception dans la
langue du formulaire. Les **12 modèles sont désactivés** par défaut. Le fichier
propose aussi les six textes de confirmation à l’écran.

Expéditeur : `Koinobori House <contact@koinoborihouse.com>`. La notification équipe
contient les champs de la demande et utilise l’email du demandeur en Reply-To.
L’accusé de réception reste sobre, sans reproduire un message libre fourni par
le visiteur. Il annonce une réponse sous 1 à 2 jours ouvrés, délai déjà affiché
sur Contact. Aucun destinataire en copie, aucune pièce jointe, aucune newsletter.

La structure suit `NotificationTools.php`, `EmailNotification.php` et les
shortcodes natifs de Fluent Forms 6.2.13. Ces modèles sont préparés localement,
pas importés en base et pas encore rendus par le moteur de notification distant.

### Protocole du premier test de réception

1. Préparer un processus de recette distinct du runtime web de consultation,
   à partir des données assainies. Maintenir l’authentification HTTP, le blocage
   des achats, des tâches planifiées et des intégrations tierces du clone.
2. Installer FluentSMTP depuis WordPress.org. Choisir la connexion SMTP pour
   réutiliser la clé SMTP Brevo existante, stockée hors dépôt. Le login SMTP et
   la clé doivent être renseignés directement dans la configuration privée ;
   ne pas les demander dans la conversation. Vérifier l’expéditeur côté Brevo.
3. Préparer l’exception réseau limitée au relais Brevo et le filtrage final des
   destinataires vers la seule adresse de test autorisée, conservée hors dépôt.
   Faire approuver cette configuration concrète avant d’assouplir l’isolation.
4. Premier lot : un message de transport, puis deux accusés FR/EN portant un
   préfixe de recette. Aucun destinataire réel provenant d’une demande client.
5. Contrôler l’acceptation par Brevo et la réception réelle, puis le dossier spam
   et les résultats d’authentification dans les en-têtes. Un retour PHP positif
   ne suffit pas : le garde actuel intercepte `wp_mail()` et renvoie volontairement
   `true` sans délivrer le message.
6. Préparer ensuite la recette HTTP complète avec nonce, validations, enregistrement,
   messages de succès et notifications. Les brouillons ne seront activés que
   dans le périmètre de test prévu avant leur mise en service définitive.

Références : [SMTP Brevo](https://help.brevo.com/hc/en-us/articles/7924908994450-Send-transactional-emails-using-Brevo-SMTP),
[connexion SMTP FluentSMTP](https://fluentsmtp.com/docs/set-up-fluent-smtp-with-any-host-or-mailer/),
[commandes de test FluentSMTP](https://docs.fluentsmtp.com/wp-cli-commands).

État mesuré du clone : FluentSMTP absent, aucune définition de notification
pour les six formulaires. L’envoi réel reste donc à configurer et à vérifier.

## Travail restant avant recette fonctionnelle

- Compléter les contrôles du calendrier, de ses textes accessibles FR/EN et
  des interactions clavier du formulaire complet.
- Vérifier le parcours HTTP complet : nonce, insertion, erreurs et confirmation.
- Importer et recetter les notifications administrateur et accusés FR/EN préparés,
  puis raccorder FluentSMTP/Brevo. Aucun envoi réel n’est validé à ce stade.
- Préparer un environnement de recette d’envoi distinct de la consultation
  seule, avec un destinataire de test autorisé, avant de modifier les protections.

## Vérifications

Syntaxe PHP locale et serveur, contrôle du diff, simulation du script, relecture
après application et rendu WordPress des six formulaires réussis. Contrôles visuels
et clavier détaillés ci-dessus ; 124 contrôles des validateurs de champs et du
honeypot réussis. Soumission HTTP et réception email restent à effectuer ; les
protections de consultation seule restent actives.

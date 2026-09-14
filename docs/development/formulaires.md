# Formulaires Contact, Entreprises et Collectivités

## État au 14 septembre 2026

Intégration déployée dans le clone privé et rendu des six formulaires contrôlé
dans WordPress via WP-CLI. La recette visuelle dans le navigateur reste à faire :
l’accès HTTP privé doit être rétabli. Les parcours Entreprises et Collectivités
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
  sous 768 px. Pas de nouvelle police ni de script tiers.
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

## Travail restant avant recette fonctionnelle

- Terminer la recette visuelle FR/EN, clavier et mobile, après reconnexion HTTP
  au site privé. Les chemins réécrits peuvent répondre 403 avant l’invite de
  connexion ; l’entrée directe `/index.php` déclenche l’authentification.
- Vérifier les champs obligatoires et dates facultatives dans le parcours complet.
- Vérifier l’antispam, les validations serveur et les messages de succès/erreur.
- Préparer les notifications administrateur et les accusés de réception FR/EN
  avec FluentSMTP/Brevo. Aucun envoi réel n’est validé à ce stade.
- Préparer un environnement de recette d’envoi distinct de la consultation
  seule, avec un destinataire de test autorisé, avant de modifier les protections.

## Vérifications

Syntaxe PHP locale et serveur, contrôle du diff, simulation du script, relecture
après application et rendu WordPress des six formulaires réussis. Recette visuelle
FR/EN, clavier, largeur 360 px et soumission/réception email restent à effectuer.
Le contrôle de rendu ne constitue pas un test de soumission.

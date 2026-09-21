# Pied de page Koinobori House

Le thème enfant remplace le rendu `Kadence\footer_markup` sur `kadence_footer`
(priorité 10). Le fichier `footer.php` du thème parent continue de fermer les
conteneurs et d'appeler `wp_footer()` : aucun fichier parent n'est modifié.

## Composition

- La maison : Boutique, L’Atelier, Lifestyle & Koi.
- Informations : Livraison, Retours, Contact.
- Professionnels : Entreprises et Collectivités, deux destinations distinctes.
- Ligne d'horizon : copyright, Mentions légales, CGV, Confidentialité, Cookies,
  sélecteur FR/EN et retour en haut.

Les liens sont résolus à partir des pages françaises existantes et de leurs
traductions Polylang publiées. Aucune destination n'est construite à partir
d'un domaine fixe et aucune page n'est créée par ce composant. Une traduction
absente ou non publiée est omise. Le sélecteur de langue garde, lorsqu'elle
existe, la traduction de la page ou de la fiche produit consultée.

Le lien Cookies ouvre la politique existante. Il ne simule pas un gestionnaire
de consentement : la configuration et la recette Complianz restent un chantier
distinct avant lancement. Aucun formulaire ni réseau social factice n'est ajouté.

Le fond washi, les titres Cormorant Garamond, les liens DM Sans et les accents
vermillon reprennent les jetons existants. Les classes sont propres au composant,
pour fonctionner aussi sur les pages qui n'utilisent pas le gabarit éditorial.
La ligne du bas mesure au minimum 88 px sur grand écran ; elle se réorganise
sur tablette. Sur mobile, les groupes s'empilent, les caractères mesurent 16 px
et les liens offrent au moins 44 px de hauteur. Le retour en haut est une ancre
native vers une cible focusable, sans JavaScript supplémentaire.

## Fichiers et activation

- `wp/themes/koinobori-child/inc/footer.php` : hooks, liens et langues.
- `wp/themes/koinobori-child/template-parts/footer-horizon.php` : rendu échappé.
- `wp/themes/koinobori-child/assets/css/footer.css` : disposition responsive.
- `functions.php` charge le module avec les autres composants du thème enfant.

Le réglage de thème `kh_horizon_footer` vaut `true` par défaut. Le passer à
`false` désactive les hooks et styles du composant, et conserve le footer Kadence.
Le garde du site privé charge explicitement le module pour les anciens fichiers
`functions.php` restaurés ; il conserve toutes ses restrictions d'accès.

## Vérification du 14 septembre 2026

- Syntaxe PHP des fichiers modifiés : PASS, PHP local 8.5.10.
- Rendus locaux FR et EN : un footer, quinze liens (services, légales, langues,
  retour en haut), libellés de la bonne langue, aucun avertissement PHP.
- Structure accessible locale observée dans Chrome : trois groupes nommés,
  navigation légale, langue du site et retour en haut.
- Recette locale Chrome : FR à 1920 px (ligne de 88 px) et 360 px (texte de 16 px,
  aucun débordement horizontal), navigation au clavier, retour en haut avec
  focus sur la cible et défilement à zéro.
- À terminer : activation sur le WordPress privé, contrôle des traductions et
  destinations réelles, recette visuelle distante.
  Les liens du banc d'essai local sont des fixtures et ne valident pas les URLs WP.

## Sources techniques vérifiées

- WordPress : [remove_action](https://developer.wordpress.org/reference/functions/remove_action/).
- Polylang : [référence des fonctions](https://polylang.pro/documentation/support/developers/function-reference/).
- Kadence 1.5.2 : `footer.php` et `inc/template-hooks.php` de l'installation
  privée, consultés avant implémentation.

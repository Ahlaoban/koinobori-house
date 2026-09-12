# Premier lot éditorial de reprise

## Livraison et activation

Le thème enfant propose un modèle de page facultatif **KH — Accueil éditorial**
(`page-templates/kh-home.php`). Il n'est attribué à aucune page existante. Les
contenus en base, les identifiants et les menus ne sont pas réécrits.

Le modèle comporte le hero, les cinq mondes, une sélection WooCommerce, Kaïro,
L'Atelier, Lifestyle si des articles sont publiés, Arts de vivre, le manifeste et
les deux parcours professionnels distincts. Une newsletter n'est affichée que
si une intégration réelle fournit son formulaire via le filtre
`koinobori_child_newsletter_markup`. Ce lot ne constitue donc pas encore une
recette complète des onze mouvements.

Les textes FR/EN viennent du dossier Lot 3, avec des adaptations de composition.
Les destinations sont résolues par WordPress/Polylang ; une traduction de page
absente ne produit pas de faux lien. Les produits et prix du modèle WordPress
sont rendus par WooCommerce. Les images illustratives de la charte ne sont pas
injectées dans le catalogue. Les articles restent masqués si aucun n'est publié.
Le header et le footer du thème existant restent appelés : leur portage Shoji et
ligne d'horizon doit encore être réalisé et recetté.

La présentation produit est une feuille CSS, sans surcharge des templates WC.
Son activation nécessite explicitement le theme_mod `kh_editorial_products` ;
il reste désactivé par défaut. La galerie conserve le sujet entier, les coins
sont droits, les contrôles gardent leur fonctionnement natif. Les variations,
stocks, taxes et paiements ne sont pas modifiés par ce code.

## Aperçu local

`tools/preview/editorial.php` exécute le véritable gabarit d'accueil avec des
données de démonstration et un header/footer de contrôle simplifiés. La fiche
produit locale utilise un balisage représentatif, pas WooCommerce. Son tarif de
29 EUR est celui du document pilote Sakura Rouge, explicitement affiché comme
référence ; ce n'est pas une lecture du prix serveur. Aucun achat/envoi n'existe.
Les liens des mondes et cartes mènent à la même fiche de contrôle ; les liens
éditoriaux aboutissent au pied de l'aperçu. Ces destinations de démonstration ne
sont pas utilisées dans le gabarit WordPress.

Le routeur local sert uniquement les quatre pages prévues, les CSS/polices/images
du thème, le logo et les JPG du catalogue. Il ne sert pas les autres fichiers du
dépôt. Lancer depuis la racine du worktree :

```powershell
php -n -S 127.0.0.1:8765 tools/preview/editorial.php
```

Routes : `/fr/`, `/en/`, `/product/fr/`, `/product/en/`.

PHP portable 8.5.10 utilisé localement, téléchargé depuis php.net et contrôlé avec
le SHA-256 officiel `22ec430195984d233eb9e62c637a945bbcda06efca2f392d9d96d62c6acd34f8`.
Le binaire reste dans `build/php-runtime`, exclu du dépôt. Il ne change pas le PHP
de l'hébergement. Source : https://www.php.net/downloads.php?os=windows

## Vérifications effectuées

- Syntaxe PHP : fonctions du thème, helper éditorial, gabarit et routeur local.
- Chrome : les quatre vues à 360, 768 et 1280 px, soit 12 configurations.
  Un seul H1 par page, langue correcte, aucun débordement horizontal, aucune
  erreur PHP visible ni image chargée en échec ; cinq mondes dans les accueils.
- Clic du CTA d'accueil : ancre `#kh-worlds`, section arrivée en haut du viewport.
- Bascule FR/EN et navigation accueil/produit dans l'aperçu.
- Inspection visuelle desktop/mobile du hero et de la fiche. Les anciens crops
  incomplets de la maquette ont été remplacés dans l'aperçu par les JPG complets
  du catalogue. Le fond washi a été atténué, y compris le mode de fusion hérité.

À terminer : rendu et liens sur le vrai WordPress, sélection des pièces à mettre
en avant, photos manquantes, header/footer, newsletter, accessibilité complète,
galerie/variations natives et parcours transactionnel. L'aperçu local ne valide
ni une commande ni une conformité complète. Aucun de ces nouveaux fichiers n'est
déployé en staging ou production à ce stade.

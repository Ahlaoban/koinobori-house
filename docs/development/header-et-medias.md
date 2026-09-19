# Header et médias produit — 13 septembre 2026

## Décisions d’Alain

Le header comporte cinq rubriques : Boutique, Lifestyle, L’Atelier, Professionnels,
Contact. « Accueil » et « Arts de vivre » quittent la navigation du header ; leurs
pages ne sont pas supprimées. Le logo KoinoboriHouse ramène à l’accueil ; ses deux
mots s’écartent pour révéler les icônes. BY BCDG disparaît à l’ouverture et revient
à la fermeture. Un seul libellé apparaît sous l’icône survolée ou au focus clavier,
sans attribut title et sans label permanent de la page active.

Amendement du 13 septembre : l’introduction automatique ne joue qu’à la première
visite de la session de l’onglet, mémorisée dans sessionStorage et partagée entre
FR/EN. Navigation interne, rechargement et retour arrière ne la relancent pas.
Si le stockage est bloqué, l’introduction est omise. Survol et focus clavier
restent disponibles. Compte et Panier sont espacés de 24 px (18 px en mode compact).
Choix d’Alain : série A, avec Contact de B et Professionnels de C.

Washi #F8F4EE, sumi #1A1410, vermillon #C8311A ; Cormorant Garamond 400 et DM Sans
hébergées localement. Les sept tracés Lucide 1.17.0 sélectionnés sont intégrés en
SVG inline, sans charger la bibliothèque dans le site :

| Rubrique / action | Icône choisie |
|---|---|
| Boutique | `store` — devanture |
| Lifestyle | `wind` — souffle de vent |
| L’Atelier | `brush` — pinceau |
| Professionnels | `handshake` — poignée de main |
| Contact | `send` — avion en papier |
| Compte | `user-round` — silhouette |
| Panier | `shopping-bag` — sac |

Licences conservées dans `licenses/lucide.txt`. Les actions restent distinctes du menu.

## Fichiers

```text
wp/themes/koinobori-child/
  functions.php                      charge inc/header.php
  inc/header.php                     hooks Kadence, menus et fragments panier
  inc/class-icon-walker.php           SVG et liens accessibles
  template-parts/header-shoji.php     structure, logo, menu et dialogue mobile
  assets/css/header.css
  assets/js/header.js
wp/plugins/kh-product-media/
  kh-product-media.php               champ produit, validation, onglet vidéo
  assets/admin.js                    sélection dans la médiathèque WordPress
  assets/video.css
tools/preview/
  editorial.php                      aperçu visuel avec données de démonstration
  test-product-media.php             tests isolés, sans base de données
```

## Installation sur une instance WordPress de recette

1. Sauvegarder le thème enfant existant et la base avant installation. Copier les
   nouveaux fichiers dans le thème enfant Koinobori déjà actif. Ne pas écraser le
   thème parent. `functions.php` ajoute uniquement le chargement de `inc/header.php`.
2. Le hook `kadence_header` remplace `Kadence\header_markup` ; le document, les hooks
   d’accessibilité et les conteneurs Kadence sont conservés. Pas de header parent
   masqué en CSS, pas de copie de son `header.php`.
3. Apparence > Menus : créer/affecter un menu FR et sa traduction EN à l’emplacement
   « KH — Navigation à cinq icônes ». Dans Options de l’écran, activer Classes CSS.
   Ajouter un exemplaire de chacune des cinq pages, dans l’ordre voulu :

   | Page FR / EN | Classe CSS |
   |---|---|
   | Boutique / Shop | `icon-shop` |
   | Lifestyle / Lifestyle | `icon-lifestyle` |
   | L’Atelier / The House | `icon-house` |
   | Professionnels / For Professionals | `icon-professionals` |
   | Contact / Contact | `icon-contact` |

   Aucun élément sans une de ces classes n’est affiché. Les sous-menus ne sont pas
   utilisés. Sans menu affecté, les liens sont résolus depuis les pages publiées
   existantes et Polylang. Ne pas inventer des URL anglaises.
4. Copier `kh-product-media` dans `wp-content/plugins/`, puis activer
   « KH — Vidéo produit ». Aucun changement de galerie, de prix, de stock ni de
   commande n’est effectué. Pas de migration obligatoire des produits existants.
5. Pour revenir au header Kadence, régler `kh_split_header` à false avec
   `wp theme mod set kh_split_header 0`. Le retrait du module vidéo laisse les
   pièces jointes et les métadonnées intactes.

Le site privé de revue reste en lecture seule : son garde charge les modules pour
la recette sans ouvrir wp-admin ni activer de transactions. Il ne constitue pas
une validation du panier AJAX ou du formulaire d’administration.

## Ajouter vos photos, vidéos et nouvelles fiches

Dans Produits > Modifier (éditeur classique WooCommerce) :

1. **Image produit** : la photo principale. **Galerie produit** : ajouter les autres
   images, en sélectionner plusieurs et réordonner les vignettes. Le thème n’ajoute
   aucun nombre maximal de photos ; les limites de taille dépendent de WordPress
   et de l’hébergement. Décrire les images avec un texte alternatif utile.
2. **Vidéo du koi / Koi video** : Choisir ouvre la médiathèque pour sélectionner ou
   téléverser une vidéo MP4 ou WebM. Une vidéo facultative par fiche, remplaçable.
   Le choix est validé côté serveur : une URL ou du HTML libre n’est pas accepté.
3. Choisir éventuellement une **image d’aperçu**. Sinon, la photo principale du
   produit sert d’affiche. Retirer détache le média sans supprimer son fichier.
4. Enregistrer le produit. L’onglet « Le koi en mouvement » apparaît uniquement si
   une vidéo valide est associée. Commandes de lecture natives, pas d’autoplay,
   pas de boucle forcée, `preload="none"`. Aucune vidéo chargée sur les cartes de
   boutique ou d’accueil. Une vidéo retirée de la médiathèque masque l’onglet.
5. Pour une nouvelle fiche, utiliser le flux normal WooCommerce, sa catégorie et
   sa traduction Polylang. Les mêmes champs sont disponibles. Les cartes de la
   sélection d’accueil sont interrogées dynamiquement ; la boutique conserve sa
   pagination WooCommerce. Publier davantage de produits ne nécessite pas de code.

Les relations vidéo/affiche sont ajoutées à la liste de copie des métadonnées de
Polylang pour les traductions. Une fiche EN déjà existante peut recevoir le même
média manuellement. Les limites d’envoi de l’hébergement ne sont pas augmentées
automatiquement. Préparer une version web compressée du film plutôt que le master.
Le module ne transcode pas les vidéos ; un fichier MP4 doit utiliser des codecs
compatibles avec les navigateurs ciblés. L’interface du nouvel éditeur produit
WooCommerce par blocs n’est pas couverte par cette première intégration.

## Recette

- Desktop : logo joint centré, ouverture du header, fermeture, BY BCDG en fondu,
  cinq icônes, une seule infobulle, lien du logo vers l’accueil.
- Clavier : ouverture au focus, labels, ordre des liens, contour de focus.
- Scroll : hauteur 100 → 68 px au-delà de 20 px, flou et fond translucent.
- Largeur moyenne/tablette/tactile : menu permanent, pas de collision des mots.
- Mobile : logo vers accueil et bouton Menu explicite, dialogue plein écran,
  focus contenu dans le dialogue, Échap et fermeture, restauration du défilement.
- Animations réduites : pas d’introduction ni de transition. JavaScript absent :
  liens visibles et fonctionnels dans un header statique.
- WordPress connecté : header décalé selon la position réelle de la barre admin.
- WooCommerce : ajout/suppression panier AJAX actualisent les deux compteurs.
- Médias : sans vidéo, MP4, WebM, affiche personnalisée ou photo principale,
  remplacement, retrait, faux ID, droits insuffisants, sauvegarde sans champs.

Tests automatisés disponibles : syntaxe PHP/JS et
`php -n tools/preview/test-product-media.php` (20 contrôles de contrat isolés).
La recette navigateur et les vérifications sur le vrai WordPress doivent être
distinguées de cet aperçu local à données de démonstration.

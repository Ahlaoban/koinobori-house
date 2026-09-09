# Koinobori House — Référence HTML/CSS de la charte v3.0

Cette archive constitue la **source de vérité visuelle et structurelle** demandée pour l’intégration WordPress/Kadence. La page de référence est écrite en HTML et CSS purs. Elle ne dépend ni d’un framework, ni d’un CDN, ni de JavaScript. Les polices Cormorant Garamond, Lora, DM Sans et Noto Serif JP sont déclarées par nom uniquement, afin d’utiliser les familles déjà auto-hébergées dans WordPress.

> **Principe directeur :** « Ma — L’Intervalle enchanté ». Le vide est structurel, le washi est continu, le sumi porte le texte, le vermillon reste rare et fonctionnel, et les compositions demeurent asymétriques.

## Contenu du dossier

| Élément | Fonction |
|---|---|
| `index.html` | Page d’accueil complète en onze mouvements, avec le contenu et l’ordre de référence. |
| `styles.css` | Design tokens, composants, états, breakpoints 1280/768/360 et commentaires d’intégration. |
| `assets/` | Uniquement les images déjà approuvées dans la charte ; aucune nouvelle image n’a été inventée. |
| `ASSETS_MANIFEST.md` | Dimensions et rôle précis des quatorze images incluses. |
| `INTEGRATION_KADENCE.md` | Correspondance précise entre les mouvements HTML et les blocs WordPress/Kadence. |
| `VALIDATION_RESPONSIVE.md` | Résultat du contrôle visuel aux trois largeurs de référence. |
| `DEMANDE_CLAUDE_SOURCE.txt` | Brief d’origine transmis par Claude, conservé pour traçabilité. |

## Ouverture locale

Il suffit d’ouvrir `index.html` dans un navigateur. Les chemins sont relatifs et toutes les images se trouvent dans `assets/`. La page doit donc fonctionner sans serveur et sans connexion réseau.

## Jetons de design verrouillés

| Catégorie | Valeur de référence |
|---|---|
| Washi | `#F8F4EE` |
| Sumi | `#1A1410` |
| Vermillon | `#C8311A` |
| Or | `#B8860B` |
| Indigo | `#2B3A6B` |
| Blanc | `#FFFDFC` |
| Titres | Cormorant Garamond, graisse 300–400 |
| Texte courant | Lora, graisse 400 |
| Interface | DM Sans, graisse 400 |
| Kanji | Noto Serif JP |
| Largeur utile | `1200px` avec marges latérales de `40px` à 1280px |
| Espacement sections | `160px` sur grand écran |
| Coins | `0` par défaut ; aucun arrondi générique |

Les variables sont toutes regroupées au début de `styles.css` et préfixées `--kh-`. Les classes de composants sont également préfixées `.kh-` afin d’éviter les collisions avec Kadence, WooCommerce ou les extensions WordPress.

## Répartition HTML, CSS et WordPress

| Couche | Responsabilité |
|---|---|
| HTML/Kadence | Ordre des onze mouvements, titres, textes, liens, cartes, formulaires et sémantique. |
| CSS | Palette, typographie, espacements, fond washi, grilles, Header Shoji, cartes, footer, états et responsive. |
| WordPress/WooCommerce | Contenus administrables, produits réels, panier, compte, catégories et publication du magazine. |
| JavaScript | Aucun n’est requis pour cette référence. Kadence ou WooCommerce peuvent conserver leurs scripts fonctionnels natifs. |

## Règle d’utilisation des images

Dans la maquette autonome, les images sont référencées par des chemins relatifs tels que `assets/hero-koinobori.png`. Dans WordPress, Claude doit téléverser les fichiers dans la médiathèque puis remplacer ces chemins par les URL WordPress correspondantes, sans changer les cadrages `object-fit`, les positions de fond ou les proportions définies dans le CSS.

Le fichier `hero-koinobori.png` est le fond principal du mouvement 02. Le logo, les textes, les boutons et le cartouche `間` restent des éléments HTML superposés ; ils ne doivent jamais être fusionnés dans l’image.

## Validation effectuée

| Largeur | Comportement vérifié |
|---|---|
| 1280px | Navigation complète, Hero asymétrique, onze mouvements et footer ligne d’horizon. |
| 768px | Menu compact, grilles adaptées, compositions éditoriales préservées. |
| 360px | Pile à une colonne, marge de 20px, aucun débordement horizontal, footer réduit. |

La classe active de navigation utilise une ligne sumi de `1px`. Les boutons sont rectangulaires et utilisent le vermillon uniquement pour l’action principale. Les cartes produit conservent la mention `- by BCDG` dans le titre.

## Référence d’autorité

En cas de divergence avec un composant WordPress existant, la hiérarchie visuelle, les variables et les proportions de `styles.css` prévalent. Les adaptations fonctionnelles de WooCommerce doivent être réalisées **à l’intérieur** de cette grammaire visuelle, sans introduire de coins arrondis, de gradients décoratifs, de typographies génériques ni de gros footer sombre.

**Auteur de la référence : Manus AI**

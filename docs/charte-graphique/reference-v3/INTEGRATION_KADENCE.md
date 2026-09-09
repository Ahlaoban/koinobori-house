# Correspondance WordPress/Kadence — Koinobori House

Ce document traduit la page de référence en unités directement reconstructibles dans Kadence. Les sélecteurs indiqués se trouvent déjà dans `styles.css` ; Claude peut donc conserver les classes et les appliquer aux blocs Kadence via le champ **Classe(s) CSS additionnelle(s)**.

| Mouvement | Sélecteur racine | Construction Kadence recommandée | Assets |
|---:|---|---|---|
| 01 | `.kh-header` | Header à trois zones : marque, navigation de sept entrées, compte ; menu compact via le header mobile Kadence. | Aucun asset obligatoire. |
| 02 | `.kh-hero` | Row Layout pleine largeur avec contenu aligné à gauche et image de fond en couverture. | `hero-koinobori.png` |
| 03 | `.kh-worlds` | Section avec en-tête scindé puis grille de cinq liens/catégories. | Aucun asset obligatoire. |
| 04 | `.kh-products` | Query Loop WooCommerce ou grille de produits ; chaque carte reçoit `.kh-product-card`. | Trois recadrages produits de référence et `trait-vermillon.png`. |
| 05 | `.kh-about` | Row Layout deux colonnes, image à gauche et texte à droite. | 🔴 **NE PAS publier `atelier-alain-catherine.png`** (O-4, cf. note sous le tableau). Utiliser `apropos-hero.png` du pack v1. |
| 06 | `.kh-lifestyle` | Bloc éditorial deux colonnes suivi d’une liste d’articles. | `lifestyle-koi.png` |
| 07 | `.kh-living` | Section indigo avec grille de trois familles et voile washi. | `arts-ceramique.png`, `arts-textile.png`, `arts-lampe.png` |
| 08 | `.kh-manifesto` | Intro éditoriale à gauche, quatre principes numérotés à droite. | `sceau-bcdg.png` |
| 09 | `.kh-professionals` | Intro puis deux offres : espaces/hospitalité et demandes particulières. | Aucun asset obligatoire. |
| 10 | `.kh-newsletter` | Formulaire compact sur une ligne ; empilement sur mobile. | Aucun asset obligatoire. |
| 11 | `.kh-footer` | Footer en deux étages, quatre colonnes légères puis baseline et liens légaux. | Aucun asset obligatoire. |

> 🔴 **Portrait Alain-Catherine, mouvement 05 — interdiction de publication.** CLAUDE.md, défauts posés le 2026-07-27 : « portrait Alain-Catherine non publié tant que Catherine n'a pas validé ». Le fichier n'est versionné nulle part, volontairement. La page L'Atelier ouvre **sans portrait**, avec `apropos-hero.png` du pack v1 (O-8). C'est le seul point de cette référence qui engage une personne réelle n'ayant rien validé. Garde-fou ajouté ici le 2026-09-09 : il n'existait que dans `NOTES-INTEGRATION-CLAUDE.md`, que l'intégrateur n'ouvre pas forcément.

## Paramétrage des blocs

Les Row Layouts Kadence doivent être placés en **largeur complète**, tandis que leur contenu interne reprend `.kh-container`. La largeur maximale est de `1200px`. À 1280px, les marges sont de `40px`; à 768px, elles passent à `28px`; à 360px, elles passent à `20px`.

Les espacements verticaux ne doivent pas être uniformisés dans l’interface Kadence. Le CSS fournit les valeurs sémantiques et les variations par mouvement. Le principe de Ma exige des pauses distinctes entre les sections, avec `160px` comme valeur de référence desktop, `96px` à la tablette et `72px` sur mobile.

## WooCommerce

Le HTML de la carte produit sert de gabarit visuel. Dans la boucle WooCommerce, il faut conserver la hiérarchie suivante : image, monde/catégorie, titre avec `- by BCDG`, puis prix et bouton. Le titre n’est jamais tronqué par CSS et la carte ne reçoit ni border-radius ni ombre diffuse générique.

## Header Shoji V2

La référence montre directement l’état ouvert et utilisable : les sept entrées sont visibles à partir de 1101px. Sous ce seuil, la navigation passe au contrôle compact. L’actif reçoit une ligne de `1px` et « Mon compte » reste vermillon. Les deux montants latéraux sont produits par les pseudo-éléments du header et ne nécessitent pas d’image supplémentaire.

## Footer Ligne d’horizon V2

Le footer ne doit pas être converti en bloc sombre massif. Le premier étage regroupe quatre colonnes très légères. Le second étage porte, à gauche, la ligne `© 2026 Koinobori House · Créations BCDG`, et au centre les **quatre** liens légaux : Mentions légales · CGV · Politique de confidentialité · Gestion des cookies. La ligne basse doit rester perceptible mais discrète, sans fond coloré plein.

> ⚠️ **Corrigé le 2026-09-09 après revue de code.** Cette ligne prescrivait `© 2026 Koinobori House · Koinobori-house · SIREN 838 329 271`, la baseline « Le Japon à votre horizon » et deux liens légaux seulement. Trois erreurs, dont une réglementaire :
> - Le SIREN **838 329 271 est faux**. Le vrai est **945 241 545, R.C.S. Quimper** ([KH-017](../../lot0/KH-017-documents-legaux/01-mentions-legales-FR.md)). Publier un numéro d'immatriculation erroné sur un site marchand français n'est pas une coquille de maquette.
> - L'entité s'écrit **Koinobori House**, jamais « Koinobori-house ».
> - L'arbitrage O-2 impose **quatre** liens légaux au centre, pas deux.
>
> La baseline « Le Japon à votre horizon » n'est arbitrée nulle part : elle est retirée ici, à rouvrir avec Alain si elle est voulue.

## Responsive

| Point de contrôle | Règle |
|---|---|
| `1280px` | Largeur desktop de référence, grille produits en trois colonnes et navigation complète. |
| `768px` | Menu compact, cinq mondes en deux colonnes, compositions éditoriales conservées. |
| `360px` | Toutes les grilles principales passent en une colonne ; marge latérale de `20px`. |

L’implémentation finale doit conserver les attributs sémantiques et les textes alternatifs présents dans `index.html`. Les interactions natives de Kadence et WooCommerce peuvent rester actives, mais elles ne doivent pas modifier les couleurs, les rayons, la typographie ou le rythme définis par la charte.


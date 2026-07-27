# Koinobori House — Pack d’images pour Claude

**Version :** 3.1 — WordPress / Kadence / WooCommerce — fond Hero ajouté  
**Auteur :** Manus AI  
**Objet :** fournir toutes les références visuelles et tous les assets utiles à la reproduction fidèle de la direction artistique « Ma — L’Intervalle enchanté ».

> **Règle principale :** le dossier `01_slides_finales` constitue la référence visuelle finale. Les fichiers séparés des autres dossiers servent à reconstruire ces compositions dans WordPress. Claude ne doit jamais réinterpréter la palette, le fond, les proportions typographiques ou les ornements en se fondant uniquement sur une description textuelle.

## 1. Organisation du pack

| Dossier | Contenu | Usage recommandé |
|---|---|---|
| `01_slides_finales` | Les 20 pages finales en PNG haute définition | Référence visuelle générale, comparaison avant/après et contrôle de conformité |
| `02_slides_sources` | Les neuf pages du PowerPoint source conservées dans la version finale | Référence stricte pour la charte d’origine, les compositions, la typographie et l’ambiance |
| `03_fonds_et_textures` | Le fond washi natif nettoyé | Arrière-plan global des nouvelles sections et matière de référence pour le site |
| `04_illustrations_editoriales` | Hero, Atelier, Lifestyle et Arts de vivre | Fonds Hero, références visuelles et illustrations des nouvelles rubriques éditoriales et commerciales |
| `05_ornements` | Pinceau vermillon, bandeau sumi, cartouche, sceau et trait vertical | Accents graphiques ponctuels ; ne jamais les multiplier sans raison |
| `99_archive_ne_pas_utiliser` | L’ancien slide 10 contenant la recommandation Shopify | Archive documentaire uniquement ; ne pas publier et ne pas intégrer au site |

## 2. Référence des vingt slides finales

| Fichier | Slide | Rôle pour Claude |
|---|---|---|
| `slide-01.png` | Couverture originale — Koinobori House / Ma | Référence absolue du logo, du koinobori vermillon, du papier et de l’atmosphère générale |
| `slide-02.png` | Le concept directeur | Référence de la respiration, des grands blancs et du panneau éditorial latéral |
| `slide-03.png` | Système de design | Palette, polices, composants, traits et micro-éléments |
| `slide-04.png` | Hero — L’envol silencieux | Modèle de hero immersif et de grande mise en scène |
| `slide-05.png` | Les cinq mondes | Navigation éditoriale entre les univers de la marque |
| `slide-06.png` | Créations BCDG | Référence des cartes produits et des CTA vermillon |
| `slide-07.png` | Le manifeste | Mise en page éditoriale numérotée et citation de clôture |
| `slide-08.png` | Architecture de la page d’accueil | Structure narrative et articulation B2C, B2G et B2B |
| `slide-09.png` | Header Fusuma | Référence de l’ouverture des panneaux, du logo centré et de la navigation révélée |
| `slide-10.png` | Roadmap WordPress | Remplacement définitif de l’ancienne page Shopify |
| `slide-11.png` | Une maison plus vaste | Introduction des nouvelles rubriques de la marque |
| `slide-12.png` | L’Atelier — Alain & Catherine | Page À propos, incarnation de la marque et grande illustration verticale |
| `slide-13.png` | Lifestyle & Koi | Archive éditoriale et organisation du magazine |
| `slide-14.png` | Modèle d’article | Structure d’un article Lifestyle en trois mouvements |
| `slide-15.png` | Arts de vivre | Extension future vers les objets japonais et asiatiques de décoration |
| `slide-16.png` | Nouvelle promenade d’accueil | Ordre des sections de la page d’accueil enrichie |
| `slide-17.png` | Footer — La ligne d’horizon | Modèle de footer minimaliste, léger et sans gros bloc sombre |
| `slide-18.png` | WordPress sans effet de thème | Contraintes d’implémentation Kadence et WooCommerce |
| `slide-19.png` | Contrat visuel pour Claude | Invariants graphiques et critères de refus |
| `slide-20.png` | HTML, CSS et JavaScript | Répartition des responsabilités entre structure, apparence et interactions |

## 3. Assets séparés à intégrer

| Fichier | Dimensions | Usage |
|---|---:|---|
| `03_fonds_et_textures/fond_washi_koinobori_house.png` | 2560 × 1440 | Fond principal des nouvelles sections ; utiliser en image de fond CSS, avec une couleur ivoire de secours |
| `04_illustrations_editoriales/hero_slide4_extraction_exacte_avec_logo.jpg` | 1552 × 1146 | Extraction fidèle du visuel fusionné dans la slide 4 ; référence graphique avec logo, cartouche, tagline et indication de navigation déjà incrustés |
| `04_illustrations_editoriales/hero_fond_propre_4x3.png` | 2176 × 1632 | Fond Hero nettoyé, sans aucun texte ni logo ; version la plus proche du cadrage source pour recomposition libre |
| `04_illustrations_editoriales/hero_fond_web_16x9.png` | 2560 × 1440 | Version recommandée pour le Hero du site : zone sûre à gauche pour le logo et la tagline en HTML, koinobori préservé sur la droite |
| `04_illustrations_editoriales/atelier_alain_catherine.png` | 1632 × 2176 | Visuel vertical de la page À propos / L’Atelier |
| `04_illustrations_editoriales/lifestyle_koi_hero.png` | 2176 × 1632 | Hero ou article vedette du magazine Lifestyle & Koi |
| `04_illustrations_editoriales/arts_de_vivre_ceramique.png` | 2176 × 1632 | Carte ou catégorie Céramique |
| `04_illustrations_editoriales/arts_de_vivre_textile.png` | 2176 × 1632 | Carte ou catégorie Textile |
| `04_illustrations_editoriales/arts_de_vivre_lampe.png` | 2176 × 1632 | Carte ou catégorie Luminaires |
| `05_ornements/trait_pinceau_vermillon.png` | 900 × 66 | Soulignement de titre, séparation courte ou accent ponctuel |
| `05_ornements/bandeau_encre_sumi.png` | 2560 × 280 | Bandeau sombre texturé pour une recommandation ou une conclusion exceptionnelle |
| `05_ornements/cartouche_koi_vertical.png` | 195 × 340 | Cartouche décoratif vertical, utilisé avec parcimonie |
| `05_ornements/sceau_bcdg.png` | 290 × 300 | Signature BCDG ou repère d’auteur lorsque pertinent |
| `05_ornements/trait_encre_vertical.png` | 85 × 1020 | Séparateur vertical entre colonnes ou étapes |

## 4. Traduction web de la charte

La charte est applicable en **HTML, CSS et JavaScript**, mais elle réside principalement dans le CSS. HTML doit fournir une structure sémantique propre et des classes stables ; CSS doit reproduire le fond washi, les polices, les espacements, les panneaux, les pinceaux, les états au survol et le responsive ; JavaScript ne doit intervenir que pour les interactions avancées, notamment l’ouverture au clic ou au toucher du Header Fusuma et certains filtres dynamiques.

| Couche | Ce que Claude doit y placer |
|---|---|
| **HTML** | `header`, `nav`, `main`, `section`, `article`, `footer`, blocs Kadence/Gutenberg et markup WooCommerce |
| **CSS** | Variables de couleurs, polices, fond washi, grilles asymétriques, panneaux, CTA vermillon, footer, responsive, focus et réduction des animations |
| **JavaScript** | Fusuma au clic/toucher, filtres dynamiques et comportements qui exigent réellement un état ; jamais pour remplacer une structure ou un style CSS incomplet |

## 5. Règles d’intégration des images

Les images doivent être importées dans la médiathèque WordPress, converties en formats web optimisés lorsque nécessaire et servies avec des tailles adaptées. Claude doit conserver les fichiers maîtres de ce pack intacts et créer des dérivés WebP ou AVIF pour la production. Les grandes illustrations doivent utiliser `object-fit: cover` uniquement si le sujet principal reste visible ; dans le cas contraire, il faut préférer une composition dédiée par breakpoint.

Le fond `fond_washi_koinobori_house.png` doit être utilisé comme texture, pas comme image éditoriale. Sa couleur de secours doit rester ivoire chaud. Pour le Hero, Claude doit utiliser en priorité `hero_fond_web_16x9.png` comme arrière-plan CSS, puis superposer le logo, le cartouche, la tagline bilingue et l’indication de navigation en HTML/CSS afin qu’ils restent nets, accessibles et responsives. Le fichier `hero_slide4_extraction_exacte_avec_logo.jpg` sert de référence visuelle ou de solution de secours, mais ne doit pas remplacer la version recomposée si le texte doit être adaptatif. Les ornements doivent être décoratifs, non essentiels à la compréhension, et porter un texte alternatif vide lorsqu’ils sont intégrés comme images HTML ; lorsque cela est possible, ils doivent être placés en arrière-plan CSS.

```css
.koinobori-hero {
  background-color: #f3ebdd;
  background-image: url("hero_fond_web_16x9.png");
  background-repeat: no-repeat;
  background-position: center center;
  background-size: cover;
}
```

Sur mobile, Claude doit prévoir un cadrage dédié plutôt qu’un simple recadrage agressif. Le koinobori ne doit jamais être coupé au niveau de la tête, de la queue ou des rubans.

## 6. Interdictions

| Élément interdit | Motif |
|---|---|
| `99_archive_ne_pas_utiliser/original_10_shopify_ne_pas_utiliser.jpg` | Contient l’ancienne orientation Shopify, abandonnée au profit de WordPress/WooCommerce |
| Anciens pinceaux contenant du texte ou un logo incrusté | Ils contaminent les nouvelles compositions et peuvent réintroduire Shopify |
| Fond beige uni | Il supprime la matière et la patine qui définissent la charte |
| Gros footer sombre et chargé | Il contredit le footer minimaliste « ligne d’horizon » |
| Esthétique manga, luxe noir dominant ou clichés touristiques | Elle éloigne la marque de l’élégance éditoriale et artisanale recherchée |

## 7. Fichiers complémentaires

Le fichier `manifest_fichiers.txt` fournit la liste exacte des fichiers du pack. Le fichier `inventaire_dimensions.txt` indique les dimensions, le format et le poids des images. Le cahier d’exécution WordPress joint au pack complète les références visuelles par les règles fonctionnelles et techniques.

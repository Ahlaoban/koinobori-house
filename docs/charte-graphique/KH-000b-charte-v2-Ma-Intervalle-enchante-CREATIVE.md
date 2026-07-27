# Koinobori House — Spécifications créatives et fonctionnelles pour Claude

**Version :** 2.0 — WordPress / WooCommerce  
**Auteur de la spécification :** Manus AI  
**Objet :** traduire fidèlement la direction artistique « Ma — L’Intervalle enchanté » dans le site `koinoborihouse.com`.

## 1. Intention générale

Koinobori House ne doit pas ressembler à une boutique WooCommerce habillée d’un décor japonais. Le site doit donner l’impression d’entrer dans une **maison éditoriale et une galerie d’art**, où le commerce est présent mais jamais envahissant. Le principe structurant est le **Ma (間)** : le vide, la respiration et l’intervalle donnent de la valeur aux images, aux mots et aux objets.

Le visiteur doit d’abord ressentir un univers, ensuite comprendre les collections, puis seulement rencontrer l’acte d’achat. La marque doit rester élégante, créative et immersive, sans tomber dans trois écueils : le Japon de carte postale, l’esthétique manga dominante ou le luxe sombre et ostentatoire.

## 2. État de départ

Au moment de l’audit, le domaine public affiche une installation WordPress utilisant Kadence, sans page ni article publié dans l’API publique. La page d’accueil montre essentiellement le logo, un formulaire de recherche et le footer générique du thème.[1] La présentation source contient dix slides aplatis en images 16:9 ; elle ne fournit donc pas de composants PowerPoint éditables à réutiliser directement.

> La présentation enrichie doit être traitée comme la spécification cible du site, et non comme le reflet d’un site déjà construit.

## 3. Architecture d’information cible

| Niveau principal | Rôle | Contenu essentiel |
|---|---|---|
| **Accueil** | Mettre en scène la marque | Hero, cinq mondes, créations BCDG, Atelier, Lifestyle, Arts de vivre, manifeste, newsletter, footer |
| **Boutique** | Vendre sans banaliser | Collections par mondes, filtres sobres, fiches produits éditorialisées |
| **Arts de vivre** | Préparer l’extension de gamme | Objets décoratifs japonais ou asiatiques sélectionnés selon la même exigence |
| **Lifestyle & Koi** | Créer un rendez-vous régulier | Culture des koi, motifs japonais, décoration, tendances, savoir-faire |
| **L’Atelier** | Incarner le projet | Alain et Catherine, origine du projet, regard, méthode de sélection, création BCDG |
| **Professionnels** | Qualifier les demandes B2B et B2G | Projets sur mesure, collectivités, minimums de commande, formulaire dédié |
| **Contact** | Ouvrir la conversation | Formulaire court, coordonnées utiles, demandes générales ou professionnelles |

La navigation principale doit employer des libellés courts. Sur ordinateur, l’ordre recommandé est : **Accueil · Boutique · Arts de vivre · Lifestyle · L’Atelier · Professionnels · Contact**. Les fonctions compte, recherche et panier peuvent être regroupées à droite sous forme d’icônes au trait fin.

## 4. Architecture narrative de la page d’accueil

| Ordre | Section | Fonction narrative | Action principale |
|---:|---|---|---|
| 1 | **Header Fusuma** | Ouvrir symboliquement la maison | Révéler la navigation |
| 2 | **Hero** | Produire l’émerveillement initial | Découvrir les mondes |
| 3 | **Les cinq mondes** | Organiser l’offre par imaginaires | Entrer dans un univers |
| 4 | **Créations BCDG** | Montrer les pièces signatures | Voir la collection |
| 5 | **L’Atelier** | Donner un visage et une intention | Rencontrer Alain et Catherine |
| 6 | **Lifestyle & Koi** | Installer un rendez-vous éditorial | Lire les derniers articles |
| 7 | **Arts de vivre** | Annoncer l’élargissement futur | Explorer la sélection |
| 8 | **Le Manifeste** | Exprimer l’origine, la réinvention et l’exception | Comprendre la démarche |
| 9 | **Professionnels** | Ouvrir le B2B et le B2G | Décrire un projet |
| 10 | **Newsletter** | Proposer une relation durable | S’inscrire |
| 11 | **Footer ligne d’horizon** | Clore sans alourdir | Accéder aux liens de service |

Cette succession doit être perçue comme une promenade. Les séparations ne prennent jamais la forme de gros aplats alternés systématiques. Elles utilisent plutôt un changement léger de texture, un pinceau d’encre, une brume, une respiration verticale ou un déplacement de composition.

## 5. Système visuel

### 5.1 Palette

| Jeton | Valeur | Usage |
|---|---|---|
| `--kh-washi` | `#F8F4EE` | Fond principal, zones de respiration |
| `--kh-sumi` | `#1A1410` | Texte, navigation, traits structurants |
| `--kh-vermillon` | `#C8311A` | Actions principales, cartouches, accents |
| `--kh-gold` | `#B8860B` | Détails précieux et citations |
| `--kh-indigo` | `#2B3A6B` | Profondeur, Arts de vivre, univers nocturnes |
| `--kh-white` | `#FFFDFC` | Cartes produits et surfaces très claires |

Le vermillon doit rester rare. Il attire l’œil vers l’action prioritaire et ne doit pas devenir une couleur décorative omniprésente.

### 5.2 Typographie

| Usage | Police | Graisse et traitement |
|---|---|---|
| Titres narratifs | Cormorant Garamond | 400, souvent italique, interlignage serré |
| Corps éditorial | Lora | 400, interlignage généreux |
| Navigation et interface | DM Sans | 300 à 400, capitales discrètes, approche légèrement augmentée |
| Japonais et kanji | Noto Serif JP | 400, usage ponctuel et signifiant |

Sur mobile, les titres doivent rester expressifs sans produire de césures absurdes. Employer `clamp()` pour les grandes tailles et conserver une largeur de texte lisible.

### 5.3 Grille et rythme

Le contenu principal utilise une largeur maximale d’environ **1 280 px**, avec des marges latérales généreuses. Les sections importantes disposent de grands espacements verticaux. Les blocs de texte éditorial ne dépassent pas environ 65 caractères par ligne. Les cartes produits restent rares par rangée : quatre au maximum sur grand écran, deux sur tablette et une ou deux sur mobile selon la taille des visuels.

## 6. Header Fusuma

Le header est une signature, mais il ne doit pas devenir un obstacle. Sur ordinateur, deux panneaux latéraux évoquant des portes fusuma peuvent s’écarter au survol ou au premier mouvement de la souris pour révéler la navigation. Le logo demeure au centre pendant la transition. Sur mobile et pour les utilisateurs ayant activé la réduction des animations, le header doit apparaître directement sous une forme simplifiée, sans effet obligatoire.

La réalisation recommandée repose sur des transformations CSS et des transitions courtes. Le composant doit accepter le clavier et ne doit jamais masquer durablement la navigation. Une version sans animation doit rester pleinement fonctionnelle.

## 7. Rubrique « L’Atelier » — Alain et Catherine

### 7.1 Positionnement

Cette rubrique ne doit pas être une page « Qui sommes-nous ? » générique. Elle doit montrer **deux personnes à l’origine d’un regard**, et non deux biographies juxtaposées. Le récit public doit rester précis, sobre et validé par Alain et Catherine avant publication.

### 7.2 Texte d’ouverture proposé

> **Deux regards, une même maison.**  
> Koinobori House est né d’un désir simple : donner à des objets porteurs d’histoire une présence nouvelle dans nos espaces de vie. Alain et Catherine choisissent, composent et racontent chaque univers avec la même exigence : respecter l’origine sans la figer, et laisser la beauté respirer.

Ce texte est une proposition éditoriale, pas une information biographique. Claude doit le considérer comme un brouillon à valider.

### 7.3 Composition recommandée

La page s’ouvre sur un grand portrait ou une scène de travail, décentré, avec un titre ample. Un second mouvement présente la naissance de Koinobori House. Un troisième explique le regard de sélection et la signature BCDG. Une citation courte, bordée d’un filet or brûlé, crée une pause. La page se termine par une invitation vers la boutique et les projets professionnels.

Aucune donnée personnelle, date, parcours ou rôle détaillé concernant Catherine ne doit être inventé. Les éléments biographiques non validés restent sous forme de champs à compléter.

## 8. Lifestyle & Koi — un magazine, pas une liste d’articles

La page d’archives du blog doit ressembler à la couverture d’un magazine. Le premier article occupe un grand espace, puis deux ou trois articles secondaires forment une composition asymétrique. Les métadonnées restent discrètes : catégorie, date et durée de lecture. Les vignettes standardisées et les encadrés lourds sont à éviter.

### 8.1 Catégories éditoriales proposées

| Catégorie | Promesse éditoriale | Exemples de sujets |
|---|---|---|
| **Koi & symboles** | Comprendre l’histoire et les significations | courage, fête des enfants, couleurs, légendes |
| **Intérieurs japonais** | Traduire une sensibilité sans pastiche | Ma, lumière, matières, équilibre |
| **Motifs & savoir-faire** | Lire les formes et les techniques | Asanoha, Seigaiha, teinture, papier |
| **Carnets d’inspiration** | Relier tendances et objets | palettes, scénographies, saisons |
| **Rencontres** | Donner la parole aux créateurs | ateliers, artisans, collectionneurs |

### 8.2 Modèle d’article

Chaque article utilise un hero éditorial avec titre, chapô, catégorie et image principale. Le corps est étroit et lisible, avec des intertitres, citations, légendes et images pleine largeur lorsque le sujet le justifie. En fin d’article, un bloc « Poursuivre le voyage » propose deux lectures connexes et, si pertinent, une sélection de produits liée au sujet. Les produits restent secondaires par rapport au contenu.

## 9. Arts de vivre — extension cohérente du catalogue

La nouvelle catégorie doit être présente dans l’architecture sans forcer son lancement immédiat. Elle peut d’abord apparaître comme un manifeste ou une page « bientôt », puis accueillir progressivement des objets décoratifs japonais ou asiatiques.

La sélection doit être organisée par **usage ou atmosphère** plutôt que par accumulation de catégories techniques. Exemples : objets de table, lumière, textiles, papier et petits paysages intérieurs. Chaque objet doit être présenté comme une pièce choisie : origine, matière, raison de la sélection et conseils de mise en scène.

Visuellement, l’indigo nuit peut signaler cet univers, mais uniquement dans des aplats ponctuels ou des fonds de section. Les fiches produits restent majoritairement claires afin de préserver la cohérence globale.

## 10. Boutique et fiches produits WooCommerce

### 10.1 Archive produits

La page boutique privilégie les mondes et les collections. Les filtres sont discrets, repliables et utiles. Les badges promotionnels agressifs, les évaluations omniprésentes et les multiples boutons concurrents sont supprimés. Une carte produit contient une image dominante, le monde, le nom, le prix et un bouton d’action vermillon.

### 10.2 Fiche produit

La fiche produit s’organise en deux colonnes sur ordinateur : galerie généreuse à gauche, informations à droite. Sous l’achat, quatre mouvements éditoriaux peuvent se succéder : **L’histoire de la pièce**, **Détails et fabrication**, **Dimensions et installation**, **Livraison et entretien**. Une section finale présente deux ou trois pièces du même monde.

Le bouton d’ajout au panier a des angles droits, un fond vermillon et une typographie DM Sans. Le prix reste visible sans dominer le récit. Les messages de stock ou de livraison sont rédigés dans un ton calme et précis.

## 11. Footer minimaliste — « La ligne d’horizon »

Le footer ne doit jamais former un gros bloc sombre. Il conserve le fond washi et se contente d’un trait horizontal très fin, ou d’un séparateur au pinceau extrêmement léger. Sa hauteur cible sur ordinateur est d’environ **72 à 96 px**, hors marges internes nécessaires à l’accessibilité.

### 11.1 Composition desktop

| Zone gauche | Zone centrale | Zone droite |
|---|---|---|
| `© 2026 Koinobori House · Créations BCDG` | `Mentions légales · CGV · Confidentialité · Contact` | Instagram · Pinterest · retour en haut |

Les icônes sociales sont au trait fin. Le lien de retour en haut peut être représenté par une flèche ascendante discrète et porter un libellé accessible. Aucun logo géant, aucune liste de catégories, aucun formulaire massif et aucun bandeau noir ne doivent être placés ici.

### 11.2 Newsletter séparée

La newsletter apparaît dans la section précédente, comme un dernier souffle éditorial : un titre court, une phrase, un champ e-mail souligné et un bouton ou une flèche vermillon. Elle ne doit pas transformer le footer en formulaire.

### 11.3 Comportement mobile

Sur mobile, les trois zones se replient en deux ou trois lignes centrées ou alignées à gauche, avec des espacements généreux. Les liens conservent une zone tactile suffisante. Le footer reste léger et ne dépasse pas ce qui est nécessaire.

### 11.4 Base CSS proposée

```css
.kh-footer {
  background: var(--kh-washi);
  color: var(--kh-sumi);
  border-top: 1px solid rgba(26, 20, 16, .12);
  font-family: "DM Sans", sans-serif;
  font-size: .78rem;
  letter-spacing: .04em;
}

.kh-footer__inner {
  width: min(1280px, calc(100% - 48px));
  min-height: 84px;
  margin-inline: auto;
  display: grid;
  grid-template-columns: 1fr auto 1fr;
  align-items: center;
  gap: 24px;
}

.kh-footer__social {
  justify-self: end;
}

@media (max-width: 760px) {
  .kh-footer__inner {
    grid-template-columns: 1fr;
    justify-items: start;
    padding-block: 28px;
  }
  .kh-footer__social { justify-self: start; }
}
```

Claude doit adapter les classes aux contraintes réelles du thème enfant, sans remplacer le principe visuel.

## 12. Implémentation WordPress / Kadence / WooCommerce

Le projet peut utiliser Kadence comme base, mais la direction artistique ne doit pas dépendre des styles par défaut du thème. Configurer les couleurs et typographies globales, puis créer des modèles réutilisables pour la page d’accueil, les mondes, l’archive Lifestyle, l’article et les pages WooCommerce. Les personnalisations structurantes doivent être placées dans un thème enfant ou un emplacement de code versionné, et non dispersées dans des champs impossibles à maintenir.

| Besoin | Implémentation recommandée |
|---|---|
| Palette et typographie | Styles globaux Kadence complétés par des variables CSS `--kh-*` |
| Sections éditoriales | Blocs Gutenberg/Kadence regroupés en compositions réutilisables |
| Mondes | Taxonomie produit ou pages éditoriales reliées aux catégories WooCommerce |
| Lifestyle | Articles WordPress, catégories éditoriales et modèle d’archive dédié |
| Atelier | Page éditoriale dédiée, blocs réutilisables pour l’extrait d’accueil |
| Footer | Surcharge du footer Kadence ou hook dédié, markup minimal |
| Produits | Surcharges ciblées des gabarits ou hooks WooCommerce, sans modifier le cœur de l’extension |
| Bilingue | Structure compatible avec l’extension multilingue retenue ; libellés et champs préparés en français et en anglais |

### 12.1 Répartition HTML, CSS et JavaScript

La charte est applicable aux **trois couches du front-end**, mais elle réside principalement dans le CSS. WordPress, Kadence et WooCommerce produisent une partie du HTML ; Claude doit conserver un balisage sémantique, ajouter des classes stables et éviter l’empilement de conteneurs génériques. Le CSS personnalisé traduit ensuite la direction artistique : matière washi, palette, familles typographiques, grilles, espacements, panneaux, pinceaux, footer, responsive et états interactifs. JavaScript n’intervient que lorsque l’interaction dépasse ce que HTML et CSS peuvent assurer proprement.

| Couche | Responsabilité pour Koinobori House | Exemples attendus |
|---|---|---|
| **HTML** | Structure, ordre de lecture, sémantique, accessibilité et points d’accroche | `header`, `nav`, `main`, `article`, `footer`, blocs Gutenberg/Kadence, markup WooCommerce et classes `.kh-*` |
| **CSS** | Environ 90 % de l’expression visuelle de la charte | Variables `--kh-*`, polices, fond washi, asymétrie, panneaux, boutons vermillon, Header Fusuma au survol, footer minimaliste, responsive, focus et `prefers-reduced-motion` |
| **JavaScript** | Interactions avancées ou dynamiques uniquement | Ouverture du Fusuma au clic ou au toucher, filtres produits sans rechargement, chargements progressifs et comportements nécessitant un état persistant |

> **Règle d’implémentation :** WordPress produit la structure ; le CSS personnalisé reproduit l’univers ; JavaScript reste léger, accessible et intentionnel.

Le Header Fusuma doit fonctionner en CSS pour le survol desktop lorsque cela suffit. Une couche JavaScript peut compléter le composant pour le clic, le tactile, l’ouverture au clavier ou la mémorisation de son état. Le contenu et la navigation restent toujours accessibles si JavaScript est désactivé ou si l’utilisateur réduit les animations.

## 13. Responsive, accessibilité et performance

La version mobile n’est pas une réduction du desktop. Les compositions asymétriques doivent se réordonner naturellement, les images conserver leur sujet principal et les transitions décoratives être simplifiées. Les animations respectent `prefers-reduced-motion`. Tous les contrôles sont utilisables au clavier, les contrastes sont vérifiés, les images portent des textes alternatifs utiles et les états de focus restent visibles.

Les visuels sont exportés dans des formats web adaptés et dimensionnés pour leur usage. Le hero doit être optimisé et ne pas bloquer le rendu. Les polices sont limitées aux graisses nécessaires. Les scripts non essentiels sont évités, notamment pour le Header Fusuma.

## 14. Critères d’acceptation pour Claude

| Domaine | Critère de sortie |
|---|---|
| Fidélité visuelle | Palette, typographies, respiration et hiérarchie correspondent à la charte de la présentation |
| Navigation | Les sept entrées principales sont présentes et restent utilisables sans animation |
| Accueil | Les onze mouvements narratifs sont en place dans l’ordre validé |
| Atelier | Le texte ne contient aucune biographie inventée ; les éléments publics sont validés |
| Lifestyle | L’archive et l’article utilisent des modèles éditoriaux distincts du blog Kadence par défaut |
| Boutique | Les archives et fiches produits ne ressemblent pas aux gabarits WooCommerce standards |
| Footer | Aucun bloc sombre ; fond washi, trait fin, une ligne sur desktop, newsletter séparée |
| Mobile | Le parcours reste lisible, tactile et élégant à 360 px de largeur |
| Accessibilité | Navigation clavier, focus visibles, alternatives textuelles et réduction des animations |
| Maintenance | Les personnalisations sont documentées et regroupées dans un thème enfant ou un code versionné |

## 15. Interdictions créatives

Claude ne doit pas introduire de dégradés « luxe » génériques, d’angles arrondis systématiques, de cartes vitrées, d’ombres épaisses, de blocs noirs massifs, de boutons multiples par section, d’icônes remplies, de fausses calligraphies illisibles ou de motifs japonais décoratifs sans fonction. Il ne doit pas utiliser un footer « méga-menu », ni déplacer la newsletter dans un gros pavé final.

## 16. Références

[1]: https://koinoborihouse.com "Koinobori House — état public observé le 25 juillet 2026"

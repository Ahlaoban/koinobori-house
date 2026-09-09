# Lot 3 — Homepage v2.0, les 11 mouvements (FR/EN)

> Statut : rédigé 2026-09-08. Remplace [homepage-v1-FR-EN.md](homepage-v1-FR-EN.md), superséde le 2026-07-27.
> Structure de référence : charte v2.0 §4 et [ux-architecture.md](../ux-architecture.md) §4.
> **Répartition** : les textes sont à Claude, la composition des 11 mouvements est à Manus ([REPARTITION](../handoff/REPARTITION-MANUS-CLAUDE.md) §2.4). Ce document fournit la matière, pas le gabarit.
> Doctrine : aucune mention d'atelier, de production, de fabrication ni d'origine de fabrication. Jamais la Chine. Jamais de tiret cadratin. Vermillon réservé à l'action prioritaire. Aucune référence au volume global de stock.

## Provenance des textes

| Origine | Mouvements |
|---|---|
| Repris tels quels de la v1, textes restés valides | 2 Hero, 3 Les cinq mondes, 4 Créations BCDG, 8 Le Manifeste, 10 Newsletter |
| Écrits le 2026-09-08 | 5 L'Atelier, 6 Lifestyle & Koi, 7 Arts de vivre, 9 Professionnels |
| Sans texte à saisir | 1 Header, 11 Footer |

Trois renommages sont répercutés : « Journal » devient « Lifestyle & Koi », la page « Univers » devient « L'Atelier », et le label public « Collections » disparaît au profit de « les cinq mondes » (A-2 annulé).

---

## Mouvement 1 — Header Fusuma

Rien à saisir. Header Shoji V2 verrouillé (C5), portage Lot 7.

---

## Mouvement 2 — Hero

Fond `hero_fond_web_16x9.png` en CSS, logo, cartouche et accroche reconstruits en HTML (v2.0 §4.1). Le koinobori n'est jamais coupé. Un seul CTA, en vermillon.

### FR

- **Titre** : Des carpes de vent originales, signées BCDG
- **Sous-titre** : Koinobori contemporains à suspendre, édités en petites séries.
- **CTA unique** : Découvrir les cinq mondes → ancre du mouvement 3

### EN

- **Title**: Original wind carps, signed by BCDG
- **Subtitle**: Contemporary koinobori to hang, released in small series.
- **CTA**: Discover the five worlds

⚠️ Le CTA disait « Découvrir les collections » en v1. Le label public « Collections » ayant été retiré (A-2 annulé), il devient « les cinq mondes ». À confirmer.

---

## Mouvement 3 — Les cinq mondes

Grille asymétrique, cartes vers les pages Mondes. **Pas de titre de section « Collections ».** Kaïro n'a plus de traitement de faveur en grille (A-4 annulé), sa mise en avant passe par le mouvement 4.

| Monde | Accroche FR | Tagline EN |
|---|---|---|
| Mer / Sea | L'appel du large | The call of the open sea |
| Motifs / Patterns | Symboles et écailles graphiques | Graphic symbols and scales |
| Hanami | La contemplation des fleurs | Blossom viewing |
| Kaïro | Un personnage, un cycle, quinze épisodes | One character, one cycle, fifteen episodes |
| Territoires / Lands | Régions et drapeaux revisités | Regions and flags revisited |

Titre de section, si Manus en veut un : **FR** « Cinq mondes » · **EN** « Five worlds ».

---

## Mouvement 4 — Créations BCDG

Porte à la fois la sélection de pièces et le récit Kaïro (O-9 : Kaïro est une création BCDG, pas un 12ᵉ mouvement).

### FR

- **Titre** : Créations BCDG
- **Chapô** : Chaque pièce est dessinée, signée et éditée en petite série. Voici quelques-unes d'entre elles.
- **Bloc Kaïro** : Kaïro, personnage original créé par BCDG, traverse sa première aventure : Le Navire Sans Nom, un cycle de quinze koinobori. Chaque épisode est une pièce, chaque pièce est un chapitre. Les quatre premiers épisodes sont disponibles.
- **CTA secondaire, bordure** : Entrer dans le cycle Kaïro → page Monde Kaïro

### EN

- **Title**: BCDG Creations
- **Intro**: Every piece is drawn, signed and released in a small series. Here are a few of them.
- **Kaïro block**: Kaïro, an original character created by BCDG, sails through his first adventure: The Nameless Ship, a cycle of fifteen koinobori. Each episode is a piece, each piece is a chapter. The first four episodes are available.
- **Secondary CTA**: Enter the Kaïro cycle

⚠️ Wording : « Sélection » ou « Créations », **jamais « bestsellers »** ni aucune formule qui laisse deviner des volumes.

---

## Mouvement 5 — L'Atelier

Donner un visage et une intention. **Sans portrait** (O-4). Rien d'inventé sur Catherine. Le contenu de cette rubrique ne parle jamais de production ni de fabrication (C2). Visuel d'ouverture par défaut : `apropos-hero.png` du pack v1 (O-8).

### FR

- **Titre** : L'Atelier
- **Texte** : Koinobori House est une maison française. Le nom de cette page vient de ce qu'on y fait, choisir. Un motif plutôt qu'un autre, une couleur qui tient au soleil, une taille qui va à une façade et pas à un couloir. Une pièce entre au catalogue quand elle tient debout toute seule.
- **Lien discret** : Entrer dans L'Atelier → `/fr/atelier/`

### EN

- **Title**: The House
- **Text**: Koinobori House is a French house. The name of this page comes from what happens here, which is choosing. One pattern rather than another, a colour that holds up in the sun, a size that suits a facade and not a corridor. A piece joins the catalogue when it stands on its own.
- **Discreet link**: Step into The House → `/en/the-house/`

---

## Mouvement 6 — Lifestyle & Koi

Installer un rendez-vous éditorial. **Section masquée tant qu'aucun article n'est publié** : jamais de lien vers une page vide.

### FR

- **Titre** : Lifestyle & Koi
- **Chapô** : Ce que raconte un motif, comment installer une pièce chez soi, ce que la culture japonaise a fait de la carpe, pourquoi le vent compte autant que la couleur.
- **Lien** : Lire le magazine → `/fr/lifestyle/`

### EN

- **Title**: Lifestyle & Koi
- **Intro**: What a pattern carries, how to hang a piece at home, what Japanese culture made of the carp, and why the wind matters as much as the colour.
- **Link**: Read the magazine → `/en/lifestyle-koi/`

---

## Mouvement 7 — Arts de vivre

Annoncer l'élargissement futur. Indigo `--kh-indigo` en aplat ponctuel seulement, jamais en bloc massif. Page « bientôt » au MVP (C6).

### FR

- **Titre** : Arts de vivre
- **Texte** : Bientôt, une sélection d'objets décoratifs japonais et asiatiques, choisis avec la même exigence que nos créations. Peu de pièces, organisées par usage et par atmosphère plutôt que par rayon.
- **Lien** : Découvrir l'intention → `/fr/arts-de-vivre/`

### EN

- **Title**: Art de Vivre
- **Text**: Coming soon, a selection of Japanese and Asian decorative objects, chosen with the same demands we place on our own creations. Few pieces, organised by use and atmosphere rather than by department.
- **Link**: Discover the intention → `/en/art-de-vivre/`

---

## Mouvement 8 — Le Manifeste

Exprimer origine du projet, réinvention et exception. Fond washi, cartouche 鯉のぼり en filigrane possible (opacité .04 à .08). ⚠️ La maison n'est pas la signature : Koinobori House est l'identité du site, BCDG la signature apposée sur les pièces.

### FR

> Un koinobori est une carpe de vent : suspendue, elle prend vie au moindre souffle. Koinobori House édite des designs originaux, signés BCDG et tirés en petites séries. Entre tradition japonaise et création contemporaine, chaque pièce est pensée pour vivre dehors comme dedans, au vent du jardin ou dans la lumière d'un salon.
>
> Koinobori House est la maison d'édition de ces carpes ; BCDG, la signature créative d'Alain Herbinière, en est le regard.

- **Lien discret** : L'univers de la maison → `/fr/atelier/`

### EN

> A koinobori is a wind carp: once hung, it comes alive with the faintest breeze. Koinobori House publishes original designs, signed by BCDG and released in small series. Between Japanese tradition and contemporary creation, each piece is designed to live outdoors and indoors alike, in the wind of a garden or the light of a living room.
>
> Koinobori House is the house that publishes these carps; BCDG, the creative signature of Alain Herbinière, is the eye behind them.

- **Discreet link**: The world of the house → `/en/the-house/`

---

## Mouvement 9 — Professionnels

Ouvrir le B2B et le B2G. Aiguillage vers les deux parcours, jamais une fusion (A-6).

### FR

- **Titre** : Professionnels
- **Texte** : Un koinobori se voit de loin. Il transforme une place, une façade, un hall ou une cour d'école en quelques minutes. Nous accompagnons les entreprises et les institutions sur leurs projets visuels.
- **Lien** : Décrire un projet → `/fr/professionnels/`

### EN

- **Title**: For Professionals
- **Text**: A koinobori can be seen from a distance. It transforms a square, a facade, a lobby or a schoolyard within minutes. We work with companies and institutions on their visual projects.
- **Link**: Describe a project → `/en/professionals/`

---

## Mouvement 10 — Newsletter

Titre court, une phrase, champ e-mail souligné, flèche ou bouton vermillon. **Jamais dans le footer** (v2.0 §11.2).

### FR

- **Texte** : Recevez les nouvelles de la maison : nouveaux designs, épisodes Kaïro, histoires de vent.
- **CTA** : S'inscrire
- **Mention** : Désinscription à tout moment. Voir notre [politique de confidentialité](/fr/politique-de-confidentialite/).

### EN

- **Text**: News from the house: new designs, Kaïro episodes, stories of wind.
- **CTA**: Subscribe
- **Note**: Unsubscribe anytime. See our [privacy policy](/en/privacy-policy/).

🔴 **Bloqué** : le champ ne doit pas être posé tant que Brevo n'est pas connecté. Un formulaire d'inscription qui n'enregistre rien est pire que pas de formulaire, et la mention de désinscription serait mensongère. À poser quand la clé Brevo sera saisie dans FluentSMTP et la liste créée.

---

## Mouvement 11 — Footer ligne d'horizon

Rien à saisir ici. Structure arbitrée en [ux-architecture.md](../ux-architecture.md) §8, présentation à Manus, qui a déjà `.kh-footer`, `.kh-services` et `.kh-horizon` dans `kh-charte-v3.css` (PR #12).

⚠️ **Divergence à arbitrer** : le `.kh-services__grid` de Manus est une bande de **4 colonnes de réassurance** au format « intitulé + précision », alors que l'arbitrage O-1 prévoit une bande de **3 groupes de liens** (Navigation · Informations · Professionnels). Les deux ne se superposent pas. À trancher avant de câbler le footer.

---

## Le bandeau confiance de la v1 n'a plus de mouvement

La v2.0 ne lui en donne aucun. Son contenu se répartit ainsi (O-1) : Livraison, Retours et Contact rejoignent la bande services du footer ; « Petites séries signées BCDG » est déjà porté par le mouvement 4. Les formulations restent utilisables telles quelles si Manus veut les replacer :

| FR | EN |
|---|---|
| Livraison offerte en France métropolitaine dès 55 € | Free delivery in metropolitan France from 55 € |
| Retours sous 14 jours | 14-day returns |
| Petites séries signées BCDG | Small series signed by BCDG |
| Une question ? contact@koinoborihouse.com | A question? contact@koinoborihouse.com |

⚠️ Jamais « frais Stripe », « frais PayPal », « commission », « droits de douane offerts ».

---

## Métadonnées SEO de la page d'accueil

| | Titre | Description |
|---|---|---|
| FR | Koinobori House · Carpes de vent originales signées BCDG | Koinobori contemporains à suspendre, dessinés et signés BCDG, édités en petites séries. Cinq mondes : Mer, Motifs, Hanami, Kaïro, Territoires. |
| EN | Koinobori House · Original wind carps signed by BCDG | Contemporary koinobori to hang, designed and signed by BCDG, released in small series. Five worlds: Sea, Patterns, Hanami, Kaïro and Lands. |

Les descriptions v1 mentionnaient « Livraison offerte en France dès 55 € ». Retiré ici pour tenir sous 155 caractères et parce qu'une incitation chiffrée dans une meta description vieillit mal. À remettre si tu y tiens.

---

## Points à trancher par Alain

1. **CTA du hero** : « Découvrir les cinq mondes » remplace « Découvrir les collections ». À confirmer.
2. **Mouvement 10** : newsletter tenue hors ligne tant que Brevo n'est pas connecté. Confirmer qu'on la pose seulement après.
3. **Footer** : divergence entre la bande de réassurance à 4 colonnes de Manus et la bande de 3 groupes de liens d'O-1.
4. **Mouvement 8** : le manifeste nomme Alain Herbinière. C'était déjà le cas en v1, mais cela n'a jamais été explicitement validé pour publication.
5. 🔴 **Contradiction sur l'identité de BCDG dans le corpus public, à trancher.** Le manifeste disait « BCDG, **l'entreprise créative** créée par Alain Herbinière », pendant que les CGV article 1 disent « BCDG **ne constitue pas une entité juridique distincte** du vendeur ». Corrigé ici le 2026-09-09 en « la signature créative d'Alain Herbinière », conforme à CLAUDE.md §Identité marque, qui ne définit BCDG que comme une signature apposée sur les produits.
   ⚠️ **Mais la même formulation subsiste dans les mentions légales** (`01-mentions-legales-FR.md`, lignes 5 et 35), où la checklist KH-017 §A la qualifie de « formulation obligatoire ». Je ne l'ai pas touchée : c'est un texte légal validé à la clôture de KH-017, et le corriger relève de ton arbitrage, pas d'une revue de code. Les deux formulations ne peuvent pas rester côte à côte en public.

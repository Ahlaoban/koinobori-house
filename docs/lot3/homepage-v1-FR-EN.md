# Lot 3 — Homepage v1 FR/EN (SUPERSÉDÉ)

> 🛑 **SUPERSÉDÉ le 2026-07-27** par les **11 mouvements** de la charte v2.0 « Ma, L'Intervalle enchanté » (§4). **Ne pas saisir la structure S0-S9 de ce document.**
>
> **Ce qui est mort ici** : l'ordre S0-S9, le titre de section « Collections » (A-2 annulé), la carte Kaïro en case large 1.3fr comme marque de flagship (A-4 annulé), la section « Journal » sous ce nom, le bandeau confiance S8, la structure footer en colonnes.
>
> **Ce qui reste vivant et doit être réutilisé tel quel** : les **textes rédigés** FR/EN (hero, manifeste, newsletter, accroches des 5 mondes, teaser BCDG). Ils sont valides, seulement à recouler dans la nouvelle structure.
>
> **Ce qui doit être écrit en plus** : les blocs des mouvements v2.0 sans équivalent ici — **L'Atelier** (mouvement 5), **Arts de vivre** (mouvement 7), **Professionnels** (mouvement 9).
>
> **Renommages à répercuter partout** : « Journal » → « **Lifestyle & Koi** » ; « Collections » (label public) → « **les cinq mondes** » ; page « Univers » → « **L'Atelier** ».
>
> Structure cible : [docs/charte-graphique/KH-000b-charte-v2-Ma-Intervalle-enchante-WORDPRESS.md](../charte-graphique/KH-000b-charte-v2-Ma-Intervalle-enchante-WORDPRESS.md) §4 et [docs/ux-architecture.md](../ux-architecture.md) §4.

---

> Historique. Statut d'origine : rédigé 2026-07-24, à valider Alain avant saisie staging.
> Structure = [docs/ux-architecture.md](../ux-architecture.md) §4, ordre immersion S0-S9.
> Blocs Kadence natifs, respiration « Ma » (padding sections ≥ 80-120 px), aucune image d'ambiance figée hors hero (arbitrage A-10 sobre + accents).
> Doctrine : aucune mention atelier/production/origine, pas de tiret cadratin, vermillon = CTA uniquement.

---

## S0 — Header Shoji V2

Verrouillé, présent partout. Rien à saisir ici (portage Lot 7).

## S1 — Hero

- Image : `bg-hero-sky` (banque images, ciel/koinobori) ; zoom lent optionnel (KH-000 §8).
- **FR titre** : Des carpes de vent originales, signées BCDG
- **FR sous-titre** : Koinobori contemporains à suspendre, édités en petites séries.
- **FR CTA (vermillon, unique)** : Découvrir les collections → ancre S2
- **EN title**: Original wind carps, signed by BCDG
- **EN subtitle**: Contemporary koinobori to hang, released in small series.
- **EN CTA**: Explore the collections

## S2 — Les collections (grille asymétrique 1.3fr 1fr 1fr 1fr 1fr)

- **Titre section FR** : Collections · **EN** : Collections
- 5 cartes (visuel `cat-*-hero` + nom + accroche 1 ligne), liens vers pages collections :

| Collection | Accroche FR | Tagline EN |
|---|---|---|
| Mer / Sea | L'appel du large | The call of the open sea |
| Kaïro | Un personnage, un cycle, quinze épisodes | One character, one cycle, fifteen episodes |
| Hanami | La contemplation des fleurs | Blossom viewing |
| Motifs / Patterns | Symboles et écailles graphiques | Graphic symbols and scales |
| Territoires / Lands | Régions et drapeaux revisités | Regions and flags revisited |

- Carte Kaïro = case large (1.3fr), cohérent flagship (A-4).

## S3 — Kaïro flagship

- **FR** : Kaïro, personnage original créé par BCDG, traverse sa première aventure : Le Navire Sans Nom, un cycle de quinze koinobori. Chaque épisode est une pièce, chaque pièce est un chapitre. Les quatre premiers épisodes sont disponibles.
- **FR CTA (secondaire, bordure)** : Entrer dans le cycle Kaïro → page collection Kaïro
- **EN**: Kaïro, an original character created by BCDG, sails through his first adventure: The Nameless Ship, a cycle of fifteen koinobori. Each episode is a piece, each piece is a chapter. The first four episodes are available.
- **EN CTA**: Enter the Kaïro cycle
- Fond : teinte Monde Kaïro discrète (pas d'aplat massif), kanji fond opacity .04 possible.

## S4 — La sélection

- **Titre FR** : La sélection · **EN** : The selection
- Grille produits WooCommerce dynamique (3-4 produits mis en avant, choix manuel admin).
- Cartes produit : fond blanc cassé (jamais blanc pur, arbitrage E), prix Cormorant, bouton vermillon.
- ⚠️ Wording : jamais « bestsellers » (doctrine stock) ; « La sélection » validé.

## S5 — Manifeste + teaser BCDG

- **FR** :
  > Un koinobori est une carpe de vent : suspendue, elle prend vie au moindre souffle. Koinobori House édite des designs originaux, signés BCDG et tirés en petites séries. Entre tradition japonaise et création contemporaine, chaque pièce est pensée pour vivre dehors comme dedans, au vent du jardin ou dans la lumière d'un salon.
  >
  > Koinobori House est la maison d'édition de ces carpes ; BCDG, l'entreprise créative créée par Alain Herbinière, en est la signature.
- **FR lien (discret)** : L'univers de la maison → page Univers
- **EN**:
  > A koinobori is a wind carp: once hung, it comes alive with the faintest breeze. Koinobori House publishes original designs, signed by BCDG and released in small series. Between Japanese tradition and contemporary creation, each piece is designed to live outdoors and indoors alike, in the wind of a garden or the light of a living room.
  >
  > Koinobori House is the house that publishes these carps; BCDG, the creative company founded by Alain Herbinière, is their signature.
- **EN link**: The world of the house
- Fond washi, cartouche 鯉のぼり filigrane possible (opacity .04-.08).
- ⚠️ Distinction maison ≠ signature respectée (UX-005). Aucune mention fabrication.

## S6 — Newsletter

- **FR** : Recevez les nouvelles de la maison : nouveaux designs, épisodes Kaïro, histoires de vent. — champ email + CTA vermillon « S'inscrire ». Mention : Désinscription à tout moment. Voir notre [politique de confidentialité].
- **EN**: News from the house: new designs, Kaïro episodes, stories of wind. — email field + CTA "Subscribe". Note: Unsubscribe anytime. See our [privacy policy].

## S7 — Le Journal

- **Titre FR** : Le Journal · **EN** : The Journal
- 2-3 cartes articles (dynamique). Section masquée tant qu'aucun article publié (jamais de lien vers page vide).

## S8 — Bandeau confiance

3 items + contact, icônes or (arbitrage A) :

| FR | EN |
|---|---|
| Livraison offerte en France dès 55 € | Free delivery in France over 55 € |
| Retours sous 14 jours | 14-day returns |
| Petites séries signées BCDG | Small series signed by BCDG |
| Une question ? contact@koinoborihouse.com | A question? contact@koinoborihouse.com |

- ⚠️ Jamais « frais Stripe/PayPal », « commission », « droits de douane offerts ».

## S9 — Footer (structure proposée, à confirmer au build)

- Colonnes : **Collections** (5 liens) · **Aide** (Livraison, Livraison USA, Retours, Contact, Sur-mesure) · **La maison** (Univers, Journal, Mentions légales, CGV, Confidentialité, Cookies, Gérer les cookies) · **Professionnels** (Entreprises, Collectivités).
- Logo noir `logo-color.png` + cartouche 鯉のぼり filigrane + sélecteur FR/EN.
- Mention : TVA non applicable, art. 293 B du CGI.

---

## Métadonnées SEO homepage

- **FR title** : Koinobori House | Carpes de vent originales signées BCDG
- **FR description** : Koinobori contemporains à suspendre, dessinés et signés BCDG, édités en petites séries. Collections Mer, Kaïro, Hanami, Motifs, Territoires. Livraison offerte en France dès 55 €.
- **EN title**: Koinobori House | Original wind carps signed by BCDG
- **EN description**: Contemporary koinobori to hang, designed and signed by BCDG, released in small series. Sea, Kaïro, Hanami, Patterns and Lands collections. Free delivery in France over 55 €.

# Architecture UX — Koinobori House

> ⚠️ **RÉVISÉ le 2026-07-27 par l'adoption de la charte v2.0 « Ma, L'Intervalle enchanté »** (arbitrages C1-C6). **A-1, A-2 et A-4 sont ANNULÉS.** Les autres arbitrages du 2026-07-24 restent en vigueur, dont **A-6**.
> Référence graphique : **charte v2.0** ([docs/charte-graphique/README.md](charte-graphique/README.md)). KH-000 v1.2 reste la base technique des fondations déployées, superseded pour les couches visuelles.
> Header Shoji V2 **verrouillé** (UX-Header-001) — jamais rouvert par ce document. C5 le confirme comme réalisation du principe Fusuma (v2.0 §6).
> Gouvernance : UX = arbitrage exclusif Alain ; ce doc fige les décisions rendues, rien d'autre.
> Planning : **lancement 15 août 2026**.

## 1. Arbitrages rendus

### 1.1 Annulés par C4 (2026-07-27)

| # | Décision du 2026-07-24 | Ce qui la remplace |
|---|---|---|
| ~~A-1~~ | ~~Menu B + mega-panel `Boutique · Collections ▾ · Kaïro · Univers · Journal`~~ | **Nav v2.0 à plat, 7 entrées** (§3). Plus de mega-panel, plus de panneau *sibling* |
| ~~A-2~~ | ~~Label « Collections » partout~~ | Le label disparaît de la nav. Les 5 collections WooCommerce sont inchangées et présentées comme « **les cinq mondes** » |
| ~~A-4~~ | ~~Kaïro flagship avec slot menu propre~~ | Plus de slot menu Kaïro. Le récit « The Nameless Ship » survit **hors nav**, sur la page Monde Kaïro. Sa présence sur la homepage est un **point ouvert** (O-9) |

Corollaires mécaniques, sans nouvel arbitrage :
- **A-11 devient sans objet** : le problème de faisabilité du sous-menu shoji disparaît avec le mega-panel.
- **A-7 conservé dans son esprit** : le CTA hero pointe vers les **cinq mondes**, pas la Boutique (v2.0 §4, mouvement 2 → « Découvrir les mondes »).
- **A-5 conservé, renommé** : « Journal » devient « **Lifestyle & Koi** » partout (nav, homepage, footer, SEO).

### 1.2 En vigueur

| # | Décision | Verdict |
|---|----------|---------|
| A-3 | Rôle de Territoires | **Monde-collection seul** (régions + drapeaux) ; le B2G reste sur Collectivités |
| A-5 | Lifestyle & Koi MVP | **Inclus** : entrée nav + section homepage conditionnelle (ex-« Journal ») |
| A-6 | Accès B2B/B2G | ✅ **NON ANNULÉ** (confirmé explicitement 2026-07-27). **1 entrée nav « Professionnels »** = page d'aiguillage → `/fr/entreprises/` + `/fr/collectivites/`, formulaires distincts, **jamais fusionnés** (doctrine). Les 2 liens footer directs sont à réconcilier avec le footer minimaliste v2.0 §11.1 — cf §8 |
| A-7 | CTA hero | **Vers les cinq mondes** (pas la Boutique) |
| A-8 | Ordre homepage | **Principe conservé**, liste de sections remplacée. « Immersion d'abord » reste vrai : les 11 mouvements v2.0 §4 sont eux-mêmes immersion-first. Seul l'énuméré S0-S9 est superseded — cf §4 |
| A-9 | Storytelling BCDG home | **Teaser court**, récit complet sur la page dédiée |
| A-10 | Richesse visuelle | **Sobre + accents** : washi dominant, ambiance limitée au hero + heros des pages Mondes |
| Mobile | Navigation mobile | **Overlay accordéon** (voir §5) |

## 2. Architecture générale

Quatre familles de contenu, 2-3 niveaux max (buildable Kadence).

**A — Commerce (B2C transactionnel)**
- `Boutique` : grille tous produits, filtres (Collection, Taille, Prix).
- **5 pages Collections** (= 5 Mondes, palette propre par Monde KH-000 §6) : Mer · Kaïro · Hanami · Motifs · Territoires.
- Fiche produit (2 blocs doctrine) → Panier → Checkout WooCommerce bilingue.

**B — Marque / éditorial (différenciation maison de marque)**
- `L'Atelier` : Alain et Catherine, origine du projet, regard, méthode de sélection, signature BCDG (v2.0 §7). Remplace l'ancienne page `Univers`. ⚠️ Rien d'inventé sur Catherine ; le texte d'ouverture v2.0 §7.2 est un brouillon à valider par Alain.
- `Lifestyle & Koi` : magazine (ex-`Journal`), 3 articles FR MVP, EN condensé. Archive éditoriale, pas une liste d'articles (v2.0 §8).
- `Arts de vivre` : page « manifeste / bientôt » au MVP (C6). **Aucune catégorie WooCommerce.**
- Kaïro : plus de slot nav (A-4 annulé). Le récit « The Nameless Ship » vit dans la section homepage et la page Monde Kaïro.

**C — Captation qualifiée (B2B/B2G, MVP = landing + Fluent Forms)**
- `Professionnels` : page d'aiguillage → `/fr/entreprises/` + `/fr/collectivites/` (et slugs EN).

**D — Utilitaire (header V2 + footer)**
- FR/EN (texte, sans drapeau) · Mon compte · Panier · Aide/Contact · légales.

## 3. Navigation desktop (v2.0 §3, arbitrage C4)

**7 entrées, à plat** (remplace la rangée placeholder du prototype V2 ; mécanique battants/logo/timings inchangée) :

`Accueil · Boutique · Arts de vivre · Lifestyle · L'Atelier · Professionnels · Contact`

Fonctions utilitaires regroupées à droite en **icônes au trait fin** : compte · recherche · panier. Plus `FR/EN` en texte, sans drapeau (UX-001).

Libellés courts obligatoires. La nav doit rester **utilisable sans animation** (critère de sortie v2.0 §14).

**Le mega-panel est supprimé** (A-1 annulé). Conséquences :
- Le constat technique A-11 (`overflow:hidden` du header incompatible avec un sous-menu interne) **devient sans objet** : plus aucun sous-menu à héberger.
- Le panneau *sibling* pleine largeur n'est plus à construire. La « Spec mega-panel » listée en §7 est retirée du reste-à-produire.
- Les 5 mondes ne sont plus atteints par la nav mais par la **page Boutique** et par le mouvement 3 de la homepage.
- Bénéfice collatéral : le risque `mouseleave` header ↔ panneau au portage Lot 7 disparaît.

⚠️ Reste à porter : « Mon compte » du prototype V2 est en corail `#E05A5A` → vermillon `#C8311A` au portage Lot 7.

### 3.1 Libellés et slugs bilingues (O-10, tranché 2026-07-27)

| FR | Slug FR | EN | Slug EN |
|---|---|---|---|
| Accueil | `/fr/` | Home | `/en/` |
| Boutique | `/fr/boutique/` | Shop | `/en/shop/` |
| Arts de vivre | `/fr/arts-de-vivre/` | **Art de Vivre** | `/en/art-de-vivre/` |
| Lifestyle | `/fr/lifestyle/` | Lifestyle | `/en/lifestyle-koi/` ⚠️ |
| L'Atelier | `/fr/atelier/` | **The House** | `/en/the-house/` (O-7) |
| Professionnels | `/fr/professionnels/` | **For Professionals** | `/en/professionals/` |
| Contact | `/fr/contact/` | Contact | `/en/contact-us/` ⚠️ |

⚠️ **Deux slugs EN corrigés le 2026-09-08 sur constat de terrain.** WordPress impose l'unicité des slugs entre langues sous Polylang Free : `/en/contact/` et `/en/lifestyle/` étaient impossibles, les slugs FR `contact` et `lifestyle` les occupant déjà. Les pages en ligne portent `contact-us` et `lifestyle-koi`. Cette table est alignée sur l'existant, il ne faut pas renommer les pages pour revenir à la version théorique.

« Art de vivre » s'emploie tel quel en anglais : on le conserve, même logique que les noms d'œuvres gardés en FR avec gloss EN.

⚠️ **« Professionnels » ≠ « Entreprises ».** La page d'aiguillage prend `/en/professionals/` ; `/en/business/` reste attribué à **Entreprises** (B2B). Ne pas confondre les deux, ni fusionner leurs formulaires (A-6).

Slugs Polylang : créer en FR, puis traduire pour obtenir le slug EN dédié.

## 4. Homepage — 11 mouvements (v2.0 §4)

L'ordre v2.0 remplace l'ordre S0-S9 du 2026-07-24 (A-8). La succession doit être perçue comme **une promenade** : les séparations n'utilisent jamais de gros aplats alternés systématiques, mais un changement léger de texture, un pinceau d'encre, une brume, une respiration verticale ou un déplacement de composition.

| # | Mouvement | Fonction narrative | Action | Notes doctrine |
|---:|---|---|---|---|
| 1 | Header Fusuma | Ouvrir symboliquement la maison | Révéler la navigation | = Shoji V2 verrouillé (C5) |
| 2 | Hero | Produire l'émerveillement initial | Découvrir les mondes | fond `hero_fond_web_16x9.png` en CSS, logo/cartouche/tagline reconstruits en HTML (v2.0 §4.1). Koinobori jamais coupé |
| 3 | Les cinq mondes | Organiser l'offre par imaginaires | Entrer dans un univers | grille asymétrique, cartes → pages Mondes. **Plus de titre « Collections »** (A-2 annulé) |
| 4 | Créations BCDG | Montrer les pièces signatures | Voir la collection | wording « Sélection » / « Créations », **jamais « bestsellers »** (doctrine stock) |
| 5 | L'Atelier | Donner un visage et une intention | Rencontrer Alain et Catherine | ⚠️ **rien d'inventé sur Catherine** ; texte v2.0 §7.2 = brouillon à valider |
| 6 | Lifestyle & Koi | Installer un rendez-vous éditorial | Lire les derniers articles | ex-« Journal » ; conditionnel contenu prêt |
| 7 | Arts de vivre | Annoncer l'élargissement futur | Explorer la sélection | page « bientôt » au MVP (C6) ; indigo en aplat ponctuel seulement |
| 8 | Le Manifeste | Exprimer origine, réinvention, exception | Comprendre la démarche | ❌ « cousu à la main » / origine / atelier / fabrication ; logo maison ≠ signature BCDG |
| 9 | Professionnels | Ouvrir le B2B et le B2G | Décrire un projet | aiguillage vers Entreprises **et** Collectivités (A-6) |
| 10 | Newsletter | Proposer une relation durable | S'inscrire | titre court + phrase + champ souligné + flèche vermillon. **Jamais dans le footer** (v2.0 §11.2) |
| 11 | Footer ligne d'horizon | Clore sans alourdir | Accéder aux liens de service | fond washi, trait fin, 72-96 px desktop. ❌ aucun bloc sombre, aucun méga-menu |

**Le bandeau confiance S8 n'a plus de mouvement dédié** dans la v2.0 : la spec est silencieuse à son sujet, elle ne le supprime pas explicitement. Son contenu (livraison France offerte ≥ 55 €, retours, contact USA) reste à replacer — cf O-1 en §8. Wording : ❌ Stripe / PayPal / commission / droits de douane offerts (KH-707).

Respiration « Ma » : grands espacements verticaux entre mouvements (≥ 80-120 px), largeur max ~1280 px, texte éditorial ≤ ~65 caractères/ligne.

## 5. Mobile

- Mécanique shoji = desktop. Mobile : **hamburger or → overlay washi plein écran** (KH-000 §9), accordéon :

```
Accueil
Boutique
Arts de vivre
Lifestyle
L'Atelier
Professionnels
Contact
────────────
Mon compte · Recherche · Panier
FR / EN
```

- Plus d'accordéon : la nav v2.0 est à plat, il n'y a plus de niveau à déplier (A-1 annulé).
- **Panier + Boutique accessibles hors menu** (icônes persistantes).
- FR/EN visible dès l'ouverture de l'overlay.
- Header mobile : forme **simplifiée d'emblée**, sans effet fusuma obligatoire. Idem pour `prefers-reduced-motion` (v2.0 §6).
- Cible de lisibilité : **360 px de largeur** (critère de sortie v2.0 §14).

## 6. Parcours utilisateurs

**Principal — découverte par univers** :
`Home (hero) → les cinq mondes (mouvement 3) → page Monde (palette propre) → fiche produit → panier → checkout`
Le mega-panel n'existe plus : l'entrée dans les mondes se fait par la homepage ou par la Boutique.

**Alternatif — transactionnel direct** :
`Home → Boutique → filtres (Collection/Taille/Prix) → fiche → panier → checkout`
La Boutique reste à 1 clic de la nav (filet visiteur pressé / habitué Etsy). Filtres **discrets, repliables** ; ❌ pas de badges promo agressifs, ni d'évaluations omniprésentes, ni de boutons concurrents (v2.0 §10.1).

**Captation B2B/B2G (hors tunnel)** :
`Header « Professionnels » ou footer → landing Entreprises OU Collectivités → Fluent Form → lead offline`

## 7. Fiche produit (v2.0 §10.2)

Deux colonnes desktop : galerie généreuse à gauche, informations à droite. Sous l'achat, **4 mouvements éditoriaux** :

1. **L'histoire de la pièce**
2. **Détails et matières** ← arbitrage C1. La spec écrit « Détails et fabrication » ; **jamais** *fabrication* (doctrine)
3. **Dimensions et installation**
4. **Livraison et entretien**

Section finale : **2 ou 3 pièces du même monde** (recommandations same-monde).

Bouton panier : **angles droits**, fond vermillon, typo DM Sans. Prix visible sans dominer le récit. Messages de stock et livraison au ton calme et précis — **jamais de volume global de stock** (doctrine).

⚠️ Ces 4 mouvements sont la **mise en page** de la fiche. Ils ne remplacent pas la **règle des 2 blocs** de `long_desc` (design/usage, puis signature BCDG) : celle-ci régit le *contenu rédigé*, saisi dans WooCommerce. Les deux coexistent.

## 8. Footer — structure arbitrée (O-1 et O-2, 2026-07-27)

Deux étages distincts. C'est la réconciliation entre l'exigence d'accessibilité (Livraison, Retours, Entreprises, Collectivités joignables directement) et le footer « ligne d'horizon » de la charte, qui impose 72-96 px sur une seule ligne et interdit le méga-menu.

### 8.1 Bande services

Au-dessus de la ligne d'horizon, discrète, en groupes **courts et identifiés**. Aucun groupe ne devient une liste exhaustive ni une répétition du header.

| Groupe | Liens |
|---|---|
| **Navigation** | Boutique · Arts de vivre · L'Atelier · Lifestyle & Koi |
| **Informations** | Livraison · Retours · Contact |
| **Professionnels** | Entreprises · Collectivités |

### 8.2 Ligne d'horizon (charte §11, strict)

Fond washi, trait fin, **72-96 px desktop, une seule ligne**, trois zones. ❌ Aucun bloc sombre, aucun logo géant, aucune liste de catégories, aucun formulaire.

| Zone gauche | Zone centrale | Zone droite |
|---|---|---|
| `© 2026 Koinobori House · Créations BCDG` | Mentions légales · CGV · Politique de confidentialité · Gestion des cookies | Retour en haut |

**Pas d'icônes sociales au lancement** (O-5) : la zone droite ne porte qu'une flèche ascendante discrète, avec libellé accessible. Instagram et Pinterest s'ajouteront quand les comptes existeront.

La zone centrale conserve les **4 liens légaux** du §11.1, conformément à la charte. « Contact » migre dans le groupe Informations de la bande services, où il est mieux placé.

La newsletter reste au **mouvement 10**, jamais dans le footer (§11.2).

### 8.3 Règles portées par cette structure

- **Livraison** est une page unique couvrant la France, l'international **et** la situation de la zone USA.
- **Livraison USA** n'est **ni dans la navigation principale ni dans le footer** tant que la zone USA n'est pas activée. Si la page existe ou doit être conservée techniquement, elle est **reliée depuis la page Livraison**. L'activation dépend des tests Colissimo USA (KH-015) et **ne bloque pas le lancement France**.
- **Retours** reste une page distincte, joignable directement.
- Le **bandeau confiance** peut pointer vers Livraison et Retours, mais **jamais en être le seul chemin d'accès**.
- Les liens **Entreprises** et **Collectivités** ne sont **pas comptés** parmi les liens de la ligne d'horizon. La page « Professionnels » du header reste une **page d'aiguillage** vers ces deux parcours. **Aucune fusion** des pages ni des formulaires (A-6).
- Mobile : la bande services se replie en colonnes empilées, la ligne d'horizon en 2-3 lignes. Zones tactiles suffisantes, lisible à 360 px.

> ⚠️ Une inférence à confirmer au build : le groupe **Navigation** de la bande services est le seul emplacement restant pour les 4 liens de nav corrigés, la zone centrale de la ligne d'horizon étant réservée aux légales par le §11.1. Si tu préfères un footer sans groupe Navigation du tout, il suffit de le retirer — le reste de la structure tient.

## 9. Points ouverts créés par la v2.0

| # | Point | Nature |
|---|---|---|
| ~~O-1~~ | ~~Accès aux pages Livraison, Retours, Livraison USA~~ | ✅ **RÉSOLU 2026-07-27** — cf §8.1 et §8.3 |
| ~~O-2~~ | ~~Les 2 liens footer directs Entreprises / Collectivités d'A-6~~ | ✅ **RÉSOLU 2026-07-27** — groupe Professionnels dédié dans la bande services, hors comptage de la ligne d'horizon. Cf §8.1 et §8.3 |
> **Tous tranchés le 2026-07-27 par défauts**, sur GO d'Alain : « quitte à ce que je modifie plus tard certains points, il faut avancer ». Ce sont des **défauts documentés et révisables**, pas des décisions verrouillées. Aucun n'exige un nouveau tour d'arbitrage pour avancer.

| ~~O-3~~ | ~~4 familles typographiques contre 2 en v1~~ | ✅ **Self-host** les 4 familles, graisses strictement nécessaires, `font-display: swap`. **Mesure à G4.** Porte de sortie si la perf coince : Noto Serif JP repasse en pile système, son usage étant « ponctuel et signifiant » (§5.2) |
| ~~O-4~~ | ~~`atelier_alain_catherine.png`, illustration destinée à la page L'Atelier~~ | ✅ **NE PAS PUBLIER.** La page L'Atelier ouvre **sans portrait**. Publier une illustration générée en la présentant comme Alain et Catherine, sans l'accord de Catherine, est le seul risque de ce lot qui engage une personne réelle. Révisable uniquement si Catherine valide explicitement |
| ~~O-5~~ | ~~Instagram · Pinterest au footer §11.1~~ | ✅ **Pas de liens sociaux au lancement.** Zone droite de la ligne d'horizon = **retour en haut seul**. Un lien mort coûte plus qu'une icône absente. À rouvrir quand les comptes existent |
| ~~O-6~~ | ~~Fallback hero `#f3ebdd` vs `--kh-washi` `#F8F4EE`~~ | ✅ Retenir `--kh-washi`. `README_HERO.md` ajoute par ailleurs `min-height: min(900px, 100svh)` sur `.koinobori-hero`, à conserver |
| ~~O-7~~ | ~~Slug EN de « L'Atelier »~~ | ✅ **`/en/the-house/`**, libellé « The House ». Évite `workshop`, qui se lit *atelier de production* et heurte la doctrine. Cohérent avec « Koinobori House » |
| O-8 | **Renommage de la page « Univers » en « L'Atelier »** : les docs v1.2 (`KH-000`, `KH-001-Image-Map`, plan d'intégration) référencent encore `/univers` et l'asset `apropos-hero.png` | 🔶 Sans gravité, ces docs sont superseded pour les couches visuelles. À rectifier au moment de créer la page, pas avant. `apropos-hero.png` devient le visuel de la page L'Atelier par défaut, O-4 écartant l'illustration v2.0 |
| ~~O-9~~ | ~~Place du récit Kaïro sur la homepage~~ | ✅ **Mouvement 4 « Créations BCDG »** : Kaïro *est* une création BCDG, le mouvement existe déjà, pas de 12ᵉ mouvement à inventer. Pas d'entrée Kaïro au header, la nav reste à 7 (critère §14) |
| ~~O-10~~ | ~~Libellés et slugs EN des 7 entrées de nav~~ | ✅ **Tranché** — cf §3.1 |

## 10. Reste à produire

1. ~~Spec mega-panel~~ — **retiré**, A-1 annulé.
2. **Wireframes des 5 pages Mondes** (hero + ambiance + kanji fond + produits, palette propre).
3. **Footer** : structure arbitrée en §8, **débloqué**. CSS de base fourni v2.0 §11.4, à adapter aux classes réelles du thème enfant.
4. **Modèles éditoriaux Lifestyle & Koi** : archive magazine + gabarit d'article, distincts du blog Kadence par défaut (v2.0 §8).
5. **Page L'Atelier** : composition v2.0 §7.3, texte d'ouverture à valider. **Sans portrait** (O-4) : le grand visuel d'ouverture est `apropos-hero.png` du pack v1, pas `atelier_alain_catherine.png`. Aucune donnée biographique sur Catherine tant qu'elle ne l'a pas fournie ou validée.
6. Migration des tokens KH-000 déployés vers la palette et les typos v2.0.

---

*Arbitrages Alain 2026-07-24, révisés 2026-07-27 par les arbitrages C1-C6 (charte v2.0). Complète [docs/ux-backlog.md](ux-backlog.md) et [docs/charte-graphique/README.md](charte-graphique/README.md). Ne rouvre ni le header V2 ni la charte.*

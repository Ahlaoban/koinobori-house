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
| ~~A-4~~ | ~~Kaïro flagship avec slot menu propre~~ | Plus de slot menu Kaïro. Le récit « The Nameless Ship » survit **hors nav** : section homepage et page Monde Kaïro |

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
| A-8 | Ordre homepage | Remplacé par les **11 mouvements** v2.0 §4 — cf §4 |
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

**Le bandeau confiance S8 disparaît** des mouvements v2.0. Son contenu (livraison France offerte ≥ 55 €, retours, contact USA) doit être replacé — cf §8. Wording : ❌ Stripe / PayPal / commission / droits de douane offerts (KH-707).

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

## 8. Points ouverts créés par la v2.0

| # | Point | Nature |
|---|---|---|
| O-1 | **Accès aux pages Livraison, Retours, Livraison USA.** Absentes de la nav v2.0 (7 entrées) comme du footer §11.1 (4 liens : Mentions légales · CGV · Confidentialité · Contact), alors que §15 interdit le footer méga-menu et que le bandeau confiance S8 est supprimé des 11 mouvements | **Arbitrage Alain requis** — ces pages sont obligatoires au MVP |
| O-2 | **Les 2 liens footer directs Entreprises / Collectivités d'A-6** ne tiennent pas dans un footer à 4 liens centraux | **Arbitrage Alain requis** — A-6 est confirmé, le footer v2.0 est verrouillé, il faut un troisième chemin |
| O-3 | **4 familles typographiques** (Cormorant Garamond, Lora, DM Sans, Noto Serif JP) contre 2 en v1 | Risque perf sur G4 (juge de paix). Mitigation : self-host, `font-display: swap`, graisses strictement nécessaires |
| O-4 | **`atelier_alain_catherine.png`** : illustration du pack destinée à la page L'Atelier | À valider par Alain **et** Catherine avant publication. Ne pas présenter une illustration générée comme un portrait de personnes réelles |
| O-5 | **Instagram · Pinterest** au footer §11.1 | Ne pas poser de lien mort : à confirmer que les comptes existent |
| O-6 | **Fallback hero** : `README_assets` §5 propose `#f3ebdd`, la palette v2.0 `--kh-washi` vaut `#F8F4EE` | Résolu sans arbitrage : retenir `--kh-washi` |
| O-7 | **Slug EN de « L'Atelier »**. `workshop` se lit *atelier de production* et heurte frontalement la doctrine. Pistes sans risque : `/en/the-house/`, `/en/our-story/`, `/en/about/` | **Arbitrage Alain requis** avant création de la page. Aucune page éditoriale n'existe encore sur staging, donc aucune migration de slug à craindre |
| O-8 | **Renommage de la page « Univers » en « L'Atelier »** : les docs v1.2 (`KH-000`, `KH-001-Image-Map`, plan d'intégration) référencent encore `/univers` et l'asset `apropos-hero.png` | Sans gravité, ces docs sont superseded pour les couches visuelles. À rectifier au moment de créer la page, pas avant |

## 9. Reste à produire

1. ~~Spec mega-panel~~ — **retiré**, A-1 annulé.
2. **Wireframes des 5 pages Mondes** (hero + ambiance + kanji fond + produits, palette propre).
3. **Footer ligne d'horizon** : CSS de base fourni v2.0 §11.4, à adapter aux classes réelles du thème enfant. Bloqué par O-1 et O-2.
4. **Modèles éditoriaux Lifestyle & Koi** : archive magazine + gabarit d'article, distincts du blog Kadence par défaut (v2.0 §8).
5. **Page L'Atelier** : composition v2.0 §7.3, texte d'ouverture à valider.
6. Migration des tokens KH-000 déployés vers la palette et les typos v2.0.

---

*Arbitrages Alain 2026-07-24, révisés 2026-07-27 par les arbitrages C1-C6 (charte v2.0). Complète [docs/ux-backlog.md](ux-backlog.md) et [docs/charte-graphique/README.md](charte-graphique/README.md). Ne rouvre ni le header V2 ni la charte.*

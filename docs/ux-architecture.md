# Architecture UX — Koinobori House (arbitrée 2026-07-24)

> **Statut : ARBITRÉE par Alain le 2026-07-24 (11/11 décisions).** Référence structurelle pour le portage header (Lot 7), les patterns homepage/pages (KH-000 couche 3) et le brief Manus.
> Référence graphique : **KH-000 Design System v1.2 « Ma (間) »** ([docs/charte-graphique/](charte-graphique/), plan d'intégration [docs/lot6/KH-000-integration-plan.md](lot6/KH-000-integration-plan.md)).
> Header Shoji V2 **verrouillé** (UX-Header-001) — jamais rouvert par ce document.
> Gouvernance : UX = arbitrage exclusif Alain ; ce doc fige les décisions rendues, rien d'autre.
> Planning : **lancement 15 août 2026** (décision Alain 2026-07-24).

## 1. Arbitrages rendus (2026-07-24)

| # | Décision | Verdict |
|---|----------|---------|
| A-1 | Structure menu desktop | **Menu B + mega-panel** : `Boutique · Collections ▾ · Kaïro · Univers · Journal` ; les 5 Mondes se déploient dans un panneau sous le header |
| A-2 | Label du regroupement | **« Collections »** partout (nav, homepage, footer, SEO) |
| A-3 | Rôle de Territoires | **Monde-collection seul** (régions + drapeaux) ; le B2G reste sur Collectivités |
| A-4 | Statut de Kaïro | **Flagship dédié** : slot menu propre + section homepage (récit « The Nameless Ship ») |
| A-5 | Journal/Lifestyle MVP | **Inclus** : slot menu + section homepage conditionnelle |
| A-6 | Accès B2B/B2G | **1 entrée header « Professionnels »** (page aiguillage B2B/B2G) **+ 2 liens footer directs** Entreprises / Collectivités |
| A-7 | CTA hero | **Vers les Collections** (pas la Boutique) |
| A-8 | Ordre homepage | **Immersion d'abord** (voir §4) |
| A-9 | Storytelling BCDG home | **Teaser court**, récit complet sur la page Univers |
| A-10 | Richesse visuelle | **Sobre + accents** : washi dominant, ambiance limitée au hero + heros des pages Mondes |
| A-11 | Faisabilité sous-menu shoji | **Levée** : mega-panel *sibling* sous le header (voir §3) ; fallback = menu aplati |
| Mobile | Navigation mobile | **Overlay accordéon** (voir §5) |

## 2. Architecture générale

Quatre familles de contenu, 2-3 niveaux max (buildable Kadence).

**A — Commerce (B2C transactionnel)**
- `Boutique` : grille tous produits, filtres (Collection, Taille, Prix).
- **5 pages Collections** (= 5 Mondes, palette propre par Monde KH-000 §6) : Mer · Kaïro · Hanami · Motifs · Territoires.
- Fiche produit (2 blocs doctrine) → Panier → Checkout WooCommerce bilingue.

**B — Marque / éditorial (différenciation maison de marque)**
- `Univers` : histoire Koinobori House + signature BCDG + manifeste (récit complet).
- `Kaïro` : flagship narratif (slot dédié).
- `Journal` : lifestyle (3 articles FR MVP, EN condensé).

**C — Captation qualifiée (B2B/B2G, MVP = landing + Fluent Forms)**
- `Professionnels` : page d'aiguillage → `/fr/entreprises/` + `/fr/collectivites/` (et slugs EN).

**D — Utilitaire (header V2 + footer)**
- FR/EN (texte, sans drapeau) · Mon compte · Panier · Aide/Contact · légales.

## 3. Navigation desktop

**Rubriques du header** (remplace la rangée placeholder du prototype V2 ; mécanique battants/logo/timings inchangée) :

`Boutique · Collections ▾ · Kaïro · Univers · Journal · Professionnels · Mon compte (corail) · Panier · FR/EN`

**Mega-panel « Collections »** :
- Le sous-menu ne peut PAS vivre dans la bande header : `overflow:hidden` (indispensable au clipping des battants ±405 px) + hauteur `clamp(130px,16vw,230px)` le rendent intenable (constat technique A-11, source `header-shoji-koino-v1.src.html`).
- Solution retenue : **panneau pleine largeur *sibling*** dans le DOM, ouvert sous le header au survol/clic de « Collections ». 5 cartes Mondes (nom + visuel + teinte du Monde). Le header validé reste intact au pixel près.
- **Vigilance portage Lot 7** : coordonner le `mouseleave` du header avec le panneau (délai de grâce / wrapper d'événements) pour que la descente de souris vers le panneau ne referme pas les battants.
- **Fallback zéro-risque** si le panneau déçoit au portage : menu aplati `Boutique · Mer · Kaïro · Hanami · Motifs · Territoires` en rangée d'icônes, éditorial au footer.

## 4. Homepage — structure arbitrée (ordre immersion)

| § | Section | Contenu | Notes doctrine |
|---|---------|---------|----------------|
| S0 | Header Shoji V2 | verrouillé, partout | — |
| S1 | Hero immersif | image ciel/koinobori, titre Cormorant italic, **1 CTA vermillon → Collections** | zoom loop 8s optionnel (KH-000 §8) |
| S2 | Les 5 Mondes | grille asymétrique `1.3fr 1fr 1fr 1fr 1fr`, cartes → pages Collections | titre éditorial : « Collections » (A-2) |
| S3 | Kaïro flagship | bandeau récit « The Nameless Ship », CTA vers le Monde Kaïro | pas de dérive merch (UX-004) |
| S4 | Sélection produits | 3-4 product-cards (blanc cassé, jamais blanc pur), prix Cormorant, CTA vermillon | wording « Sélection », pas « bestsellers » (doctrine stock) |
| S5 | Manifeste + teaser BCDG | texte court Lora, mention signature BCDG, lien Univers | ❌ « cousu à la main » / origine / atelier (G) ; ne pas confondre logo maison ≠ signature BCDG |
| S6 | Newsletter | 1 champ + CTA (Brevo) | sobre |
| S7 | Journal | 2-3 cartes articles | conditionnel contenu prêt |
| S8 | Bandeau confiance | livraison France offerte ≥ 55 €, retours, contact USA | ❌ wording Stripe/PayPal/commission/douane (KH-707) |
| S9 | Footer | colonnes : Collections · Aide · Maison (Univers, Journal, légales) · **Entreprises + Collectivités** · FR/EN ; logo noir + cartouche 鯉のぼり filigrane | structure colonnes = proposition, à confirmer au build |

Respiration « Ma » : padding vertical sections ≥ 80-120 px.

## 5. Mobile

- Mécanique shoji = desktop. Mobile : **hamburger or → overlay washi plein écran** (KH-000 §9), accordéon :

```
Boutique
Collections        ▾  (accordéon → Mer / Kaïro / Hanami / Motifs / Territoires)
Kaïro
Univers
Journal
────────────
Mon compte · Panier
FR / EN
Professionnels (bas de liste)
```

- **Panier + Boutique accessibles hors menu** (icônes persistantes).
- FR/EN visible dès l'ouverture de l'overlay.

## 6. Parcours utilisateurs

**Principal — découverte par univers** :
`Home (hero) → Collections (S2 ou mega-panel) → page Monde (palette propre) → fiche produit (2 blocs) → panier → checkout`

**Alternatif — transactionnel direct** :
`Home → Boutique → filtres (Collection/Taille/Prix) → fiche → panier → checkout`
La Boutique reste à 1 clic du header (filet visiteur pressé / habitué Etsy).

**Captation B2B/B2G (hors tunnel)** :
`Header « Professionnels » ou footer → landing Entreprises OU Collectivités → Fluent Form → lead offline`

## 7. Reste à produire (post-arbitrage)

1. **Spec mega-panel** (comportement hover/clic/fermeture, clavier/accessibilité, contenu 5 cartes) — prérequis portage header Lot 7.
2. **Wireframes des 5 pages Mondes** (hero + ambiance + kanji fond + produits, palette propre).
3. **Footer** : colonnes définitives à confirmer au build (structure §4/S9 = proposition).
4. Exécution KH-000 couches 1-4 sur staging selon [le plan d'intégration](lot6/KH-000-integration-plan.md).

---

*Arbitrages Alain 2026-07-24. Complète [docs/ux-backlog.md](ux-backlog.md) (statuts mis à jour) et le plan KH-000. Ne rouvre ni le header V2 ni la charte.*

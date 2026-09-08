# Décomposition Lots 2-8 — proposition révisée (Koinobori House MVP)

- **Date** : 2026-06-15 (rév. 2 — split AD/thème, avocat non bloquant, soft launch découplé)
- **Statut** : 🟡 **PROPOSITION — à valider (Alain)**. Frontières de lots arbitrables. UX réservée (cf `docs/ux-backlog.md` + gouvernance UX 2026-06-15).
- **Amont validé** : Lot 0 (KH-001..016) + Lot 1 (KH-107, KH-104b-pré, KH-106, KH-109, **KH-104b-full = GO**). Verrou « zéro conflit Polylang for WC » levé.
- **Chantier parallèle Manus** : direction artistique / UX / identité visuelle → **gate des Lots 6-7 uniquement**.
- **Gouvernance** : Claude = archi/contenu/structure/WC/SEO/implémentation · Manus = exploration créative/AD/UX · Alain = arbitrage final UX & marque.
- **Règle de construction** : ossature d'abord, rendu final différé. Blocs natifs / templates réversibles / pas de CSS lourd / 0 image d'ambiance baked-in tant que direction artistique non validée.

> ⚠️ Numérotation KH-2xx..8xx = **indicative** (ancres CLAUDE.md : KH-208-213, 307, 505, 707, sécurité Lot 4+7).

---

## Principe directeur de la révision

**Isoler le risque Manus.** La couche AD/UX (décision créative, gated Manus) est **séparée** de la couche technique. Résultat :
- **Lots 2-5** (contenu + commerce + forms + technique transverse) = **0 dépendance Manus** → avancent à plein régime, **soft-launch-ready**.
- **Lots 6-7** (AD puis implémentation thème) = la seule chaîne gated Manus.
- **Soft launch découplé** du thème final (cf §Soft launch).

---

## Vue d'ensemble

| Lot | Intitulé | Complexité | Gate externe |
|---|---|---|---|
| **2** | Catalogue & fiches produits | XL | images produits |
| **3** | Pages éditoriales, confiance, B2B/B2G, légales | L | rédaction |
| **4** | Paiement, checkout, taxes, livraison, retours + sécurité | L | Colissimo USA |
| **5** | Forms, emails, SMTP, RGPD, analytics **+ technique transverse** (perf, médias, schema, wishlist) | L | — |
| **6** | **Direction artistique / UX** (décision, 0 code) | XL | **Manus** |
| **7** | **Implémentation thème & intégration** (applique Lot 6) | L | **Lot 6** |
| **8** | **Pré-launch, soft launch S7, hard launch S9, stabilisation** | L | — (soft) / Colissimo USA (USA) |

**Aucun gate « avocat ».** Voir §Juridique.

---

## Lot 2 — Catalogue & fiches produits *(KH-2xx)*
**Périmètre** : taxonomie **5 collections** bilingues + slugs · attribut global Taille (50/75/100) · modèle produit (variable+variations, SKU, prix EUR, **stock par produit**) · **~17 produits MVP bilingues FR+EN** (nom `- by BCDG`, short_desc **sans atelier**, long_desc **2 blocs**, images, prix, stock) · traductions Polylang + sync stock/variations (KH-010 ✓) · **fiche = structure NEUTRE** (visuel réservé).
**Dépendances** : Lot 1 (✓).
**Risques** : wording doctrine × ~17 · images produits pas prêtes · volume.
**Critères de sortie** : ~17 produits publiés bilingues · catalogue indexable · **audit grep doctrine PASS (KH-208-213)** (0 Chine/origine, 0 stock global, **aucune mention atelier** short_desc ET long_desc FR+EN).
**Blocages** : images produits · textes FR/EN.

## Lot 3 — Pages éditoriales, confiance, B2B/B2G, légales *(KH-3xx)*
**Périmètre** : homepage (structure neutre) · à-propos/histoire · storytelling BCDG (placement réservé) · Lifestyle (3 articles EN allégé) · confiance (livraison/retours/FAQ/contact/sur-mesure) · shipping USA (« nous contacter ») · **B2B** (`/fr/entreprises/`+`/en/business/`) · **B2G** (`/fr/collectivites/`+`/en/institutions/`) wording validé · légales (mentions/CGV/confidentialité/cookies, corpus KH-017) · SEO metadata.
**Dépendances** : Lot 2 (collections) · Lot 0 légal (KH-017).
**Risques** : doctrine (Chine jamais citée, aucune exception depuis le 2026-09-08) · cohérence EN allégé.
**Critères de sortie** : pages MVP publiées bilingues · audit doctrine (KH-307) · légales cohérentes · emplacements forms en placeholder.
**Blocages** : rédaction.

## Lot 4 — Paiement, checkout, taxes, livraison, retours + sécurité *(KH-4xx)*
**Périmètre** : WC Stripe + PayPal (KH-003/004 ✓) · checkout bilingue · **franchise TVA 293 B** · **EUR only** · zones manuelles (France gratuité ≥55€ · UE Colissimo Zone A + MR · **USA « nous contacter » défaut** · DROM-COM frais réels · formule internationale) · retours/rétractation (KH-017) · **sécurité hardening** (Wordfence, 2FA — security-and-hardening Lot 4).
**Dépendances** : Lot 2 (produits) · légal (CGV/retours).
**Risques** : tarifs manuels complexes · tests Colissimo USA différés (KH-015) · **checkout OFF possible au soft launch**.
**Critères de sortie** : commande test bout-en-bout · paiements sandbox→live · zones correctes · pas multi-devise · **audit wording shipping** (jamais Stripe/PayPal/commission/droits offerts).
**Blocages** : tests Colissimo USA · décision activation checkout.

## Lot 5 — Forms, emails, SMTP, RGPD, analytics + technique transverse *(KH-5xx)*
**Périmètre** : Fluent Forms B2B+B2G (champs CLAUDE.md) · contact · sur-mesure · emails WC bilingues (FluentSMTP+Brevo, KH-005 ✓) · Complianz cookies RGPD · Plausible analytics · **+ couche technique transverse non-AD** : LiteSpeed (exclure racine) · images ShortPixel/Imagify · **schema.org Organization/Product** (SEOPress) · wishlist YITH · ACF si besoin.
**Dépendances** : Lot 3 (pages hôtes) · Lot 4 (emails WC) · Lot 2 (schema Product).
**Risques** : délivrabilité (DNS Brevo ✓) · RGPD · bilingue emails.
**Critères de sortie** : forms testés · emails bilingues reçus · cookies conformes · analytics OK · perf de base OK · schema valide · **audit KH-505**.
**Blocages** : —
> **Pourquoi ici** : ces éléments sont **non-gated Manus** → on les sort de la chaîne AD pour ne pas les bloquer.

## Lot 6 — Direction artistique / UX *(décision, 0 code)* — **GATE MANUS**
**Périmètre** : intégration des livrables Manus (5 directions → reco → design system → wireframes) · **arbitrage Alain** sur la liste réservée (header, nav, sélecteur langue, homepage, hiérarchie collections, Kaïro, storytelling BCDG, footer, pages produits, richesse visuelle, ambiances, parcours) · figer les **design tokens** (hex, typo, spacing) **après** validation.
**Sortie** = un **dossier de direction validé** (tokens + wireframes + specs), prêt à implémenter. **Aucune modif de code/thème ici.**
**Dépendances** : **Manus (externe)** + arbitrage Alain. Tourne **∥ Lots 2-5**.
**Risques** : timing Manus · buildabilité (propositions hors WP/WC/Kadence) → filtrées par le bridge buildabilité du prompt Manus.
**Critères de sortie** : direction artistique **arbitrée par Alain**, tokens + wireframes figés, faisabilité WP/WC/Kadence confirmée.
**Blocages** : Manus non livré / arbitrage en attente.

## Lot 7 — Implémentation thème & intégration *(KH-7xx)* — **GATE LOT 6**
**Périmètre** : traduction du dossier Lot 6 en **Kadence child theme** : header · nav · homepage · footer · collections · sélecteur langue · structure visuelle pages produits · tokens → variables thème · responsive · accessibilité de base.
**Dépendances** : **Lot 6** (direction validée) + contenu Lots 2-5 (styler des pages réelles).
**Risques** : rework si direction change post-implémentation · régressions visuelles.
**Critères de sortie** : thème reflète la direction arbitrée · responsive · accessibilité de base · non-régression i18n/SEO (KH-107/109 intacts).
**Blocages** : Lot 6 non figé.

## Lot 8 — Pré-launch, soft launch S7, hard launch S9, stabilisation *(KH-8xx)*
**Périmètre** :
- **Pré-launch** : audit final **KH-707** (grep Chine/origine, stock global, atelier en short_desc, Stripe/PayPal/commission/droits offerts) · security review · backups (UpdraftPlus+Drive) · deploy staging→prod · retrait noindex · sitemaps live · GSC/Bing.
- **Soft launch S7 (~15 juil)** : catalogue **indexable**, **checkout OFF** si besoin. **Ne dépend PAS de Lot 6/7 finis** (thème neutre présentable suffit — cf §Soft launch).
- **Hard launch S9 (~29 juil)** : vente publique, **checkout ON** · paiements live · **USA actif** si Colissimo OK (sinon « nous contacter »).
- **Stabilisation** : observation post-launch (abandons, signaux révision livraison) · hotfixes.
**Dépendances** : soft = Lots 2-3 (+ perf Lot 5 + thème présentable) · hard = + Lot 4 + Lot 5 + **docs légaux publiés** (avocat **non bloquant**) + idéalement Lot 7.
**Risques** : parité staging/prod · oubli noindex · juridique/avocat (non bloquant) · charge.
**Critères de sortie** : prod live indexable · audit KH-707 PASS · backups OK · (hard) vente active + paiements live + légales publiées.
**Blocages** : parité staging/prod · (USA) tests Colissimo.

---

## Soft launch — découplage explicite (POINT 3)

**Le soft launch S7 n'exige PAS que la direction artistique finale soit terminée.** Au soft launch, le site est :
- **structurellement complet** (catalogue + pages + structure),
- **indexable** (SEO/sitemaps/hreflang OK),
- **fonctionnel** (navigation, recherche ; checkout off acceptable),

…tout en restant **susceptible d'évoluer visuellement** suite aux travaux Manus (Lot 6) et à l'implémentation thème (Lot 7). Le thème au soft launch peut être **neutre/présentable** (défauts Kadence chartés a minima). La direction finale **peut atterrir entre soft et hard launch, ou après** — c'est une **montée en gamme visuelle, pas un bloqueur de soft launch**.

## Juridique — avocat non bloquant (POINT 2)

- Docs légaux **préparés + cohérents** en KH-017 (identification vendeur, SIREN/RCS, CM2C, retours/rétractation, délais livraison, 9 docs FR+EN).
- **Gate hard launch = docs légaux publiés + cohérents + RGPD/cookies fonctionnels** (contrôlable en interne).
- **Avocat = contrôle complémentaire recommandé, NON bloquant** (amélioration ; idéalement fenêtre soft→hard ; valeur surtout contrats B2B/B2G).
- ⚠️ Exposition B2C UE+US réelle (CGV, rétractation, RGPD, douanes US) ; socle couvert par KH-017 ; relecture = réduction de risque, pas prérequis.

---

## Dépendances & chemin critique (révisé)

```
Lot 1 (✓)
  └─> Lot 2 ──┬─> Lot 3 ─┐
              ├─> Lot 4 ─┤
              └──────────┴─> Lot 5 (forms/emails + perf/médias/schema)
                                  │
   [CHAÎNE TECHNIQUE = 0 dépendance Manus] ──> SOFT LAUNCH S7 (checkout off OK)
                                  │
   Manus (externe) ──> Lot 6 (AD/UX, ∥ 2-5) ──> Lot 7 (thème) ──┐
                                                                 ├─> HARD LAUNCH S9
   docs légaux (KH-017, ✓) + Lot 4 (checkout) ───────────────────┘   (avocat non bloquant)
```

**Jalons :**
- **Soft launch S7** : **Lot 2 → 3 → (perf Lot 5) → Lot 8(soft)**. Indépendant de Manus / Lot 6 / Lot 7.
- **Hard launch S9** : + **Lot 4 + Lot 5 + docs légaux + idéalement Lot 7**.
- **Chaîne AD (6→7)** : ∥, livre le rendu final ; idéalement avant hard launch, **jamais bloqueur du soft launch**.

**Gates externes** : **Manus** (Lots 6-7 seulement) · **tests Colissimo USA** (activation USA). **Plus de gate avocat.**

**Risque n°1 (timing Manus)** : désormais **confiné aux Lots 6-7**. Lots 2-5 + soft launch avancent sans lui.

---

## Zones gardées flexibles (attente Manus)

| Zone | Construit maintenant (neutre/réversible) | Figé après Manus + arbitrage Alain |
|---|---|---|
| Header | logo + cartouche, structure minimale | layout, sticky, contenu |
| Navigation | items techniques en menu brut | organisation, libellés, méga-menu |
| Sélecteur langue | fonctionnel, position défaut | placement + visibilité définitifs |
| Homepage | sections en blocs natifs empilés | narration, hero, ordre, ambiance |
| Footer | liens essentiels + légales | structure, colonnes, éditorial |
| Collections | grille Kadence par défaut | gallery/editorial, hiérarchie |
| Pages produits | 2 blocs doctrine + galerie standard | mise en page, immersion, typo |
| Kaïro | collection + produits | dispositif narratif « The Nameless Ship » |
| Storytelling BCDG | texte présent (bas/neutre) | emplacement, poids, dispositif |
| Images d'ambiance | **aucune** | tout |
| Palette / typo | **défauts neutres** | tokens issus de Manus |
| Parcours global | parcours WC standard | réagencements Manus (si buildables) |

Tout = réversible (blocs natifs, pas de CSS lourd, pas d'images baked-in), loggé dans `docs/ux-backlog.md`, jamais présenté comme décidé.

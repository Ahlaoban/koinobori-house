# CLAUDE.md — Koinobori House

Project context for Claude Code sessions on Koinobori House MVP.

## Projet

E-commerce bilingue FR+EN pour vente koinobori originaux signés BCDG. B2C + B2B/B2G simples. Hébergé o2switch. Lancement cible fin juillet 2026.

- **Démarrage** : 2026-05-27 (J1 Lot 0 — mercredi)
- **Soft launch S7** : ~15 juillet 2026 (catalogue indexable, checkout off si juridique pas prêt)
- **Hard launch S9** : ~29 juillet 2026 (vente publique après avocat CGV)
- **Durée** : 9 semaines × 6 jours/semaine × ~6h/jour = ~340h capacité
- **Repo git** : à créer (suggestion `Ahlaoban/koinobori-house`)
- **Domaine principal** : `koinoborihouse.com` (canonique)
- **Cible commerciale 12 mois** : 3000-5000 koi B2C + 5 projets B2B + 5 projets B2G

## Cadrage stratégique

- **Priorité absolue Koinobori jusqu'à J+63**. Bootstrap solo Alain.
- **MaïJinn** peut glisser d'1 mois si nécessaire. Reprise focus principal post-29 juillet.
- **Pas de scope creep**. Aucune fonctionnalité phase 2 dans MVP sans validation explicite.
- **Pas de multi-devise**. EUR only au MVP.
- **Source de vérité ventes** : WooCommerce Orders (pas de webhook Stripe custom).

## Stack technique verrouillée

| Couche | Choix |
|--------|-------|
| CMS | WordPress 6.6+ |
| E-commerce | WooCommerce dernière stable |
| Thème | Kadence Free + thème enfant |
| Cache | LiteSpeed Cache (natif o2switch, exclure racine `/`) |
| Multilingue | **Polylang Free + Polylang for WooCommerce (payant)** |
| SEO | **SEOPress Free** (sitemaps i18n par langue + hreflang + schemas). Remplace RankMath — incompatible Polylang, décision data-driven 2026-06-13 (KH-109). ⚠️ gestionnaire de redirections = SEOPress Pro/.htaccess si besoin (non requis MVP). |
| SMTP | FluentSMTP + Brevo |
| Sécurité | Wordfence Free + 2FA admin |
| Sauvegardes | UpdraftPlus + Google Drive (+ Cpanel o2switch natif) |
| Cookies/RGPD | Complianz Free |
| Images | ShortPixel ou Imagify plan payant |
| Custom fields | ACF Free |
| Formulaires | Fluent Forms Free |
| Wishlist | YITH WooCommerce Wishlist Free |
| Paiement | WooCommerce Stripe + PayPal (officiels) |
| Analytics | Plausible Analytics (sans cookies, RGPD-friendly) |
| Page builder | Gutenberg natif + Kadence Blocks |

**Ne pas utiliser** : Next.js, Supabase, Vercel, Elementor, Jetpack complet, WP Rocket (conflit LiteSpeed), WPML.

## Architecture i18n / SEO bilingue

- `koinoborihouse.com/fr/` — version française
- `koinoborihouse.com/en/` — version anglaise
- `koinoborihouse.com/` — 302 temp selon Accept-Language + cookie `koino_lang_pref` 90j
- **Jamais 301 sur racine `/`**
- **Jamais redirection `/fr/` ↔ `/en/`** : restent accessibles directement, indexables
- Polylang setting "Hide URL language for default" = **OFF**
- Hreflang : `fr-FR` + `en` + `x-default`
- Canonical par langue, jamais cross-lang
- Sitemaps SEOPress séparés `/fr/sitemap.xml` + `/en/sitemap.xml` (SEOPress génère des sitemaps par langue avec Polylang)
- LiteSpeed Cache exclut racine `/`
- Sélecteur langue header + footer
- Choix manuel utilisateur prime sur Accept-Language

## Charte graphique (Maison du vent / Atelier BCDG)

**Palette hex** :
- Ivoire chaud : `#FDF8F0`
- Noir encre : `#1A1A1A`
- Indigo profond : `#1E3A5F`
- Corail principal : `#E05A5A`
- Rouge sourd : `#B84C4C`
- Or doux : `#C6A15B`
- Gris pierre : `#E5DED2`

**Typo** : Fraunces (titres 400-600) + Inter (body 400-500). Mobile min 16px.

**Boutons** : radius 8px, padding vertical 12px. Achat = corail fond ivoire. Secondaire = indigo bordure. **Jamais or pour boutons/panier/liens/fonds massifs.**

## Identité marque

- **Logo Koinobori House** = identité marque maison / canal e-commerce. **Distinct de la signature BCDG.**
- **Signature BCDG** = signature créative apposée *sur les produits* (`- by BCDG` dans le nom modèle, trait d'union simple + mention long_desc bloc 2 fiches produits). Ne remplace **jamais** le logo Koinobori House comme identité du site.
- **Fichier logo principal** : `brand/koinoborihouse/logo-color.png` — PNG 1172×213 px, canal alpha (transparence OK), calligraphie noire « Koinobori House » + tampon rouge 鯉のぼり (écriture japonaise : kanji 鯉 « carpe » + hiragana のぼり, mot japonais courant, aucun risque doctrine Chine).
- **Favicon** : `brand/koinoborihouse/favicon-200.png` — PNG 200×200 px transparent, motif tampon rouge 鯉のぼり extrait du logo principal. Sera décliné en 16/32/48/180/192 px par WP Lot 1.
- Usage cible : header, footer, og:image, Schema.org Organization, emails Brevo, mentions légales.
- Compléments non requis MVP : SVG vectoriel (logo IA-généré, pas de fichier source vectoriel, vectorisation auto déconseillée pour la calligraphie — décision Alain 2026-06-10), variantes monochromes (post-launch si besoin).
- Ticket Lot 0 associé : **KH-009 Identité marque** (logo principal couleur + favicon déposés 2026-06-10).

## Doctrine éditoriale impérative

- ❌ **JAMAIS** mentionner Chine publiquement **dans tout contexte production / fournisseur / atelier / origine de fabrication / logistique interne / sourcing / formulation produit / fiches produits / transparence production**
- ✅ **Exception bornée — B2G culturel uniquement** : mention de la Chine autorisée **comme pays / culture** dans contexte B2G institutionnel (coopération culturelle, jumelages, projets éducatifs ou patrimoniaux). Périmètre strict : pages `/fr/collectivites/` + `/en/institutions/` et formulaire B2G associé. Voir §Doctrine B2C / B2B / B2G.
- ⚠️ **Garde-fou absolu** : la Chine peut être mentionnée uniquement comme pays/culture dans un contexte B2G culturel ou institutionnel. **Jamais** comme lieu de production, d'atelier, de fournisseur ou de fabrication, dans aucun contexte, B2C, B2B ou B2G.
- ❌ **JAMAIS** écrire "atelier chinois"
- ❌ **Aucune mention d'atelier / de production sur les fiches produits** (FR + EN) — décision Alain 2026-06-18. Si une référence à l'atelier devait apparaître ailleurs : jamais "atelier chinois", utiliser "atelier partenaire" / "atelier confidentiel".
- ❌ **JAMAIS** "fabriqué en France"
- ❌ **JAMAIS** prétendre à une fabrication interne
- ❌ **JAMAIS** référence volume global stock — stock par produit / variation uniquement
- ✅ **Wording fiche produit — règle 2 blocs (rév. 2026-06-18 ; remplace l'ancienne règle 3 blocs)** :
  - **Description courte** : commerciale, claire, désirable. **Aucune mention atelier / production.**
  - **Description longue** : structure **2 blocs** (paragraphes séparés Gutenberg) :
    1. Design / usage / contexte d'installation / atmosphère
    2. Signature BCDG + série (ex : *"Design original BCDG, signé et édité en petite série."* ou variante édition spéciale)
  - ❌ **Plus de bloc « transparence production »** : la phrase « atelier partenaire » / « partner workshop » est **supprimée des fiches** (FR + EN), décision Alain 2026-06-18. Aucune mention d'atelier/production sur les fiches.
  - ❌ **Jamais de tiret cadratin (—) dans les fiches produits** (FR + EN) : ponctuation classique (virgule, point, trait d'union simple). Signature dans le nom = `- by BCDG`. (Décision Alain 2026-06-15.)
- ✅ Audit grep pre-publish sur tous contenus (KH-208-213 + KH-505 + KH-307) + audit final KH-707 — vérifier **absence totale** de "atelier partenaire" / "partner workshop" (short_desc ET long_desc, FR/EN)
- ⚠️ **Doctrine éditoriale publique ne dispense PAS de conformité douanière** : documents douaniers exacts (origine, valeur, description, HS/HTS code)

## Doctrine stock

- **AUCUNE** référence au volume global de stock dans plan, tickets, contenus publics, imports, messages commerciaux, noms de tâches
- Travail uniquement par SKU + fiche produit + stock par produit + prix + images + descriptions FR/EN

## Catalogue MVP

- ~17 produits au lancement (composition réelle relevée 2026-06-18 : Mer 6, Motifs 3, Floral 1, Kaïro 4, Territoires 3), structure extensible
- **5 collections (taxonomie figée 2026-06-18)** FR/EN : Mer/Sea · Motifs/Patterns · Floral/Floral · Kaïro/Kaïro · Territoires/Lands. Codes SKU : MER · MOT · FLO · KAI · TER. « Territoires » regroupe régions + drapeaux (USA, Bretagne). **Pas de sous-catégories** ; thèmes transverses = tags ; « série limitée » = mention bloc 2 BCDG, pas une collection. (Remplace l'ancienne structure 6 collections Kaïro/La Mer/OKUSAI/Bretagne/Éditions spéciales/Hanami.)
- Tailles variables : attribut global `Taille` (50/75/100 cm extensible)
- Produit spécial validé : **Stars & Stripes Koinobori - by BCDG** (Territoires/Lands · USA · 100 cm · série limitée · SKU `KH-TER-001-100`)
- Nom interne fournisseur "US FLAG" jamais public

## Périmètre MVP — bilingue commercial complet

**Bilingue FR+EN dès J0** : homepage, boutique, catégories, fiches produits MVP, panier, checkout, emails WC essentiels, livraison, retours, contact, sur-mesure, professionnels, collectivités, header/footer, légales, SEO metadata, formulaires.

**EN allégé acceptable J0** : Lifestyle EN (3 articles condensés), pages confiance EN (versions courtes), B2B/B2G EN (textes courts mais formulaires complets).

**HORS MVP (phase 2)** : espace privé B2B/B2G, comptes organisations, prototypes confidentiels en ligne, devis privés en ligne, likes, vidéos massives, pages comparaison, multi-devise, Pinterest auto, CRM, bundles configurables, upload B2B/B2G, page revendeurs sans revendeur réel.

## Doctrine B2C / B2B / B2G

**Principe verrouillé MVP** : *B2C transactionnel, B2B/B2G captation qualifiée.*

### B2C — transactionnel WooCommerce

- Catalogue indexable + panier + paiement Stripe/PayPal natif WC
- Parcours autonome utilisateur du produit jusqu'au paiement
- Cible : particuliers, achat unitaire ou faible volume

### B2B / B2G — captation qualifiée MVP

- **Pages dédiées** + **formulaires simples** uniquement
- Aucune transaction directe via ces pages
- Pas d'espace privé, pas de compte organisationnel, pas de tarifs pros automatiques, pas de devis en ligne automatisé, pas de paiement différé, pas de CRM, pas de prototypes confidentiels en ligne
- Objectif MVP : collecter leads qualifiés, traitement commercial offline ensuite
- Tous éléments avancés (espace privé, devis automatisés, comptes orgas, prototypes) **strictement phase 2**

### Pages MVP B2B / B2G à prévoir

| Page | Slug FR | Slug EN | Cible |
|------|---------|---------|-------|
| Professionnels | `/fr/entreprises/` | `/en/business/` | B2B (entreprises, revendeurs, hôtellerie, événementiel privé) |
| Collectivités | `/fr/collectivites/` | `/en/institutions/` | B2G (collectivités, écoles, médiathèques, festivals, institutions culturelles) |

Slugs Polylang : créer pages en FR puis traduire via Polylang pour slug EN dédié.

### Wording B2G validé (corpus public)

Wordings autorisés au titre de l'**exception bornée B2G culturel** (cf §Doctrine éditoriale impérative — garde-fou absolu) :

> *"Koinobori House accompagne les collectivités, écoles, médiathèques, festivals et institutions culturelles dans leurs projets visuels autour du Japon et de l'Asie : jumelages, semaines culturelles, événements pédagogiques, décorations urbaines ou installations temporaires."*

> *"Collectivités engagées dans des jumelages ou coopérations culturelles avec le Japon, la Chine ou plus largement l'Asie."*

Statut : arbitrage Alain rendu 2026-05-28, **option (a) retenue avec exception bornée**. Mention Chine = pays / culture uniquement, contexte B2G institutionnel. Jamais comme lieu de production / atelier / fournisseur / fabrication.

### Champs formulaires (minimum MVP)

**Formulaire B2B** (page `/fr/entreprises/` + `/en/business/`) :
- Nom / prénom
- Email
- Organisation
- Pays
- Type de projet
- Quantité envisagée
- Date souhaitée
- Message

**Formulaire B2G** (page `/fr/collectivites/` + `/en/institutions/`) :
- Nom / prénom
- Email
- Collectivité / institution
- Pays / ville
- Type de projet (select multi ou enum) : jumelage, école, médiathèque, festival, événement culturel, décoration urbaine, autre
- Lien culturel avec le Japon, la Chine ou l'Asie, si pertinent *(exception bornée B2G culturel — cf §Doctrine éditoriale impérative garde-fou absolu)*
- Quantité envisagée
- Date souhaitée
- Besoin de devis (oui/non)
- Message

Implémentation Lot 1+ via Fluent Forms Free (stack verrouillé). Pas de plugin nouveau à ce stade. Pas de code page à ce stade.

## Tickets verrouillés

- **Lot 0** : 16 tickets (~31h) — décisions/prérequis hors code, KH-001 à KH-016
- **Lot 1** : 18 tickets (~44h) — fondation WordPress bilingue, KH-100 à KH-115 + KH-100b + KH-104b
- **Lots 2-8** : décomposition différée post-KH-104b validé (test bloquant Polylang for WC réel)

## Test bloquant KH-104b (S1)

Avant tout Lots 2-8 : tester 10 URLs FR+EN (`/fr/boutique`, `/en/shop`, `/fr/produit/test`, `/en/product/test`, `/fr/panier`, `/en/cart`, `/fr/commande`, `/en/checkout`, `/fr/mon-compte`, `/en/my-account`) + hreflang + canonical + sitemaps + cookie `koino_lang_pref` + racine `/` 302 Accept-Language + Googlebot UA. **Zéro conflit Polylang for WC = GO. Sinon = bloquer Lots 2-8.**

## USA / douanes critique

- ~25 % clients Etsy actuels = US. Marché clé.
- Marché US déjà actif via Etsy avec duties paid upfront
- Site doit éviter expérience inférieure (découverte droits à l'arrivée) **sans cannibaliser Etsy frontalement**
- KH-015 Lot 0 dédié : étudier Boxtal / Sendcloud / Easyship / Zonos pour DDP ou estimation upfront — **toutes options express/DDP différées phase 2**
- Pas de calculateur douanier maison MVP
- **Transporteur MVP B2C USA verrouillé : Colissimo international uniquement.** Pas de DHL Express, FedEx, UPS, Zonos au MVP B2C.
- **Recommandation MVP actuelle** : DAP + formulation publique prudente §1bis (aucun pourcentage ni code HTSUS public tant que non vérifié source officielle) + page dédiée `/fr/livraison-usa/` + `/en/shipping-to-usa/`.
- Cadrage complet : [docs/lot0/KH-012-KH-015-shipping-usa-seo.md](docs/lot0/KH-012-KH-015-shipping-usa-seo.md)

## Transporteurs MVP B2C verrouillés

**Règle générale France + UE** :
- **Principal** : **La Poste** (lettre suivie ≤ 3 cm BAL / Colissimo standard si > 3 cm ou hors gabarit)
- **Option** : **Mondial Relay** (France point relais + UE pays desservis BE/LU/ES/PT/NL/IT/PL)
- ❌ **Pas d'express international par défaut** pour France et UE (DHL/FedEx/UPS/Chronopost = phase 2 ces zones)
- ❌ **Pas de plugin API temps réel** au MVP

**Doctrine USA marchandise** (cf §1ter doc shipping actualisé 2026-05-29) :
- ✅ **Colissimo USA en ligne réouvert sous conditions** (source officielle La Poste) : affranchissement laposte.fr, valeur < 650 € par envoi, droits/taxes US payés upfront par expéditeur (DDP partiel pour client)
- ⚠️ **Territoires associés US** (Porto Rico, Guam, Samoa, Îles Vierges, Mariannes du Nord) restent **suspendus temporairement** si confirmé La Poste
- **Service prioritaire USA MVP** : **Colissimo USA en ligne** (tester en premier)
- **Fallback USA autorisés** uniquement si Colissimo trop cher / impossible / instable / inadapté : Chronopost / UPS / DHL / FedEx (un seul retenu cas par cas)
- ❌ Ne pas présenter UPS / DHL / FedEx / Chronopost comme **option première** USA si Colissimo fonctionne
- ❌ Lettre Suivie Internationale **non utilisable** marchandise commerciale USA (réservé documents)
- **Statut Alain 2026-05-29** : tests Colissimo USA **différés**, non bloquants Lot 0
- **Mode par défaut MVP** : **USA "nous contacter"** (zone USA désactivée WC + formulaire contact dédié) tant que tests pas réalisés
- **Bascule USA actif** = uniquement après réalisation 3 tests Colissimo USA en ligne par Alain (T1 1×Kaïro 35€ / T2 1×Stars & Stripes 49€ / T3 3×Kaïro 105€) stables/acceptables
- **Conséquence** : tests USA bloquent uniquement bascule "USA actif", pas avancement Lot 0 général. Page Livraison USA peut être draftée immédiatement avec wording "nous contacter"
- Modèle expédition USA = **DDP partiel** (droits payés upfront) → wording public adapté : *"frais d'expédition peuvent inclure les formalités, droits ou frais exigés avant l'entrée sur le territoire américain"* (cf §1bis-USA doc shipping)
- Zonos DDP plugin → phase 2 uniquement

**B2B/B2G** (commandes plusieurs centaines de koinobori) : **chantier séparé**, tests transporteurs dédiés palette/fret, pas de modèle B2C appliqué mécaniquement

Cadrage complet : [docs/lot0/KH-012-KH-015-shipping-usa-seo.md](docs/lot0/KH-012-KH-015-shipping-usa-seo.md) §1ter, §3.4 et §3.5

## Tarification livraison MVP

### Incitations actives (MVP)

- **France métropolitaine** : **livraison offerte à partir de 55 € d'achat**. Sous seuil = frais réels affichés. ❌ Pas de 70 €. ❌ DROM-COM hors gratuité.
- **USA** : contribution Koinobori House aux frais d'expédition internationale **à partir de 2 koinobori**. Règle `contribution_USA = min(grille_quantité, grille_montant)` :
  - Grille quantité : 1 koi = 0 € / 2 koi = 15 € / 3 koi = 20 € / ≥ 4 koi = 30 €
  - Grille montant produits hors livraison : < 70 € = 0 € / 70-99 € = 15 € / 100-129 € = 20 € / ≥ 130 € = 30 €
  - Activation conditionnée tests Colissimo USA en ligne KH-015

### Report explicite verrouillé (cf docs/lot0/KH-012-incentives-france-europe.md)

- **DROM-COM / UE / UK / Suisse / Europe hors UE** : **pas d'incentive automatique au MVP** (décision Alain via Phronesis 2026-05-30)
- DROM-COM = frais réels Colissimo Outre-Mer (devis Alain) ; UE = frais réels Colissimo Zone A + MR pays desservis ; UK/Suisse/Europe hors UE = mode « nous contacter »
- **Horizon de révision** : T+12 à T+18 mois post-launch checkout
- Hypothèse structurante à revérifier : COGS Kaïro < 3,5 € (commande 200 pièces). Si remonte 8-10 €/unité → arbitrage à revoir

### Signaux instrumentés pour révision

1. Abandon panier UE > 60 % sur 50 paniers ou 3 mois → étudier contribution UE
2. Volume UE ≥ 30 commandes/mois sur 2 mois → ouvrir étude formelle
3. Demandes devis manuel ≥ 10/mois sur 2 mois pour UK/Suisse/Europe hors UE → étudier activation
4. Sinistralité DROM-COM > 15 % sur 20 commandes → étudier seuil élevé ou exclusion
5. France < 55 € abandon > 50 % sur 100 paniers OU panier moyen France < 40 € sur 6 mois → étudier abaissement seuil OU contribution partielle OU tarification

### Formule internationale (zones activées, hors contribution USA)

- `displayed_shipping = carrier_cost + packaging_handling + 0.06 × (price_produit + carrier_cost)` (cf §5bis doc shipping)
- Taux 6 % couvre coûts indirects (emballage, traitement manuel, risque international, administratif, Stripe/PayPal moyens). Modifiable post-observation réelle.

### Wording (verrouillé)

- ❌ **Wording client INTERDIT** : "frais Stripe", "frais PayPal", "frais de moyen de paiement", "commission", "droits de douane offerts" → JAMAIS public
- ✅ **Wording autorisé** : "Frais de livraison internationale" / "Shipping and handling" / "Contribution aux frais d'expédition internationale" / "US shipping contribution"

### Doctrine tarifs MVP

WC configuré manuellement par zone × tranche poids × service. **Pas de tarifs temps réel**. Plugins transporteur (DHL/UPS/Zonos/Colissimo API/MR API) = phase 2, sauf exception Lot 1 plugin Mondial Relay ou Colissimo pour étiquettes/points relais.

Audit pré-publish KH-707 : grep `Stripe` / `PayPal` / `commission` / `droits de douane offerts` dans textes shipping public → fail si présent

## Doctrine Etsy / canal propriétaire

- Etsy = marketplace découverte, reste actif
- Koinobori House = canal propriétaire marque (collection, histoire, BCDG, confiance, relation)
- **Pas de cannibalisation frontale** Etsy ↔ site
- ❌ Jamais copier titres / descriptions / tags / structure fiches Etsy mot pour mot
- ❌ Jamais comparaison frontale prix Etsy vs site
- ✅ Fiches "maison de marque" articulent 7 dimensions différenciantes : collection, histoire, usage, confiance, BCDG, contexte décoratif, livraison/retours
- ✅ SEO cible queries marque + lifestyle + long-tail unique (pas exact-match Etsy)
- Doctrine détaillée : [docs/lot0/KH-012-KH-015-shipping-usa-seo.md](docs/lot0/KH-012-KH-015-shipping-usa-seo.md) §7

## Skills addyosmani / anthropic-skills à activer

- `anthropic-skills:kickoff` à chaque démarrage session
- `anthropic-skills:source-driven-development` pour tout code framework (WP/WC/plugin)
- `anthropic-skills:doubt-driven-development` avant décisions structurantes
- `anthropic-skills:security-and-hardening` Lot 4 + Lot 7
- `anthropic-skills:shipping-and-launch` Lot 7
- `anthropic-skills:triangulate` matrice triangulation décisions critiques

## Conventions PR / Git

- Repo à créer : `Ahlaoban/koinobori-house` (à confirmer avec user)
- Branches courtes (1-3 jours), trunk-based development
- Squash merge, format commit : `type(scope): subject (#N)`
- Co-Authored-By: Claude Opus 4.8 (1M context) <noreply@anthropic.com>
- **Jamais push direct main**, toujours via PR
- `.gitignore` obligatoire avant tout commit (exclure `stripe_backup_code.txt`, `.env`, `wp-config.php` si présent, etc.)

## Forbidden touches

- Multi-devise (phase 2)
- Espace privé B2B/B2G (phase 2)
- Upload B2B/B2G sans validation (phase 2)
- Likes (phase 2)
- Calculateur douanier maison (phase 2)
- Mentions Chine publiques (jamais)
- Mentions volume global stock (jamais)
- "fabriqué en France" sans validation (jamais)
- "US Flag" comme nom public (interne fournisseur seulement)

## Session memory

Persistent memory : fichiers du dossier mémoire de la session Claude Code (`MEMORY.md` + notes, auto-injectés). Hors repo, machine locale.

## Liens projets associés

- [[project_maijinn_saas_launch_strategy_2026-05-08]] (DEVCODE namespace) — MaïJinn protégé, focus principal post-29 juillet
- [[project_addyosmani_skills_installed_2026-05-26]] (DEVCODE namespace) — Skills activés sur ce projet
- [[user_alain_profile]] (DEVCODE namespace) — Bootstrap solo

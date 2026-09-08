# Passation Koinobori House — état au 27 juillet 2026

Document de reprise pour quiconque poursuit le projet : Manus, un autre intervenant, ou Alain seul.
Tout ce qui suit a été **mesuré**, pas estimé. Les valeurs viennent du dépôt et de l'API de staging le 27/07/2026.

**Lancement visé : 15 août 2026.** Soit 19 jours. Jalons : soft privé 12/08, G2 03/08, G3 06/08, **G4 10/08 (juge de paix visuel)**, G5 14/08.

---

## 1. Accès et emplacements

| Quoi | Où |
|---|---|
| Dépôt git | `C:\dev\Koinobori` → `github.com/Ahlaoban/koinobori-house` |
| Source de vérité projet | `CLAUDE.md` à la racine du dépôt |
| Staging | `https://staging.koinoborihouse.com` — derrière authentification HTTP |
| Identifiants staging | `C:\Users\herbi\.koino-staging-auth.txt` (hors dépôt) |
| Hébergement | o2switch, Cpanel + LiteSpeed |
| Pack créatif Manus | `C:\dev\Koinobori\Koinobori_House_Assets_Claude\` — **hors git**, 103 fichiers, 317 Mo |
| Empreintes du pack | [../charte-graphique/assets-manifest.md](../charte-graphique/assets-manifest.md) — SHA-256 de chaque fichier |

⚠️ Le pack créatif n'est pas versionné. Le manifeste permet de vérifier l'intégrité, **pas de restaurer**. Une sauvegarde externe au dépôt et au disque de travail doit exister.

---

## 2. État du dépôt

- **59 commits**, **127 fichiers suivis**, **10 PR toutes mergées**, 0 PR ouverte.
- `main` est à jour et contient tout.
- Une branche poussée **sans PR** : `docs/lot0-tableau-cookies` (corrige une mention auto-contradictoire dans les politiques cookies). À ouvrir ou à supprimer.
- Hors suivi volontaire : 14 variantes de logo dans `brand/`, `Koinobori_House_Assets_Claude/`, `*.pptx`.

### Ce que contient le dépôt

```
CLAUDE.md                        source de vérité : stack, doctrine, arbitrages
docs/charte-graphique/           spec v2.0 (créative + WordPress), KH-000 v1.2, manifeste assets
docs/lot0/KH-017-documents-legaux/  8 textes légaux FR/EN + checklist
docs/lot1/ lot2/ lot3/ lot6/     plans, CSV catalogue, textes pages
docs/ux-architecture.md          navigation, homepage, footer, points ouverts O-1 à O-10
docs/ux-backlog.md               backlog UX
docs/kairo-bd-recit-et-produit.md  cadrage de la BD
docs/handoff/                    ce document + CODEX-HANDOFF + REPRISE-charte
wp/themes/koinobori-child/       LE THÈME — CSS, polices woff2, theme.json, functions.php
wp/plugins/ wp/mu-plugins/       plugin taille unique, redirection de langue
catalog/                         master.csv, 9 images produit
tools/                           fetch-fonts.py, png-to-webp.js
```

---

## 3. État de staging — mesuré le 27/07

### Ce qui fonctionne

- **34 produits publiés** : 17 FR + 17 EN, les 17 paires liées dans Polylang.
- Prix, variations, stocks corrects. Breton et Bigouden ont bien leurs 2 variations 20/25 €.
- Textes enrichis finaux sur les 34 fiches : nylon, bouche 12-15 cm, émerillon, symbolique Kodomo no Hi, signature BCDG.
- **6 pages légales** créées : Livraison USA (230), Shipping to USA (231), Mentions légales (232), Legal notice (234), Politique cookies (235), Cookie policy (236).
- Le site est en **noindex** — correct pour un staging.
- Thème enfant « Koinobori House » actif sur Kadence.

### Ce qui ne fonctionne pas

| Constat | Détail |
|---|---|
| **Page d'accueil vide** | Index de blog par défaut : « Prêt pour votre première publication ? » + champ de recherche. Aucun des 11 mouvements de la charte |
| **Navigation auto-générée** | Aucun menu configuré. Le thème liste les pages publiées, et la nav se remplit toute seule à chaque publication |
| **Footer Kadence d'origine** | « © 2026 — Thème WordPress par Kadence WP ». La ligne d'horizon n'est pas posée |
| **9 produits sans visuel** | Les 6 de la collection Mer et les 3 Motifs. Ni sur staging **ni dans le dépôt**. Cartes blanches vides en boutique |
| **Sakura Rouge** | A une image dans `catalog/images/hanami/` jamais téléversée |

### Extensions : 7 installées sur ~17 prévues

Actives : WooCommerce, Polylang, Polylang for WooCommerce, SEOPress, UpdraftPlus, plugin maison « affichage taille unique ».
**Wordfence est installé mais désactivé.**

**Manquent**, alors que les pages légales publiées les décrivent :

| Manquant | Conséquence |
|---|---|
| WooCommerce Stripe + PayPal | **Aucune passerelle de paiement.** L'onglet WooCommerce est vide |
| FluentSMTP + Brevo | **Aucun email transactionnel.** Pas de confirmation de commande |
| Fluent Forms | Aucun formulaire : contact, B2B, B2G, et le formulaire de rétractation en ligne obligatoire depuis le 19/06/2026 |
| Complianz | Pas de bannière cookies |
| LiteSpeed Cache | Pas de cache, alors que G4 juge la performance |
| ACF · YITH Wishlist · ShortPixel · Plausible | Prévus, absents |

⚠️ **La politique de confidentialité §5 liste Stripe, PayPal, Brevo et Complianz comme sous-traitants réels.** Aucun n'est installé.

---

## 4. La charte v2.0 : ce qui existe déjà

C'est le point le plus souvent mal compris. **La fondation typographique est faite.**

`wp/themes/koinobori-child/assets/css/kh-foundations.css` (98 lignes) contient déjà :

- **Les 4 familles auto-hébergées en woff2**, sous-ensembles latin et latin-ext, unicode-ranges corrects : Cormorant Garamond 300/400/600 normal et italique, Lora 400 normal et italique, DM Sans 300/400, Noto Serif JP variable, Shippori Mincho 400/500.
- **Les jetons sémantiques** : `--kh-font-display`, `--kh-font-body`, `--kh-font-ui`, `--kh-font-jp`, `--kh-font-kanji`.
- **Les classes utilitaires avec leurs valeurs** : `.kh-title-hero` (Cormorant italique 300, `letter-spacing:-.02em`, `line-height:1.1`), `.kh-body` (1.75), `.kh-ui` (DM Sans 300, `.12em`, capitales, .75rem), `.kh-kanji`.
- `h1,h2,h3 { font-family: var(--kh-font-display) }`.
- Des palettes par monde : mer, kairo, hanami, motifs, territoires.
- `theme.json` avec 8 couleurs déclarées pour l'éditeur.

`tools/fetch-fonts.py` régénère les woff2 depuis Google Fonts avec sous-ensemblage fontTools.

### Ce qui manque vraiment

**1. Trois couleurs à migrer** (arbitrage C3) :

| Jeton | Déployé | Cible v2.0 |
|---|---|---|
| `--kh-washi` | `#F7F3EC` | `#F8F4EE` |
| `--kh-or` | `#C9A96E` | `#B8860B` — l'or v1 est explicitement abandonné |
| `--kh-indigo` | `#1B2B5E` | `#2B3A6B` |
| `--kh-white` cartes | absent | `#FFFDFC` |

Conformes déjà : `--kh-sumi` `#1A1410`, `--kh-vermillon` `#C8311A`.

**2. Gagner la cascade contre Kadence.** Les titres s'affichent en Lora sur les gabarits Kadence alors que la règle `h1,h2,h3` demande Cormorant. Problème de spécificité ou d'ordre de chargement, pas de fondation manquante. **C'est le premier point à diagnostiquer.**

**3. Les compositions** : les 11 mouvements de la page d'accueil, le footer ligne d'horizon (§11.4 de la spec fournit 31 lignes de CSS), les pages Mondes, les gabarits Lifestyle.

### La limite honnête de la spec

La spec v2.0 contient, mesuré : **1 bloc CSS**, **6 valeurs hexadécimales**, **11 valeurs numériques px/rem** au total sur 32 sections. Le reste est de la direction en prose plus 20 images de référence.

Il n'y a **aucune échelle typographique chiffrée ni rythme d'espacement**. Quiconque reprend devra soit les inventer, soit les mesurer sur les PNG — sauf à ce que Manus produise directement le CSS. **C'est la principale source d'écart possible avec l'intention créative, et elle est structurelle, pas imputable à l'exécutant.**

---

## 5. Doctrine non négociable

Détail complet dans `CLAUDE.md`. L'essentiel :

- ❌ **Jamais de mention de la Chine, dans aucun contexte, sans exception** (arbitrage Alain 2026-09-08). ⚠️ Cette ligne portait auparavant une exception bornée autorisant la Chine comme pays ou culture sur les pages B2G ; **cette exception est levée**. Référent culturel unique = le Japon.
- ❌ **Jamais** « atelier chinois », « fabriqué en France », ni aucune mention d'atelier ou de production sur les fiches produits.
- ❌ **Jamais** de référence au volume global de stock. Stock par produit uniquement.
- ❌ **Jamais** de tiret cadratin dans les fiches produits. La signature s'écrit `- by BCDG`, trait d'union simple.
- ❌ **Jamais** « bestsellers ».
- ❌ Wording livraison : jamais « frais Stripe », « frais PayPal », « commission », « droits de douane offerts ».
- ✅ Fiche produit en **2 blocs** : design et usage, puis signature BCDG. La phrase symbolique se place **à la fin du paragraphe 1**, pas en paragraphe séparé.

---

## 6. Décisions rendues, à ne pas rouvrir

| Réf | Décision |
|---|---|
| C1 | Fiche produit : « Détails et matières », jamais « fabrication » |
| C2 | La rubrique s'appelle « L'Atelier », et ne parle jamais de production |
| C3 | Palette v2.0 partout, tokens déployés à migrer |
| C4 | Navigation à **7 entrées** : Accueil · Boutique · Arts de vivre · Lifestyle · L'Atelier · Professionnels · Contact. **Annule A-1, A-2, A-4** — pas de mega-panel, pas de label « Collections », pas de slot Kaïro |
| C5 | Header Shoji V2 verrouillé, réalisation du principe Fusuma |
| C6 | Arts de vivre = page « bientôt ». **Aucune 6ᵉ catégorie WooCommerce** |
| A-6 | « Professionnels » est une page d'**aiguillage** vers Entreprises et Collectivités, qui gardent leurs formulaires distincts. Jamais de fusion |
| O-1/O-2 | Footer en **2 étages** : bande services (Navigation · Informations · Professionnels) au-dessus d'une ligne d'horizon stricte §11 |
| — | **Pas de gate avocat.** La validation des CGV est un contrôle complémentaire, non bloquant |
| — | BD Kaïro = **produit numérique téléchargeable**, PDF multi-appareils, vendue, couvrant les 15 épisodes |

Libellés et slugs bilingues : `docs/ux-architecture.md` §3.1.

---

## 7. Points ouverts

**Sur la charte** — `docs/ux-architecture.md` §9. Neuf des dix sont tranchés par défauts révisables. Reste **O-8** : des docs v1.2 référencent encore `/univers`, à rectifier à la création de la page.

Défauts posés le 27/07, révisables :
- 4 polices self-host, mesure de performance à G4. Porte de sortie : Noto Serif JP en pile système.
- **Le portrait Alain-Catherine n'est pas publié** tant que Catherine n'a pas validé. La page L'Atelier ouvre sans portrait.
- Pas de liens sociaux au lancement, les comptes n'étant pas confirmés.
- Kaïro dans le mouvement 4 « Créations BCDG ».

**Sur la BD** — `docs/kairo-bd-recit-et-produit.md`. Bloquants avant la première vente numérique :
- **B-7** : deux cases à cocher au checkout (accord exprès + renoncement à la rétractation). WooCommerce ne le fait pas nativement.
- **B-8** : le lien de téléchargement part par email, et aucun SMTP n'est installé.

**Réglementaire, jamais instruit** : **REP / IDU**. Si les koinobori relèvent d'une filière (emballages ménagers, textiles, articles de loisirs), un identifiant unique est obligatoire et doit figurer dans les CGV. Checklist KH-017 §H.

**Conformité** : l'audit cookies réel reste à faire après installation complète. C'est la seule des cinq conditions du README KH-017 qui ne soit pas levée.

**Éditorial** : incohérence d'accord entre **116 La Vague Bleue** (féminin) et **127 La Vague Brune** (masculin), sur une phrase structurellement identique. 116 est seule contre 16.

**Ménage** : brouillon **200** « Écailles Bleues et Oranges (Copier) » devenu inutile ; brouillons WordPress par défaut 3, 78, 182, 184.

---

## 8. Pièges connus

- **Le Browser pane intégré refuse le certificat de staging.** Passer par Chrome. L'authentification HTTP retombe quand un onglet est recréé.
- **Éditeur produits en mode Code.** Lire par `#content.value`. Un **clic programmatique sur `#publish`** fonctionne de façon fiable — 17 fois sur 17 — et évite la manœuvre screenshot puis coordonnées.
- **Les pages utilisent l'éditeur de blocs**, pas l'éditeur classique. `wpApiSettings.nonce` y est disponible, ce qui permet de créer des pages par l'API REST avec `?lang=fr|en` — testé, la langue est correctement assignée.
- **Polylang Free n'expose pas le lien de traduction en REST.** Le maillage FR↔EN passe par l'interface ou par `from_post`.
- **FR et EN partagent le même SKU** (116 et 119 = `KH-MER-001`). Ce n'est pas un doublon à corriger.
- **Polylang for WooCommerce synchronise seul** type, catégories, attributs, prix, stock et variations entre traductions.
- **Duplication de produit** : corriger les SKU auto-incrémentés, revérifier le titre, vider le champ Traductions si la source a une traduction.

---

## 9. Ce que je recommande de vérifier en premier

Par ordre de coût si on l'ignore :

1. **Les 9 photos produits manquantes.** Une boutique dont plus de la moitié des fiches sont blanches ne lance pas. Personne ne peut les inventer.
2. **Le socle transactionnel.** Sans Stripe on ne vend pas ; sans SMTP le client ne reçoit rien. C'est le chemin critique, pas la charte.
3. **Wordfence est désactivé.**
4. **La cascade Kadence contre le thème enfant.** Un diagnostic d'une heure qui conditionne toute l'application visuelle.
5. **REP / IDU.**

---

## 10. Sur les textes légaux

Les 8 documents de `docs/lot0/KH-017-documents-legaux/` sont complets et bilingues : identification vendeur, CM2C, TVA en franchise, RGPD avec durées de conservation, clause contenu numérique pour la BD.

Trois passages de nettoyage ont eu lieu le 27/07, parce que des mentions internes partaient vers des pages publiques : références de tickets (`cf KH-011`), jargon « MVP » dans les CGV et la politique de confidentialité, et une politique RGPD qui annonçait elle-même que ses durées « doivent être validées avant publication ».

**Leçon pour la suite : balayer tout contenu avant publication** à la recherche de références de tickets, de noms de lots, de « MVP », de notes de révision et de crochets. Les crochets `[date]`, `[nom]`, `[adresse]` des CGV sont en revanche **le modèle de formulaire de rétractation** et doivent rester mot pour mot.

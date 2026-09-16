# KH Social Agent — Audit de l'existant (Phase 1)

- **Date** : 2026-09-12
- **Objet** : ce qui existe déjà et peut être réutilisé (skills, corpus, assets), ce qui manque, et ce qu'il ne faut **pas** copier.
- **Instruction d'Alain** : reprendre seulement les briques utiles des skills existants (brand voice, structure de génération, mémoire, validation, principes d'apprentissage). Pas de copier-coller architectural.

---

## 1. Skills trouvés sur la machine

### 1.1 Skills Claude Code (`~/.claude/skills`)

Aucun skill dédié aux réseaux sociaux. Les skills utiles au chantier sont des skills de **méthode**, pas de contenu :

| Skill | Usage pour le Social Agent |
|---|---|
| `addyosmani-source-driven-development` | toute affirmation sur une API vient d'une page officielle citée ([02-apis-plateformes.md](02-apis-plateformes.md) est construit ainsi) |
| `addyosmani-doubt-driven-development` | revue adversariale à contexte frais de la présente architecture avant validation |
| `addyosmani-api-and-interface-design` | contrats d'événements et interface `Publisher` ([01-architecture.md](01-architecture.md) §9) |
| `addyosmani-security-and-hardening` | tokens, secrets, entrées non fiables (commentaires), à activer dès la Phase 3 |
| `addyosmani-shipping-and-launch` | mise en production du service (Phase 3) |
| `to-issues`, `triage`, `tdd` | découpage du backlog en tickets, priorisation, tests du Doctrine Gate |
| `kickoff`, `triangulate`, `phronesis` | rituels de session et décisions structurantes (D1, D8) |

### 1.2 Skills OMA (`C:\dev\oma-platform\backend\skills\prompts\`)

Quatre prompts de la plateforme OMA correspondent aux « trois skills » évoqués dans le brief. **Hypothèse à confirmer par Alain** : il s'agit de `brand_voice_v2.1.md`, `maibrand_v2.3.md` et `maipublish_v2.0.md` (`maideal_v2.3.md` en consommateur). Si ChatGPT désignait d'autres skills, les indiquer.

| Skill OMA | Brique **réutilisée** (adaptée, pas copiée) | Ce qui **n'est pas** repris |
|---|---|---|
| `brand_voice_v2.1` (Min&Maï) | La **structure** d'une charte éditoriale opérationnelle : influences de ton, règles de rédaction, **interdits absolus** (liste de mots), **orthographes exactes obligatoires** (table nom → forme correcte). Devient `content-brain/brand-voice.md` de Koinobori House avec ses propres valeurs (Ma, sobriété, ni Japon de carte postale ni manga dominant ni luxe sombre ; `Koinobori House`, `BCDG`, `Kaïro` avec tréma, `koinobori` nom commun, `Kodomo no Hi`) | Palette, typographies, noms propres et ton Min&Maï (Camus / Greene, vouvoiement B2B) ; le format DOCX |
| `maibrand_v2.3` | **Mémoire native en fichiers versionnés** (leads, calendrier éditorial, KPIs) → mémoire stratégique en git ([01-architecture.md](01-architecture.md) §6). **Convention `LEAD-<SOURCE>-<NNN>`** et **handoff `EVT_LEAD_IN`** → événement `lead.detected` v1. Séparation **visibilité 1-to-many / conversion 1-to-1** (« phare, pas projecteur ») → frontière Social Agent / Sales Agent. Table KPIs avec cibles An 1 / An 2 → tableau de bord entonnoir | Architecture par **commandes `!xxx`** et persona conversationnel ; les 4 piliers LinkedIn / mailing / réseaux / institutionnels ; « le contenu ne mentionne jamais de prix » (les prix koinobori sont publics ; seule la règle B2B/B2G sans tarif automatique s'applique) |
| `maipublish_v2.0` | **Workflow d'états** `draft → validating → review → approved → published` avec `rejected` → révision, repris et étendu en `DRAFT → GATED → PENDING_APPROVAL → APPROVED → SCHEDULED → PUBLISHED`. **Score brand voice avec seuil de passage** (70) → composante du Doctrine Gate. **Principe « seuls les livrables approuvés sortent »** et **registre traçable** → contrainte d'intégrité publication ⇐ approbation + gate | Formats documentaires (PDF, DOCX, PPTX, FEC), distribution Gmail / Drive, templates de rapports |
| `maideal_v2.3` | Consommateur d'`EVT_LEAD_IN` : sert de **modèle** pour le contrat que le futur KH Sales / Follow-up Agent devra respecter | Le pipeline commercial lui-même (hors périmètre Social Agent) |

Principe d'apprentissage : aucun des quatre skills n'implémente de boucle hypothèse → test → mesure → fusion. Le modèle de [01-architecture.md](01-architecture.md) §6.3 est nouveau ; il emprunte seulement l'idée de KPIs persistants et versionnés.

### 1.3 Autres dépôts (`C:\dev`)

`JDIC` (cartouche « Marketing & Growth (CMO) » archivée v8-v10), `maijinn-saas`, `mindara-ai-academy`, `cap-lucide` : aucune brique de publication sociale, d'analytics ou de connecteur réutilisable. Rien à reprendre.

---

## 2. Corpus Koinobori House réutilisable pour le Content Brain

| Source (repo Koinobori) | Ce qu'elle apporte | Statut pour le Content Brain |
|---|---|---|
| `CLAUDE.md` §Doctrine éditoriale, §Doctrine stock, §Tarification livraison (wording), §Identité marque, §Forbidden touches | **Toutes les règles du Doctrine Gate** ; exception B2G Chine levée le 2026-09-08 | À compiler en `content-brain/doctrine.md` (extrait opérationnel, pas une copie) |
| `docs/charte-graphique/KH-000b-…-CREATIVE.md` et `…-WORDPRESS.md` | §1 intention (Ma, maison éditoriale et galerie, trois écueils à éviter), §7 L'Atelier (deux regards, une maison ; texte d'ouverture **à valider**, aucune biographie inventée), §8.1 catégories Lifestyle & Koi (Koi & symboles, Intérieurs japonais, Motifs & savoir-faire, Carnets d'inspiration, Rencontres), §15 interdictions créatives | Base de `brand-voice.md` et des territoires G (Culture) et A (Créations) ; §8.1 = taxonomie de sujets prête à l'emploi |
| `docs/ux-architecture.md` §9 | O-4 : **portrait Alain-Catherine non publié** sans validation de Catherine ; O-5 : **pas de liens sociaux au lancement** ; O-9 : Kaïro = création BCDG, pas d'entrée de nav | Contraintes de calendrier et de consentement ([04-decisions.md](04-decisions.md) D2, D8) |
| `docs/kairo-bd-recit-et-produit.md` | Cycle *Kaïro des Vents Rouges, Le Navire Sans Nom*, 15 épisodes, 4 koi en vente (`KH-KAI-001` à `004`), BD PDF à l'automne 2026 sans date ; **B-4 : ne jamais annoncer de date ni promettre une sortie pour les épisodes 5-15** | Noyau de `content-brain/kairo/` ; règle B-4 encodée dans le gate |
| `docs/lot2/kairo-collection-EN.md`, `docs/lot2/kairo-collection-FR.csv` | Textes FR/EN validés des 4 épisodes, atmosphères (nuit bleue, gris brume…), wording 2 blocs | Exemples canoniques de ton produit ; vocabulaire Kaïro autorisé |
| `catalog/master.csv`, `catalog/README.md`, `catalog/images/` | Noms de modèles FR/EN, collections, descriptions, chemins images | ⚠️ `master.csv` porte encore les anciens codes SKU (`EDS/OKU/BRE`) et n'est pas la vérité runtime. **Le Content Brain lit WooCommerce**, pas le CSV. Les visuels `main.jpg` / `g1.jpg` sont réutilisables en social après vérification des droits (photos maison) |
| `docs/lot3/pages-professionnels-arts-de-vivre-FR-EN.md` | Wording B2B / B2G validé (sans Chine), types de projets, formulaires 9 (FR) et 10 (EN) | Territoire E (Custom) et réponses aux commentaires B2B/B2G ; libellés des `lead.segment` |
| `docs/lot0/KH-012-KH-015-shipping-usa-seo.md`, `CLAUDE.md` §Transporteurs | Wording livraison autorisé / interdit, USA « nous contacter » | Réponses aux questions livraison (catégorie `SHIPPING_QUESTION`) |
| `docs/lot0/KH-017-documents-legaux/` | Identité vendeur, médiateur CM2C, politique de confidentialité (à compléter pour les données sociales, D13) | Réponses aux questions juridiques = HITL ; politique de confidentialité à amender |
| `brand/koinoborihouse/` (logo, favicon, textures) | Identité visuelle des comptes (avatar de profil = tampon 鯉のぼり, bannières) | Prêt à l'emploi |
| `docs/charte-graphique/reference-v3/assets/` (ex. `produit-kairo-horizon.png`) | Visuels d'ambiance Manus v3.0 | Utilisables si Manus confirme les droits d'exploitation sociale |
| Mémoire de session (`MEMORY.md`) | Décisions datées : taxonomie 5 collections, header Shoji V2 verrouillé, or jamais pour boutons, Etsy non cannibalisé | Contraintes de brand voice et d'axes SEO/social (requêtes marque + lifestyle, pas exact-match Etsy) |

---

## 3. Ce qui manque et doit être produit avant la Phase 2

| Manque | Pourquoi ça bloque | Qui | Estimation |
|---|---|---|---|
| **Comptes sociaux** : aucun n'existe (O-5). Handles non réservés | Rien à publier, rien à connecter à l'agrégateur | Alain | 4 h (création, Business/Creator, Page Facebook liée pour Instagram Business, site revendiqué Pinterest, chaîne YouTube, page LinkedIn) |
| **Brand voice social** : la charte parle du site, pas des formats courts (hooks, longueur, emoji ou non, hashtags, ton des réponses) | Le Content Generator n'a pas de référence de ton pour 15 secondes de vidéo | Alain + Claude (proposition), Catherine (validation ton) | 4-6 h |
| **Bible Kaïro** : seuls les 4 épisodes vendus ont un texte ; personnages, arcs, lexique des épisodes 5-15 non documentés | Territoire C sans matière ; risque d'invention par le modèle | Alain | variable ; a minima une fiche personnages + synopsis par épisode **sans date** |
| **Personas Catherine / Alain** : territoires, sujets, ce que chacun ne dit pas, disponibilité pour valider | Choix de l'avatar / de la personne par sujet (brief §4) | Alain, Catherine | 2 h |
| **Consentement de Catherine** : portrait non validé (O-4) ; avatar et voix a fortiori | Sans accord écrit, tout le territoire « Catherine » et la Phase 7 sont à l'arrêt | Catherine | décision, pas un chantier |
| **Photothèque / vidéothèque maison** : pas de rushes des créateurs, pas de séquences d'installation, pas de packshots vidéo | Media Engine sans matière ; les avatars ne remplacent pas les vraies images (P2) | Alain, Catherine | 1 journée de tournage initial + 2 h/mois |
| **Process droits UGC** (World Gallery, photos clients) | Impossible de republier une photo client sans consentement écrit | Claude (formulaire type) + Alain | 2 h |
| **Jeux de hashtags et créneaux par plateforme** | Paramètres des variantes | Claude (proposition sourcée) | 2 h, puis appris |
| **Côté site, post-lancement** : page « lien en bio », endpoint `/go/`, champs UTM cachés Fluent Forms, plugin WP Consent API pour l'attribution WooCommerce, Plausible plan Business (Stats API) | Chaîne d'attribution ([02-apis-plateformes.md](02-apis-plateformes.md) §7) | Claude + Alain | 6-8 h, **après le 30/09** (aucun scope creep avant le lancement) |

---

## 4. Ce qu'il ne faut pas faire

- **Pas de copie de l'architecture OMA** (commandes `!`, personas, handoffs textuels). Le Social Agent est un service avec une base de données, des jobs et une UI, pas un prompt conversationnel.
- **Pas de réemploi de textes Etsy** (titres, descriptions, tags) dans les captions, doctrine Etsy / canal propriétaire.
- **Pas de portrait ni de biographie de Catherine** tant qu'elle n'a pas validé (O-4, charte §7.3).
- **Pas de contenu Making-of montrant la production** (échantillons reçus, matières, partenaires, lieux) : le territoire B se limite au geste créatif ([01-architecture.md](01-architecture.md) §7.4, décision D6).
- **Pas de teaser Kaïro daté** pour les épisodes 5-15 ni pour la BD tant que la date n'est pas arrêtée (B-4).

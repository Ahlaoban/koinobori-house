# Prompt de reprise — site koinoborihouse.com (KH-000 « Ma » + assainissement dépôt)

> À coller comme premier message de la nouvelle session. Remplace les versions précédentes.

---

Nouvelle session : poursuite du site **koinoborihouse.com** (WordPress / WooCommerce / Kadence).

**Avant d'agir : lance le skill `kickoff`** (audit des skills), puis attends mon GO.

## ÉTAT RÉEL
- **Charte** = **KH-000 Design System v1.2 « Ma (間) »** (Manus) — **supersède** le doc « Washi & Encre ». Palette : washi `#F7F3EC`, encre sumi `#1A1410`, **vermillon `#C8311A` (CTA)**, or brûlé `#B8860B`, brume, indigo `#1B2B5E` (+ sakura / forêt par monde). Typo **5 polices self-host** : Cormorant Garamond (héros italic), Shippori Mincho (sous-titres JP), Lora (corps), DM Sans (UI/prix), Noto Serif JP (kanji). **5 Mondes = 5 collections** (Mer · Kaïro · Hanami · Motifs · Territoires).
- **Plan d'intégration 5 couches = VALIDÉ** → `docs/lot6/KH-000-integration-plan.md` (1 Kadence global · 2 thème enfant · 3 blocs Gutenberg/Kadence · 4 CSS custom · 5 différé Lot 6/7). **Rien appliqué — attend mon GO d'exécution.**
- **Lot 6 PR1 « fondations »** (tokens, fonts self-host, base) = **déjà commité** (2 commits) sur `feat/lot6-pr1-foundations` (PR #2, DRAFT).
- **Header shoji V2 = VALIDÉ + VERROUILLÉ** : nuit indigo + lune dorée (`blue-night-texture-III.webp`), logo **or** (`logo-color-gold-III-cart.png`), split 57,38 %, spread fixe 405 px, hauteur `clamp(130px,16vw,230px)`. Source éditable : `header-shoji-koino-v1.src.html` ; autonome base64 : `header-shoji-koino-v1.html`. **Ne pas le rouvrir** — portage WP = Lot 7.

## ARBITRAGES VERROUILLÉS (Alain)
- **A** — **or autorisé** pour icônes / détails / header / soulignement nav ; **vermillon réservé aux CTA** ; jamais d'or en aplat de fond massif. *(assouplit l'ancienne règle « jamais or pour liens »)*
- **B** — B2B/B2G avancé (compte pro, dashboard, devis en ligne, galerie) = **phase 2**. MVP = landing + formulaire Fluent Forms.
- **C** — **ignorer** Yoast (→ SEOPress) et WP Rocket (→ LiteSpeed) recommandés par la charte §16.
- **D** — **self-host** les 5 polices (pas de CDN Google — RGPD).
- **E** — cartes produit : **pas de blanc pur** (blanc cassé).
- **F** — rubriques du header (charte §9) → **à arbitrer au portage Lot 7**.
- **G** — wording « cousu à la main » → à vérifier vs doctrine production.

## CONTRAINTES DURES
- **Socle inchangé** : WooCommerce, **Polylang** (FR+EN), **SEOPress**.
- **Aucune migration** React / Shopify / headless. Reste **WordPress/WooCommerce/Kadence Free + thème enfant**. Pas d'Elementor, pas de WP Rocket.
- **Staging d'abord**. Jamais de push direct sur `main` — toujours via PR.
- Doctrine éditoriale : **jamais la Chine, dans aucun contexte** (règle canonique : CLAUDE.md §Doctrine éditoriale impérative) ; jamais atelier ni origine ; fiche produit **2 blocs** ; taxo **5 collections** ; marque fixe (logo Koinobori House ≠ signature BCDG ; cartouche 鯉のぼり).

## ⚠️ DÉPÔT EN DÉSORDRE — à trancher en premier
- **PR #1** (`feat/kh-107-root-redirect`) : **ouverte depuis le 13 juin, jamais mergée**, + **11 commits non poussés**. Elle a absorbé tout le Lot 2 (docs catalogue) → sortie de son périmètre.
- **PR #2** (`feat/lot6-pr1-foundations`, DRAFT) : branche courante, **empilée sur PR #1**. Local **divergé** : **24 devant / 2 derrière** — GitHub a 2 commits (`85ca86c`, `72a6a0e`) au **même contenu mais SHA différents** que 2 commits locaux. ⚠️ **Ne PAS faire `git pull`** dessus (merge sale / doublons).
  - Bonne nouvelle : `origin/feat/lot6-pr1-foundations` est **propre** (`main` + les 2 commits thème) ; c'est le **local** qui est pollué.
- **39 fichiers non commités** : audit doctrine (11 docs), assets `brand/` (blue-night, logo-gold, textures shoji), `docs/charte-graphique/`, `docs/lot6/KH-000-integration-plan.md`, `header-shoji-koino-v1*.html`, `tools/`, `wp/mu-plugins/kh-single-variation-display.php`.

**Reco déjà formulée (à valider par moi)** :
1. Pousser les 11 commits puis **merger PR #1 dans `main`** → débloque (Lot 6 est empilé dessus).
2. **Rebaser Lot 6 sur le nouveau `main`** → les 22 doublons disparaissent (déjà dans main), **zéro perte** (contenu identique des 2 côtés, vérifié).
3. **Commiter les 39 fichiers** en lots cohérents (docs / assets+header / tools), en **triant** `*.bak.html` et les `.png` doublons des `.webp`.

## GOUVERNANCE
UX/AD = **arbitrage exclusif Alain**. Claude **propose, challenge, structure** — n'exécute **rien** sans GO explicite, **surtout sur git** (merge, rebase, force-push = destructif). Manus = source AD. `to-prd` en veille active.

## OUTILS / REPÈRES
- `CLAUDE.md` (doctrine, stack verrouillée) · `MEMORY.md` + notes auto-injectées · `docs/ux-backlog.md` (UX-001..010).
- **Banque d'images** : `koinobori-house-images/` (gitignoré) — `backgrounds/`, `collections/{mer,motifs,hanami,kairo,territoires}/`, `rubriques/`.
- **Outil PNG→WebP** : `node tools/png-to-webp.js <dossier> 82 [--delete]`.

## DÉMARRAGE
1. `kickoff`.
2. **Trancher le dépôt** : me proposer le plan de commandes, puis exécuter **pas à pas, avec mon GO à chaque étape sensible**.
3. Puis **exécuter le plan KH-000** (`docs/lot6/KH-000-integration-plan.md`), **couche par couche**, staging d'abord.

**N'applique rien sans mon GO.**

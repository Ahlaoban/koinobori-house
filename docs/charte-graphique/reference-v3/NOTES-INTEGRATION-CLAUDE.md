# Référence Manus v3.0 — notes de versionnement et écarts à traiter

- **Reçue** : 2026-07-27, archive `Koinobori_House_Reference_HTML_CSS.zip`
- **Statut** : ✅ **source de vérité visuelle**. Le portage WordPress s'y conforme.

Manus l'appelle « charte v3.0 », nos documents disent « v2.0 ». **Même charte, même contenu.** Le pack d'assets était déjà estampillé « Design System v3 » sur sa slide 3. À harmoniser une fois, sans urgence.

---

## Ce que contient ce dossier

| Fichier | Rôle |
|---|---|
| `index.html` | Page d'accueil complète, 11 mouvements, HTML pur |
| `styles.css` | **Le système** : jetons, composants, états, points de rupture |
| `INTEGRATION_KADENCE.md` | Correspondance mouvement par mouvement vers les blocs Kadence |
| `VALIDATION_RESPONSIVE.md` | Contrôle visuel de Manus à 1280, 768 et 360 px |
| `ASSETS_MANIFEST.md` | Dimensions et rôle des 14 images de la référence |
| `DEMANDE_CLAUDE_SOURCE.txt` | Le brief d'origine, conservé pour traçabilité |
| `assets/produit-*.png` | 4 découpes produit **nouvelles**, 680 Ko |

## Images : ce qui est versionné, ce qui ne l'est pas

**10 des 14 images de l'archive sont identiques au bit près** à celles du pack du 25/07 — vérifié par SHA-256 contre [assets-manifest.md](../assets-manifest.md). Les PNG d'origine (43 Mo) ne sont pas reversés ; ils vivent dans `Koinobori_House_Assets_Claude/`, hors git, avec le rendu de référence de Manus (`reference-autonome-1280.png`).

En revanche, **le thème enfant porte 11 déclinaisons webp** (commit `076bbdf`, 2026-08-05) dans `wp/themes/koinobori-child/assets/images/` : `arts-ceramique` · `arts-lampe` · `arts-textile` · `cartouche-koi` · `hero-koinobori` · `lifestyle-koi` · `sceau-bcdg` · `trait-vermillon` · les 4 `produit-*`. `fond-washi` est remplacé par `texture-washi-v2.webp` déjà présent. **Seul `atelier-alain-catherine` n'est versionné nulle part**, volontairement (O-4).

Pour ouvrir `index.html` en local avec toutes ses images, il faut repartir de l'archive d'origine.

---

## Ce que la référence apporte, et qui n'existait nulle part

La spec en prose contenait 11 valeurs numériques au total. Ce fichier CSS les remplace par un système complet :

- **Échelle typographique** : 7 niveaux — display, title, subtitle, lead, body, caption, ui — chacun avec taille, graisse, interligne et approche. `clamp()` sur les grandes tailles.
- **Échelle spatiale** : 12 pas de 4 px à 160 px, commentée « le Ma rendu explicite ».
- **Jetons de mise en page** : conteneur 1200 px, gouttières 40/28/20 px selon la largeur, respiration entre sections 160/96/72 px, ligne d'horizon du footer 88 px.
- **Palette v2.0 complète**, corrections C3 incluses, plus des variantes fonctionnelles en opacité.

**Toutes les classes sont préfixées `kh-`**, sans une seule balise nue ciblée. C'est ce qui rend la reprise de la cascade contre Kadence possible.

---

## Écarts à traiter au portage

Aucun ne touche le système visuel. Ce sont des arbitrages postérieurs à la conception de Manus, ou qu'il ne pouvait pas connaître.

| # | Écart | Traitement |
|---|---|---|
| 1 | La bande haute du footer porte 4 réassurances (livraison suivie, paiement sécurisé, conseil, projets pro). O-1/O-2 demandait des groupes **Navigation · Informations · Professionnels** | Garder sa mise en forme, remplacer le contenu. Structure à 2 étages déjà correcte |
| 2 | **Instagram** figure dans la ligne d'horizon | Retirer. O-5 : aucun réseau au lancement tant que les comptes ne sont pas confirmés |
| 3 | Manquent **Retours**, **CGV** et **Gestion des cookies** | Ajouter. Le lien cookies est une obligation |
| 4 | Manquent **Entreprises** et **Collectivités** | Ajouter au groupe Professionnels (A-6) |
| 5 | `atelier-alain-catherine.png` est utilisé au mouvement 05 | **Ne pas publier** tant que Catherine n'a pas validé (O-4). Ouvrir L'Atelier sans portrait |
| 6 | Noms produits de démonstration inventés : Ōkusai, Kaïro Horizon, Hanami Sakura | Remplacer par le catalogue réel au portage |
| 7 | **« Les cinq mondes » (mouvement 03) liste Mer · Kaïro · Hanami · Motifs · Arts de vivre.** Territoires n'apparaît nulle part dans la référence | Remettre **Territoires** en 5ᵉ monde. Arts de vivre n'est pas une collection (C6) : il a son propre mouvement 07 |
| 8 | Ligne d'horizon : `© 2026 Koinobori House` seul | Écrire `© 2026 Koinobori House · Créations BCDG` (CLAUDE.md §Footer) |
| 9 | `INTEGRATION_KADENCE.md` dicte un footer avec `SIREN 838 329 271` et « Koinobori-house » | **Ne pas copier.** SIREN réel : 945 241 545. Le libellé légal vient de `docs/lot0/KH-017-documents-legaux/` |
| 10 | Le header de la référence est un header simple (« Mon compte » en texte, ni recherche ni panier) | Ce n'est pas le Shoji V2 verrouillé par C5. Source du header au portage : `header-shoji-koino-v1.src.html` + icônes compte / recherche / panier au trait fin |

## Contrôles déjà passés

Doctrine : aucune mention de production, d'atelier, de fabrication ni d'origine · pas de « bestsellers » · signature `- by BCDG` en trait d'union simple, 3 occurrences · le seul tiret cadratin est dans le `<title>` du fichier de référence, hors contenu produit.

Accessibilité : `lang="fr"` · tous les `alt` renseignés · `:focus-visible` sur tous les éléments interactifs · `prefers-reduced-motion` respecté.

Navigation : **les 7 entrées exactes de C4**, sans Collections, sans Univers, sans entrée Kaïro.

---

## Revue PR #12 (2026-09-07)

Les écarts 7 à 10 viennent de la revue de code de la PR de portage. Côté thème, la revue a trouvé que `functions.php` réinjectait encore la palette v1 dans deux filtres (`block_editor_settings_all`, `wp_theme_json_data_theme`), ce qui annulait la migration C3 de `theme.json` : corrigé dans la même PR, la palette est désormais relue depuis `theme.json` par une fonction unique. Reste ouvert, à confirmer sur staging : le reset global `*` / `html { scroll-behavior }` du CSS de Manus s'applique à tout le site (effet attendu nul, Kadence pose déjà `border-box`).

---

## Revue de code max effort (2026-09-09)

Dix angles indépendants sur la PR #12, puis vérification manuelle de chaque affirmation. **15 constats retenus.** Ce qui suit dit ce qui a été corrigé et ce qui attend un arbitrage.

### Corrigé dans cette PR

| Constat | Correctif |
|---|---|
| `kh_palette()` renvoyait un **tableau vide** sur tout échec de lecture de `theme.json`, tableau que les deux filtres installaient tel quel. Avec `disableCustomColors`, l'éditeur se retrouvait sans aucune couleur, et WordPress cessait d'émettre les `--wp--preset--color--kh-*`, décolorant les pages déjà publiées. Un BOM UTF-8 suffisait | Repli littéral `koinobori_child_palette_fallback()` sur les valeurs v2.0, plus un `error_log` hors production pour que la panne soit diagnosticable |
| La fonction était un **global non gardé** sous le préfixe `kh_`, déjà employé par `wp/plugins/kh-single-variation-display/`. Les extensions se chargeant avant les thèmes, une collision aurait été un Fatal error au parsing, donc écran blanc sur le front **et** sur `wp-admin` | Renommée `koinobori_child_palette()`. Pas de garde `function_exists` : elle ferait silencieusement confiance à la fonction d'un tiers, ce qui est pire qu'une collision bruyante |
| Écart 9, SIREN **838 329 271** faux dicté par `INTEGRATION_KADENCE.md`, plus le nom d'entité « Koinobori-house » et 2 liens légaux au lieu de 4 | Ligne réécrite dans le fichier lui-même. Le garde-fou ne vivait que dans ce tableau d'écarts, que l'intégrateur n'ouvre pas forcément |
| Écarts 2, 3 et 8, ligne d'horizon | Instagram retiré, les 4 liens légaux posés, `© 2026 Koinobori House · Créations BCDG` rétabli, Contact et Livraison renvoyés à la bande services |
| Écart 7, Territoires absent des cinq mondes | Le 5ᵉ monde redevient **Territoires**. Kanji `地` et suppression du modificateur indigo (réservé à Arts de vivre) **à confirmer par Manus** |
| Écart 5, portrait Alain-Catherine | Avertissement d'interdiction ajouté **dans les trois fichiers** qui le prescrivaient : `index.html`, `INTEGRATION_KADENCE.md`, `ASSETS_MANIFEST.md` |

### 🔴 Demande l'arbitrage d'Alain, non corrigé

**L'or v2.0 `#B8860B` échoue au contraste, et c'est la charte qui est en cause, pas le portage.**

| Paire | Rapport mesuré | Seuil AA |
|---|---:|---|
| Or sur washi `#F8F4EE` | **2,97:1** | 3:1 même pour du grand texte |
| Or sur blanc `#FFFDFC` | 3,21:1 | 4,5:1 pour du texte |
| Or sur brume `#E8E4DC` | 2,57:1 | 3:1 |
| Or sur indigo `#2B3A6B` | **3,36:1** | 4,5:1 |

La dernière ligne est une **régression introduite par cette PR** : avec les valeurs v1 la même paire (`#C9A96E` sur `#1B2B5E`) mesurait **6,04:1**. Éclaircir l'indigo et foncer l'or simultanément a cassé le couple, et `.kh-eyebrow--light` l'utilise à 12 px sur la bande Arts de vivre.

Aggravant : `disableCustomColors` et `custom: false` retirent le champ hexadécimal, donc un rédacteur qui choisit « Or » pour une citation — l'usage que la charte v2.0 §5.1 réserve précisément à l'or — n'a **aucun moyen** de corriger.

Trois issues possibles, toutes à trancher par Alain avec Manus : assombrir l'or, ne l'employer que sur sumi, ou l'accepter comme purement décoratif et ne jamais lui confier de texte.

### Ouvert, à vérifier sur staging

- **Aucun markup n'utilise le CSS.** Sur 1448 lignes, seuls `:root`, le reset `*` et `html` peuvent matcher une page WordPress. Merger cette PR ne rend donc pas la page d'accueil conforme : il faudra construire les 11 mouvements, à la main dans Kadence ou en `register_block_pattern()`.
- **`.kh-page` n'est jamais posé sur `<body>`**, donc l'anneau de focus vermillon, la typographie de base, le reset des liens et le bloc `prefers-reduced-motion` de la v3 sont inertes. ⚠️ Le correctif naïf est piégé : ajouter `kh-page` sur `body` activerait aussi `.kh-page a { color: inherit; text-decoration: none }`, ce qui rendrait indiscernables tous les liens du panier, du checkout et des pages légales.
- **`html { scroll-behavior: smooth }`** s'applique à tout le site. Les notes le disaient sans effet, ce qui ne vaut que pour la moitié `box-sizing` : rien chez Kadence ni WooCommerce ne pose `scroll-behavior`, et le défilement natif entre en concurrence avec l'animation jQuery du checkout.
- **Les hex v1 abandonnés survivent** dans `kh-foundations.css` : `--kh-motifs-1` `#F7F3EC`, `--kh-motifs-3` `#C9A96E`, `--kh-mer-1` `#1B2B5E`. Les jetons maîtres sont bien surchargés, pas ceux des 5 Mondes. Inerte aujourd'hui, visible au montage des pages Mondes.
- **`--kh-white #FFFDFC` est utilisé 15 fois par le CSS mais absent de `theme.json`**, donc impossible à choisir dans l'éditeur alors que la charte le prescrit pour les cartes produits.
- **Liens légaux du footer à 10 px et 3,57:1**, contre « Mobile min 16px » et « lisible à 360 px » de la charte §13.
- **Poids** : `arts-textile.webp` fait 734 Ko pour une carte de 384 px, `hero-koinobori.webp` 528 Ko servi tel quel à un téléphone. 11 des 12 images du thème ne sont référencées par rien.
- **Duplication** : `styles.css` et `kh-charte-v3.css` sont identiques à trois `url()` près. Toute correction doit être appliquée deux fois, et un diff non vide étant l'état attendu, la dérive devient invisible.

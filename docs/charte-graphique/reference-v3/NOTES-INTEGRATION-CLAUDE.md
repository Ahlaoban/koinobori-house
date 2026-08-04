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

## Images volontairement non versionnées

**10 des 14 images de l'archive sont identiques au bit près** à celles du pack du 25/07 — vérifié par SHA-256 contre [assets-manifest.md](../assets-manifest.md). Les reverser aurait ajouté 43 Mo de doublons.

`arts-ceramique` · `arts-lampe` · `arts-textile` · `atelier-alain-catherine` · `cartouche-koi` · `fond-washi` · `hero-koinobori` · `lifestyle-koi` · `sceau-bcdg` · `trait-vermillon`

Elles vivent dans `Koinobori_House_Assets_Claude/`, hors git. **Pour ouvrir `index.html` en local avec toutes ses images, il faut repartir de l'archive d'origine.** Le rendu de référence produit par Manus est également dans l'archive (`reference-autonome-1280.png`).

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

## Contrôles déjà passés

Doctrine : aucune mention de production, d'atelier, de fabrication ni d'origine · pas de « bestsellers » · signature `- by BCDG` en trait d'union simple, 3 occurrences · le seul tiret cadratin est dans le `<title>` du fichier de référence, hors contenu produit.

Accessibilité : `lang="fr"` · tous les `alt` renseignés · `:focus-visible` sur tous les éléments interactifs · `prefers-reduced-motion` respecté.

Navigation : **les 7 entrées exactes de C4**, sans Collections, sans Univers, sans entrée Kaïro.

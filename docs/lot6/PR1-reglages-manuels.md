# PR1 — Réglage manuel requis sur chaque environnement (staging + prod)

> Ce réglage vit dans le **Customizer Kadence** (base de données, pas versionné Git).
> Il doit être reproduit à la main sur **chaque environnement** (staging, prod) après déploiement du thème `koinobori-child`.
> Sans ce réglage, Kadence charge en plus **Fraunces** (titres) et **Inter** (corps) via Google Fonts CDN — polices de l'ancienne charte, **Inter interdit par KH-000 §17.1**, et appel Google = non conforme RGPD.

## Réglage à appliquer

**Apparence → Personnaliser → Typographie** (Kadence Customizer) :

| Champ | Valeur à sélectionner |
|---|---|
| **Police de base** | Réglages par défaut *(= Système par défaut)* |
| **Famille de police du titre** | Réglages par défaut *(= Système par défaut)* |

Puis cliquer **Publier**.

⚠️ Ne **jamais** sélectionner une police KH (Cormorant, Lora…) ici — Kadence la rechargerait lui-même en doublon. Le rendu réel vient de `assets/css/kh-foundations.css` (thème enfant), qui prend la main sur les éléments Kadence dès que Google/Fraunces/Inter sont désactivés côté Kadence.

## Vérification après réglage

1. Recharger une page du site (**Ctrl+F5**).
2. DevTools → **Network** → filtrer `google` → doit rester **vide**.
3. DevTools → **Network** → filtrer `woff2` → seules les polices `assets/fonts/*.woff2` du thème apparaissent (Cormorant Garamond, Lora, DM Sans, Shippori Mincho, Noto Serif JP). Plus de fichiers au nom en hash (Fraunces/Inter).

## Pourquoi ce n'est pas dans le code

Kadence stocke ce choix en `wp_options` (Customizer), pas en fichier — impossible à versionner sans dupliquer le mécanisme de Kadence. Documenté ici pour que le geste soit reproductible à chaque nouvel environnement, conformément au choix « fondations en code, Customizer documenté » validé pour PR1.

## Historique — pourquoi ce fichier existe

Découvert lors des tests staging PR1 (2026-07-01) : la palette de couleurs de l'éditeur Gutenberg affichait aussi les couleurs par défaut de Kadence en plus des 8 couleurs KH-000, malgré `theme.json`. Kadence réinjecte sa palette via le filtre `wp_theme_json_data_theme` après le thème enfant. Corrigé en code (pas de réglage manuel nécessaire pour ce point) : voir `functions.php`, filtres `block_editor_settings_all` et `wp_theme_json_data_theme` (priorité 20, après Kadence).

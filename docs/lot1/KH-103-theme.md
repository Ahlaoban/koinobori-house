# KH-103 — Kadence + thème enfant + charte de base (Lot 1)

- **Ticket** : KH-103
- **Lot** : 1
- **Date exécution** : 2026-06-12
- **Statut** : ✅ **FERMÉ** (sous réserve vérifs front confirmées Alain)
- **Environnement** : production `koinoborihouse.com`

## 1. Objectif

Socle visuel conforme à la charte CLAUDE.md (palette, typo, logo, favicon, boutons) **sans design de pages ni contenu** (Lot 3). Code versionné dans le repo.

## 2. Thèmes installés

| Thème | Rôle | Version |
|---|---|---|
| **Koinobori House** (enfant) | **Actif** | 1.0.1 |
| Kadence (parent) | template | 1.5.0 |
| Twenty Twenty-Five | secours | — |

## 3. Code versionné — `wp/themes/koinobori-child/`

- `style.css` : en-tête de thème (Template: kadence) + référence charte + **règles boutons** (radius 8px, padding vertical 12px, texte ivoire `#FDF8F0`) — ajoutées en 1.0.1 car Kadence Free n'expose pas de section « Boutons » dans le Customizer.
- `functions.php` : enqueue de la feuille enfant après le parent (priorité 20). Aucun autre code (le mu-plugin racine KH-107 est séparé).
- Déploiement : zip `koinobori-child/` (chemins forward-slash — ⚠️ le zip natif Windows écrit des antislash que le serveur Linux refuse ; zip reconstruit via System.IO.Compression). Téléversé + « Remplacer le thème actif ».
- Commits : `ff888aa` (1.0.0 squelette + favicon-512), `b704e31` (1.0.1 boutons).

## 4. Réglages Customizer (vivent en base, pas dans le thème)

### Palette (Couleurs & polices → Couleurs → Palette globale)
- Accents : corail `#E05A5A` (principal/liens) · rouge sourd `#B84C4C` (hover) · indigo `#1E3A5F` (secondaire)
- Contrast : noir encre `#1A1A1A` + gris par défaut
- Base / fond du site : ivoire `#FDF8F0` ; gris pierre `#E5DED2` dispo
- ⚠️ **Or doux `#C6A15B` exclu de la palette** (doctrine : jamais boutons/liens/fonds)

### Typographie
- Corps : **Inter** 400, 17px (≥ 16px mobile ✓)
- Titres : **Fraunces**, poids 600 sur H1-H4 (fourchette charte 400-600)

### Performance
- **« Charger les Google Fonts localement » = ON** (RGPD : Fraunces/Inter servies depuis le serveur, zéro appel fonts.googleapis.com)

### Identité du site
- Logo : `brand/koinoborihouse/logo-color.png` (recadré auto par WP → `cropped-logo-color.png`)
- **Titre du site en texte masqué** (sinon doublon avec le logo)
- Icône de site (favicon) : `brand/koinoborihouse/favicon-512.png` (512×512)

## 5. Hors scope (respecté)

Aucun design de page, aucun contenu, aucun menu définitif → **Lot 3**. KH-103 = socle visuel uniquement.

## 6. Vérifications

- ✅ Thème enfant 1.0.1 actif, parent Kadence présent, secours TT25
- ✅ Fond ivoire, titres Fraunces, corps Inter
- ✅ Fonts locales activées
- ✅ Logo seul dans le header (doublon titre supprimé)
- ✅ Favicon tampon 鯉のぼり 512px
- ⏳ noindex toujours actif (à reconfirmer — inchangé par le thème)

## 7. Suite

- **Re-clone du staging depuis la prod** (validé ChatGPT) avant KH-104, pour que Polylang/104b-pré se testent sur une copie fidèle (prod = post KH-101/102/103).
- KH-104 Polylang Free → KH-107 mu-plugin racine → KH-104b-pré.

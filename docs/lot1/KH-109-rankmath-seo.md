# KH-109 — RankMath / SEO i18n (Lot 1)

- **Ticket** : KH-109
- **Lot** : 1
- **Date plan** : 2026-06-13
- **Statut** : 🟡 **plan + diagnostic — AUCUNE action staging avant GO Alain**
- **Environnement** : **staging uniquement** · pas de prod · pas de merge PR #1 · pas de Polylang Pro · mu-plugin KH-107 non touché · pas d'import catalogue réel
- **Bloque** : KH-104b-full (ne pas ouvrir avant verdict KH-109)

## Objectif
Installer + configurer **RankMath Free** sur staging et **valider la compatibilité SEO i18n** (hreflang / canonical / sitemaps FR-EN) avant KH-104b-full.

## 1. 🔴 Finding de compatibilité (source officielle)

RankMath **n'est pas nativement compatible Polylang** (*« Polylang … doesn't intend to [be compatible] »* — [KB RankMath](https://rankmath.com/kb/polylang-compatibility/)). Conséquences :

| Élément | Réalité RankMath + Polylang Free |
|---|---|
| **hreflang** | Fourni par **Polylang** (natif front-end), **pas** RankMath. RankMath ne gère pas le hreflang multilingue. |
| **Sitemaps multilingues** | **Support faible** (RankMath l'admet ; Yoast/SEOPress meilleurs). La structure `/fr/sitemap.xml` + `/en/sitemap.xml` **n'est pas garantie** en natif. |
| **Canonical** pages traduites | **Point de vigilance** : en mode sous-répertoire l'URL porte déjà la langue (souvent OK), mais à **vérifier** (jamais cross-lang). |
| **Shim officiel** | 2 fichiers PHP (`rank-math-ppl.php` + `rank-math.php`) à poser dans le thème pour forcer canonical par langue + sitemaps par langue (orienté **multi-domaine** ; nous = sous-répertoire). |

**Stratégie retenue : test-driven.** Installer RankMath Free **minimal** → **observer** le comportement réel (notre install est en **sous-répertoire** `/fr//en/`, pas multi-domaine) → décider le shim **uniquement si** la vérif montre canonical KO ou sitemap inutilisable. **Pas de shim à l'aveugle.**

> Garde-fou doctrine : stack verrouillé = RankMath. Si KH-109 révèle que RankMath Free + Polylang Free ne peut pas produire des sitemaps i18n acceptables **même avec le shim**, c'est un **NO-GO** → arbitrage Alain (le shim, ou rouvrir le choix du plugin SEO — Yoast/SEOPress sont meilleurs pour Polylang selon RankMath ; = changement de stack, validation explicite). **Ne pas décider maintenant** : d'abord les données.

## 2. Procédure d'installation (staging) — APRÈS GO

1. Extensions → Ajouter → **« Rank Math SEO »** (Free) → Installer → Activer.
2. **Assistant de configuration RankMath** — minimal :
   - Connexion compte RankMath : **Skip** (« Skip, I don't have an account » / ignorer) — pas obligatoire.
   - Type de site : « **Other / Blog** » (peu importe pour le test).
   - **Sitemaps** : **activer** (on en a besoin pour la vérif).
   - **Optimisations** : laisser par défaut.
   - **noindex** : **NE PAS** désactiver le « discourage search engines » de WP (staging doit rester noindex).
   - Modules : activer **Sitemap** ; **WooCommerce** (si proposé) ; laisser **Redirections** / **404 Monitor** par défaut **mais ne créer AUCUNE redirection** (surtout pas sur `/`).
3. Ne **rien** configurer d'autre (pas de connexion GSC, pas d'import Yoast, pas de redirections).

## 3. Réglages recommandés RankMath (Polylang + WooCommerce)

| Réglage | Valeur | Raison |
|---|---|---|
| Module **Sitemap** | ON | vérifier la structure i18n |
| Module **WooCommerce** | ON (si dispo Free) | métadonnées produits propres |
| Module **Redirections** | présent mais **0 règle** | éviter tout conflit avec KH-107 (`/`) |
| **hreflang** | laissé à **Polylang** | RankMath ne le gère pas — ne pas chercher à l'activer côté RankMath |
| **Canonical** | RankMath par défaut (self-canonical) | vérifier qu'il est **par langue** (§5) |
| « Discourage search engines » WP | **reste ON** (noindex) | staging |

## 4. Vigilance hreflang
- hreflang = **Polylang**. Vérifier dans le `<head>` de `/fr/` et `/en/` la présence de `rel="alternate"` : `hreflang="fr-FR"`, `hreflang="en"` (ou `en-US`), `hreflang="x-default"`.
- **Pas de duplication** : RankMath ne doit pas ajouter un second hreflang concurrent. Si doublon constaté → finding.
- Écart possible : Polylang peut émettre `en-US` au lieu de `en` (doctrine = `en`). À noter (mineur, non bloquant a priori).

## 5. Vigilance canonical
- Sur `/fr/<page>` → canonical **doit pointer sur lui-même** (`/fr/<page>`), **jamais** sur `/en/…` ni sur la version sans langue. Idem `/en/<page>` → self.
- En mode sous-répertoire, l'URL portant déjà `/fr//en/`, RankMath devrait poser le bon canonical **sans shim** — **à VÉRIFIER**. Si canonical cross-lang ou sans langue → appliquer le **shim** (§1) ou NO-GO.

## 6. Comportement attendu des sitemaps FR/EN
- RankMath Free expose par défaut un index à **`/sitemap_index.xml`** (+ sous-sitemaps `page-sitemap.xml`, `product-sitemap.xml`, etc.).
- **Inconnu à vérifier** (finding §1) : en sous-répertoire Polylang, est-ce :
  - (a) **un seul** index contenant **les deux langues** mélangées, ou
  - (b) des index **par langue** accessibles (`/fr/sitemap_index.xml` + `/en/sitemap_index.xml`), ou
  - (c) un index ne contenant **qu'une** langue (bug connu).
- **Cible doctrine** : sitemaps séparés FR/EN. **Si non atteint en natif** → décision shim / arbitrage (§1). On **observe d'abord**.

## 7. Impact sur le noindex staging
- WP « discourage search engines » **reste activé** → tout le staging en noindex (voulu).
- RankMath **respecte** ce réglage (affiche un avis « moteurs découragés »).
- Le **sitemap se génère quand même** (utile pour la vérif structure), mais les URLs sont noindex → **aucun risque d'indexation**. Conforme.
- **Ne pas** lever le noindex sur staging.

## 8. Non-régression KH-107
- **Garantie forte** : le mu-plugin KH-107 redirige `/` **au chargement du mu-plugin**, **avant** que RankMath (extension) ne soit chargé → RankMath **ne s'exécute jamais** sur `/`. Aucune interférence possible sur la racine.
- Risque résiduel = module **Redirections** RankMath : **ne créer aucune règle** (surtout pas `/`).
- **Contrôle** : re-run `KH-104b-pre-tests.sh` après install → attendu **25/27 inchangé** (A sauf Vary, B, C, E verts ; A11+D2 exceptions connues). Le script vérifie aussi `/`, `/fr/`, `/en/`, wp-admin/json/login.

## 9. Matrice de vérification (post-install)

| # | Vérif | Attendu PASS | FAIL si |
|---|---|---|---|
| V1 | `/fr/` charge | 200 | ≠200 / redirection parasite |
| V2 | `/en/` charge | 200 | ≠200 |
| V3 | page WC FR (ex `/fr/boutique/`) | 200 | ≠200 |
| V4 | page WC EN (ex `/en/shop/`) | 200 | ≠200 |
| V5 | fiche produit test FR | 200 | ≠200 |
| V6 | fiche produit test EN | 200 | ≠200 |
| V7 | **canonical** `/fr/…` | self `/fr/…` | cross-lang ou sans langue ⇒ shim/NO-GO |
| V8 | **canonical** `/en/…` | self `/en/…` | cross-lang |
| V9 | **hreflang** (Polylang) sur FR/EN | `fr-FR` + `en`(/`en-US`) + `x-default`, pas de doublon | absent / doublon |
| V10 | **sitemap index** `/sitemap_index.xml` | 200, XML valide | 404 / vide / 1 seule langue |
| V11 | **sitemap pages** | 200, contient les pages | 404 / incohérent |
| V12 | **sitemap produits** | 200, contient le(s) produit(s) | 404 |
| V13 | sitemap couvre **FR et EN** (ou index par langue) | les 2 langues présentes | 1 seule langue ⇒ finding |
| V14 | `/` hors KH-107 | 302 `x-redirect-by: koino-lang-redirect` inchangé | toute autre redirection |
| V15 | **non-régression** `KH-104b-pre-tests.sh` | **25/27** inchangé | régression |
| V16 | Site Health | aucune nouvelle anomalie critique | erreur critique |

## 10. Critères GO / NO-GO

**GO KH-109** si :
- V1–V6 = 200 · V7/V8 canonical **self par langue** · V9 hreflang présent (Polylang) sans doublon · V10–V12 sitemaps 200 · V14 KH-107 intact · V15 = 25/27 · V16 OK.
- Sitemaps i18n (V13) : **acceptable** = les deux langues sont couvertes (index unique multilingue **ou** par langue). La structure exacte `/fr/sitemap.xml`+`/en/sitemap.xml` est un *plus*, pas un bloquant si les deux langues sont bien présentes et indexables correctement.

**NO-GO / décision** si :
- canonical cross-lang non corrigeable sans shim → tester le **shim** (§1) puis re-juger.
- sitemap ne couvre qu'**une** langue même avec shim → arbitrage Alain (shim avancé / changement plugin SEO).
- régression KH-107 (V14/V15).

## 11. Rollback
- RankMath = **une extension**. Rollback = **Désactiver** (puis Supprimer si besoin). Réglages stockés en base (options `rank_math_*`) → supprimés à la désinstallation.
- Si shim posé : **retirer** `rank-math-ppl.php` + `rank-math.php` du thème (versionnés repo → réversible par commit).
- mu-plugin KH-107 **jamais touché**. Permaliens : re-enregistrer si besoin (flush).
- Pas de prod, PR #1 non mergée.

## 12. Séquence
1. **GO Alain** → installer RankMath Free minimal (§2-3), garder noindex.
2. Vérifier matrice §9 (canonical/hreflang/sitemaps + KH-107 re-run).
3. Selon V7/V8/V13 → décider shim (oui/non) → re-vérifier.
4. **Verdict GO/NO-GO §10**.
5. Si GO → ouvrir **KH-104b-full** (10 URLs) → déblocage Lots 2-8.

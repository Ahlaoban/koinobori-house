# KH-109 — SEOPress / SEO i18n (Lot 1)

- **Ticket** : KH-109
- **Lot** : 1
- **Date plan** : 2026-06-13
- **Statut** : 🟡 **plan + diagnostic — AUCUNE action staging avant GO Alain**
- **Environnement** : **staging uniquement** · pas de prod · pas de merge PR #1 · pas de Polylang Pro · mu-plugin KH-107 non touché · pas d'import catalogue réel
- **Bloque** : KH-104b-full (ne pas ouvrir avant verdict KH-109)

## 0. Décision plugin SEO (2026-06-13) — RankMath → SEOPress

Évaluation comparative (sources officielles) avant install :

| | RankMath Free | Yoast Free | **SEOPress Free** |
|---|---|---|---|
| Compat Polylang | ❌ non native (shim manuel 2 fichiers PHP) | ✅ officielle | ✅ « seamless », guide dédié |
| Sitemap i18n | faible (bug 1 langue) | 1 sitemap combiné | **par langue** (auto) |
| hreflang / canonical par langue | shim requis | via Polylang / OK | géré / OK |

**Décision Alain : SEOPress (Free).** Motifs : sitemaps **par langue** (= doctrine `/fr/`+`/en/`), éditeur **français**, léger, intégration Polylang « seamless ». **CLAUDE.md mis à jour** (stack SEO = SEOPress). RankMath abandonné (incompat Polylang, l'éditeur l'admet). **Aucun coût de bascule** (RankMath jamais installé).

> ⚠️ Limite connue : le **gestionnaire de redirections** est dans SEOPress **Pro** (Free ne l'a pas). **Non requis MVP** (racine = mu-plugin KH-107 ; pas d'autres redirections planifiées). Si besoin futur → .htaccess ou SEOPress Pro.

Sources : [SEOPress + Polylang (guide officiel)](https://www.seopress.org/support/guides/seopress-polylang-multilingual-seo-guide/) · [KB RankMath/Polylang (non compat)](https://rankmath.com/kb/polylang-compatibility/) · [Yoast + Polylang](https://polylang.pro/how-to-make-yoast-seo-multilingual-with-polylang/).

## Objectif
Installer + configurer **SEOPress Free** sur staging et **valider le SEO i18n** (hreflang / canonical / sitemaps FR-EN par langue) avant KH-104b-full.

## 1. Procédure d'installation (staging) — APRÈS GO

1. Extensions → Ajouter → **« SEOPress »** (SEOPress – On-site SEO, éditeur SEOPress) → Installer → Activer.
2. **Assistant SEOPress** — minimal :
   - Compte / clé : **Skip** (pas requis en Free).
   - Type de site, nom : minimal.
   - **XML Sitemap** : **activer**.
   - **noindex** : **NE PAS** désactiver le « discourage search engines » de WP (staging reste noindex).
   - Pas de connexion Google Search Console, pas d'import depuis un autre plugin.
3. Ne **rien** configurer d'autre (pas de redirections — non dispo en Free de toute façon).

## 2. Réglages recommandés SEOPress (Polylang + WooCommerce)

| Réglage | Valeur | Raison |
|---|---|---|
| **XML / HTML Sitemap** → XML Sitemap | **ON** | vérifier structure i18n par langue |
| Sitemap : inclure **produits** (post type product) | ON | fiches produits |
| **Titles & Metas** | défauts | suffisant MVP |
| **hreflang / Multilingue** (si option SEOPress présente) | activer si proposé, sinon laisser Polylang | éviter doublon |
| « Discourage search engines » WP | **reste ON** (noindex) | staging |
| Redirections | (absent en Free) | rien à faire |

> SEOPress détecte Polylang automatiquement : chaque traduction a ses propres meta/canonical, et les sitemaps sont générés **par langue**.

## 3. Vigilance hreflang
- Vérifier dans le `<head>` de `/fr/` et `/en/` : `rel="alternate"` `hreflang="fr-FR"` + `hreflang="en"` (ou `en-US`) + `hreflang="x-default"`.
- **Source** : SEOPress et/ou Polylang. **Vérifier qu'il n'y a qu'UNE série** (pas de doublon SEOPress + Polylang). Si doublon → ajuster (désactiver le hreflang d'un des deux).
- Écart possible `en-US` vs `en` (doctrine `en`) → noter (mineur).

## 4. Vigilance canonical
- `/fr/<page>` → canonical **self** (`/fr/<page>`), jamais `/en/…` ni sans langue. Idem `/en/…` → self.
- En sous-répertoire, SEOPress + Polylang posent normalement le canonical par langue — **à VÉRIFIER**. Si cross-lang → ajuster réglages SEOPress (canonical auto) avant NO-GO.

## 5. Comportement attendu des sitemaps FR/EN
- SEOPress expose un sitemap (`/sitemap.xml` index + sous-sitemaps `post-sitemap.xml`, `page-sitemap.xml`, `product-sitemap.xml`).
- Avec Polylang : **génération par langue** attendue → cible `/fr/sitemap.xml` + `/en/sitemap.xml` (à confirmer à l'install — c'est l'avantage SEOPress vs RankMath).
- **Vérifier** : index accessible, sous-sitemaps pages + produits présents, **les deux langues couvertes**.

## 6. Impact sur le noindex staging
- WP « discourage search engines » **reste activé** → staging noindex (voulu).
- SEOPress respecte ce réglage. Sitemap généré quand même (utile pour la vérif structure) ; URLs noindex → **aucun risque d'indexation**.
- **Ne pas** lever le noindex sur staging.

## 7. Non-régression KH-107
- **Garantie forte** : le mu-plugin KH-107 redirige `/` **au chargement du mu-plugin**, **avant** que SEOPress (extension) ne soit chargé → SEOPress **ne s'exécute jamais** sur `/`. Aucune interférence racine possible.
- SEOPress Free n'a **pas** de gestionnaire de redirections → aucun risque de règle parasite sur `/`.
- **Contrôle** : re-run `KH-104b-pre-tests.sh` après install → attendu **25/27 inchangé** (A sauf Vary, B, C, E verts ; A11+D2 exceptions connues).

## 8. Matrice de vérification (post-install)

| # | Vérif | Attendu PASS | FAIL si |
|---|---|---|---|
| V1 | `/fr/` | 200 | ≠200 / redirection parasite |
| V2 | `/en/` | 200 | ≠200 |
| V3 | page WC FR (`/fr/boutique/`) | 200 | ≠200 |
| V4 | page WC EN (`/en/shop/`) | 200 | ≠200 |
| V5 | fiche produit test FR | 200 | ≠200 |
| V6 | fiche produit test EN | 200 | ≠200 |
| V7 | **canonical** `/fr/…` | self `/fr/…` | cross-lang / sans langue |
| V8 | **canonical** `/en/…` | self `/en/…` | cross-lang |
| V9 | **hreflang** FR/EN | `fr-FR` + `en`(/`en-US`) + `x-default`, **série unique** | absent / **doublon** |
| V10 | **sitemap index** | 200, XML valide | 404 / vide |
| V11 | **sitemap pages** | 200, contient pages | 404 |
| V12 | **sitemap produits** | 200, contient produit(s) | 404 |
| V13 | sitemaps **par langue** FR + EN | `/fr/sitemap.xml` + `/en/sitemap.xml` (ou index couvrant les 2) | 1 seule langue |
| V14 | `/` hors KH-107 | 302 `x-redirect-by: koino-lang-redirect` inchangé | toute autre redirection |
| V15 | **non-régression** `KH-104b-pre-tests.sh` | **25/27** inchangé | régression |
| V16 | Site Health | aucune nouvelle anomalie critique | erreur critique |

## 9. Critères GO / NO-GO

**GO KH-109** si : V1–V6 = 200 · V7/V8 canonical **self par langue** · V9 hreflang présent **sans doublon** · V10–V12 sitemaps 200 · **V13 les deux langues couvertes** · V14 KH-107 intact · V15 = 25/27 · V16 OK.

**NO-GO / ajustement** si : canonical cross-lang · hreflang en doublon (SEOPress+Polylang) · sitemap ne couvre qu'une langue · régression KH-107. → ajuster réglages SEOPress, re-vérifier ; si irréductible → arbitrage Alain.

## 10. Rollback
- SEOPress = **une extension**. Rollback = **Désactiver** (+ Supprimer si besoin) ; réglages en base (`seopress_*`) supprimés à la désinstallation.
- mu-plugin KH-107 **jamais touché**. Permaliens : re-enregistrer si besoin (flush).
- Pas de prod, PR #1 non mergée.

## 11. Séquence
1. **GO Alain** → installer SEOPress Free minimal (§1-2), garder noindex.
2. Vérifier matrice §8 (canonical/hreflang/sitemaps par langue + KH-107 re-run).
3. Ajuster réglages SEOPress si V7/V8/V9/V13 imparfaits → re-vérifier.
4. **Verdict GO/NO-GO §9**.
5. Si GO → ouvrir **KH-104b-full** (10 URLs) → déblocage Lots 2-8.

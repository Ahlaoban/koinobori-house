# KH-106 — Polylang for WooCommerce (Lot 1)

- **Ticket** : KH-106
- **Lot** : 1
- **Date ouverture** : 2026-06-13
- **Statut** : 🔴 **BLOQUÉ — NO-GO install** (licence non achetée + WooCommerce core absent)
- **Environnement cible** : **staging uniquement** (pas de prod)
- **Bloque** : KH-104b-**full** → déblocage Lots 2-8

## 1. Objectif

Installer et valider **Polylang for WooCommerce** sur staging pour préparer KH-104b-full (10 URLs WC FR/EN). Périmètre : install + config + réglages de traduction + non-régression + **1 produit test variable** pour valider la sync stock/variations (KH-010). **Pas** d'import catalogue, pas de RankMath/Stripe/PayPal/Brevo, pas de prod.

## 2. Blocages actuels (décisions Alain 2026-06-13)

| Prérequis | Statut | Conséquence |
|---|---|---|
| **Licence Polylang for WooCommerce** (payant) | ❌ **pas achetée** | impossible de télécharger/activer le plugin → KH-106 ne peut pas démarrer |
| **WooCommerce core** (gratuit) | ❌ pas installé · ✅ **install autorisée maintenant** (étape 0, indépendante de la licence) | de-risque les conflits ; add-on inopérant sans lui |

## 3. Spec d'achat (vérifiée source officielle polylang.pro, juin 2026)

- ✅ **Polylang for WooCommerce fonctionne avec Polylang _Free_** — Pro non requis. Officiel : *« add-on that must be used in combination with Polylang Pro or Polylang »*. Notre stack (Polylang Free + add-on WC) est valide.
- ✅ **Décision Alain 2026-06-13** : acheter l'**add-on « Polylang for WooCommerce »** (~99 €/an HT), **pas** Polylang Pro / Business Pack. Conforme stack verrouillé. (Clarification : « Pro » ≠ « for WooCommerce » ; c'est l'add-on WC qui traduit produits/variations + slugs WC `/boutique`÷`/shop`, `/produit`÷`/product`.)
- 💶 **Acheter** : produit **« Polylang for WooCommerce » (standalone)** — *à partir de 99 €/an HT* (1 an de mises à jour + support).
- ❌ **Ne PAS** prendre le **Business Pack** (Pro + WC, 139 €/an HT) : inutile, on ne veut pas Polylang Pro (hors stack).
- ⚠️ Au moment de l'achat, **vérifier dans le changelog Polylang for WC** la compat **WP 7.0 / PHP 8.3** (staging) — [changelog](https://polylang.pro/polylang-for-woocommerce-changelog/).
- 📄 Doc install officielle : [Installation of the Polylang for WooCommerce addon](https://polylang.pro/documentation/support/guides/polylang-wc-installation/).

Sources : [pricing](https://polylang.pro/pricing/) · [produit WC](https://polylang.pro/pricing/polylang-for-woocommerce/) · [doc install](https://polylang.pro/documentation/support/guides/polylang-wc-installation/).

## 4. Séquence d'exécution prévue (post-achat — à exécuter par Alain sur staging)

> Je n'ai pas d'accès o2switch → install par Alain dans WP admin, je fournis la procédure + checklist + interprétation (méthode KH-104b-pré).

0. **WooCommerce core** : installer + activer (assistant WC minimal, **sans produits**, mode hors-ligne, pas de paiement configuré). Vérifier non-régression `/`, `/fr/`, `/en/`, KH-107.
1. **Polylang for WooCommerce** : téléverser le zip (compte polylang.pro), activer, saisir la clé de licence.
2. **Config traduction** : activer traduction produits / catégories produits / attributs / variations.
3. **1 produit test variable minimal** (≠ import catalogue) : ex. attribut global `Taille` (50/75), 2 variations, stock par variation → valider la sync stock/variations conforme au modèle KH-010 (parent variable + variations, cf `catalog/master.csv` structure Hanami).
4. **Validation** (critères §5) + captures.

## 5. Critères de réussite (GO) — à valider en exécution

**Installation**
- [ ] Plugin installé, version notée, actif, licence validée
- [ ] Compatible WP 7.0 / PHP 8.3, **aucun warning PHP**
- [ ] Aucun conflit avec : Kadence · Wordfence · UpdraftPlus · Polylang Free

**Fonctionnel**
- [ ] WooCommerce détecté correctement par l'add-on
- [ ] Traduction produits / catégories / attributs / **variations** activée (réglages)
- [ ] Sync stock/variations conforme KH-010 (sur le produit test)

**Technique / non-régression**
- [ ] Site Health : aucune erreur critique
- [ ] logs PHP : aucune erreur
- [ ] console navigateur : aucune erreur
- [ ] `/`, `/fr/`, `/en/`, **KH-107** : aucune régression (re-run `KH-104b-pre-tests.sh` attendu inchangé : 26/27, exception A11)

## 6. Livrables à la clôture
version exacte plugin · captures/résultats validation · liste conflits détectés · verdict GO/NO-GO · impacts sur KH-104b-full.

## 7. Impacts sur KH-104b-full
KH-104b-full (10 URLs WC FR/EN + attribut Taille) **dépend directement** de KH-106 GO. Tant que KH-106 bloqué (licence), full reste bloqué, et Lots 2-8 restent bloqués.

## 8. Hors périmètre KH-106 (ne pas ouvrir)
KH-104b-full · import catalogue · RankMath (KH-109) · Stripe · PayPal · Brevo · mise en production.

## 9. Déblocage
**Action unique requise** : acheter la licence **Polylang for WooCommerce** (§3) — décision/achat Alain, hors code. Dès la clé en main → exécuter §4-5.

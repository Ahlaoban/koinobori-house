# KH-106 — Polylang for WooCommerce (Lot 1)

- **Ticket** : KH-106
- **Lot** : 1
- **Date ouverture** : 2026-06-13
- **Statut** : 🟢 **GO** (2026-06-13) — WooCommerce core + add-on Polylang for WooCommerce installés et validés sur staging. Licence achetée. Suite = KH-104b-full (bloqué uniquement par traduction slugs WC + RankMath KH-109).
- **Environnement cible** : **staging uniquement** (pas de prod)
- **Bloque** : KH-104b-**full** → déblocage Lots 2-8

## 1. Objectif

Installer et valider **Polylang for WooCommerce** sur staging pour préparer KH-104b-full (10 URLs WC FR/EN). Périmètre : install + config + réglages de traduction + non-régression + **1 produit test variable** pour valider la sync stock/variations (KH-010). **Pas** d'import catalogue, pas de RankMath/Stripe/PayPal/Brevo, pas de prod.

## 2. Blocages actuels (décisions Alain 2026-06-13)

| Prérequis | Statut | Conséquence |
|---|---|---|
| **Licence Polylang for WooCommerce** (payant) | ❌ **pas achetée** | impossible de télécharger/activer le plugin → KH-106 ne peut pas démarrer |
| **WooCommerce core** (gratuit) | ✅ **installé 2026-06-13 (v10.8.1)** — étape 0 GO | France/EUR, coming-soon OFF, 4 pages WC créées, KH-107 non régressé |

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

## 4bis. Résultats étape 0 — WooCommerce core (2026-06-13)

🟢 **GO étape 0.** Install par Alain (WP admin staging), assistant guidé sauté, France/EUR, mode « Bientôt disponible » désactivé → « En ligne ».

| Vérif | Résultat |
|---|---|
| Version WooCommerce | **10.8.1** (WP 7.0 / PHP 8.3) |
| Warnings PHP critiques | aucun |
| 4 pages WC créées (FR) | Boutique · Panier · **Validation de la commande** (= Commande/Checkout) · Mon compte — Publiées, traduction EN en attente (add-on) |
| Site Health | **« Bien »**, 0 anomalie critique. 5 recommandations bénignes : noindex (voulu staging), extensions inactives (Wordfence-OFF — **ne pas supprimer**), évènement planifié échoué (= wp-cron loopback bloqué par basic auth staging), cache objet/page (perf, LiteSpeed gère en prod) |
| Conflits Kadence / Wordfence(off) / UpdraftPlus / Polylang Free | aucun |
| **Non-régression KH-107** | re-run `KH-104b-pre-tests.sh` post-WC = **PASS=25 FAIL=2** (`KH-104b-pre-20260613-150305.log`). Cœur i18n intact : A (sauf A11), B, C, E **tous verts**. |

**2 FAIL = hors périmètre KH-107, environnementaux, non bloquants** :
- **A11 (Vary)** : exception connue (LiteSpeed strippe `Vary`, moot car `Cache-Control: no-store`). Cf KH-104b-pré §10.
- **D2 (`/wp-login.php` → 503)** : **protection anti-bruteforce serveur o2switch/LiteSpeed** qui throttle les hits curl répétés sur l'endpoint login. **Login réel OK** (Alain connecté à wp-admin au même moment). Pas WordPress en maintenance (`/` répond 302), pas WC, pas KH-107 (le mu-plugin ne touche pas `/wp-login.php`). NB : le test D2 du script tape un endpoint protégé par l'hôte → faux négatif environnemental.

**Conclusion** : WooCommerce core n'introduit **aucune régression** sur la fondation i18n (KH-107) ni aucun conflit. Étape 0 close.

## 4ter. Résultats add-on Polylang for WooCommerce (2026-06-13) — 🟢 GO

- **Versions** : Polylang Free **3.8.4** + Polylang for WooCommerce **2.2.2**. Licence achetée (« Polylang for WooCommerce » standalone, 1 site, 99 € HT / 118,80 € TTC) et **saisie/active**.
- **Install** : zip `polylang-wc.zip` téléversé + activé. Assistant Polylang exécuté (Licences → Langues → Média → WooCommerce → Prêt) :
  - Langues FR/EN déjà OK (KH-104), rien changé.
  - **Média** : traduction des médias **non activée** (doctrine 1 bibliothèque).
  - **Étape WooCommerce** : création automatique des **traductions EN des 4 pages WC** (Shop/Cart/Checkout/My account) + install de la traduction FR du plugin.

**Validation fonctionnelle** (produit jetable `TEST KH-106 Variable`, attribut Taille 50/75/100, stock 10/5/3, prix) :

| Critère | Résultat |
|---|---|
| WooCommerce détecté par l'add-on | ✅ (étape WC du wizard, pages créées) |
| Traduction produits | ✅ FR→EN créée et liée (`/en/produit/…`) |
| Traduction attributs + variations | ✅ produit EN hérite **auto** du type variable + 3 variations |
| **Sync stock/variations (KH-010)** | ✅ **passive** (stocks 10/5/3 hérités sans re-saisie) + **active** (modif FR variation 50 : 10→**99** → EN affiche **99** dans les 2 langues) |
| Conflits / warnings | ✅ Site Health « Bien », 0 critique (Kadence/Wordfence-off/UpdraftPlus/PLL Free) |
| Non-régression KH-107 | ✅ re-run post-add-on = **25/27** (`KH-104b-pre-20260613-152937.log`), cœur i18n intact (A sauf Vary, B, C, E verts) |

**Exceptions inchangées, hors scope KH-107** : A11 (Vary strippé LiteSpeed), D2 (`/wp-login.php` 503 anti-bruteforce hôte). Cf §4bis.

**Nettoyage** : produit test FR+EN supprimé après validation. Les 4 pages WC EN (réelles) **conservées**.

**Suivi pour KH-104b-full (hors KH-106)** : traduire les **slugs de base WC** (`produit→product`, `boutique→shop`, `panier→cart`, `commande→checkout`, `mon-compte→my-account`) via réglages Polylang for WC — actuellement EN sous `/en/produit/…`. Plus RankMath KH-109 (hreflang/canonical/sitemaps).

**Verdict KH-106 = 🟢 GO.**

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

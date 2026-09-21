# Analyse de l'inventaire staging du 2026-09-17

Source : `staging-inventory-20260917T091737Z.public.json` (SHA-256 `8be47d89…d038`). Lecture seule ; aucune correction effectuée. Comparaison avec CLAUDE.md, le rapport de revue PR #14 et l'état du clone privé décrit par Astra le 16/09.

## 1. Constats bloquants ou doctrinaux

| # | Constat | Preuve (inventaire) | Conséquence | Lot |
|---|---|---|---|---|
| I1 | **Chine dans une liste publique** : formulaires 7 (Entreprises FR) et 8 (Business EN), champ `pays`/`country` = `select_country` (liste ISO complète) | `forms[7].fields[4]`, `forms[8].fields[4]` | Violation de la doctrine « la Chine n'est jamais citée » sur le staging ; le clone seul est corrigé (`prepare_enquiry_fields.php`) | corrections PC4, A5 |
| I2 | `polylang.redirect_lang = false` (**mesuré**) | `options.polylang` | Effet HTTP attendu : `/fr/` et `/en/` servent l'index du blog (constat du 10/09 ; **résultat HTTP actuel à retester** avant et après correction, sans exiger le marqueur `kh-home-title` d'un thème encore absent du staging) | PC4, `docs/development/polylang-reglages-requis.md` |
| I3 | **Pages légales non appariées** dans Polylang : 232 ↔ 234 (mentions légales), 235 ↔ 236 (cookies), 230 ↔ 231 (livraison USA) ont `translations: []` | `pages` | Sans appariement, le footer EN retombe sur la page FR (repli B3) et hreflang manquant ; `wp_page_for_privacy_policy = 252` OK | PC4 |
| I4 | **Notifications actives sur les 8 formulaires** (`notifications_enabled_any = true`, **mesuré**). FluentSMTP 2.3.1 présent ; **sa configuration n'est pas mesurée** (section `smtp_settings` exclue) : le constat « non configuré » date du 10/09 et reste **incertain** aujourd'hui | `forms[*]`, `plugins` | Hypothèse, à vérifier : sans transport configuré, les envois passent par `mail()` PHP ou échouent silencieusement. Sur le clone, les 12 modèles sont désactivés | A9, PC7 |
| I5 | `blogname` **vide** | `options.blogname` | Titre du site absent (balise `<title>`, Schema Organization, emails) | PC4 |
| I6 | Aucune zone d'expédition hors France : « Rest of the world » sans méthode ; `woocommerce_ship_to_countries = ""` (tous pays) | `shipping_zones`, `options` | Pour une adresse hors France, WooCommerce ne propose aucune méthode : **livraison indisponible, commande bloquée au checkout** (comportement documenté des zones sans méthode) | PC4 : zone UE (Colissimo Zone A + MR), USA « nous contacter » = restreindre `ship_to_countries` ; **aucune activation de pays ni de méthode sans tarifs et périmètre approuvés** |

## 2. Écarts entre staging et branche PR #14 / clone

| # | Constat | Preuve | Note |
|---|---|---|---|
| E1 | Thème enfant **1.1.0** sur staging ; aucun `kh-product-media`, aucun emplacement `kh_header` ; menus 144 (FR) affecté à `primary` + `mobile` **Kadence**, 145 (EN) **non affecté** | `versions`, `menus[*].locations` | Le header/footer/homepage de PR #14 ne sont **pas** sur le staging : seul le clone les porte. Déploiement = PC5 |
| E2 | Menus 144/145 = **7 entrées** avec Accueil et Arts de vivre (`object_id` 282/291) | `menus` | À recréer à 5 entrées (A10) avant/lors du déploiement ; le walker corrigé (B1) tolère désormais l'assignation directe |
| E3 | `mu_plugins` = `koino-lang-redirect` 1.1.0 seul : `koino-diag-frontpage.php` **absent** du staging | `mu_plugins` | B4 côté staging = seulement `redirect_lang` |
| E4 | FluentSMTP **2.3.1** (clone : 2.4.0) | `plugins` | Mise à jour au déploiement |
| E5 | `kh-single-variation-display` 1.0.0 | `plugins` | Patch 1.1.0 non déployé (attend tests) |

## 3. Catalogue

| # | Constat | Preuve |
|---|---|---|
| C1 | 35 produits = 17 FR + 17 EN appariés + **1 brouillon orphelin** `KH-MOT-4` (id 200, `lang: en`, sans traduction, sans variation) | `products` |
| C2 | Sans image : Mer ×6 et Motifs ×3 (FR et EN) = 18 fiches, conforme au constat Astra | `has_thumbnail` |
| C3 | **SKU de variation vides** pour Hanami (112/114), Mer (×12) et Motifs (×6) ; seuls Territoires 002/003 portent `KH-TER-00x-050/075` | `variations[].sku` | Doctrine SKU `KH-<COLL>-<NNN>-<TAILLE>` non appliquée aux variations ; recodage à planifier avec le conflit `master.csv` |
| C4 | Kaïro ×4 et Stars & Stripes = `simple` (une taille), cohérent | `type` |
| C5 | `gallery_count = 0` partout : aucune galerie | |

## 4. Configuration

| # | Constat | Preuve |
|---|---|---|
| K1 | `litespeed.conf.cache = false` : cache page désactivé (cohérent avec l'interface du 10/09 « LSCache indisponible ») | `options` |
| K2 | Sitemaps SEOPress : **état indéterminé, relevé à refaire**. Le script du 17/09 lisait la clé `xml_sitemap_general_enable` alors que SEOPress stocke `seopress_xml_sitemap_general_enable` dans l'option (corrigé après revue Codex) ; le `null` du JSON du 17/09 n'est pas une mesure. Ne rien activer sur cette base | `options` |
| K3 | Complianz : **état indéterminé, relevé à refaire**. Le script du 17/09 lisait `cmplz_wizard_completed`, option que Complianz n'écrit pas (la vraie est `cmplz_wizard_completed_once`, cf `settings/settings.php` du plugin) ; le `false` du JSON du 17/09 n'est pas une mesure (corrigé après revue du 21/09, une option absente vaut désormais `null`) | `options` |
| K4 | `woocommerce_calc_taxes = no` (franchise TVA, conforme) ; `EUR` ; pays par défaut `FR` | `options` |
| K5 | Action Scheduler : `fetch_patterns` **14 échecs** (WooCommerce patterns, réseau sortant bloqué ?) | `action_scheduler` |
| K6 | `posts.count = 0` : Lifestyle vide | `posts` |
| K7 | Pages parasites : 78 `remboursements_retours` (brouillon WC), 182 « Validation du code PR1 », 184 sans titre (brouillons de juillet) | `pages` |
| K8 | Langues `fr_FR` + `en_US` ; `hide_default = false`, `force_lang = 1` conformes | `languages`, `options.polylang` |

## 4 bis. Particularité de l'export du 17/09

`manifest.sha256_public` (`6c54feb1…df8f`) dans le JSON committé est l'empreinte du document **avant** insertion de ce champ, pas celle du fichier final : c'est le fichier externe `.sha256` (`8be47d89…d038`) qui fait foi. Les versions suivantes des scripts n'écrivent plus d'empreinte interne. L'export historique est conservé tel quel.

## 5. Ce que l'inventaire ne dit pas

Contenu des pages 282/291 (audit A11), réglages Stripe/PayPal/SMTP, journaux Wordfence, quantités de stock, contenu des commandes et soumissions : volontairement exclus.

## 6. Proposition d'ordre pour le lot de corrections (PC4, après revue Codex et sauvegarde vérifiée)

1. I1 pays texte libre (7/8) — doctrine.
2. I2 `redirect_lang = 1`.
3. I3 appariements Polylang 232↔234, 235↔236, 230↔231.
4. I5 `blogname`.
5. I6 zones d'expédition (UE) + restriction pays.
6. K2 sitemaps SEOPress (après nouveau relevé), K3 Complianz, K1 LiteSpeed (chacun = point de contrôle 4).
7. I4 notifications : import des 12 modèles désactivés + SMTP, activation seulement au PC7.

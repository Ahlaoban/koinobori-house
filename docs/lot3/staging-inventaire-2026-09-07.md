# Staging : inventaire des pages et formulaires posés le 2026-09-07

Mesuré sur `staging.koinoborihouse.com` en fin de session. Source des textes : `docs/lot3/pages-confiance-FR-EN.md` et `docs/lot0/KH-017-documents-legaux/` (CGV v2026-09-07). Toutes les paires sont liées dans Polylang (hreflang vérifié dans les deux sens).

## Pages

| Page | FR (id, slug) | EN (id, slug) |
|---|---|---|
| Livraison | 248 `/fr/livraison/` | 258 `/en/shipping/` |
| Livraison USA | 230 `/fr/livraison-usa/` | 231 `/en/shipping-to-usa/` |
| Retours et rétractation (formulaire de rétractation en ligne) | 249 `/fr/retours/` | 263 `/en/returns/` |
| Contact (formulaire) | 250 `/fr/contact/` | 266 `/en/contact-us/` |
| Sur-mesure | 251 `/fr/sur-mesure/` | 269 `/en/custom/` |
| Mentions légales | 232 `/fr/mentions-legales/` | 234 `/en/legal-notice/` |
| CGV v2026-09-07 (encadré D. 211-2 inclus) | 238 `/fr/conditions-generales-de-vente/` | 276 `/en/terms-and-conditions/` |
| Politique de confidentialité | 252 `/fr/politique-de-confidentialite/` | 272 `/en/privacy-policy/` |
| Politique de cookies | 235 `/fr/politique-cookies/` | 236 `/en/cookie-policy/` |

Slugs EN `contact-us` et `privacy-policy` : WordPress impose l'unicité des slugs entre langues (Polylang Free) ; le brouillon WordPress par défaut « Privacy Policy » (id 3) a été mis à la corbeille pour libérer `privacy-policy`.

**Manquent encore** : Professionnels (aiguillage), Entreprises, Collectivités, Arts de vivre (« bientôt »), L'Atelier, Lifestyle & Koi, homepage 11 mouvements, menu 7 entrées, footer 2 étages.

## Formulaires Fluent Forms

| Usage | FR | EN | Notifications |
|---|---|---|---|
| Contact | 5 (page 250) | 6 (page 266) | interne → contact@koinoborihouse.com + accusé de réception client |
| Entreprises / Business (B2B) | 7 | 8 | idem, à poser sur `/fr/entreprises/` et `/en/business/` |
| Collectivités / Institutions (B2G) | 9 | 10 | idem, à poser sur `/fr/collectivites/` et `/en/institutions/` |
| Rétractation / Withdrawal | 11 (page 249) | 12 (page 263) | interne + accusé de réception valant support durable (art. 10.3 CGV) |

Champs conformes à CLAUDE.md §Champs formulaires. **Correction du 2026-09-08** : suite à l'arbitrage « la Chine n'est jamais citée », le champ des formulaires 9 et 10 est passé de « Lien culturel avec le Japon, la Chine ou l'Asie » à « Lien culturel avec le Japon ou l'Asie » (EN : « Cultural link with Japan or Asia, if relevant »). Enregistré et vérifié après rechargement. Chaque formulaire porte une case de consentement RGPD liée à la politique de confidentialité. Bouton vermillon `#C8311A`. Les emails partent via `wp_mail` tant que FluentSMTP n'est pas connecté à Brevo.

## Extensions activées ce jour

Stripe Gateway (compte à connecter en mode test par Alain), FluentSMTP (clé Brevo à saisir par Alain), Fluent Forms, Complianz (assistant à dérouler), LiteSpeed Cache (cache ON, racine `^/$` exclue, mobile ON), Wordfence (licence gratuite à enregistrer par Alain). Réglages : page CGV WooCommerce = 238 (case d'acceptation au checkout), page de confidentialité WordPress = 252 (Polylang sert 272 en EN).

---

## Ajouts du 2026-09-08

Source des textes : [pages-professionnels-arts-de-vivre-FR-EN.md](pages-professionnels-arts-de-vivre-FR-EN.md). Toutes les paires sont liées dans Polylang, hreflang vérifié dans les deux sens.

### Pages

| Page | FR (id, slug) | EN (id, slug) | Formulaire |
|---|---|---|---|
| Professionnels (aiguillage) | 279 `/fr/professionnels/` | 285 `/en/professionals/` | aucun (A-6) |
| Entreprises / Business | 280 `/fr/entreprises/` | 287 `/en/business/` | Fluent Forms 7 / 8 |
| Collectivités et institutions | 281 `/fr/collectivites/` | 289 `/en/institutions/` | Fluent Forms 9 / 10 |
| Arts de vivre | 282 `/fr/arts-de-vivre/` | 291 `/en/art-de-vivre/` | aucun |
| L'Atelier / The House | 283 `/fr/atelier/` | 293 `/en/the-house/` | aucun |
| Lifestyle & Koi | 284 `/fr/lifestyle/` | 295 `/en/lifestyle-koi/` | aucun |

⚠️ Le slug EN de Lifestyle est `lifestyle-koi` et non `lifestyle` : WordPress impose l'unicité des slugs entre langues sous Polylang Free, le slug FR occupait déjà `lifestyle`. Même cause que `contact-us`. [ux-architecture.md](../ux-architecture.md) §3.1 a été corrigé en conséquence.

### Menus

| Menu | id | Emplacements Polylang | Entrées |
|---|---|---|---|
| Principal FR | 144 | Principal Français + Mobile Français | Accueil · Boutique · Arts de vivre · Lifestyle · L'Atelier · Professionnels · Contact |
| Principal EN | 145 | Principal English + Mobile English | Home · Shop · Art de Vivre · Lifestyle · The House · For Professionals · Contact |

Accueil et Home sont des liens personnalisés vers `/fr/` et `/en/`, la page d'accueil restant à construire. Rendu public vérifié : les deux navigations affichent bien 7 entrées dans la bonne langue. Le footer à 2 étages n'est pas posé, il relève du thème enfant (Manus).

### Métadonnées SEO

Les 12 pages portent un `_seopress_titles_title` et un `_seopress_titles_desc`, écrits par REST et vérifiés dans le rendu public (`<title>` et `<meta name="description">` présents sur les 12 URLs).

### Audit doctrine sur les pages en ligne

Balayage des 12 URLs sur le contenu rendu : 0 mention de fabrication, production, atelier partenaire ou « fabriqué en France » ; 0 mention de frais Stripe, PayPal, commission ou droits de douane offerts ; 0 référence au volume global de stock.

⚠️ **Une exception à connaître pour l'audit KH-707** : `/fr/entreprises/` et `/en/business/` contiennent le mot « Chine » / « China », uniquement parce que le champ **Pays** du formulaire B2B est une liste déroulante ISO qui énumère tous les pays du monde (`<option value='CN'>Chine`). Ce n'est pas du contenu éditorial. Deux options si tu veux un grep parfaitement propre : restreindre la liste des pays du formulaire, ou exclure les balises `<option>` du grep d'audit. Les pages B2G ne sont pas concernées, leur champ « Pays / ville » est un champ texte libre.

### À déployer sur staging (fichier, pas réglage)

`wp/mu-plugins/koino-bcdg-hyphen.php` (nouveau, 2026-09-08) corrige la signature des fiches produits. WordPress applique `wptexturize()` aux titres, et cette fonction convertit « espace tiret espace » en tiret demi-cadratin. Les 34 fiches saisies « … Koinobori - by BCDG » s'affichent donc « … – by BCDG », ce qui contredit la décision Alain du 2026-06-15 (trait d'union simple, jamais de tiret cadratin dans les fiches).

Le mu-plugin réécrit la seule occurrence fautive après coup, en priorité 20 sur `the_title`, `single_post_title` et `document_title_parts`. Il ne désactive pas `wptexturize`, dont le filtre officiel `run_wptexturize` est global et ferait perdre apostrophes typographiques et guillemets français sur tout le site. Il ne touche à aucun autre tiret.

Choix d'un mu-plugin plutôt que `functions.php` : le thème enfant est en cours de réécriture par Manus (PR #12, 65 lignes de `functions.php` modifiées), isoler évite le conflit.

⚠️ **Non déployé, non vérifié sur staging** : la session du 2026-09-08 a perdu l'authentification HTTP du staging avant de pouvoir téléverser le fichier et contrôler le rendu. À faire à la reprise : déposer le fichier dans `wp-content/mu-plugins/`, puis vérifier qu'un titre de fiche affiche bien `- by BCDG` en FR et en EN.

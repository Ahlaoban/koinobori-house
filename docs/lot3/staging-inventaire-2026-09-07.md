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

---

## Ajouts du 2026-09-08, seconde session

### Image Sakura Rouge

Média **317** (`hanami-001-sakura-rouge`), téléversé depuis `catalog/images/hanami/001-sakura-rouge/main.jpg`, texte alternatif renseigné, posé comme image principale sur les produits **111 (FR)** et **113 (EN)**. Il reste **18 fiches sans image**, soit 9 produits (Mer 6, Motifs 3) dans les deux langues : ce sont les 9 photos attendues d'Alain. Le chiffre de 20 relevé le 2026-09-07 incluait Sakura FR et EN, désormais illustrés.

### Page d'accueil

| Page | FR | EN |
|---|---|---|
| Accueil | 318 `accueil` — « Des carpes de vent originales, signées BCDG » | 319 `home` — « Original wind carps, signed by BCDG » |

Contenu conforme à [homepage-v2-11-mouvements-FR-EN.md](homepage-v2-11-mouvements-FR-EN.md) : mouvements 2, 3, 4, 5, 7, 8 et 9. Le mouvement 6 (Lifestyle & Koi) est omis tant qu'aucun article n'est publié, le mouvement 10 (newsletter) tant que Brevo n'est pas connecté. Métadonnées SEO posées sur les deux. Les deux pages sont liées dans Polylang. `show_on_front = page` et `page_on_front = 318`.

### 🔴 Anomalie non résolue : `/fr/` et `/en/` rendent toujours l'index de blog

**Ce qui est vérifié bon** : le contenu et le routage par identifiant. `/?page_id=318` rend `body class="home page page-id-318"` avec les six titres de section attendus, et `/?page_id=319` fait de même en anglais. Les réglages sont corrects, contrôlés côté REST **et** côté formulaire : `show_on_front = page`, `page_on_front = 318`. La langue des deux pages est correcte dans la liste des pages, et la paire est liée.

**Ce qui échoue** : les URL propres. `/fr/` et `/en/` rendent `body class="home blog"`, c'est-à-dire l'index de blog vide. WordPress connaît pourtant la page d'accueil, puisque `/fr/accueil/` est redirigé en canonique vers `/fr/`. La réécriture de `/fr/` ne produit donc pas `page_id=318`.

**Écarté par le test** : le cache (aucun en-tête `x-litespeed-cache`, purge effectuée, rendu identique en session connectée), les réglages (justes des deux côtés), la langue des pages (correcte), et la détection de langue du navigateur de Polylang, qui est **déjà désactivée** conformément à la doctrine, KH-107 possédant la racine.

**Tenté sans effet** : vidage des permaliens à deux reprises, aller-retour complet du réglage de lecture par le formulaire (articles récents, enregistrer, page statique, enregistrer) pour forcer le déclenchement des hooks `update_option`.

> 🔄 **Diagnostic révisé le 2026-09-09. La piste ci-dessous était fausse, je la laisse barrée pour que personne ne la reprenne.**
>
> ~~1. Le mu-plugin KH-107 s'exécute avant Polylang et appelle `home_url()`. Le renommer en `.php.off` par FTP tranche en une minute.~~
>
> **Réfuté par lecture du fichier**, qui était sur le disque depuis le début : `wp/mu-plugins/koino-lang-redirect.php` n'enregistre **aucun hook** (0 `add_filter`, 0 `add_action`) et sort immédiatement dès que le chemin demandé diffère de la racine. Il ne peut pas influencer la réécriture de `/fr/`. Le test par FTP aurait coûté une récupération d'accès pour une réponse déjà disponible.

**Ce que la preuve dit réellement**

Le corps des pages `/fr/` et `/en/` porte `class="home blog"`. Le cœur de WordPress n'émet ces deux classes ensemble que dans un cas : `is_front_page()` et `is_home()` vrais simultanément, ce qui n'arrive que par la première branche de `is_front_page()`, celle qui teste `'posts' === get_option( 'show_on_front' )`.

Autrement dit, **à l'exécution sur ces URL, `show_on_front` vaut `posts`**, alors que la base de données et le formulaire d'administration disent tous deux `page` avec `page_on_front = 318`. L'écart entre la valeur stockée et la valeur lue à l'exécution ne peut venir que d'un filtre sur l'option.

**Piste n°1 révisée : la couche pages statiques de Polylang**

`PLL_Static_Pages` filtre `option_show_on_front` et renvoie `posts` lorsqu'il n'arrive pas à résoudre la page d'accueil **pour la langue courante**. Le symptôme correspond exactement. Polylang porte `page_on_front` sur ses objets langue, construits puis mis en cache.

Or le réglage a d'abord été posé **par REST**, hors contexte d'administration : la classe `PLL_Admin_Static_Pages`, qui nettoie ce cache quand l'option change, n'était pas chargée. Le formulaire a ensuite été enregistré avec la même valeur, donc `update_option` n'a rien changé et le hook n'a pas tiré non plus.

**Contre-indice à lever en premier** : `/fr/accueil/` semblait rediriger vers `/fr/`, ce que Polylang ne ferait pas s'il ignorait la page d'accueil. Cette observation vient de la barre d'adresse, elle n'a jamais été vérifiée proprement. À trancher par un `fetch` avec `redirect: 'manual'` avant tout le reste.

**Actions à tenter, dans cet ordre, toutes depuis wp-admin, aucune par FTP**

1. Vérifier la redirection de `/fr/accueil/` en lecture seule, statut et en-tête `Location`.
2. Réglages → Lecture : choisir **une autre page** comme page d'accueil, enregistrer, puis revenir à 318 et enregistrer. Contrairement à l'aller-retour articles/page déjà tenté, cela change réellement la **valeur** de `page_on_front` et déclenche le nettoyage du cache des langues côté Polylang.
3. Si cela ne suffit pas, forcer la reconstruction du cache des langues : Langues → survoler la ligne Français → Modifier → enregistrer sans rien changer.
4. En dernier recours seulement, supprimer le transient `pll_languages_list`, ce qui demande un accès base ou WP-CLI.

**Pistes secondaires, si la précédente tombe**

- Un `home.php` ou `front-page.php` résiduel dans le zip du thème enfant déployé, absent du dépôt.
- Un conflit de règles de réécriture avec Polylang for WooCommerce sur la langue racine.

En attendant, **aucune régression** : la page d'accueil affichait déjà l'index de blog avant cette session. Le contenu est prêt et n'attend que la résolution du routage.

---

## Sauvegarde de staging — vérifiée et renforcée le 2026-09-09

`docs/lot1/KH-102-sauvegardes.md` affirmait que staging n'était **pas couvert** par UpdraftPlus, décision du 2026-06-12 quand l'environnement était vide. La revue de code du 2026-09-09 en avait tiré son constat le plus grave : le Lot 3 vivrait sans filet. **Vérification faite sur le site, c'est faux.**

État réel de l'écran UpdraftPlus de staging, toutes les archives portant l'icône Google Drive :

| Date | Contenu |
|---|---|
| 09/09 17h57 | **Complète** : base, extensions, thèmes, téléversements, mu-plugins, autres. Déclenchée manuellement, **rétention manuelle** activée |
| 09/09, 08/09, 07/09, 06/09, 04/09 à 9h30 | Base de données |
| 05/09 9h30, 03/09 17h04, 29/08 9h30 | Complètes |

Planification active : base le jeudi, fichiers le samedi. Le travail du Lot 3 des 07 et 08 septembre était donc déjà couvert par les sauvegardes quotidiennes de base.

**Ce qui reste vrai du constat, et qui compte** : staging n'est plus un environnement jetable. Il détient l'unique copie des 26 pages, des 8 formulaires, des 2 menus, des métadonnées SEO et des appariements Polylang. Le clonage **prod vers staging**, exécuté deux fois en juin et documenté comme la procédure normale, effacerait tout. Ne plus le lancer par réflexe.

La sauvegarde du 09/09 17h57 est le **point de restauration de référence** de l'état Lot 3, protégée de la rotation.

### Ce qui n'est toujours pas rejouable en production

Une sauvegarde protège staging ; elle ne transporte rien vers la prod. Restent à produire, et ce n'est pas fait :

- un export WXR des pages et des menus, ou un script de recréation ;
- l'export JSON des 8 formulaires Fluent Forms, dont les définitions de champs n'existent nulle part dans le dépôt ;
- une procédure de déploiement de `wp/mu-plugins/` et `wp/themes/`, aujourd'hui éclatée dans deux tickets du Lot 1 et jamais généralisée ;
- un relevé de ce qui est réellement déployé où, personne ne pouvant dire aujourd'hui si KH-107 tourne en production.

⚠️ Ces exports produisent des fichiers téléchargés, ce qu'une session Claude ne fait pas sans ton accord explicite. À lancer par toi depuis `Outils → Exporter` et `Fluent Forms → Outils → Export`, ou à me demander en donnant le feu vert.

---

## CGV recollées sur staging le 2026-09-09

Pages **238** `/fr/conditions-generales-de-vente/` et **276** `/en/terms-and-conditions/` mises à jour depuis les fichiers corrigés, par modification chirurgicale plutôt que par recollage intégral : les pages avaient été montées depuis ces mêmes sources, l'écart se limitait donc à quatre points.

| Changement | FR | EN |
|---|---|---|
| Téléphone retiré (arbitrage Alain du 2026-09-09, email seul canal) | 4 occurrences | 4 occurrences |
| Clause d'exclusion de remboursement des droits de douane, article 13 | ajoutée | ajoutée |
| Article 14.1, validation visuelle et bon à tirer | ajouté | ajouté |
| Délais de livraison chiffrés et renvoi à L. 216-1, article 8.4 | ajoutés | ajoutés |

**Contrôle du rendu public, après purge LiteSpeed**, identique dans les deux langues :

| | h1 | h2 | h3 | téléphone | douane | bon à tirer | délais |
|---|---:|---:|---:|---:|---|---|---|
| `/fr/conditions-generales-de-vente/` | **1** | 27 | 16 | 0 | ✅ | ✅ | ✅ |
| `/en/terms-and-conditions/` | **1** | 27 | 16 | 0 | ✅ | ✅ | ✅ |

⚠️ **Correction d'un constat de la revue.** La revue avait relevé 28 titres de niveau 1 et prédit qu'un collage vers Gutenberg produirait 28 balises `h1` par page. Vérification faite en base : les pages ne portaient **aucun** `h1`, le collage du 2026-09-07 ayant déjà rétrogradé les niveaux. Le défaut était réel dans le fichier markdown, corrigé lui aussi, mais il n'avait jamais atteint les pages. L'unique `h1` visible est celui que le thème émet pour le titre de la page, ce qui est le comportement attendu.

---

## Session du 2026-09-09, tentative de résolution de l'anomalie

Investigation interrompue : l'**authentification HTTP de staging est retombée** en cours de session, pour la deuxième fois de la journée. Symptôme constant : le corps de la page revient vide et `fetch` échoue, alors que le serveur répond bien un 401 et que la production charge normalement. Seul Alain peut ressaisir ce mot de passe.

**Ce qui a été établi avant la coupure**, et qui n'est pas à refaire :

- La langue par défaut est bien le **français** (« Langue par défaut » sur la ligne FR, l'anglais proposant « Choisir English comme langue par défaut »). Conforme à la doctrine.
- Le module **« Détecter la langue du navigateur » de Polylang est désactivé**, ce qui est conforme : la redirection de la racine appartient au mu-plugin KH-107. Ce n'est donc pas la cause.
- La piste KH-107 est **réfutée**, cf. la section précédente.
- Le diagnostic est resserré sur la couche pages statiques de Polylang, avec un contre-indice à lever d'abord.

**Ce qui n'a pas pu être tenté** : l'aller-retour sur la valeur de `page_on_front` et la reconstruction du cache des langues, faute d'accès.

⚠️ Deux impasses d'interface relevées au passage, pour ne pas les refaire : cliquer le nom de la langue dans la liste ouvre la fiche et non le formulaire d'édition, et l'URL `admin.php?page=mlang&pll_action=edit&lang=5` ne charge pas le formulaire attendu. Le bon chemin est le lien **Modifier** qui apparaît au survol de la ligne. Attention aussi à ne pas confondre ce formulaire avec celui d'ajout d'une langue, qui occupe la même colonne.

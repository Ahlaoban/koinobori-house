# Journal PC5 — déploiement du thème enfant 1.2.0 sur le staging, 2026-09-21

Même session cPanel que le PC4 (ouverte par Alain, compte `heal3867`, onglet Chrome piloté par Claude). Aucun secret lu, production jamais touchée. Source : `main` `dc6a3c8`. GO Alain donné deux fois : pour le plan, puis avant la première écriture visible.

| UTC | Action | Résultat |
|---|---|---|
| 16:26:51 | Relevé (lecture) | thème `koinobori-child` 1.1.0 (26 fichiers, sans `inc/`), Kadence 1.5.2, mu-plugin `koino-lang-redirect` seul, `kh-product-media` absent ; les 30 pages dont le thème dépend existent et sont publiées (accueil 318/319, boutique 74/81, lifestyle 284/295, atelier 283/293, professionnels 279/285, contact 250/266, livraison 248/258, retours 249/263, entreprises 280/287, collectivités 281/289, 4 légales FR + EN) ; `_wp_page_template` non posé sur 318 et 319 |
| 16:28:38 | Dépôt et contrôles (privé) | `kh-pc5-dc6a3c8.tar` construit depuis les blobs Git (58 fichiers + `SHA256SUMS`), SHA-256 `4a7b0994f453536a911e92193a257c67b19819c100c28aae39497d80cbf974d3` identique poste / serveur ; extraction dans `~/kh2027-private/tools/d-dc6a3c8/` ; `sha256sum -c` : 58 / 58 ; `php -l` : 11 fichiers sans erreur ; aucun fichier de l'ancien thème absent du nouveau ; 0 contenu publié pointant vers un fichier du thème ; réglages `kh_split_header` / `kh_horizon_footer` non posés (défaut = actifs) |
| 16:28:38 | **Sauvegardes** (`~/kh2027-private/backups/`, `0600`) | `theme-koinobori-child-1.1.0-20260921T162838Z.tar.gz` 914 491 octets SHA-256 `f07e9287c60418ce8b54bad960291d05e0cd03fc704cae53fe68bd7155b7c243` ; `staging-db-20260921T162838Z.sql.gz` 1 510 654 octets SHA-256 `86dcb13c0d67f8bb44a5adc50aa42c998bfee6081f9c301370dcf30b9ff38428` |
| 16:31:00 | **1. Bascule du thème** : copie en `koinobori-child.new` (755 / 644), ancien dossier déplacé dans `backups/theme-dir-1.1.0-20260921T163100Z`, renommage, test `wp eval` avec retour arrière automatique | `SWAPPED`, `boot-ok 1.2.0 header-fn footer-fn`, `THEME-OK` ; `koinobori-child,active,1.2.0`, 54 fichiers |
| 16:31:42 | **2.** `kh-product-media` copié et activé ; mu-plugin `koino-bcdg-hyphen.php` déposé | `kh-product-media,active,1.0.1` ; `mu-plugins/` = `koino-bcdg-hyphen.php` + `koino-lang-redirect.php` |
| ~16:31:55 | **3.** `_wp_page_template` = `page-templates/kh-home.php` sur 318 et 319 | avant : vide ×2 ; après : posé ×2 |
| 16:32:08 | **4.** Honeypot global Fluent Forms : `_fluentform_global_form_settings.misc.honeypotStatus = "yes"` (clé vérifiée dans `fluentform/app/Modules/Form/HoneyPot.php:79`) | sauvegarde de l'option `pc5-option-fluentform-global-20260921T163208Z.json` ; `update_option` = `true` ; les autres clés de `misc` inchangées |
| 16:32 | Contrôle | chaîne `chain_exit=0`, `wp eval` charge WordPress thème et plugins compris, `error_log` du docroot inchangé depuis le 28/08 |

Incident : l'extension Chrome s'est déconnectée pendant l'envoi de la commande des étapes 2 à 4. Vérification faite avant toute relance : la commande avait été saisie une seule fois et s'est exécutée entièrement (sortie `pc5-settings.txt`). Aucune double exécution.

## Retour arrière

Thème : remettre `backups/theme-dir-1.1.0-20260921T163100Z` à la place de `wp-content/themes/koinobori-child` (ou l'archive `.tar.gz`). Gabarit : supprimer la méta `_wp_page_template` de 318 et 319. Honeypot : réécrire l'option depuis le JSON. Plugin : `wp plugin deactivate kh-product-media`. Base complète : dump de 16:28:38.

## Non fait, volontairement

- **Menus à 5 entrées** : sans menu affecté à `kh_header`, le thème affiche les 5 rubriques FR et EN à partir des pages publiées (toutes présentes). Des menus par langue avec Polylang se créent dans l'admin ; l'admin du staging n'est pas accessible au Chrome de Claude (certificat auto-signé). Les menus 144 / 145 (7 entrées) restent en base, affectés aux emplacements Kadence `primary` / `mobile` que le nouveau header n'utilise plus.
- **Slogan EN** (Polylang → Traductions des chaînes), titre d'accueil SEOPress EN : admin, Alain.
- **Export JSON des 8 formulaires** (S14), recette des formulaires : à faire.
- **Mu-plugin de redirection Arts de vivre** : non déposé, cf audit ci-dessous.

## Audit des pages « Arts de vivre » (lecture seule)

- 282 `arts-de-vivre` et 291 `art-de-vivre`, publiées, modifiées le 08/09 : texte « manifeste / bientôt » (intention, cinq familles d'objets, « Bientôt », renvoi vers la Boutique et l'adresse de contact). **Ce texte est conservé mot pour mot dans `docs/lot3/pages-professionnels-arts-de-vivre-FR-EN.md`** : une redirection ne fait rien perdre.
- 284 `lifestyle` et 295 `lifestyle-koi` : texte d'attente (« Les premiers articles arrivent »).
- Liens entrants : le contenu des pages 318 / 319 (plus rendu depuis que le gabarit d'accueil est posé, il n'appelle pas `the_content()`) et les éléments de menu 299 / 306 des menus 144 / 145 (plus affichés par le nouveau header). Aucun lien visible ne subsiste vers ces pages.
- Doctrine : « objets décoratifs japonais et asiatiques », aucune mention interdite.
- À décider par Alain : reprendre ou non une phrase de l'intention dans la page Lifestyle, puis déposer le mu-plugin (301 vers Lifestyle, même langue).

## Non vérifié

Rendu réel dans un navigateur : home FR / EN, header, footer, mobile, panier, formulaires, fiche produit avec vidéo. Le Chrome de Claude ne passe pas le certificat auto-signé du staging. **Recette à faire par Alain.**

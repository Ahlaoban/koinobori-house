# S16 — observations authentifiées du staging

10 septembre 2026, Chrome de l’utilisateur, session WordPress administrateur déjà ouverte. Observations par navigation et lecture du DOM ; aucune sauvegarde de réglage, désactivation, mise à jour, purge, relance de tâche, transaction ou soumission de formulaire. Les tableaux ci-dessous sont une transcription ciblée des écrans, sans nonces, secrets, adresses personnelles ou contenu de commandes. Ce relevé ne remplace pas un export de base ni des empreintes des fichiers serveur. Le rapport système affichait « Généré le : 2026-09-10 11:28:48 +02:00 » ; les lectures suivantes ont été faites vers 11:29–11:34 Paris.

## Versions et extensions

Sources : `/wp-admin/plugins.php`, filtre `plugin_status=mustuse`, `/wp-admin/admin.php?page=wc-status`.

| Extension active | Version | Mise à jour proposée par l’écran |
|---|---|---|
| Complianz | 7.5.4 | 7.5.5 |
| FluentSMTP | 2.3.1 | 2.4.0 |
| Fluent Forms | 6.2.13 | — |
| Koinobori affichage taille unique | 1.0.0 | — |
| LiteSpeed Cache | 7.9.1 | — |
| Polylang for WooCommerce | 2.2.4 | 2.2.5 |
| Polylang | 3.8.7 | 3.8.9 |
| UpdraftPlus | 1.26.7 | — |
| WooCommerce Stripe Gateway | 10.9.1 | 11.0.0 |
| WooCommerce PayPal Payments | 4.1.2 | 4.1.3 |
| PDF Invoices & Packing Slips | 5.16.1 | — |
| WooCommerce | 11.1.0 | — |
| Wordfence | 9.0.1 | — |
| SEOPress | 10.2 | — |

14 actives, 0 inactive, mises à jour automatiques désactivées pour les 14. Un seul MU-plugin listé : Koinobori redirection racine i18n 1.1.0. Les correctifs BCDG et diagnostic accueil ne figurent pas dans cette liste. Aucun plugin ACF, Kadence Blocks, wishlist ou Plausible n’y figure ; cela ne prouve pas l’absence d’un service externe ou d’une injection de code.

Le lien Désactiver existe sur Polylang for WooCommerce ; Polylang de base est déclaré requis par cette extension. Aucun lien d’action n’a été utilisé. L’affirmation de la passation sur l’absence de ce lien pour PLLWC n’est donc pas actuelle.

## État WooCommerce

WordPress 7.1 ; PHP 8.3.33 ; MariaDB 11.4.13 ; serveur déclaré Apache, environnement `staging`, mémoire WP 512 Mo, pas multisite, debug désactivé, WP-Cron actif, pas de cache objet externe déclaré. Base WC 11.1.0-1 ; taille totale affichée 16,23 MB. Thème enfant Koinobori House 1.1.0, parent Kadence 1.5.2, classique, support WooCommerce, aucune surcharge de template WC signalée.

HPOS actif, stockage `OrdersTableDataStore`, synchronisation HPOS désactivée. Le rapport compte 44 objets page, 35 product, 46 product_variation et 19 attachment, **tous statuts confondus**. Le tableau de bord annonce 40 pages publiées ; la liste produits confirme 34 publiés et 1 brouillon. Aucun total de stock n’a été calculé.

Pages WC : boutique74 `/fr/boutique/`, panier75 `/fr/panier/` bloc cart, commande76 `/fr/commande/` bloc checkout, compte77 `/fr/mon-compte/` shortcode my_account, CGV238 `/fr/conditions-generales-de-vente/`. Les cinq traductions de pages WC portent un indicateur favorable dans le rapport ; ce contrôle ne prouve pas le fonctionnement complet du parcours bilingue.

Stripe : mode test Oui, identifiant de compte vide, OAuth Non, synchronisation Non, card/link déclarés, express actif. Pas de lecture de clés ni de paiement ; ne pas déduire l’état d’éventuelles anciennes clés du seul indicateur OAuth. PayPal : intégré favorable, état webhook « – », journalisation « – ». Mode sandbox PayPal et livraison réelle de webhooks non établis.

FluentSMTP : notification explicite « doit être configuré pour fonctionner ». Wordfence : notification explicite que l’intégration Login Security WooCommerce n’est pas activée. MFA individuel et protection complète non audités. Journaux WC actifs, conservation 30 jours, volume affiché 41 Ko ; contenu métier non ouvert.

## Jobs et cache

Action Scheduler 4.0.0 : 698 terminés, 7 échoués, 17 en attente. Filtre `wc-status&tab=action-scheduler&status=failed` : les sept échecs concernent tous `fetch_patterns`, quotidien, du 3 au 9 septembre ; message « aucun rappel n’est enregistré ». Aucun échec paiement n’est démontré par ces sept lignes. Aucune suppression ou relance effectuée.

`admin.php?page=litespeed-cache` affiche que les fonctions de cache LSCache de cette page sont actuellement indisponibles, avec nécessité de LiteSpeed Web Server ou QUIC.cloud. Cela ne prouve pas l’absence de cache au niveau hébergeur/PowerBoost. Exclusions commerce, cache effectif anonyme et CDN à vérifier.

## Sauvegardes

`options-general.php?page=updraftplus`, onglets Sauvegarder/restaurer et Réglages : neuf ensembles listés. Base du 10/09 à 09:30 ; ensemble du 09/09 à 17:57 avec base, extensions, thèmes, téléversements, MU-plugins et autres ; bases quotidiennes du 06 au 09/09 ; ensemble complet du 05/09 ; fichiers du 03/09 et du 29/08. Les archives n’ont pas été téléchargées ni restaurées.

Réglages enregistrés lus dans les champs DOM : fichiers `weekly`, rétention4 ; base `daily`, rétention7 ; Google Drive sélectionné, interface indiquant déjà connecté ; toutes les cinq catégories de fichiers cochées ; rapport e-mail non coché. Prochaine base11/09 09:26, prochains fichiers12/09 09:26. L’accès effectif aux objets Drive, leur intégrité et leur contenu restent non vérifiés. L’interface indique que le cœur et les fichiers hors wp-content, dont wp-config.php, ne sont pas inclus dans cette couverture standard. Prévoir leur reprise sécurisée séparément.

## Accueil et multilingue

`options-reading.php` : `show_on_front=page`, `page_on_front=318` intitulée Des carpes de vent originales, signées BCDG, `page_for_posts=0`, `blog_public=0`.

`/fr/` : blog vide, « Prêt pour votre première publication ? ». `/en/` : « Ready to publish your first post? ». `/fr/accueil/` et `/en/home/` : contenu éditorial complet FR/EN, cinq mondes, BCDG, Kaïro, Atelier/The House, manifeste et professionnels. Symptôme reproduit dans une session administrateur ; réponse anonyme derrière Basic et cache non comparée dans cette session.

Sur `/fr/accueil/`, body contient home, page-id-318 et thème enfant, sans `kh-page`. CSS liée : style enfant et kh-foundations ; **aucun lien kh-charte-v3.css**. Ce constat prouve un écart de chargement avec le dépôt, pas l’absence physique du fichier serveur. Balises : noindex,nofollow ; hreflang fr vers `/fr/accueil/`, en vers `/en/home/`, x-default vers `/` ; canonical vers `/`. Menu français sept entrées, Accueil vers `/fr/`, lien logo vers `/fr/accueil/` ; footer crédit Kadence. Ainsi deux liens d’accueil aboutissent à des rendus différents.

`admin.php?page=mlang_settings` : détection navigateur propose Activer, médias propose Activer ; partage des slugs et traduction des slugs désactivés avec mention Polylang Pro requis. Versions exactes ci-dessus. Aucun réglage ou licence modifié.

## Formulaires

`admin.php?page=fluent_forms` : huit actifs, IDs5–12, tous affichent0 entrée : Contact FR/EN, Entreprises FR/Business EN, Collectivités FR/Institutions EN, Rétractation FR/Withdrawal EN. Définitions complètes, notifications, accusés durables, formulaires mobiles et consentements pas encore recettés. Aucun envoi effectué.

## Limites restantes

Pas d’accès cPanel/SSH/base directe, ni inventaire fichiers avec hashes, ni session admin production, ni restauration isolée, ni test transactionnel. Les versions affichées par WP ne sont pas des checksums de distribution. Aucune collecte de données clients ni de valeurs de secrets. Cette preuve S16 complète la collecte S06 restée non authentifiée et ses réponses401 ; elle ne la remplace pas rétroactivement.

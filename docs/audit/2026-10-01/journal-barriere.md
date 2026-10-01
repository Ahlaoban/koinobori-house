# Journal — mise en service de la barrière du staging (2026-10-01)

Procédure : `docs/development/barriere-staging.md`. Exécutée par Alain, contrôles extérieurs par Claude.

1. Empreinte du mot de passe partagé créée dans le terminal cPanel, constante `KOINO_STAGING_GATE_HASH`
   ajoutée au `wp-config.php` du staging par Alain (`grep -c` = 1, `php -l` sans erreur). Une seconde
   empreinte a remplacé la première (saisie à l'aveugle ratée) avec une commande à double saisie.
2. `koino-staging-gate-100.php` déposé (SHA-256 `11750f94…22c8`, `php -l` sans erreur), copié en
   `wp-content/mu-plugins/koino-staging-gate.php` à 11:39 (heure du serveur).
3. Contrôle en navigation privée, protection cPanel encore active : page « Préproduction / Staging »
   affichée, mot de passe partagé accepté.
4. Protection cPanel « Confidentialité du répertoire » décochée par Alain.

## Contrôles extérieurs, sans mot de passe (Claude, curl)

| Requête | Réponse | Attendu |
|---|---|---|
| `GET /fr/`, `/en/`, `/fr/boutique/`, `/robots.txt`, `/sitemaps.xml` | 403 page Préproduction | ✅ |
| `GET /` | 302 → `/en/` (redirection de langue), puis 403 | ✅ |
| `GET /wp-json/`, `/wp-json/wp/v2/users`, `?rest_route=`, `?wc-ajax=`, `?wc-api=foo` | 403 JSON `koino_staging_gate` | ✅ |
| `GET /xmlrpc.php` | 403 | ✅ |
| `GET /wp-login.php`, `/wp-cron.php` | 200 | ✅ |
| `GET /wp-admin/` | 302 → `wp-login.php` | ✅ |
| `POST /?wc-api=wc_stripe` (non signé) | 204, traité par l'extension Stripe | ✅ plus de 401 |
| `POST /wp-json/paypal/v1/incoming` (non signé) | 401 `rest_forbidden` de l'API REST WordPress (contrôle de l'extension PayPal), pas la barrière | ✅ |

En-têtes de la page 403 : `X-LiteSpeed-Cache-Control: no-cache`, `X-Robots-Tag: noindex, nofollow`,
`Cache-Control: no-store`.

## Nettoyage du `.htaccess`

Après le retrait de la protection, le `.htaccess` gardait les 6 lignes d'essai du 30/09, hors de tout bloc,
sous `# END Wordfence WAF` : deux `SetEnvIf … KH_WEBHOOK`, `Order Deny,Allow`, `Deny from all`,
`Allow from env=KH_WEBHOOK`, `Satisfy Any`. Ignorées par LiteSpeed, mais susceptibles de fermer le site si
elles étaient un jour appliquées. Sauvegarde `~/kh2027-private/backups/htaccess-staging-20261001-avant-nettoyage`,
puis suppression par Alain dans le gestionnaire de fichiers. Restent seulement les lignes du bloc Wordfence
`<Files ".user.ini">` (`Require all denied` / `Deny from all`), à garder. Contrôles extérieurs refaits :
résultats identiques au tableau ci-dessus.

## Commande d'essai 344 (carte Stripe test, FR, invité, 2026-10-01 12:21)

Fenêtre privée, mot de passe partagé, non connecté. Bigouden 75 cm (variation 208, `KH-TER-003-075`) à
**25,00 €** : la correction des variations du 30/09 est confirmée. Expédition forfait 5 €, total 30 €.

- Statut **En cours**, payé par carte, identifiant de paiement Stripe enregistré (`ch_…`) : la notification
  Stripe a été reçue.
- Email client « Votre commande sur Koinobori House a été reçue » arrivé en boîte de réception Gmail
  (expéditeur `contact@koinoborihouse.com`, via Brevo).
- Email boutique « Vous avez une nouvelle commande n°344 » arrivé sur `admin@koinoborihouse.com`.
- Commandes 342 et 343, restées en attente, annulées automatiquement le 30/09 à 21:35 (emails d'annulation
  reçus côté boutique).

Remarques pour la relecture des emails (Alain, Catherine) : textes WooCommerce par défaut (« Pour information –
nous avons reçu votre commande… »), couleur de base violette de WooCommerce au lieu de la charte, date au
format « octobre 1, 2026 » (Réglages → Général → Format de date `j F Y`).

Reste : commande d'essai PayPal (bac à sable) en FR, puis carte et PayPal en EN.

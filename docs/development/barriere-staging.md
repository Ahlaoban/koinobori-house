# Barrière du staging (mu-plugin `koino-staging-gate.php`)

Remplace la protection par mot de passe de cPanel (« Directory Privacy ») sur `staging.koinoborihouse.com`.

## Pourquoi

La protection cPanel répond **HTTP 401** à tout le monde, y compris aux notifications de paiement :
Stripe (`POST /?wc-api=wc_stripe`) et PayPal (`POST /wp-json/paypal/v1/incoming`). WooCommerce ne reçoit
donc jamais la confirmation du paiement : la commande reste « En attente de paiement » et aucun email ne
part (commande d'essai 343, 2026-09-30). Les exceptions écrites dans le `.htaccess` sont sans effet sur
le serveur LiteSpeed d'o2switch. Voir `docs/audit/2026-09-30/releve-socle.md`.

## Ce que fait le mu-plugin

Actif **seulement** si `wp_get_environment_type()` vaut `staging` (le site en ligne n'est jamais concerné).

| Requête | Sans accès | Remarque |
|---|---|---|
| Pages du site | page 403 « Préproduction » (mot de passe + lien de connexion WordPress) | `noindex` |
| API REST, `?wc-ajax=`, `?wc-api=` (sauf Stripe), xmlrpc | 403 JSON | |
| `POST /?wc-api=wc_stripe` | **ouvert** | Stripe signe chaque notification, l'extension la vérifie |
| `POST /wp-json/paypal/v1/incoming` | **ouvert** | idem PayPal |
| `wp-login.php`, `wp-admin`, `admin-ajax.php`, `wp-cron.php` | ouverts | WordPress gère leur accès, comme en production |

Accès accordé à toute personne **connectée à WordPress**, ou ayant saisi le **mot de passe partagé**
(cookie signé de 30 jours, à transmettre par exemple à Catherine). Le mot de passe partagé n'existe que si
la constante `KOINO_STAGING_GATE_HASH` est définie ; sinon seule la connexion WordPress ouvre le site.
Après 10 essais ratés, une adresse IP attend un quart d'heure. Changer le mot de passe invalide tous les
cookies déjà émis.

Les réponses du staging portent `X-LiteSpeed-Cache-Control: no-cache` : une page mise en cache serait
servie sans passer par PHP, donc sans barrière.

## Mise en service (par Alain, terminal cPanel)

Ordre impératif : la barrière est en place **avant** le retrait de la protection cPanel.

1. **Empreinte du mot de passe partagé.** Dans le terminal cPanel (la saisie reste invisible, rien
   n'entre dans l'historique) :

   ```text
   read -rs KHPW; export KHPW; php -r 'echo password_hash(getenv("KHPW"), PASSWORD_DEFAULT), PHP_EOL;'; unset KHPW
   ```

   Taper le mot de passe, Entrée. La commande affiche une empreinte qui commence par `$2y$`.

2. **Constante dans `wp-config.php` du staging** (Alain seul, Claude n'ouvre jamais ce fichier), juste
   au-dessus de la ligne `/* That's all, stop editing! */`, **entre apostrophes simples** (à cause des `$`) :

   ```php
   define( 'KOINO_STAGING_GATE_HASH', '$2y$10$...' );
   ```

3. **Dépôt du fichier.** Téléverser `koino-staging-gate-100.php` dans `~/kh2027-private/tools/`, puis :

   ```text
   cd ~/kh2027-private/tools && sha256sum koino-staging-gate-100.php && php -l koino-staging-gate-100.php && cp koino-staging-gate-100.php ~/staging.koinoborihouse.com/wp-content/mu-plugins/koino-staging-gate.php && ls -l ~/staging.koinoborihouse.com/wp-content/mu-plugins/
   ```

   L'empreinte affichée doit être celle indiquée dans la PR ; `php -l` doit répondre « No syntax errors ».

4. **Contrôle, protection cPanel encore en place**, dans une fenêtre de navigation privée : après le mot de
   passe cPanel, la page « Préproduction / Staging » doit s'afficher ; le mot de passe partagé ouvre le site.
   Dans la fenêtre habituelle (connecté à l'admin), le site s'affiche directement.

5. **Retrait de la protection cPanel** : cPanel → « Directory Privacy » (Confidentialité des répertoires)
   → `staging.koinoborihouse.com` → décocher la protection → Enregistrer. Puis vérifier que le
   `.htaccess` du staging ne contient plus les essais d'exception du 30/09 (`SetEnvIf`, `Require env`,
   `Satisfy Any`).

6. **Contrôles extérieurs (Claude, sans mot de passe)** : `/fr/` → 403 page Préproduction ;
   `/wp-json/` → 403 ; `POST /?wc-api=wc_stripe` → ni 401 ni 403 (Stripe refuse une notification non
   signée, réponse 400 attendue) ; `POST /wp-json/paypal/v1/incoming` → ni 401 ni 403 ; `/wp-login.php` → 200.

7. Commandes d'essai carte (Stripe test) et PayPal (bac à sable), FR et EN : la commande passe « En cours »
   et les emails client et boutique arrivent.

## Retour arrière

Supprimer `wp-content/mu-plugins/koino-staging-gate.php` et remettre la protection cPanel. La constante de
`wp-config.php` peut rester, elle n'a aucun effet sans le fichier.

## Limites connues

- `admin-ajax.php` et `admin-post.php` restent joignables sans mot de passe, comme sur le site en ligne :
  les actions publiques des extensions (envoi de formulaire Fluent Forms par exemple) y répondent.
- Un retour de paiement par **POST** depuis un autre site n'emporterait pas le cookie (`SameSite=Lax`) :
  Stripe 3-D Secure et PayPal reviennent par navigation GET, sans effet attendu.
- À la mise en production, ce fichier ne doit **pas** être copié sur le site en ligne (il y serait inactif,
  mais inutile).

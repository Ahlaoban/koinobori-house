<?php
/**
 * Plugin Name: Koinobori — barrière du staging
 * Description: Remplace la protection par mot de passe de cPanel sur le staging. Front, API REST et wc-ajax réservés aux personnes connectées à WordPress ou munies du mot de passe partagé ; laisse passer les notifications Stripe et PayPal. Inactif hors environnement « staging ».
 * Version:     1.0.0
 * Author:      Koinobori House
 *
 * Pourquoi : la protection cPanel (auth basique LiteSpeed) répond 401 aux
 * notifications Stripe (`POST /?wc-api=wc_stripe`) et PayPal
 * (`POST /wp-json/paypal/v1/incoming`), si bien qu'aucune commande d'essai ne
 * passe « En cours » ni n'envoie d'email (commande 343, 2026-09-30). Les
 * exceptions .htaccess (Require env, Satisfy Any) sont sans effet sur ce serveur.
 *
 * Accès accordé :
 *  - toute personne connectée à WordPress ;
 *  - ou porteuse du cookie `koino_staging_gate`, obtenu en saisissant le mot de
 *    passe partagé. Son empreinte est la constante KOINO_STAGING_GATE_HASH
 *    (password_hash), à définir dans wp-config.php par Alain. Sans constante,
 *    seule la connexion WordPress ouvre le site (fermé par défaut).
 *
 * Toujours ouverts : wp-admin et admin-ajax (WordPress gère leur accès),
 * wp-login.php, wp-cron.php, WP-CLI, les deux adresses de notification.
 * Tout le reste (pages, API REST, wc-ajax, wc-api, xmlrpc) répond 403.
 *
 * Mise en service : déposer ce fichier, définir la constante, vérifier, PUIS
 * retirer la protection cPanel. Retrait : supprimer ce fichier.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const KOINO_GATE_COOKIE   = 'koino_staging_gate';
const KOINO_GATE_LIFETIME = 30 * DAY_IN_SECONDS;
const KOINO_GATE_MAX_FAIL = 10; // essais ratés par adresse IP et par quart d'heure.

/**
 * Chemin de la requête, sans la query string ni les barres de bord.
 *
 * @return string
 */
function koino_gate_request_path() {
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
	return trim( (string) wp_parse_url( $uri, PHP_URL_PATH ), '/' );
}

/**
 * Adresses de notification Stripe et PayPal : ouvertes sans mot de passe. Les
 * deux extensions vérifient elles-mêmes la signature de chaque notification.
 *
 * @return bool
 */
function koino_gate_is_payment_webhook() {
	if ( isset( $_GET['wc-api'] ) && 'wc_stripe' === strtolower( sanitize_text_field( wp_unslash( $_GET['wc-api'] ) ) ) ) {
		return true;
	}

	$route = 'paypal/v1/incoming';
	if ( isset( $_GET['rest_route'] ) && trim( sanitize_text_field( wp_unslash( $_GET['rest_route'] ) ), '/' ) === $route ) {
		return true;
	}
	return koino_gate_request_path() === rest_get_url_prefix() . '/' . $route;
}

/**
 * La requête vise-t-elle l'API REST ? (À `init`, REST_REQUEST n'est pas encore défini.)
 *
 * @return bool
 */
function koino_gate_is_rest() {
	if ( isset( $_GET['rest_route'] ) ) {
		return true;
	}
	$path   = koino_gate_request_path();
	$prefix = rest_get_url_prefix();
	return $path === $prefix || 0 === strpos( $path, $prefix . '/' );
}

/**
 * Signature du cookie : liée à l'échéance, aux clés WordPress et à l'empreinte
 * du mot de passe. Changer le mot de passe invalide tous les cookies émis.
 *
 * @param int $expires Horodatage d'expiration.
 * @return string
 */
function koino_gate_signature( $expires ) {
	return hash_hmac( 'sha256', 'koino-gate|' . $expires, wp_salt( 'auth' ) . KOINO_STAGING_GATE_HASH );
}

/**
 * @return bool Vrai si le cookie du mot de passe partagé est présent et valide.
 */
function koino_gate_has_valid_cookie() {
	if ( ! defined( 'KOINO_STAGING_GATE_HASH' ) || empty( $_COOKIE[ KOINO_GATE_COOKIE ] ) ) {
		return false;
	}
	$parts = explode( '|', (string) wp_unslash( $_COOKIE[ KOINO_GATE_COOKIE ] ), 2 );
	if ( 2 !== count( $parts ) || ! preg_match( '/^\d+$/', $parts[0] ) || (int) $parts[0] < time() ) {
		return false;
	}
	return hash_equals( koino_gate_signature( (int) $parts[0] ), $parts[1] );
}

/**
 * Traite l'envoi du formulaire. Redirige et sort en cas de succès.
 *
 * @return string Message d'erreur à afficher, ou chaîne vide.
 */
function koino_gate_handle_password() {
	if ( ! defined( 'KOINO_STAGING_GATE_HASH' ) || ! isset( $_POST['koino_gate_password'] ) ) {
		return '';
	}

	$ip    = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key   = 'koino_gate_fail_' . md5( $ip );
	$fails = (int) get_transient( $key );
	if ( $fails >= KOINO_GATE_MAX_FAIL ) {
		return 'Trop d’essais. Réessayez dans un quart d’heure. / Too many attempts, try again later.';
	}

	// Mot de passe lu tel quel : le nettoyer en modifierait la valeur.
	$password = (string) wp_unslash( $_POST['koino_gate_password'] );
	if ( ! password_verify( $password, KOINO_STAGING_GATE_HASH ) ) {
		set_transient( $key, $fails + 1, 15 * MINUTE_IN_SECONDS );
		return 'Mot de passe incorrect. / Wrong password.';
	}

	$expires = time() + KOINO_GATE_LIFETIME;
	setcookie(
		KOINO_GATE_COOKIE,
		$expires . '|' . koino_gate_signature( $expires ),
		array(
			'expires'  => $expires,
			'path'     => COOKIEPATH ? COOKIEPATH : '/',
			'domain'   => COOKIE_DOMAIN,
			'secure'   => is_ssl(),
			'httponly' => true,
			'samesite' => 'Lax', // envoyé au retour de Stripe 3DS et PayPal (navigation GET).
		)
	);
	delete_transient( $key );

	$uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
	wp_safe_redirect( home_url( $uri ), 303 );
	exit;
}

/**
 * Page 403 avec le formulaire de mot de passe et le lien de connexion.
 *
 * @param string $error Message d'erreur éventuel.
 */
function koino_gate_render( $error ) {
	status_header( 403 );
	nocache_headers();
	header( 'Content-Type: text/html; charset=utf-8' );

	$uri   = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
	$login = wp_login_url( home_url( $uri ) );
	?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Koinobori House — préproduction</title>
<style>
	body { margin: 0; min-height: 100vh; display: grid; place-items: center; background: #F8F4EE; color: #1A1410; font: 16px/1.5 system-ui, sans-serif; }
	main { width: min(22rem, calc(100% - 32px)); }
	h1 { font: italic 400 1.6rem/1.2 Georgia, serif; margin: 0 0 1rem; }
	label { display: block; margin-bottom: .25rem; }
	input { box-sizing: border-box; width: 100%; padding: .6rem; border: 1px solid #1A1410; border-radius: 0; font: inherit; background: #FFFDFC; }
	button { margin-top: .75rem; padding: .6rem 1.2rem; border: 0; border-radius: 0; background: #C8311A; color: #FFFDFC; font: inherit; cursor: pointer; }
	:focus-visible { outline: 2px solid #2B3A6B; outline-offset: 2px; }
	.err { color: #C8311A; }
	a { color: #2B3A6B; }
</style>
</head>
<body>
<main>
	<h1>Préproduction / Staging</h1>
	<?php if ( '' !== $error ) : ?>
		<p class="err" role="alert"><?php echo esc_html( $error ); ?></p>
	<?php endif; ?>
	<?php if ( defined( 'KOINO_STAGING_GATE_HASH' ) ) : ?>
	<form method="post" action="<?php echo esc_url( home_url( $uri ) ); ?>">
		<label for="koino-gate-pw">Mot de passe / Password</label>
		<input id="koino-gate-pw" type="password" name="koino_gate_password" autocomplete="current-password" required autofocus>
		<button type="submit">Entrer / Enter</button>
	</form>
	<?php endif; ?>
	<p><a href="<?php echo esc_url( $login ); ?>">Connexion WordPress / WordPress login</a></p>
</main>
</body>
</html>
	<?php
	exit;
}

/**
 * Barrière. À `init` : l'utilisateur courant est connu, et ni l'API REST ni
 * wc-api ni wc-ajax ni le gabarit n'ont encore répondu (parse_request,
 * template_redirect viennent après).
 */
function koino_staging_gate() {
	if ( 'staging' !== wp_get_environment_type() ) {
		return;
	}
	if ( is_admin() || wp_doing_cron() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		return; // wp-admin et admin-ajax : WordPress gère leur accès.
	}
	if ( isset( $GLOBALS['pagenow'] ) && 'wp-login.php' === $GLOBALS['pagenow'] ) {
		return;
	}
	if ( koino_gate_is_payment_webhook() ) {
		return;
	}

	// Rien du staging ne doit entrer dans le cache LiteSpeed : une page en cache
	// serait servie sans passer par PHP, donc sans barrière.
	header( 'X-LiteSpeed-Cache-Control: no-cache' );
	header( 'X-Robots-Tag: noindex, nofollow' );

	if ( is_user_logged_in() || koino_gate_has_valid_cookie() ) {
		return;
	}

	$error = koino_gate_handle_password();

	if ( koino_gate_is_rest() || isset( $_GET['wc-ajax'] ) || isset( $_GET['wc-api'] ) || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ) {
		wp_send_json( array( 'code' => 'koino_staging_gate', 'message' => 'Staging: access restricted.' ), 403 );
	}

	koino_gate_render( $error );
}
add_action( 'init', 'koino_staging_gate', 0 );

<?php
/**
 * Plugin Name: Koinobori — redirection racine i18n
 * Description: 302 sur la racine "/" vers /fr/ ou /en/ selon le cookie koino_lang_pref puis Accept-Language. Ne touche jamais /fr/ ni /en/. Jamais 301.
 * Version:     1.1.0
 * Author:      Koinobori House
 *
 * KH-107 (Lot 1), composante du gate KH-104b-pré.
 *
 * IMPORTANT (v1.1.0) — la redirection s'exécute AU CHARGEMENT du mu-plugin,
 * donc AVANT que Polylang (extension normale) ne soit inclus.
 * v1.0.0 utilisait le hook `init` : trop tard. Polylang redirige la racine "/"
 * vers la langue par défaut (toujours /fr/) AVANT `init` (phase de choix de
 * langue), si bien que notre logique (cookie, Accept-Language, en-têtes) ne
 * tournait jamais sur "/" (gate KH-104b-pré run 1 : A2/A3/A4/A6/C1 + cookie/Vary/
 * Cache-Control en échec). Les mu-plugins étant chargés avant les extensions,
 * exécuter ici garantit qu'on gagne la course. À ce stade pluggable.php n'est
 * pas chargé (pas de wp_redirect) : on émet la redirection avec header() natif.
 *
 * Doctrine i18n (CLAUDE.md) :
 *  - racine "/"  → 302 temporaire vers /fr/ si Accept-Language fr*, sinon /en/ (fallback EN)
 *  - cookie koino_lang_pref (90 j) PRIME sur Accept-Language (choix manuel > navigateur)
 *  - JAMAIS 301 sur la racine
 *  - JAMAIS de redirection /fr/ ↔ /en/ (restent accessibles + indexables)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'KOINO_LANG_COOKIE' ) ) {
	define( 'KOINO_LANG_COOKIE', 'koino_lang_pref' );
}

/**
 * Déduit la langue préférée ('fr' ou 'en') d'un en-tête Accept-Language.
 * Fallback EN par doctrine : EN dès que le français n'est pas explicitement préféré.
 *
 * @param string $header Valeur brute de l'en-tête Accept-Language.
 * @return string 'fr' ou 'en'.
 */
function koino_lang_from_accept_language( $header ) {
	$header = is_string( $header ) ? $header : '';
	$best   = array(); // sous-tag primaire => meilleur q.

	foreach ( explode( ',', $header ) as $part ) {
		$part = trim( $part );
		if ( '' === $part ) {
			continue;
		}

		$q   = 1.0;
		$tag = $part;
		if ( false !== strpos( $part, ';' ) ) {
			list( $tag, $params ) = explode( ';', $part, 2 );
			if ( preg_match( '/q\s*=\s*([0-9.]+)/i', $params, $m ) ) {
				$q = (float) $m[1];
			}
		}

		$primary = strtolower( substr( trim( $tag ), 0, 2 ) );
		if ( '' === $primary ) {
			continue;
		}
		if ( ! isset( $best[ $primary ] ) || $q > $best[ $primary ] ) {
			$best[ $primary ] = $q;
		}
	}

	$fr = isset( $best['fr'] ) ? $best['fr'] : -1;
	$en = isset( $best['en'] ) ? $best['en'] : -1;

	// Français choisi seulement s'il est explicitement accepté (q > 0) et au moins
	// aussi prioritaire que l'anglais. Tout le reste retombe sur EN (défaut MVP).
	return ( $fr > 0 && $fr >= $en ) ? 'fr' : 'en';
}

/**
 * Redirige la racine "/" vers /fr/ ou /en/. Exécutée au chargement du mu-plugin
 * (avant Polylang) — voir l'en-tête du fichier. Émet la redirection en header()
 * natif car wp_redirect() n'est pas encore disponible à ce stade.
 */
function koino_root_lang_redirect() {
	// Hors front-end : ne rien faire.
	if ( is_admin() ) {
		return;
	}
	if ( ( function_exists( 'wp_doing_cron' ) && wp_doing_cron() )
		|| ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() )
		|| ( defined( 'REST_REQUEST' ) && REST_REQUEST )
		|| ( defined( 'WP_CLI' ) && WP_CLI )
		|| ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ) {
		return;
	}

	// GET/HEAD uniquement (jamais sur un POST de formulaire, etc.).
	$method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : 'GET';
	if ( 'GET' !== $method && 'HEAD' !== $method ) {
		return;
	}

	if ( empty( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}
	$request_uri = wp_unslash( $_SERVER['REQUEST_URI'] );

	// On n'agit QUE sur la racine exacte (gère aussi une install en sous-dossier).
	$home_path = trim( (string) wp_parse_url( home_url(), PHP_URL_PATH ), '/' );
	$req_path  = trim( (string) wp_parse_url( $request_uri, PHP_URL_PATH ), '/' );
	if ( $req_path !== $home_path ) {
		return; // /fr/, /en/, /produit/... → jamais touchés.
	}

	// Cookie (choix manuel) PRIME sur Accept-Language.
	$lang = '';
	if ( isset( $_COOKIE[ KOINO_LANG_COOKIE ] ) ) {
		$cookie = strtolower( sanitize_text_field( wp_unslash( $_COOKIE[ KOINO_LANG_COOKIE ] ) ) );
		if ( 'fr' === $cookie || 'en' === $cookie ) {
			$lang = $cookie;
		}
	}
	if ( '' === $lang ) {
		$accept = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) : '';
		$lang   = koino_lang_from_accept_language( $accept );
	}

	// Rafraîchit / pose le cookie (fenêtre glissante 90 j).
	$expire = time() + ( 90 * DAY_IN_SECONDS );
	setcookie(
		KOINO_LANG_COOKIE,
		$lang,
		array(
			'expires'  => $expire,
			'path'     => '/',
			'secure'   => is_ssl(),
			'httponly' => false, // lisible par le sélecteur de langue JS (KH-115).
			'samesite' => 'Lax',
		)
	);

	// Cible + préservation de l'éventuelle query string (UTM, etc.).
	$target = home_url( '/' . $lang . '/' );
	$query  = wp_parse_url( $request_uri, PHP_URL_QUERY );
	if ( ! empty( $query ) ) {
		$target .= '?' . $query;
	}

	// La racine ne doit jamais être mise en cache (cf exclusion LiteSpeed) ni
	// servir la mauvaise langue derrière un cache/CDN.
	nocache_headers();
	header( 'Vary: Cookie, Accept-Language', false );
	header( 'X-Redirect-By: koino-lang-redirect' ); // signature : prouve que c'est nous (pas Polylang).

	// 302 temporaire — JAMAIS 301 sur la racine. header() natif car pluggable.php
	// (wp_redirect) n'est pas encore chargé au moment où ce mu-plugin s'exécute.
	header( 'Location: ' . $target, true, 302 );
	exit;
}

// Exécution immédiate, au chargement du mu-plugin, AVANT Polylang.
// (Pas de hook : `init` arriverait après la redirection racine de Polylang.)
koino_root_lang_redirect();

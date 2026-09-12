<?php
/** Render a private clone request under the previously verified restricted PHP. */
$root = '/home3/sc3heal3867/kh2027-private/runtime-20260910';
$control = '/home3/sc3heal3867/kh2027-private/runtime-control';
$route = isset( $argv[1] ) ? $argv[1] : '';
if ( PHP_SAPI !== 'cli' || ! in_array( $route, array( '/fr/', '/en/' ), true )
	|| realpath( $root ) !== $root || realpath( $control ) !== $control
	|| ini_get( 'allow_url_fopen' ) || function_exists( 'curl_exec' )
	|| function_exists( 'stream_socket_client' ) || function_exists( 'mail' ) ) {
	throw new RuntimeException( 'Private renderer prerequisites not met.' );
}
umask( 0077 );
$lang = trim( $route, '/' );
$_SERVER['HTTP_HOST'] = 'kh2027-test.invalid';
$_SERVER['SERVER_NAME'] = 'kh2027-test.invalid';
$_SERVER['HTTPS'] = 'on';
$_SERVER['SERVER_PORT'] = '443';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = $route;
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = $root . '/index.php';
$_SERVER['DOCUMENT_ROOT'] = $root;
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
require_once $root . '/wp-includes/plugin.php';
add_filter( 'option_active_plugins', function ( $plugins ) {
	return array_values( array_filter( $plugins, function ( $plugin ) {
		return in_array( explode( '/', $plugin )[0], array(
			'woocommerce', 'polylang', 'polylang-wc', 'kh-single-variation-display',
		), true );
	} ) );
} );
$redirects = 0;
add_filter( 'wp_redirect', function ( $location ) use ( &$redirects ) {
	++$redirects;
	return false;
} );
add_action( 'muplugins_loaded', function () {
	if ( ! defined( 'KH2027_SANDBOX' ) || ! KH2027_SANDBOX
		|| wp_get_environment_type() !== 'local' || DB_NAME !== 'sc3heal3867_kh2027drill'
		|| apply_filters( 'pre_wp_mail', null, array() ) !== true ) {
		throw new RuntimeException( 'Sandbox identity or mail guard mismatch.' );
	}
	$probe = wp_remote_get( 'https://example.com' );
	if ( ! is_wp_error( $probe ) || $probe->get_error_code() !== 'kh2027_offline' ) {
		throw new RuntimeException( 'HTTP guard mismatch.' );
	}
}, PHP_INT_MAX );
ob_start();
register_shutdown_function( function () use ( $control, $lang, &$redirects ) {
	$html = ob_get_contents();
	ob_end_clean();
	$error = error_get_last();
	$fatal = $error && in_array( $error['type'], array( E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR ), true );
	$result = array(
		'route' => '/' . $lang . '/',
		'fatal' => (bool) $fatal,
		'redirects' => $redirects,
		'html_bytes' => strlen( (string) $html ),
		'front_page' => function_exists( 'is_front_page' ) ? is_front_page() : null,
		'blog_home' => function_exists( 'is_home' ) ? is_home() : null,
		'not_found' => function_exists( 'is_404' ) ? is_404() : null,
		'queried_id' => function_exists( 'get_queried_object_id' ) ? get_queried_object_id() : null,
		'language' => function_exists( 'pll_current_language' ) ? pll_current_language() : null,
		'theme' => function_exists( 'get_stylesheet' ) ? get_stylesheet() : null,
	);
	file_put_contents( $control . '/render-' . $lang . '-20260911.html', $html );
	file_put_contents( $control . '/render-' . $lang . '-20260911.json', json_encode( $result ) );
	echo json_encode( $result ) . PHP_EOL;
} );
define( 'WP_USE_THEMES', true );
require $root . '/wp-blog-header.php';

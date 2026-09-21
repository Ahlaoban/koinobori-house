<?php
/** Set the approved /fr/ and /en/ front-page URLs, only on the private clone. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $wpdb;
if ( ! defined( 'KH2027_SANDBOX' ) || ! KH2027_SANDBOX
	|| wp_get_environment_type() !== 'local'
	|| DB_NAME !== 'sc3heal3867_kh2027drill' || $wpdb->dbname !== DB_NAME
	|| wp_parse_url( home_url(), PHP_URL_HOST ) !== 'kh2027-test.invalid'
	|| ! function_exists( 'PLL' ) || ! method_exists( PLL()->model, 'clean_languages_cache' )
	|| apply_filters( 'pre_wp_mail', null, array() ) !== true ) {
	throw new RuntimeException( 'Private clone identity or guards not available.' );
}
$probe = wp_remote_get( 'https://example.com' );
if ( ! is_wp_error( $probe ) || $probe->get_error_code() !== 'kh2027_offline' ) {
	throw new RuntimeException( 'HTTP guard not available.' );
}
$before = get_option( 'polylang' );
if ( ! is_array( $before ) || ! array_key_exists( 'redirect_lang', $before )
	|| get_option( 'show_on_front' ) !== 'page'
	|| pll_get_post( 318, 'fr' ) !== 318 || pll_get_post( 318, 'en' ) !== 319
	|| get_post_status( 318 ) !== 'publish' || get_post_status( 319 ) !== 'publish' ) {
	throw new RuntimeException( 'Expected front-page configuration not found.' );
}
if ( $before['redirect_lang'] === true ) {
	echo "Already configured.\n";
	return;
}
if ( $before['redirect_lang'] !== false ) {
	throw new RuntimeException( 'Unexpected previous URL setting.' );
}
umask( 0077 );
$backup_path = '/home3/sc3heal3867/kh2027-private/runtime-control/polylang-before-frontpage-20260911.json';
$backup = fopen( $backup_path, 'x' );
if ( ! $backup ) { throw new RuntimeException( 'Cannot create private rollback file.' ); }
$payload = wp_json_encode( $before );
$written = fwrite( $backup, $payload );
fclose( $backup );
if ( $written !== strlen( $payload ) ) { throw new RuntimeException( 'Incomplete rollback file.' ); }
$after = $before;
$after['redirect_lang'] = true;
if ( ! update_option( 'polylang', $after ) || get_option( 'polylang' ) !== $after ) {
	throw new RuntimeException( 'URL setting update failed; inspect private rollback file.' );
}
PLL()->model->clean_languages_cache();
echo "redirect_lang: false -> true; private rollback file saved; language cache cleared.\n";

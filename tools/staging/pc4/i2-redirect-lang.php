<?php
/**
 * I2: Polylang option redirect_lang = true, so /fr/ and /en/ resolve the
 * language front page (318 / 319) instead of the blog index. Only that key
 * changes; the full option is backed up.
 *
 *   wp eval-file tools/staging/pc4/i2-redirect-lang.php
 *   KH_APPLY=1 KH_CONFIRM=i2-redirect-lang wp eval-file ...
 *
 * HTTP check before and after (see README-PC4.md): HEAD 200 without Location,
 * GET body contains the front page title, not the post list.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once __DIR__ . '/lib.php';

$ctx     = kh_pc4_boot( 'i2-redirect-lang', 'i2-redirect-lang' );
$current = get_option( 'polylang' );
if ( ! is_array( $current ) || ! array_key_exists( 'redirect_lang', $current ) ) {
	WP_CLI::error( 'Option polylang missing or without redirect_lang; refusing to guess.' );
}
$keys = array( 'redirect_lang', 'hide_default', 'force_lang', 'default_lang', 'browser', 'rewrite' );
WP_CLI::log( 'before=' . wp_json_encode( array_intersect_key( $current, array_flip( $keys ) ) ) );
WP_CLI::log( 'show_on_front=' . get_option( 'show_on_front' ) . ' page_on_front=' . get_option( 'page_on_front' ) );

if ( true === $current['redirect_lang'] || 1 === $current['redirect_lang'] ) {
	WP_CLI::success( 'redirect_lang already true. Nothing to do.' );
	return;
}
if ( 'page' !== get_option( 'show_on_front' ) || ! (int) get_option( 'page_on_front' ) ) {
	WP_CLI::error( 'show_on_front/page_on_front are not set to a static page; fix that first.' );
}
if ( ! $ctx['apply'] ) {
	WP_CLI::success( 'Dry run: would set polylang.redirect_lang = true. No write.' );
	return;
}

kh_pc4_backup( $ctx, 'i2-option-polylang', array(
	array( 'kind' => 'option', 'name' => 'polylang', 'value' => $current ),
) );
$next = $current;
$next['redirect_lang'] = true;
kh_pc4_transaction( function () use ( $next ) {
	if ( ! update_option( 'polylang', $next ) ) {
		throw new RuntimeException( 'update_option returned false.' );
	}
} );
$after = get_option( 'polylang' );
WP_CLI::log( 'after=' . wp_json_encode( array_intersect_key( (array) $after, array_flip( $keys ) ) ) );
WP_CLI::success( 'redirect_lang set. Run the HTTP checks and purge LiteSpeed.' );

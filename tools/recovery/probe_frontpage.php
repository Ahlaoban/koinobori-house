<?php
/**
 * Read-only front-page and Polylang diagnostic for the KH private clone.
 *
 * Run with WP-CLI eval-file while Polylang is loaded. No post content, personal
 * data, option payload or secret is emitted.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

$home_host = (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST );
if ( ! defined( 'KH2027_SANDBOX' ) || ! KH2027_SANDBOX
	|| 'local' !== wp_get_environment_type()
	|| '.invalid' !== substr( $home_host, -8 ) ) {
	throw new RuntimeException( 'Refusing to probe outside the KH private clone.' );
}

$raw_rows = $wpdb->get_results(
	"SELECT option_name, option_value FROM {$wpdb->options}
	 WHERE option_name IN ('show_on_front','page_on_front','page_for_posts')",
	OBJECT_K
); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

if ( $wpdb->last_error || ! is_array( $raw_rows ) || 3 !== count( $raw_rows ) ) {
	throw new RuntimeException( 'Unable to read all three front-page options.' );
}

$raw = array();
foreach ( array( 'show_on_front', 'page_on_front', 'page_for_posts' ) as $option_name ) {
	$raw[ $option_name ] = isset( $raw_rows[ $option_name ] ) ? $raw_rows[ $option_name ]->option_value : null;
}

$front_id              = isset( $raw['page_on_front'] ) ? (int) $raw['page_on_front'] : 0;
$translation_candidates = function_exists( 'pll_get_post_translations' ) && $front_id
	? pll_get_post_translations( $front_id )
	: array();
$translations          = is_array( $translation_candidates )
	? array_map( 'intval', $translation_candidates )
	: array();
$post_ids     = array_values( array_unique( array_filter( array_merge( array( $front_id ), $translations ) ) ) );
$posts        = array();

foreach ( $post_ids as $post_id ) {
	$post_translation_candidates = function_exists( 'pll_get_post_translations' )
		? pll_get_post_translations( $post_id )
		: array();
	$posts[ $post_id ]            = array(
		'type'         => get_post_type( $post_id ),
		'status'       => get_post_status( $post_id ),
		'language'     => function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $post_id, 'slug' ) : null,
		'translations' => is_array( $post_translation_candidates ) ? array_map( 'intval', $post_translation_candidates ) : array(),
	);
}

$languages = array();
if ( function_exists( 'PLL' ) && isset( PLL()->model ) ) {
	foreach ( PLL()->model->get_languages_list() as $language ) {
		$languages[ $language->slug ] = array(
			'page_on_front'  => isset( $language->page_on_front ) ? (int) $language->page_on_front : null,
			'page_for_posts' => isset( $language->page_for_posts ) ? (int) $language->page_for_posts : null,
		);
	}
}

$result = array(
	'raw_options'           => $raw,
	'filtered_options_cli' => array(
		'show_on_front'  => get_option( 'show_on_front' ),
		'page_on_front'  => (int) get_option( 'page_on_front' ),
		'page_for_posts' => (int) get_option( 'page_for_posts' ),
	),
	'front_translations'    => $translations,
	'front_posts'           => $posts,
	'polylang_languages'    => $languages,
	'polylang_loaded'       => function_exists( 'pll_get_post_translations' ),
	'polylang_model_loaded' => ! empty( $languages ),
);

echo wp_json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . PHP_EOL;

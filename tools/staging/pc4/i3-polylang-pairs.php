<?php
/**
 * I3: pair the legal/shipping pages that exist in both languages but have no
 * Polylang translation group: 232<->234 (legal notice), 235<->236 (cookies),
 * 230<->231 (shipping to USA). Polylang must be loaded (no --skip-plugins).
 *
 *   wp eval-file tools/staging/pc4/i3-polylang-pairs.php
 *   KH_APPLY=1 KH_CONFIRM=i3-polylang-pairs wp eval-file ...
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once __DIR__ . '/lib.php';

$ctx = kh_pc4_boot( 'i3-polylang-pairs', 'i3-polylang-pairs' );
foreach ( array( 'pll_get_post_language', 'pll_get_post_translations', 'pll_save_post_translations' ) as $fn ) {
	if ( ! function_exists( $fn ) ) {
		WP_CLI::error( 'Polylang function missing: ' . $fn . ' (run without --skip-plugins).' );
	}
}

$pairs = array(
	'mentions-legales' => array( 'fr' => 232, 'en' => 234 ),
	'politique-cookies' => array( 'fr' => 235, 'en' => 236 ),
	'livraison-usa'    => array( 'fr' => 230, 'en' => 231 ),
);
$plan    = array();
$restore = array();
foreach ( $pairs as $label => $group ) {
	$state = array();
	foreach ( $group as $lang => $id ) {
		$post = get_post( $id );
		if ( ! $post || 'page' !== $post->post_type ) {
			WP_CLI::error( $label . ': post ' . $id . ' missing or not a page.' );
		}
		$actual = pll_get_post_language( $id );
		if ( $actual !== $lang ) {
			WP_CLI::error( $label . ': post ' . $id . ' language is "' . $actual . '", expected "' . $lang . '".' );
		}
		$existing = (array) pll_get_post_translations( $id );
		foreach ( $existing as $ex_lang => $ex_id ) {
			if ( (int) $ex_id !== $id && (int) $ex_id !== (int) ( $group[ $ex_lang ] ?? 0 ) ) {
				WP_CLI::error( $label . ': post ' . $id . ' already linked to ' . $ex_lang . ':' . $ex_id . '; refusing to overwrite.' );
			}
		}
		$state[ $lang ] = array( 'id' => $id, 'status' => $post->post_status, 'slug' => $post->post_name, 'translations' => $existing );
		$restore[]      = array( 'kind' => 'polylang_group', 'post_id' => $id, 'group' => $existing ?: array( $lang => $id ) );
	}
	$already = (int) ( $state['fr']['translations']['en'] ?? 0 ) === $group['en'] && (int) ( $state['en']['translations']['fr'] ?? 0 ) === $group['fr'];
	WP_CLI::log( $label . ': ' . wp_json_encode( $state ) . ( $already ? ' [already paired]' : ' [to pair]' ) );
	if ( ! $already ) {
		$plan[ $label ] = $group;
	}
}

if ( ! $plan ) {
	WP_CLI::success( 'All pairs already linked. Nothing to do.' );
	return;
}
if ( ! $ctx['apply'] ) {
	WP_CLI::success( 'Dry run: ' . count( $plan ) . ' pair(s) would be linked. No write.' );
	return;
}

kh_pc4_backup( $ctx, 'i3-polylang-groups', $restore );
kh_pc4_transaction( function () use ( $plan ) {
	foreach ( $plan as $label => $group ) {
		pll_save_post_translations( $group );
		$check = (array) pll_get_post_translations( $group['fr'] );
		if ( (int) ( $check['en'] ?? 0 ) !== $group['en'] ) {
			throw new RuntimeException( $label . ': verification after save failed.' );
		}
	}
} );
WP_CLI::success( count( $plan ) . ' pair(s) linked. Check hreflang on both pages, then purge LiteSpeed.' );

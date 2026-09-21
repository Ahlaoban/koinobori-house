<?php
/**
 * PC6: fill the home pages (318 FR, 319 EN) with the editable block content built by the
 * child theme (inc/home-blocks.php). Texts then live in the database and are edited in
 * WordPress. Theme and plugins must be loaded (Polylang resolves the translated URLs):
 *
 *   wp eval-file tools/staging/pc6/home-blocks-seed.php                        # dry-run
 *   KH_APPLY=1 KH_CONFIRM=home-blocks-seed wp eval-file ...                    # apply
 *
 * Refuses to run twice: a page that already holds the home blocks is left alone, so an
 * edit made by Alain or Catherine is never overwritten. Restore: tools/staging/pc4/restore.php.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once dirname( __DIR__ ) . '/pc4/lib.php';
global $wpdb;

$ctx = kh_pc4_boot( 'home-blocks-seed', 'home-blocks-seed' );
foreach ( array( 'koinobori_child_home_seed', 'pll_get_post_language', 'parse_blocks', 'serialize_block_attributes' ) as $fn ) {
	if ( ! function_exists( $fn ) ) {
		WP_CLI::error( 'Missing function: ' . $fn . ' (child theme 1.3.0 and Polylang must be loaded: no --skip-themes, no --skip-plugins).' );
	}
}
kh_pc4_require_innodb( $wpdb->posts );

$pages = array( 318 => 'fr', 319 => 'en' );
$plan  = array();
foreach ( $pages as $id => $language ) {
	$post = get_post( $id );
	if ( ! $post || 'page' !== $post->post_type || 'publish' !== $post->post_status ) {
		WP_CLI::error( 'Page ' . $id . ' missing, not a page or not published.' );
	}
	if ( pll_get_post_language( $id ) !== $language ) {
		WP_CLI::error( 'Page ' . $id . ' is not in language ' . $language . '.' );
	}
	if ( 'page-templates/kh-home.php' !== get_post_meta( $id, '_wp_page_template', true ) ) {
		WP_CLI::error( 'Page ' . $id . ' does not use the home template.' );
	}
	if ( false !== strpos( $post->post_content, 'kh-home-hero' ) ) {
		WP_CLI::log( 'page ' . $id . ' (' . $language . '): already holds the home blocks, left untouched' );
		continue;
	}

	$content = koinobori_child_home_seed( $language );
	$blocks  = array_values( array_filter( parse_blocks( $content ), static function ( $b ) { return null !== $b['blockName']; } ) );
	$count   = static function ( array $list ) use ( &$count ) {
		$n = 0;
		foreach ( $list as $b ) { if ( null !== $b['blockName'] ) { $n += 1 + $count( $b['innerBlocks'] ); } }
		return $n;
	};
	$top = array_map( static function ( $b ) { return $b['blockName'] . ':' . ( $b['attrs']['className'] ?? '' ); }, $blocks );
	if ( 7 !== count( $blocks ) || array_unique( array_column( $blocks, 'blockName' ) ) !== array( 'core/group' ) ) {
		WP_CLI::error( 'Unexpected seed structure for ' . $language . ': ' . wp_json_encode( $top ) );
	}
	// A link the theme could not resolve is dropped from the seed: report it, never invent a URL.
	$links = substr_count( $content, '<a ' );
	WP_CLI::log( sprintf( 'page %d (%s): %d bytes now -> %d bytes, %d blocks, %d links, sections=%s',
		$id, $language, strlen( $post->post_content ), strlen( $content ), $count( $blocks ), $links, wp_json_encode( $top ) ) );
	if ( preg_match( '/(\bchin[eo]|\bchina|fabricat|atelier partenaire|partner workshop|\x{2014})/iu', wp_strip_all_tags( $content ), $m ) ) {
		WP_CLI::error( 'Doctrine check failed on seed text: ' . $m[1] );
	}
	$plan[ $id ] = array( 'before' => $post->post_content, 'after' => $content );
}

if ( ! $plan ) {
	WP_CLI::success( 'Nothing to change.' );
	return;
}
if ( ! $ctx['apply'] ) {
	WP_CLI::success( 'Dry run: ' . count( $plan ) . ' page(s) would receive the home blocks. No write.' );
	return;
}

$restore = array();
foreach ( $plan as $id => $item ) {
	$restore[] = array( 'kind' => 'row', 'table' => $wpdb->posts, 'where' => array( 'ID' => $id ), 'data' => array( 'post_content' => $item['before'] ) );
}
kh_pc4_backup( $ctx, 'pc6-home-post-content', $restore );

// wp_update_post keeps a revision (visible in the editor) and clears the caches.
// WP-CLI runs without a user: the KSES save filters would rewrite trusted block markup.
kses_remove_filters();
foreach ( $plan as $id => $item ) {
	$result = wp_update_post( wp_slash( array( 'ID' => $id, 'post_content' => $item['after'] ) ), true );
	if ( is_wp_error( $result ) ) {
		WP_CLI::error( 'Update failed for page ' . $id . ': ' . $result->get_error_message() . ' (restore with the backup above).' );
	}
	$stored = get_post( $id )->post_content;
	if ( $stored !== $item['after'] ) {
		WP_CLI::warning( 'page ' . $id . ': stored content differs from the seed (' . strlen( $stored ) . ' vs ' . strlen( $item['after'] ) . ' bytes). Inspect before going further; restore with the backup above if needed.' );
	} else {
		WP_CLI::log( 'page ' . $id . ': stored ' . strlen( $stored ) . ' bytes, identical to the seed' );
	}
}
WP_CLI::success( 'Home blocks written. Check /fr/ and /en/, then open both pages in the editor: no block may be reported invalid.' );

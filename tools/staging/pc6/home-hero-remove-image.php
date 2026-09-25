<?php
/**
 * Remove the hero image block from the home pages (318 FR, 319 EN): since theme 1.3.3 the
 * hero picture is the section background (Manus hero_fond_web_16x9), not a content image.
 *
 *   wp eval-file tools/staging/pc6/home-hero-remove-image.php            # dry-run
 *   KH_APPLY=1 KH_CONFIRM=home-hero-remove-image wp eval-file ...        # apply
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once dirname( __DIR__ ) . '/pc4/lib.php';
global $wpdb;
$ctx  = kh_pc4_boot( 'home-hero-remove-image', 'home-hero-remove-image' );
$plan = array();
foreach ( array( 318, 319 ) as $id ) {
	$post = get_post( $id );
	if ( ! $post || 'page' !== $post->post_type ) { WP_CLI::error( 'Page ' . $id . ' missing.' ); }
	$pattern = '#\s*<!-- wp:image [^>]*"className":"kh-home-hero__art"[^>]*-->.*?<!-- /wp:image -->\s*#s';
	$after   = preg_replace( $pattern, "\n", $post->post_content, 1, $n );
	if ( 1 !== $n ) { WP_CLI::log( 'page ' . $id . ': no hero image block, nothing to do' ); continue; }
	WP_CLI::log( sprintf( 'page %d: %d -> %d bytes, hero image block removed', $id, strlen( $post->post_content ), strlen( $after ) ) );
	$plan[ $id ] = array( 'before' => $post->post_content, 'after' => $after );
}
if ( ! $plan ) { WP_CLI::success( 'Nothing to change.' ); return; }
if ( ! $ctx['apply'] ) { WP_CLI::success( 'Dry run: ' . count( $plan ) . ' page(s) would change. No write.' ); return; }
$restore = array();
foreach ( $plan as $id => $item ) {
	$restore[] = array( 'kind' => 'row', 'table' => $wpdb->posts, 'where' => array( 'ID' => $id ), 'data' => array( 'post_content' => $item['before'] ) );
}
kh_pc4_backup( $ctx, 'pc6-hero-post-content', $restore );
kses_remove_filters();
foreach ( $plan as $id => $item ) {
	$r = wp_update_post( wp_slash( array( 'ID' => $id, 'post_content' => $item['after'] ) ), true );
	if ( is_wp_error( $r ) ) { WP_CLI::error( 'Update failed for ' . $id . ': ' . $r->get_error_message() ); }
	WP_CLI::log( 'page ' . $id . ': stored, identical=' . var_export( get_post( $id )->post_content === $item['after'], true ) );
}
WP_CLI::success( 'Hero image block removed; the theme paints the hero background.' );

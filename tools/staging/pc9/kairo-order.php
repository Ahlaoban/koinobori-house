<?php
/**
 * PC9: Kaïro products in episode order on the world page (Alain, 2026-09-25): 1 La Promesse de la Mer,
 * 2 L'Ombre sur les Flots, 3 Le Seuil Interdit, 4 Les Voix du Pont. The default WooCommerce sorting is
 * « menu_order, then title »; with every menu_order at 0, the titles put « L'Ombre » before « La Promesse ».
 * Sets menu_order 1..4 on the French products and their translations. Editable afterwards in the admin
 * (Products > Sorting).
 *
 *   wp eval-file tools/staging/pc9/kairo-order.php                            # dry-run
 *   KH_APPLY=1 KH_CONFIRM=kairo-order wp eval-file ...                        # apply
 *
 * Restore: tools/staging/pc4/restore.php with the backup (previous menu_order of each post).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once dirname( __DIR__ ) . '/pc4/lib.php';
global $wpdb;

$ctx = kh_pc4_boot( 'kairo-order', 'kairo-order' );
if ( ! function_exists( 'pll_get_post_translations' ) ) {
	WP_CLI::error( 'Polylang must be loaded (no --skip-plugins).' );
}
kh_pc4_require_innodb( $wpdb->posts );

// French product ID => [episode, expected name fragment].
$episodes = array(
	186 => array( 1, 'La Promesse de la Mer' ),
	195 => array( 2, 'Ombre sur les Flots' ),
	196 => array( 3, 'Le Seuil Interdit' ),
	197 => array( 4, 'Les Voix du Pont' ),
);

$plan = array();
foreach ( $episodes as $id => $episode ) {
	list( $order, $name ) = $episode;
	$post = get_post( $id );
	if ( ! $post || 'product' !== $post->post_type || false === strpos( $post->post_title, $name ) || 'fr' !== pll_get_post_language( $id ) ) {
		WP_CLI::error( "Product $id missing, not French, or not « $name »." );
	}
	foreach ( pll_get_post_translations( $id ) as $lang => $pid ) {
		$current = (int) get_post_field( 'menu_order', $pid );
		WP_CLI::log( sprintf( '%d (%s) « %s »: menu_order %d -> %d', $pid, $lang, get_the_title( $pid ), $current, $order ) );
		if ( $current !== $order ) {
			$plan[ (int) $pid ] = array( 'from' => $current, 'to' => $order );
		}
	}
}
if ( ! $plan ) {
	WP_CLI::success( 'Nothing to change.' );
	return;
}
if ( ! $ctx['apply'] ) {
	WP_CLI::success( 'Dry run: ' . count( $plan ) . ' product(s) would change. No write.' );
	return;
}
$restore = array();
foreach ( $plan as $pid => $item ) {
	$restore[] = array( 'kind' => 'row', 'table' => $wpdb->posts, 'where' => array( 'ID' => $pid ), 'data' => array( 'menu_order' => $item['from'] ) );
}
kh_pc4_backup( $ctx, 'pc9-kairo-order', $restore );
kh_pc4_transaction( static function () use ( $plan, $wpdb ) {
	foreach ( $plan as $pid => $item ) {
		if ( false === $wpdb->update( $wpdb->posts, array( 'menu_order' => $item['to'] ), array( 'ID' => $pid ) ) ) {
			throw new RuntimeException( 'Update failed for ' . $pid );
		}
	}
} );
foreach ( $plan as $pid => $item ) {
	clean_post_cache( $pid );
	wc_delete_product_transients( $pid );
	WP_CLI::log( sprintf( '%d: menu_order = %d', $pid, (int) get_post_field( 'menu_order', $pid ) ) );
}
WP_CLI::success( 'Kaïro in episode order.' );

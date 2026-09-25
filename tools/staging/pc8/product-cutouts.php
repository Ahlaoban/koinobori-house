<?php
/**
 * PC8: product photos cut out (transparent background) as the main image of the products that have a photo.
 * Alain, 2026-09-25: the enlarged koi must be fully opaque; with a white-background photo, only a blend mode
 * could hide the white, and the blend let the page show through the koi. Cut-outs made by
 * tools/images/koi-cutout.py from catalog/images/<collection>/<product>/main.jpg.
 *
 * For each French product below: checks the product and its current main image, imports the cut-out into the
 * Media Library (attached to the product), and sets it as the main image of the product and of its translations.
 * Old images are kept in the Media Library (nothing deleted).
 *
 *   wp eval-file tools/staging/pc8/product-cutouts.php                        # dry-run
 *   KH_APPLY=1 KH_CONFIRM=product-cutouts wp eval-file ...                    # apply
 *
 * Restore: tools/staging/pc4/restore.php with the backup written before any write (previous _thumbnail_id rows).
 * Idempotent: a product whose main image is already its cut-out is left alone.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once dirname( __DIR__ ) . '/pc4/lib.php';
global $wpdb;

$ctx = kh_pc4_boot( 'product-cutouts', 'product-cutouts' );
if ( ! function_exists( 'pll_get_post_translations' ) || ! function_exists( 'wc_get_product' ) ) {
	WP_CLI::error( 'Polylang and WooCommerce must be loaded (no --skip-plugins).' );
}
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$dir = __DIR__ . '/cutouts';
// French product ID => [expected name fragment, current image file stem, cut-out file].
$items = array(
	111 => array( 'Sakura Rouge', 'main', 'hanami-001-sakura-rouge-detoure.webp' ),
	186 => array( 'La Promesse de la Mer', 'kairo-001-la-promesse-de-la-mer', 'kairo-001-la-promesse-de-la-mer-detoure.webp' ),
	195 => array( 'Ombre sur les Flots', 'kairo-002-l-ombre-sur-les-flots', 'kairo-002-l-ombre-sur-les-flots-detoure.webp' ),
	196 => array( 'Le Seuil Interdit', 'kairo-003-le-seuil-interdit', 'kairo-003-le-seuil-interdit-detoure.webp' ),
	197 => array( 'Les Voix du Pont', 'kairo-004-les-voix-du-pont', 'kairo-004-les-voix-du-pont-detoure.webp' ),
	198 => array( 'Stars & Stripes', 'territoires-001-stars-stripes', 'territoires-001-stars-stripes-detoure.webp' ),
	199 => array( 'Breton', 'territoires-002-breton', 'territoires-002-breton-detoure.webp' ),
	205 => array( 'Bigouden', 'territoires-003-bigouden', 'territoires-003-bigouden-detoure.webp' ),
);

$plan = array();
foreach ( $items as $id => $item ) {
	list( $name, $stem, $file ) = $item;
	$product = wc_get_product( $id );
	if ( ! $product || 'fr' !== pll_get_post_language( $id ) || false === strpos( $product->get_name(), $name ) ) {
		WP_CLI::error( "Product $id missing, not French, or not « $name »." );
	}
	$path = $dir . '/' . $file;
	if ( ! is_readable( $path ) || 'image/webp' !== wp_check_filetype( $path )['type'] ) {
		WP_CLI::error( "Cut-out missing or not WebP: $file" );
	}
	$translations = pll_get_post_translations( $id );
	$current      = (int) get_post_thumbnail_id( $id );
	$current_file = $current ? pathinfo( (string) get_attached_file( $current ), PATHINFO_FILENAME ) : '';
	if ( false !== strpos( $current_file, '-detoure' ) ) {
		WP_CLI::log( "$id: already on its cut-out ($current_file), left untouched" );
		continue;
	}
	if ( 0 !== strpos( $current_file, $stem ) ) {
		WP_CLI::error( "$id: unexpected current image « $current_file » (expected $stem…)." );
	}
	foreach ( $translations as $lang => $tid ) {
		if ( (int) get_post_thumbnail_id( $tid ) !== $current ) {
			WP_CLI::error( "$id: translation $lang ($tid) has a different main image; not handled." );
		}
	}
	WP_CLI::log( sprintf( '%d « %s » (%s): %s -> %s', $id, $product->get_name(), implode( ',', array_map( static function ( $l, $t ) { return "$l=$t"; }, array_keys( $translations ), $translations ) ), $current_file, $file ) );
	$plan[ $id ] = array( 'file' => $path, 'posts' => array_values( array_map( 'intval', $translations ) ) );
}
if ( ! $plan ) {
	WP_CLI::success( 'Nothing to change.' );
	return;
}
if ( ! $ctx['apply'] ) {
	WP_CLI::success( 'Dry run: ' . count( $plan ) . ' product(s) would get their cut-out. No write.' );
	return;
}

$restore = array();
foreach ( $plan as $item ) {
	foreach ( $item['posts'] as $pid ) {
		$meta_id = (int) $wpdb->get_var( $wpdb->prepare( "SELECT meta_id FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_thumbnail_id'", $pid ) );
		kh_pc4_db_check( 'thumbnail meta ' . $pid );
		if ( ! $meta_id ) {
			WP_CLI::error( "No _thumbnail_id row for $pid." );
		}
		$restore[] = array( 'kind' => 'row', 'table' => $wpdb->postmeta, 'where' => array( 'meta_id' => $meta_id ),
			'data' => array( 'meta_value' => (string) get_post_meta( $pid, '_thumbnail_id', true ) ) );
	}
}
kh_pc4_backup( $ctx, 'pc8-product-cutouts', $restore );

// Media import happens outside a transaction (files and generated sizes); each step is checked.
foreach ( $plan as $id => $item ) {
	$tmp = wp_tempnam( basename( $item['file'] ) );
	if ( ! $tmp || ! copy( $item['file'], $tmp ) ) {
		WP_CLI::error( "Cannot copy the cut-out for $id to a temporary file." );
	}
	$att = media_handle_sideload( array( 'name' => basename( $item['file'] ), 'tmp_name' => $tmp ), $id );
	if ( is_wp_error( $att ) ) {
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		WP_CLI::error( "Import failed for $id: " . $att->get_error_message() );
	}
	update_post_meta( $att, '_kh_asset', 'pc8-cutout' );
	if ( function_exists( 'pll_set_post_language' ) && pll_get_post_language( get_post_thumbnail_id( $id ) ) ) {
		pll_set_post_language( $att, 'fr' ); // Same convention as the image it replaces.
	}
	foreach ( $item['posts'] as $pid ) {
		set_post_thumbnail( $pid, $att );
		$ok = (int) get_post_thumbnail_id( $pid ) === (int) $att;
		WP_CLI::log( sprintf( '%d: main image = attachment %d (%s), stored=%s', $pid, $att, basename( (string) get_attached_file( $att ) ), var_export( $ok, true ) ) );
		if ( ! $ok ) {
			WP_CLI::error( "Main image not stored for $pid." );
		}
		wc_delete_product_transients( $pid );
	}
}
WP_CLI::success( 'Cut-outs set as main images. Old images kept in the Media Library.' );

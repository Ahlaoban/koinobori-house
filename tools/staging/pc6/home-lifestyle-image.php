<?php
/**
 * Lifestyle & Koi movement of the home (pages 318 FR, 319 EN): add Manus's illustration
 * (pack v2, 04_illustrations_editoriales/lifestyle_koi_hero: "Hero ou article vedette du
 * magazine Lifestyle & Koi") as an image block under the section title. The picture goes to
 * the media library once (meta _kh_asset), so it can be replaced from the editor.
 *
 *   KH_MEDIA=/path/lifestyle-koi-manus.webp wp eval-file tools/staging/pc6/home-lifestyle-image.php
 *   KH_APPLY=1 KH_CONFIRM=home-lifestyle-image KH_MEDIA=... wp eval-file ...
 *
 * Idempotent: a page that already holds the block is left alone; an edited title is reported, not guessed.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once dirname( __DIR__ ) . '/pc4/lib.php';
global $wpdb;

$ctx   = kh_pc4_boot( 'home-lifestyle-image', 'home-lifestyle-image' );
$media = (string) getenv( 'KH_MEDIA' );
if ( '' === $media || ! is_readable( $media ) ) {
	WP_CLI::error( 'KH_MEDIA must point to the prepared WebP file.' );
}
if ( ! function_exists( 'koinobori_child_block' ) ) {
	WP_CLI::error( 'Child theme not loaded (run without --skip-themes).' );
}
$alts = array(
	318 => 'Intérieur japonais, un koinobori suspendu au mur',
	319 => 'Japanese interior with a koinobori hanging on the wall',
);
$found      = get_posts( array(
	'post_type' => 'attachment', 'post_status' => 'inherit', 'meta_key' => '_kh_asset',
	'meta_value' => 'lifestyle_koi_hero', 'fields' => 'ids', 'numberposts' => 1,
) );
$attachment = $found ? (int) $found[0] : 0;
WP_CLI::log( 'media=' . basename( $media ) . ' sha256=' . hash_file( 'sha256', $media ) . ' existing_attachment=' . $attachment );

// The seeded Lifestyle & Koi section title, and only it.
$pattern = '#(<!-- wp:heading \{[^}]*"className":"kh-title"[^}]*\} -->\s*<h2 [^>]*>Lifestyle &amp; Koi</h2>\s*<!-- /wp:heading -->)#';
$plan    = array();
foreach ( $alts as $id => $alt ) {
	$post = get_post( $id );
	if ( ! $post || 'page' !== $post->post_type ) {
		WP_CLI::error( 'Page ' . $id . ' missing.' );
	}
	if ( false !== strpos( $post->post_content, 'kh-home-living__img' ) ) {
		WP_CLI::log( 'page ' . $id . ': image already present, left untouched' );
		continue;
	}
	if ( 1 !== preg_match_all( $pattern, $post->post_content ) ) {
		WP_CLI::warning( 'page ' . $id . ': Lifestyle & Koi title not found as seeded (edited?), skipped' );
		continue;
	}
	$plan[ $id ] = array( 'before' => $post->post_content, 'alt' => $alt );
	WP_CLI::log( 'page ' . $id . ': image block will be inserted under the Lifestyle & Koi title' );
}
if ( ! $plan ) {
	WP_CLI::success( 'Nothing to change.' );
	return;
}
if ( ! $ctx['apply'] ) {
	WP_CLI::success( 'Dry run: ' . count( $plan ) . ' page(s) would change' . ( $attachment ? '' : ', 1 image would be imported' ) . '. No write.' );
	return;
}

$restore = array();
foreach ( $plan as $id => $item ) {
	$restore[] = array( 'kind' => 'row', 'table' => $wpdb->posts, 'where' => array( 'ID' => $id ), 'data' => array( 'post_content' => $item['before'] ) );
}
kh_pc4_backup( $ctx, 'pc6-lifestyle-post-content', $restore );

if ( ! $attachment ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$tmp = wp_tempnam( 'lifestyle-koi-manus.webp' );
	if ( ! copy( $media, $tmp ) ) {
		WP_CLI::error( 'Cannot copy media to a temporary file.' );
	}
	$attachment = media_handle_sideload( array( 'name' => 'lifestyle-koi-manus.webp', 'tmp_name' => $tmp ), 0, 'Lifestyle & Koi' );
	if ( is_wp_error( $attachment ) ) {
		WP_CLI::error( 'Import failed: ' . $attachment->get_error_message() );
	}
	update_post_meta( $attachment, '_kh_asset', 'lifestyle_koi_hero' );
	update_post_meta( $attachment, '_wp_attachment_image_alt', $alts[318] );
	WP_CLI::log( 'imported attachment ' . $attachment );
}
$src = wp_get_attachment_image_url( $attachment, 'large' );
if ( ! $src ) {
	WP_CLI::error( 'No URL for attachment ' . $attachment );
}

kses_remove_filters();
foreach ( $plan as $id => $item ) {
	$block = koinobori_child_block(
		'image',
		array( 'id' => $attachment, 'sizeSlug' => 'large', 'linkDestination' => 'none', 'className' => 'kh-home-living__img' ),
		'<figure class="wp-block-image size-large kh-home-living__img"><img src="' . esc_url( $src ) . '" alt="' . esc_attr( $item['alt'] ) . '" class="wp-image-' . (int) $attachment . '"/></figure>'
	);
	$after = preg_replace_callback( $pattern, static function ( $m ) use ( $block ) {
		return $m[1] . "\n\n" . rtrim( $block );
	}, $item['before'], 1 );
	$r = wp_update_post( wp_slash( array( 'ID' => $id, 'post_content' => $after ) ), true );
	if ( is_wp_error( $r ) ) {
		WP_CLI::error( 'Update failed for ' . $id . ': ' . $r->get_error_message() );
	}
	$stored = get_post( $id )->post_content;
	WP_CLI::log( 'page ' . $id . ': stored ' . strlen( $stored ) . ' bytes, identical=' . var_export( $stored === $after, true ) );
}
WP_CLI::success( 'Lifestyle & Koi illustration added (attachment ' . $attachment . ').' );

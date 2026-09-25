<?php
/**
 * "Les cinq mondes" of the home (pages 318 FR, 319 EN) as Manus's illustrated cards
 * (slide 5, files 0N_<monde>_1024x1792.webp). For each world card of the content:
 *  - adds the class kh-world--<monde> (the theme paints the card's illustration from it);
 *  - adds the Japanese name under the title (editable paragraph, class kh-world__kanji).
 * The world is recognised from the category link of the card (FR and EN slugs).
 *
 *   wp eval-file tools/staging/pc6/home-worlds-cards.php                       # dry-run
 *   KH_APPLY=1 KH_CONFIRM=home-worlds-cards wp eval-file ...                   # apply
 *
 * Idempotent: a card that already has its world class is left alone.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once dirname( __DIR__ ) . '/pc4/lib.php';
global $wpdb;

$ctx = kh_pc4_boot( 'home-worlds-cards', 'home-worlds-cards' );
if ( ! function_exists( 'koinobori_child_block_p' ) ) {
	WP_CLI::error( 'Child theme not loaded (run without --skip-themes).' );
}
// Category slug (FR / EN) => world key, Japanese name as on Manus's cards.
$worlds = array(
	'mer' => 'mer', 'sea' => 'mer',
	'motifs' => 'motifs', 'patterns' => 'motifs',
	'hanami' => 'hanami', 'hanami-en' => 'hanami',
	'kairo' => 'kairo', 'kairo-en' => 'kairo',
	'territoires' => 'territoires', 'lands' => 'territoires',
);
$kanji  = array( 'mer' => '海', 'kairo' => '回廊', 'hanami' => '花見', 'motifs' => '文様', 'territoires' => '地域' );

$card = '#<!-- wp:group \{"tagName":"article","className":"kh-home-world"\} -->\s*<article class="wp-block-group kh-home-world">(.*?)</article>\s*<!-- /wp:group -->#s';
$plan = array();
foreach ( array( 318, 319 ) as $id ) {
	$post = get_post( $id );
	if ( ! $post || 'page' !== $post->post_type ) {
		WP_CLI::error( 'Page ' . $id . ' missing.' );
	}
	$seen  = array();
	$after = preg_replace_callback( $card, static function ( $m ) use ( $worlds, $kanji, &$seen ) {
		if ( ! preg_match( '#/categorie-produit/([a-z0-9-]+)/#', $m[1], $slug ) || ! isset( $worlds[ $slug[1] ] ) ) {
			return $m[0]; // Unknown card: never guess.
		}
		$key    = $worlds[ $slug[1] ];
		$seen[] = $key;
		$class  = 'kh-home-world kh-world--' . $key;
		$inner  = preg_replace( '#(<!-- /wp:heading -->)#', "$1\n\n" . rtrim( koinobori_child_block_p( $kanji[ $key ], 'kh-world__kanji' ) ), $m[1], 1 );
		return '<!-- wp:group {"tagName":"article","className":"' . $class . '"} -->' . "\n"
			. '<article class="wp-block-group ' . $class . '">' . $inner . '</article>' . "\n" . '<!-- /wp:group -->';
	}, $post->post_content );
	if ( ! $seen ) {
		WP_CLI::log( 'page ' . $id . ': no plain world card left (already done or edited), untouched' );
		continue;
	}
	WP_CLI::log( sprintf( 'page %d: %d card(s) -> %s, %d -> %d bytes', $id, count( $seen ), implode( ',', $seen ), strlen( $post->post_content ), strlen( $after ) ) );
	$plan[ $id ] = array( 'before' => $post->post_content, 'after' => $after );
}
if ( ! $plan ) {
	WP_CLI::success( 'Nothing to change.' );
	return;
}
if ( ! $ctx['apply'] ) {
	WP_CLI::success( 'Dry run: ' . count( $plan ) . ' page(s) would change. No write.' );
	return;
}
$restore = array();
foreach ( $plan as $id => $item ) {
	$restore[] = array( 'kind' => 'row', 'table' => $wpdb->posts, 'where' => array( 'ID' => $id ), 'data' => array( 'post_content' => $item['before'] ) );
}
kh_pc4_backup( $ctx, 'pc6-worlds-post-content', $restore );
kses_remove_filters();
foreach ( $plan as $id => $item ) {
	$r = wp_update_post( wp_slash( array( 'ID' => $id, 'post_content' => $item['after'] ) ), true );
	if ( is_wp_error( $r ) ) {
		WP_CLI::error( 'Update failed for ' . $id . ': ' . $r->get_error_message() );
	}
	WP_CLI::log( 'page ' . $id . ': stored, identical=' . var_export( get_post( $id )->post_content === $item['after'], true ) );
}
WP_CLI::success( 'World cards marked.' );

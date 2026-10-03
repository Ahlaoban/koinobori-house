<?php
/**
 * PC11: replace the old seller address (1 rue du Marais, 29730 Treffiagat) by the shop address
 * (34 quater rue de la Marine, 29730 Le Guilvinec) on the STAGING site. Decision Alain 2026-10-03:
 * everywhere, return addresses included (Ternand stays as second return address).
 *
 * Scope: pages and other content (not revisions, not orders), options, Fluent Forms form meta.
 * Out of scope on purpose: customer data (orders, order addresses, sessions, customer lookup, user meta),
 * revisions (history) and FluentSMTP logs (history, purged after 14 days).
 *
 *   wp eval-file tools/staging/pc11/adresse-replace.php                                        # dry-run
 *   KH_APPLY=1 KH_CONFIRM=adresse-replace wp eval-file tools/staging/pc11/adresse-replace.php  # apply
 *
 * Apply writes a private backup first (restore with tools/staging/pc4/restore.php) and runs in a transaction.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once dirname( __DIR__ ) . '/pc4/lib.php';
global $wpdb;

$ctx = kh_pc4_boot( 'adresse-replace', 'adresse-replace' );

$replace = static function ( $text ) {
	$text = preg_replace( '/1,?\s*rue du marais/iu', '34 quater rue de la Marine', $text );
	$text = preg_replace( '/29730\s+treffiagat/iu', '29730 Le Guilvinec', $text );
	return preg_replace( '/treffiagat/iu', 'Le Guilvinec', $text );
};
$deep = static function ( $value ) use ( &$deep, $replace ) {
	if ( is_string( $value ) ) {
		return is_serialized( $value ) ? serialize( $deep( unserialize( $value, array( 'allowed_classes' => false ) ) ) ) : $replace( $value );
	}
	if ( is_array( $value ) ) {
		foreach ( $value as $k => $v ) { $value[ $k ] = $deep( $v ); }
	}
	return $value;
};
$like    = '%' . $wpdb->esc_like( 'treffiagat' ) . '%';
$like2   = '%' . $wpdb->esc_like( 'rue du marais' ) . '%';
$excerpt = static function ( $text ) {
	$text = preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $text ) );
	$pos  = stripos( $text, 'guilvinec' );
	return substr( $text, max( 0, (int) $pos - 80 ), 200 );
};

$restore = array();
$writes  = array();

/* ---- Content: every post type except revisions and orders ---- */
$posts = $wpdb->get_results( $wpdb->prepare(
	"SELECT ID, post_type, post_status, post_title, post_content, post_excerpt FROM {$wpdb->posts}
	 WHERE post_type NOT IN ('revision','shop_order','shop_order_placehold','shop_order_refund')
	   AND ( LOWER(post_content) LIKE %s OR LOWER(post_content) LIKE %s OR LOWER(post_title) LIKE %s OR LOWER(post_excerpt) LIKE %s )",
	$like, $like2, $like, $like
) );
kh_pc4_db_check( 'posts' );
foreach ( $posts as $p ) {
	$new = array( 'post_title' => $replace( $p->post_title ), 'post_content' => $replace( $p->post_content ), 'post_excerpt' => $replace( $p->post_excerpt ) );
	WP_CLI::log( sprintf( '[post] %d %s %s "%s" :: …%s…', $p->ID, $p->post_type, $p->post_status, $p->post_title, $excerpt( $new['post_content'] ) ) );
	$restore[] = array( 'kind' => 'row', 'table' => $wpdb->posts, 'where' => array( 'ID' => (int) $p->ID ),
		'data' => array( 'post_title' => $p->post_title, 'post_content' => $p->post_content, 'post_excerpt' => $p->post_excerpt ) );
	$writes[]  = static function () use ( $wpdb, $p, $new ) {
		if ( false === $wpdb->update( $wpdb->posts, $new, array( 'ID' => (int) $p->ID ) ) ) {
			throw new RuntimeException( 'post ' . $p->ID );
		}
		clean_post_cache( (int) $p->ID );
	};
}

/* ---- Options (transients and Stripe checkout caches, which hold customer data, excluded) ---- */
$options = $wpdb->get_col( $wpdb->prepare(
	"SELECT option_name FROM {$wpdb->options}
	 WHERE ( LOWER(option_value) LIKE %s OR LOWER(option_value) LIKE %s )
	   AND option_name NOT LIKE %s AND option_name NOT LIKE %s AND option_name NOT LIKE %s",
	$like, $like2, $wpdb->esc_like( '_transient_' ) . '%', $wpdb->esc_like( '_site_transient_' ) . '%',
	$wpdb->esc_like( 'wcstripe_cache_' ) . '%'
) );
kh_pc4_db_check( 'options' );
foreach ( $options as $name ) {
	$old = get_option( $name );
	$new = $deep( $old );
	WP_CLI::log( sprintf( '[option] %s :: …%s…', $name, $excerpt( is_scalar( $new ) ? $new : wp_json_encode( $new, JSON_UNESCAPED_UNICODE ) ) ) );
	$restore[] = array( 'kind' => 'option', 'name' => $name, 'value' => $old );
	$writes[]  = static function () use ( $name, $new ) { update_option( $name, $new ); };
}

/* ---- Fluent Forms form meta (confirmation messages) ---- */
$ff = $wpdb->prefix . 'fluentform_form_meta';
if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $ff ) ) === $ff ) {
	$rows = $wpdb->get_results( $wpdb->prepare( "SELECT id, form_id, meta_key, value FROM {$ff} WHERE LOWER(value) LIKE %s OR LOWER(value) LIKE %s", $like, $like2 ) );
	kh_pc4_db_check( 'fluentform_form_meta' );
	foreach ( $rows as $r ) {
		$new = $deep( (string) $r->value );
		WP_CLI::log( sprintf( '[form] meta %d form %d %s :: …%s…', $r->id, $r->form_id, $r->meta_key, $excerpt( $new ) ) );
		$restore[] = array( 'kind' => 'row', 'table' => $ff, 'where' => array( 'id' => (int) $r->id ), 'data' => array( 'value' => $r->value ) );
		$writes[]  = static function () use ( $wpdb, $ff, $r, $new ) {
			if ( false === $wpdb->update( $ff, array( 'value' => $new ), array( 'id' => (int) $r->id ) ) ) {
				throw new RuntimeException( 'form meta ' . $r->id );
			}
		};
	}
}

WP_CLI::log( sprintf( '[total] %d change(s)', count( $writes ) ) );
if ( ! $ctx['apply'] ) {
	WP_CLI::success( 'Dry run: nothing written.' );
	return;
}
if ( ! $writes ) {
	WP_CLI::success( 'Nothing to replace.' );
	return;
}
foreach ( array( $wpdb->posts, $wpdb->options, $ff ) as $table ) {
	kh_pc4_require_innodb( $table );
}
kh_pc4_backup( $ctx, 'pc11-adresse', $restore );
kh_pc4_transaction( static function () use ( $writes ) {
	foreach ( $writes as $write ) { $write(); }
} );
wp_cache_flush();
WP_CLI::success( count( $writes ) . ' change(s) applied.' );

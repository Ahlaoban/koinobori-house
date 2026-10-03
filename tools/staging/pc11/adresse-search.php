<?php
/**
 * PC11: read-only search of the STAGING database for the old seller address (Treffiagat), before it is
 * replaced by the shop address (34 quater rue de la Marine, 29730 Le Guilvinec). Decision Alain 2026-10-03.
 *
 * Scans every text column of every table with the site prefix. For each hit prints the table, the column,
 * the row key and a short excerpt around the match. Writes nothing.
 *
 *   wp eval-file tools/staging/pc11/adresse-search.php
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once dirname( __DIR__ ) . '/pc4/lib.php';
global $wpdb;

$ctx = kh_pc4_boot( 'adresse-search', 'adresse-search' );
if ( $ctx['apply'] ) {
	WP_CLI::error( 'This script is read-only: run it without KH_APPLY.' );
}

$needles = array( 'treffiagat', 'rue du marais' );
$columns = $wpdb->get_results( $wpdb->prepare(
	"SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.COLUMNS
	 WHERE TABLE_SCHEMA = %s AND TABLE_NAME LIKE %s
	   AND DATA_TYPE IN ('char','varchar','tinytext','text','mediumtext','longtext')
	 ORDER BY TABLE_NAME, ORDINAL_POSITION",
	DB_NAME, $wpdb->esc_like( $wpdb->prefix ) . '%'
) );
kh_pc4_db_check( 'columns' );

$keys = array();
$hits = 0;
foreach ( $columns as $c ) {
	$table = $c->TABLE_NAME;
	if ( ! isset( $keys[ $table ] ) ) {
		$pk = $wpdb->get_results( "SHOW KEYS FROM `{$table}` WHERE Key_name = 'PRIMARY'" );
		$keys[ $table ] = $pk ? $pk[0]->Column_name : null;
	}
	$key   = $keys[ $table ];
	$col   = $c->COLUMN_NAME;
	$where = implode( ' OR ', array_map( static function ( $n ) use ( $wpdb, $col ) {
		return $wpdb->prepare( "LOWER(`{$col}`) LIKE %s", '%' . $wpdb->esc_like( $n ) . '%' );
	}, $needles ) );
	$select = $key ? "`{$key}` AS k, `{$col}` AS v" : "NULL AS k, `{$col}` AS v";
	$rows   = $wpdb->get_results( "SELECT {$select} FROM `{$table}` WHERE {$where} LIMIT 50" );
	kh_pc4_db_check( $table . '.' . $col );
	foreach ( $rows as $r ) {
		$hits++;
		$v   = (string) $r->v;
		$pos = false;
		foreach ( $needles as $n ) {
			$p = stripos( $v, $n );
			if ( false !== $p && ( false === $pos || $p < $pos ) ) { $pos = $p; }
		}
		$from    = max( 0, (int) $pos - 90 );
		$excerpt = preg_replace( '/\s+/', ' ', wp_strip_all_tags( substr( $v, $from, 220 ) ) );
		$label   = '';
		if ( $wpdb->posts === $table && $key ) {
			$post  = get_post( (int) $r->k );
			$label = $post ? sprintf( ' [%s %s "%s"]', $post->post_type, $post->post_status, $post->post_title ) : '';
		} elseif ( $wpdb->postmeta === $table && $key ) {
			$meta  = $wpdb->get_row( $wpdb->prepare( "SELECT post_id, meta_key FROM {$wpdb->postmeta} WHERE meta_id = %d", (int) $r->k ) );
			$label = $meta ? sprintf( ' [post %d %s, meta %s]', $meta->post_id, get_post_type( (int) $meta->post_id ), $meta->meta_key ) : '';
		} elseif ( $wpdb->options === $table && $key ) {
			$label = ' [option ' . $wpdb->get_var( $wpdb->prepare( "SELECT option_name FROM {$wpdb->options} WHERE option_id = %d", (int) $r->k ) ) . ']';
		} elseif ( $wpdb->usermeta === $table && $key ) {
			$label = ' [usermeta ' . $wpdb->get_var( $wpdb->prepare( "SELECT meta_key FROM {$wpdb->usermeta} WHERE umeta_id = %d", (int) $r->k ) ) . ']';
		}
		WP_CLI::log( sprintf( '[hit] %s.%s %s=%s%s :: …%s…', $table, $col, (string) $key, (string) $r->k, $label, $excerpt ) );
	}
}
WP_CLI::log( sprintf( '[total] %d hit(s) in %d text columns scanned', $hits, count( $columns ) ) );

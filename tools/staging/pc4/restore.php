<?php
/**
 * Restore a PC4 backup written by lib.php (kh_pc4_backup).
 *
 *   wp eval-file tools/staging/pc4/restore.php <backup.json>                        # dry-run
 *   KH_APPLY=1 KH_CONFIRM=restore wp eval-file tools/staging/pc4/restore.php <backup.json>
 *
 * Kinds: option (update_option with the saved value), row (update by where
 * clause), polylang_group (pll_save_post_translations with the saved group;
 * a single-element group unlinks the post). Polylang kinds need plugins loaded.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once __DIR__ . '/lib.php';
global $wpdb;

$ctx  = kh_pc4_boot( 'restore', 'restore' );
$file = (string) ( $args[0] ?? '' );
$real = '' === $file ? '' : (string) realpath( $file );
if ( '' === $real || 0 !== strpos( $real, KH_PC4_BACKUPS . '/' ) ) {
	WP_CLI::error( 'Backup path must be a file inside ' . KH_PC4_BACKUPS );
}
$backup = json_decode( (string) file_get_contents( $real ), true );
if ( ! is_array( $backup ) || ( $backup['db'] ?? '' ) !== KH_PC4_DB || ( $backup['host'] ?? '' ) !== KH_PC4_HOST || empty( $backup['restore'] ) ) {
	WP_CLI::error( 'Backup does not target this staging or has no restore records.' );
}
WP_CLI::log( 'backup=' . basename( $real ) . ' script=' . ( $backup['script'] ?? '?' ) . ' created=' . ( $backup['created_utc'] ?? '?' ) . ' records=' . count( $backup['restore'] ) );
foreach ( $backup['restore'] as $i => $record ) {
	WP_CLI::log( '#' . $i . ' ' . $record['kind'] . ' ' . wp_json_encode( array_diff_key( $record, array( 'value' => 1, 'data' => 1, 'group' => 1 ) ) ) );
}
if ( ! $ctx['apply'] ) {
	WP_CLI::success( 'Dry run: nothing restored.' );
	return;
}

kh_pc4_transaction( function () use ( $backup, $wpdb ) {
	foreach ( $backup['restore'] as $record ) {
		switch ( $record['kind'] ) {
			case 'option':
				update_option( $record['name'], $record['value'] );
				break;
			case 'row':
				if ( 0 !== strpos( $record['table'], $wpdb->prefix ) ) {
					throw new RuntimeException( 'Refusing table outside prefix: ' . $record['table'] );
				}
				if ( false === $wpdb->update( $record['table'], $record['data'], $record['where'] ) ) {
					throw new RuntimeException( 'row restore failed on ' . $record['table'] );
				}
				break;
			case 'polylang_group':
				if ( ! function_exists( 'pll_save_post_translations' ) ) {
					throw new RuntimeException( 'Polylang not loaded.' );
				}
				pll_save_post_translations( $record['group'] );
				break;
			default:
				throw new RuntimeException( 'Unknown record kind: ' . $record['kind'] );
		}
	}
} );
WP_CLI::success( 'Restored ' . count( $backup['restore'] ) . ' record(s). Purge LiteSpeed.' );

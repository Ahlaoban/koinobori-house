<?php
/**
 * I5: site title (blogname) is empty on staging; set "Koinobori House".
 *
 *   wp eval-file tools/staging/pc4/i5-blogname.php
 *   KH_APPLY=1 KH_CONFIRM=i5-blogname wp eval-file ...
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once __DIR__ . '/lib.php';

const KH_I5_BLOGNAME = 'Koinobori House';

$ctx     = kh_pc4_boot( 'i5-blogname', 'i5-blogname' );
$current = (string) get_option( 'blogname' );
WP_CLI::log( 'before=' . wp_json_encode( $current ) );
if ( KH_I5_BLOGNAME === $current ) {
	WP_CLI::success( 'blogname already set. Nothing to do.' );
	return;
}
if ( '' !== $current ) {
	WP_CLI::error( 'blogname is not empty ("' . $current . '"); refusing to overwrite without a decision.' );
}
if ( ! $ctx['apply'] ) {
	WP_CLI::success( 'Dry run: would set blogname = "' . KH_I5_BLOGNAME . '". No write.' );
	return;
}
kh_pc4_backup( $ctx, 'i5-option-blogname', array( array( 'kind' => 'option', 'name' => 'blogname', 'value' => $current ) ) );
kh_pc4_transaction( function () {
	if ( ! update_option( 'blogname', KH_I5_BLOGNAME ) ) {
		throw new RuntimeException( 'update_option returned false.' );
	}
} );
WP_CLI::log( 'after=' . wp_json_encode( get_option( 'blogname' ) ) );
WP_CLI::success( 'blogname set. Check the <title> of /fr/ and /en/, then purge LiteSpeed.' );

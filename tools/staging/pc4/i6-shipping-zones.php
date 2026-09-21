<?php
/**
 * I6: shipping zones. Staging has only "France métropolitaine"; every other
 * address gets no method (checkout blocked). Target per CLAUDE.md: a EU zone
 * (Colissimo Zone A + Mondial Relay for BE/LU/ES/PT/NL/IT/PL) and USA in
 * "contact us" mode, i.e. selling/shipping countries restricted.
 *
 * This script is REPORT-ONLY until tariffs and the country perimeter are
 * approved by Alain: KH_I6_TARIFFS_APPROVED must be set to true in a reviewed
 * commit before KH_APPLY has any effect.
 *
 *   wp eval-file tools/staging/pc4/i6-shipping-zones.php
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once __DIR__ . '/lib.php';
global $wpdb;

const KH_I6_TARIFFS_APPROVED = false;

$ctx = kh_pc4_boot( 'i6-shipping-zones', 'i6-shipping-zones' );
if ( $ctx['apply'] && ! KH_I6_TARIFFS_APPROVED ) {
	WP_CLI::error( 'Tariffs and perimeter not approved: apply is disabled by design.' );
}

$p     = $wpdb->prefix;
$zones = $wpdb->get_results( "SELECT zone_id, zone_name, zone_order FROM {$p}woocommerce_shipping_zones ORDER BY zone_order, zone_id", ARRAY_A );
kh_pc4_db_check( 'zones' );
$zones[] = array( 'zone_id' => 0, 'zone_name' => 'Rest of the world', 'zone_order' => 999 );
foreach ( $zones as $zone ) {
	$id   = (int) $zone['zone_id'];
	$locs = $wpdb->get_col( $wpdb->prepare( "SELECT CONCAT(location_type, ':', location_code) FROM {$p}woocommerce_shipping_zone_locations WHERE zone_id = %d", $id ) );
	kh_pc4_db_check( 'locations ' . $id );
	$methods = $wpdb->get_results( $wpdb->prepare( "SELECT instance_id, method_id, is_enabled FROM {$p}woocommerce_shipping_zone_methods WHERE zone_id = %d ORDER BY method_order", $id ), ARRAY_A );
	kh_pc4_db_check( 'methods ' . $id );
	$m = array();
	foreach ( $methods as $method ) {
		$settings = get_option( 'woocommerce_' . $method['method_id'] . '_' . (int) $method['instance_id'] . '_settings' );
		$m[]      = $method['method_id'] . '#' . $method['instance_id'] . ( $method['is_enabled'] ? '' : '(off)' ) . ' title="' . ( is_array( $settings ) ? ( $settings['title'] ?? '' ) : '' ) . '"'
			. ( is_array( $settings ) && isset( $settings['cost'] ) ? ' cost=' . $settings['cost'] : '' )
			. ( is_array( $settings ) && isset( $settings['min_amount'] ) ? ' min_amount=' . $settings['min_amount'] : '' );
	}
	WP_CLI::log( 'zone ' . $id . ' "' . $zone['zone_name'] . '" locations=' . wp_json_encode( $locs ) . ' methods=' . wp_json_encode( $m ) );
}
WP_CLI::log( 'woocommerce_allowed_countries=' . wp_json_encode( get_option( 'woocommerce_allowed_countries' ) ) . ' specific=' . wp_json_encode( get_option( 'woocommerce_specific_allowed_countries' ) ) );
WP_CLI::log( 'woocommerce_ship_to_countries=' . wp_json_encode( get_option( 'woocommerce_ship_to_countries' ) ) . ' specific=' . wp_json_encode( get_option( 'woocommerce_specific_ship_to_countries' ) ) );

WP_CLI::log( 'PLAN (not applied): zone "Union européenne" = BE LU ES PT NL IT PL + other EU countries per doctrine (Colissimo Zone A real cost; Mondial Relay for BE/LU/ES/PT/NL/IT/PL); ship_to_countries restricted to FR + EU list; USA = contact form (no zone); DROM-COM = real Colissimo Outre-Mer cost pending quotes; UK/CH/non-EU Europe = contact form.' );
WP_CLI::success( 'Report only. Tariffs and perimeter must be approved before any zone or method is created.' );

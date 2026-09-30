<?php
/**
 * PC10: read-only report of the transactional base of the STAGING site (payments, mail, shipping, cookies,
 * security, backups, cache), to plan the work up to the launch of 2026-10-15.
 *
 * Writes nothing. Never prints a secret: for keys, tokens and passwords it only says whether a value is
 * present ("set") or not ("empty"); e-mail addresses are reduced to their domain.
 *
 *   wp eval-file tools/staging/pc10/socle-report.php
 *
 * Plugins and theme must be loaded (no --skip-plugins): the gateways and e-mails are read from WooCommerce.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once dirname( __DIR__ ) . '/pc4/lib.php';
global $wpdb;

$ctx = kh_pc4_boot( 'socle-report', 'socle-report' );
if ( $ctx['apply'] ) {
	WP_CLI::error( 'This script is read-only: run it without KH_APPLY.' );
}

$set    = static function ( $v ) { return ( is_string( $v ) || is_numeric( $v ) ) && '' !== trim( (string) $v ) ? 'set' : 'empty'; };
$domain = static function ( $v ) { $v = (string) $v; return false !== strpos( $v, '@' ) ? '…@' . substr( strrchr( $v, '@' ), 1 ) : ( '' === $v ? 'empty' : 'set' ); };
$line   = static function ( $section, $text ) { WP_CLI::log( sprintf( '[%s] %s', $section, $text ) ); };
$opt    = static function ( $name, $key = null ) {
	$v = get_option( $name, null );
	if ( null === $key ) { return $v; }
	return is_array( $v ) && array_key_exists( $key, $v ) ? $v[ $key ] : null;
};
$show   = static function ( $v ) { return null === $v ? 'absent' : ( is_scalar( $v ) ? (string) $v : wp_json_encode( $v ) ); };

/* ---- General ---- */
$line( 'site', 'blog_public (1 = indexable) = ' . $show( $opt( 'blog_public' ) ) );
$line( 'site', 'admin_email = ' . $domain( $opt( 'admin_email' ) ) );
$line( 'site', 'users by role = ' . wp_json_encode( count_users()['avail_roles'] ) );

/* ---- WooCommerce ---- */
foreach ( array( 'woocommerce_coming_soon', 'woocommerce_store_pages_only', 'woocommerce_default_country', 'woocommerce_allowed_countries',
	'woocommerce_specific_allowed_countries', 'woocommerce_ship_to_countries', 'woocommerce_specific_ship_to_countries',
	'woocommerce_enable_guest_checkout', 'woocommerce_enable_signup_and_login_from_checkout', 'woocommerce_terms_page_id',
	'woocommerce_calc_taxes', 'woocommerce_currency', 'woocommerce_manage_stock', 'woocommerce_email_from_name' ) as $name ) {
	$line( 'woocommerce', $name . ' = ' . $show( $opt( $name ) ) );
}
$line( 'woocommerce', 'woocommerce_email_from_address = ' . $domain( $opt( 'woocommerce_email_from_address' ) ) );
if ( function_exists( 'wc_get_order_statuses' ) ) {
	$counts = array();
	foreach ( array_keys( wc_get_order_statuses() ) as $status ) {
		$counts[ $status ] = (int) wc_orders_count( str_replace( 'wc-', '', $status ) );
	}
	$line( 'woocommerce', 'orders by status = ' . wp_json_encode( array_filter( $counts ) ) );
}

/* ---- Payment gateways: id, enabled, title only ---- */
if ( function_exists( 'WC' ) && WC()->payment_gateways() ) {
	foreach ( WC()->payment_gateways()->payment_gateways() as $gateway ) {
		$line( 'payments', sprintf( '%s enabled=%s title="%s"', $gateway->id, $gateway->enabled, wp_strip_all_tags( (string) $gateway->get_title() ) ) );
	}
}
$stripe = $opt( 'woocommerce_stripe_settings' );
if ( is_array( $stripe ) ) {
	$line( 'stripe', sprintf( 'enabled=%s testmode=%s | test keys: publishable=%s secret=%s webhook=%s | live keys: publishable=%s secret=%s webhook=%s',
		$stripe['enabled'] ?? 'absent', $stripe['testmode'] ?? 'absent',
		$set( $stripe['test_publishable_key'] ?? '' ), $set( $stripe['test_secret_key'] ?? '' ), $set( $stripe['test_webhook_secret'] ?? '' ),
		$set( $stripe['publishable_key'] ?? '' ), $set( $stripe['secret_key'] ?? '' ), $set( $stripe['webhook_secret'] ?? '' ) ) );
} else {
	$line( 'stripe', 'woocommerce_stripe_settings absent' );
}
$ppcp = $opt( 'woocommerce-ppcp-settings' );
if ( is_array( $ppcp ) ) {
	$line( 'paypal', sprintf( 'legacy settings: enabled=%s sandbox_on=%s | sandbox: client_id=%s secret=%s merchant=%s | live: client_id=%s secret=%s merchant=%s',
		$show( $ppcp['enabled'] ?? null ), $show( $ppcp['sandbox_on'] ?? null ),
		$set( $ppcp['client_id_sandbox'] ?? '' ), $set( $ppcp['client_secret_sandbox'] ?? '' ), $set( $ppcp['merchant_id_sandbox'] ?? '' ),
		$set( $ppcp['client_id_production'] ?? '' ), $set( $ppcp['client_secret_production'] ?? '' ), $set( $ppcp['merchant_id_production'] ?? '' ) ) );
}
$ppcp_common = $opt( 'woocommerce-ppcp-data-common' );
if ( is_array( $ppcp_common ) ) {
	$line( 'paypal', sprintf( 'new settings: use_sandbox=%s use_manual_connection=%s client_id=%s client_secret=%s merchant_connected=%s',
		$show( $ppcp_common['use_sandbox'] ?? null ), $show( $ppcp_common['use_manual_connection'] ?? null ),
		$set( $ppcp_common['client_id'] ?? '' ), $set( $ppcp_common['client_secret'] ?? '' ),
		$set( is_array( $ppcp_common['merchant'] ?? null ) ? ( $ppcp_common['merchant']['merchant_id'] ?? '' ) : '' ) ) );
}
if ( ! is_array( $ppcp ) && ! is_array( $ppcp_common ) ) {
	$line( 'paypal', 'no PayPal Payments settings stored' );
}

/* ---- WooCommerce e-mails: id and state only ---- */
if ( function_exists( 'WC' ) ) {
	foreach ( WC()->mailer()->get_emails() as $email ) {
		$line( 'wc-emails', sprintf( '%s enabled=%s customer=%s', $email->id, $email->is_enabled() ? 'yes' : 'no', $email->is_customer_email() ? 'yes' : 'no' ) );
	}
}

/* ---- FluentSMTP: provider and presence of credentials ---- */
$smtp = $opt( 'fluentmail-settings' );
if ( is_array( $smtp ) ) {
	$connections = is_array( $smtp['connections'] ?? null ) ? $smtp['connections'] : array();
	$line( 'smtp', 'connections = ' . count( $connections ) . ', default = ' . $set( is_array( $smtp['misc'] ?? null ) ? ( $smtp['misc']['default_connection'] ?? '' ) : '' ) );
	foreach ( $connections as $connection ) {
		$p = is_array( $connection['provider_settings'] ?? null ) ? $connection['provider_settings'] : array();
		$line( 'smtp', sprintf( 'provider=%s sender=%s host=%s port=%s auth=%s credentials: api_key=%s username=%s password=%s',
			$show( $p['provider'] ?? null ), $domain( $p['sender_email'] ?? '' ), $set( $p['host'] ?? '' ), $show( $p['port'] ?? null ), $show( $p['auth'] ?? null ),
			$set( $p['api_key'] ?? '' ), $set( $p['username'] ?? '' ), $set( $p['password'] ?? '' ) ) );
	}
} else {
	$line( 'smtp', 'fluentmail-settings absent: FluentSMTP not configured' );
}
$logs = $wpdb->prefix . 'fsmpt_email_logs';
if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $logs ) ) === $logs ) {
	$line( 'smtp', 'e-mail log by status = ' . wp_json_encode( $wpdb->get_results( "SELECT status, COUNT(*) AS n, MAX(created_at) AS last FROM {$logs} GROUP BY status", ARRAY_A ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL
}

/* ---- Fluent Forms notifications ---- */
$meta = $wpdb->prefix . 'fluentform_form_meta';
if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $meta ) ) === $meta ) {
	foreach ( $wpdb->get_results( "SELECT form_id, value FROM {$meta} WHERE meta_key = 'notifications' ORDER BY form_id", ARRAY_A ) as $row ) { // phpcs:ignore WordPress.DB.PreparedSQL
		$n = json_decode( (string) $row['value'], true );
		$line( 'forms', sprintf( 'form %d notification "%s" enabled=%s', $row['form_id'], wp_strip_all_tags( (string) ( $n['name'] ?? '' ) ), $show( $n['enabled'] ?? null ) ) );
	}
}

/* ---- Shipping ---- */
if ( class_exists( 'WC_Shipping_Zones' ) ) {
	$zones   = WC_Shipping_Zones::get_zones();
	$zones[] = array( 'zone_id' => 0 );
	foreach ( $zones as $z ) {
		$zone      = new WC_Shipping_Zone( (int) $z['zone_id'] );
		$locations = array_map( static function ( $l ) { return $l->code; }, $zone->get_zone_locations() );
		$line( 'shipping', sprintf( 'zone %d "%s" locations=%s', $zone->get_id(), $zone->get_zone_name(), implode( ',', $locations ) ) );
		foreach ( $zone->get_shipping_methods() as $method ) {
			$line( 'shipping', sprintf( '  %s#%d enabled=%s title="%s" cost=%s min_amount=%s requires=%s', $method->id, $method->instance_id, $method->enabled,
				wp_strip_all_tags( (string) $method->get_title() ), $show( $method->get_option( 'cost', null ) ), $show( $method->get_option( 'min_amount', null ) ), $show( $method->get_option( 'requires', null ) ) ) );
		}
	}
}

/* ---- Cookies, SEO, cache, security, backups ---- */
$line( 'complianz', 'cmplz_wizard_completed_once = ' . $show( $opt( 'cmplz_wizard_completed_once' ) ) );
$banners = $wpdb->prefix . 'cmplz_cookiebanners';
if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $banners ) ) === $banners ) {
	$line( 'complianz', 'cookie banners = ' . (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$banners}" ) ); // phpcs:ignore WordPress.DB.PreparedSQL
}
$cmplz = $opt( 'cmplz_options' );
if ( is_array( $cmplz ) ) {
	$line( 'complianz', sprintf( 'regions=%s uses_cookies=%s compile_statistics=%s', $show( $cmplz['regions'] ?? null ), $show( $cmplz['uses_cookies'] ?? null ), $show( $cmplz['compile_statistics'] ?? null ) ) );
}
$line( 'seo', 'seopress sitemap enabled = ' . $show( $opt( 'seopress_xml_sitemap_option_name', 'seopress_xml_sitemap_general_enable' ) ) );
$line( 'cache', 'litespeed.conf.cache = ' . $show( $opt( 'litespeed.conf.cache' ) ) );
$wf = $wpdb->base_prefix . 'wfconfig';
if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wf ) ) === $wf ) {
	foreach ( array( 'wafStatus', 'firewallEnabled', 'loginSecurityEnabled', 'scheduledScansEnabled', 'alertEmails' ) as $key ) {
		$v = $wpdb->get_var( $wpdb->prepare( "SELECT val FROM {$wf} WHERE name = %s", $key ) ); // phpcs:ignore WordPress.DB.PreparedSQL
		$line( 'wordfence', $key . ' = ' . ( 'alertEmails' === $key ? $domain( (string) $v ) : $show( $v ) ) );
	}
}
$wfls = $wpdb->base_prefix . 'wfls_2fa_secrets';
if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wfls ) ) === $wfls ) {
	$line( 'wordfence', 'users with two-factor = ' . (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wfls}" ) ); // phpcs:ignore WordPress.DB.PreparedSQL
}
foreach ( array( 'updraft_interval', 'updraft_interval_database', 'updraft_retain', 'updraft_retain_db', 'updraft_service' ) as $name ) {
	$line( 'backups', $name . ' = ' . $show( $opt( $name ) ) );
}
$last = $opt( 'updraft_last_backup' );
$line( 'backups', 'last backup = ' . ( is_array( $last ) && ! empty( $last['backup_time'] ) ? gmdate( 'c', (int) $last['backup_time'] ) . ' success=' . $show( $last['success'] ?? null ) : 'none recorded' ) );

WP_CLI::success( 'Read-only report done. Nothing written.' );

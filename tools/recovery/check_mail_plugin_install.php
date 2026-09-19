<?php
/**
 * Private clone only: verify FluentSMTP is loaded while every outbound path
 * remains blocked. This script never calls wp_mail() or opens a connection.
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! defined( 'KH2027_WEB_REVIEW' )
	|| ! KH2027_WEB_REVIEW || ! defined( 'KH2027_SANDBOX' ) || ! KH2027_SANDBOX ) {
	throw new RuntimeException( 'This helper is restricted to the private review clone.' );
}

global $wpdb;
require_once ABSPATH . 'wp-admin/includes/plugin.php';

$plugin_file = WP_PLUGIN_DIR . '/fluent-smtp/fluent-smtp.php';
$plugin_data = is_file( $plugin_file ) ? get_plugin_data( $plugin_file, false, false ) : array();
$raw_value = $wpdb->get_var( $wpdb->prepare(
	"SELECT option_value FROM {$wpdb->options} WHERE option_name = %s",
	'active_plugins'
) );
$raw_plugins = maybe_unserialize( $raw_value );
$visible_plugins = get_option( 'active_plugins', array() );
$http_result = apply_filters( 'pre_http_request', false, array(), 'https://example.invalid/' );

$checks = array(
	'file_present'       => is_file( $plugin_file ),
	'version_2_4_0'      => ( $plugin_data['Version'] ?? '' ) === '2.4.0',
	'active_in_database' => is_array( $raw_plugins ) && in_array( 'fluent-smtp/fluent-smtp.php', $raw_plugins, true ),
	'active_after_guard' => is_array( $visible_plugins ) && in_array( 'fluent-smtp/fluent-smtp.php', $visible_plugins, true ),
	'plugin_loaded'      => function_exists( 'fluentMail' ),
	'email_blocked'      => true === apply_filters( 'pre_wp_mail', null, array() ),
	'http_blocked'       => is_wp_error( $http_result ) && 'kh2027_offline' === $http_result->get_error_code(),
);

foreach ( $checks as $name => $passed ) {
	WP_CLI::log( $name . '=' . ( $passed ? 'yes' : 'no' ) );
}

if ( in_array( false, $checks, true ) ) {
	WP_CLI::error( 'FluentSMTP installation or review isolation check failed.' );
}

WP_CLI::success( 'FluentSMTP 2.4.0 is active; mail and external HTTP remain blocked.' );

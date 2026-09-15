<?php
/**
 * Verify the private clone's one-recipient SMTP test runtime without sending mail.
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! defined( 'KH2027_WEB_REVIEW' )
	|| ! KH2027_WEB_REVIEW || ! defined( 'KH2027_SANDBOX' ) || ! KH2027_SANDBOX
	|| ! defined( 'KH2027_MAIL_TEST' ) || ! KH2027_MAIL_TEST
	|| ! defined( 'KH2027_MAIL_TEST_RECIPIENT' ) || ! is_email( KH2027_MAIL_TEST_RECIPIENT ) ) {
	throw new RuntimeException( 'This helper is restricted to the private SMTP test runtime.' );
}

$secret_file = '/home3/sc3heal3867/kh2027-private/web-control/secrets/fluent-smtp-credentials.php';
$real_secret = realpath( $secret_file );
if ( ! $real_secret || strpos( $real_secret, ABSPATH ) === 0 || ( fileperms( $real_secret ) & 0777 ) !== 0600 ) {
	WP_CLI::error( 'Private SMTP credentials are missing or have unsafe permissions.' );
}
require $real_secret;

$raw = get_option( 'fluentmail-settings', array() );
$connections = is_array( $raw ) ? ( $raw['connections'] ?? array() ) : array();
$connection = count( $connections ) === 1 ? reset( $connections ) : array();
$stored = is_array( $connection ) ? ( $connection['provider_settings'] ?? array() ) : array();
$factory = fluentMail( \FluentMail\App\Services\Mailer\Providers\Factory::class );
$provider = $factory->make( 'smtp' );
if ( $stored ) {
	$provider->setSettings( $stored );
}

$valid_atts = array(
	'to'          => array( KH2027_MAIL_TEST_RECIPIENT ),
	'subject'     => kh_review_mail_test_subject(),
	'message'     => kh_review_mail_test_message(),
	'headers'     => array( 'Content-Type: text/plain; charset=UTF-8' ),
	'attachments' => array(),
);
$rewritten = kh_review_mail_test_wp_mail( array(
	'to'          => array( 'blocked@example.invalid' ),
	'subject'     => 'Blocked message',
	'message'     => 'Blocked body',
	'headers'     => array( 'Cc: blocked@example.invalid', 'Bcc: blocked@example.invalid' ),
	'attachments' => array( '/tmp/blocked.txt' ),
) );

$http_result = apply_filters( 'pre_http_request', false, array(), 'https://example.invalid/' );
$checks = array(
	'mail_test_mode'          => true === KH2027_MAIL_TEST,
	'plugin_loaded'           => function_exists( 'fluentMail' ),
	'mail_function_disabled'  => ! function_exists( 'mail' ),
	'curl_function_disabled'  => ! function_exists( 'curl_exec' ),
	'fsockopen_enabled'       => function_exists( 'fsockopen' ),
	'stream_socket_enabled'   => function_exists( 'stream_socket_client' ),
	'http_still_blocked'      => is_wp_error( $http_result ) && 'kh2027_offline' === $http_result->get_error_code(),
	'guard_filter_loaded'     => false !== has_filter( 'pre_wp_mail', 'kh_review_mail_test_pre_wp_mail' ),
	'allowed_message_passes'  => null === kh_review_mail_test_pre_wp_mail( null, $valid_atts ),
	'other_message_blocked'   => is_wp_error( kh_review_mail_test_pre_wp_mail( null, $rewritten ) ),
	'recipient_forced'        => ( $rewritten['to'] ?? array() ) === array( KH2027_MAIL_TEST_RECIPIENT ),
	'copies_removed'          => ( $rewritten['headers'] ?? array() ) === array(),
	'attachments_removed'     => ( $rewritten['attachments'] ?? null ) === array(),
	'one_connection'          => count( $connections ) === 1,
	'provider_is_smtp'        => ( $stored['provider'] ?? '' ) === 'smtp',
	'host_is_brevo'           => ( $stored['host'] ?? '' ) === 'smtp-relay.brevo.com',
	'sender_is_verified'      => ( $stored['sender_email'] ?? '' ) === 'kaeljin@koi-nobori.com',
	'username_not_in_database' => ( $stored['username'] ?? null ) === '',
	'password_not_in_database' => ( $stored['password'] ?? null ) === '',
	'private_username_loaded' => defined( 'FLUENTMAIL_SMTP_USERNAME' )
		&& $provider->getSetting( 'username' ) === FLUENTMAIL_SMTP_USERNAME,
	'private_password_loaded' => defined( 'FLUENTMAIL_SMTP_PASSWORD' )
		&& $provider->getSetting( 'password' ) === FLUENTMAIL_SMTP_PASSWORD,
);

foreach ( $checks as $name => $passed ) {
	WP_CLI::log( $name . '=' . ( $passed ? 'yes' : 'no' ) );
}
if ( in_array( false, $checks, true ) ) {
	WP_CLI::error( 'Private SMTP test runtime check failed. No email sent.' );
}
WP_CLI::success( 'One-recipient SMTP test runtime is ready. No email sent.' );

<?php
/** Send one allowlisted SMTP transport test from the private clone. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! defined( 'KH2027_WEB_REVIEW' )
	|| ! KH2027_WEB_REVIEW || ! defined( 'KH2027_SANDBOX' ) || ! KH2027_SANDBOX
	|| ! defined( 'KH2027_MAIL_TEST' ) || ! KH2027_MAIL_TEST
	|| ! defined( 'KH2027_MAIL_TEST_RECIPIENT' ) || ! is_email( KH2027_MAIL_TEST_RECIPIENT )
	|| 'confirmed-once' !== getenv( 'KH2027_SMTP_SEND_APPROVED' ) ) {
	throw new RuntimeException( 'This helper requires the approved private SMTP test runtime.' );
}

$secret_file = '/home3/sc3heal3867/kh2027-private/web-control/secrets/fluent-smtp-credentials.php';
$real_secret = realpath( $secret_file );
if ( ! $real_secret || strpos( $real_secret, ABSPATH ) === 0 || ( fileperms( $real_secret ) & 0777 ) !== 0600 ) {
	WP_CLI::error( 'Private SMTP credentials are missing or have unsafe permissions.' );
}
require $real_secret;

$result = wp_mail(
	KH2027_MAIL_TEST_RECIPIENT,
	kh_review_mail_test_subject(),
	kh_review_mail_test_message(),
	array( 'Content-Type: text/plain; charset=UTF-8' )
);
if ( is_wp_error( $result ) ) {
	WP_CLI::error( 'SMTP test failed: ' . $result->get_error_code() );
}
if ( true !== $result ) {
	WP_CLI::error( 'SMTP test did not report successful transport.' );
}
WP_CLI::log( 'recipient_allowlisted=yes' );
WP_CLI::log( 'copies=no' );
WP_CLI::log( 'attachments=no' );
WP_CLI::success( 'One SMTP transport test was accepted for delivery.' );

<?php
/** Verify the 12-message notification-test runtime without sending email. */
$plan = require __DIR__ . '/enquiry_notification_test_runtime.php';

$allowed_subject = '[Koinobori House] Nouvelle demande — Contact';
$sample = array(
	'to'          => array( KH2027_MAIL_TEST_RECIPIENT ),
	'subject'     => $allowed_subject,
	'message'     => '<p>Koinobori House</p><p>kh2027-http-dry-run@example.invalid</p>',
	'headers'     => array(
		'From: Koinobori House <contact@koinoborihouse.com>',
		'Reply-To: kh2027-http-dry-run@example.invalid',
	),
	'attachments' => array(),
);
$rewritten = kh_review_notification_test_wp_mail( $sample );
$preflight = kh_review_notification_test_pre_wp_mail( null, $rewritten );
$blocked = $sample;
$blocked['subject'] = 'Unexpected notification';
$blocked = kh_review_notification_test_wp_mail( $blocked );
$http_result = apply_filters( 'pre_http_request', false, array(), 'https://example.invalid/' );
$checks = array(
	'forms'                 => 6 === count( $plan ),
	'notifications'         => 12 === array_sum( array_map( function ( $item ) {
		return count( $item['notifications'] );
	}, $plan ) ),
	'recipient_forced'      => ( $rewritten['to'] ?? array() ) === array( KH2027_MAIL_TEST_RECIPIENT ),
	'subject_prefixed'      => ( $rewritten['subject'] ?? '' ) === '[KH2027 NOTIFICATION TEST] ' . $allowed_subject,
	'copies_removed'        => ! kh_review_notification_test_header_values( $rewritten['headers'] ?? array(), 'Cc' )
		&& ! kh_review_notification_test_header_values( $rewritten['headers'] ?? array(), 'Bcc' ),
	'attachments_removed'   => array() === ( $rewritten['attachments'] ?? null ),
	'allowed_message_passes' => null === $preflight,
	'other_message_blocked' => '[KH2027 BLOCKED]' === ( $blocked['subject'] ?? '' )
		&& is_wp_error( kh_review_notification_test_pre_wp_mail( null, $blocked ) ),
	'http_still_blocked'    => is_wp_error( $http_result ) && 'kh2027_offline' === $http_result->get_error_code(),
	'mail_function_disabled' => ! function_exists( 'mail' ),
	'fsockopen_enabled'     => function_exists( 'fsockopen' ),
);

foreach ( $checks as $name => $passed ) {
	WP_CLI::log( $name . '=' . ( $passed ? 'yes' : 'no' ) );
}
if ( in_array( false, $checks, true ) ) {
	WP_CLI::error( 'Notification-test runtime check failed. No email sent.' );
}
WP_CLI::success( 'Twelve-message notification-test runtime is ready. No email sent.' );

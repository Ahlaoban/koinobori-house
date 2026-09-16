<?php
/** Render all 12 Fluent Forms notifications through the guard without SMTP delivery. */
$plan = require __DIR__ . '/enquiry_notification_test_runtime.php';

add_filter( 'pre_wp_mail', function ( $return, $atts ) {
	if ( is_wp_error( $return ) ) {
		WP_CLI::warning( 'guard_blocked_subject=' . ( $atts['subject'] ?? '(missing)' ) );
		WP_CLI::warning( 'guard_checks=' . wp_json_encode(
			$GLOBALS['kh_review_notification_test_last_block'] ?? array()
		) );
		WP_CLI::warning( 'message_checks=' . wp_json_encode(
			$GLOBALS['kh_review_notification_test_message_checks'] ?? array()
		) );
		return $return;
	}
	$subject = isset( $atts['subject'] ) && is_string( $atts['subject'] ) ? $atts['subject'] : '';
	$prefix = '[KH2027 NOTIFICATION TEST] ';
	$valid = 0 === strpos( $subject, $prefix )
		&& ( $atts['to'] ?? array() ) === array( KH2027_MAIL_TEST_RECIPIENT )
		&& empty( $atts['attachments'] );
	return $valid ? true : new WP_Error(
		'kh2027_render_check_blocked',
		'Rendered notification did not pass the final no-delivery check.'
	);
}, PHP_INT_MAX, 2 );

$notifier = new \FluentForm\App\Services\FormBuilder\Notifications\EmailNotification( wpFluentForm() );
$rendered = 0;
foreach ( $plan as $item ) {
	foreach ( $item['notifications'] as $notification ) {
		$result = $notifier->notify(
			$notification,
			$item['response'],
			$item['form'],
			$item['entry_id']
		);
		if ( true !== $result ) {
			WP_CLI::error(
				'Render check failed for form ' . $item['form_id'] .
				' notification ' . $notification['name'] . '. No email sent.'
			);
		}
		++$rendered;
		WP_CLI::log( 'rendered_form=' . $item['form_id'] . ' notification=' . $notification['name'] . ' guarded=yes' );
	}
}

$limits = kh_review_notification_test_limits();
$counts = $GLOBALS['kh_review_notification_test_counts'] ?? array();
if ( 12 !== $rendered || $counts !== $limits ) {
	WP_CLI::error( 'Rendered notification counts differ from the approved plan. No email sent.' );
}
WP_CLI::success( 'All 12 rendered notifications passed the guard. No email sent.' );

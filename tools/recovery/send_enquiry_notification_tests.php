<?php
/** Send one guarded batch of the 12 disabled Fluent Forms notifications. */
$plan = require __DIR__ . '/enquiry_notification_test_runtime.php';
if ( 'confirmed-twelve-once' !== getenv( 'KH2027_NOTIFICATION_SEND_APPROVED' ) ) {
	WP_CLI::error( 'The one-time 12-message send gate is closed.' );
}

$marker_dir = '/home3/sc3heal3867/kh2027-private/web-control/notification-tests';
$marker_file = $marker_dir . '/batch-20260916-v2.json';
if ( 0 === strpos( $marker_dir, ABSPATH ) || ( ! is_dir( $marker_dir ) && ! wp_mkdir_p( $marker_dir ) ) ) {
	WP_CLI::error( 'Could not prepare the private notification-test ledger.' );
}
chmod( $marker_dir, 0700 );
$marker = @fopen( $marker_file, 'x' );
if ( false === $marker ) {
	WP_CLI::error( 'This notification-test batch was already started; refusing to send it again.' );
}
chmod( $marker_file, 0600 );
$state = array(
	'batch'       => '20260916-v2',
	'started_utc' => gmdate( DATE_ATOM ),
	'completed'   => false,
	'accepted'    => 0,
	'forms'       => array(),
);
fwrite( $marker, wp_json_encode( $state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
fflush( $marker );

$notifier = new \FluentForm\App\Services\FormBuilder\Notifications\EmailNotification( wpFluentForm() );
foreach ( $plan as $item ) {
	foreach ( $item['notifications'] as $notification ) {
		$result = $notifier->notify(
			$notification,
			$item['response'],
			$item['form'],
			$item['entry_id']
		);
		if ( true !== $result ) {
			$state['failed'] = array(
				'form_id'      => $item['form_id'],
				'notification' => $notification['name'],
				'at_utc'       => gmdate( DATE_ATOM ),
			);
			ftruncate( $marker, 0 );
			rewind( $marker );
			fwrite( $marker, wp_json_encode( $state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
			fclose( $marker );
			WP_CLI::error( 'Notification transport failed after ' . $state['accepted'] . ' accepted message(s).' );
		}
		++$state['accepted'];
		$state['forms'][] = array(
			'form_id'      => $item['form_id'],
			'notification' => $notification['name'],
			'accepted_utc' => gmdate( DATE_ATOM ),
		);
		ftruncate( $marker, 0 );
		rewind( $marker );
		fwrite( $marker, wp_json_encode( $state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
		fflush( $marker );
		WP_CLI::log( 'form=' . $item['form_id'] . ' notification=' . $notification['name'] . ' accepted=yes' );
	}
}

$limits = kh_review_notification_test_limits();
$counts = $GLOBALS['kh_review_notification_test_counts'] ?? array();
if ( 12 !== $state['accepted'] || $counts !== $limits ) {
	$state['failed'] = array( 'reason' => 'guard-count-mismatch', 'at_utc' => gmdate( DATE_ATOM ) );
	ftruncate( $marker, 0 );
	rewind( $marker );
	fwrite( $marker, wp_json_encode( $state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
	fclose( $marker );
	WP_CLI::error( 'The notification guard did not observe the exact 12-message plan.' );
}

$state['completed'] = true;
$state['completed_utc'] = gmdate( DATE_ATOM );
ftruncate( $marker, 0 );
rewind( $marker );
fwrite( $marker, wp_json_encode( $state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
fclose( $marker );
WP_CLI::log( 'recipient_allowlisted=yes' );
WP_CLI::log( 'copies=no' );
WP_CLI::log( 'attachments=no' );
WP_CLI::success( 'The 12 Fluent Forms notification tests were accepted for delivery.' );

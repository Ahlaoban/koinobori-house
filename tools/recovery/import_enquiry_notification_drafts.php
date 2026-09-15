<?php
/**
 * Import the six bilingual enquiry email notifications as disabled drafts.
 *
 * Dry-run by default. Set KH2027_APPLY_NOTIFICATIONS=1 for the private clone
 * WP-CLI process to apply. This script never sends mail or configures SMTP.
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! defined( 'KH2027_WEB_REVIEW' )
	|| ! KH2027_WEB_REVIEW || ! defined( 'KH2027_SANDBOX' ) || ! KH2027_SANDBOX ) {
	throw new RuntimeException( 'This helper is restricted to the private review clone.' );
}
if ( ! defined( 'FLUENTFORM_VERSION' ) || FLUENTFORM_VERSION !== '6.2.13' ) {
	WP_CLI::error( 'Expected Fluent Forms 6.2.13.' );
}

global $wpdb;

$apply = getenv( 'KH2027_APPLY_NOTIFICATIONS' ) === '1';
$expected = array(
	'contact'       => 5,
	'contact-us'    => 6,
	'entreprises'   => 7,
	'business'      => 8,
	'collectivites' => 9,
	'institutions'  => 10,
);
$drafts = require __DIR__ . '/enquiry_notification_drafts.php';
$table = $wpdb->prefix . 'fluentform_form_meta';

if ( array_keys( $drafts ) !== array_keys( $expected ) ) {
	WP_CLI::error( 'Draft/page set mismatch.' );
}

$engine = $wpdb->get_var( $wpdb->prepare(
	'SELECT ENGINE FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = %s',
	$table
) );
if ( strtoupper( (string) $engine ) !== 'INNODB' ) {
	WP_CLI::error( 'The Fluent Forms meta table must use InnoDB.' );
}

$expected_names = array();
foreach ( $expected as $slug => $form_id ) {
	$page = get_page_by_path( $slug );
	if ( ! $page || ! preg_match( '/\[fluentform\s+id=["\x27](\d+)["\x27]\s*\]/', $page->post_content, $match )
		|| (int) $match[1] !== $form_id || ! \FluentForm\App\Models\Form::find( $form_id ) ) {
		WP_CLI::error( 'Unexpected page/form association for ' . $slug . '.' );
	}

	$notifications = $drafts[ $slug ]['notifications'] ?? array();
	if ( count( $notifications ) !== 2 || empty( $drafts[ $slug ]['confirmation_message'] ) ) {
		WP_CLI::error( 'Incomplete draft set for ' . $slug . '.' );
	}

	foreach ( $notifications as $notification ) {
		if ( ( $notification['enabled'] ?? null ) !== false
			|| ! empty( $notification['cc'] ) || ! empty( $notification['bcc'] )
			|| ! empty( $notification['attachments'] ) ) {
			WP_CLI::error( 'Unsafe notification draft for ' . $slug . '.' );
		}
		\FluentForm\App\Services\Settings\Validator::validate( 'notifications', $notification );
		$expected_names[] = $notification['name'];
	}

	$form_settings = \FluentForm\App\Models\Form::getFormsDefaultSettings( $form_id );
	$form_settings['confirmation']['messageToShow'] = $drafts[ $slug ]['confirmation_message'];
	\FluentForm\App\Services\Settings\Validator::validate( 'confirmations', $form_settings['confirmation'] );
}

sort( $expected_names );
$ids_sql = implode( ',', array_map( 'intval', array_values( $expected ) ) );
$existing_rows = $wpdb->get_results(
	"SELECT id, form_id, value FROM {$table} WHERE meta_key = 'notifications' AND form_id IN ({$ids_sql}) ORDER BY form_id, id",
	ARRAY_A
);

function kh2027_notifications_match( $rows, $expected_names, $drafts, $expected, $table, $wpdb ) {
	if ( count( $rows ) !== count( $expected_names ) ) {
		return false;
	}
	$names = array();
	foreach ( $rows as $row ) {
		$value = json_decode( $row['value'], true );
		if ( ! is_array( $value ) || ( $value['enabled'] ?? null ) !== false || empty( $value['name'] ) ) {
			return false;
		}
		$names[] = $value['name'];
	}
	sort( $names );
	if ( $names !== $expected_names ) {
		return false;
	}
	foreach ( $expected as $slug => $form_id ) {
		$value = $wpdb->get_var( $wpdb->prepare(
			"SELECT value FROM {$table} WHERE meta_key = 'formSettings' AND form_id = %d",
			$form_id
		) );
		$settings = json_decode( (string) $value, true );
		if ( ( $settings['confirmation']['messageToShow'] ?? '' ) !== $drafts[ $slug ]['confirmation_message'] ) {
			return false;
		}
	}
	return true;
}

if ( $existing_rows ) {
	if ( kh2027_notifications_match( $existing_rows, $expected_names, $drafts, $expected, $table, $wpdb ) ) {
		WP_CLI::success( 'The 12 disabled notification drafts and six confirmations are already installed.' );
		return;
	}
	WP_CLI::error( 'Existing enquiry notifications differ; refusing to overwrite them.' );
}

foreach ( $expected as $form_id ) {
	$count = (int) $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(*) FROM {$table} WHERE meta_key = 'formSettings' AND form_id = %d",
		$form_id
	) );
	if ( $count !== 1 ) {
		WP_CLI::error( 'Expected one formSettings row for form ' . $form_id . '.' );
	}
}

if ( ! $apply ) {
	WP_CLI::log( 'mode=dry-run' );
	WP_CLI::log( 'forms=6' );
	WP_CLI::log( 'disabled_notifications_to_create=12' );
	WP_CLI::log( 'confirmation_messages_to_update=6' );
	WP_CLI::success( 'Validation passed; no database change and no email sent.' );
	return;
}

$backup_dir = '/home3/sc3heal3867/kh2027-private/web-control/notification-backups';
if ( strpos( $backup_dir, ABSPATH ) === 0 || ( ! is_dir( $backup_dir ) && ! wp_mkdir_p( $backup_dir ) ) ) {
	WP_CLI::error( 'Could not prepare the private backup directory.' );
}
chmod( $backup_dir, 0700 );

$backup_rows = $wpdb->get_results(
	"SELECT id, form_id, meta_key, value FROM {$table} WHERE form_id IN ({$ids_sql}) AND meta_key IN ('notifications','formSettings') ORDER BY form_id, id",
	ARRAY_A
);
$backup_file = $backup_dir . '/enquiry-notifications-' . gmdate( 'Ymd-His' ) . '-' . wp_generate_password( 6, false, false ) . '.json';
$written = file_put_contents( $backup_file, wp_json_encode(
	array( 'created_utc' => gmdate( DATE_ATOM ), 'rows' => $backup_rows ),
	JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
), LOCK_EX );
if ( ! $written || ! chmod( $backup_file, 0600 ) ) {
	WP_CLI::error( 'Could not write the private backup.' );
}

$service = new \FluentForm\App\Services\Settings\SettingsService();
$wpdb->query( 'START TRANSACTION' );

try {
	foreach ( $expected as $slug => $form_id ) {
		foreach ( $drafts[ $slug ]['notifications'] as $notification ) {
			$service->store( array(
				'form_id' => $form_id,
				'meta_key' => 'notifications',
				'value' => wp_json_encode( $notification ),
			) );
		}

		$form_settings = \FluentForm\App\Models\Form::getFormsDefaultSettings( $form_id );
		$form_settings['confirmation']['messageToShow'] = $drafts[ $slug ]['confirmation_message'];
		$meta_id = (int) $wpdb->get_var( $wpdb->prepare(
			"SELECT id FROM {$table} WHERE meta_key = 'formSettings' AND form_id = %d",
			$form_id
		) );
		$service->store( array(
			'form_id' => $form_id,
			'meta_id' => $meta_id,
			'meta_key' => 'formSettings',
			'value' => wp_json_encode( $form_settings ),
		) );
	}

	$installed_rows = $wpdb->get_results(
		"SELECT id, form_id, value FROM {$table} WHERE meta_key = 'notifications' AND form_id IN ({$ids_sql}) ORDER BY form_id, id",
		ARRAY_A
	);
	if ( ! kh2027_notifications_match( $installed_rows, $expected_names, $drafts, $expected, $table, $wpdb ) ) {
		throw new RuntimeException( 'Post-write verification failed.' );
	}
	if ( $wpdb->last_error ) {
		throw new RuntimeException( 'Database error: ' . $wpdb->last_error );
	}

	$wpdb->query( 'COMMIT' );
} catch ( Throwable $error ) {
	$wpdb->query( 'ROLLBACK' );
	WP_CLI::error( $error->getMessage() );
}

WP_CLI::log( 'backup=' . basename( $backup_file ) );
WP_CLI::log( 'forms=6' );
WP_CLI::log( 'disabled_notifications=12' );
WP_CLI::log( 'confirmation_messages=6' );
WP_CLI::success( 'Disabled notification drafts installed; no email was sent.' );

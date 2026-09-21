<?php
/** Validate the private notification-test runtime and return its six-form plan. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! defined( 'KH2027_WEB_REVIEW' )
	|| ! KH2027_WEB_REVIEW || ! defined( 'KH2027_SANDBOX' ) || ! KH2027_SANDBOX
	|| ! defined( 'KH2027_MAIL_TEST' ) || ! KH2027_MAIL_TEST
	|| ! defined( 'KH2027_MAIL_TEST_RECIPIENT' ) || ! is_email( KH2027_MAIL_TEST_RECIPIENT )
	|| 'twelve-notifications-once' !== getenv( 'KH2027_NOTIFICATION_TEST_APPROVED' )
	|| ! function_exists( 'kh_review_notification_test_wp_mail' ) ) {
	throw new RuntimeException( 'This helper requires the approved private notification-test runtime.' );
}
if ( ! defined( 'FLUENTFORM_VERSION' ) || '6.2.13' !== FLUENTFORM_VERSION ) {
	WP_CLI::error( 'Expected Fluent Forms 6.2.13.' );
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';
$smtp_file = WP_PLUGIN_DIR . '/fluent-smtp/fluent-smtp.php';
$smtp_data = is_readable( $smtp_file ) ? get_plugin_data( $smtp_file, false, false ) : array();
if ( '2.4.0' !== ( $smtp_data['Version'] ?? '' ) || ! function_exists( 'fluentMail' ) ) {
	WP_CLI::error( 'Expected FluentSMTP 2.4.0.' );
}

$secret_file = '/home3/sc3heal3867/kh2027-private/web-control/secrets/fluent-smtp-credentials.php';
$real_secret = realpath( $secret_file );
if ( ! $real_secret || 0 === strpos( $real_secret, ABSPATH ) || 0600 !== ( fileperms( $real_secret ) & 0777 ) ) {
	WP_CLI::error( 'Private SMTP credentials are missing or have unsafe permissions.' );
}
require $real_secret;

$raw = get_option( 'fluentmail-settings', array() );
$connections = is_array( $raw ) ? ( $raw['connections'] ?? array() ) : array();
$connection = 1 === count( $connections ) ? reset( $connections ) : array();
$stored_smtp = is_array( $connection ) ? ( $connection['provider_settings'] ?? array() ) : array();
$factory = fluentMail( \FluentMail\App\Services\Mailer\Providers\Factory::class );
$provider = $factory->make( 'smtp' );
if ( $stored_smtp ) {
	$provider->setSettings( $stored_smtp );
}
$smtp_valid = 1 === count( $connections )
	&& 'smtp' === ( $stored_smtp['provider'] ?? '' )
	&& 'smtp-relay.brevo.com' === ( $stored_smtp['host'] ?? '' )
	&& 'contact@koinoborihouse.com' === ( $stored_smtp['sender_email'] ?? '' )
	&& '' === ( $stored_smtp['username'] ?? null )
	&& '' === ( $stored_smtp['password'] ?? null )
	&& defined( 'FLUENTMAIL_SMTP_USERNAME' ) && FLUENTMAIL_SMTP_USERNAME
	&& defined( 'FLUENTMAIL_SMTP_PASSWORD' ) && FLUENTMAIL_SMTP_PASSWORD
	&& $provider->getSetting( 'username' ) === FLUENTMAIL_SMTP_USERNAME
	&& $provider->getSetting( 'password' ) === FLUENTMAIL_SMTP_PASSWORD;
if ( ! $smtp_valid ) {
	WP_CLI::error( 'Private Brevo SMTP configuration differs from the approved setup.' );
}

global $wpdb;
$forms = array(
	'contact'       => 5,
	'contact-us'    => 6,
	'entreprises'   => 7,
	'business'      => 8,
	'collectivites' => 9,
	'institutions'  => 10,
);
$drafts = require __DIR__ . '/enquiry_notification_drafts.php';
$meta_table = $wpdb->prefix . 'fluentform_form_meta';
$submission_table = $wpdb->prefix . 'fluentform_submissions';
$plan = array();

foreach ( $forms as $slug => $form_id ) {
	$page = get_page_by_path( $slug );
	$form = \FluentForm\App\Models\Form::find( $form_id );
	if ( ! $page || ! $form
		|| ! preg_match( '/\[fluentform\s+id=["\x27](\d+)["\x27]\s*\]/', $page->post_content, $match )
		|| $form_id !== (int) $match[1] ) {
		WP_CLI::error( 'Unexpected page/form association for ' . $slug . '.' );
	}

	$expected_notifications = $drafts[ $slug ]['notifications'] ?? array();
	$rows = $wpdb->get_col( $wpdb->prepare(
		"SELECT value FROM {$meta_table} WHERE form_id = %d AND meta_key = 'notifications' ORDER BY id",
		$form_id
	) );
	$actual_by_name = array();
	foreach ( $rows as $row ) {
		$notification = json_decode( $row, true );
		if ( ! is_array( $notification ) || empty( $notification['name'] ) ) {
			WP_CLI::error( 'Invalid stored notification for form ' . $form_id . '.' );
		}
		$actual_by_name[ $notification['name'] ] = $notification;
	}
	if ( 2 !== count( $actual_by_name ) || 2 !== count( $expected_notifications ) ) {
		WP_CLI::error( 'Expected two stored notification drafts for form ' . $form_id . '.' );
	}
	$verified_notifications = array();
	foreach ( $expected_notifications as $expected_notification ) {
		$name = $expected_notification['name'];
		$actual = $actual_by_name[ $name ] ?? null;
		if ( ! is_array( $actual ) ) {
			WP_CLI::error( 'A stored notification is missing for form ' . $form_id . '.' );
		}
		// SettingsService::store may add normalised keys: every drafted key must match, extra keys are tolerated.
		$comparable = array_intersect_key( $actual, $expected_notification );
		if ( $comparable != $expected_notification || false !== ( $actual['enabled'] ?? null )
			|| ! empty( $actual['cc'] ) || ! empty( $actual['bcc'] ) || ! empty( $actual['attachments'] ) ) {
			WP_CLI::error( 'A stored notification differs from its disabled draft for form ' . $form_id . '.' );
		}
		$verified_notifications[] = $actual;
	}

	$entry = $wpdb->get_row( $wpdb->prepare(
		"SELECT id, response FROM {$submission_table} WHERE form_id = %d ORDER BY id DESC LIMIT 1",
		$form_id
	), ARRAY_A );
	$response = $entry ? json_decode( $entry['response'], true ) : null;
	$email = is_array( $response ) ? ( $response['email'] ?? '' ) : '';
	if ( ! $entry || ! is_array( $response ) || ! is_string( $email )
		|| ! preg_match( '/^kh2027-http-[a-z0-9-]+@example\.invalid$/', $email ) ) {
		WP_CLI::error( 'Latest submission is not the expected synthetic record for form ' . $form_id . '.' );
	}
	$encoded_response = wp_json_encode( $response );
	preg_match_all( '/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i', (string) $encoded_response, $emails );
	foreach ( array_unique( $emails[0] ) as $response_email ) {
		if ( ! preg_match( '/^kh2027-http-[a-z0-9-]+@example\.invalid$/i', $response_email ) ) {
			WP_CLI::error( 'A non-synthetic address was found in form ' . $form_id . ' test data.' );
		}
	}

	$plan[] = array(
		'slug'          => $slug,
		'form_id'       => $form_id,
		'form'          => $form,
		'entry_id'      => (int) $entry['id'],
		'response'      => $response,
		'notifications' => $verified_notifications,
	);
}

return $plan;

<?php
/**
 * Configure the private clone's Brevo SMTP connection without sending mail.
 * Credentials are loaded from a private file and never stored in WordPress.
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! defined( 'KH2027_WEB_REVIEW' )
	|| ! KH2027_WEB_REVIEW || ! defined( 'KH2027_SANDBOX' ) || ! KH2027_SANDBOX ) {
	throw new RuntimeException( 'This helper is restricted to the private review clone.' );
}
require_once ABSPATH . 'wp-admin/includes/plugin.php';
$plugin_file = WP_PLUGIN_DIR . '/fluent-smtp/fluent-smtp.php';
$plugin_data = is_readable( $plugin_file ) ? get_plugin_data( $plugin_file, false, false ) : array();
if ( ( $plugin_data['Version'] ?? '' ) !== '2.4.0' ) {
	WP_CLI::error( 'Expected FluentSMTP 2.4.0.' );
}

$secret_file = '/home3/sc3heal3867/kh2027-private/web-control/secrets/fluent-smtp-credentials.php';
$real_secret = realpath( $secret_file );
if ( ! $real_secret || strpos( $real_secret, ABSPATH ) === 0 || ( fileperms( $real_secret ) & 0777 ) !== 0600 ) {
	WP_CLI::error( 'Private SMTP credentials are missing or have unsafe permissions.' );
}
require $real_secret;

if ( ! defined( 'FLUENTMAIL_SMTP_USERNAME' ) || ! FLUENTMAIL_SMTP_USERNAME
	|| ! defined( 'FLUENTMAIL_SMTP_PASSWORD' ) || ! FLUENTMAIL_SMTP_PASSWORD ) {
	WP_CLI::error( 'Private SMTP credentials are incomplete.' );
}

$sender = 'contact@koinoborihouse.com';
$legacy_sender = 'kaeljin@koi-nobori.com';
$key = md5( $sender );
$connection = array(
	'provider'         => 'smtp',
	'sender_email'     => $sender,
	'sender_name'      => 'Koinobori House',
	'force_from_email' => 'yes',
	'force_from_name'  => 'yes',
	'host'             => 'smtp-relay.brevo.com',
	'port'             => 587,
	'encryption'       => 'tls',
	'auto_tls'         => 'yes',
	'auth'             => 'yes',
	'username'         => '',
	'password'         => '',
	'key_store'        => 'wp_config',
);

$factory = fluentMail( \FluentMail\App\Services\Mailer\Providers\Factory::class );
$provider = $factory->make( 'smtp' );
$provider->validateProviderInformation( $connection );

$missing = new stdClass();
$raw_option = get_option( 'fluentmail-settings', $missing );
$had_settings = $raw_option !== $missing;
$raw = $had_settings ? $raw_option : array();
$connections = is_array( $raw ) ? ( $raw['connections'] ?? array() ) : array();
$connection_key = '';
if ( isset( $connections[ $key ] ) ) {
	$stored = $connections[ $key ]['provider_settings'] ?? array();
	$expected = $connection;
	foreach ( $expected as $name => $value ) {
		if ( ! array_key_exists( $name, $stored ) || $stored[ $name ] !== $value ) {
			WP_CLI::error( 'An existing FluentSMTP connection differs; refusing to overwrite it.' );
		}
	}
	if ( count( $connections ) !== 1 ) {
		WP_CLI::error( 'Unexpected additional FluentSMTP connections.' );
	}
	$provider->setSettings( $stored );
	if ( $provider->getSetting( 'username' ) !== FLUENTMAIL_SMTP_USERNAME
		|| $provider->getSetting( 'password' ) !== FLUENTMAIL_SMTP_PASSWORD ) {
		WP_CLI::error( 'Private credential resolution failed.' );
	}
	WP_CLI::success( 'Brevo SMTP is already configured; credentials remain outside WordPress. No email sent.' );
	return;
}

if ( $connections ) {
	$legacy_key = md5( $legacy_sender );
	$legacy_connection = $connection;
	$legacy_connection['sender_email'] = $legacy_sender;
	if ( count( $connections ) !== 1 || ! isset( $connections[ $legacy_key ] ) ) {
		WP_CLI::error( 'Unexpected FluentSMTP connection; refusing to overwrite it.' );
	}
	$stored = $connections[ $legacy_key ]['provider_settings'] ?? array();
	foreach ( $legacy_connection as $name => $value ) {
		if ( ! array_key_exists( $name, $stored ) || $stored[ $name ] !== $value ) {
			WP_CLI::error( 'The existing FluentSMTP connection differs; refusing to overwrite it.' );
		}
	}
	$connection_key = $legacy_key;
}

$backup_dir = '/home3/sc3heal3867/kh2027-private/web-control/smtp-setting-backups';
if ( strpos( $backup_dir, ABSPATH ) === 0 || ( ! is_dir( $backup_dir ) && ! wp_mkdir_p( $backup_dir ) ) ) {
	WP_CLI::error( 'Could not prepare the private backup directory.' );
}
chmod( $backup_dir, 0700 );
$backup_file = $backup_dir . '/fluentmail-settings-' . gmdate( 'Ymd-His' ) . '.json';
if ( ! file_put_contents( $backup_file, wp_json_encode(
	array( 'created_utc' => gmdate( DATE_ATOM ), 'settings' => $raw ),
	JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
), LOCK_EX ) || ! chmod( $backup_file, 0600 ) ) {
	WP_CLI::error( 'Could not back up the previous FluentSMTP settings.' );
}

$model = new \FluentMail\App\Models\Settings();
$model->store( array(
	'connection'     => $connection,
	'connection_key' => $connection_key,
	'valid_senders'  => array(),
) );

try {
	$stored_raw = get_option( 'fluentmail-settings', array() );
	$stored = $stored_raw['connections'][ $key ]['provider_settings'] ?? array();
	if ( ( $stored['username'] ?? null ) !== '' || ( $stored['password'] ?? null ) !== ''
		|| ( $stored['key_store'] ?? '' ) !== 'wp_config' ) {
		throw new RuntimeException( 'Credential-free WordPress storage verification failed.' );
	}
	$provider->setSettings( $stored );
	if ( $provider->getSetting( 'username' ) !== FLUENTMAIL_SMTP_USERNAME
		|| $provider->getSetting( 'password' ) !== FLUENTMAIL_SMTP_PASSWORD ) {
		throw new RuntimeException( 'Private credential resolution failed after configuration.' );
	}
} catch ( Throwable $error ) {
	$had_settings
		? update_option( 'fluentmail-settings', $raw, false )
		: delete_option( 'fluentmail-settings' );
	WP_CLI::error( $error->getMessage() . ' Previous settings restored.' );
}

WP_CLI::log( 'provider=smtp' );
WP_CLI::log( 'host=smtp-relay.brevo.com' );
WP_CLI::log( 'port=587' );
WP_CLI::log( 'encryption=tls' );
WP_CLI::log( 'sender=' . $sender );
WP_CLI::log( 'credentials_in_wordpress=no' );
WP_CLI::log( 'backup=' . basename( $backup_file ) );
WP_CLI::success( 'Brevo SMTP configured without sending mail; review guard remains active.' );

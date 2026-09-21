<?php
/**
 * Authenticate against Brevo SMTP, then disconnect without submitting a message.
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! defined( 'KH2027_WEB_REVIEW' )
	|| ! KH2027_WEB_REVIEW || ! defined( 'KH2027_SANDBOX' ) || ! KH2027_SANDBOX
	|| ! defined( 'KH2027_MAIL_TEST' ) || ! KH2027_MAIL_TEST ) {
	throw new RuntimeException( 'This helper is restricted to the private SMTP test runtime.' );
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

function kh2027_smtp_read_reply( $socket ) {
	$reply = '';
	do {
		$line = fgets( $socket, 516 );
		if ( false === $line ) {
			throw new RuntimeException( 'The SMTP server closed the connection unexpectedly.' );
		}
		$reply .= $line;
	} while ( isset( $line[3] ) && '-' === $line[3] );
	return array( (int) substr( $reply, 0, 3 ), trim( $reply ) );
}

function kh2027_smtp_command( $socket, $command, array $expected_codes ) {
	if ( false === fwrite( $socket, $command . "\r\n" ) ) {
		throw new RuntimeException( 'Could not write to the SMTP connection.' );
	}
	list( $code ) = kh2027_smtp_read_reply( $socket );
	if ( ! in_array( $code, $expected_codes, true ) ) {
		throw new RuntimeException( 'Unexpected SMTP response code: ' . $code . '.' );
	}
	return $code;
}

$context = stream_context_create( array(
	'ssl' => array(
		'verify_peer'      => true,
		'verify_peer_name' => true,
		'peer_name'        => 'smtp-relay.brevo.com',
	),
) );
$socket = stream_socket_client(
	'tcp://smtp-relay.brevo.com:587',
	$error_number,
	$error_message,
	15,
	STREAM_CLIENT_CONNECT,
	$context
);
if ( false === $socket ) {
	WP_CLI::error( 'Could not connect to Brevo SMTP (' . (int) $error_number . ').' );
}
stream_set_timeout( $socket, 15 );

try {
	list( $greeting_code ) = kh2027_smtp_read_reply( $socket );
	if ( 220 !== $greeting_code ) {
		throw new RuntimeException( 'Unexpected SMTP greeting code: ' . $greeting_code . '.' );
	}
	kh2027_smtp_command( $socket, 'EHLO koinoborihouse.test', array( 250 ) );
	kh2027_smtp_command( $socket, 'STARTTLS', array( 220 ) );
	if ( true !== stream_socket_enable_crypto( $socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT ) ) {
		throw new RuntimeException( 'Could not establish the TLS session.' );
	}
	kh2027_smtp_command( $socket, 'EHLO koinoborihouse.test', array( 250 ) );
	kh2027_smtp_command( $socket, 'AUTH LOGIN', array( 334 ) );
	kh2027_smtp_command( $socket, base64_encode( FLUENTMAIL_SMTP_USERNAME ), array( 334 ) );
	kh2027_smtp_command( $socket, base64_encode( FLUENTMAIL_SMTP_PASSWORD ), array( 235 ) );
	kh2027_smtp_command( $socket, 'QUIT', array( 221 ) );
} catch ( Throwable $error ) {
	fclose( $socket );
	WP_CLI::error( $error->getMessage() . ' No recipient or message was submitted.' );
}

fclose( $socket );
WP_CLI::success( 'Brevo SMTP authentication succeeded. No recipient or message was submitted.' );

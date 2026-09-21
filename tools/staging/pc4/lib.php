<?php
/**
 * Shared runtime for the PC4 staging corrections.
 *
 * Every script: fails closed on the staging identity guards, runs in dry-run
 * unless KH_APPLY=1 and KH_CONFIRM=<script token> are both set, writes a
 * private 0600 backup before any write, and wraps writes in a transaction.
 * Backups are restored by restore.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const KH_PC4_HOST      = 'staging.koinoborihouse.com';
const KH_PC4_DB        = 'heal3867_wp551';
const KH_PC4_FORBIDDEN = 'wp354';
const KH_PC4_PREFIX    = 'wprs_';
const KH_PC4_ROOT      = '/home3/heal3867/staging.koinoborihouse.com';
const KH_PC4_PRIVATE   = '/home3/heal3867/kh2027-private';
const KH_PC4_BACKUPS   = KH_PC4_PRIVATE . '/backups';

/** Run the guards, prepare the backup directory, return the run context. */
function kh_pc4_boot( $script, $confirm_token ) {
	global $wpdb;
	$abspath = (string) realpath( ABSPATH );
	$guards  = array(
		'php_sapi_cli'        => 'cli' === PHP_SAPI,
		'wp_cli'              => defined( 'WP_CLI' ) && WP_CLI,
		'environment_staging' => 'staging' === wp_get_environment_type(),
		'home_host'           => KH_PC4_HOST === (string) wp_parse_url( (string) get_option( 'home' ), PHP_URL_HOST )
			&& 'https' === (string) wp_parse_url( (string) get_option( 'home' ), PHP_URL_SCHEME ),
		'siteurl_host'        => KH_PC4_HOST === (string) wp_parse_url( (string) get_option( 'siteurl' ), PHP_URL_HOST )
			&& 'https' === (string) wp_parse_url( (string) get_option( 'siteurl' ), PHP_URL_SCHEME ),
		'db_name_constant'    => defined( 'DB_NAME' ) && KH_PC4_DB === DB_NAME,
		'db_name_connection'  => KH_PC4_DB === $wpdb->dbname,
		'db_not_production'   => defined( 'DB_NAME' ) && false === strpos( DB_NAME, KH_PC4_FORBIDDEN ) && false === strpos( (string) $wpdb->dbname, KH_PC4_FORBIDDEN ),
		'table_prefix'        => KH_PC4_PREFIX === $wpdb->prefix,
		'abspath_realpath'    => $abspath === KH_PC4_ROOT,
		'not_multisite'       => ! is_multisite(),
		'backups_outside_web' => 0 !== strpos( KH_PC4_BACKUPS, $abspath ),
	);
	fwrite( STDERR, $script . ' guards ' . wp_json_encode( $guards ) . PHP_EOL );
	foreach ( $guards as $name => $ok ) {
		if ( ! $ok ) {
			WP_CLI::error( 'Guard failed: ' . $name );
		}
	}

	$apply = '1' === (string) getenv( 'KH_APPLY' );
	if ( $apply && (string) getenv( 'KH_CONFIRM' ) !== $confirm_token ) {
		WP_CLI::error( 'KH_APPLY=1 requires KH_CONFIRM=' . $confirm_token );
	}
	if ( $apply ) {
		umask( 0077 );
		if ( ! is_dir( KH_PC4_BACKUPS ) && ! mkdir( KH_PC4_BACKUPS, 0700, true ) ) {
			WP_CLI::error( 'Cannot create the private backup directory.' );
		}
		chmod( KH_PC4_BACKUPS, 0700 );
		if ( ! is_writable( KH_PC4_BACKUPS ) ) {
			WP_CLI::error( 'Backup directory not writable.' );
		}
	}
	WP_CLI::log( 'mode=' . ( $apply ? 'APPLY' : 'dry-run' ) );
	return array( 'apply' => $apply, 'script' => $script, 'utc' => gmdate( 'Ymd\THis\Z' ) );
}

/** Abort on the first database error; a silent failure must never look like success. */
function kh_pc4_db_check( $what ) {
	global $wpdb;
	if ( '' !== (string) $wpdb->last_error ) {
		throw new RuntimeException( 'Database error (' . $what . '): ' . $wpdb->last_error );
	}
}

function kh_pc4_require_innodb( $table ) {
	global $wpdb;
	$engine = $wpdb->get_var( $wpdb->prepare( 'SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s', DB_NAME, $table ) );
	kh_pc4_db_check( 'engine ' . $table );
	if ( 'INNODB' !== strtoupper( (string) $engine ) ) {
		WP_CLI::error( 'Transactional table required: ' . $table );
	}
}

/**
 * Write a private backup. $restore is a list of records understood by
 * restore.php: {kind: 'option', name, value} | {kind: 'row', table, where, data}
 * | {kind: 'polylang_group', post_id, group}.
 */
function kh_pc4_backup( $ctx, $label, array $restore, array $extra = array() ) {
	$path = KH_PC4_BACKUPS . '/' . $label . '-' . $ctx['utc'] . '.json';
	if ( file_exists( $path ) ) {
		WP_CLI::error( 'Backup already exists: ' . $path );
	}
	$json   = wp_json_encode( array(
		'script'      => $ctx['script'],
		'created_utc' => gmdate( 'c' ),
		'host'        => KH_PC4_HOST,
		'db'          => KH_PC4_DB,
		'restore'     => $restore,
		'extra'       => $extra,
	), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	// An unencodable value must never produce an empty backup followed by a write.
	if ( false === $json || null === json_decode( $json, true ) ) {
		WP_CLI::error( 'Backup cannot be encoded; nothing written to the database.' );
	}
	$handle = fopen( $path, 'x' );
	if ( false === $handle ) {
		WP_CLI::error( 'Cannot create backup: ' . $path );
	}
	$written = fwrite( $handle, $json . PHP_EOL );
	fclose( $handle );
	chmod( $path, 0600 );
	if ( $written !== strlen( $json ) + 1 ) {
		unlink( $path );
		WP_CLI::error( 'Incomplete backup; nothing written to the database.' );
	}
	WP_CLI::log( 'backup=' . basename( $path ) . ' sha256=' . hash_file( 'sha256', $path ) );
	return $path;
}

/** Run $writes inside a transaction; any exception rolls back and aborts. */
function kh_pc4_transaction( callable $writes ) {
	global $wpdb;
	if ( false === $wpdb->query( 'START TRANSACTION' ) ) {
		WP_CLI::error( 'Cannot start transaction.' );
	}
	try {
		$writes();
		kh_pc4_db_check( 'writes' );
		if ( false === $wpdb->query( 'COMMIT' ) ) {
			throw new RuntimeException( 'Commit failed.' );
		}
	} catch ( Throwable $error ) {
		$wpdb->query( 'ROLLBACK' );
		WP_CLI::error( 'Rolled back: ' . $error->getMessage() );
	}
}

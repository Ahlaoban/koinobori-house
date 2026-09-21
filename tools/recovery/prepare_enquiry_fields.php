<?php
/**
 * Private clone only: wp eval-file prepare_enquiry_fields.php [apply BACKUP_DIR]
 * Default is read-only. BACKUP_DIR must exist outside the public document root.
 * Fluent Forms 6.2.13 uses the saved validation_rules for rendering and validation.
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! defined( 'KH2027_WEB_REVIEW' )
	|| ! KH2027_WEB_REVIEW || ! defined( 'KH2027_SANDBOX' ) || ! KH2027_SANDBOX ) {
	throw new RuntimeException( 'This helper is restricted to the private review clone.' );
}
global $wpdb;
$engine = $wpdb->get_var( $wpdb->prepare( 'SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=%s AND TABLE_NAME=%s', DB_NAME, $wpdb->prefix . 'fluentform_forms' ) );
if ( strtoupper( (string) $engine ) !== 'INNODB' ) { throw new RuntimeException( 'Transactional table required.' ); }
$apply = ( $args[0] ?? '' ) === 'apply';
// realpath( '' ) is the current directory: an empty argument must never pass the guard.
$backup_dir = ( $apply && '' !== trim( (string) ( $args[1] ?? '' ) ) ) ? realpath( $args[1] ) : false;
$public_root = rtrim( wp_normalize_path( realpath( ABSPATH ) ), '/' ) . '/';
if ( $apply && ( ! $backup_dir || ! is_writable( $backup_dir )
	|| str_starts_with( rtrim( wp_normalize_path( $backup_dir ), '/' ) . '/', $public_root ) ) ) {
	throw new RuntimeException( 'An existing writable backup directory outside the web root is required.' );
}
$pages = array( 'entreprises' => 7, 'business' => 8, 'collectivites' => 9, 'institutions' => 10 );
$plan = array();
foreach ( $pages as $slug => $id ) {
	$page = get_page_by_path( $slug );
	if ( ! $page || ! preg_match( '/\[fluentform\s+id=["\x27]' . $id . '["\x27]\s*\]/', $page->post_content ) ) {
		throw new RuntimeException( 'Unexpected page/form association: ' . $slug );
	}
	$row = $wpdb->get_row( $wpdb->prepare( "SELECT id,status,form_fields FROM {$wpdb->prefix}fluentform_forms WHERE id=%d", $id ), ARRAY_A );
	if ( ! $row || $row['status'] !== 'published' ) { throw new RuntimeException( 'Unexpected form status.' ); }
	$data = json_decode( $row['form_fields'], true, 512, JSON_THROW_ON_ERROR );
	$english = in_array( $id, array( 8, 10 ), true );
	$changes = array();
	$walk = function ( &$node ) use ( &$walk, &$changes, $english ) {
		$name = $node['attributes']['name'] ?? '';
		if ( ( $node['element'] ?? '' ) === 'select_country' && in_array( $name, array( 'pays', 'country' ), true ) ) {
			$node['element'] = 'input_text';
			$node['attributes']['type'] = 'text';
			$node['attributes']['value'] = '';
			unset( $node['attributes']['multiple'] );
			$node['settings']['editor_options'] = array( 'title' => 'Simple Text', 'icon_class' => 'ff-edit-text', 'template' => 'inputText' );
			$changes[] = $name . ': country list to free text';
		}
		if ( ( $node['element'] ?? '' ) === 'input_number' && in_array( $name, array( 'quantite', 'quantity' ), true ) ) {
			$old_min = $node['settings']['validation_rules']['min']['value'] ?? null;
			if ( ! is_numeric( $old_min ) || (float) $old_min < 1 ) {
				$node['settings']['validation_rules']['min'] = array(
					'value' => 1, 'global' => false,
					'message' => $english ? 'Please enter a quantity of at least 1.' : 'Indiquez une quantité d’au moins 1.',
				);
				$node['attributes']['min'] = 1;
				$changes[] = $name . ': minimum 1';
			}
		}
		foreach ( $node as &$child ) { if ( is_array( $child ) ) { $walk( $child ); } }
		unset( $child );
	};
	$walk( $data );
	if ( $changes ) {
		$plan[] = array( 'id' => $id, 'before' => $row['form_fields'], 'after' => wp_json_encode( $data, JSON_UNESCAPED_UNICODE ), 'changes' => $changes );
	}
}
foreach ( $plan as $item ) { WP_CLI::log( $item['id'] . ': ' . implode( '; ', $item['changes'] ) ); }
if ( ! $apply || ! $plan ) { WP_CLI::success( $apply ? 'Already prepared.' : 'Dry run; no changes written.' ); return; }
$backup = $backup_dir . '/enquiry-fields-' . gmdate( 'Ymd-His' ) . '-' . wp_generate_password( 6, false ) . '.json';
$handle = fopen( $backup, 'x' );
if ( ! $handle ) { throw new RuntimeException( 'Cannot create backup.' ); }
chmod( $backup, 0600 );
$backup_json = wp_json_encode( $plan, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
$written = fwrite( $handle, $backup_json );
fclose( $handle );
if ( $written !== strlen( $backup_json ) ) { throw new RuntimeException( 'Incomplete backup; no changes written.' ); }
if ( false === $wpdb->query( 'START TRANSACTION' ) ) { throw new RuntimeException( 'Cannot start transaction.' ); }
try {
	foreach ( $plan as $item ) {
		$current = $wpdb->get_var( $wpdb->prepare( "SELECT form_fields FROM {$wpdb->prefix}fluentform_forms WHERE id=%d FOR UPDATE", $item['id'] ) );
		if ( $current !== $item['before'] ) { throw new RuntimeException( 'Concurrent change; transaction cancelled.' ); }
		$result = $wpdb->update( $wpdb->prefix . 'fluentform_forms', array( 'form_fields' => $item['after'] ), array( 'id' => $item['id'] ), array( '%s' ), array( '%d' ) );
		if ( 1 !== $result ) { throw new RuntimeException( 'Update failed.' ); }
	}
	if ( false === $wpdb->query( 'COMMIT' ) ) { throw new RuntimeException( 'Commit failed.' ); }
} catch ( Throwable $error ) {
	$wpdb->query( 'ROLLBACK' );
	throw $error;
}
WP_CLI::success( 'Four enquiry definitions prepared; backup: ' . basename( $backup ) );

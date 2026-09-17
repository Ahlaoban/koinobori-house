<?php
/**
 * I1: forms 7 (Entreprises FR) and 8 (Business EN), field pays/country:
 * select_country (ISO list, includes an entry the public site must never show)
 * becomes a free text field. Name, label, required flag, validation rules and
 * every other setting are preserved. Same transformation as the one validated
 * on the private clone (tools/recovery/prepare_enquiry_fields.php).
 *
 *   wp eval-file tools/staging/pc4/i1-country-free-text.php                  # dry-run
 *   KH_APPLY=1 KH_CONFIRM=i1-country-free-text wp eval-file ...              # apply
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once __DIR__ . '/lib.php';
global $wpdb;

$ctx   = kh_pc4_boot( 'i1-country-free-text', 'i1-country-free-text' );
$table = $wpdb->prefix . 'fluentform_forms';
kh_pc4_require_innodb( $table );

$forms = array( 7 => array( 'name' => 'pays', 'title' => 'Entreprises FR' ), 8 => array( 'name' => 'country', 'title' => 'Business EN' ) );
$plan  = array();
foreach ( $forms as $id => $expect ) {
	$row = $wpdb->get_row( $wpdb->prepare( "SELECT id, title, status, form_fields FROM {$table} WHERE id = %d", $id ), ARRAY_A );
	kh_pc4_db_check( 'form ' . $id );
	if ( ! $row || 'published' !== $row['status'] || $row['title'] !== $expect['title'] ) {
		WP_CLI::error( 'Unexpected form ' . $id . ': ' . wp_json_encode( array( 'status' => $row['status'] ?? null, 'title' => $row['title'] ?? null ) ) );
	}
	$data    = json_decode( $row['form_fields'], true, 512, JSON_THROW_ON_ERROR );
	$found   = 0;
	$changes = array();
	$walk    = function ( &$node ) use ( &$walk, &$found, &$changes, $expect ) {
		if ( is_array( $node ) && ( $node['element'] ?? '' ) === 'select_country' && ( $node['attributes']['name'] ?? '' ) === $expect['name'] ) {
			++$found;
			$before = array(
				'label'    => $node['settings']['label'] ?? '',
				'required' => $node['settings']['validation_rules']['required']['value'] ?? null,
				'rules'    => array_keys( (array) ( $node['settings']['validation_rules'] ?? array() ) ),
			);
			$node['element']            = 'input_text';
			$node['attributes']['type'] = 'text';
			$node['attributes']['value'] = '';
			unset( $node['attributes']['multiple'] );
			$node['settings']['editor_options'] = array( 'title' => 'Simple Text', 'icon_class' => 'ff-edit-text', 'template' => 'inputText' );
			$after = array(
				'label'    => $node['settings']['label'] ?? '',
				'required' => $node['settings']['validation_rules']['required']['value'] ?? null,
				'rules'    => array_keys( (array) ( $node['settings']['validation_rules'] ?? array() ) ),
			);
			if ( $before !== $after ) {
				WP_CLI::error( 'Field settings would change beyond the element type; aborting.' );
			}
			$changes[] = $expect['name'] . ': select_country -> input_text (label "' . $after['label'] . '", required=' . var_export( $after['required'], true ) . ', rules=' . implode( ',', $after['rules'] ) . ')';
		}
		if ( is_array( $node ) ) {
			foreach ( $node as &$child ) { if ( is_array( $child ) ) { $walk( $child ); } }
			unset( $child );
		}
	};
	$walk( $data );
	if ( 0 === $found ) {
		WP_CLI::log( 'form ' . $id . ': field ' . $expect['name'] . ' is not select_country, nothing to do' );
		continue;
	}
	if ( 1 !== $found ) {
		WP_CLI::error( 'form ' . $id . ': expected exactly one ' . $expect['name'] . ' field, found ' . $found );
	}
	$plan[] = array( 'id' => $id, 'before' => $row['form_fields'], 'after' => wp_json_encode( $data, JSON_UNESCAPED_UNICODE ), 'changes' => $changes );
}

foreach ( $plan as $item ) {
	WP_CLI::log( 'form ' . $item['id'] . ': ' . implode( '; ', $item['changes'] ) );
}
if ( ! $plan ) {
	WP_CLI::success( 'Nothing to change.' );
	return;
}
if ( ! $ctx['apply'] ) {
	WP_CLI::success( 'Dry run: ' . count( $plan ) . ' form(s) would change. No write.' );
	return;
}

$restore = array();
foreach ( $plan as $item ) {
	$restore[] = array( 'kind' => 'row', 'table' => $table, 'where' => array( 'id' => $item['id'] ), 'data' => array( 'form_fields' => $item['before'] ) );
}
kh_pc4_backup( $ctx, 'i1-fluentform-forms', $restore );

kh_pc4_transaction( function () use ( $wpdb, $table, $plan ) {
	foreach ( $plan as $item ) {
		$current = $wpdb->get_var( $wpdb->prepare( "SELECT form_fields FROM {$table} WHERE id = %d FOR UPDATE", $item['id'] ) );
		if ( $current !== $item['before'] ) {
			throw new RuntimeException( 'form ' . $item['id'] . ' changed since the dry run.' );
		}
		if ( 1 !== $wpdb->update( $table, array( 'form_fields' => $item['after'] ), array( 'id' => $item['id'] ), array( '%s' ), array( '%d' ) ) ) {
			throw new RuntimeException( 'update failed for form ' . $item['id'] );
		}
	}
} );
WP_CLI::success( 'Applied to ' . count( $plan ) . ' form(s). Verify the public forms, then purge LiteSpeed.' );

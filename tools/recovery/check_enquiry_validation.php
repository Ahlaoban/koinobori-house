<?php
/** wp eval-file: exercise Fluent Forms field validators without submitting or sending. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! defined( 'KH2027_WEB_REVIEW' )
	|| ! KH2027_WEB_REVIEW || ! defined( 'KH2027_SANDBOX' ) || ! KH2027_SANDBOX ) {
	throw new RuntimeException( 'Private review clone only.' );
}

use FluentForm\App\Helpers\Helper;
use FluentForm\App\Services\Parser\Form as FormParser;
use FluentForm\App\Services\Parser\Validations;

$failures = 0;
$checks = 0;
foreach ( array( 'contact' => 5, 'contact-us' => 6, 'entreprises' => 7, 'business' => 8, 'collectivites' => 9, 'institutions' => 10 ) as $slug => $id ) {
	$page = get_page_by_path( $slug );
	if ( ! $page || ! preg_match( '/\[fluentform\s+id=["\x27]' . $id . '["\x27]\s*\]/', $page->post_content ) ) {
		throw new RuntimeException( 'Unexpected form/page association.' );
	}
	$form = \FluentForm\App\Models\Form::find( $id );
	$fields = ( new FormParser( $form ) )->getInputs( array( 'raw', 'rules' ) );
	$data = array();
	foreach ( $fields as $name => $field ) {
		$type = $field['element'];
		$value = 'Test de recette';
		if ( $type === 'input_email' ) { $value = 'qa@example.invalid'; }
		if ( $type === 'input_number' ) { $value = '1'; }
		if ( $type === 'input_date' ) { $value = ''; }
		if ( $type === 'terms_and_condition' ) { $value = 'on'; }
		if ( in_array( $type, array( 'select', 'input_radio' ), true ) ) {
			$options = $field['raw']['settings']['advanced_options'] ?? array();
			$value = $options[0]['value'] ?? '';
			if ( ! empty( $field['raw']['attributes']['multiple'] ) ) { $value = array( $value ); }
		}
		$data[ $name ] = $value;
	}
	$validate = function ( $values ) use ( $fields, $form ) {
		$rules = ( new Validations( $fields, $values ) )->get();
		$rules = apply_filters( 'fluentform/validations', $rules, $form, $values );
		$validator = wpFluentForm( 'validator' )->make( $values, $rules[0], $rules[1] );
		$validator->validate();
		$errors = $validator->errors();
		foreach ( $fields as $name => $field ) {
			$field['name'] = $name;
			$field['data_key'] = $name;
			$error = Helper::validateInput( $field, $values, $form );
			$error = $error ?: Helper::validateSelectionLimits( $field['raw'], $values[ $name ] ?? null );
			$error = apply_filters( 'fluentform/validate_input_item_' . $field['element'], $error, $field, $values, $fields, $form, $errors );
			if ( $error ) { $errors[ $name ] = (array) $error; }
		}
		return $errors;
	};
	$assert = function ( $ok, $case ) use ( &$checks, &$failures, $id ) {
		++$checks;
		if ( ! $ok ) { ++$failures; WP_CLI::log( 'FAIL form ' . $id . ' ' . $case ); }
	};
	$baseline = $validate( $data );
	$assert( ! $baseline, 'valid values with optional date empty: ' . implode( ',', array_keys( $baseline ) ) );
	foreach ( $fields as $name => $field ) {
		if ( ! empty( $field['rules']['required']['value'] ) ) {
			$values = $data;
			unset( $values[ $name ] );
			$assert( isset( $validate( $values )[ $name ] ), 'missing ' . $name );
		}
		$invalid = array();
		if ( $field['element'] === 'input_email' ) { $invalid = array( 'not-an-email' ); }
		if ( $field['element'] === 'input_number' ) { $invalid = array( '0', '-1', 'abc' ); }
		if ( $field['element'] === 'input_date' ) {
			$format = $field['raw']['settings']['date_format'];
			$values = $data;
			$values[ $name ] = ( new DateTimeImmutable( '2028-02-29' ) )->format( $format );
			$assert( ! $validate( $values ), 'valid leap day ' . $name );
			$invalid = array( 'not-a-date', array( '2028-02-29' ), "bad\0date" );
			// Format a valid 31 January, then replace its month to create 31 February.
			$impossible = ( new DateTimeImmutable( '2028-01-31' ) )->format( $format );
			if ( strpos( $format, 'm' ) !== false ) { $invalid[] = str_replace( '01', '02', $impossible ); }
		}
		if ( in_array( $field['element'], array( 'select', 'input_radio' ), true ) ) {
			$invalid = array( empty( $field['raw']['attributes']['multiple'] ) ? '__invalid_option__' : array( '__invalid_option__' ) );
		}
		foreach ( $invalid as $value ) {
			$values = $data;
			$values[ $name ] = $value;
			$assert( isset( $validate( $values )[ $name ] ), 'invalid ' . $name );
		}
	}
	WP_CLI::log( 'Checked form ' . $id );
}
// Exercise the native honeypot's rejection path while catching its JSON termination.
if ( ! defined( 'DOING_AJAX' ) ) { define( 'DOING_AJAX', true ); }
class KH_Enquiry_Antispam_Blocked extends RuntimeException {}
add_filter( 'wp_die_ajax_handler', function () {
	return function () { throw new KH_Enquiry_Antispam_Blocked(); };
} );
$honeypot = new \FluentForm\App\Modules\Form\HoneyPot( wpFluentForm() );
foreach ( array( 5, 6, 7, 8, 9, 10 ) as $id ) {
	$empty = $honeypot->getConversationalHoneypotInput( $id );
	if ( count( $empty ) !== 1 ) { ++$failures; WP_CLI::log( 'FAIL honeypot disabled ' . $id ); continue; }
	$key = array_key_first( $empty );
	foreach ( array( $empty, array(), array( $key => 'bot' ), array( $key => 'bot', 'isFFConversational' => 1 ) ) as $index => $request ) {
		++$checks;
		$blocked = false;
		ob_start();
		try { $honeypot->verify( array(), $request, $id ); }
		catch ( KH_Enquiry_Antispam_Blocked $error ) { $blocked = true; }
		finally { ob_end_clean(); }
		if ( $blocked !== ( $index > 0 ) ) { ++$failures; WP_CLI::log( 'FAIL honeypot ' . $id . ' case ' . $index ); }
	}
}
WP_CLI::log( 'Checks: ' . $checks . '; failures: ' . $failures . '. Field validators and native honeypot only; no submission, mail or nonce test.' );
if ( $failures ) { WP_CLI::error( 'Field validation needs attention.' ); }
WP_CLI::success( 'Field validators passed.' );

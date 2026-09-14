<?php
/** Presentation of the existing bilingual Fluent Forms enquiry pages. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function koinobori_child_is_enquiry_page() {
	return is_page( array( 'contact', 'contact-us', 'entreprises', 'business', 'collectivites', 'institutions' ) );
}

add_filter( 'body_class', function ( $classes ) {
	if ( koinobori_child_is_enquiry_page() ) { $classes[] = 'kh-enquiry-page'; }
	return $classes;
} );

add_action( 'wp_enqueue_scripts', function () {
	if ( ! koinobori_child_is_enquiry_page() ) { return; }
	$file = '/assets/css/enquiries.css';
	wp_enqueue_style( 'kh-enquiries', get_stylesheet_directory_uri() . $file,
		array( 'kh-charte-v3' ), filemtime( get_stylesheet_directory() . $file ) );
}, 30 );

// Verified against Fluent Forms 6.2.13 FormBuilder.php and Components/Text.php.
// https://developers.fluentforms.com/hooks/filters/form/
add_filter( 'fluentform/form_class', function ( $class, $form ) {
	return koinobori_child_is_enquiry_page() ? $class . ' kh-enquiry-form' : $class;
}, 10, 2 );

/** Browser autofill complements the labels; validation remains in Fluent Forms. */
function koinobori_child_enquiry_autocomplete( $data, $form ) {
	if ( ! koinobori_child_is_enquiry_page() ) { return $data; }
	$tokens = array(
		'prenom' => 'given-name', 'first_name' => 'given-name',
		'nom' => 'family-name', 'last_name' => 'family-name',
		'email' => 'email', 'organisation' => 'organization',
		'collectivite' => 'organization', 'institution' => 'organization',
		'pays' => 'country-name', 'country' => 'country-name',
	);
	$name = $data['attributes']['name'] ?? '';
	if ( isset( $tokens[ $name ] ) ) {
		$data['attributes']['autocomplete'] = $tokens[ $name ];
	}
	return $data;
}
add_filter( 'fluentform/rendering_field_data_input_text', 'koinobori_child_enquiry_autocomplete', 10, 2 );
add_filter( 'fluentform/rendering_field_data_input_email', 'koinobori_child_enquiry_autocomplete', 10, 2 );

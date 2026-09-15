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
	$style = '/assets/css/enquiries.css';
	$script = '/assets/js/enquiries.js';
	wp_enqueue_style( 'kh-enquiries', get_stylesheet_directory_uri() . $style,
		array( 'kh-charte-v3' ), filemtime( get_stylesheet_directory() . $style ) );
	wp_enqueue_script( 'kh-enquiries', get_stylesheet_directory_uri() . $script,
		array(), filemtime( get_stylesheet_directory() . $script ), true );
}, 30 );

// Verified against Fluent Forms 6.2.13 FormBuilder.php and Components/Text.php.
// https://developers.fluentforms.com/hooks/filters/form/
add_filter( 'fluentform/form_class', function ( $class, $form ) {
	return koinobori_child_is_enquiry_page() ? $class . ' kh-enquiry-form' : $class;
}, 10, 2 );

/** Resolve saved form associations for AJAX, where is_page() is not available. */
function koinobori_child_enquiry_form_language( $form_id ) {
	static $languages = null;
	if ( $languages === null ) {
		$languages = array();
		foreach ( array( 'contact' => 'fr', 'contact-us' => 'en', 'entreprises' => 'fr',
			'business' => 'en', 'collectivites' => 'fr', 'institutions' => 'en' ) as $slug => $language ) {
			$page = get_page_by_path( $slug );
			if ( $page && preg_match( '/\[fluentform\s+id=["\x27](\d+)["\x27]\s*\]/', $page->post_content, $match ) ) {
				$languages[ (int) $match[1] ] = $language;
			}
		}
	}
	return $languages[ (int) $form_id ] ?? null;
}

add_filter( 'fluentform/honeypot_status', function ( $enabled, $form_id ) {
	return koinobori_child_enquiry_form_language( $form_id ) ? true : $enabled;
}, 10, 2 );

add_filter( 'fluentform/honeypot_spam_message', function ( $message, $form_id ) {
	$language = koinobori_child_enquiry_form_language( $form_id );
	if ( ! $language ) { return $message; }
	return $language === 'fr'
		? 'La demande n’a pas pu être envoyée. Rechargez la page, puis réessayez.'
		: 'Your enquiry could not be sent. Reload the page and try again.';
}, 10, 2 );

add_filter( 'fluentform/validate_input_item_input_date', function ( $error, $field, $values, $fields, $form ) {
	$language = koinobori_child_enquiry_form_language( $form->id );
	$name = $field['raw']['attributes']['name'] ?? '';
	if ( $error || ! $language || ! in_array( $name, array( 'date_souhaitee', 'preferred_date' ), true ) ) { return $error; }
	$value = $values[ $name ] ?? '';
	if ( $value === '' || $value === null ) { return $error; }
	$format = $field['raw']['settings']['date_format'] ?? 'd/m/Y';
	$date = false;
	if ( is_string( $value ) && strpos( $value, "\0" ) === false ) {
		$date = DateTimeImmutable::createFromFormat( '!' . $format, $value );
	}
	$issues = DateTimeImmutable::getLastErrors();
	if ( $date && ( ! $issues || ( ! $issues['warning_count'] && ! $issues['error_count'] ) ) && $date->format( $format ) === $value ) {
		return $error;
	}
	return $language === 'fr' ? 'Choisissez une date valide dans le calendrier.' : 'Choose a valid date in the calendar.';
}, 10, 5 );

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
	// Country is a free-text field: its former select prompt is misleading.
	if ( in_array( $name, array( 'pays', 'country' ), true ) ) {
		$data['attributes']['placeholder'] = '';
	}
	return $data;
}
add_filter( 'fluentform/rendering_field_data_input_text', 'koinobori_child_enquiry_autocomplete', 10, 2 );
add_filter( 'fluentform/rendering_field_data_input_email', 'koinobori_child_enquiry_autocomplete', 10, 2 );

/** Keep the calendar French even when the plugin's language pack is incomplete. */
function koinobori_child_enquiry_is_french() {
	return koinobori_child_is_enquiry_page() && is_page( array( 'contact', 'entreprises', 'collectivites' ) );
}

add_filter( 'fluentform/date_i18n', function ( $locale ) {
	if ( ! koinobori_child_enquiry_is_french() ) { return $locale; }
	return array_replace( $locale, array(
		'weekdays' => array(
			'shorthand' => array( 'dim', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam' ),
			'longhand' => array( 'dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi' ),
		),
		'months' => array(
			'shorthand' => array( 'janv', 'févr', 'mars', 'avr', 'mai', 'juin', 'juil', 'août', 'sept', 'oct', 'nov', 'déc' ),
			'longhand' => array( 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre' ),
		),
		'firstDayOfWeek' => 1,
		'rangeSeparator' => ' au ',
		'weekAbbreviation' => 'Sem',
		'scrollTitle' => 'Faire défiler pour augmenter la valeur',
		'toggleTitle' => 'Cliquer pour basculer',
		'yearAriaLabel' => 'Année',
		'monthAriaLabel' => 'Mois',
	) );
} );

add_filter( 'fluentform/frontend_date_format', function ( $config ) {
	if ( koinobori_child_enquiry_is_french() ) { $config['ariaDateFormat'] = 'j F Y'; }
	return $config;
} );

add_filter( 'gettext_fluentform', function ( $translation, $text ) {
	if ( koinobori_child_enquiry_is_french()
		&& $text === ' Use arrow keys to navigate dates. Press enter to select a date.' ) {
		return ' Utilisez les flèches pour parcourir les dates et Entrée pour choisir une date.';
	}
	return $translation;
}, 10, 2 );

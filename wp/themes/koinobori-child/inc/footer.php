<?php
/** Bilingual service links and a light horizon footer. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function koinobori_child_footer_enabled() {
	return (bool) get_theme_mod( 'kh_horizon_footer', true );
}

add_action( 'wp', function () {
	if ( ! koinobori_child_footer_enabled() ) { return; }
	// Kadence 1.5.2 footer.php / inc/template-hooks.php, original priority 10.
	// https://developer.wordpress.org/reference/functions/remove_action/
	remove_action( 'kadence_footer', 'Kadence\\footer_markup', 10 );
	add_action( 'kadence_footer', 'koinobori_child_footer_render', 10 );
} );
add_action( 'wp_body_open', function () {
	if ( koinobori_child_footer_enabled() ) {
		echo '<span id="kh-page-top" tabindex="-1"></span>';
	}
} );
add_action( 'wp_enqueue_scripts', function () {
	if ( ! koinobori_child_footer_enabled() ) { return; }
	$file = '/assets/css/footer.css';
	wp_enqueue_style( 'kh-footer', get_stylesheet_directory_uri() . $file,
		array( 'kh-charte-v3' ), filemtime( get_stylesheet_directory() . $file ) );
}, 30 );

/** Keep missing/unpublished translations out of navigation, as in the header. */
function koinobori_child_footer_links( $entries, $language ) {
	$links = array();
	foreach ( $entries as $entry ) {
		$url = koinobori_child_editorial_page_url( $entry[0], $language );
		if ( $url ) {
			$links[] = array( 'url' => $url, 'label' => 'en' === $language ? $entry[2] : $entry[1] );
		}
	}
	return $links;
}

function koinobori_child_footer_render() {
	$language = koinobori_child_header_text( 'fr', 'en' );
	$groups = array(
		array( 'title' => koinobori_child_header_text( 'La maison', 'The house' ), 'entries' => array(
			array( 'boutique', 'Boutique', 'Shop' ),
			array( 'atelier', 'L’Atelier', 'The House' ),
			array( 'lifestyle', 'Lifestyle & Koi', 'Lifestyle & Koi' ),
		) ),
		array( 'title' => koinobori_child_header_text( 'Informations', 'Information' ), 'entries' => array(
			array( 'livraison', 'Livraison', 'Shipping' ),
			array( 'retours', 'Retours', 'Returns' ),
			array( 'contact', 'Contact', 'Contact' ),
		) ),
		array( 'title' => koinobori_child_header_text( 'Professionnels', 'For professionals' ), 'entries' => array(
			array( 'entreprises', 'Entreprises', 'Businesses' ),
			array( 'collectivites', 'Collectivités', 'Institutions' ),
		) ),
	);
	$legal = koinobori_child_footer_links( array(
		array( 'mentions-legales', 'Mentions légales', 'Legal notice' ),
		array( 'conditions-generales-de-vente', 'CGV', 'Terms of sale' ),
		array( 'politique-de-confidentialite', 'Confidentialité', 'Privacy' ),
		// Link to the real policy; consent management is provided by Complianz separately.
		array( 'politique-cookies', 'Cookies', 'Cookies' ),
	), $language );
	// Polylang owns translated URLs, including product/category translations.
	// https://polylang.pro/documentation/support/developers/function-reference/#pll_the_languages
	$languages = function_exists( 'pll_the_languages' ) ? pll_the_languages( array(
		'raw' => 1, 'hide_if_empty' => 0, 'hide_if_no_translation' => 1,
	) ) : array();
	include get_stylesheet_directory() . '/template-parts/footer-horizon.php';
}

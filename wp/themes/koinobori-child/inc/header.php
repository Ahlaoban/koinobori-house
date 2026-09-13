<?php
/** Split wordmark, WordPress menus and WooCommerce actions. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once __DIR__ . '/class-icon-walker.php';

function koinobori_child_header_text( $fr, $en ) {
	$lang = function_exists( 'pll_current_language' ) ? pll_current_language() : substr( get_locale(), 0, 2 );
	return 'en' === $lang ? $en : $fr;
}

function koinobori_child_header_enabled() { return (bool) get_theme_mod( 'kh_split_header', true ); }
add_action( 'init', function () {
	register_nav_menus( array( 'kh_header' => 'KH — Navigation à cinq icônes' ) );
}, 5 );
add_action( 'wp', function () {
	if ( ! koinobori_child_header_enabled() ) { return; }
	// Kadence 1.5.2 inc/template-hooks.php: keep the parent document and wrappers.
	remove_action( 'kadence_header', 'Kadence\header_markup' );
	add_action( 'kadence_header', 'koinobori_child_header_render' );
} );
add_action( 'wp_enqueue_scripts', function () {
	if ( ! koinobori_child_header_enabled() ) { return; }
	$dir = get_stylesheet_directory();
	$url = get_stylesheet_directory_uri();
	wp_enqueue_style( 'kh-header', $url . '/assets/css/header.css', array( 'kh-charte-v3' ), filemtime( $dir . '/assets/css/header.css' ) );
	wp_enqueue_script( 'kh-header', $url . '/assets/js/header.js', array(), filemtime( $dir . '/assets/js/header.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
	if ( function_exists( 'WC' ) ) { wp_enqueue_script( 'wc-cart-fragments' ); }
}, 30 );

/** Fallback until FR/EN menus are assigned: resolve existing published pages only. */
function koinobori_child_header_defaults() {
	$lang = koinobori_child_header_text( 'fr', 'en' );
	$items = array();
	foreach ( array(
		array( 'boutique', 'Boutique', 'Shop', 'shop' ),
		array( 'lifestyle', 'Lifestyle', 'Lifestyle', 'lifestyle' ),
		array( 'atelier', 'L’Atelier', 'The House', 'house' ),
		array( 'professionnels', 'Professionnels', 'For Professionals', 'professionals' ),
		array( 'contact', 'Contact', 'Contact', 'contact' ),
	) as $i => $entry ) {
		$url = koinobori_child_editorial_page_url( $entry[0], $lang );
		if ( 'shop' === $entry[3] && function_exists( 'wc_get_page_permalink' ) ) { $url = wc_get_page_permalink( 'shop' ); }
		if ( ! $url ) { continue; }
		$items[] = (object) array( 'ID' => 0, 'db_id' => 0, 'menu_item_parent' => 0, 'object_id' => 0,
			'url' => $url, 'title' => 'en' === $lang ? $entry[2] : $entry[1], 'classes' => array( 'icon-' . $entry[3] ),
			'current' => ( 'shop' === $entry[3] && function_exists( 'is_shop' ) && is_shop() ) || is_page( $entry[0] ), 'target' => '', 'xfn' => '' );
	}
	return $items;
}
function koinobori_child_header_menu() {
	$walker = new Koinobori_Icon_Walker();
	if ( has_nav_menu( 'kh_header' ) ) {
		wp_nav_menu( array( 'theme_location' => 'kh_header', 'container' => false, 'menu_class' => 'kh-nav-list',
			'menu_id' => '', 'depth' => 1, 'walker' => $walker, 'fallback_cb' => false ) );
	} else {
		echo '<ul class="kh-nav-list">' . $walker->walk( koinobori_child_header_defaults(), 1 ) . '</ul>'; // Escaped by the walker.
	}
}
function koinobori_child_header_cart( $mobile = false ) {
	if ( ! function_exists( 'WC' ) ) { return ''; }
	$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	$label = koinobori_child_header_text( 'Panier', 'Cart' );
	$class = $mobile ? 'kh-cart-mobile' : 'kh-cart-desktop';
	return '<a class="kh-action ' . $class . '" href="' . esc_url( wc_get_cart_url() ) . '" aria-label="'
		. esc_attr( $label . ' (' . $count . ')' ) . '">' . Koinobori_Icon_Walker::icon( 'cart' )
		. '<span class="kh-tip" aria-hidden="true">' . esc_html( $label ) . '</span><span class="kh-cart-count" aria-hidden="true">' . esc_html( $count ) . '</span></a>';
}
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
	$fragments['a.kh-cart-desktop'] = koinobori_child_header_cart();
	$fragments['a.kh-cart-mobile'] = koinobori_child_header_cart( true );
	return $fragments;
} );
function koinobori_child_header_actions( $mobile = false ) {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$label = koinobori_child_header_text( 'Mon compte', 'My account' );
		echo '<a class="kh-action" href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '" aria-label="' . esc_attr( $label ) . '">'
			. Koinobori_Icon_Walker::icon( 'account' ) . '<span class="kh-tip" aria-hidden="true">' . esc_html( $label ) . '</span></a>';
	}

	echo koinobori_child_header_cart( $mobile ); // Escaped at construction.
	if ( function_exists( 'pll_the_languages' ) ) {
		$languages = pll_the_languages( array( 'raw' => 1, 'hide_if_empty' => 0, 'hide_if_no_translation' => 1 ) );
		echo '<div class="kh-header-languages" aria-label="' . esc_attr( koinobori_child_header_text( 'Langue', 'Language' ) ) . '">';
		foreach ( $languages as $language ) {
			echo '<a href="' . esc_url( $language['url'] ) . '" lang="' . esc_attr( $language['slug'] ) . '" hreflang="' . esc_attr( $language['slug'] ) . '"'
				. ( $language['current_lang'] ? ' aria-current="true"' : '' ) . '>' . esc_html( strtoupper( $language['slug'] ) ) . '</a>';
		}
		echo '</div>';
	}
}
function koinobori_child_header_render() {
	$home = function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
	$home_label = koinobori_child_header_text( 'KoinoboriHouse — Accueil', 'KoinoboriHouse — Home' );
	$close = koinobori_child_header_text( 'Fermer le menu', 'Close menu' );
	include get_stylesheet_directory() . '/template-parts/header-shoji.php';
}

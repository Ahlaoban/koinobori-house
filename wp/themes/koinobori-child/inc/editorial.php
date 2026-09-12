<?php
/** Opt-in editorial layouts. Existing pages and shop settings remain unchanged. */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function koinobori_child_editorial_enabled() {
	return is_page_template( 'page-templates/kh-home.php' )
		|| ( function_exists( 'is_product' ) && is_product() && get_theme_mod( 'kh_editorial_products', false ) );
}

add_filter( 'body_class', function ( $classes ) {
	if ( koinobori_child_editorial_enabled() ) {
		$classes[] = 'kh-page';
		$classes[] = 'kh-editorial';
	}
	return $classes;
} );

add_action( 'wp_enqueue_scripts', function () {
	if ( koinobori_child_editorial_enabled() ) {
		$file = '/assets/css/kh-editorial.css';
		wp_enqueue_style( 'kh-editorial', get_stylesheet_directory_uri() . $file,
			array( 'kh-charte-v3' ), filemtime( get_stylesheet_directory() . $file ) );
	}
}, 25 );

/** Resolve a published page in the requested language; never invent a destination. */
function koinobori_child_editorial_page_url( $fr_slug, $language ) {
	$page = get_page_by_path( $fr_slug );
	if ( ! $page ) {
		return '';
	}
	$id = $page->ID;
	if ( function_exists( 'pll_get_post' ) ) {
		$id = pll_get_post( $id, $language );
	}
	return $id && 'publish' === get_post_status( $id ) ? get_permalink( $id ) : '';
}

/** Keep category IDs and translated URLs under WooCommerce/Polylang authority. */
function koinobori_child_editorial_world_url( $slug, $language ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( ! $term || is_wp_error( $term ) ) {
		return '';
	}
	$id = $term->term_id;
	if ( function_exists( 'pll_get_term' ) ) {
		$id = pll_get_term( $id, $language );
	}
	$url = $id ? get_term_link( (int) $id, 'product_cat' ) : '';
	return is_wp_error( $url ) ? '' : $url;
}

/** Query in the active language. WooCommerce still renders images, prices and links. */
function koinobori_child_editorial_products( $language ) {
	if ( ! function_exists( 'wc_get_product' ) || ! shortcode_exists( 'products' ) ) {
		return;
	}
	$query = new WP_Query( array(
		'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 4,
		'fields' => 'ids', 'orderby' => 'menu_order title', 'order' => 'ASC',
		'lang' => $language, 'no_found_rows' => true,
		'tax_query' => array( array(
			'taxonomy' => 'product_visibility', 'field' => 'name',
			'terms' => array( 'exclude-from-catalog' ), 'operator' => 'NOT IN',
		) ),
	) );
	if ( $query->posts ) {
		$ids = implode( ',', array_map( 'absint', $query->posts ) );
		// IDs come from the query, never from visitor input. WooCommerce owns the markup.
		echo do_shortcode( '[products ids="' . $ids . '" columns="4" orderby="post__in"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

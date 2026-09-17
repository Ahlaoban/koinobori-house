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

/**
 * Resolve a page in the requested language; never invent a destination.
 * Strict mode returns '' unless the translation is published. Lenient mode
 * (legal links) falls back to the French page and keeps the link even while
 * the page is temporarily unpublished, so mandatory links never vanish.
 */
function koinobori_child_editorial_page_url( $fr_slug, $language, $require_published = true ) {
	$page = get_page_by_path( $fr_slug );
	if ( ! $page ) {
		return '';
	}
	$fr_id = $page->ID;
	$id    = $fr_id;
	if ( function_exists( 'pll_get_post' ) ) {
		$id = pll_get_post( $fr_id, $language );
	}
	if ( $id && 'publish' === get_post_status( $id ) ) {
		return get_permalink( $id );
	}
	if ( $require_published ) {
		return '';
	}
	if ( 'publish' === get_post_status( $fr_id ) ) {
		return get_permalink( $fr_id );
	}
	return get_permalink( $id ? $id : $fr_id );
}

/** Keep category IDs and translated URLs under WooCommerce/Polylang authority. */
function koinobori_child_editorial_world_url( $slug, $language ) {
	// The canonical slugs are French. Query that language explicitly, even on /en/.
	$terms = get_terms( array(
		'taxonomy' => 'product_cat', 'slug' => $slug, 'lang' => 'fr',
		'hide_empty' => false, 'number' => 1,
	) );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return '';
	}
	$id = $terms[0]->term_id;
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

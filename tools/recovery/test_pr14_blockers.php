<?php
/**
 * Clone-only checks for the PR #14 blocker fixes (B1 header walker, B3 legal links).
 *
 * Run with WP-CLI on the private clone:
 *   wp eval-file tools/recovery/test_pr14_blockers.php
 *
 * Every write happens inside a transaction that is always rolled back: no menu,
 * page status or theme mod survives the run. Prints one JSON object; exit code 1
 * on any failure. Tests 6 (HTTP /fr/ and /en/) and 7 (notification import) are
 * documented separately and not covered here.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! defined( 'WP_CLI' ) || ! WP_CLI || ! defined( 'KH2027_SANDBOX' ) || ! KH2027_SANDBOX
	|| 'local' !== wp_get_environment_type() || 'sc3heal3867_kh2027drill' !== DB_NAME
	|| true !== apply_filters( 'pre_wp_mail', null, array() ) ) {
	WP_CLI::error( 'Private review clone only.' );
}

global $wpdb;

// Load the child theme modules if the active theme or the review guard did not.
foreach ( array(
	'koinobori_child_editorial_page_url' => 'editorial',
	'koinobori_child_header_menu'        => 'header',
	'koinobori_child_footer_legal_url'   => 'footer',
) as $fn => $inc ) {
	if ( ! function_exists( $fn ) ) {
		require_once get_stylesheet_directory() . '/inc/' . $inc . '.php';
	}
	if ( ! function_exists( $fn ) ) {
		WP_CLI::error( 'Theme function missing: ' . $fn . ' (is the deployed theme at ' . $fn . '?)' );
	}
}
if ( ! class_exists( 'Koinobori_Icon_Walker' ) ) {
	WP_CLI::error( 'Koinobori_Icon_Walker missing.' );
}

$results = array();
function kh_t( $name, $condition, $detail = '' ) {
	$GLOBALS['results'][ $name ] = array( 'pass' => (bool) $condition, 'detail' => $detail );
}
function kh_page_id( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? (int) $page->ID : 0;
}
function kh_set_status( $id, $status ) {
	global $wpdb;
	$wpdb->update( $wpdb->posts, array( 'post_status' => $status ), array( 'ID' => $id ) );
	clean_post_cache( $id );
}
function kh_render_menu() {
	ob_start();
	koinobori_child_header_menu();
	return (string) ob_get_clean();
}
function kh_icon_keys( $menu_id ) {
	$keys = array();
	foreach ( (array) wp_get_nav_menu_items( $menu_id ) as $item ) {
		$keys[] = Koinobori_Icon_Walker::key_for( $item );
	}
	return $keys;
}

$wpdb->query( 'START TRANSACTION' );
try {
	// --- Test 1: real menu, no icon classes -------------------------------------
	$expected_keys = array( 'shop', 'lifestyle', 'house', 'professionals', 'contact' );
	$pages = array( 'lifestyle', 'atelier', 'professionnels', 'contact' );
	$menu_id = wp_create_nav_menu( 'kh-test-pr14-' . wp_generate_password( 4, false ) );
	if ( is_wp_error( $menu_id ) ) {
		throw new RuntimeException( 'Cannot create test menu.' );
	}
	$shop_id = function_exists( 'wc_get_page_id' ) ? (int) wc_get_page_id( 'shop' ) : 0;
	$item_ids = array();
	$item_ids[] = wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-object-id' => $shop_id, 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish', 'menu-item-title' => 'Boutique' ) );
	foreach ( $pages as $slug ) {
		$item_ids[] = wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-object-id' => kh_page_id( $slug ), 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish', 'menu-item-title' => ucfirst( $slug ) ) );
	}
	$locations = (array) get_theme_mod( 'nav_menu_locations', array() );
	$locations['kh_header'] = (int) $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	$html = kh_render_menu();
	$li_open = substr_count( $html, '<li class="kh-nav-item">' );
	$li_close = substr_count( $html, '</li>' );
	$keys = kh_icon_keys( $menu_id );
	kh_t( '1_menu_without_classes_renders_5', has_nav_menu( 'kh_header' ) && 5 === $li_open && 5 === $li_close, "li={$li_open}/{$li_close}" );
	kh_t( '1_menu_without_classes_icons', $keys === $expected_keys, implode( ',', $keys ) );
	kh_t( '1_no_neutral_icon', false === strpos( $html, 'M10 13a5 5 0 0 0 7.54.54' ), '' );

	// --- Test 2: same menu with explicit icon-* classes -------------------------
	foreach ( $item_ids as $i => $item_id ) {
		update_post_meta( $item_id, '_menu_item_classes', array( 'icon-' . $expected_keys[ $i ] ) );
	}
	$keys2 = kh_icon_keys( $menu_id );
	$html2 = kh_render_menu();
	kh_t( '2_menu_with_classes', $keys2 === $expected_keys && 5 === substr_count( $html2, '</li>' ), implode( ',', $keys2 ) );

	// --- Test 3: unknown custom link gets the neutral icon and a closed li -------
	wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-type' => 'custom', 'menu-item-url' => home_url( '/kh-test-unknown/' ), 'menu-item-status' => 'publish', 'menu-item-title' => 'Inconnu' ) );
	$keys3 = kh_icon_keys( $menu_id );
	$html3 = kh_render_menu();
	kh_t( '3_unknown_item_neutral_icon', 'link' === end( $keys3 ) && 6 === substr_count( $html3, '</li>' ) && false !== strpos( $html3, 'M10 13a5 5 0 0 0 7.54.54' ), implode( ',', $keys3 ) );

	// --- Test 4: EN legal translation missing -> published FR page, never '' -----
	$fr_id = kh_page_id( 'mentions-legales' );
	$en_id = function_exists( 'pll_get_post' ) ? (int) pll_get_post( $fr_id, 'en' ) : 0;
	kh_t( '4_precondition_pages', $fr_id && $en_id, "fr={$fr_id} en={$en_id}" );
	if ( $fr_id && $en_id ) {
		kh_set_status( $en_id, 'draft' );
		$legal_en = koinobori_child_footer_legal_url( 'mentions-legales', 'en' );
		$strict_en = koinobori_child_editorial_page_url( 'mentions-legales', 'en' );
		kh_t( '4_en_draft_falls_back_to_fr', '' === $strict_en && $legal_en === get_permalink( $fr_id ), $legal_en );
		ob_start();
		wp_set_current_user( (int) ( get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ) )[0] ?? 0 ) );
		do_action( 'admin_notices' );
		$notices4 = (string) ob_get_clean();
		kh_t( '4_admin_warning_only', false !== strpos( $notices4, 'notice-warning' ) && false === strpos( $notices4, 'bloquant avant mise en ligne' ), '' );

		// --- Test 5: FR legal page in draft -> no link, blocking notice -------------
		kh_set_status( $fr_id, 'draft' );
		$legal_fr = koinobori_child_footer_legal_url( 'mentions-legales', 'fr' );
		$legal_en2 = koinobori_child_footer_legal_url( 'mentions-legales', 'en' );
		kh_t( '5_fr_draft_no_link_no_draft_permalink', '' === $legal_fr && '' === $legal_en2, "fr='{$legal_fr}' en='{$legal_en2}'" );
		ob_start();
		do_action( 'admin_notices' );
		$notices5 = (string) ob_get_clean();
		kh_t( '5_admin_blocking_notice', false !== strpos( $notices5, 'bloquant avant mise en ligne' ) && false !== strpos( $notices5, 'mentions-legales' ), '' );
	}
} finally {
	$wpdb->query( 'ROLLBACK' );
}

$failed = array_keys( array_filter( $results, static function ( $r ) { return ! $r['pass']; } ) );
echo wp_json_encode( array( 'rolled_back' => true, 'failed' => $failed, 'results' => $results ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . PHP_EOL;
if ( $failed ) {
	WP_CLI::halt( 1 );
}

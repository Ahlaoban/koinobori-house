<?php
/** Isolated contract tests; does not load WordPress or touch a database. */
define( 'ABSPATH', __DIR__ );
$hooks = array(); $allowed = true; $nonce = true; $checks = 0;
function add_action( $name, $callback, ...$args ) { $GLOBALS['hooks'][$name] = $callback; }
function add_filter( $name, $callback, ...$args ) { $GLOBALS['hooks'][$name] = $callback; }
function absint( $v ) { return abs( (int) $v ); }
function get_post_type( $id ) { return 99 === $id ? 'post' : 'attachment'; }
function get_post_status( $id ) { return 8 === $id ? 'trash' : 'inherit'; }
function get_post_mime_type( $id ) { return array( 1 => 'video/mp4', 2 => 'video/webm', 3 => 'image/jpeg', 4 => 'text/html', 8 => 'video/mp4', 99 => 'video/mp4' )[$id] ?? ''; }
function wp_get_attachment_url( $id ) { return 'https://example.test/uploads/' . $id . '.mp4'; }
function wp_get_attachment_image_url( $id, $size ) { return 'https://example.test/uploads/' . $id . '.jpg'; }
function get_locale() { return 'fr_FR'; }
function esc_attr( $v ) { return htmlspecialchars( $v, ENT_QUOTES ); }
function esc_url( $v ) { return esc_attr( $v ); }
function esc_html( $v ) { return esc_attr( $v ); }
function sanitize_text_field( $v ) { return strip_tags( $v ); }
function wp_unslash( $v ) { return $v; }
function wp_verify_nonce( ...$args ) { return $GLOBALS['nonce']; }
function current_user_can( ...$args ) { return $GLOBALS['allowed']; }
class WC_Admin_Meta_Boxes { static $errors = array(); static function add_error( $message ) { self::$errors[] = $message; } }
class MediaProduct {
	public $meta = array();
	function get_id() { return 42; }
	function get_meta( $key ) { return $this->meta[$key] ?? ''; }
	function update_meta_data( $key, $value ) { $this->meta[$key] = $value; }
	function get_image_id() { return 3; }
	function get_name() { return '<Koi> & vent'; }
}
function check( $condition, $message ) { $GLOBALS['checks']++; if ( ! $condition ) { throw new RuntimeException( $message ); } }
require dirname( __DIR__, 2 ) . '/wp/plugins/kh-product-media/kh-product-media.php';
$product = new MediaProduct();
check( false === khpm_video_data( $product ), 'Existing product without video unchanged' );
check( ! isset( $hooks['woocommerce_product_tabs']( array() )['khpm_video'] ), 'No empty tab' );
$_POST = array( 'khpm_nonce' => 'valid', 'khpm_video_id' => '1', 'khpm_poster_id' => '3' );
khpm_save( $product );
check( 1 === $product->get_meta( '_khpm_video_id' ), 'Valid MP4 saved' );
check( isset( $hooks['woocommerce_product_tabs']( array() )['khpm_video'] ), 'Video tab appears' );
ob_start(); khpm_render(); $html = ob_get_clean();
check( str_contains( $html, 'preload="none"' ) && ! str_contains( $html, 'autoplay' ), 'No autoplay or eager loading' );
check( str_contains( $html, '&lt;Koi&gt; &amp; vent' ), 'Product names escaped' );
check( str_contains( $html, '3.jpg' ), 'Chosen poster rendered' );
foreach ( array( '4', '8', '99', '-1', '1garbage', array( '1' ) ) as $bad ) {
	$_POST['khpm_video_id'] = $bad; khpm_save( $product );
	check( 1 === $product->get_meta( '_khpm_video_id' ), 'Invalid input preserves previous video' );
}
$_POST['khpm_video_id'] = '2'; $allowed = false; khpm_save( $product );
check( 1 === $product->get_meta( '_khpm_video_id' ), 'Unauthorized save ignored' );
$allowed = true; $nonce = false; khpm_save( $product );
check( 1 === $product->get_meta( '_khpm_video_id' ), 'Invalid nonce ignored' );
$nonce = true; $_POST = array(); khpm_save( $product );
check( 1 === $product->get_meta( '_khpm_video_id' ), 'Quick edit/import does not clear video' );
$_POST = array( 'khpm_nonce' => 'valid', 'khpm_video_id' => '2', 'khpm_poster_id' => '0' ); khpm_save( $product );
check( 'video/webm' === khpm_video_data( $product )['type'], 'WebM supported' );
check( str_contains( khpm_video_data( $product )['poster'], '3.jpg' ), 'Featured image fallback' );
$_POST['khpm_video_id'] = '0'; khpm_save( $product );
check( false === khpm_video_data( $product ), 'Explicit remove clears relation' );
check( in_array( '_khpm_video_id', $hooks['pll_copy_post_metas']( array() ), true ), 'Translation copy includes media' );
echo "PASS: $checks media contract checks (isolated PHP, not a WordPress integration test).\n";

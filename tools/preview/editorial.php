<?php
/** Local visual harness only. Fixtures are not a WooCommerce integration test. */
if ( PHP_SAPI !== 'cli-server' && PHP_SAPI !== 'cli' ) { http_response_code( 404 ); exit; }
$repo = dirname( __DIR__, 2 );
$theme = $repo . '/wp/themes/koinobori-child';
$path = parse_url( $_SERVER['REQUEST_URI'] ?? '/fr/', PHP_URL_PATH );
if ( str_starts_with( $path, '/assets/' ) ) {
	$asset = realpath( $theme . $path );
	$allowed = realpath( $theme . '/assets' ) . DIRECTORY_SEPARATOR;
	if ( ! $asset || ! str_starts_with( $asset, $allowed ) || ! is_file( $asset ) ) { http_response_code( 404 ); exit; }
	$types = array( 'css' => 'text/css', 'webp' => 'image/webp', 'woff2' => 'font/woff2' );
	$ext = pathinfo( $asset, PATHINFO_EXTENSION );
	if ( ! isset( $types[$ext] ) ) { http_response_code( 404 ); exit; }
	header( 'Content-Type: ' . $types[$ext] ); readfile( $asset ); exit;
}
if ( '/logo.png' === $path ) { header( 'Content-Type: image/png' ); readfile( $repo . '/brand/koinoborihouse/logo-color.png' ); exit; }
if ( str_starts_with( $path, '/catalog/images/' ) ) {
	$asset = realpath( $repo . $path );
	$allowed = realpath( $repo . '/catalog/images' ) . DIRECTORY_SEPARATOR;
	if ( ! $asset || ! str_starts_with( $asset, $allowed ) || 'jpg' !== pathinfo( $asset, PATHINFO_EXTENSION ) ) { http_response_code( 404 ); exit; }
	header( 'Content-Type: image/jpeg' ); readfile( $asset ); exit;
}
if ( ! in_array( $path, array( '/fr/', '/en/', '/product/fr/', '/product/en/' ), true ) ) { http_response_code( 404 ); exit; }
define( 'ABSPATH', $repo . '/' );
$language = str_contains( $path, '/en/' ) ? 'en' : 'fr';
$product_view = str_starts_with( $path, '/product/' );
function add_filter( ...$args ) {}
function add_action( ...$args ) {}
function pll_current_language( $format = '' ) { global $language; return $language; }
function esc_html( $v ) { return htmlspecialchars( (string) $v, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $v ) { return esc_html( $v ); }
function esc_url( $v ) { return esc_html( $v ); }
function get_page_by_path( $slug ) { return (object) array( 'ID' => $slug ); }
function pll_get_post( $id, $lang ) { return $lang . '-' . $id; }
function get_post_status( $id ) { return 'publish'; }
function get_permalink( $id ) { return '#preview-footer'; }
function get_terms( $args ) { return array( (object) array( 'term_id' => 1 ) ); }
function pll_get_term( $id, $lang ) { return $id; }
function get_term_link( $id, $taxonomy ) { global $language; return '/product/' . $language . '/'; }
function is_wp_error( $value ) { return false; }
function absint( $id ) { return abs( (int) $id ); }
function shortcode_exists( $name ) { return true; }
function wc_get_product( $id ) { return false; }
function wp_reset_postdata() {}
function apply_filters( $name, $value, ...$args ) { return $value; }
class WP_Query {
	public $posts;
	public function __construct( $args ) { $this->posts = 'product' === $args['post_type'] ? array( 1, 2, 3, 4 ) : array(); }
	public function have_posts() { return false; }
}
function do_shortcode( $value ) {
	global $language;
	$fixtures = array( array( 'hanami/001-sakura-rouge', 'Hanami', 'Sakura Rouge' ), array( 'kairo/001-la-promesse-de-la-mer', 'Kaïro', 'La Promesse de la Mer' ), array( 'territoires/003-bigouden', 'Territoires', 'Bigouden' ), array( 'territoires/002-breton', 'Territoires', 'Breton' ) );
	$html = '<div class="woocommerce"><ul class="products columns-4">';
	foreach ( $fixtures as $fixture ) {
		$html .= '<li class="product"><a href="/product/' . $language . '/"><img src="/catalog/images/' . $fixture[0] . '/main.jpg" alt="' . esc_attr( $fixture[2] ) . '" width="600" height="750" loading="lazy"><span class="kh-eyebrow">' . $fixture[1] . '</span><h2 class="woocommerce-loop-product__title">' . $fixture[2] . ' - by BCDG</h2></a></li>';
	}
	return $html . '</ul></div>';
}
function get_header() {
	global $language, $product_view;
	header( 'Content-Type: text/html; charset=utf-8' );
	$base = $product_view ? '/product/' : '/';
	echo '<!doctype html><html lang="' . $language . '"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Koinobori House · Aperçu éditorial</title>';
	foreach ( array( 'kh-foundations', 'kh-charte-v3', 'kh-editorial' ) as $css ) { echo '<link rel="stylesheet" href="/assets/css/' . $css . '.css">'; }
	echo '<style>body{margin:0}.preview-note{padding:.6rem 1.25rem;background:#1a1410;color:#fffdfc;font:14px/1.5 Arial,sans-serif}.preview-header{display:flex;gap:2rem;align-items:center;justify-content:space-between;padding:1.4rem max(20px,calc((100vw - 1200px)/2));border-bottom:1px solid #d5cfc6}.preview-header img{width:230px;height:auto}.preview-header nav{display:flex;gap:1rem;flex-wrap:wrap;font:16px Arial}.preview-product{max-width:1200px;margin:4rem auto;padding:0 20px}.preview-product .product{display:grid;grid-template-columns:1.1fr 1fr;gap:4rem}.preview-product img{width:100%;height:auto}.preview-product .price{margin:2rem 0}.preview-product label{display:block;margin:1.5rem 0 .5rem}.preview-product .single_add_to_cart_button{margin-top:1.5rem;padding:1rem 2rem;border:0}.preview-footer{border-top:1px solid #d5cfc6;padding:2rem 20px;text-align:center;font:14px Arial}@media(max-width:700px){.preview-header{gap:1rem;flex-wrap:wrap}.preview-header img{width:190px}.preview-product .product{grid-template-columns:1fr;gap:2rem}.preview-product{margin:2rem auto}}</style></head>';
	echo '<body class="kh-page kh-editorial' . ( $product_view ? ' single-product woocommerce' : '' ) . '"><div class="preview-note">Aperçu local · Composition en cours · Visuels de référence · Aucun achat ni envoi</div><header class="preview-header"><a href="/' . $language . '/"><img src="/logo.png" alt="Koinobori House" width="1172" height="213"></a><nav aria-label="Aperçu"><a href="/' . $language . '/">' . ( 'en' === $language ? 'Home' : 'Accueil' ) . '</a><a href="/product/' . $language . '/">' . ( 'en' === $language ? 'Product' : 'Produit' ) . '</a><a href="' . $base . 'fr/" lang="fr">FR</a><a href="' . $base . 'en/" lang="en">EN</a></nav></header>';
}
function get_footer() { echo '<footer id="preview-footer" class="preview-footer">Koinobori House · Créations BCDG</footer></body></html>'; }
require $theme . '/inc/editorial.php';
if ( ! $product_view ) { require $theme . '/page-templates/kh-home.php'; exit; }
get_header();
?>
<main class="preview-product"><div class="product">
<div class="woocommerce-product-gallery"><img src="/catalog/images/hanami/001-sakura-rouge/main.jpg" alt="Sakura Rouge, visuel du catalogue" width="2200" height="1080"></div>
<div class="summary entry-summary"><p class="kh-eyebrow">Hanami · BCDG</p><h1 class="product_title">Sakura Rouge<br>Koinobori - by BCDG</h1>
<div class="woocommerce-product-details__short-description"><p><?php echo 'en' === $language ? 'A flying carp scattered with sakura petals, deep red on a soft ground. A decorative piece inspired by hanami, to hang indoors or in the garden.' : 'Une carpe volante semée de pétales de sakura, rouge profond sur fond clair. Pièce décorative inspirée du hanami, à suspendre en intérieur comme au jardin.'; ?></p></div>
<p class="price">29 € <small>(<?php echo 'en' === $language ? 'reference price' : 'tarif de référence'; ?>)</small></p>
<div class="variations"><label for="size"><?php echo 'en' === $language ? 'Size' : 'Taille'; ?></label><select id="size"><option>75 cm</option></select></div>
<button class="single_add_to_cart_button" disabled><?php echo 'en' === $language ? 'Visual preview only' : 'Aperçu visuel uniquement'; ?></button>
</div></div><section class="woocommerce-tabs"><div class="panel"><h2><?php echo 'en' === $language ? 'Details and materials' : 'Détails et matières'; ?></h2><p><?php echo 'en' === $language ? 'A piece to hang indoors or outdoors, signed by BCDG and released in a small series.' : 'Une pièce à suspendre en intérieur ou en extérieur, signée BCDG et éditée en petite série.'; ?></p></div></section></main>
<?php get_footer(); ?>

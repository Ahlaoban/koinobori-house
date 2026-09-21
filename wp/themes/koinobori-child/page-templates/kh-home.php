<?php
/**
 * Template Name: KH — Accueil éditorial
 * Template Post Type: page
 *
 * Every public text of the home lives in the page content (native blocks), edited in
 * WordPress. This template only provides the frame. Kadence 1.5.2 header.php already
 * opens <main id="inner-wrap">: no nested landmark, and kadence_single (page title) is
 * not fired here, so the H1 is the heading block of the content.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div id="main" class="kh-home">
	<?php
	while ( have_posts() ) {
		the_post();
		the_content();
	}
	// Newsletter: rendered only once an actual, configured form is supplied, heading included.
	$newsletter = apply_filters( 'koinobori_child_newsletter_markup', '', koinobori_child_header_text( 'fr', 'en' ) );
	if ( is_string( $newsletter ) && '' !== trim( $newsletter ) ) {
		echo '<section class="kh-movement kh-newsletter"><div class="kh-container">' . $newsletter . '</div></section>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted plugin form rendering.
	}
	?>
</div>
<?php
get_footer();

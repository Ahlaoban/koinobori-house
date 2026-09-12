<?php
/**
 * Template Name: KH — Accueil éditorial
 * Template Post Type: page
 * Select explicitly on a review page; no existing content is overwritten.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$language = function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : substr( get_locale(), 0, 2 );
$language = 'en' === $language ? 'en' : 'fr';
$t = static function ( $fr, $en ) use ( $language ) { return 'en' === $language ? $en : $fr; };
$page_url = static function ( $slug ) use ( $language ) { return koinobori_child_editorial_page_url( $slug, $language ); };
$worlds = array(
	array( 'mer', $t( 'Mer', 'Sea' ), $t( "L’appel du large", 'The call of the open sea' ) ),
	array( 'motifs', $t( 'Motifs', 'Patterns' ), $t( 'Symboles et écailles graphiques', 'Graphic symbols and scales' ) ),
	array( 'hanami', 'Hanami', $t( 'La contemplation des fleurs', 'Blossom viewing' ) ),
	array( 'kairo', 'Kaïro', $t( 'Un personnage, un cycle, quinze épisodes', 'One character, one cycle, fifteen episodes' ) ),
	array( 'territoires', $t( 'Territoires', 'Lands' ), $t( 'Régions et drapeaux revisités', 'Regions and flags revisited' ) ),
);
get_header();
?>
<main id="primary" class="kh-home">
	<section class="kh-home-hero" aria-labelledby="kh-home-title">
		<div class="kh-container kh-home-hero__inner">
			<p class="kh-eyebrow">Koinobori House · <?php echo esc_html( $t( 'Créations BCDG', 'BCDG Creations' ) ); ?></p>
			<h1 id="kh-home-title"><?php echo esc_html( $t( 'Des carpes de vent originales, signées BCDG', 'Original wind carps, signed by BCDG' ) ); ?></h1>
			<p class="kh-lead"><?php echo esc_html( $t( 'Koinobori contemporains à suspendre, édités en petites séries.', 'Contemporary koinobori to hang, released in small series.' ) ); ?></p>
			<a class="kh-button kh-button--primary" href="#kh-worlds"><?php echo esc_html( $t( 'Découvrir les cinq mondes', 'Discover the five worlds' ) ); ?></a>
		</div>
		<div class="kh-home-hero__art" role="img" aria-label="<?php echo esc_attr( $t( 'Koinobori dans un paysage japonais', 'Koinobori in a Japanese landscape' ) ); ?>"></div>
	</section>
	<section id="kh-worlds" class="kh-movement" aria-labelledby="kh-worlds-title">
		<div class="kh-container">
			<p class="kh-eyebrow">01 · <?php echo esc_html( $t( 'Les univers', 'The worlds' ) ); ?></p>
			<h2 id="kh-worlds-title" class="kh-title"><?php echo esc_html( $t( 'Cinq mondes', 'Five worlds' ) ); ?></h2>
			<div class="kh-home-worlds">
			<?php foreach ( $worlds as $index => $world ) :
				$url = koinobori_child_editorial_world_url( $world[0], $language ); ?>
				<article class="kh-home-world">
					<span class="kh-eyebrow" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
					<h3><?php if ( $url ) : ?><a href="<?php echo esc_url( $url ); ?>"><?php endif; ?><?php echo esc_html( $world[1] ); ?><?php if ( $url ) : ?><span aria-hidden="true"> ↗</span></a><?php endif; ?></h3>
					<p><?php echo esc_html( $world[2] ); ?></p>
				</article>
			<?php endforeach; ?>
			</div>
		</div>
	</section>
	<section class="kh-movement kh-home-creations" aria-labelledby="kh-creations-title">
		<div class="kh-container">
			<p class="kh-eyebrow">02 · <?php echo esc_html( $t( 'La sélection', 'The selection' ) ); ?></p>
			<h2 id="kh-creations-title" class="kh-title"><?php echo esc_html( $t( 'Créations BCDG', 'BCDG Creations' ) ); ?></h2>
			<p class="kh-lead"><?php echo esc_html( $t( "Chaque pièce est dessinée, signée et éditée en petite série. Voici quelques-unes d’entre elles.", 'Every piece is drawn, signed and released in a small series. Here are a few of them.' ) ); ?></p>
			<?php koinobori_child_editorial_products( $language ); ?>
			<div class="kh-home-kairo">
				<h3>Kaïro</h3>
				<p><?php echo esc_html( $t( 'Le Navire Sans Nom, un cycle de quinze koinobori. Chaque épisode est une pièce, chaque pièce est un chapitre.', 'The Nameless Ship, a cycle of fifteen koinobori. Each episode is a piece, each piece is a chapter.' ) ); ?></p>
				<?php $url = koinobori_child_editorial_world_url( 'kairo', $language ); if ( $url ) : ?>
				<a class="kh-text-link" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $t( 'Entrer dans le cycle Kaïro', 'Enter the Kaïro cycle' ) ); ?> <span aria-hidden="true">→</span></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<section class="kh-movement kh-home-house" aria-labelledby="kh-house-title">
		<div class="kh-container kh-home-split">
			<div><p class="kh-eyebrow">03 · Koinobori House</p><h2 id="kh-house-title" class="kh-title"><?php echo esc_html( $t( "L’Atelier", 'The House' ) ); ?></h2></div>
			<div><p class="kh-lead"><?php echo esc_html( $t( 'Koinobori House est une maison française. Un motif plutôt qu’un autre, une couleur, une taille qui va à une façade et pas à un couloir. Une pièce entre au catalogue quand elle tient debout toute seule.', 'Koinobori House is a French house. One pattern rather than another, a colour, a size that suits a facade and not a corridor. A piece joins the catalogue when it stands on its own.' ) ); ?></p>
			<?php $url = $page_url( 'atelier' ); if ( $url ) : ?><a class="kh-text-link" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $t( "Entrer dans L’Atelier", 'Step into The House' ) ); ?> <span aria-hidden="true">→</span></a><?php endif; ?></div>
		</div>
	</section>
	<?php
	$journal = new WP_Query( array( 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3, 'lang' => $language, 'no_found_rows' => true ) );
	if ( $journal->have_posts() ) : ?>
	<section class="kh-movement" aria-labelledby="kh-journal-title"><div class="kh-container">
		<h2 id="kh-journal-title" class="kh-title">Lifestyle &amp; Koi</h2>
		<div class="kh-home-journal">
		<?php while ( $journal->have_posts() ) : $journal->the_post(); ?>
			<article><a href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); } ?><h3><?php the_title(); ?></h3></a><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p></article>
		<?php endwhile; ?>
		</div>
	</div></section>
	<?php endif; wp_reset_postdata(); ?>
	<section class="kh-movement kh-home-living" aria-labelledby="kh-living-title"><div class="kh-container kh-home-split">
		<div><p class="kh-eyebrow"><?php echo esc_html( $t( 'Bientôt', 'Coming soon' ) ); ?></p><h2 id="kh-living-title" class="kh-title"><?php echo esc_html( $t( 'Arts de vivre', 'Art de Vivre' ) ); ?></h2></div>
		<div><p class="kh-lead"><?php echo esc_html( $t( 'Une sélection d’objets décoratifs japonais et asiatiques, choisis avec la même exigence que nos créations. Peu de pièces, organisées par usage et par atmosphère plutôt que par rayon.', 'A selection of Japanese and Asian decorative objects, chosen with the same demands we place on our own creations. Few pieces, organised by use and atmosphere rather than by department.' ) ); ?></p>
		<?php $url = $page_url( 'arts-de-vivre' ); if ( $url ) : ?><a class="kh-text-link" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $t( 'Découvrir l’intention', 'Discover the intention' ) ); ?> <span aria-hidden="true">→</span></a><?php endif; ?></div>
	</div></section>
	<section class="kh-movement kh-home-manifesto" aria-labelledby="kh-manifesto-title"><div class="kh-container">
		<p class="kh-eyebrow"><?php echo esc_html( $t( 'Le manifeste', 'The manifesto' ) ); ?></p>
		<h2 id="kh-manifesto-title"><?php echo esc_html( $t( 'Au moindre souffle.', 'At the faintest breeze.' ) ); ?></h2>
		<p class="kh-lead"><?php echo esc_html( $t( 'Un koinobori est une carpe de vent : suspendue, elle prend vie au moindre souffle. Entre tradition japonaise et création contemporaine, chaque pièce est pensée pour vivre dehors comme dedans, au vent du jardin ou dans la lumière d’un salon.', 'A koinobori is a wind carp: once hung, it comes alive with the faintest breeze. Between Japanese tradition and contemporary creation, each piece is designed to live outdoors and indoors alike, in the wind of a garden or the light of a living room.' ) ); ?></p>
	</div></section>
	<section class="kh-movement" aria-labelledby="kh-professionals-title"><div class="kh-container">
		<h2 id="kh-professionals-title" class="kh-title"><?php echo esc_html( $t( 'Un projet, un lieu.', 'A project, a place.' ) ); ?></h2>
		<div class="kh-home-professionals">
		<?php foreach ( array(
			array( 'entreprises', $t( 'Entreprises', 'Businesses' ), $t( 'Donner une présence singulière à vos espaces.', 'Bring a distinctive presence to your spaces.' ) ),
			array( 'collectivites', $t( 'Collectivités', 'Institutions' ), $t( 'Imaginer un projet culturel autour des carpes de vent.', 'Imagine a cultural project around wind carps.' ) ),
		) as $professional ) : $url = $page_url( $professional[0] ); ?>
			<article><h3><?php echo esc_html( $professional[1] ); ?></h3><p><?php echo esc_html( $professional[2] ); ?></p><?php if ( $url ) : ?><a class="kh-text-link" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $t( 'Parlons de votre projet', 'Tell us about your project' ) ); ?> <span aria-hidden="true">→</span></a><?php endif; ?></article>
		<?php endforeach; ?>
		</div>
	</div></section>
	<?php // Newsletter is rendered only once an actual, configured form is supplied.
	$newsletter = apply_filters( 'koinobori_child_newsletter_markup', '', $language );
	if ( is_string( $newsletter ) && '' !== trim( $newsletter ) ) : ?>
	<section class="kh-movement kh-newsletter" aria-labelledby="kh-newsletter-title"><div class="kh-container">
		<h2 id="kh-newsletter-title" class="kh-title"><?php echo esc_html( $t( 'Garder le fil', 'Stay in touch' ) ); ?></h2>
		<?php echo $newsletter; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted plugin form rendering. ?>
	</div></section>
	<?php endif; ?>
</main>
<?php get_footer(); ?>

<?php
/**
 * Editable home: the page content is made of native blocks, so every public text is
 * edited in the WordPress editor (cap Alain 2026-09-21). This file only provides:
 *  - two shortcodes for the parts that are queries, not texts;
 *  - the seed content used once by tools/staging/pc6/home-blocks-seed.php.
 * After seeding, the database is the source of truth: the texts below are never read again.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Four catalogue products in the current language. WooCommerce owns the markup. */
add_shortcode( 'kh_creations', function () {
	ob_start();
	koinobori_child_editorial_products( koinobori_child_header_text( 'fr', 'en' ) );
	return (string) ob_get_clean();
} );

/** Three latest articles in the current language; prints nothing while there is none. */
add_shortcode( 'kh_journal', function () {
	$journal = new WP_Query( array(
		'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 3,
		'lang' => koinobori_child_header_text( 'fr', 'en' ), 'no_found_rows' => true, 'ignore_sticky_posts' => true,
	) );
	if ( ! $journal->have_posts() ) { return ''; }
	$html = '<div class="kh-home-journal">';
	while ( $journal->have_posts() ) {
		$journal->the_post();
		$html .= '<article><a href="' . esc_url( get_permalink() ) . '">'
			. ( has_post_thumbnail() ? get_the_post_thumbnail( null, 'medium_large', array( 'loading' => 'lazy' ) ) : '' )
			. '<h3>' . esc_html( get_the_title() ) . '</h3></a><p>' . esc_html( wp_trim_words( get_the_excerpt(), 24 ) ) . '</p></article>';
	}
	wp_reset_postdata();
	return $html . '</div>';
} );

/** Serialise one block. serialize_block_attributes() escapes what a block comment cannot hold. */
function koinobori_child_block( $name, array $attrs, $html ) {
	$json = $attrs ? ' ' . serialize_block_attributes( $attrs ) : '';
	return "<!-- wp:{$name}{$json} -->\n{$html}\n<!-- /wp:{$name} -->\n";
}

function koinobori_child_block_p( $html, $class = '' ) {
	return koinobori_child_block( 'paragraph', $class ? array( 'className' => $class ) : array(),
		'<p' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . '>' . $html . '</p>' );
}

function koinobori_child_block_h( $html, $level = 2, $class = '' ) {
	$attrs = array();
	if ( 2 !== $level ) { $attrs['level'] = $level; }
	if ( $class ) { $attrs['className'] = $class; }
	return koinobori_child_block( 'heading', $attrs,
		'<h' . $level . ' class="' . esc_attr( trim( 'wp-block-heading ' . $class ) ) . '">' . $html . '</h' . $level . '>' );
}

function koinobori_child_block_group( $inner, $class, $tag = 'div', $anchor = '' ) {
	$attrs = array();
	if ( 'div' !== $tag ) { $attrs['tagName'] = $tag; }
	$attrs['className'] = $class;
	return koinobori_child_block( 'group', $attrs,
		'<' . $tag . ( $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '' ) . ' class="' . esc_attr( 'wp-block-group ' . $class ) . '">'
		. $inner . '</' . $tag . '>' );
}

function koinobori_child_block_link( $label, $url ) {
	return $url ? koinobori_child_block_p( '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>', 'kh-link' ) : '';
}

/** Seed content of the home page, in blocks, for one language. */
function koinobori_child_home_seed( $language ) {
	$language = 'en' === $language ? 'en' : 'fr';
	$t   = static function ( $fr, $en ) use ( $language ) { return esc_html( 'en' === $language ? $en : $fr ); };
	$url = static function ( $slug ) use ( $language ) { return koinobori_child_editorial_page_url( $slug, $language ); };

	$hero = koinobori_child_block_group(
		koinobori_child_block_group(
			koinobori_child_block_p( 'Koinobori House · ' . $t( 'Créations BCDG', 'BCDG Creations' ), 'kh-eyebrow' )
			. koinobori_child_block_h( $t( 'Des carpes de vent originales, signées BCDG', 'Original wind carps, signed by BCDG' ), 1 )
			. koinobori_child_block_p( $t( 'Koinobori contemporains à suspendre, édités en petites séries.', 'Contemporary koinobori to hang, released in small series.' ), 'kh-lead' )
			. koinobori_child_block( 'buttons', array(), '<div class="wp-block-buttons">'
				. koinobori_child_block( 'button', array(), '<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#kh-worlds">'
					. $t( 'Découvrir les cinq mondes', 'Discover the five worlds' ) . '</a></div>' ) . '</div>' ),
			'kh-container kh-home-hero__inner' )
		. koinobori_child_block( 'image', array( 'sizeSlug' => 'full', 'linkDestination' => 'none', 'className' => 'kh-home-hero__art' ),
			'<figure class="wp-block-image size-full kh-home-hero__art"><img src="' . esc_url( get_theme_file_uri( 'assets/images/hero-koinobori.webp' ) )
			. '" alt="' . esc_attr( 'en' === $language ? 'Koinobori in a Japanese landscape' : 'Koinobori dans un paysage japonais' ) . '"/></figure>' ),
		'kh-home-hero', 'section' );

	$worlds = '';
	foreach ( array(
		array( 'mer', $t( 'Mer', 'Sea' ), $t( 'L’appel du large', 'The call of the open sea' ) ),
		array( 'motifs', $t( 'Motifs', 'Patterns' ), $t( 'Symboles et écailles graphiques', 'Graphic symbols and scales' ) ),
		array( 'hanami', 'Hanami', $t( 'La contemplation des fleurs', 'Blossom viewing' ) ),
		array( 'kairo', 'Kaïro', $t( 'Un personnage, un cycle, quinze épisodes', 'One character, one cycle, fifteen episodes' ) ),
		array( 'territoires', $t( 'Territoires', 'Lands' ), $t( 'Régions et drapeaux revisités', 'Regions and flags revisited' ) ),
	) as $index => $world ) {
		$world_url = koinobori_child_editorial_world_url( $world[0], $language );
		$worlds   .= koinobori_child_block_group(
			koinobori_child_block_p( sprintf( '%02d', $index + 1 ), 'kh-eyebrow' )
			. koinobori_child_block_h( $world_url ? '<a href="' . esc_url( $world_url ) . '">' . $world[1] . '</a>' : $world[1], 3 )
			. koinobori_child_block_p( $world[2] ),
			'kh-home-world', 'article' );
	}
	$worlds = koinobori_child_block_group( koinobori_child_block_group(
		koinobori_child_block_p( '01 · ' . $t( 'Les univers', 'The worlds' ), 'kh-eyebrow' )
		. koinobori_child_block_h( $t( 'Cinq mondes', 'Five worlds' ), 2, 'kh-title' )
		. koinobori_child_block_group( $worlds, 'kh-home-worlds' ),
		'kh-container' ), 'kh-movement', 'section', 'kh-worlds' );

	$creations = koinobori_child_block_group( koinobori_child_block_group(
		koinobori_child_block_p( '02 · ' . $t( 'La sélection', 'The selection' ), 'kh-eyebrow' )
		. koinobori_child_block_h( $t( 'Créations BCDG', 'BCDG Creations' ), 2, 'kh-title' )
		. koinobori_child_block_p( $t( 'Chaque pièce est dessinée, signée et éditée en petite série. Voici quelques-unes d’entre elles.', 'Every piece is drawn, signed and released in a small series. Here are a few of them.' ), 'kh-lead' )
		. koinobori_child_block( 'shortcode', array(), '[kh_creations]' )
		. koinobori_child_block_group(
			koinobori_child_block_h( 'Kaïro', 3 )
			. koinobori_child_block_p( $t( 'Le Navire Sans Nom, un cycle de quinze koinobori. Chaque épisode est une pièce, chaque pièce est un chapitre.', 'The Nameless Ship, a cycle of fifteen koinobori. Each episode is a piece, each piece is a chapter.' ) )
			. koinobori_child_block_link( 'en' === $language ? 'Enter the Kaïro cycle' : 'Entrer dans le cycle Kaïro', koinobori_child_editorial_world_url( 'kairo', $language ) ),
			'kh-home-kairo' ),
		'kh-container' ), 'kh-movement kh-home-creations', 'section' );

	$house = koinobori_child_block_group( koinobori_child_block_group(
		koinobori_child_block_group(
			koinobori_child_block_p( '03 · Koinobori House', 'kh-eyebrow' )
			. koinobori_child_block_h( $t( 'L’Atelier', 'The House' ), 2, 'kh-title' ), 'kh-home-split__title' )
		. koinobori_child_block_group(
			koinobori_child_block_p( $t( 'Koinobori House est une maison française. Un motif plutôt qu’un autre, une couleur, une taille qui va à une façade et pas à un couloir. Une pièce entre au catalogue quand elle tient debout toute seule.', 'Koinobori House is a French house. One pattern rather than another, a colour, a size that suits a facade and not a corridor. A piece joins the catalogue when it stands on its own.' ), 'kh-lead' )
			. koinobori_child_block_link( 'en' === $language ? 'Step into The House' : 'Entrer dans L’Atelier', $url( 'atelier' ) ), 'kh-home-split__text' ),
		'kh-container kh-home-split' ), 'kh-movement kh-home-house', 'section' );

	$living = koinobori_child_block_group( koinobori_child_block_group(
		koinobori_child_block_group(
			koinobori_child_block_group(
				koinobori_child_block_p( '04 · ' . $t( 'Art de vivre', 'Art of living' ), 'kh-eyebrow' )
				. koinobori_child_block_h( 'Lifestyle &amp; Koi', 2, 'kh-title' ), 'kh-home-split__title' )
			. koinobori_child_block_group(
				koinobori_child_block_p( $t( 'Des récits, des gestes et des objets autour du koi et de l’art de vivre japonais. Pour installer, offrir et regarder vivre une carpe de vent.', 'Stories, gestures and objects around koi and the Japanese art of living. How to hang, give and watch a wind carp come alive.' ), 'kh-lead' )
				. koinobori_child_block_link( 'en' === $language ? 'Read Lifestyle & Koi' : 'Lire Lifestyle & Koi', $url( 'lifestyle' ) ), 'kh-home-split__text' ),
			'kh-home-split' )
		. koinobori_child_block( 'shortcode', array(), '[kh_journal]' ),
		'kh-container' ), 'kh-movement kh-home-living', 'section' );

	$manifesto = koinobori_child_block_group( koinobori_child_block_group(
		koinobori_child_block_p( $t( 'Le manifeste', 'The manifesto' ), 'kh-eyebrow' )
		. koinobori_child_block_h( $t( 'Au moindre souffle.', 'At the faintest breeze.' ) )
		. koinobori_child_block_p( $t( 'Un koinobori est une carpe de vent : suspendue, elle prend vie au moindre souffle. Entre tradition japonaise et création contemporaine, chaque pièce est pensée pour vivre dehors comme dedans, au vent du jardin ou dans la lumière d’un salon.', 'A koinobori is a wind carp: once hung, it comes alive with the faintest breeze. Between Japanese tradition and contemporary creation, each piece is designed to live outdoors and indoors alike, in the wind of a garden or the light of a living room.' ), 'kh-lead' ),
		'kh-container' ), 'kh-movement kh-home-manifesto', 'section' );

	$cards = '';
	foreach ( array(
		array( 'entreprises', $t( 'Entreprises', 'Businesses' ), $t( 'Donner une présence singulière à vos espaces.', 'Bring a distinctive presence to your spaces.' ) ),
		array( 'collectivites', $t( 'Collectivités', 'Institutions' ), $t( 'Imaginer un projet culturel autour des carpes de vent.', 'Imagine a cultural project around wind carps.' ) ),
	) as $card ) {
		$cards .= koinobori_child_block_group(
			koinobori_child_block_h( $card[1], 3 ) . koinobori_child_block_p( $card[2] )
			. koinobori_child_block_link( 'en' === $language ? 'Tell us about your project' : 'Parlons de votre projet', $url( $card[0] ) ),
			'kh-home-professional', 'article' );
	}
	$professionals = koinobori_child_block_group( koinobori_child_block_group(
		koinobori_child_block_h( $t( 'Un projet, un lieu.', 'A project, a place.' ), 2, 'kh-title' )
		. koinobori_child_block_group( $cards, 'kh-home-professionals' ),
		'kh-container' ), 'kh-movement', 'section' );

	return $hero . $worlds . $creations . $house . $living . $manifesto . $professionals;
}

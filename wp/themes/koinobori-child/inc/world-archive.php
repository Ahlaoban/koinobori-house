<?php
/**
 * The five worlds' category pages (product_cat archives): the collection banner at the top
 * (koinobori-house-images/collections, choice of Alain on 2026-09-25), on the washi paper.
 * The banners carry their own lettering: shown whole, never cropped. Every text stays editable in WordPress (cap Alain 2026-09-21):
 *  - title = category name, text = category description (Products > Categories);
 *  - Japanese name = the "Nom japonais" field of the French category (the English one inherits it);
 *  - banner = the category thumbnail when one is set, otherwise the theme's collection banner.
 * Only the five worlds are dressed; any other category keeps Kadence's archive header.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * French category slug => theme banner (assets/images/collection-<key>-{960,1920}.webp, 16:9).
 * The French slugs are the canonical ones (see editorial.php).
 */
function koinobori_child_worlds() {
	return array(
		'mer'         => 'collection-mer',
		'motifs'      => 'collection-motifs',
		'hanami'      => 'collection-hanami',
		'kairo'       => 'collection-kairo',
		'territoires' => 'collection-territoires',
	);
}

/** The French translation of a category (itself without Polylang), or null. */
function koinobori_child_world_fr_term( $term ) {
	if ( ! $term instanceof WP_Term || 'product_cat' !== $term->taxonomy ) {
		return null;
	}
	if ( function_exists( 'pll_get_term' ) ) {
		$fr = pll_get_term( $term->term_id, 'fr' );
		$fr = $fr ? get_term( (int) $fr, 'product_cat' ) : null;
		return $fr instanceof WP_Term ? $fr : null;
	}
	return $term;
}

/** World key of the current category archive, or '' outside the five worlds. */
function koinobori_child_world_key() {
	static $key = null;
	if ( null !== $key ) {
		return $key;
	}
	if ( ! did_action( 'wp' ) ) {
		return ''; // Main query not parsed yet: answer, but do not cache.
	}
	$key = '';
	if ( function_exists( 'is_product_category' ) && is_product_category() ) {
		$fr = koinobori_child_world_fr_term( get_queried_object() );
		if ( $fr && isset( koinobori_child_worlds()[ $fr->slug ] ) ) {
			$key = $fr->slug;
		}
	}
	return $key;
}

/** Kadence prints neither its hero title nor its in-content title: the world header replaces them. */
add_filter( 'kadence_post_layout', function ( $layout ) {
	if ( is_array( $layout ) && koinobori_child_world_key() ) {
		$layout['title'] = 'hide';
	}
	return $layout;
} );

add_filter( 'body_class', function ( $classes ) {
	$key = koinobori_child_world_key();
	if ( $key ) {
		$classes[] = 'kh-world-archive';
		$classes[] = 'kh-world-archive--' . $key;
	}
	return $classes;
} );

add_action( 'wp_enqueue_scripts', function () {
	if ( koinobori_child_world_key() ) {
		$file = '/assets/css/kh-world-archive.css';
		wp_enqueue_style( 'kh-world-archive', get_stylesheet_directory_uri() . $file,
			array( 'kh-charte-v3' ), filemtime( get_stylesheet_directory() . $file ) );
	}
}, 25 );

/** Japanese name of a world: the category's own field, else its French translation's. */
function koinobori_child_world_kanji( WP_Term $term ) {
	$kanji = (string) get_term_meta( $term->term_id, 'kh_kanji', true );
	if ( '' === $kanji ) {
		$fr    = koinobori_child_world_fr_term( $term );
		$kanji = $fr ? (string) get_term_meta( $fr->term_id, 'kh_kanji', true ) : '';
	}
	return $kanji;
}

/** Banner: category thumbnail (current, then French) if it is a real image, else the theme's. */
function koinobori_child_world_image( WP_Term $term, $key ) {
	$fr = koinobori_child_world_fr_term( $term );
	foreach ( array( $term, $fr ) as $candidate ) {
		$id = $candidate ? absint( get_term_meta( $candidate->term_id, 'thumbnail_id', true ) ) : 0;
		if ( $id && wp_attachment_is_image( $id ) ) {
			// The heading names the world: the banner is decorative, empty alt.
			return wp_get_attachment_image( $id, 'full', false, array( 'alt' => '', 'fetchpriority' => 'high', 'decoding' => 'async', 'sizes' => '(max-width: 1320px) 100vw, 1290px' ) );
		}
	}
	$base = 'assets/images/' . koinobori_child_worlds()[ $key ];
	return '<img src="' . esc_url( get_theme_file_uri( $base . '-1920.webp' ) ) . '" srcset="'
		. esc_url( get_theme_file_uri( $base . '-960.webp' ) ) . ' 960w, ' . esc_url( get_theme_file_uri( $base . '-1920.webp' ) ) . ' 1920w"'
		. ' sizes="(max-width: 1320px) 100vw, 1290px" width="1920" height="1080" alt="" fetchpriority="high" decoding="async">';
}

/** The world header, printed where Kadence prints its archive hero (before #primary). */
add_action( 'kadence_hero_header', function () {
	$key  = koinobori_child_world_key();
	$term = get_queried_object();
	if ( ! $key || ! $term instanceof WP_Term ) {
		return;
	}
	$kanji       = koinobori_child_world_kanji( $term );
	$description = trim( (string) $term->description );
	?>
	<section class="kh-world-hero kh-world-hero--<?php echo esc_attr( $key ); ?>">
		<div class="kh-world-hero__inner site-container">
			<figure class="kh-world-hero__art"><?php echo koinobori_child_world_image( $term, $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts. ?></figure>
			<div class="kh-world-hero__text">
				<h1 class="kh-world-hero__title"><?php echo esc_html( $term->name ); ?></h1>
				<?php if ( '' !== $kanji ) : ?>
					<p class="kh-world-hero__kanji" lang="ja"><?php echo esc_html( $kanji ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== $description ) : ?>
					<div class="kh-world-hero__lead"><?php echo wp_kses_post( function_exists( 'wc_format_content' ) ? wc_format_content( $description ) : wpautop( $description ) ); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}, 5 );

/* ---- Admin: "Nom japonais" field on product categories ---- */

add_action( 'init', function () {
	register_term_meta( 'product_cat', 'kh_kanji', array(
		'type'              => 'string',
		'single'            => true,
		'sanitize_callback' => 'sanitize_text_field',
		'show_in_rest'      => false,
	) );
} );

function koinobori_child_kanji_help() {
	return 'Affiché sous le titre de la page du monde (ex. 海). Laissez vide sur la catégorie anglaise : elle reprend celui de la catégorie française. / Shown under the world page title. Leave empty on the English category: it uses the French one.';
}

add_action( 'product_cat_add_form_fields', function () {
	wp_nonce_field( 'koinobori_child_kanji', 'koinobori_child_kanji_nonce' );
	?>
	<div class="form-field term-kh-kanji-wrap">
		<label for="kh_kanji">Nom japonais / Japanese name</label>
		<input name="kh_kanji" id="kh_kanji" type="text" value="" maxlength="20" lang="ja">
		<p><?php echo esc_html( koinobori_child_kanji_help() ); ?></p>
	</div>
	<?php
} );

add_action( 'product_cat_edit_form_fields', function ( $term ) {
	wp_nonce_field( 'koinobori_child_kanji', 'koinobori_child_kanji_nonce' );
	?>
	<tr class="form-field term-kh-kanji-wrap">
		<th scope="row"><label for="kh_kanji">Nom japonais / Japanese name</label></th>
		<td>
			<input name="kh_kanji" id="kh_kanji" type="text" maxlength="20" lang="ja" value="<?php echo esc_attr( (string) get_term_meta( $term->term_id, 'kh_kanji', true ) ); ?>">
			<p class="description"><?php echo esc_html( koinobori_child_kanji_help() ); ?></p>
		</td>
	</tr>
	<?php
} );

/** Absent field (quick edit, import, REST) never erases the stored value. */
function koinobori_child_save_kanji( $term_id ) {
	if ( ! isset( $_POST['kh_kanji'], $_POST['koinobori_child_kanji_nonce'] )
		|| ! is_string( $_POST['kh_kanji'] ) || ! is_string( $_POST['koinobori_child_kanji_nonce'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['koinobori_child_kanji_nonce'] ) ), 'koinobori_child_kanji' )
		|| ! current_user_can( 'edit_term', $term_id ) ) {
		return;
	}
	$kanji = sanitize_text_field( wp_unslash( $_POST['kh_kanji'] ) );
	if ( '' === $kanji ) {
		delete_term_meta( $term_id, 'kh_kanji' );
	} else {
		update_term_meta( $term_id, 'kh_kanji', mb_substr( $kanji, 0, 20 ) );
	}
}
add_action( 'created_product_cat', 'koinobori_child_save_kanji' );
add_action( 'edited_product_cat', 'koinobori_child_save_kanji' );

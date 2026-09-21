<?php
/**
 * Plugin Name: KH — Vidéo produit
 * Description: Une vidéo de la médiathèque par fiche WooCommerce, sans modifier les galeries photo.
 * Version: 1.0.1
 * Requires PHP: 8.1
 * Requires Plugins: woocommerce
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function khpm_text( $fr, $en ) {
	$lang = function_exists( 'pll_current_language' ) ? pll_current_language() : substr( get_locale(), 0, 2 );
	return 'en' === $lang ? $en : $fr;
}

/** Only Media Library attachments of supported types; never arbitrary URLs or HTML. */
function khpm_valid_attachment( $id, $kind ) {
	$id = absint( $id );
	if ( ! $id || 'attachment' !== get_post_type( $id ) || 'trash' === get_post_status( $id ) ) { return false; }
	$types = 'video' === $kind ? array( 'video/mp4', 'video/webm' ) : array( 'image/jpeg', 'image/png', 'image/webp', 'image/avif' );
	return in_array( get_post_mime_type( $id ), $types, true ) && (bool) wp_get_attachment_url( $id );
}

add_action( 'add_meta_boxes_product', function () {
	add_meta_box( 'khpm-video', 'Vidéo du koi / Koi video', 'khpm_editor', 'product', 'normal', 'default' );
} );

function khpm_editor( $post ) {
	wp_nonce_field( 'khpm_save', 'khpm_nonce' );
	echo '<p>Ajoutez un film du koi au vent. Les photos restent dans « Galerie produit ». / Add a film of the koi in the wind. Photos remain in the Product gallery.</p>';
	foreach ( array( 'video' => 'Vidéo MP4 ou WebM / MP4 or WebM video', 'poster' => 'Image d’aperçu (facultative) / Optional poster' ) as $kind => $label ) {
		$id = absint( get_post_meta( $post->ID, '_khpm_' . $kind . '_id', true ) );
		$name = $id && khpm_valid_attachment( $id, $kind ) ? get_the_title( $id ) : 'Aucun média / No media';
		echo '<div class="khpm-field" data-kind="' . esc_attr( $kind ) . '"><p><strong>' . esc_html( $label ) . '</strong></p>';
		echo '<input type="hidden" name="khpm_' . esc_attr( $kind ) . '_id" value="' . esc_attr( $id ) . '">';
		echo '<p class="khpm-name" role="status">' . esc_html( $name ) . '</p>';
		echo '<button type="button" class="button khpm-select">Choisir / Choose</button> <button type="button" class="button khpm-remove">Retirer / Remove</button></div>';
	}
	echo '<p>Sans aperçu choisi, la photo principale est utilisée. Aucun démarrage automatique. Retirer ne supprime pas le fichier de la médiathèque.</p>';
}

add_action( 'admin_enqueue_scripts', function () {
	$screen = get_current_screen();
	if ( ! $screen || 'product' !== $screen->post_type || 'post' !== $screen->base ) { return; }
	wp_enqueue_media();
	wp_enqueue_script( 'khpm-admin', plugins_url( 'assets/admin.js', __FILE__ ), array( 'media-views' ), '1.0.0', true );
} );

/** WC owns the save transaction; absent fields (quick edit/import) never erase media. */
add_action( 'woocommerce_admin_process_product_object', 'khpm_save' );
function khpm_save( $product ) {
	if ( ! isset( $_POST['khpm_nonce'] ) || ! is_string( $_POST['khpm_nonce'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['khpm_nonce'] ) ), 'khpm_save' )
		|| ! current_user_can( 'edit_post', $product->get_id() )
		|| ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) { return; }
	foreach ( array( 'video', 'poster' ) as $kind ) {
		$field = 'khpm_' . $kind . '_id';
		if ( ! isset( $_POST[ $field ] ) ) { continue; }
		$raw = wp_unslash( $_POST[ $field ] );
		if ( ! is_string( $raw ) || ! preg_match( '/^[0-9]+$/D', $raw ) ) {
			WC_Admin_Meta_Boxes::add_error( 'KH : sélection de média invalide ; valeur précédente conservée.' );
			continue;
		}
		$id = absint( $raw );
		if ( $id && ! khpm_valid_attachment( $id, $kind ) ) {
			WC_Admin_Meta_Boxes::add_error( 'KH : format de média non pris en charge ; valeur précédente conservée.' );
			continue;
		}
		$product->update_meta_data( '_khpm_' . $kind . '_id', $id );
	}
}

add_filter( 'pll_copy_post_metas', function ( $metas ) {
	return array_values( array_unique( array_merge( $metas, array( '_khpm_video_id', '_khpm_poster_id' ) ) ) );
} );

function khpm_video_data( $product ) {
	if ( ! $product ) { return false; }
	$id = absint( $product->get_meta( '_khpm_video_id' ) );
	if ( ! khpm_valid_attachment( $id, 'video' ) ) { return false; }
	$poster = absint( $product->get_meta( '_khpm_poster_id' ) );
	if ( ! khpm_valid_attachment( $poster, 'poster' ) ) { $poster = $product->get_image_id(); }
	return array( 'url' => wp_get_attachment_url( $id ), 'type' => get_post_mime_type( $id ),
		'poster' => $poster ? wp_get_attachment_image_url( $poster, 'large' ) : '' );
}

add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
	global $product;
	if ( khpm_video_data( $product ) ) {
		$tabs['khpm_video'] = array( 'title' => khpm_text( 'Le koi en mouvement', 'The koi in motion' ), 'priority' => 15, 'callback' => 'khpm_render' );
	}
	return $tabs;
} );

function khpm_render() {
	global $product;
	$data = khpm_video_data( $product );
	if ( ! $data ) { return; }
	$label = khpm_text( 'Le koi en mouvement', 'The koi in motion' );
	echo '<section class="khpm-film" aria-label="' . esc_attr( $label ) . '"><h2>' . esc_html( $label ) . '</h2>';
	echo '<video controls playsinline preload="none" aria-label="' . esc_attr( $product->get_name() . ', ' . $label ) . '"';
	if ( $data['poster'] ) { echo ' poster="' . esc_url( $data['poster'] ) . '"'; }
	echo '><source src="' . esc_url( $data['url'] ) . '" type="' . esc_attr( $data['type'] ) . '">';
	echo '<a href="' . esc_url( $data['url'] ) . '">' . esc_html( khpm_text( 'Ouvrir la vidéo', 'Open video' ) ) . '</a></video></section>';
}

add_action( 'wp_enqueue_scripts', function () {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) { return; }
	$product = wc_get_product( get_queried_object_id() );
	if ( khpm_video_data( $product ) ) {
		wp_enqueue_style( 'khpm-video', plugins_url( 'assets/video.css', __FILE__ ), array(), '1.0.0' );
	}
} );

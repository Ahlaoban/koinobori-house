<?php
/**
 * Koinobori House — thème enfant Kadence.
 *
 * Rôle minimal au Lot 1 : charger la feuille de style enfant après le parent.
 * Le code i18n racine (302 + cookie) vit dans un mu-plugin (KH-107), pas ici.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'koinobori-child',
			get_stylesheet_uri(),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	},
	20
);

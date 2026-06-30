<?php
/**
 * Koinobori House — thème enfant Kadence.
 *
 * Lot 6 / PR1 — Fondations Design System KH-000 v1.2 (« Ma »).
 * Fondations versionnées en CODE (assets/css/kh-foundations.css + assets/fonts/),
 * pas dans le Customizer. Référence : docs/charte-graphique/KH-000…md.
 *
 * Le code i18n racine (302 + cookie) vit dans un mu-plugin (KH-107), pas ici.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Styles front : style.css (en-tête thème) puis kh-foundations.css (tokens, fonts, base).
 * Versions = filemtime → cache-busting propre à chaque déploiement.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		$dir = get_stylesheet_directory();
		$uri = get_stylesheet_directory_uri();

		wp_enqueue_style(
			'koinobori-child',
			get_stylesheet_uri(),
			array(),
			file_exists( "$dir/style.css" ) ? filemtime( "$dir/style.css" ) : '1.1.0'
		);

		$foundations = "$dir/assets/css/kh-foundations.css";
		wp_enqueue_style(
			'kh-foundations',
			"$uri/assets/css/kh-foundations.css",
			array( 'koinobori-child' ),
			file_exists( $foundations ) ? filemtime( $foundations ) : '1.1.0'
		);
	},
	20
);

/**
 * Preload des 3 polices critiques above-the-fold (héros, corps, UI).
 * crossorigin obligatoire pour les fonts (fetch CORS-anonyme), même en same-origin.
 */
add_action(
	'wp_head',
	function () {
		$uri    = get_stylesheet_directory_uri() . '/assets/fonts/';
		$critic = array(
			'cormorant-garamond-400-italic-latin.woff2',
			'lora-400-normal-latin.woff2',
			'dm-sans-400-normal-latin.woff2',
		);
		foreach ( $critic as $f ) {
			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( $uri . $f )
			);
		}
	},
	1
);

/**
 * Palette éditeur = couleurs KH-000 uniquement ; bloque les couleurs/dégradés libres.
 * Le rendu front reste piloté par les variables --kh-* (kh-foundations.css).
 */
add_action(
	'after_setup_theme',
	function () {
		add_theme_support(
			'editor-color-palette',
			array(
				array( 'name' => __( 'Washi', 'koinobori-child' ),     'slug' => 'kh-washi',     'color' => '#F7F3EC' ),
				array( 'name' => __( 'Sumi', 'koinobori-child' ),      'slug' => 'kh-sumi',      'color' => '#1A1410' ),
				array( 'name' => __( 'Vermillon', 'koinobori-child' ), 'slug' => 'kh-vermillon', 'color' => '#C8311A' ),
				array( 'name' => __( 'Or', 'koinobori-child' ),        'slug' => 'kh-or',        'color' => '#C9A96E' ),
				array( 'name' => __( 'Brume', 'koinobori-child' ),     'slug' => 'kh-brume',     'color' => '#E8E4DC' ),
				array( 'name' => __( 'Indigo', 'koinobori-child' ),    'slug' => 'kh-indigo',    'color' => '#1B2B5E' ),
				array( 'name' => __( 'Sakura', 'koinobori-child' ),    'slug' => 'kh-sakura',    'color' => '#F2C4CE' ),
				array( 'name' => __( 'Forêt', 'koinobori-child' ),     'slug' => 'kh-foret',     'color' => '#3D5A3E' ),
			)
		);
		add_theme_support( 'disable-custom-colors' );
		add_theme_support( 'disable-custom-gradients' );

		// Éditeur Gutenberg = mêmes fondations que le front.
		add_editor_style( 'assets/css/kh-foundations.css' );
	}
);

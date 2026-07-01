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
 * Palette éditeur = couleurs KH-000 uniquement.
 * La palette + le blocage des couleurs/dégradés libres sont déclarés dans theme.json
 * (settings.color : palette KH, custom=false, defaultPalette=false) — theme.json du
 * thème enfant a priorité sur celui de Kadence, ce qui neutralise sa palette.
 * Ici on ne garde que le chargement des fondations dans l'éditeur Gutenberg.
 */
add_action(
	'after_setup_theme',
	function () {
		add_editor_style( 'assets/css/kh-foundations.css' );
	}
);

/**
 * Force la palette de l'éditeur de blocs sur les 8 couleurs KH-000, APRÈS Kadence.
 * theme.json ne suffit pas : Kadence réinjecte sa propre palette. Ce filtre
 * (priorité 100) écrase le résultat final pour les blocs cœur.
 */
add_filter(
	'block_editor_settings_all',
	function ( $settings ) {
		$settings['colors'] = array(
			array( 'slug' => 'kh-washi',     'name' => 'Washi',     'color' => '#F7F3EC' ),
			array( 'slug' => 'kh-sumi',      'name' => 'Sumi',      'color' => '#1A1410' ),
			array( 'slug' => 'kh-vermillon', 'name' => 'Vermillon', 'color' => '#C8311A' ),
			array( 'slug' => 'kh-or',        'name' => 'Or',        'color' => '#C9A96E' ),
			array( 'slug' => 'kh-brume',     'name' => 'Brume',     'color' => '#E8E4DC' ),
			array( 'slug' => 'kh-indigo',    'name' => 'Indigo',    'color' => '#1B2B5E' ),
			array( 'slug' => 'kh-sakura',    'name' => 'Sakura',    'color' => '#F2C4CE' ),
			array( 'slug' => 'kh-foret',     'name' => 'Forêt',     'color' => '#3D5A3E' ),
		);
		$settings['disableCustomColors']    = true;
		$settings['gradients']              = array();
		$settings['disableCustomGradients'] = true;
		return $settings;
	},
	100
);

/**
 * Palette KH-000 dans la couche theme.json elle-même, APRÈS Kadence.
 * C'est le mécanisme que l'éditeur moderne lit réellement : Kadence injecte sa
 * palette via wp_theme_json_data_theme ; on repasse derrière (priorité 20) pour
 * imposer les 8 couleurs KH et couper défauts/dégradés/couleurs libres.
 */
add_filter(
	'wp_theme_json_data_theme',
	function ( $theme_json ) {
		return $theme_json->update_with(
			array(
				'version'  => 3,
				'settings' => array(
					'color' => array(
						'defaultPalette'   => false,
						'defaultGradients' => false,
						'custom'           => false,
						'customGradient'   => false,
						'gradients'        => array(),
						'palette'          => array(
							array( 'slug' => 'kh-washi',     'name' => 'Washi',     'color' => '#F7F3EC' ),
							array( 'slug' => 'kh-sumi',      'name' => 'Sumi',      'color' => '#1A1410' ),
							array( 'slug' => 'kh-vermillon', 'name' => 'Vermillon', 'color' => '#C8311A' ),
							array( 'slug' => 'kh-or',        'name' => 'Or',        'color' => '#C9A96E' ),
							array( 'slug' => 'kh-brume',     'name' => 'Brume',     'color' => '#E8E4DC' ),
							array( 'slug' => 'kh-indigo',    'name' => 'Indigo',    'color' => '#1B2B5E' ),
							array( 'slug' => 'kh-sakura',    'name' => 'Sakura',    'color' => '#F2C4CE' ),
							array( 'slug' => 'kh-foret',     'name' => 'Forêt',     'color' => '#3D5A3E' ),
						),
					),
				),
			)
		);
	},
	20
);

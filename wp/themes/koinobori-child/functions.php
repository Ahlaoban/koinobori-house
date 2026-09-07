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

		// Charte v3.0 « Ma, L'Intervalle enchanté » — référence Manus du 2026-07-27.
		// Chargée APRÈS les fondations : son :root redéfinit --kh-washi, --kh-or et
		// --kh-indigo aux valeurs v2.0 (arbitrage C3), et le mapping Kadence des
		// fondations hérite automatiquement des nouvelles valeurs via var().
		$charte = "$dir/assets/css/kh-charte-v3.css";
		wp_enqueue_style(
			'kh-charte-v3',
			"$uri/assets/css/kh-charte-v3.css",
			array( 'kh-foundations' ),
			file_exists( $charte ) ? filemtime( $charte ) : '3.0.0'
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
		add_editor_style( 'assets/css/kh-charte-v3.css' );
	}
);

/**
 * Palette KH — source unique : settings.color.palette de theme.json.
 * Les deux filtres ci-dessous la relisent au lieu de la dupliquer : la migration
 * v2.0 (arbitrage C3, 2026-07-27) ne se fait qu'à un seul endroit.
 *
 * @return array<int, array{slug:string,name:string,color:string}>
 */
function kh_palette() {
	static $palette = null;
	if ( null === $palette ) {
		$palette = array();
		$file    = get_stylesheet_directory() . '/theme.json';
		if ( is_readable( $file ) ) {
			$json = json_decode( (string) file_get_contents( $file ), true );
			if ( isset( $json['settings']['color']['palette'] ) && is_array( $json['settings']['color']['palette'] ) ) {
				$palette = $json['settings']['color']['palette'];
			}
		}
	}
	return $palette;
}

/**
 * Force la palette de l'éditeur de blocs sur les 8 couleurs KH (theme.json), APRÈS Kadence.
 * theme.json seul ne suffit pas : Kadence réinjecte sa propre palette. Ce filtre
 * (priorité 100) écrase le résultat final pour les blocs cœur.
 */
add_filter(
	'block_editor_settings_all',
	function ( $settings ) {
		$settings['colors'] = kh_palette();
		$settings['disableCustomColors']    = true;
		$settings['gradients']              = array();
		$settings['disableCustomGradients'] = true;
		return $settings;
	},
	100
);

/**
 * Palette KH dans la couche theme.json elle-même, APRÈS Kadence.
 * C'est le mécanisme que l'éditeur moderne lit réellement : Kadence injecte sa
 * palette via wp_theme_json_data_theme ; on repasse derrière (priorité 20) pour
 * imposer les 8 couleurs KH (relues depuis theme.json) et couper défauts/dégradés/couleurs libres.
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
						'palette'          => kh_palette(),
					),
				),
			)
		);
	},
	20
);

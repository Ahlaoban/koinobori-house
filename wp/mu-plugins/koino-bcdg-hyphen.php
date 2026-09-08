<?php
/**
 * Plugin Name: Koinobori — trait d'union de la signature BCDG
 * Description: Rétablit le trait d'union simple dans « - by BCDG », que wptexturize convertit en tiret demi-cadratin. Ne touche à aucun autre tiret du site.
 * Version:     1.0.0
 * Author:      Koinobori House
 *
 * Doctrine (CLAUDE.md § Doctrine éditoriale impérative, décision Alain 2026-06-15) :
 * la signature dans le nom du modèle s'écrit « - by BCDG », trait d'union simple.
 * Jamais de tiret cadratin ni demi-cadratin dans les fiches produits, FR comme EN.
 *
 * Le problème : wptexturize() convertit « espace tiret espace » en tiret
 * demi-cadratin (&#8211;), documenté sur developer.wordpress.org/reference/
 * functions/wptexturize/. Les 34 fiches produits saisies « Sakura Rouge
 * Koinobori - by BCDG » s'affichent donc « … – by BCDG ».
 *
 * Pourquoi ne pas désactiver wptexturize : le filtre officiel `run_wptexturize`
 * est global. Le couper ferait perdre les apostrophes typographiques et les
 * guillemets français sur tout le site, pour corriger une seule chaîne. On
 * réécrit donc l'unique occurrence fautive, après coup.
 *
 * Pourquoi un mu-plugin plutôt que functions.php : le thème enfant est en cours
 * de réécriture par Manus (PR #12, 65 lignes de functions.php modifiées).
 * Isoler ici évite un conflit et garde le correctif indépendant du thème.
 *
 * wptexturize est accroché à `the_title` en priorité 10 (default-filters.php),
 * on passe donc en 20.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remet un trait d'union simple devant « by BCDG ».
 *
 * Couvre les trois formes que peut prendre le demi-cadratin selon l'étape du
 * rendu : entité numérique, entité nommée, caractère UTF-8 brut. L'espace
 * séparateur est accepté sous forme normale ou insécable, au cas où une couche
 * de typographie française l'aurait converti.
 *
 * @param string $title Titre déjà passé par wptexturize.
 * @return string
 */
function koino_bcdg_restore_hyphen( $title ) {
	if ( ! is_string( $title ) || '' === $title ) {
		return $title;
	}

	// Sortie rapide : rien à faire si la signature n'est pas là.
	if ( false === stripos( $title, 'by BCDG' ) ) {
		return $title;
	}

	return preg_replace(
		'/(?:&#8211;|&#x2013;|&ndash;|\x{2013})(\s|&nbsp;|&#160;|\x{00A0})*by BCDG/iu',
		'- by BCDG',
		$title
	);
}

add_filter( 'the_title', 'koino_bcdg_restore_hyphen', 20 );
add_filter( 'single_post_title', 'koino_bcdg_restore_hyphen', 20 );

/**
 * Même correction dans la balise <title> du document.
 */
add_filter(
	'document_title_parts',
	function ( $parts ) {
		if ( isset( $parts['title'] ) ) {
			$parts['title'] = koino_bcdg_restore_hyphen( $parts['title'] );
		}

		return $parts;
	},
	20
);

<?php
/**
 * Plugin Name: Koinobori — diagnostic page d'accueil (TEMPORAIRE)
 * Description: Affiche, pour un administrateur connecté qui ajoute ?koino_diag=1 à une URL du front, l'état réel des options de page d'accueil avant et après filtres. À retirer une fois l'anomalie résolue.
 * Version:     1.0.0
 * Author:      Koinobori House
 *
 * ---------------------------------------------------------------------------
 * POURQUOI CE FICHIER EXISTE
 *
 * Sur staging, /fr/ et /en/ rendent l'index de blog au lieu de la page
 * d'accueil, alors que ?page_id=318 rend correctement la page avec tout son
 * contenu.
 *
 * Ce qui est établi, et qu'il est inutile de re-tester :
 *
 *  - Les réglages sont justes. `show_on_front = page`, `page_on_front = 318`,
 *    vérifiés côté REST et côté formulaire.
 *  - Les données sont justes. La page 318 est bien rattachée au français
 *    (filtre `lang=fr` de la liste des pages), la 319 à l'anglais, et la paire
 *    est liée dans Polylang.
 *  - Le français est bien la langue par défaut, la langue est définie par le
 *    répertoire, et le masquage pour la langue par défaut est désactivé.
 *  - Le mu-plugin KH-107 est hors de cause : il n'enregistre aucun hook et
 *    sort dès que le chemin demandé diffère de la racine.
 *  - Aucun cache n'est en cause : cache de pages purgé plusieurs fois, cache
 *    des langues Polylang nettoyé par trois chemins différents (aller-retour
 *    articles/page, changement réel de valeur 318 vers 282 puis retour,
 *    enregistrement du formulaire « Modifications des URL »), et il n'existe
 *    aucun drop-in de cache objet persistant sur ce site.
 *  - Le filtre de langue de l'administration, qui était sur English, n'est pas
 *    en cause : remis sur « toutes les langues » puis réenregistrement, sans
 *    effet.
 *  - Le module « Détecter la langue du navigateur » de Polylang est désactivé.
 *
 * Ce qui n'a pas pu être testé depuis l'interface : la désactivation de
 * Polylang for WooCommerce, qui n'expose aucun lien de désactivation.
 *
 * ---------------------------------------------------------------------------
 * CE QUE LA PREUVE DIT
 *
 * `page_for_posts` a été positionné sur 284 : la page /fr/lifestyle/ a continué
 * de rendre `page page-id-284` au lieu de l'index de blog, et la valeur n'a
 * même pas persisté. Les deux options de page statique sont donc ignorées
 * ensemble, ce qui n'arrive que si `show_on_front` ne vaut pas `page` au
 * moment où WP_Query décide.
 *
 * Autrement dit : quelque chose filtre `option_show_on_front` sur le front.
 * Polylang le fait dans `PLL_Static_Pages`, en renvoyant `posts` lorsqu'il
 * n'arrive pas à résoudre la page d'accueil pour la langue courante. Reste à
 * savoir pourquoi il n'y arrive pas, ce qui demande de voir l'état PHP.
 *
 * ---------------------------------------------------------------------------
 * MODE D'EMPLOI
 *
 * 1. Déposer ce fichier dans wp-content/mu-plugins/ (il s'active seul).
 * 2. Connecté en administrateur, ouvrir https://staging.koinoborihouse.com/fr/?koino_diag=1
 * 3. Copier le bloc affiché en haut de page et le coller dans la session.
 * 4. SUPPRIMER CE FICHIER une fois le diagnostic posé.
 *
 * Le diagnostic ne s'affiche que pour un utilisateur pouvant gérer les options,
 * et uniquement si le paramètre est présent. Il ne modifie rien.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Liste les callbacks accrochés à un filtre, sous forme lisible.
 *
 * @param string $hook Nom du filtre.
 * @return string
 */
function koino_diag_hooks( $hook ) {
	global $wp_filter;

	if ( empty( $wp_filter[ $hook ] ) ) {
		return 'aucun';
	}

	$out = array();
	foreach ( $wp_filter[ $hook ]->callbacks as $priority => $callbacks ) {
		foreach ( $callbacks as $cb ) {
			$f = $cb['function'];
			if ( is_string( $f ) ) {
				$name = $f;
			} elseif ( is_array( $f ) ) {
				$name = ( is_object( $f[0] ) ? get_class( $f[0] ) : (string) $f[0] ) . '::' . $f[1];
			} else {
				$name = 'closure';
			}
			$out[] = $priority . ' → ' . $name;
		}
	}

	return implode( ' | ', $out );
}

add_action(
	'wp_footer',
	function () {
		if ( ! isset( $_GET['koino_diag'] ) || ! current_user_can( 'manage_options' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		global $wpdb, $wp_query;

		// Valeurs brutes en base, sans passer par get_option() donc sans filtres.
		$brut = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			"SELECT option_name, option_value FROM {$wpdb->options}
			 WHERE option_name IN ('show_on_front','page_on_front','page_for_posts')",
			OBJECT_K
		);

		$lignes = array(
			'--- OPTIONS ---',
			'brut base   show_on_front  : ' . ( isset( $brut['show_on_front'] ) ? $brut['show_on_front']->option_value : 'absent' ),
			'brut base   page_on_front  : ' . ( isset( $brut['page_on_front'] ) ? $brut['page_on_front']->option_value : 'absent' ),
			'brut base   page_for_posts : ' . ( isset( $brut['page_for_posts'] ) ? $brut['page_for_posts']->option_value : 'absent' ),
			'get_option  show_on_front  : ' . get_option( 'show_on_front' ),
			'get_option  page_on_front  : ' . get_option( 'page_on_front' ),
			'get_option  page_for_posts : ' . get_option( 'page_for_posts' ),
			'',
			'--- FILTRES ACCROCHES ---',
			'option_show_on_front  : ' . koino_diag_hooks( 'option_show_on_front' ),
			'option_page_on_front  : ' . koino_diag_hooks( 'option_page_on_front' ),
			'option_page_for_posts : ' . koino_diag_hooks( 'option_page_for_posts' ),
			'',
			'--- REQUETE ---',
			'query_vars : ' . wp_json_encode( array_filter( $wp_query->query ) ),
			'is_home ' . ( is_home() ? 'oui' : 'non' ) . ' | is_front_page ' . ( is_front_page() ? 'oui' : 'non' ) . ' | is_page ' . ( is_page() ? 'oui' : 'non' ),
			'',
			'--- POLYLANG ---',
		);

		if ( function_exists( 'pll_current_language' ) ) {
			$lignes[] = 'langue courante  : ' . pll_current_language();
			$lignes[] = 'langue defaut    : ' . ( function_exists( 'pll_default_language' ) ? pll_default_language() : '?' );
			$lignes[] = 'traduction de 318 en courante : ' . ( function_exists( 'pll_get_post' ) ? var_export( pll_get_post( 318, pll_current_language() ), true ) : '?' );
			$lignes[] = 'traduction de 318 en fr       : ' . ( function_exists( 'pll_get_post' ) ? var_export( pll_get_post( 318, 'fr' ), true ) : '?' );
			$lignes[] = 'traduction de 318 en en       : ' . ( function_exists( 'pll_get_post' ) ? var_export( pll_get_post( 318, 'en' ), true ) : '?' );
			$lignes[] = 'langue de 318                 : ' . ( function_exists( 'pll_get_post_language' ) ? var_export( pll_get_post_language( 318 ), true ) : '?' );

			// Ce que Polylang a resolu comme page d'accueil pour chaque langue.
			if ( function_exists( 'PLL' ) && isset( PLL()->static_pages ) ) {
				$sp                = PLL()->static_pages;
				$lignes[]          = 'PLL static_pages->page_on_front  : ' . var_export( isset( $sp->page_on_front ) ? $sp->page_on_front : 'propriete absente', true );
				$lignes[]          = 'PLL static_pages->page_for_posts : ' . var_export( isset( $sp->page_for_posts ) ? $sp->page_for_posts : 'propriete absente', true );
				$lignes[]          = 'classe : ' . get_class( $sp );
			} else {
				$lignes[] = 'PLL()->static_pages : inaccessible';
			}

			if ( function_exists( 'PLL' ) && isset( PLL()->model ) ) {
				foreach ( PLL()->model->get_languages_list() as $l ) {
					$lignes[] = 'langue ' . $l->slug . ' → page_on_front=' . var_export( isset( $l->page_on_front ) ? $l->page_on_front : 'absent', true )
						. ' page_for_posts=' . var_export( isset( $l->page_for_posts ) ? $l->page_for_posts : 'absent', true );
				}
			}
		} else {
			$lignes[] = 'Polylang inactif ou non charge';
		}

		printf(
			'<pre style="position:relative;z-index:99999;background:#111;color:#0f0;padding:16px;font:12px/1.5 monospace;white-space:pre-wrap;">%s</pre>',
			esc_html( implode( "\n", $lignes ) )
		);
	},
	9999
);

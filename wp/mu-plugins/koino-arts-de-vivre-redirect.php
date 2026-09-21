<?php
/**
 * Plugin Name: Koinobori — redirection Arts de vivre vers Lifestyle
 * Description: Redirige en 301 les anciennes pages « Arts de vivre » (FR) et « Art de Vivre » (EN) vers la page Lifestyle de la même langue. Aucune page n'est supprimée ni modifiée.
 * Version:     1.0.0
 * Author:      Koinobori House
 *
 * Arbitrage Alain du 2026-09-16 (CLAUDE.md, en tête de fichier) : « Arts de vivre »
 * n'est plus une rubrique autonome, Lifestyle est la destination canonique
 * (`/fr/lifestyle/`, `/en/lifestyle-koi/`). Les anciennes pages (ID 282 et 291 sur
 * le staging) sont auditées, leur contenu utile transféré, puis redirigées.
 *
 * À déposer seulement APRÈS l'audit et le transfert du contenu : tant que ce fichier
 * est absent, les deux pages restent consultables. Le retirer annule la redirection.
 *
 * Règles i18n respectées : jamais de redirection entre `/fr/` et `/en/` (la cible est
 * la traduction Lifestyle de la langue de la page demandée), jamais de redirection
 * de la racine. Les pages sont reconnues par leur slug, pas par leur ID, pour que le
 * fichier vaille sur le clone, le staging et la production.
 *
 * Pourquoi un mu-plugin : SEOPress Free n'a pas de gestionnaire de redirections et
 * l'édition de `.htaccess` est réservée à Alain.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'template_redirect', function () {
	if ( ! is_page( array( 'arts-de-vivre', 'art-de-vivre' ) ) ) {
		return;
	}
	$source   = get_queried_object_id();
	$language = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $source ) : '';
	$language = 'en' === $language ? 'en' : 'fr';

	// The French slug is the reference; Polylang gives the page of the requested language.
	$lifestyle = get_page_by_path( 'lifestyle' );
	$target    = $lifestyle ? $lifestyle->ID : 0;
	if ( $target && function_exists( 'pll_get_post' ) ) {
		$target = (int) pll_get_post( $target, $language );
	}
	// Never invent a destination: no published Lifestyle page in that language, no redirect.
	if ( ! $target || $target === $source || 'publish' !== get_post_status( $target ) ) {
		return;
	}
	wp_safe_redirect( get_permalink( $target ), 301, 'koino-arts-de-vivre-redirect' );
	exit;
} );

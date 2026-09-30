<?php
/**
 * PC7: first texts of the five worlds' category pages (theme 1.3.14, inc/world-archive.php).
 *  - French categories: "Nom japonais" (term meta kh_kanji), as on the home cards;
 *  - French and English categories: description = the home card's line (validated by Alain on 25/09).
 * After seeding, WordPress is the source of truth (Products > Categories): a value already present is
 * never overwritten, so an edit by Alain or Catherine is kept.
 *
 *   wp eval-file tools/staging/pc7/world-terms-seed.php                        # dry-run
 *   KH_APPLY=1 KH_CONFIRM=world-terms-seed wp eval-file ...                    # apply
 *
 * Restore: tools/staging/pc4/restore.php with the backup written before any write (descriptions);
 * the kanji were absent before: `wp term meta delete <id> kh_kanji` for the ids listed in the backup.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once dirname( __DIR__ ) . '/pc4/lib.php';
global $wpdb;

$ctx = kh_pc4_boot( 'world-terms-seed', 'world-terms-seed' );
if ( ! function_exists( 'pll_get_term' ) || ! function_exists( 'pll_get_term_language' ) ) {
	WP_CLI::error( 'Polylang not loaded (run without --skip-plugins).' );
}
kh_pc4_require_innodb( $wpdb->term_taxonomy );
kh_pc4_require_innodb( $wpdb->termmeta );

// French slug => Japanese name, French line, English line (home cards, pages 318/319).
$worlds = array(
	'mer'         => array( '海', 'L’appel du large', 'The call of the open sea' ),
	'motifs'      => array( '文様', 'Symboles et écailles graphiques', 'Graphic symbols and scales' ),
	'hanami'      => array( '花見', 'La contemplation des fleurs', 'Blossom viewing' ),
	'kairo'       => array( '回廊', 'Un personnage, un cycle, quinze épisodes', 'One character, one cycle, fifteen episodes' ),
	'territoires' => array( '地域', 'Régions et drapeaux revisités', 'Regions and flags revisited' ),
);

$plan = array(); // Each item: term_taxonomy row and/or kanji meta to write.
foreach ( $worlds as $slug => $w ) {
	$fr = get_terms( array( 'taxonomy' => 'product_cat', 'slug' => $slug, 'lang' => 'fr', 'hide_empty' => false, 'number' => 1 ) );
	if ( is_wp_error( $fr ) || ! $fr ) {
		WP_CLI::error( 'French category missing: ' . $slug );
	}
	$fr = $fr[0];
	if ( 'fr' !== pll_get_term_language( $fr->term_id ) ) {
		WP_CLI::error( 'Category ' . $slug . ' is not French.' );
	}
	$en_id = (int) pll_get_term( $fr->term_id, 'en' );
	$en    = $en_id ? get_term( $en_id, 'product_cat' ) : null;
	if ( ! $en instanceof WP_Term ) {
		WP_CLI::error( 'English translation missing for ' . $slug );
	}
	foreach ( array( array( $fr, $w[1] ), array( $en, $w[2] ) ) as $pair ) {
		list( $term, $text ) = $pair;
		if ( '' === trim( $term->description ) ) {
			$plan[] = array( 'kind' => 'description', 'term' => $term, 'value' => $text );
		} else {
			WP_CLI::log( sprintf( '%s (%d): description already set, left untouched', $term->slug, $term->term_id ) );
		}
	}
	if ( '' === (string) get_term_meta( $fr->term_id, 'kh_kanji', true ) ) {
		$plan[] = array( 'kind' => 'kanji', 'term' => $fr, 'value' => $w[0] );
	} else {
		WP_CLI::log( sprintf( '%s (%d): kanji already set, left untouched', $fr->slug, $fr->term_id ) );
	}
}
foreach ( $plan as $item ) {
	WP_CLI::log( sprintf( '%s (%d, %s): %s -> %s', $item['term']->slug, $item['term']->term_id,
		pll_get_term_language( $item['term']->term_id ), $item['kind'], $item['value'] ) );
	if ( preg_match( '/(\bchin[eo]|\bchina|fabricat|atelier partenaire|partner workshop|\x{2014})/iu', $item['value'], $m ) ) {
		WP_CLI::error( 'Doctrine check failed: ' . $m[1] );
	}
}
if ( ! $plan ) {
	WP_CLI::success( 'Nothing to change.' );
	return;
}
if ( ! $ctx['apply'] ) {
	WP_CLI::success( 'Dry run: ' . count( $plan ) . ' value(s) would be written. No write.' );
	return;
}

$restore = array();
$added   = array();
foreach ( $plan as $item ) {
	if ( 'description' === $item['kind'] ) {
		$restore[] = array( 'kind' => 'row', 'table' => $wpdb->term_taxonomy,
			'where' => array( 'term_taxonomy_id' => $item['term']->term_taxonomy_id ), 'data' => array( 'description' => $item['term']->description ) );
	} else {
		$added[] = $item['term']->term_id; // Was absent: undo = wp term meta delete <id> kh_kanji.
	}
}
kh_pc4_backup( $ctx, 'pc7-world-terms', $restore, array( 'kh_kanji_added_on_terms' => $added ) );

kh_pc4_transaction( static function () use ( $plan ) {
	foreach ( $plan as $item ) {
		$id = $item['term']->term_id;
		if ( 'description' === $item['kind'] ) {
			$r = wp_update_term( $id, 'product_cat', array( 'description' => $item['value'] ) );
			if ( is_wp_error( $r ) ) {
				throw new RuntimeException( 'Update failed for ' . $id . ': ' . $r->get_error_message() );
			}
			clean_term_cache( $id, 'product_cat' );
			$ok = get_term( $id, 'product_cat' )->description === $item['value'];
		} else {
			update_term_meta( $id, 'kh_kanji', $item['value'] );
			$ok = get_term_meta( $id, 'kh_kanji', true ) === $item['value'];
		}
		WP_CLI::log( sprintf( '%d %s: stored, identical=%s', $id, $item['kind'], var_export( $ok, true ) ) );
		if ( ! $ok ) {
			throw new RuntimeException( 'Stored value differs for ' . $id . ' ' . $item['kind'] );
		}
	}
} );
WP_CLI::success( 'World categories seeded.' );

<?php
/**
 * Plugin Name: Koinobori — trait d'union de la signature BCDG
 * Description: Rétablit le trait d'union simple dans « - by BCDG », que wptexturize convertit en tiret demi-cadratin ou cadratin. Ne touche à aucun autre tiret du site.
 * Version:     1.1.0
 * Author:      Koinobori House
 *
 * Doctrine (CLAUDE.md § Doctrine éditoriale impérative, décision Alain 2026-06-15) :
 * la signature s'écrit « - by BCDG », trait d'union simple, dans le nom du modèle
 * et dans le bloc 2 de la description longue. Jamais de tiret cadratin ni
 * demi-cadratin dans les fiches produits, FR comme EN.
 *
 * Le problème : wptexturize() convertit trois formes en tirets typographiques
 * (wp-includes/formatting.php) :
 *   « espace tiret espace »        → &#8211; demi-cadratin
 *   « espace deux-tirets espace »  → &#8212; cadratin
 *   « trois tirets »               → &#8212; cadratin
 * Les fiches saisies « … Koinobori - by BCDG » s'affichent donc « … – by BCDG ».
 *
 * Pourquoi ne pas désactiver wptexturize : le filtre officiel `run_wptexturize`
 * est global. Le couper ferait perdre les apostrophes typographiques et les
 * guillemets français sur tout le site, pour corriger une seule chaîne. On
 * réécrit donc l'unique occurrence fautive, après coup.
 *
 * Pourquoi un mu-plugin plutôt que functions.php : le thème enfant a été
 * réécrit par Manus (PR #12). Isoler ici garde le correctif indépendant du thème.
 *
 * ---------------------------------------------------------------------------
 * v1.1.0 — corrections issues de la revue de code du 2026-09-09.
 *
 * 1. `document_title_parts` était le mauvais hook. Le cœur n'y accroche pas
 *    wptexturize : il l'accroche à `document_title`, appliqué APRÈS l'assemblage
 *    des parties par wp_get_document_title(). La correction était donc annulée
 *    juste après, et la balise <title> gardait le tiret fautif sur toutes les
 *    fiches produits. Remplacé par `document_title`.
 * 2. `preg_replace()` renvoie null sur échec PCRE, notamment sur un sujet qui
 *    n'est pas de l'UTF-8 valide sous le modificateur /u — cas réel, le
 *    catalogue étant importé par CSV depuis Windows. Ce null partait dans
 *    `the_title`, qui n'émettait alors plus rien : fiche sans titre, h2 vide en
 *    boucle, title.rendered à null dans l'API REST. Le titre d'origine est
 *    désormais conservé si la substitution échoue.
 * 3. Le cadratin n'était pas couvert, alors que ce fichier prétendait s'en
 *    protéger. Ajouté, ainsi que ses trois formes d'entité.
 * 4. La signature vit aussi dans la description longue et la description
 *    courte, que le correctif ne filtrait pas. Ajoutées.
 * 5. Le drapeau /i combiné à un remplacement en dur réécrivait la casse saisie
 *    par le rédacteur. La signature est désormais capturée et restituée telle
 *    quelle : seul le tiret change.
 * 6. La fermeture anonyme sur `document_title_parts` était indétachable, dans un
 *    mu-plugin lui-même non désactivable depuis l'administration. Toutes les
 *    fonctions sont nommées, donc retirables par remove_filter().
 *
 * wptexturize est accroché en priorité 10 à `the_title`, `single_post_title`,
 * `document_title` et `the_content` (wp-includes/default-filters.php), et
 * WooCommerce l'accroche à `woocommerce_short_description`. On passe donc en 20.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remet un trait d'union simple devant la signature BCDG.
 *
 * Couvre le demi-cadratin et le cadratin, chacun sous ses formes entité
 * numérique, entité hexadécimale, entité nommée et caractère UTF-8 brut.
 * Le séparateur accepte l'espace insécable sous forme d'entité ; `\s` sous /u
 * couvre déjà le caractère U+00A0. Le quantificateur est possessif pour écarter
 * tout retour arrière catastrophique sur une longue suite d'espaces.
 *
 * La signature elle-même est capturée et restituée : la casse saisie est
 * préservée, seul le tiret est réécrit.
 *
 * @param mixed $text Contenu déjà passé par wptexturize.
 * @return mixed Le contenu corrigé, ou l'entrée inchangée si rien à faire.
 */
function koino_bcdg_restore_hyphen( $text ) {
	if ( ! is_string( $text ) || '' === $text ) {
		return $text;
	}

	// Sortie rapide : rien à faire si la signature n'est pas là.
	if ( false === stripos( $text, 'BCDG' ) ) {
		return $text;
	}

	$result = preg_replace(
		'/(?:&#8211;|&#x2013;|&ndash;|\x{2013}|&#8212;|&#x2014;|&mdash;|\x{2014})(?:&nbsp;|&#160;|\s)*+(by\s+BCDG)/iu',
		'- $1',
		$text
	);

	// preg_replace renvoie null en cas d'échec PCRE (UTF-8 invalide, limite de
	// retour arrière). Ne jamais propager ce null : mieux vaut un tiret fautif
	// qu'un titre absent.
	return is_string( $result ) ? $result : $text;
}

/**
 * Applique la correction à un tableau de parties de titre.
 *
 * Conservé pour mémoire, non accroché : `document_title_parts` ne subit pas
 * wptexturize, c'est `document_title` qui le subit. Voir la note 1 de l'en-tête.
 *
 * @param array $parts Parties du titre du document.
 * @return array
 */
function koino_bcdg_restore_hyphen_parts( $parts ) {
	if ( is_array( $parts ) && isset( $parts['title'] ) ) {
		$parts['title'] = koino_bcdg_restore_hyphen( $parts['title'] );
	}

	return $parts;
}

add_filter( 'the_title', 'koino_bcdg_restore_hyphen', 20 );
add_filter( 'single_post_title', 'koino_bcdg_restore_hyphen', 20 );
add_filter( 'document_title', 'koino_bcdg_restore_hyphen', 20 );
add_filter( 'the_content', 'koino_bcdg_restore_hyphen', 20 );
add_filter( 'woocommerce_short_description', 'koino_bcdg_restore_hyphen', 20 );

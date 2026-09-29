<?php
/**
 * Plugin Name: Koinobori — affichage taille unique
 * Description: Sur un produit variable n'exposant qu'UNE seule taille, masque le menu déroulant d'attribut ("Choose an option") et affiche la taille en texte, avec auto-sélection (bouton "Ajouter au panier" actif). Réaffiche le menu déroulant automatiquement dès qu'une 2e taille vivante existe.
 * Version:     1.1.0
 * Author:      Koinobori House
 *
 * Chantier UX catalogue (2026-06-25).
 *
 * Garde les produits en type "variable" (doctrine KH-203 : variable mono-variation,
 * extensible sans coût de schéma) tout en évitant le déroulé inutile quand une seule
 * taille est proposée.
 *
 * 100% DYNAMIQUE : la décision est prise À CHAQUE RENDU à partir du nombre de valeurs
 * que WooCommerce expose réellement pour l'attribut (get_variation_attributes() ne
 * renvoie que les valeurs portées par une variation). Conséquence :
 *  - 1 seule taille  -> texte (ex. "75 cm"), pas de déroulé.
 *  - 2 tailles ou +  -> menu déroulant natif WooCommerce, RÉAFFICHÉ tout seul.
 * Aucun réglage par produit, aucune conversion simple/variable, rien à retoucher.
 *
 * Bilingue : le libellé affiché vient du terme dans la langue de la page (Polylang
 * fournit le slug traduit côté produit EN), donc "75 cm" en FR et "75 cm (30 in)" en EN.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remplace le <select> d'attribut par un texte quand l'attribut n'expose qu'une
 * valeur. Le <select> natif est conservé mais masqué : le script WooCommerce des
 * variations en a besoin pour résoudre la variation et activer "Ajouter au panier".
 *
 * @param string $html Markup du <select> généré par WooCommerce.
 * @param array  $args Arguments de wc_dropdown_variation_attribute_options() :
 *                     'options' (valeurs portées par les variations), 'attribute',
 *                     'product', 'selected', etc.
 * @return string HTML filtré.
 */
function kh_single_variation_as_text( $html, $args ) {
	$options = isset( $args['options'] ) ? $args['options'] : array();
	$attribute = isset( $args['attribute'] ) ? sanitize_title( $args['attribute'] ) : '';

	// Le composant ne concerne que l'attribut Taille prévu par la doctrine catalogue.
	// Un autre attribut mono-valeur (couleur, finition...) reste un select WooCommerce.
	if ( ! in_array( $attribute, array( 'pa_taille', 'taille' ), true )
		|| ! is_array( $options ) || 1 !== count( $options ) ) {
		return $html;
	}

	$value = (string) reset( $options );

	// Libellé lisible : terme (traduit Polylang) si attribut global, sinon valeur brute.
	$label = $value;
	if ( '' !== $attribute && taxonomy_exists( $attribute ) ) {
		$term = get_term_by( 'slug', $value, $attribute );
		if ( $term && ! is_wp_error( $term ) ) {
			$label = $term->name;
		}
	}

	return '<span class="kh-size-single">' . esc_html( $label ) . '</span>'
		. '<span class="kh-size-control kh-size-hidden">' . $html . '</span>';
}
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'kh_single_variation_as_text', 20, 2 );

/**
 * CSS + JS (fiche produit uniquement) :
 *  - masque le <select> conservé et le lien "Effacer" en mode taille unique ;
 *  - auto-sélectionne l'unique valeur pour activer le bouton "Ajouter au panier".
 * Idempotent et rejoué après l'init WooCommerce des variations (filet setTimeout).
 */
function kh_single_variation_assets() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	?>
	<style id="kh-single-variation-css">
		.kh-size-hidden { display: none !important; }
		.kh-size-single { font-weight: 600; }
		.variations_form.kh-all-attributes-single .reset_variations { display: none !important; }
	</style>
	<script id="kh-single-variation-js">
	( function ( $ ) {
		if ( ! $ ) { return; }
		$( function () {
			function applyForm( $form ) {
				$form.find( '.kh-size-control select' ).each( function () {
					var $select = $( this );
					var $control = $select.closest( '.kh-size-control' );
					var $label = $control.prev( '.kh-size-single' );
					var $real = $select.find( 'option' ).filter( function () {
						return '' !== this.value;
					} );

					// Si WooCommerce retire l'option du DOM, rendre le contrôle natif.
					if ( 1 !== $real.length ) {
						$control.removeClass( 'kh-size-hidden' );
						$label.hide();
						return;
					}
					$control.addClass( 'kh-size-hidden' );
					$label.show();

					if ( ! $select.val() ) {
						$select.val( $real.val() ).trigger( 'change' );
					}
				} );

				var total = $form.find( '.variations select' ).length;
				var hidden = $form.find( '.kh-size-control.kh-size-hidden select' ).length;
				$form.toggleClass( 'kh-all-attributes-single', total > 0 && total === hidden );
			}

			function applySingle() {
				$( '.variations_form' ).each( function () {
					applyForm( $( this ) );
				} );
			}
			applySingle();
			setTimeout( applySingle, 60 ); // après l'init WooCommerce des variations.

			// Le lien Effacer vide aussi le select Taille masqué. Le resélectionner
			// après le reset laisse les attributs visibles réellement réinitialisés.
			$( document.body ).on( 'reset_data', '.variations_form', function () {
				var $form = $( this );
				setTimeout( function () { applyForm( $form ); }, 0 );
			} );
		} );
	} )( window.jQuery );
	</script>
	<?php
}
add_action( 'wp_footer', 'kh_single_variation_assets', 99 );

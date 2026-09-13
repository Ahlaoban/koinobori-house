<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Koinobori_Icon_Walker extends Walker_Nav_Menu {
	/** Inline paths from the supplied Manus header; professionals extends its outline style. */
	public static function icon( $key ) {
		$icons = array(
			'shop' => '<path d="M6 8h12l-1 11a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/>',
			'lifestyle' => '<path d="M6 4h12a1 1 0 0 1 1 1v15H8a2 2 0 0 1-2-2V4z"/><path d="M6 17h13"/>',
			'house' => '<circle cx="12" cy="8" r="3.4"/><path d="M5 20c0-3.6 3-6 7-6s7 2.4 7 6"/>',
			'professionals' => '<circle cx="9" cy="8" r="3"/><path d="M3 20a6 6 0 0 1 12 0M16 5.5a3 3 0 0 1 0 5.5M17.5 14a6 6 0 0 1 3.5 6"/>',
			'contact' => '<path d="M3 6h18v12H3z"/><path d="M3.5 7l8.5 6.5L20.5 7"/>',
			'account' => '<path d="M14 4h6v16h-6M3 12h12m-4-4 4 4-4 4"/>',
			'cart' => '<path d="m3 4 2 1 3 12h11l2-9H6M9 21h.01M18 21h.01"/>',
		);
		return '<svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true" focusable="false">' . ( $icons[ $key ] ?? $icons['shop'] ) . '</svg>';
	}
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$key = '';
		foreach ( array( 'shop', 'lifestyle', 'house', 'professionals', 'contact' ) as $candidate ) {
			if ( in_array( 'icon-' . $candidate, (array) $item->classes, true ) ) { $key = $candidate; break; }
		}
		// Only the five approved classes appear. No obsolete Home / Art de vivre entry.
		if ( ! $key ) { return; }
		$current = ! empty( $item->current );
		$output .= '<li class="kh-nav-item"><a class="kh-nav-link" href="' . esc_url( $item->url ) . '" aria-label="' . esc_attr( wp_strip_all_tags( $item->title ) ) . '"'
			. ( $current ? ' aria-current="page"' : '' ) . '>' . self::icon( $key )
			. '<span class="kh-tip" aria-hidden="true">' . esc_html( wp_strip_all_tags( $item->title ) ) . '</span></a>';
	}
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( array_intersect( array( 'icon-shop', 'icon-lifestyle', 'icon-house', 'icon-professionals', 'icon-contact' ), (array) $item->classes ) ) { $output .= '</li>'; }
	}
}

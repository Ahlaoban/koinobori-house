<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Koinobori_Icon_Walker extends Walker_Nav_Menu {
	/** Lucide 1.17.0, selected by Alain: A + Contact B + Professionals C. See licenses/lucide.txt. */
	public static function icon( $key ) {
		$icons = array(
			'shop' => '<path d="M15 21v-5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v5"/><path d="M17.774 10.31a1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.451 0 1.12 1.12 0 0 0-1.548 0 2.5 2.5 0 0 1-3.452 0 1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.77-3.248l2.889-4.184A2 2 0 0 1 7 2h10a2 2 0 0 1 1.653.873l2.895 4.192a2.5 2.5 0 0 1-3.774 3.244"/><path d="M4 10.95V19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8.05"/>',
			'lifestyle' => '<path d="M12.8 19.6A2 2 0 1 0 14 16H2M17.5 8a2.5 2.5 0 1 1 2 4H2M9.8 4.4A2 2 0 1 1 11 8H2"/>',
			'house' => '<path d="m11 10 3 3M6.5 21A3.5 3.5 0 1 0 3 17.5a2.62 2.62 0 0 1-.708 1.792A1 1 0 0 0 3 21zM9.969 17.031 21.378 5.624a1 1 0 0 0-3.002-3.002L6.967 14.031"/>',
			'professionals' => '<path d="m11 17 2 2a1 1 0 1 0 3-3"/><path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"/><path d="m21 3 1 11h-2M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3M3 4h8"/>',
			'contact' => '<path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"/><path d="m21.854 2.147-10.94 10.939"/>',
			'account' => '<circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>',
			'cart' => '<path d="M16 10a4 4 0 0 1-8 0M3.103 6.034h17.794M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"/>',
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

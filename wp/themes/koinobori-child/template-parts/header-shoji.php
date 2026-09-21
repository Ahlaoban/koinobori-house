<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<header id="kh-header" class="kh-header">
	<a class="kh-header-home" href="<?php echo esc_url( $home ); ?>" aria-label="<?php echo esc_attr( $home_label ); ?>">
		<span class="kh-wordmark" aria-hidden="true"><span class="kh-word-left">Koinobori</span><span class="kh-word-right">House</span></span>
		<span class="kh-by" aria-hidden="true">BY BCDG</span>
	</a>
	<div class="kh-header-center">
		<nav aria-label="<?php echo esc_attr( koinobori_child_header_text( 'Menu principal', 'Main menu' ) ); ?>"><?php koinobori_child_header_menu(); ?></nav>
		<div class="kh-header-actions"><?php koinobori_child_header_actions(); ?></div>
	</div>
	<button class="kh-menu-toggle" type="button" aria-controls="kh-menu-dialog" aria-expanded="false">Menu <span aria-hidden="true">☰</span></button>
</header>
<div class="kh-header-spacer" aria-hidden="true"></div>
<dialog id="kh-menu-dialog" class="kh-menu-dialog" aria-label="<?php echo esc_attr( koinobori_child_header_text( 'Navigation', 'Navigation' ) ); ?>">
	<button class="kh-menu-close" type="button" aria-label="<?php echo esc_attr( $close ); ?>">KoinoboriHouse <span aria-hidden="true">×</span><small>BY BCDG</small></button>
	<nav aria-label="<?php echo esc_attr( koinobori_child_header_text( 'Menu mobile', 'Mobile menu' ) ); ?>"><?php koinobori_child_header_menu(); ?></nav>
	<div class="kh-header-actions"><?php koinobori_child_header_actions( true ); ?></div>
</dialog>

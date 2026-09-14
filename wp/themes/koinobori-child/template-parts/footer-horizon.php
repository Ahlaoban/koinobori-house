<?php
/** @var array $groups @var array $legal @var array $languages @var string $language */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<footer class="kh-site-footer" id="kh-site-footer">
	<div class="kh-footer-services kh-footer-container">
		<?php foreach ( $groups as $index => $group ) :
			$links = koinobori_child_footer_links( $group['entries'], $language );
			if ( ! $links ) { continue; }
			?>
			<nav class="kh-footer-group" aria-labelledby="kh-footer-heading-<?php echo esc_attr( $index ); ?>">
				<h2 id="kh-footer-heading-<?php echo esc_attr( $index ); ?>"><?php echo esc_html( $group['title'] ); ?></h2>
				<ul>
					<?php foreach ( $links as $link ) : ?>
						<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endforeach; ?>
	</div>
	<div class="kh-footer-rule">
		<div class="kh-footer-bottom kh-footer-container">
			<p class="kh-footer-copyright">© <?php echo esc_html( wp_date( 'Y' ) ); ?> Koinobori House <span>· <?php echo esc_html( koinobori_child_header_text( 'Créations BCDG', 'BCDG creations' ) ); ?></span></p>
			<?php if ( $legal ) : ?>
				<nav class="kh-footer-legal" aria-label="<?php echo esc_attr( koinobori_child_header_text( 'Informations légales', 'Legal information' ) ); ?>">
					<ul>
						<?php foreach ( $legal as $link ) : ?>
							<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>
			<div class="kh-footer-tools">
				<?php if ( $languages ) : ?>
					<nav class="kh-footer-languages" aria-label="<?php echo esc_attr( koinobori_child_header_text( 'Langue du site', 'Site language' ) ); ?>">
						<?php foreach ( $languages as $lang ) : ?>
							<a href="<?php echo esc_url( $lang['url'] ); ?>" lang="<?php echo esc_attr( $lang['slug'] ); ?>" hreflang="<?php echo esc_attr( $lang['slug'] ); ?>" aria-label="<?php echo esc_attr( 'fr' === $lang['slug'] ? 'Français' : 'English' ); ?>"<?php echo $lang['current_lang'] ? ' aria-current="true"' : ''; ?>><?php echo esc_html( strtoupper( $lang['slug'] ) ); ?></a>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>
				<a class="kh-footer-top" href="#kh-page-top" aria-label="<?php echo esc_attr( koinobori_child_header_text( 'Retour en haut', 'Back to top' ) ); ?>">
					<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m5 12 7-7 7 7M12 5v14"/></svg>
				</a>
			</div>
		</div>
	</div>
</footer>

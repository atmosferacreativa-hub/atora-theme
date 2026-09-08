<?php
/**
 * Pie del theme.
 *
 * @package Atora_Theme
 */
?>
</main>

<?php if ( ! atora_theme_is_canvas_template() ) : ?>
	<?php if ( ! atora_theme_render_editable_template_part( 'footer' ) ) : ?>
	<?php
	$footer_layout = atora_theme_get_theme_mod( 'atora_theme_footer_layout', 'atora_them_footer_layout', 'full' );
	$footer_text   = atora_theme_get_theme_mod(
		'atora_theme_footer_text',
		'atora_them_footer_text',
		__( 'Cursos, programas y comunidades creadas con ATORA LMS.', 'atora-theme' )
	);
	$footer_credit = atora_theme_get_theme_mod(
		'atora_theme_footer_credit',
		'atora_them_footer_credit',
		__( 'Atora Theme / www.atora.studio', 'atora-theme' )
	);
	$footer_items  = array_filter(
		array(
			'location' => atora_theme_get_theme_mod( 'atora_theme_footer_location', 'atora_them_footer_location', '' ),
			'email'    => atora_theme_get_theme_mod( 'atora_theme_footer_email', 'atora_them_footer_email', '' ),
			'phone'    => atora_theme_get_theme_mod( 'atora_theme_footer_phone', 'atora_them_footer_phone', '' ),
		)
	);
	$social_links  = array_filter(
		array(
			'Instagram' => atora_theme_get_theme_mod( 'atora_theme_footer_instagram', 'atora_them_footer_instagram', '' ),
			'YouTube'   => atora_theme_get_theme_mod( 'atora_theme_footer_youtube', 'atora_them_footer_youtube', '' ),
			'Facebook'  => atora_theme_get_theme_mod( 'atora_theme_footer_facebook', 'atora_them_footer_facebook', '' ),
			'TikTok'    => atora_theme_get_theme_mod( 'atora_theme_footer_tiktok', 'atora_them_footer_tiktok', '' ),
		)
	);
	?>
	<footer class="atora-theme-footer atora-theme-footer--<?php echo esc_attr( $footer_layout ); ?>">
		<div class="atora-theme-footer__inner">
			<div class="atora-theme-footer__brand">
				<?php echo atora_theme_logo_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $footer_text ) : ?>
					<p><?php echo esc_html( $footer_text ); ?></p>
				<?php endif; ?>
			</div>
			<nav class="atora-theme-footer__nav" aria-label="<?php esc_attr_e( 'Navegacion del pie', 'atora-theme' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'menu_class'     => 'atora-theme-footer-menu',
						'container'      => false,
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</nav>
			<?php if ( 'compact' !== $footer_layout && ( $footer_items || $social_links ) ) : ?>
				<div class="atora-theme-footer__contact">
					<?php if ( $footer_items ) : ?>
						<ul class="atora-theme-footer__contact-list">
							<?php if ( ! empty( $footer_items['location'] ) ) : ?>
								<li><?php echo esc_html( $footer_items['location'] ); ?></li>
							<?php endif; ?>
							<?php if ( ! empty( $footer_items['email'] ) ) : ?>
								<li><a href="mailto:<?php echo esc_attr( antispambot( $footer_items['email'] ) ); ?>"><?php echo esc_html( antispambot( $footer_items['email'] ) ); ?></a></li>
							<?php endif; ?>
							<?php if ( ! empty( $footer_items['phone'] ) ) : ?>
								<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $footer_items['phone'] ) ); ?>"><?php echo esc_html( $footer_items['phone'] ); ?></a></li>
							<?php endif; ?>
						</ul>
					<?php endif; ?>
					<?php if ( $social_links ) : ?>
						<div class="atora-theme-footer__social" aria-label="<?php esc_attr_e( 'Redes sociales', 'atora-theme' ); ?>">
							<?php foreach ( $social_links as $label => $url ) : ?>
								<a href="<?php echo esc_url( $url ); ?>" rel="me noopener" target="_blank"><?php echo esc_html( $label ); ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="atora-theme-footer__meta">
				<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
				<?php if ( $footer_credit ) : ?>
					<p><?php echo esc_html( $footer_credit ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</footer>
	<?php endif; ?>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>

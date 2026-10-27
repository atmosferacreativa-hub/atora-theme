<?php
/**
 * Pie de página.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	</<?php echo esc_attr( atora_main_tag() ); ?>>

	<footer class="meridian-footer">
		<div class="meridian-shell">
			<div class="meridian-footer__grid">
				<div class="meridian-footer__brand">
					<?php echo atora_get_logo_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( '' !== atora_get_footer_text() ) : ?>
						<p><?php echo esc_html( atora_get_footer_text() ); ?></p>
					<?php endif; ?>
				</div>

				<div>
					<h3><?php esc_html_e( 'Learning', 'atora-learning' ); ?></h3>
					<ul class="meridian-footer__links">
						<li><a href="<?php echo esc_url( atora_get_archive_link( 'lm_course' ) ); ?>"><?php esc_html_e( 'Courses', 'atora-learning' ); ?></a></li>
						<li><a href="<?php echo esc_url( atora_get_archive_link( 'lm_program' ) ); ?>"><?php esc_html_e( 'Programs', 'atora-learning' ); ?></a></li>
						<li><a href="<?php echo esc_url( atora_get_dashboard_url() ); ?>"><?php esc_html_e( 'Student Dashboard', 'atora-learning' ); ?></a></li>
						<?php if ( '' !== Atora_Theme_Plugin_Bridge::get_certificate_verify_url() ) : ?>
							<li><a href="<?php echo esc_url( Atora_Theme_Plugin_Bridge::get_certificate_verify_url() ); ?>"><?php esc_html_e( 'Verify a certificate', 'atora-learning' ); ?></a></li>
						<?php endif; ?>
					</ul>
				</div>

				<div>
					<h3><?php esc_html_e( 'Resources', 'atora-learning' ); ?></h3>
					<ul class="meridian-footer__links">
						<?php if ( atora_has_store() ) : ?>
							<li><a href="<?php echo esc_url( atora_get_shop_url() ); ?>"><?php esc_html_e( 'Shop', 'atora-learning' ); ?></a></li>
							<li><a href="<?php echo esc_url( atora_get_cart_url() ); ?>"><?php esc_html_e( 'Cart', 'atora-learning' ); ?></a></li>
						<?php endif; ?>
						<?php if ( atora_has_podcast() ) : ?>
							<li><a href="<?php echo esc_url( atora_get_archive_link( 'podcast' ) ); ?>"><?php esc_html_e( 'Podcast', 'atora-learning' ); ?></a></li>
						<?php endif; ?>
						<?php if ( '' !== atora_get_blog_url() ) : ?>
							<li><a href="<?php echo esc_url( atora_get_blog_url() ); ?>"><?php esc_html_e( 'Journal', 'atora-learning' ); ?></a></li>
						<?php endif; ?>
					</ul>
				</div>

				<div>
					<h3><?php esc_html_e( 'Navigation', 'atora-learning' ); ?></h3>
					<?php if ( has_nav_menu( 'footer' ) ) : ?>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'container'      => false,
								'menu_class'     => 'meridian-footer__links',
								'fallback_cb'    => 'atora_nav_fallback',
							)
						);
						?>
					<?php else : ?>
						<?php
						$atora_footer_pages = array(
							__( 'About', 'atora-learning' )   => atora_get_page_url_by_slugs( array( 'nosotros', 'acerca', 'about' ) ),
							__( 'Contact', 'atora-learning' ) => atora_get_page_url_by_slugs( array( 'contacto', 'contact' ) ),
							__( 'Support', 'atora-learning' ) => atora_get_page_url_by_slugs( array( 'soporte', 'ayuda', 'support' ) ),
						);
						?>
						<ul class="meridian-footer__links">
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'atora-learning' ); ?></a></li>
							<?php foreach ( array_filter( $atora_footer_pages ) as $atora_label => $atora_url ) : ?>
								<li><a href="<?php echo esc_url( $atora_url ); ?>"><?php echo esc_html( $atora_label ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</div>

			<div class="meridian-footer__bottom">
				<p>
					<?php
					printf(
						/* translators: 1: current year, 2: site name. */
						esc_html__( '© %1$d %2$s.', 'atora-learning' ),
						(int) wp_date( 'Y' ),
						esc_html( get_bloginfo( 'name' ) )
					);
					?>
				</p>
			</div>

			<div class="meridian-footer__legal-row" aria-label="<?php esc_attr_e( 'Legal navigation', 'atora-learning' ); ?>">
				<?php if ( has_nav_menu( 'legal' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'legal',
							'container'      => false,
							'menu_class'     => 'meridian-mini-menu',
							'fallback_cb'    => 'atora_nav_fallback',
						)
					);
					?>
				<?php else : ?>
					<?php
					$atora_legal = array_filter(
						array(
							__( 'Privacy', 'atora-learning' ) => get_privacy_policy_url(),
							__( 'Terms', 'atora-learning' )   => atora_get_page_url_by_slugs( array( 'terminos-y-condiciones', 'terminos', 'terms' ) ),
							// Enlace público que exigen Google Play y App Store (ATORA LMS 6.33+).
							__( 'Delete my account', 'atora-learning' ) => Atora_Theme_Plugin_Bridge::get_account_deletion_url(),
						)
					);
					?>
					<?php if ( $atora_legal ) : ?>
						<ul class="meridian-mini-menu">
							<?php foreach ( $atora_legal as $atora_label => $atora_url ) : ?>
								<li><a href="<?php echo esc_url( $atora_url ); ?>"><?php echo esc_html( $atora_label ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</footer>

	<?php wp_footer(); ?>
</body>
</html>

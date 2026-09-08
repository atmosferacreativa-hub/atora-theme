<?php
/**
 * CTA comercial de curso para Atora Theme.
 *
 * Variables: $cta_url, $cta_label, $tagline, $subtitle, $course_permalink.
 *
 * @package Atora_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="cc-cta-bottom atora-theme-course-cta">
	<p class="cc-cta-title"><?php esc_html_e( 'Empieza a crear con criterio.', 'atora-lms' ); ?></p>
	<?php if ( ! empty( $tagline ) || ! empty( $subtitle ) ) : ?>
		<p><?php echo esc_html( $tagline ?: $subtitle ); ?></p>
	<?php endif; ?>
	<?php if ( ! empty( $cta_url ) ) : ?>
		<a class="cc-btn-white" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_label ); ?></a>
	<?php elseif ( ! is_user_logged_in() ) : ?>
		<a class="cc-btn-white" href="<?php echo esc_url( wp_login_url( $course_permalink ) ); ?>"><?php esc_html_e( 'Iniciar sesion', 'atora-lms' ); ?></a>
	<?php endif; ?>
</div>

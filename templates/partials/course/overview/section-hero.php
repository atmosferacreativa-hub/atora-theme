<?php
/**
 * Hero de vista de estudiante para Atora Theme.
 *
 * Variables: $course_id, $user_id, $is_enrolled, $progress, $done, $total,
 * $cta_lesson_id, $cta_label_key, $course_excerpt, $instructor_names,
 * $course_thumbnail_id.
 *
 * @package Atora_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="cov-hero atora-theme-student-hero<?php echo ( ! $user_id || ! $is_enrolled ) ? ' cov-hero--funnel' : ''; ?>">
	<div class="cov-hero-content">
		<p class="cov-kicker"><?php esc_html_e( 'Curso', 'atora-lms' ); ?></p>
		<h1 class="cov-title"><?php echo esc_html( get_the_title( $course_id ) ); ?></h1>

		<?php if ( ! empty( $course_excerpt ) ) : ?>
			<p class="cov-desc"><?php echo esc_html( $course_excerpt ); ?></p>
		<?php endif; ?>

		<div class="cov-meta-row">
			<span class="cov-meta-item">
				<?php
				printf(
					/* translators: %d: lesson count. */
					esc_html__( '%d lecciones', 'atora-lms' ),
					absint( $total )
				);
				?>
			</span>
			<?php if ( ! empty( $instructor_names ) ) : ?>
				<span class="cov-meta-item"><?php echo esc_html( implode( ', ', $instructor_names ) ); ?></span>
			<?php endif; ?>
		</div>

		<?php if ( $user_id && $is_enrolled ) : ?>
			<div class="cov-progress-row">
				<div class="cov-progress-bar-wrap">
					<div class="cov-progress-bar" style="width:<?php echo esc_attr( $progress ); ?>%"></div>
				</div>
				<span class="cov-progress-label">
					<?php echo esc_html( $progress ); ?>% -
					<?php echo esc_html( $done ); ?>/<?php echo esc_html( $total ); ?>
					<?php esc_html_e( 'completadas', 'atora-lms' ); ?>
				</span>
			</div>
		<?php endif; ?>

		<?php if ( ! $user_id ) : ?>
			<a class="cov-btn cov-btn-primary" href="<?php echo esc_url( wp_login_url( get_permalink( $course_id ) ) ); ?>">
				<?php esc_html_e( 'Iniciar sesion para acceder', 'atora-lms' ); ?>
			</a>
		<?php elseif ( ! $is_enrolled ) : ?>
			<p class="cov-gate-msg"><?php esc_html_e( 'Debes inscribirte en este curso para acceder a las lecciones.', 'atora-lms' ); ?></p>
		<?php elseif ( ! empty( $cta_lesson_id ) ) : ?>
			<a class="cov-btn cov-btn-primary" href="<?php echo esc_url( get_permalink( $cta_lesson_id ) ); ?>">
				<?php echo esc_html( __( $cta_label_key, 'atora-lms' ) ); // phpcs:ignore WordPress.WP.I18n ?>
			</a>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $course_thumbnail_id ) ) : ?>
		<div class="cov-hero-thumb">
			<?php echo wp_get_attachment_image( $course_thumbnail_id, 'large', false, array( 'class' => 'cov-thumb-img' ) ); ?>
		</div>
	<?php endif; ?>
</div>

<?php
/**
 * Hero comercial de curso para Atora Theme.
 *
 * Variables provistas por ATORA LMS:
 * $title, $subtitle, $tagline, $duration, $lesson_ids, $certificate,
 * $program_ids, $price, $price_label, $cta_url, $cta_label,
 * $course_permalink, $hero_featured_image_html, $hero_featured_image_url,
 * $embed_src, $hero_fallback_iframe_src, $hero_direct_video_url,
 * $hero_has_video_media, $hero_has_direct_video.
 *
 * @package Atora_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="cc-hero atora-theme-course-hero">
	<div class="atora-theme-course-hero__copy">
		<p class="cc-hero-kicker"><?php esc_html_e( 'Curso', 'atora-lms' ); ?></p>
		<h1 class="cc-hero-title"><?php echo esc_html( $title ); ?></h1>

		<?php if ( ! empty( $tagline ) || ! empty( $subtitle ) ) : ?>
			<p class="cc-hero-tagline"><?php echo esc_html( $tagline ?: $subtitle ); ?></p>
		<?php endif; ?>

		<div class="cc-hero-meta atora-theme-course-hero__meta">
			<?php if ( ! empty( $duration ) ) : ?>
				<span><?php echo esc_html( $duration ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $lesson_ids ) ) : ?>
				<span>
					<?php
					printf(
						/* translators: %d: lesson count. */
						esc_html__( '%d lecciones', 'atora-lms' ),
						absint( count( $lesson_ids ) )
					);
					?>
				</span>
			<?php endif; ?>
			<?php if ( ! empty( $certificate ) ) : ?>
				<span><?php esc_html_e( 'Certificado incluido', 'atora-lms' ); ?></span>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $program_ids ) ) : ?>
			<div class="cc-program-chips">
				<?php foreach ( $program_ids as $program_id ) : ?>
					<a class="cc-program-chip" href="<?php echo esc_url( get_permalink( $program_id ) ); ?>">
						<?php echo esc_html( get_the_title( $program_id ) ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="atora-theme-course-hero__panel">
		<?php if ( ! empty( $hero_featured_image_html ) ) : ?>
			<figure class="cc-hero-media-top atora-theme-course-hero__image">
				<?php echo $hero_featured_image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</figure>
		<?php endif; ?>

		<?php if ( ! empty( $hero_has_video_media ) ) : ?>
			<div class="cc-hero-media-card">
				<?php if ( ! empty( $embed_src ) ) : ?>
					<div class="cc-hero-video-ratio">
						<iframe src="<?php echo esc_url( $embed_src ); ?>" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
					</div>
				<?php elseif ( ! empty( $hero_fallback_iframe_src ) ) : ?>
					<div class="cc-hero-video-ratio">
						<iframe src="<?php echo esc_url( $hero_fallback_iframe_src ); ?>" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
					</div>
				<?php elseif ( ! empty( $hero_has_direct_video ) ) : ?>
					<div class="cc-hero-video-ratio">
						<video controls preload="metadata"<?php echo ! empty( $hero_featured_image_url ) ? ' poster="' . esc_url( $hero_featured_image_url ) . '"' : ''; ?>>
							<source src="<?php echo esc_url( $hero_direct_video_url ); ?>">
						</video>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $price ) || ! empty( $cta_url ) || ! is_user_logged_in() ) : ?>
			<div class="cc-hero-cta-box atora-theme-course-hero__cta">
				<?php if ( ! empty( $price ) ) : ?>
					<p class="cc-hero-price"><?php echo esc_html( $price ); ?></p>
					<?php if ( ! empty( $price_label ) ) : ?>
						<p class="cc-hero-price-label"><?php echo esc_html( $price_label ); ?></p>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( ! empty( $cta_url ) ) : ?>
					<a class="cc-btn-primary" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_label ); ?></a>
				<?php elseif ( ! is_user_logged_in() ) : ?>
					<a class="cc-btn-primary" href="<?php echo esc_url( wp_login_url( $course_permalink ) ); ?>"><?php esc_html_e( 'Iniciar sesion', 'atora-lms' ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

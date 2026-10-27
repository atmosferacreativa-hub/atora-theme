<?php
/**
 * Course card.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$course = atora_get_course_card_data( get_the_ID() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'atora-course-card' ); ?> data-reveal>
	<div class="atora-course-thumbnail">
		<a href="<?php the_permalink(); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'atora-meridian-card' ); ?>
			<?php endif; ?>
		</a>

		<?php if ( ! empty( $course['label'] ) ) : ?>
			<div class="atora-course-badges">
				<span class="atora-course-level-badge"><?php echo esc_html( $course['label'] ); ?></span>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $course['duration'] ) ) : ?>
			<div class="atora-course-duration">
				<span><?php echo esc_html( $course['duration'] ); ?></span>
			</div>
		<?php endif; ?>
	</div>

	<div class="atora-course-content">
		<div class="atora-course-meta">
			<span><?php esc_html_e( 'Course', 'atora-learning' ); ?></span>
			<span><?php echo esc_html( get_the_date() ); ?></span>
		</div>

		<h3 class="atora-course-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( ! empty( $course['instructors'] ) ) : ?>
			<div class="atora-course-instructor">
				<span><?php echo esc_html( implode( ', ', $course['instructors'] ) ); ?></span>
			</div>
		<?php endif; ?>

		<p class="atora-course-excerpt"><?php echo esc_html( atora_get_plain_excerpt( get_the_ID(), 20 ) ); ?></p>

		<div class="atora-course-stats">
			<?php if ( ! empty( $course['lessons'] ) ) : ?>
				<span><?php echo esc_html( sprintf( _n( '%d lesson', '%d lessons', (int) $course['lessons'], 'atora-learning' ), (int) $course['lessons'] ) ); ?></span>
			<?php endif; ?>
		</div>

		<div class="atora-course-footer">
			<div>
				<?php if ( ! empty( $course['price'] ) ) : ?>
					<span class="atora-course-price-current"><?php echo esc_html( wp_strip_all_tags( (string) $course['price'] ) ); ?></span>
				<?php endif; ?>
			</div>
			<a class="meridian-button meridian-button--primary meridian-button--small" href="<?php the_permalink(); ?>"><?php echo esc_html( $course['cta_label'] ); ?></a>
		</div>
	</div>
</article>

<?php
/**
 * Program archive fallback.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="meridian-archive-hero">
	<div class="meridian-archive-hero__copy" data-reveal>
		<p class="meridian-eyebrow"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
		<h1><?php esc_html_e( 'Programs', 'atora-learning' ); ?></h1>
		<p><?php echo esc_html( atora_get_context_description() ); ?></p>
	</div>
</section>

<div class="meridian-content-shell">
	<?php if ( have_posts() ) : ?>
		<div class="posts-grid">
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<article class="post-card" data-reveal>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="post-thumbnail">
							<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
								<?php the_post_thumbnail( 'atora-meridian-card' ); ?>
							</a>
						</div>
					<?php endif; ?>
					<div class="post-card__body">
						<div class="meridian-entry-meta">
							<span><?php esc_html_e( 'Program', 'atora-learning' ); ?></span>
						</div>
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p><?php echo esc_html( atora_get_plain_excerpt( get_the_ID(), 22 ) ); ?></p>
						<div class="entry-footer">
							<a class="meridian-button meridian-button--primary meridian-button--small" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View Program', 'atora-learning' ); ?></a>
						</div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

		<?php atora_render_pagination(); ?>
	<?php else : ?>
		<div class="meridian-empty-state">
			<h2><?php esc_html_e( 'No programs available yet', 'atora-learning' ); ?></h2>
			<p><?php esc_html_e( 'New programs will appear here soon.', 'atora-learning' ); ?></p>
		</div>
	<?php endif; ?>
</div>

<?php
get_footer();

<?php
/**
 * Entrada individual.
 *
 * @package Atora_Theme
 */

get_header();
?>

<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'atora-theme-single' ); ?>>
		<header class="atora-theme-single__hero">
			<div class="atora-theme-container atora-theme-single__grid">
				<div>
					<p class="atora-theme-eyebrow"><?php esc_html_e( 'Articulo', 'atora-theme' ); ?></p>
					<h1><?php the_title(); ?></h1>
					<?php atora_theme_entry_meta(); ?>
				</div>
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="atora-theme-single__media">
						<?php the_post_thumbnail( 'atora-theme-hero' ); ?>
					</figure>
				<?php endif; ?>
			</div>
		</header>
		<div class="atora-theme-entry-content atora-theme-readable">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div>
	</article>

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<div class="atora-theme-container atora-theme-comments">
			<?php comments_template(); ?>
		</div>
	<?php endif; ?>
<?php endwhile; ?>

<?php
get_footer();

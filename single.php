<?php
/**
 * Entrada individual.
 *
 * @package Atora_Them
 */

get_header();
?>

<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'atora-them-single' ); ?>>
		<header class="atora-them-single__hero">
			<div class="atora-them-container atora-them-single__grid">
				<div>
					<p class="atora-them-eyebrow"><?php esc_html_e( 'Articulo', 'atora-them' ); ?></p>
					<h1><?php the_title(); ?></h1>
					<?php atora_them_entry_meta(); ?>
				</div>
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="atora-them-single__media">
						<?php the_post_thumbnail( 'atora-them-hero' ); ?>
					</figure>
				<?php endif; ?>
			</div>
		</header>
		<div class="atora-them-entry-content atora-them-readable">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div>
	</article>

	<?php if ( comments_open() || get_comments_number() ) : ?>
		<div class="atora-them-container atora-them-comments">
			<?php comments_template(); ?>
		</div>
	<?php endif; ?>
<?php endwhile; ?>

<?php
get_footer();

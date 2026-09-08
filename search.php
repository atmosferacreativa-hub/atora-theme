<?php
/**
 * Resultados de busqueda.
 *
 * @package Atora_Them
 */

get_header();
?>

<section class="atora-them-archive-hero">
	<div class="atora-them-container">
		<p class="atora-them-eyebrow"><?php esc_html_e( 'Busqueda', 'atora-them' ); ?></p>
		<h1>
			<?php
			printf(
				/* translators: %s: search query. */
				esc_html__( 'Resultados para "%s"', 'atora-them' ),
				esc_html( get_search_query() )
			);
			?>
		</h1>
		<?php get_search_form(); ?>
	</div>
</section>

<div class="atora-them-container atora-them-post-grid">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<?php get_template_part( 'template-parts/content', get_post_type() ); ?>
		<?php endwhile; ?>
		<div class="atora-them-pagination">
			<?php the_posts_pagination(); ?>
		</div>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</div>

<?php
get_footer();

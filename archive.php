<?php
/**
 * Archivos.
 *
 * @package Atora_Theme
 */

get_header();
?>

<section class="atora-theme-archive-hero">
	<div class="atora-theme-container">
		<p class="atora-theme-eyebrow"><?php esc_html_e( 'Archivo', 'atora-theme' ); ?></p>
		<h1><?php the_archive_title(); ?></h1>
		<?php the_archive_description( '<div class="atora-theme-archive-description">', '</div>' ); ?>
	</div>
</section>

<div class="atora-theme-container atora-theme-post-grid">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<?php get_template_part( 'template-parts/content', get_post_type() ); ?>
		<?php endwhile; ?>
		<div class="atora-theme-pagination">
			<?php the_posts_pagination(); ?>
		</div>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</div>

<?php
get_footer();

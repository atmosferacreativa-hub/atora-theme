<?php
/**
 * Plantilla principal.
 *
 * @package Atora_Theme
 */

get_header();
?>

<section class="atora-theme-archive-hero">
	<div class="atora-theme-container">
		<p class="atora-theme-eyebrow"><?php esc_html_e( 'Publicaciones', 'atora-theme' ); ?></p>
		<h1><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
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

<?php
/**
 * Portada del sitio.
 *
 * @package Atora_Theme
 */

get_header();
?>

<?php if ( have_posts() ) : ?>
	<?php the_post(); ?>
	<?php if ( '' !== trim( wp_strip_all_tags( get_the_content() ) ) || has_blocks() ) : ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'atora-theme-front-content' ); ?>>
			<div class="atora-theme-entry-content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php else : ?>
		<?php get_template_part( 'template-parts/front', 'them' ); ?>
	<?php endif; ?>
<?php else : ?>
	<?php get_template_part( 'template-parts/front', 'them' ); ?>
<?php endif; ?>

<?php
get_footer();

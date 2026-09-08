<?php
/**
 * Template Name: Canvas para maquetadores
 * Template Post Type: page, post
 *
 * @package Atora_Theme
 */

get_header();
?>

<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'atora-theme-canvas-content' ); ?>>
		<?php the_content(); ?>
	</article>
<?php endwhile; ?>

<?php
get_footer();

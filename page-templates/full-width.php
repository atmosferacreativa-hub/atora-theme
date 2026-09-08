<?php
/**
 * Template Name: Ancho completo
 * Template Post Type: page, post
 *
 * @package Atora_Them
 */

get_header();
?>

<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'atora-them-fullwidth' ); ?>>
		<div class="atora-them-entry-content">
			<?php the_content(); ?>
		</div>
	</article>
<?php endwhile; ?>

<?php
get_footer();

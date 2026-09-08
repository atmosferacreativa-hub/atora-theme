<?php
/**
 * Template Name: Landing Atora Them
 * Template Post Type: page
 *
 * @package Atora_Them
 */

get_header();
?>

<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'atora-them-landing' ); ?>>
		<?php if ( has_blocks() || '' !== trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
			<div class="atora-them-entry-content">
				<?php the_content(); ?>
			</div>
		<?php else : ?>
			<?php get_template_part( 'template-parts/front', 'them' ); ?>
		<?php endif; ?>
	</article>
<?php endwhile; ?>

<?php
get_footer();

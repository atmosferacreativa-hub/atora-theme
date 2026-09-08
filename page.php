<?php
/**
 * Pagina generica.
 *
 * @package Atora_Them
 */

get_header();
?>

<?php while ( have_posts() ) : ?>
	<?php the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'atora-them-page' ); ?>>
		<?php if ( ! has_blocks() ) : ?>
			<header class="atora-them-page__header">
				<div class="atora-them-container">
					<h1><?php the_title(); ?></h1>
				</div>
			</header>
		<?php endif; ?>
		<div class="atora-them-entry-content">
			<?php the_content(); ?>
		</div>
	</article>
<?php endwhile; ?>

<?php
get_footer();

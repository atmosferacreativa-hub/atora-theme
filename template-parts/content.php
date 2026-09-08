<?php
/**
 * Tarjeta de contenido.
 *
 * @package Atora_Them
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'atora-them-card' ); ?>>
	<a class="atora-them-card__media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'atora-them-card' ); ?>
		<?php else : ?>
			<?php
			$title_initial = get_the_title();
			$title_initial = function_exists( 'mb_substr' ) ? mb_substr( $title_initial, 0, 1 ) : substr( $title_initial, 0, 1 );
			?>
			<span class="atora-them-card__placeholder"><?php echo esc_html( $title_initial ); ?></span>
		<?php endif; ?>
	</a>
	<div class="atora-them-card__body">
		<?php atora_them_entry_meta(); ?>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p><?php echo esc_html( atora_them_card_excerpt() ); ?></p>
		<a class="atora-them-text-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Leer mas', 'atora-them' ); ?></a>
	</div>
</article>

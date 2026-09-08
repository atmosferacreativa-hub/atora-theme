<?php
/**
 * Tarjeta de contenido.
 *
 * @package Atora_Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'atora-theme-card' ); ?>>
	<a class="atora-theme-card__media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'atora-theme-card' ); ?>
		<?php else : ?>
			<?php
			$title_initial = get_the_title();
			$title_initial = function_exists( 'mb_substr' ) ? mb_substr( $title_initial, 0, 1 ) : substr( $title_initial, 0, 1 );
			?>
			<span class="atora-theme-card__placeholder"><?php echo esc_html( $title_initial ); ?></span>
		<?php endif; ?>
	</a>
	<div class="atora-theme-card__body">
		<?php atora_theme_entry_meta(); ?>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p><?php echo esc_html( atora_theme_card_excerpt() ); ?></p>
		<a class="atora-theme-text-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Leer mas', 'atora-theme' ); ?></a>
	</div>
</article>

<?php
/**
 * Comentarios.
 *
 * @package Atora_Theme
 */

if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="atora-theme-comments__box">
	<?php if ( have_comments() ) : ?>
		<h2>
			<?php
			printf(
				esc_html(
					_n(
						'%s comentario',
						'%s comentarios',
						get_comments_number(),
						'atora-theme'
					)
				),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>
		<ol class="comment-list">
			<?php wp_list_comments(); ?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php comment_form(); ?>
</section>

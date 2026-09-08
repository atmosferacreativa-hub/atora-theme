<?php
/**
 * 404.
 *
 * @package Atora_Theme
 */

get_header();
?>

<section class="atora-theme-archive-hero">
	<div class="atora-theme-container">
		<p class="atora-theme-eyebrow"><?php esc_html_e( '404', 'atora-theme' ); ?></p>
		<h1><?php esc_html_e( 'No encontramos esta pagina.', 'atora-theme' ); ?></h1>
		<p><?php esc_html_e( 'Puedes volver al inicio o buscar otro contenido del sitio.', 'atora-theme' ); ?></p>
		<?php get_search_form(); ?>
	</div>
</section>

<?php
get_footer();

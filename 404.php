<?php
/**
 * 404.
 *
 * @package Atora_Them
 */

get_header();
?>

<section class="atora-them-archive-hero">
	<div class="atora-them-container">
		<p class="atora-them-eyebrow"><?php esc_html_e( '404', 'atora-them' ); ?></p>
		<h1><?php esc_html_e( 'No encontramos esta pagina.', 'atora-them' ); ?></h1>
		<p><?php esc_html_e( 'Puedes volver al inicio o buscar otro contenido del sitio.', 'atora-them' ); ?></p>
		<?php get_search_form(); ?>
	</div>
</section>

<?php
get_footer();

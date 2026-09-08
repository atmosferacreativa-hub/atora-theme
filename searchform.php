<?php
/**
 * Formulario de busqueda.
 *
 * @package Atora_Theme
 */
?>

<form role="search" method="get" class="atora-theme-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e( 'Buscar', 'atora-theme' ); ?></span>
		<input type="search" class="atora-theme-search-field" placeholder="<?php esc_attr_e( 'Buscar en el sitio', 'atora-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	</label>
	<button type="submit" class="atora-theme-button"><?php esc_html_e( 'Buscar', 'atora-theme' ); ?></button>
</form>

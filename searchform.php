<?php
/**
 * Formulario de busqueda.
 *
 * @package Atora_Them
 */
?>

<form role="search" method="get" class="atora-them-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e( 'Buscar', 'atora-them' ); ?></span>
		<input type="search" class="atora-them-search-field" placeholder="<?php esc_attr_e( 'Buscar en el sitio', 'atora-them' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	</label>
	<button type="submit" class="atora-them-button"><?php esc_html_e( 'Buscar', 'atora-them' ); ?></button>
</form>

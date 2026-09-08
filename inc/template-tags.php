<?php
/**
 * Helpers de presentacion del theme.
 *
 * @package Atora_Them
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function atora_them_is_canvas_template(): bool {
	// Solo ocultar header/footer cuando el usuario eligio explicitamente
	// un template "canvas" (blank). Evitamos inferirlo por metas de builders,
	// porque eso causa que en paginas normales se pierda el header/footer.
	return is_page_template(
		array(
			'page-templates/canvas.php',
			'elementor_canvas',
		)
	);
}

function atora_them_color_mode(): string {
	$mode = get_theme_mod( 'atora_them_color_mode', 'light' );
	$mode = sanitize_key( (string) $mode );
	return in_array( $mode, array( 'light', 'dark', 'auto' ), true ) ? $mode : 'light';
}

function atora_them_asset_url( string $path ): string {
	return trailingslashit( ATORA_THEM_URI ) . ltrim( $path, '/' );
}

function atora_them_render_editable_template_part( string $area ): bool {
	if ( ! function_exists( 'clms_render_editable_template_part' ) ) {
		return false;
	}

	return (bool) clms_render_editable_template_part( $area );
}

function atora_them_logo_markup(): string {
	if ( has_custom_logo() ) {
		return get_custom_logo();
	}

	// Sin logo personalizado: no forzamos el logo por defecto del theme,
	// el sitio se queda sin logo tal como lo dejo el usuario en el Customizer.
	return '';
}

function atora_them_nav_fallback(): void {
	$pages = wp_list_pages(
		array(
			'title_li' => '',
			'echo'     => false,
			'depth'    => 1,
		)
	);

	if ( ! $pages ) {
		return;
	}

	echo '<ul class="atora-them-menu">' . $pages . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function atora_them_entry_meta(): void {
	?>
	<div class="atora-them-entry-meta">
		<span><?php echo esc_html( get_the_date() ); ?></span>
		<?php if ( has_category() ) : ?>
			<span><?php the_category( ', ' ); ?></span>
		<?php endif; ?>
	</div>
	<?php
}

function atora_them_archive_link( string $post_type, string $fallback_path ): string {
	$link = get_post_type_archive_link( $post_type );
	return $link ? $link : home_url( $fallback_path );
}

function atora_them_card_excerpt( int $words = 22 ): string {
	$excerpt = get_the_excerpt();
	if ( ! $excerpt ) {
		$excerpt = wp_strip_all_tags( get_the_content() );
	}
	return wp_trim_words( $excerpt, $words );
}

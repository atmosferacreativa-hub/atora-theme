<?php
/**
 * Helpers de presentacion del theme.
 *
 * @package Atora_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function atora_theme_get_theme_mod( string $new_key, string $legacy_key, $default = null ) {
	$mods = get_theme_mods();
	if ( is_array( $mods ) ) {
		if ( array_key_exists( $new_key, $mods ) ) {
			return get_theme_mod( $new_key );
		}
		if ( array_key_exists( $legacy_key, $mods ) ) {
			return get_theme_mod( $legacy_key );
		}
	}

	return get_theme_mod( $new_key, get_theme_mod( $legacy_key, $default ) );
}

function atora_theme_is_canvas_template(): bool {
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

function atora_theme_color_mode(): string {
	$mode = atora_theme_get_theme_mod( 'atora_theme_color_mode', 'atora_them_color_mode', 'light' );
	$mode = sanitize_key( (string) $mode );
	return in_array( $mode, array( 'light', 'dark', 'auto' ), true ) ? $mode : 'light';
}

function atora_theme_asset_url( string $path ): string {
	return trailingslashit( ATORA_THEME_URI ) . ltrim( $path, '/' );
}

function atora_theme_render_editable_template_part( string $area ): bool {
	if ( ! function_exists( 'clms_render_editable_template_part' ) ) {
		return false;
	}

	return (bool) clms_render_editable_template_part( $area );
}

function atora_theme_logo_markup(): string {
	if ( has_custom_logo() ) {
		return get_custom_logo();
	}

	// Sin logo personalizado: no forzamos el logo por defecto del theme,
	// el sitio se queda sin logo tal como lo dejo el usuario en el Customizer.
	return '';
}

function atora_theme_nav_fallback(): void {
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

	echo '<ul class="atora-theme-menu">' . $pages . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function atora_theme_entry_meta(): void {
	?>
	<div class="atora-theme-entry-meta">
		<span><?php echo esc_html( get_the_date() ); ?></span>
		<?php if ( has_category() ) : ?>
			<span><?php the_category( ', ' ); ?></span>
		<?php endif; ?>
	</div>
	<?php
}

function atora_theme_archive_link( string $post_type, string $fallback_path ): string {
	$link = get_post_type_archive_link( $post_type );
	return $link ? $link : home_url( $fallback_path );
}

function atora_theme_card_excerpt( int $words = 22 ): string {
	$excerpt = get_the_excerpt();
	if ( ! $excerpt ) {
		$excerpt = wp_strip_all_tags( get_the_content() );
	}
	return wp_trim_words( $excerpt, $words );
}

// Back-compat: nombres anteriores.
if ( ! function_exists( 'atora_them_is_canvas_template' ) ) {
	function atora_them_is_canvas_template(): bool {
		return atora_theme_is_canvas_template();
	}
}
if ( ! function_exists( 'atora_them_color_mode' ) ) {
	function atora_them_color_mode(): string {
		return atora_theme_color_mode();
	}
}
if ( ! function_exists( 'atora_them_asset_url' ) ) {
	function atora_them_asset_url( string $path ): string {
		return atora_theme_asset_url( $path );
	}
}
if ( ! function_exists( 'atora_them_render_editable_template_part' ) ) {
	function atora_them_render_editable_template_part( string $area ): bool {
		return atora_theme_render_editable_template_part( $area );
	}
}
if ( ! function_exists( 'atora_them_logo_markup' ) ) {
	function atora_them_logo_markup(): string {
		return atora_theme_logo_markup();
	}
}
if ( ! function_exists( 'atora_them_nav_fallback' ) ) {
	function atora_them_nav_fallback(): void {
		atora_theme_nav_fallback();
	}
}
if ( ! function_exists( 'atora_them_entry_meta' ) ) {
	function atora_them_entry_meta(): void {
		atora_theme_entry_meta();
	}
}
if ( ! function_exists( 'atora_them_archive_link' ) ) {
	function atora_them_archive_link( string $post_type, string $fallback_path ): string {
		return atora_theme_archive_link( $post_type, $fallback_path );
	}
}
if ( ! function_exists( 'atora_them_card_excerpt' ) ) {
	function atora_them_card_excerpt( int $words = 22 ): string {
		return atora_theme_card_excerpt( $words );
	}
}

<?php
/**
 * ATORA Studio (Theme) — quick access to customizable parts.
 *
 * @package Atora_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a small icon similar in spirit to ATORA, but with a different color.
 */
function atora_theme_studio_icon_data_uri(): string {
	$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">'
		. '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">'
		. '<stop offset="0" stop-color="#7c3aed"/><stop offset="1" stop-color="#2563eb"/>'
		. '</linearGradient></defs>'
		. '<path fill="url(#g)" d="M32 6c14.36 0 26 11.64 26 26S46.36 58 32 58 6 46.36 6 32 17.64 6 32 6z"/>'
		. '<path fill="#fff" d="M22 44l10-28h3l10 28h-4l-2.1-6H28.1L26 44h-4zm7.5-9.3h9L34 21.2l-4.5 13.5z"/>'
		. '</svg>';

	return 'data:image/svg+xml;base64,' . base64_encode( $svg );
}

function atora_theme_studio_edit_link_for_area( string $area ): string {
	$area = sanitize_key( $area );
	$post_id = 0;
	$opt = get_option( 'clms_template_part_active_' . $area, 0 );
	$post_id = absint( $opt );

	// Fallback: listado del CPT (más confiable que Site Editor para CPTs).
	if ( ! $post_id ) {
		return admin_url( 'edit.php?post_type=clms_template_part' );
	}

	// Abrir editor de post (block editor) para el template part activo.
	return admin_url( 'post.php?post=' . $post_id . '&action=edit' );
}

add_action(
	'admin_menu',
	static function (): void {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$capability = 'edit_theme_options';
		$slug       = 'atora-studio';

		add_menu_page(
			__( 'ATORA Studio', 'atora-theme' ),
			__( 'ATORA Studio', 'atora-theme' ),
			$capability,
			$slug,
			'atora_theme_studio_render_page',
			atora_theme_studio_icon_data_uri(),
			61
		);

		add_submenu_page(
			$slug,
			__( 'Header', 'atora-theme' ),
			__( 'Header', 'atora-theme' ),
			$capability,
			'atora-studio-header',
			static function (): void {
				wp_safe_redirect( atora_theme_studio_edit_link_for_area( 'header' ) );
				exit;
			}
		);

		add_submenu_page(
			$slug,
			__( 'Footer', 'atora-theme' ),
			__( 'Footer', 'atora-theme' ),
			$capability,
			'atora-studio-footer',
			static function (): void {
				wp_safe_redirect( atora_theme_studio_edit_link_for_area( 'footer' ) );
				exit;
			}
		);

		add_submenu_page(
			$slug,
			__( 'Plantillas', 'atora-theme' ),
			__( 'Plantillas', 'atora-theme' ),
			$capability,
			'atora-studio-templates',
			static function (): void {
				wp_safe_redirect( admin_url( 'site-editor.php?postType=wp_template' ) );
				exit;
			}
		);

		add_submenu_page(
			$slug,
			__( 'Patrones', 'atora-theme' ),
			__( 'Patrones', 'atora-theme' ),
			$capability,
			'atora-studio-patterns',
			static function (): void {
				wp_safe_redirect( admin_url( 'site-editor.php?path=/patterns' ) );
				exit;
			}
		);

		add_submenu_page(
			$slug,
			__( 'Personalizar', 'atora-theme' ),
			__( 'Personalizar', 'atora-theme' ),
			$capability,
			'atora-studio-customize',
			static function (): void {
				wp_safe_redirect( admin_url( 'customize.php' ) );
				exit;
			}
		);
	},
	20
);

function atora_theme_studio_render_page(): void {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'Sin permisos.', 'atora-theme' ) );
	}

	$header_url = atora_theme_studio_edit_link_for_area( 'header' );
	$footer_url = atora_theme_studio_edit_link_for_area( 'footer' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'ATORA Studio', 'atora-theme' ); ?></h1>
		<p><?php esc_html_e( 'Accesos rápidos para personalizar el tema (header, footer, plantillas y patrones).', 'atora-theme' ); ?></p>

		<div style="max-width:980px;margin-top:12px;">
			<div style="display:flex;gap:10px;align-items:flex-start;padding:10px 12px;border:1px solid #dbe2ea;background:#f8fafc;border-radius:10px;">
				<span class="dashicons dashicons-lock" style="margin-top:2px;color:#64748b;"></span>
				<div>
					<strong style="display:block;margin-bottom:2px;"><?php esc_html_e( 'Tip', 'atora-theme' ); ?></strong>
					<span style="color:#475569;"><?php esc_html_e( 'Si ves candados en una plantilla/patrón, para editarla debes duplicarla y editar la copia.', 'atora-theme' ); ?></span>
				</div>
			</div>
		</div>

		<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px;max-width:980px;margin-top:14px;">
			<a class="button button-primary" style="padding:12px 14px;height:auto;display:flex;flex-direction:column;align-items:flex-start;gap:6px;" href="<?php echo esc_url( $header_url ); ?>">
				<strong><?php esc_html_e( 'Editar Header', 'atora-theme' ); ?></strong>
				<span style="font-weight:400;opacity:.9;"><?php esc_html_e( 'Menú, logo, botones, etc.', 'atora-theme' ); ?></span>
			</a>
			<a class="button button-primary" style="padding:12px 14px;height:auto;display:flex;flex-direction:column;align-items:flex-start;gap:6px;" href="<?php echo esc_url( $footer_url ); ?>">
				<strong><?php esc_html_e( 'Editar Footer', 'atora-theme' ); ?></strong>
				<span style="font-weight:400;opacity:.9;"><?php esc_html_e( 'Links, texto legal, etc.', 'atora-theme' ); ?></span>
			</a>
			<a class="button" style="padding:12px 14px;height:auto;display:flex;flex-direction:column;align-items:flex-start;gap:6px;" href="<?php echo esc_url( admin_url( 'site-editor.php?postType=wp_template' ) ); ?>">
				<strong><?php esc_html_e( 'Plantillas', 'atora-theme' ); ?></strong>
				<span style="font-weight:400;opacity:.85;"><?php esc_html_e( 'Single, Archive, Page, etc.', 'atora-theme' ); ?></span>
			</a>
			<a class="button" style="padding:12px 14px;height:auto;display:flex;flex-direction:column;align-items:flex-start;gap:6px;" href="<?php echo esc_url( admin_url( 'site-editor.php?path=/patterns' ) ); ?>">
				<strong><?php esc_html_e( 'Patrones', 'atora-theme' ); ?></strong>
				<span style="font-weight:400;opacity:.85;"><?php esc_html_e( 'Secciones reutilizables.', 'atora-theme' ); ?></span>
			</a>
			<a class="button" style="padding:12px 14px;height:auto;display:flex;flex-direction:column;align-items:flex-start;gap:6px;" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>">
				<strong><?php esc_html_e( 'Personalizar', 'atora-theme' ); ?></strong>
				<span style="font-weight:400;opacity:.85;"><?php esc_html_e( 'Colores, logo, ajustes del tema.', 'atora-theme' ); ?></span>
			</a>
		</div>
	</div>
	<?php
}

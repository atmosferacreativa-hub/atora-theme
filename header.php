<?php
/**
 * Cabecera del theme.
 *
 * @package Atora_Theme
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="atora-theme-skip" href="#content"><?php esc_html_e( 'Saltar al contenido', 'atora-theme' ); ?></a>

<?php if ( ! atora_theme_is_canvas_template() ) : ?>
	<?php if ( ! atora_theme_render_editable_template_part( 'header' ) ) : ?>
		<header class="atora-theme-header" data-atora-header>
			<div class="atora-theme-header__inner">
				<?php echo atora_theme_logo_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				<button class="atora-theme-nav-toggle" type="button" aria-expanded="false" aria-controls="atora-theme-primary-nav">
					<span></span>
					<span></span>
					<span></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Abrir menu', 'atora-theme' ); ?></span>
				</button>

				<nav class="atora-theme-nav" id="atora-theme-primary-nav" aria-label="<?php esc_attr_e( 'Navegacion principal', 'atora-theme' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_class'     => 'atora-theme-menu',
							'container'      => false,
							'fallback_cb'    => 'atora_theme_nav_fallback',
							'depth'          => 2,
						)
					);
					?>
				</nav>
				<?php
				$cta_enabled = (bool) atora_theme_get_theme_mod( 'atora_theme_header_cta_enabled', 'atora_them_header_cta_enabled', true );
				$cta_label   = atora_theme_get_theme_mod( 'atora_theme_header_cta_label', 'atora_them_header_cta_label', __( 'Ingresar', 'atora-theme' ) );
				$cta_url     = atora_theme_get_theme_mod( 'atora_theme_header_cta_url', 'atora_them_header_cta_url', wp_login_url() );
				if ( $cta_enabled && $cta_label && $cta_url ) :
					?>
					<a class="atora-theme-header__cta" href="<?php echo esc_url( $cta_url ); ?>">
						<?php echo esc_html( $cta_label ); ?>
					</a>
				<?php endif; ?>
			</div>
		</header>
	<?php endif; ?>
<?php endif; ?>

<main id="content" class="atora-theme-site-main">

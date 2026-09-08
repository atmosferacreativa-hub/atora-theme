<?php
/**
 * Cabecera del theme.
 *
 * @package Atora_Them
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
<a class="atora-them-skip" href="#content"><?php esc_html_e( 'Saltar al contenido', 'atora-them' ); ?></a>

<?php if ( ! atora_them_is_canvas_template() ) : ?>
	<?php if ( ! atora_them_render_editable_template_part( 'header' ) ) : ?>
		<header class="atora-them-header" data-atora-header>
			<div class="atora-them-header__inner">
				<?php echo atora_them_logo_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				<button class="atora-them-nav-toggle" type="button" aria-expanded="false" aria-controls="atora-them-primary-nav">
					<span></span>
					<span></span>
					<span></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Abrir menu', 'atora-them' ); ?></span>
				</button>

				<nav class="atora-them-nav" id="atora-them-primary-nav" aria-label="<?php esc_attr_e( 'Navegacion principal', 'atora-them' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_class'     => 'atora-them-menu',
							'container'      => false,
							'fallback_cb'    => 'atora_them_nav_fallback',
							'depth'          => 2,
						)
					);
					?>
				</nav>
				<?php
				$cta_enabled = (bool) get_theme_mod( 'atora_them_header_cta_enabled', true );
				$cta_label   = get_theme_mod( 'atora_them_header_cta_label', __( 'Ingresar', 'atora-them' ) );
				$cta_url     = get_theme_mod( 'atora_them_header_cta_url', wp_login_url() );
				if ( $cta_enabled && $cta_label && $cta_url ) :
					?>
					<a class="atora-them-header__cta" href="<?php echo esc_url( $cta_url ); ?>">
						<?php echo esc_html( $cta_label ); ?>
					</a>
				<?php endif; ?>
			</div>
		</header>
	<?php endif; ?>
<?php endif; ?>

<main id="content" class="atora-them-site-main">

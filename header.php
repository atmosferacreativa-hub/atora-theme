<?php
/**
 * Cabecera.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#site-main"><?php esc_html_e( 'Skip to content', 'atora-learning' ); ?></a>

<header class="meridian-header" data-site-header>
	<div class="meridian-header__rail">
		<div class="meridian-shell meridian-header__rail-inner">
			<div class="meridian-header__rail-nav">
				<?php if ( has_nav_menu( 'top' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'top',
							'container'      => false,
							'menu_class'     => 'meridian-mini-menu',
							'fallback_cb'    => 'atora_nav_fallback',
						)
					);
					?>
				<?php else : ?>
					<nav aria-label="<?php esc_attr_e( 'Utility navigation', 'atora-learning' ); ?>">
						<?php atora_nav_fallback( array( 'menu_class' => 'meridian-mini-menu' ) ); ?>
					</nav>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="meridian-shell meridian-header__main">
		<div class="meridian-header__row meridian-header__row--top">
			<div class="meridian-header__brand">
				<?php echo atora_get_logo_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<button class="meridian-header__toggle" type="button" data-mobile-toggle aria-controls="site-navigation" aria-expanded="false">
				<span></span>
				<span></span>
				<span></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Toggle menu', 'atora-learning' ); ?></span>
			</button>

			<div class="meridian-header__actions">
				<button class="meridian-action meridian-action--search" type="button" data-search-toggle aria-expanded="false" aria-controls="site-search-panel">
					<?php esc_html_e( 'Search', 'atora-learning' ); ?>
				</button>
				<a class="meridian-action" href="<?php echo esc_url( atora_get_account_url() ); ?>"><?php echo is_user_logged_in() ? esc_html__( 'My learning', 'atora-learning' ) : esc_html__( 'Log in', 'atora-learning' ); ?></a>
				<?php if ( atora_has_store() ) : ?>
					<a class="meridian-action meridian-action--cart" href="<?php echo esc_url( atora_get_cart_url() ); ?>">
						<?php esc_html_e( 'Cart', 'atora-learning' ); ?>
						<span class="meridian-cart-count"><?php echo esc_html( number_format_i18n( atora_get_cart_count() ) ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( '' !== atora_get_primary_cta_label() ) : ?>
					<a class="meridian-button meridian-button--primary meridian-button--small meridian-header__cta" href="<?php echo esc_url( atora_get_primary_cta_url() ); ?>">
						<?php echo esc_html( atora_get_primary_cta_label() ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="meridian-header__row meridian-header__row--nav">
			<nav id="site-navigation" class="meridian-header__nav" data-navigation aria-label="<?php esc_attr_e( 'Primary navigation', 'atora-learning' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'meridian-menu',
						'fallback_cb'    => 'atora_nav_fallback',
					)
				);
				?>

				<div class="meridian-header__nav-actions">
					<a class="meridian-action" href="<?php echo esc_url( atora_get_account_url() ); ?>"><?php echo is_user_logged_in() ? esc_html__( 'My learning', 'atora-learning' ) : esc_html__( 'Log in', 'atora-learning' ); ?></a>
					<?php if ( atora_has_store() ) : ?>
						<a class="meridian-action meridian-action--cart" href="<?php echo esc_url( atora_get_cart_url() ); ?>">
							<?php esc_html_e( 'Cart', 'atora-learning' ); ?>
							<span class="meridian-cart-count"><?php echo esc_html( number_format_i18n( atora_get_cart_count() ) ); ?></span>
						</a>
					<?php endif; ?>
					<?php if ( '' !== atora_get_primary_cta_label() ) : ?>
						<a class="meridian-button meridian-button--primary meridian-button--small" href="<?php echo esc_url( atora_get_primary_cta_url() ); ?>">
							<?php echo esc_html( atora_get_primary_cta_label() ); ?>
						</a>
					<?php endif; ?>
				</div>
			</nav>
		</div>
	</div>

	<div id="site-search-panel" class="meridian-search-panel" data-search-panel hidden>
		<div class="meridian-shell meridian-search-panel__inner">
			<div class="meridian-search-panel__copy">
				<p class="meridian-eyebrow"><?php esc_html_e( 'Search', 'atora-learning' ); ?></p>
				<h2><?php esc_html_e( 'Find courses, programs and articles.', 'atora-learning' ); ?></h2>
			</div>

			<div class="meridian-search-panel__form">
				<?php get_search_form(); ?>
				<button class="meridian-button meridian-button--ghost meridian-button--small" type="button" data-search-close>
					<?php esc_html_e( 'Close', 'atora-learning' ); ?>
				</button>
			</div>
		</div>
	</div>
</header>

<<?php echo esc_attr( atora_main_tag() ); ?> id="site-main" class="site-main">

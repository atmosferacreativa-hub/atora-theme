<?php
/**
 * Configuracion principal del theme.
 *
 * @package Atora_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function atora_theme_setup(): void {
	load_theme_textdomain( 'atora-theme', ATORA_THEME_DIR . '/languages' );
	// Back-compat: dominio anterior (si existieran traducciones legacy).
	load_theme_textdomain( 'atora-them', ATORA_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'woocommerce' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 360,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Navegacion principal', 'atora-theme' ),
			'footer'  => __( 'Navegacion del pie', 'atora-theme' ),
			'legal'   => __( 'Navegacion legal', 'atora-theme' ),
		)
	);

	add_image_size( 'atora-theme-card', 720, 520, true );
	add_image_size( 'atora-theme-hero', 1600, 1000, true );
	add_image_size( 'atora-theme-square', 900, 900, true );

	// Back-compat: nombres anteriores de tamaños.
	add_image_size( 'atora-them-card', 720, 520, true );
	add_image_size( 'atora-them-hero', 1600, 1000, true );
	add_image_size( 'atora-them-square', 900, 900, true );

	add_editor_style(
		array(
			'https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Instrument+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&display=swap',
			'assets/css/editor.css',
		)
	);
}
add_action( 'after_setup_theme', 'atora_theme_setup' );

function atora_theme_enqueue_assets(): void {
	wp_enqueue_style(
		'atora-theme-fonts',
		'https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Instrument+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'atora-theme-style',
		get_stylesheet_uri(),
		array(),
		ATORA_THEME_VERSION
	);

	wp_enqueue_style(
		'atora-theme-main',
		atora_theme_asset_url( 'assets/css/main.css' ),
		array( 'atora-theme-style', 'atora-theme-fonts' ),
		ATORA_THEME_VERSION
	);

	if ( atora_theme_should_enqueue_lms_styles() ) {
		wp_enqueue_style(
			'atora-theme-lms',
			atora_theme_asset_url( 'assets/css/atora-lms.css' ),
			array( 'atora-theme-main' ),
			ATORA_THEME_VERSION
		);
	}

	wp_enqueue_script(
		'atora-theme-main',
		atora_theme_asset_url( 'assets/js/main.js' ),
		array(),
		ATORA_THEME_VERSION,
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'atora_theme_enqueue_assets' );

function atora_theme_should_enqueue_lms_styles(): bool {
	// Mantener WooCommerce con estilos por defecto en la tienda (shop/cart/checkout/product).
	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		return false;
	}

	if ( is_singular( array( 'lm_course', 'lm_lesson', 'lm_program', 'atora_teacher' ) ) ) {
		return true;
	}

	if ( is_post_type_archive( array( 'lm_course', 'lm_program' ) ) ) {
		return true;
	}

	// En páginas normales, cargar estilos LMS solo si hay bloques/shortcodes de ATORA.
	if ( is_singular() ) {
		$post = get_post();
		if ( $post instanceof WP_Post ) {
			$content = (string) $post->post_content;

			if ( '' !== trim( $content ) ) {
				// Bloques ATORA (secciones premium + wrapper).
				if ( function_exists( 'has_block' ) ) {
					$atora_blocks = array(
						'atora-lms/section-hero',
						'atora-lms/section-faq',
						'atora-lms/section-instructor',
						'atora-lms/section-curriculum',
						'atora-lms/section-cta',
						'atora-lms/course-template',
					);
					foreach ( $atora_blocks as $block_name ) {
						if ( has_block( $block_name, $content ) ) {
							return true;
						}
					}

					if ( false !== strpos( $content, '<!-- wp:atora-lms/shortcode-' ) ) {
						return true;
					}
				}

				// Shortcodes LMS comunes ([clms_*] y [atora_*]).
				if ( false !== strpos( $content, '[clms_' ) || false !== strpos( $content, '[atora_' ) ) {
					return true;
				}
			}
		}
	}

	return false;
}

function atora_theme_body_classes( array $classes ): array {
	$classes[] = 'atora-theme';
	$classes[] = 'atora-theme-mode-' . atora_theme_color_mode();

	if ( atora_theme_is_canvas_template() ) {
		$classes[] = 'atora-theme-canvas';
	}

	if ( is_front_page() ) {
		$classes[] = 'atora-theme-home';
	}

	if ( is_home() || is_singular( 'post' ) || is_archive() ) {
		$classes[] = 'atora-theme-content';
	}

	if ( is_singular( array( 'lm_course', 'lm_lesson', 'lm_program', 'atora_teacher' ) ) ) {
		$classes[] = 'atora-theme-lms-view';
	}

	if ( is_user_logged_in() ) {
		$classes[] = 'atora-theme-logged-in';
	} else {
		$classes[] = 'atora-theme-guest';
	}

	return $classes;
}
add_filter( 'body_class', 'atora_theme_body_classes' );

function atora_theme_pattern_category(): void {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category(
			'atora-theme',
			array( 'label' => __( 'Atora Theme', 'atora-theme' ) )
		);
	}
}
add_action( 'init', 'atora_theme_pattern_category' );

add_filter(
	'excerpt_length',
	static function ( int $length ): int {
		return is_admin() ? $length : 24;
	},
	99
);

add_filter(
	'excerpt_more',
	static function (): string {
		return '...';
	}
);

// Preconnect para Google Fonts — reduce latencia de carga de fuentes externas.
function atora_theme_preconnect_fonts(): void {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'atora_theme_preconnect_fonts', 1 );

// Hacer non-blocking el CSS principal (atora-theme-main) usando preload + onload.
function atora_theme_async_main_css( $html, $handle, $href, $media ) {
	if ( 'atora-theme-main' === $handle || 'atora-them-main' === $handle ) {
		$html  = "<link rel='preload' href='{$href}' as='style' onload=\"this.onload=null;this.rel='stylesheet'\" media='{$media}'>\n";
		$html .= "<noscript><link rel='stylesheet' href='{$href}' media='{$media}'></noscript>\n";
	}
	return $html;
}
add_filter( 'style_loader_tag', 'atora_theme_async_main_css', 10, 4 );

// Back-compat: nombres anteriores (si algún child theme los usa).
if ( ! function_exists( 'atora_them_setup' ) ) {
	function atora_them_setup(): void {
		atora_theme_setup();
	}
}
if ( ! function_exists( 'atora_them_enqueue_assets' ) ) {
	function atora_them_enqueue_assets(): void {
		atora_theme_enqueue_assets();
	}
}
if ( ! function_exists( 'atora_them_should_enqueue_lms_styles' ) ) {
	function atora_them_should_enqueue_lms_styles(): bool {
		return atora_theme_should_enqueue_lms_styles();
	}
}
if ( ! function_exists( 'atora_them_body_classes' ) ) {
	function atora_them_body_classes( array $classes ): array {
		return atora_theme_body_classes( $classes );
	}
}
if ( ! function_exists( 'atora_them_pattern_category' ) ) {
	function atora_them_pattern_category(): void {
		atora_theme_pattern_category();
	}
}
if ( ! function_exists( 'atora_them_preconnect_fonts' ) ) {
	function atora_them_preconnect_fonts(): void {
		atora_theme_preconnect_fonts();
	}
}
if ( ! function_exists( 'atora_them_async_main_css' ) ) {
	function atora_them_async_main_css( $html, $handle, $href, $media ) {
		return atora_theme_async_main_css( $html, $handle, $href, $media );
	}
}

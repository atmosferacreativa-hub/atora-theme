<?php
/**
 * Identidad visual de la academia (Apariencia → Personalizar → Identidad ATORA).
 *
 * Una sola fuente de color y tipografía para todo el sitio: cabecera, blog,
 * portada y las páginas de ATORA LMS (curso, programa, lección, panel). El
 * plugin pinta con las variables `--atora-*`; aquí se fijan sus valores.
 *
 * Si en ATORA LMS → Ajustes la academia eligió un tema distinto de "Claro"
 * (oscuro, vibrante, pastel, elegante o personalizado), manda el del plugin.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Paletas disponibles. Todos los acentos pasan 4.5:1 con texto blanco.
 *
 * @return array<string,array{label:string,accent:string}>
 */
function atora_identity_palettes() {
	return array(
		'meridian' => array(
			'label'  => __( 'ATORA blue', 'atora-learning' ),
			'accent' => '#1f55e6',
		),
		'ambar'    => array(
			'label'  => __( 'Amber', 'atora-learning' ),
			'accent' => '#a3540a',
		),
		'bosque'   => array(
			'label'  => __( 'Forest', 'atora-learning' ),
			'accent' => '#0f7a5a',
		),
		'vino'     => array(
			'label'  => __( 'Wine', 'atora-learning' ),
			'accent' => '#a3264b',
		),
		'grafito'  => array(
			'label'  => __( 'Institutional graphite', 'atora-learning' ),
			'accent' => '#2b3a55',
		),
		'custom'   => array(
			'label'  => __( 'Custom color', 'atora-learning' ),
			'accent' => '',
		),
	);
}

/**
 * Combinaciones tipográficas. Las fuentes van dentro del tema (sin Google Fonts).
 *
 * @return array<string,array{label:string,display:string,body:string}>
 */
function atora_identity_type_pairs() {
	$system = 'system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';

	return array(
		'editorial' => array(
			'label'   => __( 'Editorial (Sora + Source Sans 3)', 'atora-learning' ),
			'display' => '"Sora", ' . $system,
			'body'    => '"Source Sans 3", ' . $system,
		),
		'clara'     => array(
			'label'   => __( 'Clear (Source Sans 3)', 'atora-learning' ),
			'display' => '"Source Sans 3", ' . $system,
			'body'    => '"Source Sans 3", ' . $system,
		),
		'sistema'   => array(
			'label'   => __( 'System fonts (lightest)', 'atora-learning' ),
			'display' => $system,
			'body'    => $system,
		),
	);
}

/**
 * Tamaños de lectura para el contenido de las lecciones.
 *
 * @return array<string,array{label:string,size:string}>
 */
function atora_identity_reading_sizes() {
	return array(
		'normal'  => array(
			'label' => __( 'Normal (17 px)', 'atora-learning' ),
			'size'  => '1.0625rem',
		),
		'comoda'  => array(
			'label' => __( 'Comfortable (18 px)', 'atora-learning' ),
			'size'  => '1.125rem',
		),
		'grande'  => array(
			'label' => __( 'Large (20 px)', 'atora-learning' ),
			'size'  => '1.25rem',
		),
	);
}

/**
 * Ajustes guardados, ya validados.
 *
 * @return array<string,string>
 */
function atora_identity_settings() {
	$palettes = atora_identity_palettes();
	$pairs    = atora_identity_type_pairs();
	$sizes    = atora_identity_reading_sizes();

	$palette = (string) get_theme_mod( 'atora_palette', 'meridian' );
	$palette = isset( $palettes[ $palette ] ) ? $palette : 'meridian';

	$custom = sanitize_hex_color( (string) get_theme_mod( 'atora_accent_custom', '' ) );
	$accent = 'custom' === $palette && $custom ? $custom : $palettes[ $palette ]['accent'];
	if ( '' === $accent ) {
		$accent = $palettes['meridian']['accent'];
	}

	$type = (string) get_theme_mod( 'atora_type_pair', 'editorial' );
	$type = isset( $pairs[ $type ] ) ? $type : 'editorial';

	$reading = (string) get_theme_mod( 'atora_reading_size', 'comoda' );
	$reading = isset( $sizes[ $reading ] ) ? $reading : 'comoda';

	$mode = (string) get_theme_mod( 'atora_color_mode', 'light' );
	$mode = isset( atora_identity_color_modes()[ $mode ] ) ? $mode : 'light';

	return array(
		'palette' => $palette,
		'accent'  => atora_identity_readable_accent( $accent ),
		'type'    => $type,
		'reading' => $reading,
		'mode'    => $mode,
	);
}

/**
 * Modos de color.
 *
 * @return array<string,string>
 */
function atora_identity_color_modes() {
	return array(
		'light' => __( 'Light', 'atora-learning' ),
		'dark'  => __( 'Dark', 'atora-learning' ),
		'auto'  => __( 'Follow the device (light or dark)', 'atora-learning' ),
	);
}

/**
 * Oscurece un acento hasta que el texto blanco encima tenga contraste 4.5:1.
 *
 * @param string $hex Color #rrggbb.
 * @return string
 */
function atora_identity_readable_accent( $hex ) {
	$rgb = atora_identity_hex_to_rgb( $hex );
	for ( $i = 0; $i < 20 && atora_identity_contrast_with_white( $rgb ) < 4.5; $i++ ) {
		$rgb = array_map(
			static function ( $c ) {
				return (int) floor( $c * 0.9 );
			},
			$rgb
		);
	}

	return atora_identity_rgb_to_hex( $rgb );
}

/**
 * @param string $hex Color.
 * @return int[]
 */
function atora_identity_hex_to_rgb( $hex ) {
	$hex = ltrim( (string) sanitize_hex_color( $hex ), '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if ( 6 !== strlen( $hex ) ) {
		$hex = '1f55e6';
	}

	return array( hexdec( substr( $hex, 0, 2 ) ), hexdec( substr( $hex, 2, 2 ) ), hexdec( substr( $hex, 4, 2 ) ) );
}

/**
 * @param int[] $rgb Color.
 * @return string
 */
function atora_identity_rgb_to_hex( array $rgb ) {
	return sprintf( '#%02x%02x%02x', max( 0, min( 255, $rgb[0] ) ), max( 0, min( 255, $rgb[1] ) ), max( 0, min( 255, $rgb[2] ) ) );
}

/**
 * Contraste WCAG de un color contra el blanco.
 *
 * @param int[] $rgb Color.
 * @return float
 */
function atora_identity_contrast_with_white( array $rgb ) {
	$lum = 0.0;
	foreach ( array( 0.2126, 0.7152, 0.0722 ) as $i => $weight ) {
		$c    = $rgb[ $i ] / 255;
		$c    = $c <= 0.03928 ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
		$lum += $weight * $c;
	}

	return 1.05 / ( $lum + 0.05 );
}

/**
 * Mezcla un color con otro (0 = a, 1 = b).
 *
 * @param string $a     Color base.
 * @param string $b     Color de mezcla.
 * @param float  $ratio Proporción de b.
 * @return string
 */
function atora_identity_mix( $a, $b, $ratio ) {
	$ca = atora_identity_hex_to_rgb( $a );
	$cb = atora_identity_hex_to_rgb( $b );
	$out = array();
	foreach ( array( 0, 1, 2 ) as $i ) {
		$out[] = (int) round( $ca[ $i ] + ( $cb[ $i ] - $ca[ $i ] ) * $ratio );
	}

	return atora_identity_rgb_to_hex( $out );
}

/**
 * Contraste WCAG entre dos colores.
 *
 * @param string $a Color.
 * @param string $b Color.
 * @return float
 */
function atora_identity_contrast( $a, $b ) {
	$lum = static function ( $hex ) {
		$rgb = atora_identity_hex_to_rgb( $hex );
		$l   = 0.0;
		foreach ( array( 0.2126, 0.7152, 0.0722 ) as $i => $weight ) {
			$c  = $rgb[ $i ] / 255;
			$c  = $c <= 0.03928 ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
			$l += $weight * $c;
		}
		return $l;
	};
	$la = $lum( $a );
	$lb = $lum( $b );

	return ( max( $la, $lb ) + 0.05 ) / ( min( $la, $lb ) + 0.05 );
}

/**
 * Aclara un color hasta que se lea sobre un fondo oscuro (contraste mínimo).
 *
 * @param string $hex     Color.
 * @param string $surface Fondo.
 * @param float  $min     Contraste mínimo.
 * @return string
 */
function atora_identity_lighten_for( $hex, $surface, $min ) {
	for ( $r = 0.0; $r <= 0.9; $r += 0.02 ) {
		$candidate = atora_identity_mix( $hex, '#ffffff', $r );
		if ( atora_identity_contrast( $candidate, $surface ) >= $min ) {
			return $candidate;
		}
	}

	return '#ffffff';
}

/**
 * Variables de color: las del acento, para el modo claro u oscuro.
 *
 * @param string $accent Acento validado (texto blanco encima ≥ 4.5:1).
 * @param bool   $dark   Modo oscuro.
 * @return array<string,string>
 */
function atora_identity_accent_tokens( $accent, $dark = false ) {
	if ( ! $dark ) {
		$hover = atora_identity_mix( $accent, '#000000', 0.18 );

		return array(
			'--atora-accent'       => $accent,
			'--atora-accent-hover' => $hover,
			'--atora-accent-soft'  => atora_identity_mix( $accent, '#ffffff', 0.9 ),
			'--atora-accent-ink'   => atora_identity_mix( $accent, '#000000', 0.28 ),
			'--atora-on-accent'    => '#ffffff',
			'--atora-btn-bg'       => $accent,
			'--atora-btn-bg-hover' => $hover,
			'--atora-blue-700'     => $hover,
			'--atora-blue-50'      => atora_identity_mix( $accent, '#ffffff', 0.92 ),
		);
	}

	// Oscuro: el acento se aclara hasta leerse sobre las superficies (4.5:1),
	// y si el blanco ya no contrasta encima, el texto de los botones pasa a tinta.
	$surface = '#171d2b';
	$light   = atora_identity_lighten_for( $accent, $surface, 4.5 );
	$on      = atora_identity_contrast( $light, '#ffffff' ) >= 4.5 ? '#ffffff' : '#0e1320';
	$hover   = atora_identity_mix( $light, '#ffffff', 0.15 );

	return array(
		'--atora-accent'       => $light,
		'--atora-accent-hover' => $hover,
		'--atora-accent-soft'  => atora_identity_mix( $surface, $accent, 0.28 ),
		'--atora-accent-ink'   => atora_identity_lighten_for( $accent, $surface, 7 ),
		'--atora-on-accent'    => $on,
		'--atora-btn-bg'       => $light,
		'--atora-btn-bg-hover' => $hover,
		'--atora-blue-700'     => $hover,
		'--atora-blue-50'      => atora_identity_mix( $surface, $accent, 0.2 ),
	);
}

/**
 * Superficies y textos de la paleta clara de Meridian (los mismos de style.css).
 *
 * @return array<string,string>
 */
function atora_identity_light_palette() {
	return array(
		'--atora-bg'             => '#f8f6f1',
		'--atora-surface'        => '#ffffff',
		'--atora-surface-2'      => '#f2eee6',
		'--atora-text'           => '#172033',
		'--atora-text-primary'   => '#172033',
		'--atora-text-secondary' => '#46526a',
		'--atora-text-muted'     => '#46526a',
		'--atora-text-subtle'    => '#5f6b80',
		'--atora-border'         => '#e3ddd2',
		'--atora-border-soft'    => '#ece7de',
		'--atora-border-strong'  => '#cdc5b6',
		'--atora-highlight'      => '#f2a900',
		'--atora-highlight-soft' => '#fff3d1',
		'--atora-success'        => '#12805c',
		'--atora-success-bg'     => '#e5f6ef',
		'--atora-success-text'   => '#0b5a40',
		'--atora-warning'        => '#b26b00',
		'--atora-warning-bg'     => '#fff4dc',
		'--atora-warning-text'   => '#6e4300',
		'--atora-danger'         => '#c23838',
		'--atora-danger-bg'      => '#fdeeee',
		'--atora-danger-text'    => '#851d1d',
	);
}

/**
 * Paleta oscura de Meridian: tinta azulada, no negro puro, y textos ≥ 7:1.
 *
 * @return array<string,string>
 */
function atora_identity_dark_palette() {
	return array(
		'--atora-bg'             => '#0e1320',
		'--atora-surface'        => '#171d2b',
		'--atora-surface-2'      => '#1f2738',
		'--atora-text'           => '#e8ebf2',
		'--atora-text-primary'   => '#e8ebf2',
		'--atora-text-secondary' => '#b4bccb',
		'--atora-text-muted'     => '#b4bccb',
		'--atora-text-subtle'    => '#939db0',
		'--atora-border'         => '#2c3548',
		'--atora-border-soft'    => '#252d3e',
		'--atora-border-strong'  => '#3c475d',
		'--atora-highlight'      => '#f5b82e',
		'--atora-highlight-soft' => '#3a2d0c',
		'--atora-success'        => '#3ccf91',
		'--atora-success-bg'     => '#10291f',
		'--atora-success-text'   => '#8fe6c0',
		'--atora-warning'        => '#f2a93b',
		'--atora-warning-bg'     => '#33250b',
		'--atora-warning-text'   => '#f7cd85',
		'--atora-danger'         => '#f07070',
		'--atora-danger-bg'      => '#3a1416',
		'--atora-danger-text'    => '#f6aaaa',
		'--meridian-shadow-sm'   => '0 10px 24px rgba(0, 0, 0, 0.35)',
		'--meridian-shadow-md'   => '0 18px 44px rgba(0, 0, 0, 0.45)',
		'--meridian-shadow-lg'   => '0 26px 72px rgba(0, 0, 0, 0.55)',
		'color-scheme'           => 'dark',
	);
}

/**
 * Los colores con nombre del editor (theme.json) siguen al modo oscuro: un
 * bloque con fondo "Blanco" y texto "Tinta" se invierte en lugar de quedar
 * como una isla clara.
 *
 * @return array<string,string>
 */
function atora_identity_dark_presets() {
	return array(
		'--wp--preset--color--white'         => 'var(--atora-surface)',
		'--wp--preset--color--ink'           => 'var(--atora-text)',
		'--wp--preset--color--ink-2'         => 'var(--atora-text-muted)',
		'--wp--preset--color--meridian-ink'  => 'var(--atora-text)',
		'--wp--preset--color--bg-2'          => 'var(--atora-bg)',
		'--wp--preset--color--meridian-sand' => 'var(--atora-surface-2)',
		'--wp--preset--color--meridian-clay' => 'var(--atora-surface-2)',
		'--wp--preset--color--line'          => 'var(--atora-border)',
		'--wp--preset--color--amber-soft'    => 'var(--atora-highlight-soft)',
	);
}

/**
 * Puente con los tokens --ac-* del plugin. El plugin los deriva de --atora-*,
 * pero su dark-mode.css los cambia por grises fijos cuando el teléfono está en
 * modo oscuro, aunque la academia sea clara: salían tarjetas oscuras sobre
 * fondo claro. Aquí vuelven a seguir a --atora-* (claro u oscuro, según el modo).
 *
 * @return array<string,string>
 */
function atora_identity_plugin_bridge_tokens() {
	return array(
		'--ac-brand'    => 'var(--atora-accent)',
		'--ac-brand-dk' => 'var(--atora-accent-hover)',
		'--ac-brand-lt' => 'var(--atora-accent-soft)',
		'--ac-white'    => 'var(--atora-surface)',
		'--ac-gray-50'  => 'var(--atora-bg)',
		'--ac-gray-100' => 'var(--atora-surface-2)',
		'--ac-gray-200' => 'var(--atora-surface)',
		'--ac-gray-300' => 'var(--atora-border)',
		'--ac-gray-400' => 'var(--atora-border-strong)',
		'--ac-gray-500' => 'var(--atora-text-subtle)',
		'--ac-gray-600' => 'var(--atora-text-muted)',
		'--ac-gray-700' => 'var(--atora-text-muted)',
		'--ac-gray-800' => 'var(--atora-text)',
		'--ac-gray-900' => 'var(--atora-text)',
	);
}

/**
 * @param array<string,string> $vars Variables.
 * @return string
 */
function atora_identity_declarations( array $vars ) {
	$css = '';
	foreach ( $vars as $name => $value ) {
		$css .= $name . ':' . $value . ';';
	}

	return $css;
}

/**
 * CSS de la identidad.
 *
 * - `:root`: letra y acento, para cualquier página.
 * - Paleta (clara u oscura) sobre `body.atora-meridian` cuando ATORA LMS usa su
 *   tema "Claro" (o no está activo): el plugin redefine las variables en `body`
 *   y aquí se le gana con más especificidad. Si la academia eligió otro tema en
 *   ATORA LMS → Ajustes (oscuro, vibrante…), manda el del plugin.
 * - Modo "según el dispositivo": la paleta oscura va dentro de
 *   `@media (prefers-color-scheme: dark)`.
 *
 * @return string
 */
function atora_identity_css() {
	$s    = atora_identity_settings();
	$pair = atora_identity_type_pairs()[ $s['type'] ];
	$size = atora_identity_reading_sizes()[ $s['reading'] ]['size'];

	$type = array(
		'--atora-font-display' => $pair['display'],
		'--atora-font-body'    => $pair['body'],
		'--atora-reading-size' => $size,
	);

	$owned  = 'body.atora-meridian.atora-theme-light,body.atora-meridian:not([class*="atora-theme-"])';
	$light  = atora_identity_declarations( atora_identity_light_palette() + atora_identity_accent_tokens( $s['accent'] ) + array( 'color-scheme' => 'light' ) );
	$dark   = atora_identity_declarations( atora_identity_dark_palette() + atora_identity_accent_tokens( $s['accent'], true ) + atora_identity_dark_presets() );
	$bridge = atora_identity_declarations( atora_identity_plugin_bridge_tokens() );

	// Valores ya validados (hex de sanitize_hex_color y listas cerradas).
	$css  = ':root{' . atora_identity_declarations( atora_identity_accent_tokens( $s['accent'] ) ) . atora_identity_declarations( $type ) . '}';
	$css .= 'body.atora-meridian{' . $bridge . '}';

	if ( 'dark' === $s['mode'] ) {
		$css .= $owned . '{' . $dark . '}';
	} else {
		$css .= $owned . '{' . $light . '}';
		if ( 'auto' === $s['mode'] ) {
			$css .= '@media (prefers-color-scheme: dark){' . $owned . '{' . $dark . '}}';
		}
	}

	// Panel del estudiante, panel docente y SpeedGrader todavía traen fondos
	// blancos fijos en el plugin: en modo oscuro se mantienen como tarjetas
	// claras (con su propia paleta clara) en lugar de quedar ilegibles.
	if ( 'light' !== $s['mode'] ) {
		$islands = 'body.atora-meridian .clms-sd,body.atora-meridian .clms-td,body.atora-meridian .clms-sg-wrap';
		$css    .= $islands . '{' . $light . $bridge . '}';
	}

	// Tema oscuro del plugin: los colores con nombre del editor también se invierten.
	$css .= 'body.atora-meridian.atora-theme-dark{' . atora_identity_declarations( atora_identity_dark_presets() ) . '}';

	return $css;
}

/**
 * Color de la barra del navegador en el teléfono (claro y oscuro).
 */
function atora_identity_theme_color_meta() {
	$s = atora_identity_settings();
	if ( 'dark' === $s['mode'] ) {
		echo '<meta name="theme-color" content="#0e1320">' . "\n";
		return;
	}

	echo '<meta name="theme-color" content="#f8f6f1"' . ( 'auto' === $s['mode'] ? ' media="(prefers-color-scheme: light)"' : '' ) . '>' . "\n";
	if ( 'auto' === $s['mode'] ) {
		echo '<meta name="theme-color" content="#0e1320" media="(prefers-color-scheme: dark)">' . "\n";
	}
}
add_action( 'wp_head', 'atora_identity_theme_color_meta', 3 );

/**
 * Imprime la identidad después de las hojas del plugin.
 */
function atora_identity_print_css() {
	echo '<style id="atora-identity">' . atora_identity_css() . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- valores validados arriba.
}
add_action( 'wp_head', 'atora_identity_print_css', 99 );

/**
 * Precarga de las fuentes del tema (solo las que usa la combinación elegida).
 */
function atora_identity_preload_fonts() {
	$type  = atora_identity_settings()['type'];
	$files = array();
	if ( 'editorial' === $type ) {
		$files = array( 'sora-latin-wght.woff2', 'source-sans-3-latin-wght.woff2' );
	} elseif ( 'clara' === $type ) {
		$files = array( 'source-sans-3-latin-wght.woff2' );
	}

	foreach ( $files as $file ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( ATORA_THEME_URI . '/assets/fonts/' . $file )
		);
	}
}
add_action( 'wp_head', 'atora_identity_preload_fonts', 2 );

/**
 * Clases de body para la identidad.
 *
 * @param string[] $classes Clases.
 * @return string[]
 */
function atora_identity_body_class( $classes ) {
	$s         = atora_identity_settings();
	$classes[] = 'atora-type-' . sanitize_html_class( $s['type'] );
	$classes[] = 'atora-mode-' . sanitize_html_class( $s['mode'] );

	return $classes;
}
add_filter( 'body_class', 'atora_identity_body_class' );

/**
 * Personalizador: una sola sección, con lo que de verdad cambia el sitio.
 *
 * @param WP_Customize_Manager $wp_customize Gestor.
 */
function atora_identity_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'atora_identity',
		array(
			'title'       => __( 'ATORA identity', 'atora-learning' ),
			'description' => __( 'Color, type and reading comfort for the whole academy: home, blog, courses, lessons and dashboard. If ATORA LMS → Settings uses a theme other than "Light", that theme rules the academy pages.', 'atora-learning' ),
			'priority'    => 25,
		)
	);

	$palettes = array();
	foreach ( atora_identity_palettes() as $key => $palette ) {
		$palettes[ $key ] = $palette['label'];
	}

	$wp_customize->add_setting(
		'atora_palette',
		array(
			'default'           => 'meridian',
			'sanitize_callback' => static function ( $value ) {
				return array_key_exists( $value, atora_identity_palettes() ) ? $value : 'meridian';
			},
		)
	);
	$wp_customize->add_control(
		'atora_palette',
		array(
			'section' => 'atora_identity',
			'label'   => __( 'Main color', 'atora-learning' ),
			'type'    => 'radio',
			'choices' => $palettes,
		)
	);

	$wp_customize->add_setting(
		'atora_accent_custom',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'atora_accent_custom',
			array(
				'section'         => 'atora_identity',
				'label'           => __( 'Custom color', 'atora-learning' ),
				'description'     => __( 'If it is too light, the theme darkens it just enough for buttons to stay readable.', 'atora-learning' ),
				'active_callback' => static function () {
					return 'custom' === get_theme_mod( 'atora_palette', 'meridian' );
				},
			)
		)
	);

	$wp_customize->add_setting(
		'atora_color_mode',
		array(
			'default'           => 'light',
			'sanitize_callback' => static function ( $value ) {
				return array_key_exists( $value, atora_identity_color_modes() ) ? $value : 'light';
			},
		)
	);
	$wp_customize->add_control(
		'atora_color_mode',
		array(
			'section'     => 'atora_identity',
			'label'       => __( 'Color mode', 'atora-learning' ),
			'description' => __( 'Dark mode rests the eyes when studying at night. "Follow the device" uses each student\'s phone or computer setting.', 'atora-learning' ),
			'type'        => 'radio',
			'choices'     => atora_identity_color_modes(),
		)
	);

	$pairs = array();
	foreach ( atora_identity_type_pairs() as $key => $pair ) {
		$pairs[ $key ] = $pair['label'];
	}
	$wp_customize->add_setting(
		'atora_type_pair',
		array(
			'default'           => 'editorial',
			'sanitize_callback' => static function ( $value ) {
				return array_key_exists( $value, atora_identity_type_pairs() ) ? $value : 'editorial';
			},
		)
	);
	$wp_customize->add_control(
		'atora_type_pair',
		array(
			'section' => 'atora_identity',
			'label'   => __( 'Typeface', 'atora-learning' ),
			'type'    => 'radio',
			'choices' => $pairs,
		)
	);

	$sizes = array();
	foreach ( atora_identity_reading_sizes() as $key => $size ) {
		$sizes[ $key ] = $size['label'];
	}
	$wp_customize->add_setting(
		'atora_reading_size',
		array(
			'default'           => 'comoda',
			'sanitize_callback' => static function ( $value ) {
				return array_key_exists( $value, atora_identity_reading_sizes() ) ? $value : 'comoda';
			},
		)
	);
	$wp_customize->add_control(
		'atora_reading_size',
		array(
			'section'     => 'atora_identity',
			'label'       => __( 'Lesson reading size', 'atora-learning' ),
			'description' => __( 'Lesson text only. Students can zoom further with their browser.', 'atora-learning' ),
			'type'        => 'radio',
			'choices'     => $sizes,
		)
	);

	$wp_customize->add_setting(
		'atora_header_cta_label',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'atora_header_cta_label',
		array(
			'section'     => 'atora_identity',
			'label'       => __( 'Header button: label', 'atora-learning' ),
			'description' => __( 'Empty: "Explore Learning". Type "-" to hide it.', 'atora-learning' ),
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'atora_header_cta_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'atora_header_cta_url',
		array(
			'section'     => 'atora_identity',
			'label'       => __( 'Header button: link', 'atora-learning' ),
			'description' => __( 'Empty: the course catalog.', 'atora-learning' ),
			'type'        => 'url',
		)
	);

	$wp_customize->add_setting(
		'atora_footer_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'atora_footer_text',
		array(
			'section'     => 'atora_identity',
			'label'       => __( 'Footer text', 'atora-learning' ),
			'description' => __( 'Empty: the site tagline (Settings → General).', 'atora-learning' ),
			'type'        => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'atora_blog_vendor_promo',
		array(
			'default'           => false,
			'sanitize_callback' => 'rest_sanitize_boolean',
		)
	);
	$wp_customize->add_control(
		'atora_blog_vendor_promo',
		array(
			'section'     => 'atora_identity',
			'label'       => __( 'Show the ATORA promotion on the blog', 'atora-learning' ),
			'description' => __( 'Only for ATORA\'s own sites (atora.studio, demo). Leave it off in an institution\'s academy.', 'atora-learning' ),
			'type'        => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'atora_identity_customize_register' );

/**
 * Atajo en el menú Apariencia hacia la sección de identidad.
 */
function atora_identity_admin_menu() {
	add_theme_page(
		__( 'ATORA identity', 'atora-learning' ),
		__( 'ATORA identity', 'atora-learning' ),
		'edit_theme_options',
		esc_url( admin_url( 'customize.php?autofocus[section]=atora_identity' ) )
	);
}
add_action( 'admin_menu', 'atora_identity_admin_menu' );

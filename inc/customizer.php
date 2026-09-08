<?php
/**
 * Opciones del personalizador.
 *
 * @package Atora_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function atora_theme_customize_register( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'atora_theme_design',
		array(
			'title'       => __( 'Atora Theme', 'atora-theme' ),
			'description' => __( 'Ajustes visuales globales del tema.', 'atora-theme' ),
			'priority'    => 35,
		)
	);

	$wp_customize->add_setting(
		'atora_theme_color_mode',
		array(
			'default'           => atora_theme_get_theme_mod( 'atora_theme_color_mode', 'atora_them_color_mode', 'light' ),
			'sanitize_callback' => 'atora_theme_sanitize_color_mode',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_theme_color_mode',
		array(
			'type'    => 'select',
			'section' => 'atora_theme_design',
			'label'   => __( 'Modo visual', 'atora-theme' ),
			'choices' => array(
				'light' => __( 'Claro', 'atora-theme' ),
				'dark'  => __( 'Oscuro', 'atora-theme' ),
				'auto'  => __( 'Automatico segun sistema', 'atora-theme' ),
			),
		)
	);

	$wp_customize->add_setting(
		'atora_theme_header_cta_enabled',
		array(
			'default'           => (bool) atora_theme_get_theme_mod( 'atora_theme_header_cta_enabled', 'atora_them_header_cta_enabled', true ),
			'sanitize_callback' => 'atora_theme_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_theme_header_cta_enabled',
		array(
			'type'    => 'checkbox',
			'section' => 'atora_theme_design',
			'label'   => __( 'Mostrar boton de cabecera', 'atora-theme' ),
		)
	);

	$wp_customize->add_setting(
		'atora_theme_header_cta_label',
		array(
			'default'           => atora_theme_get_theme_mod( 'atora_theme_header_cta_label', 'atora_them_header_cta_label', __( 'Ingresar', 'atora-theme' ) ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_theme_header_cta_label',
		array(
			'type'    => 'text',
			'section' => 'atora_theme_design',
			'label'   => __( 'Texto del boton de cabecera', 'atora-theme' ),
		)
	);

	$wp_customize->add_setting(
		'atora_theme_header_cta_url',
		array(
			'default'           => atora_theme_get_theme_mod( 'atora_theme_header_cta_url', 'atora_them_header_cta_url', wp_login_url() ),
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_theme_header_cta_url',
		array(
			'type'    => 'url',
			'section' => 'atora_theme_design',
			'label'   => __( 'URL del boton de cabecera', 'atora-theme' ),
		)
	);

	$wp_customize->add_section(
		'atora_theme_footer',
		array(
			'title'       => __( 'Footer Atora Theme', 'atora-theme' ),
			'description' => __( 'Contenido editable del pie de pagina.', 'atora-theme' ),
			'priority'    => 36,
		)
	);

	$wp_customize->add_setting(
		'atora_theme_footer_layout',
		array(
			'default'           => atora_theme_get_theme_mod( 'atora_theme_footer_layout', 'atora_them_footer_layout', 'full' ),
			'sanitize_callback' => 'atora_theme_sanitize_footer_layout',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_theme_footer_layout',
		array(
			'type'    => 'select',
			'section' => 'atora_theme_footer',
			'label'   => __( 'Formato del footer', 'atora-theme' ),
			'choices' => array(
				'full'    => __( 'Completo', 'atora-theme' ),
				'compact' => __( 'Compacto', 'atora-theme' ),
			),
		)
	);

	$wp_customize->add_setting(
		'atora_theme_footer_text',
		array(
			'default'           => atora_theme_get_theme_mod(
				'atora_theme_footer_text',
				'atora_them_footer_text',
				__( 'Cursos, programas y comunidades creadas con ATORA LMS.', 'atora-theme' )
			),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_theme_footer_text',
		array(
			'type'    => 'textarea',
			'section' => 'atora_theme_footer',
			'label'   => __( 'Texto institucional', 'atora-theme' ),
		)
	);

	$wp_customize->add_setting(
		'atora_theme_footer_credit',
		array(
			'default'           => atora_theme_get_theme_mod(
				'atora_theme_footer_credit',
				'atora_them_footer_credit',
				__( 'Atora Theme / www.atora.studio', 'atora-theme' )
			),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_theme_footer_credit',
		array(
			'type'    => 'text',
			'section' => 'atora_theme_footer',
			'label'   => __( 'Credito inferior', 'atora-theme' ),
		)
	);

	$wp_customize->add_setting(
		'atora_theme_footer_location',
		array(
			'default'           => atora_theme_get_theme_mod( 'atora_theme_footer_location', 'atora_them_footer_location', '' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_theme_footer_location',
		array(
			'type'    => 'text',
			'section' => 'atora_theme_footer',
			'label'   => __( 'Ubicacion', 'atora-theme' ),
		)
	);

	$wp_customize->add_setting(
		'atora_theme_footer_email',
		array(
			'default'           => atora_theme_get_theme_mod( 'atora_theme_footer_email', 'atora_them_footer_email', '' ),
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_theme_footer_email',
		array(
			'type'    => 'email',
			'section' => 'atora_theme_footer',
			'label'   => __( 'Email de contacto', 'atora-theme' ),
		)
	);

	$wp_customize->add_setting(
		'atora_theme_footer_phone',
		array(
			'default'           => atora_theme_get_theme_mod( 'atora_theme_footer_phone', 'atora_them_footer_phone', '' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_theme_footer_phone',
		array(
			'type'    => 'text',
			'section' => 'atora_theme_footer',
			'label'   => __( 'Telefono', 'atora-theme' ),
		)
	);

	$social_links = array(
		'instagram' => __( 'Instagram', 'atora-theme' ),
		'youtube'   => __( 'YouTube', 'atora-theme' ),
		'facebook'  => __( 'Facebook', 'atora-theme' ),
		'tiktok'    => __( 'TikTok', 'atora-theme' ),
	);

	foreach ( $social_links as $key => $label ) {
		$wp_customize->add_setting(
			'atora_theme_footer_' . $key,
			array(
				'default'           => atora_theme_get_theme_mod( 'atora_theme_footer_' . $key, 'atora_them_footer_' . $key, '' ),
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'atora_theme_footer_' . $key,
			array(
				'type'    => 'url',
				'section' => 'atora_theme_footer',
				'label'   => sprintf(
					/* translators: %s: social network name. */
					__( 'URL de %s', 'atora-theme' ),
					$label
				),
			)
		);
	}
}
add_action( 'customize_register', 'atora_theme_customize_register' );

function atora_theme_sanitize_color_mode( string $value ): string {
	$value = sanitize_key( $value );
	return in_array( $value, array( 'light', 'dark', 'auto' ), true ) ? $value : 'light';
}

function atora_theme_sanitize_checkbox( $value ): bool {
	return (bool) $value;
}

function atora_theme_sanitize_footer_layout( string $value ): string {
	$value = sanitize_key( $value );
	return in_array( $value, array( 'full', 'compact' ), true ) ? $value : 'full';
}

// Back-compat: nombres anteriores.
if ( ! function_exists( 'atora_them_customize_register' ) ) {
	function atora_them_customize_register( WP_Customize_Manager $wp_customize ): void {
		atora_theme_customize_register( $wp_customize );
	}
}
if ( ! function_exists( 'atora_them_sanitize_color_mode' ) ) {
	function atora_them_sanitize_color_mode( string $value ): string {
		return atora_theme_sanitize_color_mode( $value );
	}
}
if ( ! function_exists( 'atora_them_sanitize_checkbox' ) ) {
	function atora_them_sanitize_checkbox( $value ): bool {
		return atora_theme_sanitize_checkbox( $value );
	}
}
if ( ! function_exists( 'atora_them_sanitize_footer_layout' ) ) {
	function atora_them_sanitize_footer_layout( string $value ): string {
		return atora_theme_sanitize_footer_layout( $value );
	}
}

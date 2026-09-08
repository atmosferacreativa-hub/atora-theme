<?php
/**
 * Opciones del personalizador.
 *
 * @package Atora_Them
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function atora_them_customize_register( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'atora_them_design',
		array(
			'title'       => __( 'Atora Them', 'atora-them' ),
			'description' => __( 'Ajustes visuales globales del tema.', 'atora-them' ),
			'priority'    => 35,
		)
	);

	$wp_customize->add_setting(
		'atora_them_color_mode',
		array(
			'default'           => 'light',
			'sanitize_callback' => 'atora_them_sanitize_color_mode',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_them_color_mode',
		array(
			'type'    => 'select',
			'section' => 'atora_them_design',
			'label'   => __( 'Modo visual', 'atora-them' ),
			'choices' => array(
				'light' => __( 'Claro', 'atora-them' ),
				'dark'  => __( 'Oscuro', 'atora-them' ),
				'auto'  => __( 'Automatico segun sistema', 'atora-them' ),
			),
		)
	);

	$wp_customize->add_setting(
		'atora_them_header_cta_enabled',
		array(
			'default'           => true,
			'sanitize_callback' => 'atora_them_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_them_header_cta_enabled',
		array(
			'type'    => 'checkbox',
			'section' => 'atora_them_design',
			'label'   => __( 'Mostrar boton de cabecera', 'atora-them' ),
		)
	);

	$wp_customize->add_setting(
		'atora_them_header_cta_label',
		array(
			'default'           => __( 'Ingresar', 'atora-them' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_them_header_cta_label',
		array(
			'type'    => 'text',
			'section' => 'atora_them_design',
			'label'   => __( 'Texto del boton de cabecera', 'atora-them' ),
		)
	);

	$wp_customize->add_setting(
		'atora_them_header_cta_url',
		array(
			'default'           => wp_login_url(),
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_them_header_cta_url',
		array(
			'type'    => 'url',
			'section' => 'atora_them_design',
			'label'   => __( 'URL del boton de cabecera', 'atora-them' ),
		)
	);

	$wp_customize->add_section(
		'atora_them_footer',
		array(
			'title'       => __( 'Footer Atora Them', 'atora-them' ),
			'description' => __( 'Contenido editable del pie de pagina.', 'atora-them' ),
			'priority'    => 36,
		)
	);

	$wp_customize->add_setting(
		'atora_them_footer_layout',
		array(
			'default'           => 'full',
			'sanitize_callback' => 'atora_them_sanitize_footer_layout',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_them_footer_layout',
		array(
			'type'    => 'select',
			'section' => 'atora_them_footer',
			'label'   => __( 'Formato del footer', 'atora-them' ),
			'choices' => array(
				'full'    => __( 'Completo', 'atora-them' ),
				'compact' => __( 'Compacto', 'atora-them' ),
			),
		)
	);

	$wp_customize->add_setting(
		'atora_them_footer_text',
		array(
			'default'           => __( 'Fotografia, comunicacion estrategica y experiencias digitales para marcas y creadores.', 'atora-them' ),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_them_footer_text',
		array(
			'type'    => 'textarea',
			'section' => 'atora_them_footer',
			'label'   => __( 'Texto institucional', 'atora-them' ),
		)
	);

	$wp_customize->add_setting(
		'atora_them_footer_credit',
		array(
			'default'           => __( 'Atora Them / www.atora.studio', 'atora-them' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_them_footer_credit',
		array(
			'type'    => 'text',
			'section' => 'atora_them_footer',
			'label'   => __( 'Credito inferior', 'atora-them' ),
		)
	);

	$wp_customize->add_setting(
		'atora_them_footer_location',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_them_footer_location',
		array(
			'type'    => 'text',
			'section' => 'atora_them_footer',
			'label'   => __( 'Ubicacion', 'atora-them' ),
		)
	);

	$wp_customize->add_setting(
		'atora_them_footer_email',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_them_footer_email',
		array(
			'type'    => 'email',
			'section' => 'atora_them_footer',
			'label'   => __( 'Email de contacto', 'atora-them' ),
		)
	);

	$wp_customize->add_setting(
		'atora_them_footer_phone',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'atora_them_footer_phone',
		array(
			'type'    => 'text',
			'section' => 'atora_them_footer',
			'label'   => __( 'Telefono', 'atora-them' ),
		)
	);

	$social_links = array(
		'instagram' => __( 'Instagram', 'atora-them' ),
		'youtube'   => __( 'YouTube', 'atora-them' ),
		'facebook'  => __( 'Facebook', 'atora-them' ),
		'tiktok'    => __( 'TikTok', 'atora-them' ),
	);

	foreach ( $social_links as $key => $label ) {
		$wp_customize->add_setting(
			'atora_them_footer_' . $key,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'atora_them_footer_' . $key,
			array(
				'type'    => 'url',
				'section' => 'atora_them_footer',
				'label'   => sprintf(
					/* translators: %s: social network name. */
					__( 'URL de %s', 'atora-them' ),
					$label
				),
			)
		);
	}
}
add_action( 'customize_register', 'atora_them_customize_register' );

function atora_them_sanitize_color_mode( string $value ): string {
	$value = sanitize_key( $value );
	return in_array( $value, array( 'light', 'dark', 'auto' ), true ) ? $value : 'light';
}

function atora_them_sanitize_checkbox( $value ): bool {
	return (bool) $value;
}

function atora_them_sanitize_footer_layout( string $value ): string {
	$value = sanitize_key( $value );
	return in_array( $value, array( 'full', 'compact' ), true ) ? $value : 'full';
}

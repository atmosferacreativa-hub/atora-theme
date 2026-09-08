<?php
/**
 * Integracion visual con ATORA LMS.
 *
 * @package Atora_Them
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function atora_them_register_lms_presets(): void {
	if ( ! class_exists( 'CLMS_UI_Template_Presets', false ) ) {
		return;
	}

	CLMS_UI_Template_Presets::register(
		'atora-them-course-commercial',
		array(
			'label'       => __( 'Atora Them: Curso comercial', 'atora-them' ),
			'description' => __( 'Landing comercial con hero, beneficios, temario, testimonios, captura y CTA.', 'atora-them' ),
			'theme'       => atora_them_color_mode(),
			'contexts'    => array( 'course_commercial' ),
			'sections'    => array(
				array( 'id' => 'hero', 'enabled' => true, 'variant' => 'centered' ),
				array( 'id' => 'summary', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'benefits', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'profiles', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'curriculum', 'enabled' => true, 'variant' => 'accordion' ),
				array( 'id' => 'instructor', 'enabled' => true, 'variant' => 'minimal' ),
				array( 'id' => 'testimonials', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'faq', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'crm_lead', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'cta', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'related', 'enabled' => true, 'variant' => 'default' ),
			),
		)
	);

	CLMS_UI_Template_Presets::register(
		'atora-them-course-student',
		array(
			'label'       => __( 'Atora Them: Vista del curso', 'atora-them' ),
			'description' => __( 'Vista del curso enfocada en progreso, ruta de aprendizaje y continuidad.', 'atora-them' ),
			'theme'       => atora_them_color_mode(),
			'contexts'    => array( 'course_overview' ),
			'sections'    => array(
				array( 'id' => 'hero', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'video', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'about', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'academic', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'curriculum', 'enabled' => true, 'variant' => 'accordion' ),
				array( 'id' => 'instructor', 'enabled' => true, 'variant' => 'minimal' ),
				array( 'id' => 'testimonials', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'faq', 'enabled' => true, 'variant' => 'default' ),
			),
		)
	);

	CLMS_UI_Template_Presets::register(
		'atora-them-program',
		array(
			'label'       => __( 'Atora Them: Programa', 'atora-them' ),
			'description' => __( 'Presentacion de programas y diplomados con narrativa comercial y CTA claro.', 'atora-them' ),
			'theme'       => atora_them_color_mode(),
			'contexts'    => array( 'program_commercial', 'program_overview' ),
			'sections'    => array(
				array( 'id' => 'hero', 'enabled' => true, 'variant' => 'centered' ),
				array( 'id' => 'summary', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'academic', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'modules', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'instructor', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'crm_lead', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'cta', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'related', 'enabled' => true, 'variant' => 'default' ),
			),
		)
	);

	CLMS_UI_Template_Presets::register(
		'atora-them-lesson-focus',
		array(
			'label'       => __( 'Atora Them: Leccion movil', 'atora-them' ),
			'description' => __( 'Leccion clara, tactil y enfocada en video, recursos y siguiente paso.', 'atora-them' ),
			'theme'       => atora_them_color_mode(),
			'contexts'    => array( 'lesson' ),
			'sections'    => array(
				array( 'id' => 'breadcrumb', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'header', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'cover', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'videos', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'content', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'resources', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'quiz', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'submission', 'enabled' => true, 'variant' => 'default' ),
				array( 'id' => 'next', 'enabled' => true, 'variant' => 'default' ),
			),
		)
	);
}
add_action( 'clms_ui_register_presets', 'atora_them_register_lms_presets' );

function atora_them_lms_color_scheme( string $scheme ): string {
	$mode = atora_them_color_mode();
	return 'auto' === $mode ? $scheme : $mode;
}
add_filter( 'clms_course_overview_color_scheme', 'atora_them_lms_color_scheme' );

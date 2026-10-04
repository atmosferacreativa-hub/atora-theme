<?php
/**
 * Control de progreso del tema (3.0.10), con `wp eval-file`.
 *
 * Los caminos del tema (atora_get_course_progress(), el puente y el de las
 * plantillas, atora_theme_get_course_progress()) deben dar el mismo número:
 * - con ATORA_PROGRESS_STUB=1 se define atora_lms_get_progress() → 37;
 * - sin el plugin: respaldo con _clms_completed_lessons, y la meta
 *   _clms_progress_{curso} (que el plugin no escribe) se ignora.
 */

$user_id   = (int) wp_insert_user( array( 'user_login' => 'progreso_' . wp_generate_password( 6, false ), 'user_pass' => wp_generate_password(), 'role' => 'subscriber' ) );
$course_id = (int) wp_insert_post( array( 'post_type' => 'post', 'post_status' => 'publish', 'post_title' => 'Curso de control' ) );
// Meta antigua con un valor que no debe aparecer.
update_user_meta( $user_id, '_clms_progress_' . $course_id, 88 );

$expected = getenv( 'ATORA_PROGRESS_STUB' ) ? 37 : 0;
if ( getenv( 'ATORA_PROGRESS_STUB' ) && ! function_exists( 'atora_lms_get_progress' ) ) {
	function atora_lms_get_progress( int $user_id, int $wp_course_id ): int {
		return 37;
	}
}

$helper = atora_get_course_progress( $user_id, $course_id );
$bridge = (int) round( Atora_Theme_Plugin_Bridge::get_course_progress( $course_id, $user_id ) );
// Plantillas: pasan por atora_theme_get_plugin_course_progress(), que el puente define.
$template = function_exists( 'atora_theme_get_course_progress' ) ? (int) round( atora_theme_get_course_progress( $course_id, $user_id ) ) : -1;

wp_delete_user( $user_id );
wp_delete_post( $course_id, true );

if ( $helper !== $expected || $bridge !== $expected || $template !== $expected ) {
	fwrite( STDERR, "Progreso distinto: helper={$helper} bridge={$bridge} plantilla={$template} esperado={$expected}\n" );
	exit( 1 );
}
echo "Progreso coherente: {$helper}\n";

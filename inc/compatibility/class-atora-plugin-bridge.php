<?php
/**
 * Puente único entre Atora Meridian y ATORA LMS.
 *
 * Todo dato académico que muestra el tema pasa por aquí: acceso, progreso,
 * lecciones, programas y enlaces públicos del plugin. Si el plugin no está
 * activo (o es anterior), cada método devuelve un valor neutro: el tema nunca
 * escribe datos del plugin ni reimplementa sus reglas.
 *
 * Reglas (3.1.0):
 * - Acceso a un curso = `CLMS_Helper::user_can_access_course()` (6.33.2+:
 *   matrícula directa o programa vigente que lo contenga, igual que la app).
 * - Progreso = `atora_lms_get_progress()` (6.28.2+, mismo cálculo que la app).
 * - Cursos del estudiante = matrículas + cursos de sus programas vigentes.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// La guarda va con constante: PHP declara la clase al compilar el archivo, así
// que un class_exists() aquí siempre sería verdadero (fallo corregido en 3.0.10).
if ( defined( 'ATORA_THEME_PLUGIN_BRIDGE_LOADED' ) ) {
	return;
}
define( 'ATORA_THEME_PLUGIN_BRIDGE_LOADED', true );

final class Atora_Theme_Plugin_Bridge {

	/**
	 * ¿Está activo ATORA LMS?
	 */
	public static function is_lms_active(): bool {
		return class_exists( 'CLMS_Helper' ) || defined( 'ATORA_LMS_DIR' );
	}

	/**
	 * Comprueba si existe una función del plugin.
	 *
	 * @param mixed $function Nombre de la función.
	 */
	public static function plugin_function_exists( $function ): bool {
		return is_string( $function ) && '' !== trim( $function ) && function_exists( $function );
	}

	/**
	 * Progreso del usuario en un curso (0–100).
	 *
	 * @param int $course_id ID del post `lm_course`.
	 * @param int $user_id   Usuario (0 = actual).
	 */
	public static function get_course_progress( $course_id, $user_id = 0 ): float {
		$course_id = absint( $course_id );
		$user_id   = self::normalize_user_id( $user_id );

		if ( ! $course_id || ! $user_id ) {
			return 0.0;
		}

		return (float) atora_get_course_progress( $user_id, $course_id );
	}

	/**
	 * ¿Puede el usuario entrar al curso? Misma regla que la web del plugin y la app.
	 *
	 * @param int $course_id ID del post `lm_course`.
	 * @param int $user_id   Usuario (0 = actual).
	 */
	public static function user_has_course_access( $course_id, $user_id = 0 ): bool {
		$course_id = absint( $course_id );
		$user_id   = self::normalize_user_id( $user_id );

		if ( ! $course_id || ! $user_id || ! class_exists( 'CLMS_Helper' ) ) {
			return false;
		}

		if ( method_exists( 'CLMS_Helper', 'user_can_access_course' ) ) {
			return (bool) CLMS_Helper::user_can_access_course( $user_id, $course_id );
		}

		return method_exists( 'CLMS_Helper', 'user_is_enrolled_in_course' )
			&& (bool) CLMS_Helper::user_is_enrolled_in_course( $user_id, $course_id );
	}

	/**
	 * Cursos del estudiante: matrícula directa y cursos de sus programas vigentes.
	 *
	 * @param int $user_id Usuario.
	 * @return int[] IDs de posts `lm_course` publicados.
	 */
	public static function get_user_course_ids( $user_id ): array {
		$user_id = absint( $user_id );
		if ( ! $user_id || ! class_exists( 'CLMS_Helper' ) ) {
			return array();
		}

		$ids = method_exists( 'CLMS_Helper', 'get_user_enrolled_courses' )
			? (array) CLMS_Helper::get_user_enrolled_courses( $user_id )
			: array();

		// 6.33.2+: acceso por inscripción a un programa.
		if ( class_exists( 'ATORA_Course_Access_Service' ) && method_exists( 'ATORA_Course_Access_Service', 'program_courses' ) ) {
			$ids = array_merge( $ids, array_keys( (array) ATORA_Course_Access_Service::program_courses( $user_id ) ) );
		}

		$ids = self::normalize_id_list( $ids );

		return array_values(
			array_filter(
				$ids,
				static function ( $id ) {
					return 'lm_course' === get_post_type( $id ) && 'publish' === get_post_status( $id );
				}
			)
		);
	}

	/**
	 * Programas vigentes del estudiante.
	 *
	 * @param int $user_id Usuario.
	 * @return int[]
	 */
	public static function get_user_program_ids( $user_id ): array {
		$user_id = absint( $user_id );
		if ( ! $user_id || ! class_exists( 'CLMS_Helper' ) || ! method_exists( 'CLMS_Helper', 'get_user_enrolled_programs' ) ) {
			return array();
		}

		return self::normalize_id_list( CLMS_Helper::get_user_enrolled_programs( $user_id ) );
	}

	/**
	 * IDs de lecciones del curso, en el orden del plugin.
	 *
	 * @param int $course_id ID del post `lm_course`.
	 * @return int[]
	 */
	public static function get_course_lessons( $course_id ): array {
		$course_id = absint( $course_id );
		if ( ! $course_id || ! class_exists( 'CLMS_Helper' ) || ! method_exists( 'CLMS_Helper', 'get_course_lessons' ) ) {
			return array();
		}

		return self::normalize_id_list( CLMS_Helper::get_course_lessons( $course_id ) );
	}

	/**
	 * IDs de cursos de un programa, en el orden del plugin.
	 *
	 * @param int $program_id ID del post `lm_program`.
	 * @return int[]
	 */
	public static function get_program_courses( $program_id ): array {
		$program_id = absint( $program_id );
		if ( ! $program_id || ! class_exists( 'CLMS_Helper' ) || ! method_exists( 'CLMS_Helper', 'get_program_courses' ) ) {
			return array();
		}

		return self::normalize_id_list( CLMS_Helper::get_program_courses( $program_id ) );
	}

	/**
	 * Curso al que pertenece una lección.
	 *
	 * @param int $lesson_id ID del post `lm_lesson`.
	 */
	public static function get_lesson_course_id( $lesson_id ): int {
		$lesson_id = absint( $lesson_id );
		if ( ! $lesson_id || ! class_exists( 'CLMS_Helper' ) || ! method_exists( 'CLMS_Helper', 'get_course_id_from_lesson' ) ) {
			return 0;
		}

		return absint( CLMS_Helper::get_course_id_from_lesson( $lesson_id ) );
	}

	/**
	 * IDs de docentes (posts `atora_teacher`) asignados al curso.
	 *
	 * @param int $course_id ID del post `lm_course`.
	 * @return int[]
	 */
	public static function get_course_teachers( $course_id ): array {
		$course_id = absint( $course_id );
		if ( ! $course_id ) {
			return array();
		}

		return self::normalize_id_list( get_post_meta( $course_id, '_clms_course_teacher_ids', true ) );
	}

	/**
	 * Página pública para verificar certificados (`/verificar/`), o ''.
	 */
	public static function get_certificate_verify_url(): string {
		if ( ! class_exists( 'ATORA_Certificate_Verify' ) ) {
			return '';
		}

		return get_option( 'permalink_structure' )
			? home_url( '/verificar/' )
			: add_query_arg( ATORA_Certificate_Verify::QUERY_VAR, '__form', home_url( '/' ) );
	}

	/**
	 * Página pública para pedir la eliminación de la cuenta (la exigen las tiendas), o ''.
	 */
	public static function get_account_deletion_url(): string {
		if ( ! class_exists( 'ATORA_Account_Deletion' ) || ! method_exists( 'ATORA_Account_Deletion', 'public_url' ) ) {
			return '';
		}

		return (string) ATORA_Account_Deletion::public_url();
	}

	/**
	 * Usuario indicado o el actual.
	 *
	 * @param mixed $user_id Usuario.
	 */
	private static function normalize_user_id( $user_id ): int {
		$user_id = absint( $user_id );

		return $user_id > 0 ? $user_id : absint( get_current_user_id() );
	}

	/**
	 * Convierte cualquier lista de IDs en int[] sin ceros ni duplicados.
	 *
	 * @param mixed $value Lista.
	 * @return int[]
	 */
	private static function normalize_id_list( $value ): array {
		if ( ! is_array( $value ) ) {
			$value = ! empty( $value ) ? maybe_unserialize( $value ) : array();
		}

		if ( ! is_array( $value ) ) {
			return array();
		}

		return array_values( array_unique( array_filter( array_map( 'absint', $value ) ) ) );
	}
}

/*
 * Envolturas públicas. Se mantienen los nombres de 3.0.x para temas hijos.
 */

if ( ! function_exists( 'atora_theme_is_lms_active' ) ) {
	function atora_theme_is_lms_active() {
		return Atora_Theme_Plugin_Bridge::is_lms_active();
	}
}

if ( ! function_exists( 'atora_theme_plugin_function_exists' ) ) {
	function atora_theme_plugin_function_exists( $function ) {
		return Atora_Theme_Plugin_Bridge::plugin_function_exists( $function );
	}
}

if ( ! function_exists( 'atora_theme_get_plugin_course_progress' ) ) {
	function atora_theme_get_plugin_course_progress( $course_id, $user_id = 0 ) {
		return Atora_Theme_Plugin_Bridge::get_course_progress( $course_id, $user_id );
	}
}

if ( ! function_exists( 'atora_theme_get_course_progress' ) ) {
	function atora_theme_get_course_progress( $course_id, $user_id = null ) {
		return Atora_Theme_Plugin_Bridge::get_course_progress( $course_id, (int) $user_id );
	}
}

if ( ! function_exists( 'atora_theme_plugin_user_has_access' ) ) {
	function atora_theme_plugin_user_has_access( $course_id, $user_id = 0 ) {
		return Atora_Theme_Plugin_Bridge::user_has_course_access( $course_id, $user_id );
	}
}

if ( ! function_exists( 'atora_theme_user_has_course_access' ) ) {
	function atora_theme_user_has_course_access( $course_id, $user_id = null ) {
		return Atora_Theme_Plugin_Bridge::user_has_course_access( $course_id, (int) $user_id );
	}
}

if ( ! function_exists( 'atora_theme_plugin_get_course_lessons' ) ) {
	function atora_theme_plugin_get_course_lessons( $course_id ) {
		return Atora_Theme_Plugin_Bridge::get_course_lessons( $course_id );
	}
}

if ( ! function_exists( 'atora_theme_plugin_get_course_teachers' ) ) {
	function atora_theme_plugin_get_course_teachers( $course_id ) {
		return Atora_Theme_Plugin_Bridge::get_course_teachers( $course_id );
	}
}

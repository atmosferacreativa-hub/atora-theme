<?php
/**
 * Traducciones del tema.
 *
 * Archivos estándar de WordPress en `languages/` (`{locale}.po` / `.mo`), los
 * mismos idiomas que ATORA LMS: inglés (texto original), es_ES, es_MX, fr_FR,
 * de_DE, pt_BR, pt_PT, nl_NL, sv_SE, ar y fi. Plantilla: `atora-learning.pot`.
 *
 * Si el sitio usa una variante sin archivo propio (es_VE, es_CO, fr_CA,
 * pt_AO, de_AT…), se carga la del mismo idioma.
 *
 * Para actualizar: `wp i18n make-pot . languages/atora-learning.pot …`,
 * traducir los `.po` (Poedit o similar) y `wp i18n make-mo languages`.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Archivo de traducción del mismo idioma cuando la variante regional no tiene uno.
 *
 * @param string $locale Locale del sitio o del usuario.
 * @return string Ruta del .mo, o '' si no hay.
 */
function atora_theme_fallback_mofile( $locale ) {
	$dir = ATORA_THEME_DIR . '/languages/';

	if ( is_readable( $dir . $locale . '.mo' ) ) {
		return $dir . $locale . '.mo';
	}

	$fallbacks = array(
		'es' => array( 'es_ES', 'es_MX' ),
		'pt' => array( 'pt_BR', 'pt_PT' ),
		'fr' => array( 'fr_FR' ),
		'de' => array( 'de_DE' ),
		'nl' => array( 'nl_NL' ),
		'sv' => array( 'sv_SE' ),
		'ar' => array( 'ar' ),
		'fi' => array( 'fi' ),
	);
	// Hispanoamérica: el español de México está más cerca que el de España.
	if ( 0 === strpos( $locale, 'es_' ) && 'es_ES' !== $locale ) {
		$fallbacks['es'] = array( 'es_MX', 'es_ES' );
	}

	$language = strtolower( substr( (string) $locale, 0, 2 ) );
	foreach ( isset( $fallbacks[ $language ] ) ? $fallbacks[ $language ] : array() as $candidate ) {
		if ( is_readable( $dir . $candidate . '.mo' ) ) {
			return $dir . $candidate . '.mo';
		}
	}

	return '';
}

/**
 * Carga la traducción del tema (con la variante del mismo idioma si hace falta).
 */
function atora_theme_load_translations() {
	load_theme_textdomain( 'atora-learning', ATORA_THEME_DIR . '/languages' );

	if ( is_textdomain_loaded( 'atora-learning' ) ) {
		return;
	}

	$locale = function_exists( 'determine_locale' ) ? determine_locale() : get_locale();
	$mofile = atora_theme_fallback_mofile( $locale );
	if ( '' !== $mofile ) {
		load_textdomain( 'atora-learning', $mofile, $locale );
	}
}
add_action( 'after_setup_theme', 'atora_theme_load_translations', 1 );

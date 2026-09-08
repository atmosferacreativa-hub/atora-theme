<?php
/**
 * Bootstrap de Atora Them.
 *
 * @package Atora_Them
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ATORA_THEM_VERSION', '1.1.0' );
define( 'ATORA_THEM_DIR', get_template_directory() );
define( 'ATORA_THEM_URI', get_template_directory_uri() );

require_once ATORA_THEM_DIR . '/inc/template-tags.php';
require_once ATORA_THEM_DIR . '/inc/setup.php';
require_once ATORA_THEM_DIR . '/inc/customizer.php';
require_once ATORA_THEM_DIR . '/inc/admin.php';
require_once ATORA_THEM_DIR . '/inc/atora-lms.php';


/* ─── Anti-duplicados de posts (v1.0.4) ─── */
/**
 * Evita que los sticky posts aparezcan dos veces en home/archives.
 * Por defecto WordPress los muestra arriba Y vuelve a colarlos en su
 * posición cronológica del loop principal. Aquí los excluímos del
 * orden cronológico (siguen apareciendo arriba como sticky).
 */
add_action( 'pre_get_posts', function ( $q ) {
	if ( is_admin() || ! $q->is_main_query() ) {
		return;
	}
	if ( $q->is_home() || $q->is_archive() ) {
		$stickies = get_option( 'sticky_posts' );
		if ( ! empty( $stickies ) ) {
			$q->set( 'post__not_in', array_merge(
				(array) $q->get( 'post__not_in' ),
				$stickies
			) );
		}
	}
} );

/**
 * Helper de deduplicación para Query Loops manuales, shortcodes,
 * widgets o cualquier código que renderice posts.
 *
 * Uso:
 *
 *   while ( $custom_query->have_posts() ) {
 *       $custom_query->the_post();
 *       if ( atora_them_seen_post( get_the_ID() ) ) {
 *           continue;
 *       }
 *       get_template_part( 'template-parts/content' );
 *   }
 *
 * Devuelve true la segunda vez que ve el mismo ID en la misma carga
 * de página; false la primera (y registra el ID).
 */
function atora_them_seen_post( $post_id ) {
	static $seen = array();
	if ( in_array( $post_id, $seen, true ) ) {
		return true;
	}
	$seen[] = $post_id;
	return false;
}

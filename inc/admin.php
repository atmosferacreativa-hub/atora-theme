<?php
/**
 * Ajustes del admin para Atora Them.
 *
 * @package Atora_Them
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Oculta el metabox nativo "Campos personalizados" de WordPress.
 *
 * ATORA usa sus propios metaboxes y servicios de post meta; esto solo retira
 * la caja generica del editor para evitar ediciones accidentales.
 */
function atora_them_remove_custom_fields_metabox(): void {
	$post_types = get_post_types( array(), 'names' );

	foreach ( $post_types as $post_type ) {
		remove_meta_box( 'postcustom', $post_type, 'normal' );
		remove_meta_box( 'postcustom', $post_type, 'advanced' );
	}
}
add_action( 'add_meta_boxes', 'atora_them_remove_custom_fields_metabox', 99 );
add_action( 'admin_menu', 'atora_them_remove_custom_fields_metabox', 99 );

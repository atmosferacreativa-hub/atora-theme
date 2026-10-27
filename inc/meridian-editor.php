<?php
/**
 * Meridian editor integration, patterns and presentation controls.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const ATORA_PRESENTATION_META = '_atora_meridian_presentation';

/**
 * Default presentation settings for editable screens.
 *
 * @return array<string,mixed>
 */
function atora_get_presentation_defaults() {
	return array(
		'eyebrow'               => '',
		'intro'                 => '',
		'primary_label'         => '',
		'primary_url'           => '',
		'secondary_label'       => '',
		'secondary_url'         => '',
		'hide_hero'             => false,
		'hide_sidebar'          => false,
		'show_curated_sections' => false,
	);
}

/**
 * Get saved presentation settings for a post.
 *
 * @param int $post_id Post ID.
 * @return array<string,mixed>
 */
function atora_get_presentation_settings( $post_id = 0 ) {
	$post_id  = $post_id ? absint( $post_id ) : get_the_ID();
	$saved    = get_post_meta( $post_id, ATORA_PRESENTATION_META, true );
	$defaults = atora_get_presentation_defaults();

	if ( ! is_array( $saved ) ) {
		return $defaults;
	}

	$settings = wp_parse_args( $saved, $defaults );

	$settings['hide_hero']             = ! empty( $settings['hide_hero'] );
	$settings['hide_sidebar']          = ! empty( $settings['hide_sidebar'] );
	$settings['show_curated_sections'] = ! empty( $settings['show_curated_sections'] );

	return $settings;
}

/**
 * Normalize and sanitize presentation settings before persistence.
 *
 * @param array<string,mixed> $raw Raw settings.
 * @return array<string,mixed>
 */
function atora_sanitize_presentation_settings( array $raw ) {
	$defaults = atora_get_presentation_defaults();

	return array(
		'eyebrow'               => isset( $raw['eyebrow'] ) ? sanitize_text_field( (string) $raw['eyebrow'] ) : $defaults['eyebrow'],
		'intro'                 => isset( $raw['intro'] ) ? sanitize_textarea_field( (string) $raw['intro'] ) : $defaults['intro'],
		'primary_label'         => isset( $raw['primary_label'] ) ? sanitize_text_field( (string) $raw['primary_label'] ) : $defaults['primary_label'],
		'primary_url'           => isset( $raw['primary_url'] ) ? esc_url_raw( (string) $raw['primary_url'] ) : $defaults['primary_url'],
		'secondary_label'       => isset( $raw['secondary_label'] ) ? sanitize_text_field( (string) $raw['secondary_label'] ) : $defaults['secondary_label'],
		'secondary_url'         => isset( $raw['secondary_url'] ) ? esc_url_raw( (string) $raw['secondary_url'] ) : $defaults['secondary_url'],
		'hide_hero'             => ! empty( $raw['hide_hero'] ),
		'hide_sidebar'          => ! empty( $raw['hide_sidebar'] ),
		'show_curated_sections' => ! empty( $raw['show_curated_sections'] ),
	);
}

/**
 * Determine whether a settings payload contains real visual overrides.
 *
 * @param array<string,mixed> $settings Presentation settings.
 * @return bool
 */
function atora_presentation_has_visual_overrides( array $settings ) {
	$defaults   = atora_get_presentation_defaults();
	$normalized = wp_parse_args( $settings, $defaults );

	$normalized['hide_hero']             = ! empty( $normalized['hide_hero'] );
	$normalized['hide_sidebar']          = ! empty( $normalized['hide_sidebar'] );
	$normalized['show_curated_sections'] = ! empty( $normalized['show_curated_sections'] );

	return ! empty( array_diff_assoc( $normalized, $defaults ) );
}

/**
 * Detect whether the user explicitly wants Meridian to render a visual shell.
 *
 * @param array<string,mixed> $settings Presentation settings.
 * @return bool
 */
function atora_presentation_requests_visual_shell( array $settings ) {
	return ! empty( $settings['eyebrow'] )
		|| ! empty( $settings['intro'] )
		|| ! empty( $settings['show_curated_sections'] )
		|| ( ! empty( $settings['primary_label'] ) && ! empty( $settings['primary_url'] ) )
		|| ( ! empty( $settings['secondary_label'] ) && ! empty( $settings['secondary_url'] ) );
}

/**
 * Render a normalized CTA model using defaults when custom values are missing.
 *
 * @param array<string,mixed> $settings Presentation settings.
 * @param array<int,array<string,string>> $defaults Default CTA list.
 * @return array<int,array<string,string>>
 */
function atora_get_presentation_actions( array $settings, array $defaults ) {
	$actions = array();

	if ( ! empty( $settings['primary_label'] ) && ! empty( $settings['primary_url'] ) ) {
		$actions[] = array(
			'label' => (string) $settings['primary_label'],
			'url'   => (string) $settings['primary_url'],
			'kind'  => 'primary',
		);
	}

	if ( ! empty( $settings['secondary_label'] ) && ! empty( $settings['secondary_url'] ) ) {
		$actions[] = array(
			'label' => (string) $settings['secondary_label'],
			'url'   => (string) $settings['secondary_url'],
			'kind'  => 'ghost',
		);
	}

	return ! empty( $actions ) ? $actions : $defaults;
}

/**
 * Detect whether a post has meaningful editor content, including media-only layouts.
 *
 * @param int|WP_Post|null $post Post object or ID.
 * @return bool
 */
function atora_post_has_editor_content( $post = null ) {
	$post = get_post( $post );

	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	$content = trim( (string) $post->post_content );
	if ( '' === $content ) {
		return false;
	}

	$content_without_comments = preg_replace( '/<!--[\s\S]*?-->/', '', $content );
	$content_without_comments = is_string( $content_without_comments ) ? trim( $content_without_comments ) : '';

	return '' !== $content_without_comments;
}

/**
 * Register editor supports and patterns.
 *
 * @return void
 */
function atora_register_editor_integration() {
	add_post_type_support( 'page', 'excerpt' );

	// La paleta del editor vive en theme.json (incluye los slugs meridian-* de 3.0.x).
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name' => __( 'Small', 'atora-learning' ),
				'size' => 14,
				'slug' => 'small',
			),
			array(
				'name' => __( 'Base', 'atora-learning' ),
				'size' => 18,
				'slug' => 'base',
			),
			array(
				'name' => __( 'Large', 'atora-learning' ),
				'size' => 24,
				'slug' => 'large',
			),
			array(
				'name' => __( 'Display', 'atora-learning' ),
				'size' => 48,
				'slug' => 'display',
			),
		)
	);

	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category(
			'atora-meridian',
			array(
				'label' => __( 'ATORA: pages', 'atora-learning' ),
			)
		);
	}

	if ( function_exists( 'register_block_pattern' ) ) {
		register_block_pattern(
			'atora-meridian/landing-hero',
			array(
				'title'       => __( 'Course or program presentation', 'atora-learning' ),
				'description' => __( 'Opening block for a page that presents a course or program, with two buttons.', 'atora-learning' ),
				'categories'  => array( 'atora-meridian' ),
				'content'     => sprintf(
					'<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"48px","bottom":"48px","right":"40px","left":"40px"},"blockGap":"24px"},"border":{"radius":"28px"}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-white-background-color has-background" style="border-radius:28px;padding-top:48px;padding-right:40px;padding-bottom:48px;padding-left:40px"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em","fontSize":"12px"}},"textColor":"meridian-blue"} -->
<p class="has-meridian-blue-color has-text-color" style="font-size:12px;letter-spacing:0.08em;text-transform:uppercase">%1$s</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"fontSize":"display"} -->
<h1 class="wp-block-heading has-display-font-size">%2$s</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"base"} -->
<p class="has-base-font-size">%3$s</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"meridian-blue","textColor":"white"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-meridian-blue-background-color has-text-color has-background wp-element-button">%4$s</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">%5$s</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
					esc_html__( 'New cohort', 'atora-learning' ),
					esc_html__( 'Name of the course or program', 'atora-learning' ),
					esc_html__( 'In one or two sentences: who it is for, what they will be able to do at the end and how long it takes.', 'atora-learning' ),
					esc_html__( 'Enroll', 'atora-learning' ),
					esc_html__( 'See the syllabus', 'atora-learning' )
				),
			)
		);

		register_block_pattern(
			'atora-meridian/editorial-intro',
			array(
				'title'       => __( 'Article introduction', 'atora-learning' ),
				'description' => __( 'Introduction for articles, guides and institutional pages.', 'atora-learning' ),
				'categories'  => array( 'atora-meridian' ),
				'content'     => sprintf(
					'<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"40px","bottom":"40px","right":"32px","left":"32px"},"blockGap":"20px"},"border":{"radius":"24px"}},"backgroundColor":"meridian-sand","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-meridian-sand-background-color has-background" style="border-radius:24px;padding-top:40px;padding-right:32px;padding-bottom:40px;padding-left:32px"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.08em","fontSize":"12px"}},"textColor":"meridian-blue"} -->
<p class="has-meridian-blue-color has-text-color" style="font-size:12px;letter-spacing:0.08em;text-transform:uppercase">%1$s</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"fontSize":"large"} -->
<h2 class="wp-block-heading has-large-font-size">%2$s</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"fontSize":"base"} -->
<p class="has-base-font-size">%3$s</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
					esc_html__( 'Guide', 'atora-learning' ),
					esc_html__( 'Start with the main idea of the article', 'atora-learning' ),
					esc_html__( 'Explain in a few lines what the reader will find and why it matters.', 'atora-learning' )
				),
			)
		);

		register_block_pattern(
			'atora-meridian/cta-band',
			array(
				'title'       => __( 'Closing invitation', 'atora-learning' ),
				'description' => __( 'Band with a button to close a page.', 'atora-learning' ),
				'categories'  => array( 'atora-meridian' ),
				'content'     => sprintf(
					'<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"32px","bottom":"32px","right":"32px","left":"32px"},"blockGap":"16px"},"border":{"radius":"24px"}},"backgroundColor":"meridian-blue","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-white-color has-meridian-blue-background-color has-text-color has-background" style="border-radius:24px;padding-top:32px;padding-right:32px;padding-bottom:32px;padding-left:32px"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">%1$s</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>%2$s</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"textColor":"meridian-blue","backgroundColor":"white"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-meridian-blue-color has-white-background-color has-text-color has-background wp-element-button">%3$s</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
					esc_html__( 'Ready to start?', 'atora-learning' ),
					esc_html__( 'Tell the reader what to do next and link the button to the course, program or contact page.', 'atora-learning' ),
					esc_html__( 'Get started', 'atora-learning' )
				),
			)
		);
	}
}
add_action( 'after_setup_theme', 'atora_register_editor_integration', 20 );

/**
 * Bloques de ATORA LMS en páginas y entradas.
 *
 * Los bloques de *estructura de curso* (secciones y plantillas de curso) solo
 * tienen sentido dentro de un curso, así que se ocultan del insertador en
 * páginas y entradas. Los bloques de *función* del plugin (panel del
 * estudiante, catálogo, mis cursos, acceso, formularios…, `atora-lms/shortcode-*`)
 * son justamente para páginas y siguen disponibles.
 *
 * 3.1.0: el tema ya no borra bloques al guardar. Antes quitaba en silencio
 * cualquier `atora-lms/*` de páginas y entradas, incluido el panel.
 *
 * @param bool|string[]           $allowed_blocks Lista permitida.
 * @param WP_Block_Editor_Context $editor_context Contexto.
 * @return bool|string[]
 */
function atora_filter_allowed_block_types( $allowed_blocks, $editor_context ) {
	if ( empty( $editor_context->post ) || ! $editor_context->post instanceof WP_Post ) {
		return $allowed_blocks;
	}

	if ( ! in_array( $editor_context->post->post_type, array( 'page', 'post' ), true ) ) {
		return $allowed_blocks;
	}

	$registry = WP_Block_Type_Registry::get_instance()->get_all_registered();
	$current  = true === $allowed_blocks ? array_keys( $registry ) : ( is_array( $allowed_blocks ) ? $allowed_blocks : array() );

	return array_values(
		array_filter(
			$current,
			static function ( $block_name ) {
				return ! atora_is_course_structure_block( (string) $block_name );
			}
		)
	);
}
add_filter( 'allowed_block_types_all', 'atora_filter_allowed_block_types', 20, 2 );

/**
 * ¿Es un bloque de estructura de curso de ATORA LMS?
 *
 * @param string $block_name Nombre del bloque.
 * @return bool
 */
function atora_is_course_structure_block( $block_name ) {
	if ( 0 !== strpos( $block_name, 'atora-lms/' ) ) {
		return false;
	}

	return 0 !== strpos( $block_name, 'atora-lms/shortcode-' );
}

/**
 * Hide the raw custom fields metabox on editor screens.
 *
 * @param mixed $screen Optional screen context from WordPress hooks.
 * @return void
 */
function atora_hide_legacy_custom_fields_box( $screen = null ) {
	foreach ( array( 'page', 'post' ) as $post_type ) {
		if ( post_type_supports( $post_type, 'custom-fields' ) ) {
			remove_post_type_support( $post_type, 'custom-fields' );
		}

		remove_meta_box( 'postcustom', $post_type, 'normal' );
	}
}
add_action( 'admin_init', 'atora_hide_legacy_custom_fields_box' );
add_action( 'add_meta_boxes', 'atora_hide_legacy_custom_fields_box', 100 );
add_action( 'do_meta_boxes', 'atora_hide_legacy_custom_fields_box', 1000 );

/**
 * Hide raw legacy custom fields in the editor if another component re-adds them.
 *
 * @return void
 */
function atora_hide_legacy_custom_fields_styles() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || ! in_array( $screen->post_type, array( 'page', 'post' ), true ) ) {
		return;
	}
	?>
	<style>
		#postcustom,
		#postcustomstuff,
		.editor-post-meta-boxes-area #postcustom {
			display: none !important;
		}
	</style>
	<?php
}
add_action( 'admin_head-post.php', 'atora_hide_legacy_custom_fields_styles' );
add_action( 'admin_head-post-new.php', 'atora_hide_legacy_custom_fields_styles' );

/**
 * Load a dedicated editor stylesheet instead of the frontend bundle.
 *
 * @return void
 */
function atora_register_editor_stylesheet() {
	add_editor_style( 'assets/css/editor-meridian.css' );
}
add_action( 'after_setup_theme', 'atora_register_editor_stylesheet', 25 );

/**
 * Register the Meridian presentation metabox.
 *
 * @return void
 */
function atora_register_presentation_metabox() {
	foreach ( array( 'page', 'post' ) as $post_type ) {
		add_meta_box(
			'atora-meridian-presentation',
			__( 'Page presentation', 'atora-learning' ),
			'atora_render_presentation_metabox',
			$post_type,
			'side',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'atora_register_presentation_metabox' );

/**
 * Render the Meridian presentation metabox.
 *
 * @param WP_Post $post Post object.
 * @return void
 */
function atora_render_presentation_metabox( $post ) {
	$settings = atora_get_presentation_settings( $post->ID );
	wp_nonce_field( 'atora_save_presentation', 'atora_presentation_nonce' );
	?>
	<p>
		<label for="atora-presentation-eyebrow"><strong><?php esc_html_e( 'Label above the title', 'atora-learning' ); ?></strong></label>
		<input type="text" id="atora-presentation-eyebrow" name="atora_presentation[eyebrow]" value="<?php echo esc_attr( (string) $settings['eyebrow'] ); ?>" class="widefat">
	</p>
	<p>
		<label for="atora-presentation-intro"><strong><?php esc_html_e( 'Hero text', 'atora-learning' ); ?></strong></label>
		<textarea id="atora-presentation-intro" name="atora_presentation[intro]" rows="4" class="widefat"><?php echo esc_textarea( (string) $settings['intro'] ); ?></textarea>
	</p>
	<p>
		<label for="atora-presentation-primary-label"><strong><?php esc_html_e( 'Primary button text', 'atora-learning' ); ?></strong></label>
		<input type="text" id="atora-presentation-primary-label" name="atora_presentation[primary_label]" value="<?php echo esc_attr( (string) $settings['primary_label'] ); ?>" class="widefat">
	</p>
	<p>
		<label for="atora-presentation-primary-url"><strong><?php esc_html_e( 'Primary button URL', 'atora-learning' ); ?></strong></label>
		<input type="url" id="atora-presentation-primary-url" name="atora_presentation[primary_url]" value="<?php echo esc_attr( (string) $settings['primary_url'] ); ?>" class="widefat">
	</p>
	<p>
		<label for="atora-presentation-secondary-label"><strong><?php esc_html_e( 'Secondary button text', 'atora-learning' ); ?></strong></label>
		<input type="text" id="atora-presentation-secondary-label" name="atora_presentation[secondary_label]" value="<?php echo esc_attr( (string) $settings['secondary_label'] ); ?>" class="widefat">
	</p>
	<p>
		<label for="atora-presentation-secondary-url"><strong><?php esc_html_e( 'Secondary button URL', 'atora-learning' ); ?></strong></label>
		<input type="url" id="atora-presentation-secondary-url" name="atora_presentation[secondary_url]" value="<?php echo esc_attr( (string) $settings['secondary_url'] ); ?>" class="widefat">
	</p>
	<p>
		<label>
			<input type="checkbox" name="atora_presentation[hide_hero]" value="1" <?php checked( ! empty( $settings['hide_hero'] ) ); ?>>
			<?php esc_html_e( 'Hide hero on this entry', 'atora-learning' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="atora_presentation[hide_sidebar]" value="1" <?php checked( ! empty( $settings['hide_sidebar'] ) ); ?>>
			<?php esc_html_e( 'Use full-width content', 'atora-learning' ); ?>
		</label>
	</p>
	<?php if ( 'page' === $post->post_type ) : ?>
	<p>
		<label>
			<input type="checkbox" name="atora_presentation[show_curated_sections]" value="1" <?php checked( ! empty( $settings['show_curated_sections'] ) ); ?>>
			<?php esc_html_e( 'Show highlighted sections (courses, programs, articles)', 'atora-learning' ); ?>
		</label>
	</p>
	<?php endif; ?>
	<p class="description"><?php esc_html_e( 'If the page already has its own blocks, the theme shows them as they are. Use these fields only to add a header on top.', 'atora-learning' ); ?></p>
	<?php
}

/**
 * Save presentation settings.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function atora_save_presentation_metabox( $post_id ) {
	if ( ! isset( $_POST['atora_presentation_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['atora_presentation_nonce'] ) ), 'atora_save_presentation' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	$post_type = get_post_type( $post_id );
	if ( ! in_array( $post_type, array( 'page', 'post' ), true ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$raw      = isset( $_POST['atora_presentation'] ) && is_array( $_POST['atora_presentation'] ) ? wp_unslash( $_POST['atora_presentation'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- se sanea campo por campo en atora_sanitize_presentation_settings(); nonce verificado arriba.
	$settings = atora_sanitize_presentation_settings( $raw );

	if ( ! atora_presentation_has_visual_overrides( $settings ) ) {
		delete_post_meta( $post_id, ATORA_PRESENTATION_META );
		return;
	}

	update_post_meta( $post_id, ATORA_PRESENTATION_META, $settings );
}
add_action( 'save_post', 'atora_save_presentation_metabox' );

/**
 * Localize theme page templates dynamically.
 *
 * @param array<string,string> $templates Existing templates.
 * @return array<string,string>
 */
function atora_localize_page_templates( $templates ) {
	if ( isset( $templates['page-templates/landing-meridian.php'] ) ) {
		$templates['page-templates/landing-meridian.php'] = __( 'Landing page (full width)', 'atora-learning' );
	}

	if ( isset( $templates['page-student-dashboard.php'] ) ) {
		$templates['page-student-dashboard.php'] = __( 'Student Dashboard', 'atora-learning' );
	}

	return $templates;
}
add_filter( 'theme_page_templates', 'atora_localize_page_templates' );


/**
 * Patrones para lecciones: estructuras pedagógicas listas para usar.
 *
 * Solo bloques del núcleo (grupo, título, lista, párrafo) con una clase: se
 * ven bien en la web, se pueden editar sin aprender nada nuevo y, en la app,
 * llegan como texto limpio (la app muestra `content_text`).
 */
function atora_register_lesson_patterns() {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	register_block_pattern_category(
		'atora-lessons',
		array( 'label' => __( 'ATORA: lessons', 'atora-learning' ) )
	);

	$box = static function ( $variant, $title, $body_blocks ) {
		return '<!-- wp:group {"className":"atora-callout atora-callout--' . $variant . '","layout":{"type":"constrained"}} -->'
			. '<div class="wp-block-group atora-callout atora-callout--' . $variant . '">'
			. '<!-- wp:heading {"level":3,"className":"atora-callout__title"} --><h3 class="wp-block-heading atora-callout__title">' . esc_html( $title ) . '</h3><!-- /wp:heading -->'
			. $body_blocks
			. '</div><!-- /wp:group -->';
	};

	$list = static function ( array $items, $ordered = false ) {
		$tag   = $ordered ? 'ol' : 'ul';
		$attrs = $ordered ? ' {"ordered":true}' : '';
		$html  = '<!-- wp:list' . $attrs . ' --><' . $tag . ' class="wp-block-list">';
		foreach ( $items as $item ) {
			$html .= '<!-- wp:list-item --><li>' . esc_html( $item ) . '</li><!-- /wp:list-item -->';
		}

		return $html . '</' . $tag . '><!-- /wp:list -->';
	};

	$paragraph = static function ( $text ) {
		return '<!-- wp:paragraph --><p>' . esc_html( $text ) . '</p><!-- /wp:paragraph -->';
	};

	$patterns = array(
		'objectives' => array(
			'title'   => __( 'Learning objectives', 'atora-learning' ),
			'content' => $box(
				'objectives',
				__( 'By the end of this lesson you will be able to…', 'atora-learning' ),
				$list(
					array(
						__( 'Explain the main concept in your own words.', 'atora-learning' ),
						__( 'Apply it to a real example.', 'atora-learning' ),
						__( 'Recognize the most common mistakes.', 'atora-learning' ),
					)
				)
			),
		),
		'key-idea' => array(
			'title'   => __( 'Key idea', 'atora-learning' ),
			'content' => $box(
				'key',
				__( 'Key idea', 'atora-learning' ),
				$paragraph( __( 'Write here, in one or two sentences, what the student must not forget.', 'atora-learning' ) )
			),
		),
		'activity' => array(
			'title'   => __( 'Practice activity', 'atora-learning' ),
			'content' => $box(
				'activity',
				__( 'Practice', 'atora-learning' ),
				$list(
					array(
						__( 'Read the case or watch the video again.', 'atora-learning' ),
						__( 'Answer the question in your notebook or in the assignment.', 'atora-learning' ),
						__( 'Compare your answer with the key idea.', 'atora-learning' ),
					),
					true
				)
			),
		),
		'summary' => array(
			'title'   => __( 'Lesson summary', 'atora-learning' ),
			'content' => $box(
				'summary',
				__( 'Summary', 'atora-learning' ),
				$list(
					array(
						__( 'First idea of the lesson.', 'atora-learning' ),
						__( 'Second idea of the lesson.', 'atora-learning' ),
						__( 'What comes in the next lesson.', 'atora-learning' ),
					)
				)
			),
		),
		'glossary' => array(
			'title'   => __( 'Glossary', 'atora-learning' ),
			'content' => $box(
				'glossary',
				__( 'Glossary', 'atora-learning' ),
				'<!-- wp:paragraph --><p><strong>' . esc_html__( 'Term', 'atora-learning' ) . ':</strong> ' . esc_html__( 'short definition in plain language.', 'atora-learning' ) . '</p><!-- /wp:paragraph -->'
					. '<!-- wp:paragraph --><p><strong>' . esc_html__( 'Term', 'atora-learning' ) . ':</strong> ' . esc_html__( 'short definition in plain language.', 'atora-learning' ) . '</p><!-- /wp:paragraph -->'
			),
		),
	);

	foreach ( $patterns as $slug => $pattern ) {
		register_block_pattern(
			'atora-meridian/lesson-' . $slug,
			array(
				'title'      => $pattern['title'],
				'categories' => array( 'atora-lessons' ),
				'postTypes'  => array( 'lm_lesson', 'post', 'page' ),
				'content'    => $pattern['content'],
			)
		);
	}
}
add_action( 'init', 'atora_register_lesson_patterns', 20 );

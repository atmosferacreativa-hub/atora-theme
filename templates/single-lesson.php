<?php
/**
 * Single Lesson Template Override — Atora Theme v1.1.0
 *
 * Delega al plugin para render pero asegura que los tokens del tema
 * se aplican correctamente y el layout es responsive.
 *
 * @package Atora_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( ! have_posts() ) {
	get_footer();
	return;
}

the_post();

$lesson_id = get_the_ID();

$view = class_exists( 'CLMS_UI_Lesson_Sections', false ) && method_exists( 'CLMS_UI_Lesson_Sections', 'build_view_model' )
	? CLMS_UI_Lesson_Sections::build_view_model( $lesson_id )
	: array(
		'sections_output'    => array(),
		'render_section_ids' => array(),
		'view_state'         => 'guest',
		'user_id'            => get_current_user_id(),
		'course_id'          => 0,
		'sidebar_html'       => '',
	);

$sections_output    = isset( $view['sections_output'] ) && is_array( $view['sections_output'] ) ? $view['sections_output'] : array();
$render_section_ids = isset( $view['render_section_ids'] ) && is_array( $view['render_section_ids'] ) ? $view['render_section_ids'] : array();
$view_state         = isset( $view['view_state'] ) ? sanitize_key( (string) $view['view_state'] ) : 'guest';
$user_id            = isset( $view['user_id'] ) ? absint( $view['user_id'] ) : get_current_user_id();
$course_id          = isset( $view['course_id'] ) ? absint( $view['course_id'] ) : 0;
$sidebar_html       = (string) ( $view['sidebar_html'] ?? '' );
?>

<div class="atora-lesson-wrap">
	<div class="atora-lesson-layout <?php echo $sidebar_html ? 'has-sidebar' : ''; ?>">
		<div class="atora-lesson-main" role="region" aria-label="<?php esc_attr_e( 'Contenido de la lección', 'atora-theme' ); ?>">
			<?php foreach ( $render_section_ids as $section_id ) : ?>
				<?php if ( ! empty( $sections_output[ $section_id ] ) ) : ?>
					<?php echo $sections_output[ $section_id ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
			<?php endforeach; ?>

			<?php if ( 'guest' === $view_state ) : ?>
				<div class="atora-lesson-gate">
					<p><?php esc_html_e( 'Inicia sesion para continuar.', 'atora-lms' ); ?></p>
					<a class="atora-btn atora-btn-primary" href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>">
						<?php esc_html_e( 'Iniciar sesión', 'atora-lms' ); ?>
					</a>
				</div>
			<?php elseif ( 'restricted' === $view_state ) : ?>
				<div class="atora-lesson-gate">
					<p><?php esc_html_e( 'Aun no tienes acceso a esta leccion. Inscribete en el curso para activar tu acceso.', 'atora-lms' ); ?></p>
					<?php if ( $course_id ) : ?>
						<a class="atora-btn atora-btn-primary" href="<?php echo esc_url( get_permalink( $course_id ) ); ?>">
							<?php esc_html_e( 'Ver curso', 'atora-lms' ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $sidebar_html ) : ?>
			<aside class="atora-lesson-sidebar">
				<?php echo $sidebar_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</aside>
		<?php endif; ?>
	</div>
</div>

<?php
if ( class_exists( 'CLMS_UI_Lesson_Sections', false ) ) {
	CLMS_UI_Lesson_Sections::render_inline_assets();
}

get_footer();

<?php
/**
 * Template Name: Student Dashboard
 *
 * Con ATORA LMS, el panel lo dibuja el plugin ([clms_dashboard] o su bloque) y
 * esta plantilla solo le da el marco. El resumen propio del tema aparece
 * únicamente si la página no tiene el panel del plugin (o el plugin no está).
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$atora_page_id     = (int) get_queried_object_id();
$atora_content_raw = (string) get_post_field( 'post_content', $atora_page_id );
$atora_has_plugin  = atora_theme_is_lms_active()
	&& ( has_shortcode( $atora_content_raw, 'clms_dashboard' ) || false !== strpos( $atora_content_raw, 'wp:atora-lms/' ) );

if ( $atora_has_plugin ) :
	while ( have_posts() ) :
		the_post();
		?>
		<div class="meridian-content-shell meridian-content-shell--app">
			<?php the_content(); ?>
		</div>
		<?php
	endwhile;
	get_footer();
	return;
endif;

if ( ! is_user_logged_in() ) :
	?>
	<section class="meridian-dashboard-hero">
		<div class="meridian-dashboard-hero__grid">
			<div class="meridian-dashboard-hero__copy" data-reveal>
				<p class="meridian-eyebrow"><?php esc_html_e( 'Student area', 'atora-learning' ); ?></p>
				<h1><?php esc_html_e( 'Your learning, in one place.', 'atora-learning' ); ?></h1>
				<p><?php esc_html_e( 'Log in to continue your courses, see your progress and find your next lesson.', 'atora-learning' ); ?></p>
				<div class="meridian-hero-actions">
					<a class="meridian-button meridian-button--primary" href="<?php echo esc_url( atora_get_login_url( get_permalink() ) ); ?>"><?php esc_html_e( 'Log in', 'atora-learning' ); ?></a>
					<a class="meridian-button meridian-button--ghost" href="<?php echo esc_url( atora_get_archive_link( 'lm_course' ) ); ?>"><?php esc_html_e( 'Browse courses', 'atora-learning' ); ?></a>
				</div>
			</div>
		</div>
	</section>
	<?php
	get_footer();
	return;
endif;

$user_id          = get_current_user_id();
$user             = wp_get_current_user();
$enrolled_courses = atora_get_user_enrolled_courses( $user_id );
$programs_count   = count( Atora_Theme_Plugin_Bridge::get_user_program_ids( $user_id ) );
$course_count     = count( $enrolled_courses );
$avg_progress     = 0;
$completed_count  = 0;
$progress_by_id   = array();

foreach ( $enrolled_courses as $course_id ) {
	$progress_by_id[ $course_id ] = atora_get_course_progress( $user_id, $course_id );
	if ( $progress_by_id[ $course_id ] >= 100 ) {
		++$completed_count;
	}
}
if ( $course_count > 0 ) {
	$avg_progress = (int) round( array_sum( $progress_by_id ) / $course_count );
}
?>

<section class="meridian-dashboard-hero">
	<div class="meridian-dashboard-hero__grid">
		<div class="meridian-dashboard-hero__copy" data-reveal>
			<p class="meridian-eyebrow"><?php esc_html_e( 'Student dashboard', 'atora-learning' ); ?></p>
			<h1>
				<?php
				/* translators: %s: student's name. */
				echo esc_html( sprintf( __( 'Welcome back, %s.', 'atora-learning' ), $user->display_name ) );
				?>
			</h1>
			<p><?php esc_html_e( 'Pick up where you left off.', 'atora-learning' ); ?></p>
		</div>

		<div class="meridian-dashboard-panel" data-reveal>
			<h2 class="meridian-dashboard-panel__title"><?php esc_html_e( 'Snapshot', 'atora-learning' ); ?></h2>
			<div class="meridian-dashboard-grid">
				<div class="meridian-stat-card">
					<strong><?php echo esc_html( number_format_i18n( $course_count ) ); ?></strong>
					<span><?php esc_html_e( 'Courses', 'atora-learning' ); ?></span>
				</div>
				<div class="meridian-stat-card">
					<strong><?php echo esc_html( number_format_i18n( $programs_count ) ); ?></strong>
					<span><?php esc_html_e( 'Programs', 'atora-learning' ); ?></span>
				</div>
				<div class="meridian-stat-card">
					<strong><?php echo esc_html( (string) $avg_progress ); ?>%</strong>
					<span><?php esc_html_e( 'Average progress', 'atora-learning' ); ?></span>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="meridian-dashboard-shell">
	<div class="meridian-dashboard-layout">
		<div class="meridian-dashboard-panel" data-reveal>
			<h2><?php esc_html_e( 'Your active courses', 'atora-learning' ); ?></h2>

			<?php if ( ! empty( $enrolled_courses ) ) : ?>
				<div class="meridian-grid meridian-grid--2">
					<?php foreach ( $enrolled_courses as $course_id ) : ?>
						<?php $progress = (int) $progress_by_id[ $course_id ]; ?>
						<article class="post-card">
							<?php if ( has_post_thumbnail( $course_id ) ) : ?>
								<div class="post-thumbnail">
									<a href="<?php echo esc_url( get_permalink( $course_id ) ); ?>" tabindex="-1" aria-hidden="true">
										<?php echo get_the_post_thumbnail( $course_id, 'atora-meridian-card' ); ?>
									</a>
								</div>
							<?php endif; ?>
							<div class="post-card__body">
								<div class="meridian-entry-meta">
									<span><?php echo esc_html( atora_get_primary_label( $course_id ) ); ?></span>
									<span><?php echo esc_html( (string) $progress ); ?>%</span>
								</div>
								<h3 class="entry-title"><a href="<?php echo esc_url( get_permalink( $course_id ) ); ?>"><?php echo esc_html( get_the_title( $course_id ) ); ?></a></h3>
								<p><?php echo esc_html( atora_get_plain_excerpt( $course_id, 18 ) ); ?></p>
								<div class="meridian-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo esc_attr( (string) $progress ); ?>" aria-label="<?php esc_attr_e( 'Course progress', 'atora-learning' ); ?>"><span style="width:<?php echo esc_attr( (string) $progress ); ?>%;"></span></div>
								<div class="entry-footer">
									<a class="meridian-button meridian-button--primary meridian-button--small" href="<?php echo esc_url( get_permalink( $course_id ) ); ?>">
										<?php echo esc_html( $progress >= 100 ? __( 'Review', 'atora-learning' ) : __( 'Continue', 'atora-learning' ) ); ?>
									</a>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="meridian-empty-state">
					<h3><?php esc_html_e( 'No enrolled courses yet', 'atora-learning' ); ?></h3>
					<p><?php esc_html_e( 'When you enroll in a course, your progress will appear here.', 'atora-learning' ); ?></p>
					<a class="meridian-button meridian-button--primary" href="<?php echo esc_url( atora_get_archive_link( 'lm_course' ) ); ?>"><?php esc_html_e( 'Browse courses', 'atora-learning' ); ?></a>
				</div>
			<?php endif; ?>
		</div>

		<aside class="meridian-sidebar">
			<div class="meridian-sidebar-card" data-reveal>
				<h3><?php esc_html_e( 'Account', 'atora-learning' ); ?></h3>
				<ul class="meridian-sidebar-list">
					<li><?php echo esc_html( $user->user_email ); ?></li>
					<li>
						<?php
						/* translators: %d: number of completed courses. */
						echo esc_html( sprintf( _n( '%d completed course', '%d completed courses', $completed_count, 'atora-learning' ), $completed_count ) );
						?>
					</li>
				</ul>
				<div class="meridian-hero-actions">
					<a class="meridian-button meridian-button--ghost meridian-button--small" href="<?php echo esc_url( atora_get_page_url_by_slugs( array( 'cuenta', 'mi-perfil', 'perfil' ) ) ?: atora_get_account_url() ); ?>"><?php esc_html_e( 'Manage account', 'atora-learning' ); ?></a>
					<a class="meridian-button meridian-button--ghost meridian-button--small" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Log out', 'atora-learning' ); ?></a>
				</div>
			</div>

			<?php
			$atora_support_url = atora_get_page_url_by_slugs( array( 'soporte', 'ayuda', 'contacto', 'support', 'contact' ) );
			?>
			<div class="meridian-sidebar-card" data-reveal>
				<h3><?php esc_html_e( 'Useful next steps', 'atora-learning' ); ?></h3>
				<ul class="meridian-sidebar-list">
					<?php if ( post_type_exists( 'lm_program' ) ) : ?>
						<li><a href="<?php echo esc_url( atora_get_archive_link( 'lm_program' ) ); ?>"><?php esc_html_e( 'Explore programs', 'atora-learning' ); ?></a></li>
					<?php endif; ?>
					<?php if ( atora_has_store() ) : ?>
						<li><a href="<?php echo esc_url( atora_get_shop_url() ); ?>"><?php esc_html_e( 'Visit the store', 'atora-learning' ); ?></a></li>
					<?php endif; ?>
					<?php if ( '' !== $atora_support_url ) : ?>
						<li><a href="<?php echo esc_url( $atora_support_url ); ?>"><?php esc_html_e( 'Support', 'atora-learning' ); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>
		</aside>
	</div>
</div>

<?php
get_footer();

<?php
/**
 * Archive Course Template Override — Atora Theme v1.1.0
 *
 * Listado de cursos con filtros y búsqueda.
 * Hereda tokens del tema automáticamente.
 *
 * @package Atora_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( ! have_posts() ) {
	?>
	<div class="atora-theme-archive-empty">
		<h1><?php esc_html_e( 'No hay cursos disponibles', 'atora-theme' ); ?></h1>
		<p><?php esc_html_e( 'Por favor, intenta más tarde.', 'atora-theme' ); ?></p>
	</div>
	<?php
	get_footer();
	return;
}
?>

<div class="atora-theme-archive-wrapper">
	<!-- Hero del archivo -->
	<div class="atora-theme-archive-hero" style="background: linear-gradient(180deg, var(--as-cream) 0%, var(--as-bone) 100%); padding: 72px 0;">
		<div class="atora-theme-container">
			<p class="atora-theme-eyebrow">· Cursos disponibles</p>
			<h1 style="font-family: var(--as-font-serif); font-size: 56px; font-weight: 700; letter-spacing: -0.02em;">Explora nuestros programas</h1>
			<p style="font-size: 17px; line-height: 1.65; max-width: 600px;">Selecciona el curso que mejor se adapte a tus objetivos y comienza tu ruta de crecimiento.</p>
		</div>
	</div>

	<!-- Contenido: grid de cursos -->
	<div class="atora-theme-container" style="padding: 72px 20px;">
		<!-- Grid 3 columnas -->
		<div class="atora-theme-posts-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 32px;">
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<div class="atora-theme-course-card" style="background: var(--as-surface); border: 1px solid var(--as-rule); border-radius: var(--as-radius-lg); box-shadow: var(--as-shadow-sm); overflow: hidden; display: flex; flex-direction: column;">
					<!-- Featured image 4:3 -->
					<div style="position: relative; width: 100%; aspect-ratio: 4/3; overflow: hidden;">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'atora-theme-card', array( 'style' => 'width: 100%; height: 100%; object-fit: cover;' ) ); ?>
						<?php else : ?>
							<div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--as-gold-soft) 0%, var(--as-blue-soft) 100%);"></div>
						<?php endif; ?>
					</div>

					<!-- Contenido -->
					<div style="padding: 24px; flex: 1; display: flex; flex-direction: column;">
						<!-- Categoría chip -->
						<?php
						$categories = get_the_terms( get_the_ID(), 'lm_course_level' );
						if ( $categories && ! is_wp_error( $categories ) ) {
							foreach ( $categories as $cat ) {
								echo '<span style="display: inline-block; background: var(--as-blue-soft); color: var(--as-blue); font-family: var(--as-font-mono); font-size: 10px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.08em; padding: 4px 8px; border-radius: 4px;">' . esc_html( $cat->name ) . '</span>';
								break; // Solo la primera
							}
						}
						?>

						<!-- Título -->
						<h3 style="font-family: var(--as-font-serif); font-size: 20px; font-weight: 700; margin: 12px 0 8px; color: var(--as-ink);">
							<a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;">
								<?php the_title(); ?>
							</a>
						</h3>

						<!-- Autor -->
						<p style="font-size: 14px; color: var(--as-muted); margin: 0;">
							<?php echo esc_html( get_the_author() ); ?>
						</p>

						<!-- Descripción -->
						<p style="font-size: 14px; color: var(--as-ink); line-height: 1.6; margin: 12px 0; flex: 1;">
							<?php echo esc_html( wp_trim_words( get_the_excerpt(), 15 ) ); ?>
						</p>

						<!-- Footer: precio y botón -->
						<div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid var(--as-rule);">
							<!-- TODO: agregar meta de precio si existe -->
							<p style="font-family: var(--as-font-serif); font-size: 16px; font-weight: 700; color: var(--as-gold); margin: 0;">$79 USD</p>
							<a href="<?php the_permalink(); ?>" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: var(--as-ink); color: var(--as-cream); border-radius: 999px; text-decoration: none; font-weight: 700;">→</a>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		</div>

		<!-- Paginación -->
		<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
	</div>
</div>

<?php get_footer();

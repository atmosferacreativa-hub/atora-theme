<?php
/**
 * Portada por defecto de Atora Theme.
 *
 * @package Atora_Theme
 */

$courses_url  = atora_theme_archive_link( 'lm_course', '/cursos/' );
$programs_url = atora_theme_archive_link( 'lm_program', '/programas/' );
$blog_url     = get_permalink( get_option( 'page_for_posts' ) );
$blog_url     = $blog_url ? $blog_url : home_url( '/blog/' );
?>

<section class="atora-theme-hero">
	<div class="atora-theme-container atora-theme-hero__grid">
		<div class="atora-theme-hero__content">
			<p class="atora-theme-eyebrow"><?php esc_html_e( 'Desde Caracas para el mundo', 'atora-theme' ); ?></p>
			<h1><?php esc_html_e( 'Comunica. Fotografia. Crea.', 'atora-theme' ); ?></h1>
			<p><?php esc_html_e( 'Una experiencia comercial para cursos, programas y comunidades creadas con ATORA LMS.', 'atora-theme' ); ?></p>
			<div class="atora-theme-actions">
				<a class="atora-theme-button" href="<?php echo esc_url( $programs_url ); ?>"><?php esc_html_e( 'Ver programas', 'atora-theme' ); ?></a>
				<a class="atora-theme-button atora-theme-button--ghost" href="<?php echo esc_url( $courses_url ); ?>"><?php esc_html_e( 'Explorar cursos', 'atora-theme' ); ?></a>
			</div>
		</div>
		<div class="atora-theme-hero__visual" aria-hidden="true">
			<div class="atora-theme-aperture">
				<span>f/1.8</span>
				<span>ISO 400</span>
				<span>1/250s</span>
			</div>
			<img src="<?php echo esc_url( atora_theme_asset_url( 'assets/images/logo-atora-theme.jpg' ) ); ?>" alt="">
		</div>
	</div>
</section>

<section class="atora-theme-marquee" aria-label="<?php esc_attr_e( 'Areas de formacion', 'atora-theme' ); ?>">
	<div>
		<span><?php esc_html_e( 'Fotografia con intencion', 'atora-theme' ); ?></span>
		<span><?php esc_html_e( 'Comunicacion estrategica', 'atora-theme' ); ?></span>
		<span><?php esc_html_e( 'Marca y contenido', 'atora-theme' ); ?></span>
		<span><?php esc_html_e( 'Estrategia digital', 'atora-theme' ); ?></span>
		<span><?php esc_html_e( 'Maestria de la luz', 'atora-theme' ); ?></span>
	</div>
</section>

<section class="atora-theme-section">
	<div class="atora-theme-container">
		<div class="atora-theme-section__head">
			<p class="atora-theme-eyebrow"><?php esc_html_e( 'Oferta comercial', 'atora-theme' ); ?></p>
			<h2><?php esc_html_e( 'Dos caminos. Un ecosistema.', 'atora-theme' ); ?></h2>
		</div>
		<div class="atora-theme-offer-grid">
			<a class="atora-theme-offer-card" href="<?php echo esc_url( $programs_url ); ?>">
				<span>01</span>
				<h3><?php esc_html_e( 'Programas y rutas', 'atora-theme' ); ?></h3>
				<p><?php esc_html_e( 'Rutas profundas, escalables y administradas desde ATORA LMS.', 'atora-theme' ); ?></p>
			</a>
			<a class="atora-theme-offer-card" href="<?php echo esc_url( $courses_url ); ?>">
				<span>02</span>
				<h3><?php esc_html_e( 'Cursos independientes', 'atora-theme' ); ?></h3>
				<p><?php esc_html_e( 'Piezas concretas para aprender una habilidad, practicar y avanzar.', 'atora-theme' ); ?></p>
			</a>
			<a class="atora-theme-offer-card" href="<?php echo esc_url( $blog_url ); ?>">
				<span>03</span>
				<h3><?php esc_html_e( 'Recursos y contenidos', 'atora-theme' ); ?></h3>
				<p><?php esc_html_e( 'Articulos abiertos para atraer audiencia y generar confianza.', 'atora-theme' ); ?></p>
			</a>
		</div>
	</div>
</section>

<section class="atora-theme-section atora-theme-section--alt">
	<div class="atora-theme-container atora-theme-split">
		<div>
			<p class="atora-theme-eyebrow"><?php esc_html_e( 'Diseñado para movil', 'atora-theme' ); ?></p>
			<h2><?php esc_html_e( 'Ligero, editable y preparado para ATORA LMS.', 'atora-theme' ); ?></h2>
			<p><?php esc_html_e( 'El tema entrega composicion, contraste y ritmo visual. El plugin conserva rutas, acceso, progreso y evaluaciones.', 'atora-theme' ); ?></p>
		</div>
		<div class="atora-theme-mobile-stack">
			<div><?php esc_html_e( 'Landing comercial para visitantes', 'atora-theme' ); ?></div>
			<div><?php esc_html_e( 'Panel del alumno', 'atora-theme' ); ?></div>
			<div><?php esc_html_e( 'Plantillas Gutenberg para paginas y contenido', 'atora-theme' ); ?></div>
		</div>
	</div>
</section>

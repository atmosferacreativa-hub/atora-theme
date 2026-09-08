<?php
/**
 * Portada por defecto de Atora Them.
 *
 * @package Atora_Them
 */

$courses_url  = atora_them_archive_link( 'lm_course', '/cursos/' );
$programs_url = atora_them_archive_link( 'lm_program', '/programas/' );
$blog_url     = get_permalink( get_option( 'page_for_posts' ) );
$blog_url     = $blog_url ? $blog_url : home_url( '/blog/' );
?>

<section class="atora-them-hero">
	<div class="atora-them-container atora-them-hero__grid">
		<div class="atora-them-hero__content">
			<p class="atora-them-eyebrow"><?php esc_html_e( 'Desde Caracas para el mundo', 'atora-them' ); ?></p>
			<h1><?php esc_html_e( 'Comunica. Fotografia. Crea.', 'atora-them' ); ?></h1>
			<p><?php esc_html_e( 'Una experiencia comercial para cursos, programas y comunidades creadas con ATORA LMS.', 'atora-them' ); ?></p>
			<div class="atora-them-actions">
				<a class="atora-them-button" href="<?php echo esc_url( $programs_url ); ?>"><?php esc_html_e( 'Ver programas', 'atora-them' ); ?></a>
				<a class="atora-them-button atora-them-button--ghost" href="<?php echo esc_url( $courses_url ); ?>"><?php esc_html_e( 'Explorar cursos', 'atora-them' ); ?></a>
			</div>
		</div>
		<div class="atora-them-hero__visual" aria-hidden="true">
			<div class="atora-them-aperture">
				<span>f/1.8</span>
				<span>ISO 400</span>
				<span>1/250s</span>
			</div>
			<img src="<?php echo esc_url( atora_them_asset_url( 'assets/images/logo-atora-theme.jpg' ) ); ?>" alt="">
		</div>
	</div>
</section>

<section class="atora-them-marquee" aria-label="<?php esc_attr_e( 'Areas de formacion', 'atora-them' ); ?>">
	<div>
		<span><?php esc_html_e( 'Fotografia con intencion', 'atora-them' ); ?></span>
		<span><?php esc_html_e( 'Comunicacion estrategica', 'atora-them' ); ?></span>
		<span><?php esc_html_e( 'Marca y contenido', 'atora-them' ); ?></span>
		<span><?php esc_html_e( 'Estrategia digital', 'atora-them' ); ?></span>
		<span><?php esc_html_e( 'Maestria de la luz', 'atora-them' ); ?></span>
	</div>
</section>

<section class="atora-them-section">
	<div class="atora-them-container">
		<div class="atora-them-section__head">
			<p class="atora-them-eyebrow"><?php esc_html_e( 'Oferta comercial', 'atora-them' ); ?></p>
			<h2><?php esc_html_e( 'Dos caminos. Un ecosistema.', 'atora-them' ); ?></h2>
		</div>
		<div class="atora-them-offer-grid">
			<a class="atora-them-offer-card" href="<?php echo esc_url( $programs_url ); ?>">
				<span>01</span>
				<h3><?php esc_html_e( 'Programas y rutas', 'atora-them' ); ?></h3>
				<p><?php esc_html_e( 'Rutas profundas, escalables y administradas desde ATORA LMS.', 'atora-them' ); ?></p>
			</a>
			<a class="atora-them-offer-card" href="<?php echo esc_url( $courses_url ); ?>">
				<span>02</span>
				<h3><?php esc_html_e( 'Cursos independientes', 'atora-them' ); ?></h3>
				<p><?php esc_html_e( 'Piezas concretas para aprender una habilidad, practicar y avanzar.', 'atora-them' ); ?></p>
			</a>
			<a class="atora-them-offer-card" href="<?php echo esc_url( $blog_url ); ?>">
				<span>03</span>
				<h3><?php esc_html_e( 'Recursos y contenidos', 'atora-them' ); ?></h3>
				<p><?php esc_html_e( 'Articulos abiertos para atraer audiencia y generar confianza.', 'atora-them' ); ?></p>
			</a>
		</div>
	</div>
</section>

<section class="atora-them-section atora-them-section--alt">
	<div class="atora-them-container atora-them-split">
		<div>
			<p class="atora-them-eyebrow"><?php esc_html_e( 'Diseñado para movil', 'atora-them' ); ?></p>
			<h2><?php esc_html_e( 'Ligero, editable y preparado para ATORA LMS.', 'atora-them' ); ?></h2>
			<p><?php esc_html_e( 'El tema entrega composicion, contraste y ritmo visual. El plugin conserva rutas, acceso, progreso y evaluaciones.', 'atora-them' ); ?></p>
		</div>
		<div class="atora-them-mobile-stack">
			<div><?php esc_html_e( 'Landing comercial para visitantes', 'atora-them' ); ?></div>
			<div><?php esc_html_e( 'Panel del alumno', 'atora-them' ); ?></div>
			<div><?php esc_html_e( 'Plantillas Gutenberg para paginas y contenido', 'atora-them' ); ?></div>
		</div>
	</div>
</section>

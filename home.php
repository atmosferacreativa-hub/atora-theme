<?php
/**
 * Indice del blog — versión con anti-duplicados.
 *
 * Reemplaza el bloque del loop por uno que recuerda los IDs ya pintados.
 * El resto del archivo es idéntico al original.
 *
 * @package Atora_Them
 */

get_header();
?>

<?php
$posts_page_id = absint( get_option( 'page_for_posts' ) );
$posts_page    = $posts_page_id ? get_post( $posts_page_id ) : null;

if ( $posts_page instanceof WP_Post && 'publish' === $posts_page->post_status && '' !== trim( (string) $posts_page->post_content ) ) :
	$previous_post = $GLOBALS['post'] ?? null;
	$GLOBALS['post'] = $posts_page; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	setup_postdata( $posts_page );
	?>
	<div class="atora-them-entry-content">
		<?php echo apply_filters( 'the_content', $posts_page->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<?php
	wp_reset_postdata();
	$GLOBALS['post'] = $previous_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
else :
	?>
	<section class="atora-them-archive-hero">
		<div class="atora-them-container">
			<p class="atora-them-eyebrow"><?php esc_html_e( 'Blog', 'atora-them' ); ?></p>
			<h1><?php esc_html_e( 'Ideas para aprender, crear y comunicar mejor.', 'atora-them' ); ?></h1>
		</div>
	</section>

	<div class="atora-them-container atora-them-post-grid">
		<?php
		if ( have_posts() ) :
			// ── Anti-duplicados ────────────────────────────────────────────────
			// Recordamos los IDs ya pintados; si un post repite (sticky duplicado,
			// query combinada con otro loop, etc.) lo saltamos en silencio.
			$atora_seen_ids = array();

			while ( have_posts() ) :
				the_post();
				$atora_pid = get_the_ID();

				if ( in_array( $atora_pid, $atora_seen_ids, true ) ) {
					continue;
				}
				$atora_seen_ids[] = $atora_pid;

				get_template_part( 'template-parts/content', get_post_type() );
			endwhile;
			?>
			<div class="atora-them-pagination">
				<?php the_posts_pagination(); ?>
			</div>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php
get_footer();

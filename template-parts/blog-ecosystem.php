<?php
/**
 * Blog layout: hero, category chips, post grid and ecosystem sidebar.
 *
 * @package Atora_Learning
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blog_url      = atora_get_blog_url() ?: home_url( '/' );
$blog_page_num = max( 1, (int) get_query_var( 'paged' ) );
$current_term  = ( is_category() || is_tag() ) ? get_queried_object() : null;
$vendor_promo  = atora_blog_shows_vendor_promo();

if ( $current_term instanceof WP_Term ) {
	$eyebrow = is_category() ? __( 'Topic', 'atora-learning' ) : __( 'Tag', 'atora-learning' );
	$heading = $current_term->name;
	/* translators: %s: topic name. */
	$intro   = $current_term->description ? wp_strip_all_tags( $current_term->description ) : sprintf( __( 'Articles about %s.', 'atora-learning' ), $current_term->name );
} else {
	$posts_page_id = (int) get_option( 'page_for_posts' );
	$settings      = $posts_page_id ? atora_get_presentation_settings( $posts_page_id ) : atora_get_presentation_defaults();
	$eyebrow       = ! empty( $settings['eyebrow'] ) ? (string) $settings['eyebrow'] : (string) get_bloginfo( 'name' );
	$heading       = $posts_page_id ? get_the_title( $posts_page_id ) : __( 'Journal', 'atora-learning' );
	$intro         = ! empty( $settings['intro'] ) ? (string) $settings['intro'] : ( $posts_page_id && has_excerpt( $posts_page_id ) ? get_the_excerpt( $posts_page_id ) : __( 'Articles, guides and news from the academy.', 'atora-learning' ) );
}

$latest = get_posts(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 4,
		'ignore_sticky_posts' => true,
	)
);

$categories = atora_blog_ecosystem_categories();
$stats      = atora_blog_ecosystem_stats();
$courses    = function_exists( 'atora_get_archive_link' ) ? atora_get_archive_link( 'lm_course' ) : home_url( '/cursos/' );
$eco        = atora_blog_ecosystem_data();
?>

<div class="atora-blog">
	<section class="atora-blog-hero">
		<div class="atora-blog-shell atora-blog-hero__grid">
			<div class="atora-blog-hero__copy">
				<p class="atora-blog-eyebrow is-on-dark"><?php echo esc_html( $eyebrow ); ?></p>
				<h1><?php echo esc_html( $heading ); ?></h1>
				<p class="atora-blog-hero__intro"><?php echo esc_html( $intro ); ?></p>

				<div class="atora-blog-actions">
					<?php if ( post_type_exists( 'lm_course' ) ) : ?>
						<a class="atora-blog-button is-amber" href="<?php echo esc_url( $courses ); ?>"><?php esc_html_e( 'Explore courses', 'atora-learning' ); ?></a>
					<?php endif; ?>
					<?php if ( $vendor_promo ) : ?>
						<a class="atora-blog-button is-outline" href="<?php echo esc_url( $eco['links']['ecosystem'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Discover the ATORA ecosystem', 'atora-learning' ); ?></a>
					<?php endif; ?>
				</div>

				<?php if ( $stats && ! $current_term ) : ?>
					<ul class="atora-blog-stats">
						<?php foreach ( $stats as $stat ) : ?>
							<li><strong><?php echo esc_html( number_format_i18n( $stat['value'] ) ); ?></strong><span><?php echo esc_html( $stat['label'] ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<?php if ( $latest ) : ?>
				<aside class="atora-blog-panel" aria-label="<?php esc_attr_e( 'Latest', 'atora-learning' ); ?>">
					<div class="atora-blog-panel__head">
						<strong><?php esc_html_e( 'Latest', 'atora-learning' ); ?></strong>
						<span class="atora-blog-badge">
							<?php
							/* translators: %s: date. */
							echo esc_html( sprintf( __( 'Updated %s', 'atora-learning' ), get_the_date( 'j M', $latest[0] ) ) );
							?>
						</span>
					</div>
					<ol class="atora-blog-panel__list">
						<?php foreach ( $latest as $item ) : ?>
							<?php $item_cat = atora_blog_ecosystem_primary_category( $item->ID ); ?>
							<li>
								<a href="<?php echo esc_url( get_permalink( $item ) ); ?>">
									<span class="atora-blog-panel__thumb">
										<?php if ( has_post_thumbnail( $item ) ) : ?>
											<?php echo get_the_post_thumbnail( $item, 'thumbnail', array( 'alt' => '' ) ); ?>
										<?php endif; ?>
									</span>
									<span class="atora-blog-panel__text">
										<span class="atora-blog-panel__title"><?php echo esc_html( get_the_title( $item ) ); ?></span>
										<span class="atora-blog-panel__meta"><?php echo esc_html( get_the_date( '', $item ) ); ?><?php echo $item_cat ? ' · ' . esc_html( $item_cat->name ) : ''; ?></span>
									</span>
								</a>
							</li>
						<?php endforeach; ?>
					</ol>
				</aside>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( $categories ) : ?>
		<nav class="atora-blog-shell atora-blog-topics" aria-label="<?php esc_attr_e( 'Blog topics', 'atora-learning' ); ?>">
			<a class="atora-blog-topic<?php echo $current_term ? '' : ' is-active'; ?>" href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'All', 'atora-learning' ); ?></a>
			<?php foreach ( $categories as $category ) : ?>
				<?php $active = $current_term instanceof WP_Term && (int) $current_term->term_id === (int) $category->term_id; ?>
				<a class="atora-blog-topic<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_category_link( $category ) ); ?>"><?php echo esc_html( $category->name ); ?> <span><?php echo esc_html( number_format_i18n( $category->count ) ); ?></span></a>
			<?php endforeach; ?>
		</nav>
	<?php endif; ?>

	<div class="atora-blog-shell atora-blog-layout">
		<div class="atora-blog-main">
			<p class="atora-blog-kicker"><?php
			/* translators: %d: page number. */
			echo esc_html( $blog_page_num > 1 ? sprintf( __( 'Articles · page %d', 'atora-learning' ), $blog_page_num ) : __( 'Articles', 'atora-learning' ) );
			?></p>

			<?php if ( have_posts() ) : ?>
				<div class="atora-blog-grid">
					<?php
					$index = 0;
					while ( have_posts() ) :
						the_post();
						atora_blog_ecosystem_card( get_post(), 0 === $index );
						++$index;
					endwhile;
					?>
				</div>

				<div class="atora-blog-pagination">
					<?php
					the_posts_pagination(
						array(
							'mid_size'  => 1,
							'prev_text' => __( '← Newer', 'atora-learning' ),
							'next_text' => __( 'Older →', 'atora-learning' ),
						)
					);
					?>
				</div>
			<?php else : ?>
				<div class="atora-blog-empty">
					<h2><?php esc_html_e( 'No articles here yet', 'atora-learning' ); ?></h2>
					<p><?php esc_html_e( 'Come back soon or explore the other topics.', 'atora-learning' ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<?php get_template_part( 'template-parts/blog', 'sidebar', array( 'eco' => $eco, 'vendor_promo' => $vendor_promo ) ); ?>
	</div>

	<section class="atora-blog-final">
		<div class="atora-blog-shell">
			<p class="atora-blog-eyebrow is-on-dark"><?php esc_html_e( 'Next step', 'atora-learning' ); ?></p>
			<h2><?php esc_html_e( 'Turn what you read into practice.', 'atora-learning' ); ?></h2>
			<p><?php esc_html_e( 'Courses with guided lessons, clear rubrics and feedback on every assignment.', 'atora-learning' ); ?></p>
			<div class="atora-blog-actions">
				<?php if ( post_type_exists( 'lm_course' ) ) : ?>
					<a class="atora-blog-button is-amber" href="<?php echo esc_url( $courses ); ?>"><?php esc_html_e( 'View courses', 'atora-learning' ); ?></a>
				<?php endif; ?>
				<?php if ( $vendor_promo ) : ?>
					<a class="atora-blog-button is-outline" href="<?php echo esc_url( $eco['links']['demo'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Request an ATORA demo', 'atora-learning' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
</div>

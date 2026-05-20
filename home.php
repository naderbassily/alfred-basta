<?php
/**
 * Template for the author blog posts index.
 *
 * @package Alfred_Basta
 */

get_header();

$posts_page_id = (int) get_option( 'page_for_posts' );
$hero_title    = __( 'Articles & Papers', 'alfred' );
$hero_intro    = $posts_page_id ? get_post_meta( $posts_page_id, 'blog_hero_intro', true ) : '';
$papers_query  = post_type_exists( 'papers' ) ? new WP_Query(
	array(
		'post_type'           => 'papers',
		'post_status'         => 'publish',
		'posts_per_page'      => -1,
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
	)
) : null;

if ( ! $hero_intro ) {
	$hero_intro = __( 'Reflections from Alfred Basta on books, faith, cybersecurity, teaching, grief, and the craft of patient scholarship.', 'alfred' );
}
?>

<main id="primary" class="site-main single-book-page blog-page blog-archive-page">
	<?php alfred_custom_site_navigation( __( 'Blog navigation', 'alfred' ), 'site-nav-search-blog' ); ?>

	<section class="book-hero blog-hero" id="home">
		<div class="book-hero__backdrop"></div>
		<div class="container book-archive-hero__container">
			<div class="book-archive-hero__content">
				<div class="book-hero__eyebrow reveal"><?php esc_html_e( 'Author Blog', 'alfred' ); ?></div>
				<h1 class="book-hero__title reveal reveal-delay-1"><?php echo esc_html( $hero_title ); ?></h1>
				<div class="book-hero__description book-archive-hero__description reveal reveal-delay-2 visible">
					<?php echo wp_kses_post( wpautop( $hero_intro ) ); ?>
				</div>
			</div>
		</div>
	</section>

	<div class="section-separator"></div>

		<section class="section section-white blog-index-section">
			<div class="container">
			<span class="section-label reveal"><?php esc_html_e( 'Latest Writing', 'alfred' ); ?></span>
			<div class="gold-rule reveal reveal-delay-1"></div>
			<h2 class="section-title reveal reveal-delay-1"><?php esc_html_e( 'Notes from the', 'alfred' ); ?> <em><?php esc_html_e( 'Author', 'alfred' ); ?></em></h2>

			<?php if ( have_posts() ) : ?>
				<div class="blog-archive-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						$categories   = get_the_category();
						$label        = ! empty( $categories ) ? $categories[0]->name : __( 'Article', 'alfred' );
						$card_excerpt = trim( get_post_field( 'post_excerpt', get_the_ID() ) );
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card blog-archive-card reveal reveal-delay-2' ); ?>>
							<a class="blog-card-image" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'large' ); ?>
								<?php else : ?>
									<div class="blog-img-placeholder"><span><?php esc_html_e( 'Blog Post Cover', 'alfred' ); ?></span></div>
								<?php endif; ?>
							</a>

							<div class="blog-card-body">
								<div class="blog-meta">
									<span class="blog-tag"><?php echo esc_html( $label ); ?></span>
								</div>
								<h3 class="blog-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<?php if ( $card_excerpt ) : ?>
									<p class="blog-card-excerpt"><?php echo esc_html( $card_excerpt ); ?></p>
								<?php endif; ?>
								<a class="blog-read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read Article', 'alfred' ); ?></a>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<div class="blog-pagination reveal">
					<?php
					the_posts_pagination(
						array(
							'mid_size'  => 1,
							'prev_text' => __( 'Previous', 'alfred' ),
							'next_text' => __( 'Next', 'alfred' ),
						)
					);
					?>
				</div>
			<?php else : ?>
				<div class="blog-empty reveal reveal-delay-2">
					<h3><?php esc_html_e( 'No articles yet', 'alfred' ); ?></h3>
					<p><?php esc_html_e( 'New reflections and author updates will appear here once posts are published.', 'alfred' ); ?></p>
				</div>
				<?php endif; ?>
			</div>
		</section>

		<?php if ( $papers_query instanceof WP_Query && $papers_query->have_posts() ) : ?>
			<div class="section-separator"></div>

			<section class="section section-white papers-index-section">
				<div class="container">
					<span class="section-label reveal"><?php esc_html_e( 'Research & Publishing', 'alfred' ); ?></span>
					<div class="gold-rule reveal reveal-delay-1"></div>
					<h2 class="section-title reveal reveal-delay-1"><?php esc_html_e( 'Papers and', 'alfred' ); ?> <em><?php esc_html_e( 'Publications', 'alfred' ); ?></em></h2>

						<div class="papers-grid">
							<?php
							while ( $papers_query->have_posts() ) :
								$papers_query->the_post();
								$paper_excerpt = trim( get_post_field( 'post_excerpt', get_the_ID() ) );
								$paper_link    = function_exists( 'get_field' ) ? get_field( 'paper_link', get_the_ID() ) : get_post_meta( get_the_ID(), 'paper_link', true );
								$paper_url     = '';
								$paper_label   = '';
								$paper_terms   = array();
								$paper_taxes   = get_object_taxonomies( get_post_type(), 'names' );

								if ( is_array( $paper_link ) ) {
									$paper_url = ! empty( $paper_link['url'] ) ? $paper_link['url'] : '';
								} elseif ( is_string( $paper_link ) ) {
									$paper_url = $paper_link;
								}

								if ( ! $paper_url ) {
									$paper_url = get_permalink();
								}

								foreach ( $paper_taxes as $paper_taxonomy ) {
									if ( in_array( $paper_taxonomy, array( 'category', 'post_tag' ), true ) ) {
										continue;
									}

									$paper_terms = get_the_terms( get_the_ID(), $paper_taxonomy );

									if ( ! empty( $paper_terms ) && ! is_wp_error( $paper_terms ) ) {
										$paper_label = $paper_terms[0]->name;
										break;
									}
								}
								?>
								<article id="paper-<?php the_ID(); ?>" <?php post_class( 'paper-card reveal reveal-delay-2' ); ?>>
									<div class="paper-card__meta">
										<span><?php esc_html_e( 'Paper', 'alfred' ); ?></span>
										<span><?php echo esc_html( get_the_date() ); ?></span>
									</div>
									<?php if ( $paper_label ) : ?>
										<div class="paper-card__taxonomy"><?php echo esc_html( $paper_label ); ?></div>
									<?php endif; ?>
									<h3 class="paper-card__title"><a href="<?php echo esc_url( $paper_url ); ?>" target="_blank" rel="noopener"><?php the_title(); ?></a></h3>
									<?php if ( $paper_excerpt ) : ?>
										<p class="paper-card__excerpt"><?php echo esc_html( $paper_excerpt ); ?></p>
									<?php endif; ?>
									<a class="blog-read-more paper-card__link" href="<?php echo esc_url( $paper_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Open Paper', 'alfred' ); ?></a>
								</article>
							<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>
			</section>
		<?php endif; ?>
	
		<?php alfred_custom_site_footer(); ?>
	</main>

<?php
get_footer();

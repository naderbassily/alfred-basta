<?php
/**
 * Template for single author blog posts.
 *
 * @package Alfred_Basta
 */

get_header();

while ( have_posts() ) :
	the_post();

	$categories             = get_the_category();
	$selected_related_books = function_exists( 'get_field' ) ? get_field( 'select_related_books', get_the_ID() ) : get_post_meta( get_the_ID(), 'select_related_books', true );
	$related_book_ids       = array();
	$related_args           = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'post__not_in'        => array( get_the_ID() ),
		'ignore_sticky_posts' => true,
	);

	if ( $selected_related_books ) {
		$selected_related_books = is_array( $selected_related_books ) ? $selected_related_books : array( $selected_related_books );

		foreach ( $selected_related_books as $selected_related_book ) {
			if ( $selected_related_book instanceof WP_Post ) {
				$related_book_ids[] = (int) $selected_related_book->ID;
			} elseif ( is_array( $selected_related_book ) && ! empty( $selected_related_book['ID'] ) ) {
				$related_book_ids[] = (int) $selected_related_book['ID'];
			} elseif ( is_numeric( $selected_related_book ) ) {
				$related_book_ids[] = (int) $selected_related_book;
			}
		}
	}

	$related_book_ids = array_values( array_unique( array_filter( $related_book_ids ) ) );
	$related_books    = new WP_Query(
		array(
			'post_type'           => 'book',
			'post_status'         => 'publish',
			'posts_per_page'      => $related_book_ids ? count( $related_book_ids ) : 1,
			'post__in'            => $related_book_ids ? $related_book_ids : array( 0 ),
			'orderby'             => 'post__in',
			'ignore_sticky_posts' => true,
		)
	);

	if ( ! empty( $categories ) ) {
		$related_args['category__in'] = wp_list_pluck( $categories, 'term_id' );
	}

	$related_posts = new WP_Query( $related_args );
	?>

		<main id="primary" class="site-main single-book-page blog-page single-post-page">
			<?php alfred_custom_site_navigation( __( 'Article navigation', 'alfred' ), 'site-nav-search-single-post' ); ?>

			<section class="book-hero blog-single-hero" id="home">
				<div class="book-hero__backdrop"></div>
				<div class="container book-hero__container blog-single-hero__container blog-single-hero__container--text-only">
					<div class="book-hero__content">
						<div class="book-hero__eyebrow reveal"><?php esc_html_e( 'Author Blog', 'alfred' ); ?></div>
						<h1 class="book-hero__title blog-single-hero__title reveal reveal-delay-1"><?php the_title(); ?></h1>
						<?php if ( has_excerpt() ) : ?>
							<div class="book-hero__description reveal reveal-delay-2 visible">
								<?php the_excerpt(); ?>
							</div>
					<?php endif; ?>
					<div class="book-hero__actions reveal reveal-delay-3">
						<a href="<?php echo esc_url( alfred_get_blog_archive_url() ); ?>" class="btn-outline"><?php esc_html_e( 'All Articles', 'alfred' ); ?></a>
						<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Contact Alfred', 'alfred' ); ?></a>
					</div>
				</div>
			</div>
		</section>

		<div class="section-separator"></div>

			<section class="section section-white blog-single-content-section">
				<div class="container blog-single-layout">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-single-article' ); ?>>
						<div class="blog-single-article__content">
							<?php
							the_content();

						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'alfred' ),
								'after'  => '</div>',
							)
						);
						?>
					</div>
				</article>

				<?php if ( $related_posts->have_posts() ) : ?>
					<aside class="blog-single-sidebar reveal reveal-delay-1">
						<div class="blog-single-sidebar__card">
							<div class="blog-single-sidebar__label"><?php esc_html_e( 'Related Articles', 'alfred' ); ?></div>
							<div class="blog-sidebar-article-list">
								<?php
								while ( $related_posts->have_posts() ) :
									$related_posts->the_post();
									?>
									<a class="blog-sidebar-article" href="<?php the_permalink(); ?>">
										<span><?php esc_html_e( 'Read Article', 'alfred' ); ?></span>
										<strong><?php the_title(); ?></strong>
									</a>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
							</div>
						</div>
					</aside>
				<?php endif; ?>
			</div>
		</section>

		<?php if ( $related_books->have_posts() ) : ?>
			<section class="section books-section section-white blog-related-section">
				<div class="container">
					<span class="section-label reveal"><?php esc_html_e( 'Related Reading', 'alfred' ); ?></span>
					<div class="gold-rule reveal reveal-delay-1"></div>
					<h2 class="section-title reveal reveal-delay-1"><?php esc_html_e( 'Related', 'alfred' ); ?> <em><?php esc_html_e( 'Books', 'alfred' ); ?></em></h2>

					<div class="books-grid blog-related-books-grid">
						<?php
						while ( $related_books->have_posts() ) :
							$related_books->the_post();
							$related_book_terms = get_the_terms( get_the_ID(), 'book-genre' );
							$related_book_label = '';

							if ( ! empty( $related_book_terms ) && ! is_wp_error( $related_book_terms ) ) {
								$related_book_label = $related_book_terms[0]->name;
							}
							?>
							<article class="book-card reveal reveal-delay-2">
								<a href="<?php the_permalink(); ?>" class="book-cover-link" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
									<div class="book-cover-wrap">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'large' ); ?>
										<?php else : ?>
											<div class="book-placeholder">
												<div class="book-placeholder-num"><?php echo esc_html( get_the_date( 'Y' ) ); ?></div>
												<div class="book-placeholder-title"><?php the_title(); ?></div>
											</div>
										<?php endif; ?>
										<div class="book-cover-overlay">
											<span><?php esc_html_e( 'View Book', 'alfred' ); ?></span>
										</div>
									</div>
								</a>
								<div class="book-genre"><?php echo esc_html( $related_book_label ); ?></div>
								<h3 class="book-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<div class="book-desc"><?php echo esc_html( get_the_excerpt() ); ?></div>
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
endwhile;

get_footer();

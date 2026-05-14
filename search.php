<?php
/**
 * Template for displaying search results.
 *
 * @package Alfred_Basta
 */

get_header();

$search_query = get_search_query();
?>

<main id="primary" class="site-main single-book-page utility-page search-results-page">
	<?php alfred_custom_site_navigation( __( 'Search navigation', 'alfred' ), 'site-nav-search-results' ); ?>

	<section class="book-hero utility-hero" id="home">
		<div class="book-hero__backdrop"></div>
		<div class="container book-archive-hero__container">
			<div class="book-archive-hero__content">
				<div class="book-hero__eyebrow reveal"><?php esc_html_e( 'Search', 'alfred' ); ?></div>
				<h1 class="book-hero__title reveal reveal-delay-1">
					<?php
					printf(
						/* translators: %s: search query. */
						esc_html__( 'Results for "%s"', 'alfred' ),
						esc_html( $search_query )
					);
					?>
				</h1>
				<div class="book-hero__description book-archive-hero__description reveal reveal-delay-2 visible">
					<p><?php esc_html_e( 'Search across Alfred Basta books, articles, pages, and resources.', 'alfred' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<div class="section-separator"></div>

	<section class="section section-white utility-results-section">
		<div class="container">
			<div class="utility-search-panel reveal">
				<form class="utility-search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
					<label class="screen-reader-text" for="utility-search-input"><?php esc_html_e( 'Search the site', 'alfred' ); ?></label>
					<input id="utility-search-input" type="search" name="s" value="<?php echo esc_attr( $search_query ); ?>" placeholder="<?php esc_attr_e( 'Search books, articles, and pages', 'alfred' ); ?>">
					<button type="submit"><?php esc_html_e( 'Search', 'alfred' ); ?></button>
				</form>
			</div>

			<?php if ( have_posts() ) : ?>
				<div class="utility-results-meta reveal reveal-delay-1">
					<?php
					global $wp_query;
					printf(
						/* translators: %d: number of search results. */
						esc_html( _n( '%d result found', '%d results found', (int) $wp_query->found_posts, 'alfred' ) ),
						(int) $wp_query->found_posts
					);
					?>
				</div>

				<div class="utility-result-list">
					<?php
					while ( have_posts() ) :
						the_post();
						$post_type_object = get_post_type_object( get_post_type() );
						$result_label     = $post_type_object ? $post_type_object->labels->singular_name : __( 'Result', 'alfred' );
						$result_excerpt   = get_the_excerpt();
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'utility-result-card reveal reveal-delay-2' ); ?>>
							<a class="utility-result-card__media" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium_large' ); ?>
								<?php else : ?>
									<span><?php echo esc_html( $result_label ); ?></span>
								<?php endif; ?>
							</a>
							<div class="utility-result-card__body">
								<div class="utility-result-card__type"><?php echo esc_html( $result_label ); ?></div>
								<h2 class="utility-result-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<?php if ( $result_excerpt ) : ?>
									<p><?php echo esc_html( $result_excerpt ); ?></p>
								<?php endif; ?>
								<a class="blog-read-more utility-result-card__link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Open Result', 'alfred' ); ?></a>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<div class="blog-pagination utility-pagination reveal">
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
				<div class="utility-empty reveal reveal-delay-1">
					<h2><?php esc_html_e( 'No results found', 'alfred' ); ?></h2>
					<p><?php esc_html_e( 'Try a different keyword, browse the book catalog, or visit the author blog.', 'alfred' ); ?></p>
					<div class="utility-actions">
						<a class="btn-outline" href="<?php echo esc_url( alfred_get_book_archive_url() ); ?>"><?php esc_html_e( 'Browse Books', 'alfred' ); ?></a>
						<a class="btn-primary" href="<?php echo esc_url( alfred_get_blog_archive_url() ); ?>"><?php esc_html_e( 'Author Blog', 'alfred' ); ?></a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php alfred_custom_site_footer(); ?>
</main>

<?php
get_footer();

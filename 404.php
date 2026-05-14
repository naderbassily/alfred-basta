<?php
/**
 * Template for displaying 404 pages.
 *
 * @package Alfred_Basta
 */

get_header();
?>

<main id="primary" class="site-main single-book-page utility-page error-page">
	<?php alfred_custom_site_navigation( __( '404 navigation', 'alfred' ), 'site-nav-search-404' ); ?>

	<section class="book-hero utility-hero error-hero" id="home">
		<div class="book-hero__backdrop"></div>
		<div class="container book-archive-hero__container">
			<div class="book-archive-hero__content">
				<div class="book-hero__eyebrow reveal"><?php esc_html_e( 'Page Not Found', 'alfred' ); ?></div>
				<h1 class="book-hero__title reveal reveal-delay-1"><?php esc_html_e( 'This page stepped out of the library.', 'alfred' ); ?></h1>
				<div class="book-hero__description book-archive-hero__description reveal reveal-delay-2 visible">
					<p><?php esc_html_e( 'The link may have changed, or the page may no longer exist. Search the site or use one of the main paths below.', 'alfred' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<div class="section-separator"></div>

	<section class="section section-white utility-results-section error-guide-section">
		<div class="container">
			<div class="utility-search-panel reveal">
				<form class="utility-search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
					<label class="screen-reader-text" for="utility-404-search-input"><?php esc_html_e( 'Search the site', 'alfred' ); ?></label>
					<input id="utility-404-search-input" type="search" name="s" placeholder="<?php esc_attr_e( 'Search books, articles, and pages', 'alfred' ); ?>">
					<button type="submit"><?php esc_html_e( 'Search', 'alfred' ); ?></button>
				</form>
			</div>

			<div class="utility-destination-grid">
				<a class="utility-destination-card reveal reveal-delay-1" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<span><?php esc_html_e( 'Home', 'alfred' ); ?></span>
					<strong><?php esc_html_e( 'Return to Alfred Basta', 'alfred' ); ?></strong>
					<p><?php esc_html_e( 'Start again from the homepage experience.', 'alfred' ); ?></p>
				</a>

				<a class="utility-destination-card reveal reveal-delay-2" href="<?php echo esc_url( alfred_get_book_archive_url() ); ?>">
					<span><?php esc_html_e( 'Books', 'alfred' ); ?></span>
					<strong><?php esc_html_e( 'Browse the catalog', 'alfred' ); ?></strong>
					<p><?php esc_html_e( 'Explore books by title, topic, and genre.', 'alfred' ); ?></p>
				</a>

				<a class="utility-destination-card reveal reveal-delay-3" href="<?php echo esc_url( alfred_get_blog_archive_url() ); ?>">
					<span><?php esc_html_e( 'Blog', 'alfred' ); ?></span>
					<strong><?php esc_html_e( 'Read author articles', 'alfred' ); ?></strong>
					<p><?php esc_html_e( 'Find recent reflections and updates.', 'alfred' ); ?></p>
				</a>

				<a class="utility-destination-card reveal reveal-delay-4" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<span><?php esc_html_e( 'Contact', 'alfred' ); ?></span>
					<strong><?php esc_html_e( 'Send a message', 'alfred' ); ?></strong>
					<p><?php esc_html_e( 'Reach out for speaking, publishing, or reader inquiries.', 'alfred' ); ?></p>
				</a>
			</div>
		</div>
	</section>

	<?php alfred_custom_site_footer(); ?>
</main>

<?php
get_footer();

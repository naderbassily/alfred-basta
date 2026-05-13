<?php
/**
 * Template for the About Alfred page.
 *
 * @package Alfred_Basta
 */

get_header();

while ( have_posts() ) :
	the_post();

	$hero_kicker = '';
	$hero_intro  = '';
	$hero_image  = '';

	if ( function_exists( 'get_field' ) ) {
		$hero_kicker = get_field( 'about_hero_kicker', get_the_ID() );
		$hero_intro = get_field( 'hero_introduction', get_the_ID() );
	}

	if ( is_array( $hero_kicker ) ) {
		$hero_kicker = '';
	}

	if ( ! $hero_kicker ) {
		$hero_kicker = get_post_meta( get_the_ID(), 'about_hero_kicker', true );
	}

	if ( ! $hero_kicker ) {
		$hero_kicker = 'Biography';
	}

	if ( is_array( $hero_intro ) ) {
		$hero_intro = '';
	}

	if ( ! $hero_intro ) {
		$hero_intro = get_post_meta( get_the_ID(), 'hero_introduction', true );
	}

	if ( ! $hero_intro ) {
		$hero_intro = has_excerpt() ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 42 );
	}

	if ( has_post_thumbnail() ) {
		$hero_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
	}

	if ( ! $hero_image && file_exists( get_theme_file_path( 'assets/images/alfred-hero.jpg' ) ) ) {
		$hero_image = get_theme_file_uri( 'assets/images/alfred-hero.jpg' );
	}
	?>

	<main id="primary" class="site-main single-book-page about-page">
		<nav class="site-nav single-book-nav-shell" id="siteNav" aria-label="<?php esc_attr_e( 'About Alfred navigation', 'alfred' ); ?>">
			<div class="nav-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Alfred Basta</a></div>
			<?php alfred_front_page_navigation(); ?>
			<form class="nav-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
				<label class="screen-reader-text" for="site-nav-search-about"><?php esc_html_e( 'Search the site', 'alfred' ); ?></label>
				<input id="site-nav-search-about" class="nav-search__input" type="search" name="s" placeholder="<?php esc_attr_e( 'Search', 'alfred' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="submit" class="nav-search__submit"><?php esc_html_e( 'Search', 'alfred' ); ?></button>
			</form>
			<button class="nav-toggle" id="navToggle" type="button" aria-label="<?php esc_attr_e( 'Toggle menu', 'alfred' ); ?>" aria-expanded="false" aria-controls="navLinks">
				<span></span><span></span><span></span>
			</button>
		</nav>

		<section class="book-hero about-page-hero" id="home">
			<div class="book-hero__backdrop"></div>
			<div class="container book-hero__container about-page-hero__container">
				<div class="book-hero__content about-page-hero__content">
					<?php if ( $hero_kicker ) : ?>
						<div class="book-hero__eyebrow reveal"><?php echo esc_html( $hero_kicker ); ?></div>
					<?php endif; ?>
					<h1 class="book-hero__title reveal reveal-delay-1"><?php the_title(); ?></h1>

					<?php if ( $hero_intro ) : ?>
						<div class="book-hero__description reveal reveal-delay-2 visible">
							<?php echo wp_kses_post( wpautop( $hero_intro ) ); ?>
						</div>
					<?php endif; ?>

					<div class="book-hero__actions reveal reveal-delay-3">
						<a href="<?php echo esc_url( alfred_get_book_archive_url() ); ?>" class="btn-outline"><?php esc_html_e( 'Browse Books', 'alfred' ); ?></a>
						<a href="<?php echo esc_url( alfred_get_front_page_anchor_url( '#contact' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Get in Touch', 'alfred' ); ?></a>
					</div>
				</div>

				<div class="about-page-hero__media reveal reveal-delay-1">
					<div class="about-page-hero__frame">
						<?php if ( $hero_image ) : ?>
							<img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="about-page-hero__image">
						<?php else : ?>
							<div class="about-page-hero__placeholder">
								<span><?php esc_html_e( 'Professor', 'alfred' ); ?></span>
								<strong>Alfred Basta</strong>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<div class="section-separator"></div>

		<section class="section section-white about-page-content">
			<div class="container about-page-content__container">
				<div class="about-page-content__intro reveal">
					<span class="section-label"><?php esc_html_e( 'Biography', 'alfred' ); ?></span>
					<div class="gold-rule"></div>
					<h2 class="section-title"><?php esc_html_e( 'The Story Behind', 'alfred' ); ?> <em><?php esc_html_e( 'the Work', 'alfred' ); ?></em></h2>
				</div>

				<div class="about-page-content__body reveal reveal-delay-1">
					<?php the_content(); ?>
				</div>
			</div>
		</section>

		<div class="section-separator"></div>

		<footer class="site-footer" id="contact">
			<div class="footer-top">
				<div class="footer-brand">
					<div class="footer-brand-name">Alfred Basta</div>
					<p class="footer-brand-desc">
						Ph.D. Cryptography · Professor at Purdue Global &amp; Georgia State University ·
						Author of 40+ books published by Wiley, Cengage &amp; Amazon ·
						Chair, EC-Council CPENT Scheme Committee · Based in Woodstock, GA.
					</p>
					<div class="footer-social">
						<a href="https://www.linkedin.com/in/alfred-basta-a94379249/" class="social-link" aria-label="LinkedIn" target="_blank" rel="noopener">
							<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
								<path d="M6.94 8.5H3.56V20h3.38V8.5ZM5.25 3C4.17 3 3.5 3.72 3.5 4.66c0 .92.65 1.66 1.71 1.66h.02c1.1 0 1.77-.74 1.77-1.66C6.98 3.72 6.35 3 5.25 3ZM20.5 12.56c0-3.52-1.88-5.16-4.39-5.16-2.02 0-2.93 1.12-3.43 1.9V8.5H9.31c.04.53 0 11.5 0 11.5h3.37v-6.42c0-.34.02-.68.12-.92.27-.68.89-1.39 1.94-1.39 1.37 0 1.92 1.05 1.92 2.59V20H20.5v-7.44Z" fill="currentColor"/>
							</svg>
						</a>
					</div>
				</div>

				<div class="footer-col">
					<div class="footer-col-title">Navigate</div>
					<?php alfred_front_page_navigation( 'footerNavigationLinks', 'footer-links' ); ?>
				</div>

				<div class="footer-col">
					<div class="footer-col-title">Books By Topic</div>
					<ul class="footer-links">
						<li><a href="<?php echo esc_url( alfred_get_book_archive_url() ); ?>">Cybersecurity &amp; Pen Testing</a></li>
						<li><a href="<?php echo esc_url( alfred_get_book_archive_url() ); ?>">Mathematics &amp; Cryptography</a></li>
						<li><a href="<?php echo esc_url( alfred_get_book_archive_url() ); ?>">Faith &amp; Spirituality</a></li>
						<li><a href="<?php echo esc_url( alfred_get_book_archive_url() ); ?>">Linux &amp; Networking</a></li>
						<li><a href="<?php echo esc_url( alfred_get_book_archive_url() ); ?>">Database Security</a></li>
					</ul>
				</div>

				<div class="footer-col">
					<div class="footer-col-title">Get in Touch</div>
					<ul class="footer-links">
						<li><a href="mailto:<?php echo esc_attr( antispambot( 'contact@alfredbasta.com' ) ); ?>"><?php echo esc_html( antispambot( 'contact@alfredbasta.com' ) ); ?></a></li>
						<li><a href="https://cloudsecurityalliance.org/profiles/alfred-basta" target="_blank" rel="noopener">Cloud Security Alliance</a></li>
						<li><a href="<?php echo esc_url( alfred_get_front_page_anchor_url( '#contact' ) ); ?>">Speaking &amp; Consulting</a></li>
						<li><a href="<?php echo esc_url( alfred_get_front_page_anchor_url( '#contact' ) ); ?>">Publisher Inquiries</a></li>
					</ul>
				</div>
			</div>

			<div class="footer-bottom">
				<p class="footer-copy">© <?php echo esc_html( wp_date( 'Y' ) ); ?> <span>Dr. Alfred Basta</span>. All rights reserved.</p>
				<p class="footer-copy">Professor · Author · Cybersecurity Expert · Woodstock, GA</p>
			</div>
		</footer>
	</main>
	<?php
endwhile;

get_footer();

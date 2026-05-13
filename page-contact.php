<?php
/**
 * Template for the Contact page.
 *
 * @package Alfred_Basta
 */

get_header();

while ( have_posts() ) :
	the_post();

	$contact_kicker = 'Contact';
	$contact_intro  = has_excerpt() ? get_the_excerpt() : 'For speaking, consulting, publisher inquiries, or reader correspondence, reach out using the details below.';
	$contact_email  = 'contact@alfredbasta.com';
	$contact_phone  = '';
	$contact_place  = 'Woodstock, GA';

	if ( function_exists( 'get_field' ) ) {
		$contact_kicker_field = get_field( 'contact_hero_kicker', get_the_ID() );
		$contact_intro_field  = get_field( 'contact_intro', get_the_ID() );
		$contact_email_field  = get_field( 'contact_email', get_the_ID() );
		$contact_phone_field  = get_field( 'contact_phone', get_the_ID() );
		$contact_place_field  = get_field( 'contact_location', get_the_ID() );
	} else {
		$contact_kicker_field = get_post_meta( get_the_ID(), 'contact_hero_kicker', true );
		$contact_intro_field  = get_post_meta( get_the_ID(), 'contact_intro', true );
		$contact_email_field  = get_post_meta( get_the_ID(), 'contact_email', true );
		$contact_phone_field  = get_post_meta( get_the_ID(), 'contact_phone', true );
		$contact_place_field  = get_post_meta( get_the_ID(), 'contact_location', true );
	}

	if ( is_string( $contact_kicker_field ) && '' !== trim( $contact_kicker_field ) ) {
		$contact_kicker = trim( $contact_kicker_field );
	}

	if ( is_string( $contact_intro_field ) && '' !== trim( $contact_intro_field ) ) {
		$contact_intro = trim( $contact_intro_field );
	}

	if ( is_string( $contact_email_field ) && is_email( trim( $contact_email_field ) ) ) {
		$contact_email = trim( $contact_email_field );
	}

	if ( is_string( $contact_phone_field ) && '' !== trim( $contact_phone_field ) ) {
		$contact_phone = trim( $contact_phone_field );
	}

	if ( is_string( $contact_place_field ) && '' !== trim( $contact_place_field ) ) {
		$contact_place = trim( $contact_place_field );
	}
	?>

	<main id="primary" class="site-main single-book-page contact-page">
		<nav class="site-nav single-book-nav-shell" id="siteNav" aria-label="<?php esc_attr_e( 'Contact navigation', 'alfred' ); ?>">
			<div class="nav-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Alfred Basta</a></div>
			<?php alfred_front_page_navigation(); ?>
			<form class="nav-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
				<label class="screen-reader-text" for="site-nav-search-contact"><?php esc_html_e( 'Search the site', 'alfred' ); ?></label>
				<input id="site-nav-search-contact" class="nav-search__input" type="search" name="s" placeholder="<?php esc_attr_e( 'Search', 'alfred' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="submit" class="nav-search__submit"><?php esc_html_e( 'Search', 'alfred' ); ?></button>
			</form>
			<button class="nav-toggle" id="navToggle" type="button" aria-label="<?php esc_attr_e( 'Toggle menu', 'alfred' ); ?>" aria-expanded="false" aria-controls="navLinks">
				<span></span><span></span><span></span>
			</button>
		</nav>

		<section class="book-hero contact-page-hero" id="home">
			<div class="book-hero__backdrop"></div>
			<div class="container contact-page-hero__container">
				<div class="book-hero__content contact-page-hero__content">
					<div class="book-hero__eyebrow reveal"><?php echo esc_html( $contact_kicker ); ?></div>
					<h1 class="book-hero__title reveal reveal-delay-1"><?php the_title(); ?></h1>
					<div class="book-hero__description reveal reveal-delay-2 visible">
						<?php echo wp_kses_post( wpautop( $contact_intro ) ); ?>
					</div>
					<div class="book-hero__actions reveal reveal-delay-3">
						<a href="mailto:<?php echo esc_attr( antispambot( $contact_email ) ); ?>" class="btn-primary"><?php esc_html_e( 'Email Alfred', 'alfred' ); ?></a>
						<a href="<?php echo esc_url( alfred_get_book_archive_url() ); ?>" class="btn-outline"><?php esc_html_e( 'Browse Books', 'alfred' ); ?></a>
					</div>
				</div>
				<div class="contact-page-hero__panel reveal reveal-delay-2">
					<div class="contact-card">
						<div class="contact-card__label"><?php esc_html_e( 'Email', 'alfred' ); ?></div>
						<a href="mailto:<?php echo esc_attr( antispambot( $contact_email ) ); ?>"><?php echo esc_html( antispambot( $contact_email ) ); ?></a>
					</div>
					<?php if ( $contact_phone ) : ?>
						<div class="contact-card">
							<div class="contact-card__label"><?php esc_html_e( 'Phone', 'alfred' ); ?></div>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $contact_phone ) ); ?>"><?php echo esc_html( $contact_phone ); ?></a>
						</div>
					<?php endif; ?>
					<div class="contact-card">
						<div class="contact-card__label"><?php esc_html_e( 'Location', 'alfred' ); ?></div>
						<span><?php echo esc_html( $contact_place ); ?></span>
					</div>
				</div>
			</div>
		</section>

		<div class="section-separator"></div>

		<section class="section section-white contact-page-content">
			<div class="container contact-page-content__container">
				<div class="contact-page-content__intro reveal">
					<span class="section-label"><?php esc_html_e( 'Inquiries', 'alfred' ); ?></span>
					<div class="gold-rule"></div>
					<h2 class="section-title"><?php esc_html_e( 'Start a', 'alfred' ); ?> <em><?php esc_html_e( 'Conversation', 'alfred' ); ?></em></h2>
				</div>

				<div class="contact-page-content__body reveal reveal-delay-1">
					<?php if ( '' !== trim( get_the_content() ) ) : ?>
						<?php the_content(); ?>
					<?php else : ?>
						<div class="contact-inquiry-grid">
							<div class="contact-inquiry">
								<h3><?php esc_html_e( 'Speaking & Consulting', 'alfred' ); ?></h3>
								<p><?php esc_html_e( 'Invite Dr. Basta for cybersecurity, education, leadership, or faith-focused conversations.', 'alfred' ); ?></p>
							</div>
							<div class="contact-inquiry">
								<h3><?php esc_html_e( 'Publisher Inquiries', 'alfred' ); ?></h3>
								<p><?php esc_html_e( 'Reach out about books, interviews, excerpts, rights, or future publishing opportunities.', 'alfred' ); ?></p>
							</div>
							<div class="contact-inquiry">
								<h3><?php esc_html_e( 'Reader Correspondence', 'alfred' ); ?></h3>
								<p><?php esc_html_e( "Send notes, questions, and reflections related to Alfred's books and teaching.", 'alfred' ); ?></p>
							</div>
						</div>
					<?php endif; ?>
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
						<li><a href="mailto:<?php echo esc_attr( antispambot( $contact_email ) ); ?>"><?php echo esc_html( antispambot( $contact_email ) ); ?></a></li>
						<li><a href="https://cloudsecurityalliance.org/profiles/alfred-basta" target="_blank" rel="noopener">Cloud Security Alliance</a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Speaking &amp; Consulting</a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Publisher Inquiries</a></li>
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

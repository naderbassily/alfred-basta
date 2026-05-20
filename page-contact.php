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
					<?php if ( isset( $_GET['contact_status'] ) && 'sent' === sanitize_key( wp_unslash( $_GET['contact_status'] ) ) ) : ?>
						<div class="contact-form-notice contact-form-notice--success" role="status">
							<?php esc_html_e( 'Thank you. Your message has been sent.', 'alfred' ); ?>
						</div>
					<?php elseif ( isset( $_GET['contact_status'] ) && 'error' === sanitize_key( wp_unslash( $_GET['contact_status'] ) ) ) : ?>
						<div class="contact-form-notice contact-form-notice--error" role="alert">
							<?php esc_html_e( 'Something went wrong. Please check the form and try again.', 'alfred' ); ?>
						</div>
					<?php endif; ?>

					<form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="alfred_contact_form">
						<?php wp_nonce_field( 'alfred_contact_form', 'alfred_contact_nonce' ); ?>

						<p class="contact-form__hidden">
							<label for="contact-website"><?php esc_html_e( 'Website', 'alfred' ); ?></label>
							<input id="contact-website" type="text" name="contact_website" tabindex="-1" autocomplete="off">
						</p>

						<div class="contact-form__row">
							<p class="contact-form__field">
								<label for="contact-name"><?php esc_html_e( 'Name', 'alfred' ); ?></label>
								<input id="contact-name" type="text" name="contact_name" autocomplete="name" required>
							</p>
							<p class="contact-form__field">
								<label for="contact-email"><?php esc_html_e( 'Email', 'alfred' ); ?></label>
								<input id="contact-email" type="email" name="contact_email" autocomplete="email" required>
							</p>
						</div>

						<p class="contact-form__field">
							<label for="contact-subject"><?php esc_html_e( 'Subject', 'alfred' ); ?></label>
							<input id="contact-subject" type="text" name="contact_subject" required>
						</p>

						<p class="contact-form__field">
							<label for="contact-message"><?php esc_html_e( 'Message', 'alfred' ); ?></label>
							<textarea id="contact-message" name="contact_message" rows="7" required></textarea>
						</p>

						<button type="submit" class="btn-primary contact-form__submit"><?php esc_html_e( 'Send Message', 'alfred' ); ?></button>
					</form>

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

			<?php alfred_custom_site_footer(); ?>
	</main>
	<?php
endwhile;

get_footer();

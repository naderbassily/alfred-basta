<?php
/**
 * Homepage landing markup.
 *
 * @package Alfred_Basta
 */

$hero_image_uri  = ! empty( $args['hero_image_uri'] ) ? $args['hero_image_uri'] : '';
$about_image_uri = ! empty( $args['about_image_uri'] ) ? $args['about_image_uri'] : '';
$posts_page_url  = ! empty( $args['posts_page_url'] ) ? $args['posts_page_url'] : '#blog';
$book_archive_url = post_type_exists( 'book' ) ? alfred_get_book_archive_url() : '';
$book_query       = new WP_Query(
	array(
		'post_type'      => 'book',
		'posts_per_page' => 4,
		'post_status'    => 'publish',
	)
);
?>

<main id="primary" class="site-main front-page-landing">
	<nav class="site-nav" id="siteNav" aria-label="<?php esc_attr_e( 'Homepage navigation', 'alfred' ); ?>">
		<div class="nav-logo">Alfred Basta</div>
		<?php
		alfred_front_page_navigation();
		?>
		<form class="nav-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
			<label class="screen-reader-text" for="site-nav-search-home"><?php esc_html_e( 'Search the site', 'alfred' ); ?></label>
			<input id="site-nav-search-home" class="nav-search__input" type="search" name="s" placeholder="<?php esc_attr_e( 'Search site', 'alfred' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
			<button type="submit" class="nav-search__submit"><?php esc_html_e( 'Search', 'alfred' ); ?></button>
		</form>
		<button class="nav-toggle" id="navToggle" type="button" aria-label="<?php esc_attr_e( 'Toggle menu', 'alfred' ); ?>" aria-expanded="false" aria-controls="navLinks">
			<span></span><span></span><span></span>
		</button>
	</nav>

	<section class="hero" id="home">
		<div class="hero-photo">
			<?php if ( $hero_image_uri ) : ?>
				<img src="<?php echo esc_url( $hero_image_uri ); ?>" alt="<?php esc_attr_e( 'Dr. Alfred Basta', 'alfred' ); ?>" id="heroImg">
			<?php endif; ?>
		</div>

		<div class="hero-content">
			<span class="hero-eyebrow">Professor · Author · Cybersecurity Expert · Ph.D. Cryptography</span>
			<h1 class="hero-name">Alfred Basta</h1>
			<p class="hero-tagline">
				Author of <em>Penetration Testing from Contract to Report</em>,<br>
				<em>Grief: A Christian Perspective</em> and more...
			</p>
			<a href="#books" class="btn-primary">Browse Books</a>
		</div>

		<div class="scroll-indicator">
			<span>Scroll</span>
			<div class="scroll-line"></div>
		</div>
	</section>

	<div class="section-separator"></div>

	<section class="section books-section section-white" id="books">
		<div class="container">
			<span class="section-label reveal">Selected Works</span>
			<div class="gold-rule reveal reveal-delay-1"></div>
			<h2 class="section-title reveal reveal-delay-1">Featured <em>Books</em></h2>
			<p class="section-subtitle reveal reveal-delay-2">
				Published by Wiley, Cengage, and Amazon spanning cybersecurity, mathematics,
				faith, and the full depth of human experience.
			</p>

			<div class="books-grid">
				<?php if ( $book_query->have_posts() ) : ?>
					<?php
					$delay_classes = array(
						'reveal-delay-1',
						'reveal-delay-2',
						'reveal-delay-3',
						'reveal-delay-4',
					);
					?>
					<?php while ( $book_query->have_posts() ) : ?>
						<?php
						$book_query->the_post();
						$delay_class   = $delay_classes[ $book_query->current_post ] ?? 'reveal-delay-1';
						$book_taxonomy = '';
						$book_terms    = array();
						$taxonomies    = get_object_taxonomies( get_post_type(), 'names' );

						foreach ( $taxonomies as $taxonomy ) {
							if ( in_array( $taxonomy, array( 'category', 'post_tag' ), true ) ) {
								continue;
							}

							$terms = get_the_terms( get_the_ID(), $taxonomy );

							if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
								$book_taxonomy = $taxonomy;
								$book_terms    = $terms;
								break;
							}
						}

						$book_label = '';

						if ( ! empty( $book_terms ) ) {
							$book_label = $book_terms[0]->name;
						}

						$book_excerpt = get_the_excerpt();
						?>
						<article class="book-card reveal <?php echo esc_attr( $delay_class ); ?>">
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
										<span>View Book</span>
									</div>
								</div>
							</a>
							<div class="book-genre"><?php echo esc_html( $book_label ); ?></div>
							<h3 class="book-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<div class="book-desc"><?php echo esc_html( $book_excerpt ); ?></div>
						</article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<div class="book-card reveal reveal-delay-1">
						<div class="book-cover-wrap">
							<div class="book-placeholder">
								<div class="book-placeholder-num">No Books Yet</div>
								<div class="book-placeholder-title">Create book posts to populate this section.</div>
							</div>
						</div>
						<div class="book-genre">Book CPT</div>
						<div class="book-title">Featured Books</div>
						<div class="book-desc">This section will automatically pull the latest published books once the book custom post type has content.</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="books-cta reveal">
				<a href="<?php echo esc_url( $book_archive_url ? $book_archive_url : '#contact' ); ?>" class="btn-outline">View All Books</a>
			</div>
		</div>
	</section>

	<div class="section-separator"></div>

	<section class="about-section" id="about">
		<div class="about-image-col">
			<?php if ( $about_image_uri ) : ?>
				<img src="<?php echo esc_url( $about_image_uri ); ?>" alt="<?php esc_attr_e( 'Dr. Alfred Basta portrait', 'alfred' ); ?>">
			<?php endif; ?>
		</div>
		<div class="about-content-col">
			<span class="section-label reveal">About Dr. Alfred Basta</span>
			<div class="gold-rule reveal reveal-delay-1"></div>
			<h2 class="section-title reveal reveal-delay-1">Scholar, Author,<br><em>and Man of Faith</em></h2>
			<blockquote class="about-quote reveal reveal-delay-2">
				"Dr. Basta represents a rare synthesis of scholarly rigor, practical expertise,
				and spiritual depth demonstrating that excellence in technical mastery and
				spiritual formation is not only compatible, but mutually enriching."
			</blockquote>
			<p class="section-subtitle reveal reveal-delay-3 about-copy">
				Dr. Alfred Basta is a distinguished Professor of Cybersecurity, Computer Science,
				and Mathematics with a Ph.D. in Cryptography. With nearly three decades of academic
				leadership at Purdue Global, Georgia State University, and UC San Diego, he has
				established himself as a towering figure in cybersecurity education and a
				deeply pastoral voice in Christian ministry.
			</p>
			<div class="about-stats reveal reveal-delay-4">
				<div class="stat-item">
					<div class="stat-num">40+</div>
					<div class="stat-label">Books Published</div>
				</div>
				<div class="stat-item">
					<div class="stat-num">60+</div>
					<div class="stat-label">Certifications</div>
				</div>
				<div class="stat-item">
					<div class="stat-num">28+</div>
					<div class="stat-label">Years Teaching</div>
				</div>
				<div class="stat-item">
					<div class="stat-num">3</div>
					<div class="stat-label">Patents &amp; Copyrights</div>
				</div>
			</div>
			<div class="about-cta reveal reveal-delay-5">
				<a href="<?php echo esc_url( home_url( '/about-alfred/' ) ); ?>" class="btn-outline">Read Full Story</a>
			</div>
		</div>
	</section>

	<div class="section-separator"></div>

	<section class="section testimonials-section section-white" id="testimonials">
		<div class="container">
			<span class="section-label reveal">What Readers Say</span>
			<div class="gold-rule reveal reveal-delay-1"></div>
			<h2 class="section-title reveal reveal-delay-1">Words That <em>Matter</em></h2>
			<p class="section-subtitle reveal reveal-delay-2">
				From students, colleagues, and readers whose lives were shaped by Alfred's work.
			</p>

			<div class="testimonials-track">
				<div class="testimonial-card reveal reveal-delay-1">
					<div class="testimonial-stars">
						<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
					</div>
					<p class="testimonial-text">
						"Dr. Basta's writing on grief is unlike anything I've encountered. He holds
						theological depth and raw pastoral compassion in the same hand. It gave me
						language for my pain and unshakeable hope in God's faithfulness."
					</p>
					<div class="testimonial-author">
						<div class="author-avatar">SM</div>
						<div>
							<div class="author-info-name">Sarah M.</div>
							<div class="author-info-role">Reader · Woodstock, GA</div>
						</div>
					</div>
				</div>

				<div class="testimonial-card reveal reveal-delay-2">
					<div class="testimonial-stars">
						<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
					</div>
					<p class="testimonial-text">
						"I've adopted Dr. Basta's penetration testing textbook as the core text in
						my graduate security program. It is the most thorough, field-ready guide
						available. His ability to translate expert practice into teachable content
						is exceptional."
					</p>
					<div class="testimonial-author">
						<div class="author-avatar">RK</div>
						<div>
							<div class="author-info-name">Prof. Robert K.</div>
							<div class="author-info-role">Cybersecurity Program Director</div>
						</div>
					</div>
				</div>

				<div class="testimonial-card reveal reveal-delay-3">
					<div class="testimonial-stars">
						<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
					</div>
					<p class="testimonial-text">
						"Dr. Basta's textbooks got me through my OSCP certification. The way he
						structures complex security concepts methodically, clearly, without losing
						depth, is unlike any other author in this field. I keep his books on my desk."
					</p>
					<div class="testimonial-author">
						<div class="author-avatar">JL</div>
						<div>
							<div class="author-info-name">James L.</div>
							<div class="author-info-role">OSCP Certified · Security Engineer</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="section-separator"></div>

	<section class="newsletter-section" id="newsletter">
		<div class="newsletter-inner">
			<div class="newsletter-icon reveal">✉</div>
			<span class="section-label reveal reveal-delay-1 newsletter-label">Stay Connected</span>
			<h2 class="section-title reveal reveal-delay-2">
				Join Alfred's<br><em>Inner Circle</em>
			</h2>
			<p class="section-subtitle reveal reveal-delay-3">
				Get new book announcements, exclusive excerpts, and personal reflections
				delivered to your inbox. No noise, only substance.
			</p>
			<form class="newsletter-form reveal reveal-delay-4" id="newsletterForm">
				<input type="email" placeholder="Your email address" required aria-label="<?php esc_attr_e( 'Email address', 'alfred' ); ?>">
				<button type="submit">Subscribe</button>
			</form>
			<p class="newsletter-fine reveal reveal-delay-5">No spam. Unsubscribe anytime. We respect your inbox.</p>
		</div>
	</section>

	<div class="section-separator"></div>

	<section class="section blog-section section-white" id="blog">
		<div class="container">
			<span class="section-label reveal">From the Desk of Dr. Basta</span>
			<div class="gold-rule reveal reveal-delay-1"></div>
			<h2 class="section-title reveal reveal-delay-1">Latest <em>Writing</em></h2>
			<p class="section-subtitle reveal reveal-delay-2">
				Perspectives on cybersecurity, faith, mathematics, and the intersection of
				rigorous thinking with deep human meaning.
			</p>

			<div class="blog-grid">
				<div class="blog-card reveal reveal-delay-1">
					<div class="blog-card-image">
						<div class="blog-img-placeholder"><span>Blog Post Cover</span></div>
					</div>
					<div class="blog-card-body">
						<div class="blog-meta">
							<span class="blog-tag">Faith</span>
							<span class="blog-date">Coming Soon</span>
						</div>
						<h3 class="blog-card-title">Navigating Grief Without Losing Faith</h3>
						<p class="blog-card-excerpt">
							Loss strips us of every comfortable certainty. But it also creates the conditions
							where the sovereignty of God becomes not a doctrine we recite, but a foundation
							we actually stand on.
						</p>
						<a href="<?php echo esc_url( $posts_page_url ); ?>" class="blog-read-more">Read Article</a>
					</div>
				</div>

				<div class="blog-card reveal reveal-delay-2">
					<div class="blog-card-image">
						<div class="blog-img-placeholder"><span>Blog Post Cover</span></div>
					</div>
					<div class="blog-card-body">
						<div class="blog-meta">
							<span class="blog-tag">Cybersecurity</span>
							<span class="blog-date">Coming Soon</span>
						</div>
						<h3 class="blog-card-title">Why Every Security Breach Starts With a Human Decision</h3>
						<p class="blog-card-excerpt">
							After 28 years in the field, one truth keeps reasserting itself: the most
							sophisticated technical controls fail when the human element is ignored.
							Security culture is not optional, it is foundational.
						</p>
						<a href="<?php echo esc_url( $posts_page_url ); ?>" class="blog-read-more">Read Article</a>
					</div>
				</div>

				<div class="blog-card reveal reveal-delay-3">
					<div class="blog-card-image">
						<div class="blog-img-placeholder"><span>Blog Post Cover</span></div>
					</div>
					<div class="blog-card-body">
						<div class="blog-meta">
							<span class="blog-tag">Education</span>
							<span class="blog-date">Coming Soon</span>
						</div>
						<h3 class="blog-card-title">Mathematics Is the Language Cybersecurity Has Always Spoken</h3>
						<p class="blog-card-excerpt">
							Students ask why they need to understand cryptographic math when the tools
							handle everything. The answer is simple: tools fail. Understanding does not.
						</p>
						<a href="<?php echo esc_url( $posts_page_url ); ?>" class="blog-read-more">Read Article</a>
					</div>
				</div>
			</div>

			<div class="books-cta reveal blog-cta">
				<a href="<?php echo esc_url( $posts_page_url ); ?>" class="btn-outline">View All Articles</a>
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
					<li><a href="#books">Cybersecurity &amp; Pen Testing</a></li>
					<li><a href="#books">Mathematics &amp; Cryptography</a></li>
					<li><a href="#books">Faith &amp; Spirituality</a></li>
					<li><a href="#books">Linux &amp; Networking</a></li>
					<li><a href="#books">Database Security</a></li>
				</ul>
			</div>

			<div class="footer-col">
				<div class="footer-col-title">Get in Touch</div>
				<ul class="footer-links">
					<li><a href="mailto:<?php echo esc_attr( antispambot( 'contact@alfredbasta.com' ) ); ?>"><?php echo esc_html( antispambot( 'contact@alfredbasta.com' ) ); ?></a></li>
					<li><a href="https://cloudsecurityalliance.org/profiles/alfred-basta" target="_blank" rel="noopener">Cloud Security Alliance</a></li>
					<li><a href="#contact">Speaking &amp; Consulting</a></li>
					<li><a href="#contact">Publisher Inquiries</a></li>
				</ul>
			</div>
		</div>

		<div class="footer-bottom">
			<p class="footer-copy">© <?php echo esc_html( wp_date( 'Y' ) ); ?> <span>Dr. Alfred Basta</span>. All rights reserved.</p>
			<p class="footer-copy">Professor · Author · Cybersecurity Expert · Woodstock, GA</p>
		</div>
	</footer>
</main>

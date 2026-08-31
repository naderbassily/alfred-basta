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
$front_page_id    = get_queried_object_id();
$about_quote      = 'Dr. Basta represents a rare synthesis of scholarly rigor, practical expertise, and spiritual depth demonstrating that excellence in technical mastery and spiritual formation is not only compatible, but mutually enriching.';
$about_summary    = 'Dr. Alfred Basta is a distinguished Professor of Cybersecurity, Computer Science, and Mathematics with a Ph.D. in Cryptography. With nearly three decades of academic leadership at Purdue Global, Georgia State University, and UC San Diego, he has established himself as a towering figure in cybersecurity education and a deeply pastoral voice in Christian ministry.';
$about_stats      = array(
	array(
		'field'    => 'books_published_count',
		'value'    => '40+',
		'label'    => 'Books Published',
	),
	array(
		'field'    => 'certifications_count',
		'value'    => '60+',
		'label'    => 'Certifications',
	),
	array(
		'field'    => 'years_teaching_experience',
		'value'    => '28+',
		'label'    => 'Years Teaching',
	),
	array(
		'field'    => 'patents_and_copyrights',
		'value'    => '3',
		'label'    => 'Patents & Copyrights',
	),
);

if ( $front_page_id ) {
	$about_quote_field   = function_exists( 'get_field' ) ? get_field( 'about_quote', $front_page_id ) : get_post_meta( $front_page_id, 'about_quote', true );
	$about_summary_field = function_exists( 'get_field' ) ? get_field( 'about_alfred_summary', $front_page_id ) : get_post_meta( $front_page_id, 'about_alfred_summary', true );

	if ( is_string( $about_quote_field ) && '' !== trim( $about_quote_field ) ) {
		$about_quote = trim( $about_quote_field );
	}

	if ( is_string( $about_summary_field ) && '' !== trim( $about_summary_field ) ) {
		$about_summary = trim( $about_summary_field );
	}

	foreach ( $about_stats as $index => $about_stat ) {
		$stat_value = function_exists( 'get_field' ) ? get_field( $about_stat['field'], $front_page_id ) : get_post_meta( $front_page_id, $about_stat['field'], true );

		if ( is_array( $stat_value ) ) {
			continue;
		}

		$stat_value = trim( (string) $stat_value );

		if ( '' !== $stat_value ) {
			$about_stats[ $index ]['value'] = $stat_value;
		}
	}
}

$book_query       = new WP_Query(
	array(
		'post_type'           => 'book',
		'posts_per_page'      => 8,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
	)
);
$blog_query       = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
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
			<input id="site-nav-search-home" class="nav-search__input" type="search" name="s" placeholder="<?php esc_attr_e( 'Search', 'alfred' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
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
				<?php echo wp_kses_post( wpautop( $about_quote ) ); ?>
			</blockquote>
			<div class="section-subtitle reveal reveal-delay-3 about-copy">
				<?php echo wp_kses_post( wpautop( $about_summary ) ); ?>
			</div>
			<div class="about-stats reveal reveal-delay-4">
				<?php foreach ( $about_stats as $about_stat ) : ?>
					<div class="stat-item">
						<div class="stat-num"><?php echo esc_html( $about_stat['value'] ); ?></div>
						<div class="stat-label"><?php echo esc_html( $about_stat['label'] ); ?></div>
					</div>
				<?php endforeach; ?>
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
						"Dr. Alfred Basta's unwavering support made this book possible and he has
						been my beacon throughout my academic journey. Your wisdom and encouragement
						have shaped not only my career but also my character."
					</p>
					<div class="testimonial-author">
						<div class="author-avatar">SD</div>
						<div>
							<div class="author-info-name">Stephan DeLong</div>
							<div class="author-info-role">Cybersecurity Researcher and Co-Author</div>
						</div>
					</div>
				</div>

				<div class="testimonial-card reveal reveal-delay-2">
					<div class="testimonial-stars">
						<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
					</div>
					<p class="testimonial-text">
						"Great Book for Newbies... This book is designed to give readers of all
						backgrounds and experience levels a well-researched and engaging introduction
						to the fascinating realm of network security. It teaches the skills needed to
						go from hoping a system is secure to knowing that it is."
					</p>
					<div class="testimonial-author">
						<div class="author-avatar">VR</div>
						<div>
							<div class="author-info-name">Verified Reader</div>
							<div class="author-info-role">IT Student Review</div>
						</div>
					</div>
				</div>

				<div class="testimonial-card reveal reveal-delay-3">
					<div class="testimonial-stars">
						<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
					</div>
					<p class="testimonial-text">
						"Dr. Basta's extensive curriculum and insights effectively close the
						knowledge gap between the penetration tester and security analyst jobs.
						His framework provides the lab exercises and real-world experience needed
						to execute pen testing contracts without hassle."
					</p>
					<div class="testimonial-author">
						<div class="author-avatar">EC</div>
						<div>
							<div class="author-info-name">EC-Council</div>
							<div class="author-info-role">International Council of E-Commerce Consultants</div>
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
				<?php if ( $blog_query->have_posts() ) : ?>
					<?php
					$blog_delay_classes = array(
						'reveal-delay-1',
						'reveal-delay-2',
						'reveal-delay-3',
					);
					?>
					<?php while ( $blog_query->have_posts() ) : ?>
						<?php
						$blog_query->the_post();
						$delay_class  = $blog_delay_classes[ $blog_query->current_post ] ?? 'reveal-delay-1';
						$categories   = get_the_category();
						$blog_label   = ! empty( $categories ) ? $categories[0]->name : __( 'Article', 'alfred' );
						$blog_excerpt = trim( get_post_field( 'post_excerpt', get_the_ID() ) );
						?>
						<article class="blog-card reveal <?php echo esc_attr( $delay_class ); ?>">
							<a class="blog-card-image" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'large' ); ?>
								<?php else : ?>
									<div class="blog-img-placeholder"><span><?php esc_html_e( 'Blog Post Cover', 'alfred' ); ?></span></div>
								<?php endif; ?>
							</a>
							<div class="blog-card-body">
								<div class="blog-meta">
									<span class="blog-tag"><?php echo esc_html( $blog_label ); ?></span>
								</div>
								<h3 class="blog-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<?php if ( $blog_excerpt ) : ?>
									<p class="blog-card-excerpt"><?php echo esc_html( $blog_excerpt ); ?></p>
								<?php endif; ?>
								<a href="<?php the_permalink(); ?>" class="blog-read-more"><?php esc_html_e( 'Read Article', 'alfred' ); ?></a>
							</div>
						</article>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<div class="blog-card reveal reveal-delay-1">
						<div class="blog-card-image">
							<div class="blog-img-placeholder"><span><?php esc_html_e( 'Blog Post Cover', 'alfred' ); ?></span></div>
						</div>
						<div class="blog-card-body">
							<div class="blog-meta">
								<span class="blog-tag"><?php esc_html_e( 'Author Blog', 'alfred' ); ?></span>
							</div>
							<h3 class="blog-card-title"><?php esc_html_e( 'New articles will appear here', 'alfred' ); ?></h3>
							<p class="blog-card-excerpt"><?php esc_html_e( 'Publish WordPress posts to populate this section with Alfred Basta author blog entries.', 'alfred' ); ?></p>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="books-cta reveal blog-cta">
				<a href="<?php echo esc_url( $posts_page_url ); ?>" class="btn-outline">View All Articles</a>
			</div>
		</div>
	</section>

	<div class="section-separator"></div>

	<?php alfred_custom_site_footer(); ?>
</main>

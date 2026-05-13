<?php
/**
 * Template for single book posts.
 *
 * @package Alfred_Basta
 */

get_header();

while ( have_posts() ) :
	the_post();

	$acf_fields = function_exists( 'get_fields' ) ? (array) get_fields() : array();

	$book_label      = alfred_get_book_label( get_the_ID() );
	$book_hero_label = '';
	$taxonomies      = get_object_taxonomies( get_post_type( get_the_ID() ), 'names' );

	foreach ( $taxonomies as $taxonomy ) {
		if ( in_array( $taxonomy, array( 'category', 'post_tag' ), true ) ) {
			continue;
		}

		$terms = get_the_terms( get_the_ID(), $taxonomy );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$book_hero_label = $terms[0]->name;
			break;
		}
	}

	if ( ! $book_hero_label ) {
		$book_hero_label = alfred_get_first_acf_value(
			$acf_fields,
			array(
				'category',
				'genre',
				'topic',
				'subject',
				'book_category',
			)
		);

		if ( is_array( $book_hero_label ) ) {
			if ( isset( $book_hero_label['label'] ) && is_string( $book_hero_label['label'] ) ) {
				$book_hero_label = $book_hero_label['label'];
			} elseif ( isset( $book_hero_label['name'] ) && is_string( $book_hero_label['name'] ) ) {
				$book_hero_label = $book_hero_label['name'];
			} else {
				$book_hero_label = '';
			}
		}
	}

	$description = alfred_get_first_acf_value(
		$acf_fields,
		array(
			'description',
			'book_description',
			'overview',
			'synopsis',
			'summary',
			'long_description',
		)
	);

	if ( ! $description ) {
		$description = has_excerpt() ? get_the_excerpt() : get_the_content();
	}

	$buy_link = alfred_get_first_acf_value(
		$acf_fields,
		array(
			'amazon_link',
			'amazon_url',
			'amazon',
			'buy_link',
			'purchase_link',
			'external_link',
			'book_link',
		)
	);

	$buy_url   = '';
	$buy_label = 'Buy on Amazon';

	if ( is_array( $buy_link ) ) {
		$buy_url   = ! empty( $buy_link['url'] ) ? $buy_link['url'] : '';
		$buy_label = ! empty( $buy_link['title'] ) ? $buy_link['title'] : $buy_label;
	} elseif ( is_string( $buy_link ) ) {
		$buy_url = $buy_link;
	}

	$detail_keys = array(
		'publisher'   => 'Publisher',
		'imprint'     => 'Imprint',
		'isbn'        => 'ISBN',
		'isbn_13'     => 'ISBN-13',
		'isbn13'      => 'ISBN-13',
		'asin'        => 'ASIN',
		'pages'       => 'Pages',
		'page_count'  => 'Pages',
		'edition'     => 'Edition',
		'publication' => 'Publication',
	);

	$detail_items = array();

	$release_date = alfred_get_first_acf_value(
		$acf_fields,
		array(
			'release_date',
			'publication_date',
			'published_date',
			'publish_date',
		)
	);

	if ( is_array( $release_date ) ) {
		$release_date = '';
	}

	if ( ! $release_date ) {
		$release_date = get_the_date( 'F j, Y' );
	}

	foreach ( $detail_keys as $field_name => $label ) {
		$value = alfred_get_first_acf_value( $acf_fields, array( $field_name ) );

		if ( null === $value || '' === $value || is_array( $value ) ) {
			continue;
		}

		$detail_items[] = array(
			'label' => $label,
			'value' => $value,
		);
	}

	if ( $release_date ) {
		$pages_index = null;

		foreach ( $detail_items as $index => $detail_item ) {
			if ( 'Pages' === $detail_item['label'] ) {
				$pages_index = $index;
				break;
			}
		}

		$release_date_item = array(
			'label' => 'Release Date',
			'value' => $release_date,
		);

		if ( null !== $pages_index ) {
			array_splice( $detail_items, $pages_index + 1, 0, array( $release_date_item ) );
		} else {
			$detail_items[] = $release_date_item;
		}
	}

	$related_books = new WP_Query(
		array(
			'post_type'      => 'book',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'post__not_in'   => array( get_the_ID() ),
		)
	);
	?>

	<main id="primary" class="site-main single-book-page">
		<nav class="site-nav single-book-nav-shell" id="siteNav" aria-label="<?php esc_attr_e( 'Book navigation', 'alfred' ); ?>">
			<div class="nav-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Alfred Basta</a></div>
			<?php alfred_front_page_navigation(); ?>
			<form class="nav-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
				<label class="screen-reader-text" for="site-nav-search-book"><?php esc_html_e( 'Search the site', 'alfred' ); ?></label>
				<input id="site-nav-search-book" class="nav-search__input" type="search" name="s" placeholder="<?php esc_attr_e( 'Search', 'alfred' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
				<button type="submit" class="nav-search__submit"><?php esc_html_e( 'Search', 'alfred' ); ?></button>
			</form>
			<button class="nav-toggle" id="navToggle" type="button" aria-label="<?php esc_attr_e( 'Toggle menu', 'alfred' ); ?>" aria-expanded="false" aria-controls="navLinks">
				<span></span><span></span><span></span>
			</button>
		</nav>

		<section class="book-hero" id="home">
			<div class="book-hero__backdrop"></div>
			<div class="container book-hero__container">
				<div class="book-hero__media reveal reveal-delay-1">
					<div class="book-hero__cover-shell">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', array( 'class' => 'book-hero__cover' ) ); ?>
						<?php else : ?>
							<div class="book-hero__placeholder">
								<span><?php echo esc_html( get_the_date( 'Y' ) ); ?></span>
								<strong><?php the_title(); ?></strong>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="book-hero__content">
						<?php if ( $book_hero_label ) : ?>
							<div class="book-hero__eyebrow reveal"><?php echo esc_html( $book_hero_label ); ?></div>
						<?php endif; ?>
					<h1 class="book-hero__title reveal reveal-delay-1"><?php the_title(); ?></h1>

					<?php if ( $description ) : ?>
						<div class="book-hero__description reveal reveal-delay-2">
							<?php echo wp_kses_post( wpautop( $description ) ); ?>
						</div>
					<?php endif; ?>

						<div class="book-hero__actions reveal reveal-delay-3">
							<a href="<?php echo esc_url( alfred_get_book_archive_url() ); ?>" class="btn-outline">Browse All Books</a>
						<?php if ( $buy_url ) : ?>
							<a href="<?php echo esc_url( $buy_url ); ?>" class="btn-primary" target="_blank" rel="noopener"><?php echo esc_html( $buy_label ); ?></a>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $detail_items ) ) : ?>
						<div class="book-meta-grid reveal reveal-delay-4">
							<?php foreach ( $detail_items as $detail_item ) : ?>
								<div class="book-meta-card">
									<div class="book-meta-card__label"><?php echo esc_html( $detail_item['label'] ); ?></div>
									<div class="book-meta-card__value"><?php echo esc_html( $detail_item['value'] ); ?></div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<div class="section-separator"></div>

		<section class="section section-white single-book-story">
			<div class="container single-book-story__container">
				<div class="single-book-story__intro reveal">
					<span class="section-label">About This Book</span>
					<div class="gold-rule"></div>
					<h2 class="section-title">Read the <em>Full Overview</em></h2>
				</div>

				<div class="single-book-story__body reveal reveal-delay-2">
					<?php the_content(); ?>
				</div>
			</div>
		</section>

		<?php if ( $related_books->have_posts() ) : ?>
			<div class="section-separator"></div>

			<section class="section books-section section-white single-book-related">
				<div class="container">
					<span class="section-label reveal">More From Alfred</span>
					<div class="gold-rule reveal reveal-delay-1"></div>
					<h2 class="section-title reveal reveal-delay-1">Continue Your <em>Reading</em></h2>

					<div class="books-grid">
						<?php
						$delay_classes = array(
							'reveal-delay-1',
							'reveal-delay-2',
							'reveal-delay-3',
						);
						?>
						<?php while ( $related_books->have_posts() ) : ?>
							<?php
							$related_books->the_post();
							$delay_class = $delay_classes[ $related_books->current_post ] ?? 'reveal-delay-1';
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
								<?php
								$related_book_terms = get_the_terms( get_the_ID(), 'book-genre' );
								$related_book_label = '';

								if ( ! empty( $related_book_terms ) && ! is_wp_error( $related_book_terms ) ) {
									$related_book_label = $related_book_terms[0]->name;
								}
								?>
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

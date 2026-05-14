<?php
/**
 * Template for single author blog posts.
 *
 * @package Alfred_Basta
 */

get_header();

while ( have_posts() ) :
	the_post();

	$categories   = get_the_category();
	$primary_term = ! empty( $categories ) ? $categories[0]->name : __( 'Author Blog', 'alfred' );
	$related_args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'post__not_in'        => array( get_the_ID() ),
		'ignore_sticky_posts' => true,
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
			<div class="container book-hero__container blog-single-hero__container">
				<div class="book-hero__media reveal reveal-delay-1">
					<div class="blog-single-hero__image-shell">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', array( 'class' => 'blog-single-hero__image' ) ); ?>
						<?php else : ?>
							<div class="blog-single-hero__placeholder">
								<span><?php echo esc_html( $primary_term ); ?></span>
								<strong><?php the_title(); ?></strong>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="book-hero__content">
					<div class="book-hero__eyebrow reveal"><?php echo esc_html( $primary_term ); ?></div>
					<h1 class="book-hero__title reveal reveal-delay-1"><?php the_title(); ?></h1>
					<div class="book-hero__description reveal reveal-delay-2 visible">
						<p>
							<?php
							printf(
								/* translators: 1: post date, 2: author name. */
								esc_html__( 'Published %1$s by %2$s.', 'alfred' ),
								esc_html( get_the_date() ),
								esc_html( get_the_author() )
							);
							?>
						</p>
					</div>
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
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-single-article reveal' ); ?>>
					<div class="blog-single-article__meta">
						<span><?php echo esc_html( $primary_term ); ?></span>
						<span><?php echo esc_html( get_the_date() ); ?></span>
					</div>

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

				<aside class="blog-single-sidebar reveal reveal-delay-1">
					<div class="blog-single-sidebar__card">
						<div class="blog-single-sidebar__label"><?php esc_html_e( 'Written By', 'alfred' ); ?></div>
						<h2><?php echo esc_html( get_the_author() ); ?></h2>
						<p><?php esc_html_e( 'Author, professor, cybersecurity expert, and scholar writing across technical mastery, teaching, faith, and formation.', 'alfred' ); ?></p>
					</div>

					<?php if ( has_category() ) : ?>
						<div class="blog-single-sidebar__card">
							<div class="blog-single-sidebar__label"><?php esc_html_e( 'Topics', 'alfred' ); ?></div>
							<div class="blog-single-topic-list">
								<?php the_category( '' ); ?>
							</div>
						</div>
					<?php endif; ?>
				</aside>
			</div>
		</section>

		<?php if ( $related_posts->have_posts() ) : ?>
			<section class="section section-white blog-related-section">
				<div class="container">
					<span class="section-label reveal"><?php esc_html_e( 'Keep Reading', 'alfred' ); ?></span>
					<div class="gold-rule reveal reveal-delay-1"></div>
					<h2 class="section-title reveal reveal-delay-1"><?php esc_html_e( 'Related', 'alfred' ); ?> <em><?php esc_html_e( 'Articles', 'alfred' ); ?></em></h2>

					<div class="blog-archive-grid blog-related-grid">
						<?php
						while ( $related_posts->have_posts() ) :
							$related_posts->the_post();
							$related_categories = get_the_category();
							$related_label      = ! empty( $related_categories ) ? $related_categories[0]->name : __( 'Article', 'alfred' );
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-archive-card reveal reveal-delay-2' ); ?>>
								<a class="blog-archive-card__image" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'large' ); ?>
									<?php else : ?>
										<div class="blog-archive-card__placeholder">
											<span><?php echo esc_html( $related_label ); ?></span>
											<strong><?php the_title(); ?></strong>
										</div>
									<?php endif; ?>
								</a>

								<div class="blog-archive-card__body">
									<div class="blog-archive-card__meta">
										<span><?php echo esc_html( $related_label ); ?></span>
									</div>
									<h3 class="blog-archive-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<div class="blog-archive-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></div>
									<a class="blog-read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read Article', 'alfred' ); ?></a>
								</div>
							</article>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<div class="blog-post-navigation reveal">
			<div class="container">
				<?php
				the_post_navigation(
					array(
						'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous', 'alfred' ) . '</span> <span class="nav-title">%title</span>',
						'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next', 'alfred' ) . '</span> <span class="nav-title">%title</span>',
					)
				);
				?>
			</div>
		</div>

		<?php
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
		?>

		<?php alfred_custom_site_footer(); ?>
	</main>

	<?php
endwhile;

get_footer();

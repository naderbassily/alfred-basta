<?php
/**
 * Template for post archives.
 *
 * @package Alfred_Basta
 */

get_header();

$archive_title       = get_the_archive_title();
$archive_description = get_the_archive_description();

if ( is_category() ) {
	$archive_title = single_cat_title( '', false );
} elseif ( is_tag() ) {
	$archive_title = single_tag_title( '', false );
} elseif ( is_author() ) {
	$archive_title = get_the_author();
}

if ( ! $archive_description ) {
	$archive_description = __( 'Browse Alfred Basta articles by topic, date, and author archive.', 'alfred' );
}
?>

<main id="primary" class="site-main single-book-page blog-page blog-archive-page">
	<?php alfred_custom_site_navigation( __( 'Blog archive navigation', 'alfred' ), 'site-nav-search-blog-archive' ); ?>

	<section class="book-hero blog-hero" id="home">
		<div class="book-hero__backdrop"></div>
		<div class="container book-archive-hero__container">
			<div class="book-archive-hero__content">
				<div class="book-hero__eyebrow reveal"><?php esc_html_e( 'Author Blog', 'alfred' ); ?></div>
				<h1 class="book-hero__title reveal reveal-delay-1"><?php echo esc_html( $archive_title ); ?></h1>
				<div class="book-hero__description book-archive-hero__description reveal reveal-delay-2 visible">
					<?php echo wp_kses_post( wpautop( $archive_description ) ); ?>
				</div>
			</div>
		</div>
	</section>

	<div class="section-separator"></div>

	<section class="section section-white blog-index-section">
		<div class="container">
			<span class="section-label reveal"><?php esc_html_e( 'Archive', 'alfred' ); ?></span>
			<div class="gold-rule reveal reveal-delay-1"></div>
			<h2 class="section-title reveal reveal-delay-1"><?php esc_html_e( 'Browse the', 'alfred' ); ?> <em><?php esc_html_e( 'Articles', 'alfred' ); ?></em></h2>

			<?php if ( have_posts() ) : ?>
				<div class="blog-archive-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						$categories   = get_the_category();
						$label        = ! empty( $categories ) ? $categories[0]->name : __( 'Article', 'alfred' );
						$card_excerpt = trim( get_post_field( 'post_excerpt', get_the_ID() ) );
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-archive-card reveal reveal-delay-2' ); ?>>
							<a class="blog-archive-card__image" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'large' ); ?>
								<?php else : ?>
									<div class="blog-archive-card__placeholder">
										<span><?php echo esc_html( $label ); ?></span>
										<strong><?php the_title(); ?></strong>
									</div>
								<?php endif; ?>
							</a>

							<div class="blog-archive-card__body">
								<div class="blog-archive-card__meta">
									<span><?php echo esc_html( $label ); ?></span>
								</div>
								<?php if ( $card_excerpt ) : ?>
									<div class="blog-archive-card__excerpt"><?php echo esc_html( $card_excerpt ); ?></div>
								<?php endif; ?>
								<a class="blog-read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read Article', 'alfred' ); ?></a>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<div class="blog-pagination reveal">
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
				<div class="blog-empty reveal reveal-delay-2">
					<h3><?php esc_html_e( 'No articles found', 'alfred' ); ?></h3>
					<p><?php esc_html_e( 'Try another topic or return to the full author blog.', 'alfred' ); ?></p>
					<a class="btn-outline" href="<?php echo esc_url( alfred_get_blog_archive_url() ); ?>"><?php esc_html_e( 'View All Articles', 'alfred' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php alfred_custom_site_footer(); ?>
</main>

<?php
get_footer();

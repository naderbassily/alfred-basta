<?php
/**
 * Template Name: Homepage Template
 *
 * @package Alfred_Basta
 */

get_header();

$hero_image_uri  = '';
$about_image_uri = '/wp-content/uploads/2026/05/basta.jpg';

if ( file_exists( get_theme_file_path( 'assets/images/alfred-hero.jpg' ) ) ) {
	$hero_image_uri = get_theme_file_uri( 'assets/images/alfred-hero.jpg' );
}

$posts_page_url = alfred_get_blog_archive_url();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		if ( '' !== trim( get_the_content() ) ) :
			?>
			<main id="primary" class="site-main front-page-builder-content">
				<?php the_content(); ?>
			</main>
			<?php
		else :
			get_template_part(
				'template-parts/home-landing',
				null,
				array(
					'hero_image_uri'  => $hero_image_uri,
					'about_image_uri' => $about_image_uri,
					'posts_page_url'  => $posts_page_url,
				)
			);
		endif;
	}
}

get_footer();

<?php
/**
 * Front page template.
 *
 * @package Alfred_Basta
 */

get_header();

$hero_image_uri  = '';
$about_image_uri = 'http://alfred-basta.local/wp-content/uploads/2026/05/basta.jpg';
$front_page_has_editor_content = false;

if ( file_exists( get_theme_file_path( 'assets/images/alfred-hero.jpg' ) ) ) {
	$hero_image_uri = get_theme_file_uri( 'assets/images/alfred-hero.jpg' );
}

$posts_page_url = alfred_get_blog_archive_url();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$front_page_has_editor_content = '' !== trim( get_the_content() );
		break;
	}

	rewind_posts();
}

if ( $front_page_has_editor_content ) :
	?>
	<main id="primary" class="site-main front-page-builder-content">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</main>
	<?php
	get_footer();
	return;
endif;
get_template_part(
	'template-parts/home-landing',
	null,
	array(
		'hero_image_uri'  => $hero_image_uri,
		'about_image_uri' => $about_image_uri,
		'posts_page_url'  => $posts_page_url,
	)
);
get_footer();

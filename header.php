<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Alfred_Basta
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'alfred' ); ?></a>

	<?php if ( ! alfred_is_landing_page() && ! alfred_is_book_layout() && ! alfred_is_about_page() && ! alfred_is_contact_page() && ! alfred_is_blog_layout() && ! alfred_is_utility_layout() ) : ?>
		<?php alfred_custom_site_navigation( __( 'Site navigation', 'alfred' ), 'site-nav-search-default' ); ?>
	<?php endif; ?>

<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Alfred_Basta
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function alfred_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	if ( function_exists( 'alfred_is_about_page' ) && alfred_is_about_page() ) {
		$classes[] = 'alfred-about-page';
	}

	if ( function_exists( 'alfred_is_contact_page' ) && alfred_is_contact_page() ) {
		$classes[] = 'alfred-contact-page';
	}

	return $classes;
}
add_filter( 'body_class', 'alfred_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function alfred_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'alfred_pingback_header' );

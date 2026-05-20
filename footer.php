<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Alfred_Basta
 */

?>
<?php if ( ! alfred_is_landing_page() && ! alfred_is_book_layout() && ! alfred_is_about_page() && ! alfred_is_contact_page() && ! alfred_is_blog_layout() && ! alfred_is_utility_layout() ) : ?>
	<?php alfred_custom_site_footer(); ?>
<?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

<?php
/**
 * Alfred Basta functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Alfred_Basta
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function alfred_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Alfred Basta, use a find and replace
		* to change 'alfred' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'alfred', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1'          => esc_html__( 'Primary', 'alfred' ),
			'front-page-menu' => esc_html__( 'Front Page Menu', 'alfred' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'alfred_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'alfred_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function alfred_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'alfred_content_width', 640 );
}
add_action( 'after_setup_theme', 'alfred_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function alfred_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'alfred' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'alfred' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'alfred_widgets_init' );

/**
 * Check whether the current request uses the landing homepage layout.
 *
 * @return bool
 */
function alfred_is_landing_page() {
	return is_front_page() || is_page_template( 'template-homepage.php' );
}

/**
 * Check whether the current request uses the custom book layout.
 *
 * @return bool
 */
function alfred_is_book_layout() {
	return is_singular( 'book' ) || is_post_type_archive( 'book' ) || (bool) get_query_var( 'alfred_book_archive' );
}

/**
 * Check whether the current request is the custom About Alfred page.
 *
 * @return bool
 */
function alfred_is_about_page() {
	return is_page( 'about-alfred' );
}

/**
 * Shared navigation links used across the landing page and book layout.
 *
 * @return array<int, array{anchor:string,label:string}>
 */
function alfred_get_front_page_nav_links() {
	return array(
		array(
			'anchor' => '#home',
			'label'  => 'Home',
		),
		array(
			'anchor' => '#books',
			'label'  => 'Books',
		),
		array(
			'anchor' => '/about-alfred/',
			'label'  => 'About Alfred',
		),
		array(
			'anchor' => '#blog',
			'label'  => 'Blog',
		),
		array(
			'anchor' => '#contact',
			'label'  => 'Contact',
		),
	);
}

/**
 * Resolve a front-page section URL for the current context.
 *
 * @param string $anchor Fragment identifier, e.g. #books.
 * @return string
 */
function alfred_get_front_page_anchor_url( $anchor ) {
	if ( alfred_is_landing_page() ) {
		return $anchor;
	}

	return home_url( '/' ) . ltrim( $anchor, '/' );
}

/**
 * Get the public archive URL for books.
 *
 * @return string
 */
function alfred_get_book_archive_url() {
	$archive_url = get_post_type_archive_link( 'book' );

	if ( $archive_url ) {
		return $archive_url;
	}

	return home_url( '/book/' );
}

/**
 * Register a fallback rewrite for the book archive.
 *
 * Supports themes where the book CPT exists but `has_archive` is disabled.
 */
function alfred_register_book_archive_rewrite() {
	add_rewrite_rule( '^book/?$', 'index.php?alfred_book_archive=1', 'top' );
}
add_action( 'init', 'alfred_register_book_archive_rewrite' );

/**
 * Register custom query vars.
 *
 * @param array $vars Public query vars.
 * @return array
 */
function alfred_register_query_vars( $vars ) {
	$vars[] = 'alfred_book_archive';

	return $vars;
}
add_filter( 'query_vars', 'alfred_register_query_vars' );

/**
 * Force the custom `/book/` route to behave like a book archive.
 *
 * @param WP_Query $query Main query instance.
 */
function alfred_prepare_book_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->get( 'alfred_book_archive' ) ) {
		return;
	}

	$query->set( 'post_type', 'book' );
	$query->set( 'post_status', 'publish' );
	$query->set( 'name', '' );
	$query->set( 'pagename', '' );
	$query->is_home              = false;
	$query->is_page              = false;
	$query->is_singular          = false;
	$query->is_single            = false;
	$query->is_archive           = true;
	$query->is_post_type_archive = true;
}
add_action( 'pre_get_posts', 'alfred_prepare_book_archive_query' );

/**
 * Load the dedicated archive template for the custom `/book/` route.
 *
 * @param string $template Resolved template path.
 * @return string
 */
function alfred_book_archive_template( $template ) {
	if ( get_query_var( 'alfred_book_archive' ) ) {
		$archive_template = locate_template( 'archive-book.php' );

		if ( $archive_template ) {
			return $archive_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'alfred_book_archive_template' );

/**
 * Flush rewrite rules once after registering the fallback book archive route.
 */
function alfred_maybe_flush_book_archive_rewrite() {
	if ( get_option( 'alfred_book_archive_rewrite_flushed_v2' ) ) {
		return;
	}

	flush_rewrite_rules( false );
	update_option( 'alfred_book_archive_rewrite_flushed_v2', 1 );
}
add_action( 'init', 'alfred_maybe_flush_book_archive_rewrite', 99 );

/**
 * Enqueue scripts and styles.
 */
function alfred_scripts() {
	wp_enqueue_style( 'alfred-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'alfred-style', 'rtl', 'replace' );

	if ( alfred_is_landing_page() || alfred_is_book_layout() || alfred_is_about_page() ) {
		wp_enqueue_style(
			'alfred-fonts',
			'https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap',
			array(),
			null
		);
	}

	if ( alfred_is_landing_page() ) {

		wp_enqueue_style(
			'alfred-front-page',
			get_template_directory_uri() . '/assets/css/front-page.css',
			array( 'alfred-style', 'alfred-fonts' ),
			_S_VERSION
		);

		wp_enqueue_script(
			'alfred-front-page',
			get_template_directory_uri() . '/assets/js/front-page.js',
			array(),
			_S_VERSION,
			true
		);
	} elseif ( alfred_is_book_layout() || alfred_is_about_page() ) {
		wp_enqueue_style(
			'alfred-front-page',
			get_template_directory_uri() . '/assets/css/front-page.css',
			array( 'alfred-style', 'alfred-fonts' ),
			_S_VERSION
		);

		wp_enqueue_style(
			'alfred-book',
			get_template_directory_uri() . '/assets/css/book.css',
			array( 'alfred-front-page' ),
			_S_VERSION
		);

		wp_enqueue_script(
			'alfred-front-page',
			get_template_directory_uri() . '/assets/js/front-page.js',
			array(),
			_S_VERSION
		);
	} else {
		wp_enqueue_script( 'alfred-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'alfred_scripts' );

/**
 * Fallback menu for the homepage anchor navigation.
 *
 * @param array $args Menu arguments passed by wp_nav_menu().
 */
function alfred_front_page_menu_fallback( $args = array() ) {
	$links      = alfred_get_front_page_nav_links();
	$menu_id    = ! empty( $args['menu_id'] ) ? $args['menu_id'] : 'navLinks';
	$menu_class = ! empty( $args['menu_class'] ) ? $args['menu_class'] : 'nav-links';

	printf(
		'<ul id="%1$s" class="%2$s">',
		esc_attr( $menu_id ),
		esc_attr( $menu_class )
	);

	foreach ( $links as $link ) {
		printf(
			'<li><a href="%1$s">%2$s</a></li>',
			esc_url( alfred_get_front_page_anchor_url( $link['anchor'] ) ),
			esc_html( $link['label'] )
		);
	}

	echo '</ul>';
}

/**
 * Render the WordPress-managed front page navigation menu.
 *
 * @param string $menu_id    Menu element ID.
 * @param string $menu_class Menu element class.
 * @return void
 */
function alfred_front_page_navigation( $menu_id = 'navLinks', $menu_class = 'nav-links' ) {
	wp_nav_menu(
		array(
			'theme_location' => 'front-page-menu',
			'container'      => false,
			'menu_id'        => $menu_id,
			'menu_class'     => $menu_class,
			'fallback_cb'    => 'alfred_front_page_menu_fallback',
			'depth'          => 1,
		)
	);
}

/**
 * Get the first non-empty value from an array of candidate ACF field names.
 *
 * @param array $fields         Available ACF fields keyed by field name.
 * @param array $candidate_keys Candidate field names.
 * @return mixed
 */
function alfred_get_first_acf_value( $fields, $candidate_keys ) {
	foreach ( $candidate_keys as $candidate_key ) {
		if ( isset( $fields[ $candidate_key ] ) && '' !== $fields[ $candidate_key ] && array() !== $fields[ $candidate_key ] ) {
			return $fields[ $candidate_key ];
		}
	}

	return null;
}

/**
 * Build a display label for book taxonomy and year.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function alfred_get_book_label( $post_id ) {
	$taxonomies = get_object_taxonomies( get_post_type( $post_id ), 'names' );

	foreach ( $taxonomies as $taxonomy ) {
		if ( in_array( $taxonomy, array( 'category', 'post_tag' ), true ) ) {
			continue;
		}

		$terms = get_the_terms( $post_id, $taxonomy );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			return $terms[0]->name . ' · ' . get_the_date( 'Y', $post_id );
		}
	}

	return get_the_date( 'Y', $post_id );
}

/**
 * Normalize a book title for de-duplication.
 *
 * @param string $title Book title.
 * @return string
 */
function alfred_normalize_book_title( $title ) {
	$title = wp_strip_all_tags( html_entity_decode( (string) $title, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
	$title = str_replace( "\xc2\xa0", ' ', $title );
	$title = strtolower( trim( preg_replace( '/\s+/', ' ', $title ) ) );

	return $title;
}

/**
 * Update a book field using ACF when available, falling back to post meta.
 *
 * @param int    $post_id Post ID.
 * @param string $field   Field name.
 * @param mixed  $value   Field value.
 * @return void
 */
function alfred_update_book_field( $post_id, $field, $value ) {
	if ( function_exists( 'update_field' ) ) {
		update_field( $field, $value, $post_id );
	}

	update_post_meta( $post_id, $field, $value );
}

/**
 * Attach a remote image as the book featured image.
 *
 * @param int    $post_id   Post ID.
 * @param string $image_url Remote image URL.
 * @return void
 */
function alfred_import_book_cover( $post_id, $image_url ) {
	if ( ! $image_url || has_post_thumbnail( $post_id ) ) {
		return;
	}

	if ( ! function_exists( 'media_sideload_image' ) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	$attachment_id = media_sideload_image( esc_url_raw( $image_url ), $post_id, null, 'id' );

	if ( ! is_wp_error( $attachment_id ) && $attachment_id ) {
		set_post_thumbnail( $post_id, (int) $attachment_id );
	}
}

/**
 * Import Alfred Basta books from the provided CSV once.
 *
 * @return void
 */
function alfred_import_books_from_csv() {
	$csv_path = '/Users/bassily/Library/Application Support/Claude/local-agent-mode-sessions/383701a4-fbe9-4ead-89a8-d2f999e2b9fa/2a52bb49-eea9-4489-bdb5-3491c73bc9f3/local_f33fd674-6f81-4a50-a715-87af583dbeac/outputs/alfred_basta_books.csv';
	$done_key = 'alfred_books_csv_import_completed_v1';

	if ( get_option( $done_key ) || ! file_exists( $csv_path ) || ! post_type_exists( 'book' ) ) {
		return;
	}

	$handle = fopen( $csv_path, 'r' );

	if ( false === $handle ) {
		update_option(
			$done_key,
			array(
				'status'  => 'failed',
				'message' => 'Unable to open CSV file.',
			)
		);
		return;
	}

	$headers = fgetcsv( $handle );

	if ( empty( $headers ) ) {
		fclose( $handle );
		update_option(
			$done_key,
			array(
				'status'  => 'failed',
				'message' => 'CSV file is empty.',
			)
		);
		return;
	}

	$existing_posts = get_posts(
		array(
			'post_type'      => 'book',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	$existing_asins  = array();
	$existing_titles = array();

	foreach ( $existing_posts as $existing_post_id ) {
		$existing_title                             = alfred_normalize_book_title( get_the_title( $existing_post_id ) );
		$existing_titles[ $existing_title ]         = (int) $existing_post_id;
		$existing_asin                              = (string) get_post_meta( $existing_post_id, 'asin', true );
		$existing_asins[ trim( strtoupper( $existing_asin ) ) ] = (int) $existing_post_id;
	}

	$taxonomies      = get_object_taxonomies( 'book', 'names' );
	$book_taxonomies = array_values(
		array_filter(
			$taxonomies,
			static function( $taxonomy ) {
				return ! in_array( $taxonomy, array( 'category', 'post_tag' ), true );
			}
		)
	);

	$counts = array(
		'created' => 0,
		'skipped' => 0,
		'failed'  => 0,
	);

	while ( false !== ( $row = fgetcsv( $handle ) ) ) {
		$data = array();

		foreach ( $headers as $index => $header ) {
			$data[ $header ] = isset( $row[ $index ] ) ? trim( (string) $row[ $index ] ) : '';
		}

		$title     = $data['title'] ?? '';
		$asin      = strtoupper( $data['asin'] ?? '' );
		$title_key = alfred_normalize_book_title( $title );

		if ( ! $title || isset( $existing_titles[ $title_key ] ) || ( $asin && isset( $existing_asins[ $asin ] ) ) ) {
			++$counts['skipped'];
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => 'book',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_excerpt' => $data['description'] ?? '',
				'post_content' => $data['description'] ?? '',
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			++$counts['failed'];
			continue;
		}

		$field_map = array(
			'amazon_url'       => 'amazon_url',
			'amazon_link'      => 'amazon_url',
			'asin'             => 'asin',
			'description'      => 'description',
			'publisher'        => 'publisher',
			'pages'            => 'pages',
			'page_count'       => 'pages',
			'publication_date' => 'publication_date',
			'isbn'             => 'isbn',
			'isbn_13'          => 'isbn_13',
			'category'         => 'category',
		);

		foreach ( $field_map as $field_name => $source_key ) {
			if ( empty( $data[ $source_key ] ) ) {
				continue;
			}

			alfred_update_book_field( $post_id, $field_name, $data[ $source_key ] );
		}

		if ( ! empty( $data['category'] ) && ! empty( $book_taxonomies ) ) {
			foreach ( $book_taxonomies as $taxonomy ) {
				wp_set_object_terms( $post_id, $data['category'], $taxonomy, false );
			}
		}

		if ( ! empty( $data['cover_image_url'] ) ) {
			alfred_import_book_cover( $post_id, $data['cover_image_url'] );
		}

		$existing_titles[ $title_key ] = (int) $post_id;

		if ( $asin ) {
			$existing_asins[ $asin ] = (int) $post_id;
		}

		++$counts['created'];
	}

	fclose( $handle );

	update_option(
		$done_key,
		array(
			'status' => 'completed',
			'counts' => $counts,
		)
	);
}
add_action( 'init', 'alfred_import_books_from_csv', 120 );

/**
 * Update existing book excerpts from the refreshed CSV once.
 *
 * Matches by ASIN first, then normalized title.
 *
 * @return void
 */
function alfred_update_book_excerpts_from_csv() {
	$csv_path = '/Users/bassily/Desktop/alfred_basta_books_with_excerpts.csv';
	$done_key = 'alfred_books_excerpt_import_completed_v1';

	if ( get_option( $done_key ) || ! file_exists( $csv_path ) || ! post_type_exists( 'book' ) ) {
		return;
	}

	$handle = fopen( $csv_path, 'r' );

	if ( false === $handle ) {
		update_option(
			$done_key,
			array(
				'status'  => 'failed',
				'message' => 'Unable to open excerpt CSV file.',
			)
		);
		return;
	}

	$headers = fgetcsv( $handle );

	if ( empty( $headers ) ) {
		fclose( $handle );
		update_option(
			$done_key,
			array(
				'status'  => 'failed',
				'message' => 'Excerpt CSV file is empty.',
			)
		);
		return;
	}

	$book_ids = get_posts(
		array(
			'post_type'      => 'book',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);

	$books_by_asin  = array();
	$books_by_title = array();

	foreach ( $book_ids as $book_id ) {
		$normalized_title = alfred_normalize_book_title( get_the_title( $book_id ) );

		if ( $normalized_title ) {
			$books_by_title[ $normalized_title ] = (int) $book_id;
		}

		$asin = trim( strtoupper( (string) get_post_meta( $book_id, 'asin', true ) ) );

		if ( $asin ) {
			$books_by_asin[ $asin ] = (int) $book_id;
		}
	}

	$counts = array(
		'updated'   => 0,
		'skipped'   => 0,
		'unmatched' => 0,
		'failed'    => 0,
	);

	while ( false !== ( $row = fgetcsv( $handle ) ) ) {
		$data = array();

		foreach ( $headers as $index => $header ) {
			$data[ $header ] = isset( $row[ $index ] ) ? trim( (string) $row[ $index ] ) : '';
		}

		$title   = $data['title'] ?? '';
		$asin    = trim( strtoupper( $data['asin'] ?? '' ) );
		$excerpt = $data['excerpt'] ?? '';

		if ( '' === $excerpt ) {
			++$counts['skipped'];
			continue;
		}

		$post_id = 0;

		if ( $asin && isset( $books_by_asin[ $asin ] ) ) {
			$post_id = (int) $books_by_asin[ $asin ];
		} elseif ( $title ) {
			$title_key = alfred_normalize_book_title( $title );

			if ( $title_key && isset( $books_by_title[ $title_key ] ) ) {
				$post_id = (int) $books_by_title[ $title_key ];
			}
		}

		if ( ! $post_id ) {
			++$counts['unmatched'];
			continue;
		}

		$result = wp_update_post(
			array(
				'ID'           => $post_id,
				'post_excerpt' => $excerpt,
			),
			true
		);

		if ( is_wp_error( $result ) ) {
			++$counts['failed'];
			continue;
		}

		++$counts['updated'];
	}

	fclose( $handle );

	update_option(
		$done_key,
		array(
			'status' => 'completed',
			'counts' => $counts,
		)
	);
}
add_action( 'init', 'alfred_update_book_excerpts_from_csv', 125 );

/**
 * Remove duplicate book posts created by overlapping import requests.
 *
 * Keeps the lowest-ID post for each normalized title.
 *
 * @return void
 */
function alfred_cleanup_duplicate_books() {
	$done_key = 'alfred_books_duplicate_cleanup_completed_v1';

	if ( get_option( $done_key ) || ! post_type_exists( 'book' ) ) {
		return;
	}

	$book_ids = get_posts(
		array(
			'post_type'      => 'book',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);

	$seen       = array();
	$trashed    = array();
	$kept_count = 0;

	foreach ( $book_ids as $book_id ) {
		$normalized_title = alfred_normalize_book_title( get_the_title( $book_id ) );

		if ( ! $normalized_title ) {
			continue;
		}

		if ( isset( $seen[ $normalized_title ] ) ) {
			wp_trash_post( $book_id );
			$trashed[] = (int) $book_id;
			continue;
		}

		$seen[ $normalized_title ] = (int) $book_id;
		++$kept_count;
	}

	update_option(
		$done_key,
		array(
			'kept'    => $kept_count,
			'trashed' => $trashed,
		)
	);
}
add_action( 'init', 'alfred_cleanup_duplicate_books', 130 );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

<?php
/**
 * Template for the book archive.
 *
 * @package Alfred_Basta
 */

get_header();

$archive_description = get_the_archive_description();

if ( ! $archive_description ) {
	$archive_description = 'A curated collection of Alfred Basta titles spanning cybersecurity, mathematics, grief, faith, and practical scholarship.';
}

$genre_terms = get_terms(
	array(
		'taxonomy'   => 'book-genre',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

$selected_sort = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'title_asc';
$selected_sort = in_array( $selected_sort, array( 'title_asc', 'publication_date_desc', 'publication_date_asc' ), true ) ? $selected_sort : 'title_asc';

$selected_search = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';

$selected_genres = isset( $_GET['genre'] ) ? (array) wp_unslash( $_GET['genre'] ) : array();
$selected_genres = array_values(
	array_filter(
		array_map( 'sanitize_title', $selected_genres )
	)
);

$book_query_args = array(
	'post_type'              => 'book',
	'post_status'            => 'publish',
	'posts_per_page'         => -1,
	'ignore_sticky_posts'    => true,
	'no_found_rows'          => true,
	'update_post_meta_cache' => true,
	'update_post_term_cache' => true,
);

$book_query   = new WP_Query( $book_query_args );
$book_posts   = $book_query->posts;
$initial_step = 12;
$show_step    = 8;

$resolve_publication_timestamp = static function ( $post_id ) {
	$candidate_fields = array(
		'release_date',
		'publication_date',
		'published_date',
		'publish_date',
	);

	foreach ( $candidate_fields as $candidate_field ) {
		$value = get_post_meta( $post_id, $candidate_field, true );

		if ( is_array( $value ) ) {
			continue;
		}

		$value = trim( (string) $value );

		if ( '' === $value ) {
			continue;
		}

		$timestamp = strtotime( $value );

		if ( false !== $timestamp ) {
			return $timestamp;
		}
	}

	return get_post_timestamp( $post_id ) ?: 0;
};

if ( ! empty( $book_posts ) ) {
	usort(
		$book_posts,
		static function ( $left, $right ) use ( $selected_sort, $resolve_publication_timestamp ) {
			if ( 'title_asc' === $selected_sort ) {
				return strcasecmp( $left->post_title, $right->post_title );
			}

			$left_timestamp  = $resolve_publication_timestamp( $left->ID );
			$right_timestamp = $resolve_publication_timestamp( $right->ID );

			if ( $left_timestamp === $right_timestamp ) {
				return strcasecmp( $left->post_title, $right->post_title );
			}

			if ( 'publication_date_asc' === $selected_sort ) {
				return $left_timestamp <=> $right_timestamp;
			}

			return $right_timestamp <=> $left_timestamp;
		}
	);
}

$results_count   = count( $book_posts );
$active_filters  = ! empty( $selected_genres ) || '' !== $selected_search;
?>

<main id="primary" class="site-main single-book-page book-archive-page">
	<nav class="site-nav single-book-nav-shell" id="siteNav" aria-label="<?php esc_attr_e( 'Book archive navigation', 'alfred' ); ?>">
		<div class="nav-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Alfred Basta</a></div>
		<?php alfred_front_page_navigation(); ?>
		<form class="nav-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
			<label class="screen-reader-text" for="site-nav-search-archive"><?php esc_html_e( 'Search the site', 'alfred' ); ?></label>
			<input id="site-nav-search-archive" class="nav-search__input" type="search" name="s" placeholder="<?php esc_attr_e( 'Search', 'alfred' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
			<button type="submit" class="nav-search__submit"><?php esc_html_e( 'Search', 'alfred' ); ?></button>
		</form>
		<button class="nav-toggle" id="navToggle" type="button" aria-label="<?php esc_attr_e( 'Toggle menu', 'alfred' ); ?>" aria-expanded="false" aria-controls="navLinks">
			<span></span><span></span><span></span>
		</button>
	</nav>

	<section class="book-hero book-archive-hero" id="home">
		<div class="book-hero__backdrop"></div>
		<div class="container book-archive-hero__container">
			<div class="book-archive-hero__content">
				<div class="book-hero__eyebrow reveal"><?php esc_html_e( 'Books Archive', 'alfred' ); ?></div>
				<h1 class="book-hero__title reveal reveal-delay-1"><?php post_type_archive_title(); ?></h1>
				<div class="book-hero__description book-archive-hero__description reveal reveal-delay-2">
					<?php echo wp_kses_post( wpautop( $archive_description ) ); ?>
				</div>
			</div>
		</div>
	</section>

	<div class="section-separator"></div>

	<section class="section books-section section-white book-archive-grid-section">
		<div class="container">
			<span class="section-label reveal"><?php esc_html_e( 'All Titles', 'alfred' ); ?></span>
			<div class="gold-rule reveal reveal-delay-1"></div>
			<h2 class="section-title reveal reveal-delay-1"><?php esc_html_e( 'Browse the', 'alfred' ); ?> <em><?php esc_html_e( 'Collection', 'alfred' ); ?></em></h2>
			<p class="section-subtitle reveal reveal-delay-2">
				<?php esc_html_e( 'Sort the catalog, narrow it by genre, and expand the grid without leaving the page.', 'alfred' ); ?>
			</p>

			<div class="book-archive-layout">
				<aside class="book-archive-sidebar reveal reveal-delay-2">
					<details class="book-archive-controls" data-book-controls data-active-filters="<?php echo esc_attr( $active_filters ? 'true' : 'false' ); ?>" open>
						<summary class="book-archive-controls__summary">
							<span><?php esc_html_e( 'Filters & Sort', 'alfred' ); ?></span>
							<span class="book-archive-controls__summary-meta"><?php echo esc_html( $active_filters ? __( 'Active filters', 'alfred' ) : __( 'Tap to refine', 'alfred' ) ); ?></span>
						</summary>
						<div class="book-archive-controls__body">
							<div class="book-archive-search">
								<label class="book-archive-controls__label" for="book-search"><?php esc_html_e( 'Search titles', 'alfred' ); ?></label>
								<input id="book-search" class="book-archive-search__input" type="search" value="<?php echo esc_attr( $selected_search ); ?>" placeholder="<?php esc_attr_e( 'Search by title, description, or topic', 'alfred' ); ?>" data-book-search-input>
							</div>

							<div class="book-archive-sort">
								<label class="book-archive-controls__label" for="book-sort"><?php esc_html_e( 'Sort by', 'alfred' ); ?></label>
								<div class="book-archive-select-wrap">
									<select id="book-sort" name="sort" class="book-archive-select" data-book-sort>
										<option value="title_asc" <?php selected( $selected_sort, 'title_asc' ); ?>><?php esc_html_e( 'Title (A-Z)', 'alfred' ); ?></option>
										<option value="publication_date_desc" <?php selected( $selected_sort, 'publication_date_desc' ); ?>><?php esc_html_e( 'Publication Date (Newest)', 'alfred' ); ?></option>
										<option value="publication_date_asc" <?php selected( $selected_sort, 'publication_date_asc' ); ?>><?php esc_html_e( 'Publication Date (Oldest)', 'alfred' ); ?></option>
									</select>
								</div>
							</div>

							<div class="book-archive-filters">
								<div class="book-archive-controls__label"><?php esc_html_e( 'Filter by genre', 'alfred' ); ?></div>
								<?php if ( ! empty( $genre_terms ) && ! is_wp_error( $genre_terms ) ) : ?>
									<div class="book-archive-checkboxes">
										<?php foreach ( $genre_terms as $genre_term ) : ?>
											<label class="book-archive-checkbox">
												<input type="checkbox" name="genre[]" value="<?php echo esc_attr( $genre_term->slug ); ?>" data-book-genre <?php checked( in_array( $genre_term->slug, $selected_genres, true ) ); ?>>
												<span><?php echo esc_html( $genre_term->name ); ?></span>
											</label>
										<?php endforeach; ?>
									</div>
								<?php else : ?>
									<p class="book-archive-controls__empty"><?php esc_html_e( 'No genres available yet.', 'alfred' ); ?></p>
								<?php endif; ?>
							</div>

							<div class="book-archive-sidebar-actions">
								<button type="button" class="book-archive-reset" data-book-reset><?php esc_html_e( 'Reset filters', 'alfred' ); ?></button>
								<a href="#home" class="book-archive-backtop"><?php esc_html_e( 'Back to Top', 'alfred' ); ?></a>
							</div>
						</div>
					</details>
				</aside>

				<div class="book-archive-main">
					<div class="book-archive-results reveal reveal-delay-3">
						<p class="book-archive-results__count" data-book-results-count>
							<?php
							printf(
								/* translators: %d: number of books. */
								esc_html( _n( '%d book found', '%d books found', $results_count, 'alfred' ) ),
								esc_html( $results_count )
							);
							?>
						</p>
						<p class="book-archive-results__meta" data-book-results-meta>
							<?php
							if ( $active_filters ) {
								esc_html_e( 'Showing books that match the current live filters.', 'alfred' );
							} else {
								esc_html_e( 'Use search, sort, or genre filters to refine the archive instantly.', 'alfred' );
							}
							?>
						</p>
					</div>

					<?php if ( ! empty( $book_posts ) ) : ?>
						<?php
						$delay_classes = array(
							'reveal-delay-1',
							'reveal-delay-2',
							'reveal-delay-3',
							'reveal-delay-4',
						);
						?>
						<div
							class="books-grid book-archive-grid"
							data-book-grid
							data-initial-count="<?php echo esc_attr( $initial_step ); ?>"
							data-step-count="<?php echo esc_attr( $show_step ); ?>"
						>
							<?php foreach ( $book_posts as $index => $book_post ) : ?>
						<?php
						$delay_class  = $delay_classes[ $index % count( $delay_classes ) ] ?? 'reveal-delay-1';
						$book_excerpt = get_the_excerpt( $book_post );
						$book_terms   = get_the_terms( $book_post, 'book-genre' );
						$genre_slugs  = array();
						$genre_names  = array();

						if ( ! $book_excerpt ) {
							$book_excerpt = wp_trim_words( wp_strip_all_tags( $book_post->post_content ), 28 );
						}

						if ( ! empty( $book_terms ) && ! is_wp_error( $book_terms ) ) {
							$genre_slugs = wp_list_pluck( $book_terms, 'slug' );
							$genre_names = wp_list_pluck( $book_terms, 'name' );
						}

						$book_search_content = implode(
							' ',
							array_filter(
								array(
									get_the_title( $book_post ),
									$book_excerpt,
									implode( ' ', $genre_names ),
								)
							)
						);
						?>
							<article
								class="book-card reveal <?php echo esc_attr( $delay_class ); ?>"
								data-book-card
								data-book-title="<?php echo esc_attr( wp_strip_all_tags( get_the_title( $book_post ) ) ); ?>"
								data-book-publication="<?php echo esc_attr( (string) $resolve_publication_timestamp( $book_post->ID ) ); ?>"
								data-book-genres="<?php echo esc_attr( implode( ' ', $genre_slugs ) ); ?>"
								data-book-search="<?php echo esc_attr( strtolower( wp_strip_all_tags( $book_search_content ) ) ); ?>"
								<?php echo $index >= $initial_step ? 'hidden' : ''; ?>
							>
							<a href="<?php echo esc_url( get_permalink( $book_post ) ); ?>" class="book-cover-link" aria-label="<?php echo esc_attr( get_the_title( $book_post ) ); ?>">
								<div class="book-cover-wrap">
									<?php if ( has_post_thumbnail( $book_post ) ) : ?>
										<?php echo get_the_post_thumbnail( $book_post, 'large' ); ?>
									<?php else : ?>
										<div class="book-placeholder">
											<div class="book-placeholder-num"><?php echo esc_html( get_the_date( 'Y', $book_post ) ); ?></div>
											<div class="book-placeholder-title"><?php echo esc_html( get_the_title( $book_post ) ); ?></div>
										</div>
									<?php endif; ?>
									<div class="book-cover-overlay">
										<span><?php esc_html_e( 'View Book', 'alfred' ); ?></span>
									</div>
								</div>
								</a>
								<div class="book-genre"><?php echo esc_html( ! empty( $genre_names ) ? $genre_names[0] : '' ); ?></div>
								<h3 class="book-title"><a href="<?php echo esc_url( get_permalink( $book_post ) ); ?>"><?php echo esc_html( get_the_title( $book_post ) ); ?></a></h3>
								</article>
								<?php endforeach; ?>
						</div>

						<?php if ( $results_count > $initial_step ) : ?>
							<div class="book-archive-reveal reveal reveal-delay-3" data-book-reveal>
								<button type="button" class="btn-outline" data-book-show-more><?php esc_html_e( 'Show More', 'alfred' ); ?></button>
								<button type="button" class="btn-primary" data-book-show-all><?php esc_html_e( 'Show All', 'alfred' ); ?></button>
							</div>
						<?php endif; ?>
						<div class="book-archive-empty reveal reveal-delay-1" data-book-empty-state hidden>
							<h3><?php esc_html_e( 'No books match these filters.', 'alfred' ); ?></h3>
							<p><?php esc_html_e( 'Try another genre, adjust the search text, or reset the filters.', 'alfred' ); ?></p>
						</div>
					<?php else : ?>
						<div class="book-archive-empty reveal reveal-delay-1">
							<h3><?php esc_html_e( 'No books published yet.', 'alfred' ); ?></h3>
							<p><?php esc_html_e( 'Create book posts to populate this archive.', 'alfred' ); ?></p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<div class="section-separator"></div>

	<?php alfred_custom_site_footer(); ?>
</main>

<?php if ( $results_count > $initial_step ) : ?>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const grid = document.querySelector('[data-book-grid]');
			const revealControls = document.querySelector('[data-book-reveal]');
			const sortSelect = document.querySelector('[data-book-sort]');
			const searchInput = document.querySelector('[data-book-search-input]');
			const genreInputs = Array.from(document.querySelectorAll('[data-book-genre]'));
			const resetButton = document.querySelector('[data-book-reset]');
			const resultsCount = document.querySelector('[data-book-results-count]');
			const resultsMeta = document.querySelector('[data-book-results-meta]');
			const emptyState = document.querySelector('[data-book-empty-state]');

			if (!grid || !sortSelect || !searchInput || !resultsCount || !resultsMeta || !emptyState) {
				return;
			}

			const cards = Array.from(grid.querySelectorAll('[data-book-card]'));
			const showMoreButton = revealControls ? revealControls.querySelector('[data-book-show-more]') : null;
			const showAllButton = revealControls ? revealControls.querySelector('[data-book-show-all]') : null;
			const initialCount = Number(grid.dataset.initialCount || 12);
			const stepCount = Number(grid.dataset.stepCount || 8);
			let visibleCount = initialCount;
			let filteredCards = cards.slice();

			const updateUrl = function (searchTerm, selectedGenres, sortValue) {
				if (!window.history || !window.history.replaceState) {
					return;
				}

				const nextUrl = new URL(window.location.href);

				nextUrl.searchParams.delete('q');
				nextUrl.searchParams.delete('sort');
				nextUrl.searchParams.delete('genre');

				if (searchTerm) {
					nextUrl.searchParams.set('q', searchTerm);
				}

				if (sortValue && sortValue !== 'title_asc') {
					nextUrl.searchParams.set('sort', sortValue);
				}

				selectedGenres.forEach(function (genre) {
					nextUrl.searchParams.append('genre', genre);
				});

				window.history.replaceState({}, '', nextUrl.toString());
			};

			const setResultsMeta = function (searchTerm, selectedGenres) {
				const metaParts = [];

				if (searchTerm) {
					metaParts.push(`Search: "${searchTerm}"`);
				}

				if (selectedGenres.length) {
					metaParts.push(`Genres: ${selectedGenres.join(', ')}`);
				}

				resultsMeta.textContent = metaParts.length
					? metaParts.join(' • ')
					: 'Use search, sort, or genre filters to refine the archive instantly.';
			};

			const applyFilters = function (resetVisibleCount) {
				const searchTerm = searchInput.value.trim().toLowerCase();
				const selectedGenres = genreInputs
					.filter(function (input) {
						return input.checked;
					})
					.map(function (input) {
						return input.value;
					});
				const selectedGenreLabels = genreInputs
					.filter(function (input) {
						return input.checked;
					})
					.map(function (input) {
						return input.parentElement.textContent.trim();
					});
				const sortValue = sortSelect.value;

				filteredCards = cards.filter(function (card) {
					const cardSearch = (card.dataset.bookSearch || '').toLowerCase();
					const cardGenres = (card.dataset.bookGenres || '').split(/\s+/).filter(Boolean);
					const matchesSearch = !searchTerm || cardSearch.includes(searchTerm);
					const matchesGenres = !selectedGenres.length || selectedGenres.some(function (genre) {
						return cardGenres.includes(genre);
					});

					return matchesSearch && matchesGenres;
				});

				filteredCards.sort(function (left, right) {
					const leftTitle = (left.dataset.bookTitle || '').toLowerCase();
					const rightTitle = (right.dataset.bookTitle || '').toLowerCase();
					const leftPublication = Number(left.dataset.bookPublication || 0);
					const rightPublication = Number(right.dataset.bookPublication || 0);

					if (sortValue === 'publication_date_desc') {
						return rightPublication - leftPublication || leftTitle.localeCompare(rightTitle);
					}

					if (sortValue === 'publication_date_asc') {
						return leftPublication - rightPublication || leftTitle.localeCompare(rightTitle);
					}

					return leftTitle.localeCompare(rightTitle);
				});

				filteredCards.forEach(function (card) {
					grid.appendChild(card);
				});

				if (resetVisibleCount) {
					visibleCount = initialCount;
				}

				cards.forEach(function (card) {
					card.hidden = true;
				});

				filteredCards.slice(0, visibleCount).forEach(function (card) {
					card.hidden = false;
				});

				resultsCount.textContent = filteredCards.length === 1 ? '1 book found' : `${filteredCards.length} books found`;
				setResultsMeta(searchInput.value.trim(), selectedGenreLabels);

				grid.hidden = filteredCards.length === 0;
				emptyState.hidden = filteredCards.length !== 0;

				if (revealControls) {
					const allVisible = filteredCards.length <= visibleCount;

					revealControls.hidden = filteredCards.length <= initialCount || allVisible;
					revealControls.style.display = filteredCards.length <= initialCount || allVisible ? 'none' : 'flex';

					if (showMoreButton) {
						showMoreButton.hidden = allVisible;
					}

					if (showAllButton) {
						showAllButton.hidden = allVisible;
					}
				}

				updateUrl(searchInput.value.trim(), selectedGenres, sortValue);
			};

			searchInput.addEventListener('input', function () {
				applyFilters(true);
			});

			sortSelect.addEventListener('change', function () {
				applyFilters(true);
			});

			genreInputs.forEach(function (input) {
				input.addEventListener('change', function () {
					applyFilters(true);
				});
			});

			if (resetButton) {
				resetButton.addEventListener('click', function () {
					searchInput.value = '';
					sortSelect.value = 'title_asc';
					genreInputs.forEach(function (input) {
						input.checked = false;
					});
					applyFilters(true);
				});
			}

			if (showMoreButton) {
				showMoreButton.addEventListener('click', function () {
					visibleCount = Math.min(visibleCount + stepCount, filteredCards.length);
					applyFilters(false);
				});
			}

			if (showAllButton) {
				showAllButton.addEventListener('click', function () {
					visibleCount = filteredCards.length;
					applyFilters(false);
				});
			}

			applyFilters(true);
		});
	</script>
<?php endif; ?>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		const controls = document.querySelector('[data-book-controls]');

		if (!controls || controls.tagName.toLowerCase() !== 'details') {
			return;
		}

		const mobileQuery = window.matchMedia('(max-width: 640px)');
		const hasActiveFilters = controls.dataset.activeFilters === 'true';

		const syncFilterDisclosure = function () {
			controls.open = !mobileQuery.matches || hasActiveFilters;
		};

		syncFilterDisclosure();

		if (mobileQuery.addEventListener) {
			mobileQuery.addEventListener('change', syncFilterDisclosure);
		}
	});
</script>

<?php
wp_reset_postdata();
get_footer();

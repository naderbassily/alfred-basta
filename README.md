# Alfred Basta WordPress Theme

Custom WordPress theme for Dr. Alfred Basta, professor, author, cybersecurity expert, and Ph.D. in Cryptography.

The theme presents Alfred's work through a cinematic author homepage, a browsable book catalog, individual book pages, and a dedicated biography page. It began from the Underscores starter theme and has been customized for a polished editorial site focused on books, scholarship, faith, cybersecurity, and teaching.

## Theme Overview

- Homepage landing experience with hero imagery, featured books, biography preview, testimonials, newsletter prompt, blog preview, and contact footer.
- Custom book archive with live search, genre filtering, sorting, and progressive "show more" controls.
- Single book layout with cover art, metadata, purchase link support, overview content, and related books.
- About Alfred page with custom hero image support and biography content.
- Shared visual direction built around black, white, and gold tones, editorial serif typography, and reveal animations.
- Compatibility with standard WordPress posts/pages plus ACF-powered book metadata where available.

## Key Files

- `front-page.php` renders the site homepage and falls back to editor content when the front page has manually authored content.
- `template-parts/home-landing.php` contains the custom homepage sections.
- `archive-book.php` renders the book catalog and includes the current archive filtering behavior.
- `single-book.php` renders individual book detail pages.
- `page-about-alfred.php` renders the custom biography page for `/about-alfred/`.
- `assets/css/front-page.css` contains landing page and shared custom layout styles.
- `assets/css/book.css` contains book archive, single book, and about page styles.
- `assets/js/front-page.js` handles navigation, reveal animations, smooth scrolling, hero parallax, and newsletter form UI.
- `functions.php` registers theme support, navigation locations, custom routing helpers, asset loading, book archive fallback routing, and temporary book import utilities.

## Content Model

The theme expects a custom post type named `book`. The post type is currently managed outside the theme, likely through WordPress/ACF configuration.

Recommended book fields:

- `amazon_url` or `amazon_link`
- `asin`
- `description`
- `publisher`
- `pages` or `page_count`
- `publication_date`, `release_date`, `published_date`, or `publish_date`
- `isbn` or `isbn_13`
- `category`, `genre`, `topic`, or a taxonomy such as `book-genre`

Book covers should be assigned as featured images. The archive and single templates include graceful placeholders when a cover is missing.

## Navigation

The theme registers two menu locations:

- `Primary`
- `Front Page Menu`

The custom landing/book/about layouts use the front page navigation pattern. If no menu is assigned, a fallback menu links to Home, Books, About Alfred, Blog, and Contact.

## Local Development

This repository is intended to track only the custom theme folder:

```sh
app/public/wp-content/themes/alfred
```

It does not include WordPress core, uploads, database content, or plugins.

The theme includes the inherited Underscores development configuration:

```sh
npm install
composer install
```

Useful commands:

```sh
npm run lint:js
npm run compile:rtl
composer lint:php
composer lint:wpcs
```

Note: the current custom CSS is written directly in `assets/css/`, not compiled from Sass.

## Current Cleanup Notes

The theme is functional, but several items are queued for polish:

- Remove hard-coded local image and CSV paths.
- Extract repeated nav/footer markup into shared template parts.
- Move the inline book archive JavaScript into a dedicated enqueued asset.
- Convert temporary book import routines into a safer migration/admin workflow or remove them after data is finalized.
- Update `package.json` and other inherited starter metadata to match this project.

## Repository

GitHub: <https://github.com/naderbassily/alfred-basta>

## License

GPL-2.0-or-later, inherited from the Underscores starter theme.

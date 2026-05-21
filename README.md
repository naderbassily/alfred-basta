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

## WordPress Theme Updates From GitHub Releases

Alfred Basta includes the Plugin Update Checker library in `inc/plugin-update-checker/` so WordPress can detect and install theme updates from GitHub.

The updater is initialized in `functions.php` and points at:

```text
https://github.com/naderbassily/alfred-basta/
```

The stable branch is `main`. WordPress compares the installed theme version from the `Version:` header in `style.css` against GitHub releases, tags, or the configured stable branch. If GitHub has a higher version, WordPress shows an update notification in Appearance -> Themes and supports the standard one-click theme update flow.

### Repository Shape

This repository must contain only the theme files at the repository root:

```text
alfred/
├── style.css
├── functions.php
├── assets/
├── inc/
└── template-parts/
```

Do not wrap the theme in `wp-content/`, `wordpress/`, or another project folder. GitHub release source ZIPs must expose `style.css` and `functions.php` directly inside the extracted top-level theme folder so WordPress can update `wp-content/themes/alfred/` correctly.

### Release Workflow

1. Develop locally on a feature branch.
2. Bump `Version:` in `style.css`.
3. Keep `_S_VERSION` in `functions.php` in sync with `style.css`.
4. Commit and push the branch.
5. Merge the branch into `main` after review and testing.
6. Create a GitHub Release from `main` with a matching tag, for example `v1.0.1`.
7. WordPress checks for updates periodically and shows Update Available when the release version is higher than the installed version.
8. Use Appearance -> Themes to run the one-click update.

Recommended tag and release names:

```text
v1.0.1
v1.0.2
v1.1.0
```

Avoid prereleases for production updates. Plugin Update Checker ignores GitHub releases marked as prerelease, which is useful for staging tests but not for live update prompts.

### Private Repository Support

If the GitHub repository is private, production/staging WordPress installs need a GitHub token to check releases. Define the token outside the theme code.

Preferred `wp-config.php` setup:

```php
define( 'ALFRED_BASTA_GITHUB_TOKEN', 'github_pat_or_classic_token_here' );
```

Environment variable alternative:

```text
ALFRED_BASTA_GITHUB_TOKEN=github_pat_or_classic_token_here
```

Use a token with the minimum read-only repository access needed to read releases and source archives. Never commit a real token to this repository.

### Testing Updates Locally

To test the update flow:

1. Install an older packaged copy of the theme, for example version `1.0.0`.
2. Confirm `style.css` in GitHub `main` has a higher version, for example `1.0.1`.
3. Create a GitHub Release from `main` using a matching tag such as `v1.0.1`.
4. In WordPress admin, go to Dashboard -> Updates and click Check again, or wait for the normal update check.
5. Confirm Appearance -> Themes shows Update Available for Alfred Basta.
6. Run the update.
7. Confirm the theme remains active and the frontend/admin load without PHP errors.

Plugin Update Checker can also be inspected with the Debug Bar plugin. After installing Debug Bar, open the PUC panel in the admin toolbar and trigger Check Now for `alfred`.

### Rollback Workflow

1. Keep a ZIP of the currently working theme release before applying updates.
2. If an update fails, upload the previous release ZIP through Appearance -> Themes -> Add New -> Upload Theme.
3. Alternatively, restore the previous release from hosting backups or deploy the previous Git tag to the server.
4. Clear object/page caches after rollback.
5. Confirm the active theme version in `style.css` matches the intended rollback version.

### Hosting Requirements

- WordPress must be able to make outbound HTTPS requests to GitHub.
- The web server must be able to write to `wp-content/themes/` during theme updates.
- ZIP extraction must be available in PHP/WordPress.
- WP Engine, cPanel, and other managed hosts may require correct filesystem credentials or write permissions.
- Caches should be cleared after updates when CSS, JS, or templates change.

### Troubleshooting

- No update appears: confirm the GitHub Release is published, not a prerelease, and its version/tag is higher than the installed `style.css` version.
- Private repository returns no update: confirm `ALFRED_BASTA_GITHUB_TOKEN` is configured on the WordPress host and has read access to `naderbassily/alfred-basta`.
- Update downloads but fails to install: confirm the repository ZIP contains the theme at the root and not nested inside `wp-content/` or another project folder.
- Permission errors: confirm WordPress can write to `wp-content/themes/`.
- Authentication errors for private repos: confirm the token is valid, read-only, and available without being committed.
- Theme appears inactive after update: reinstall the previous release ZIP or restore from backup, then check the package folder structure before releasing again.

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

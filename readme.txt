=== Indfir ===

Contributors: Indra Firdaus
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A news magazine theme for indfir.com. No page builder, no paid licenses,
no plugin dependencies.

== Description ==

Indfir assembles the front page automatically from the categories you choose,
so there is no layout that needs to be rebuilt every day. Homepage block
layout:

1. Hero      - main news, one featured highlight with image, and a most popular column.
2. Block 1-3 - one main category (two columns) flanked by two narrow categories.
3. Block 4-5 - two large featured highlights with images plus a category sidebar.
4. Block 6   - full-width category: three highlights and a row of cards.
5. Latest    - list of latest articles; this block carries the page numbering.

On page 2 and beyond, only the "Latest" list is displayed, so page
navigation behaves like a regular archive.

== Settings ==

Everything is configured via Appearance > Customize (Customizer):

* Site Identity     - logo and tagline next to the logo.
* Colors            - primary color (navbar) and accent color.
* Typography & Layout - Google Fonts on/off, custom font stack, container
                        width, excerpt length.
* Header & Top Bar  - show top bar, left text, automatic date.
* Social Media      - Facebook, Instagram, X, YouTube.
* Homepage: Blocks  - title and category for each block. Leave the title
                      empty to hide the block.
* Single Post       - share buttons, related posts, author box,
                      previous/next navigation, breadcrumb.
* Footer            - column titles, description, copyright text.

== Menu Locations ==

* Primary Menu (navbar) - supports dropdowns up to 3 levels deep.
* Top Menu (top bar)    - single level, reused in the first footer column.
* Footer Menu           - single level, displayed above the footer columns.

If no menu is assigned to "Primary Menu", the theme displays Home
plus the seven busiest categories automatically.

== Widget Areas ==

* Main Sidebar   - displayed on posts, archives, search, and the homepage.
                   If empty, content uses full width.
* Footer Column 1-4 - if all four are empty, the footer uses the default
                   display (description, Latest, Popular, Browse).

== Popular ==

The "Popular" column uses the theme's built-in view counter stored in the
_indfir_views meta. Visits from logged-in users are not counted. Numbers
start accumulating once the theme is activated, so during the first few days
the ordering still follows the date.

== Image Sizes ==

* indfir-lead  800x500  - hero highlights and wide blocks.
* indfir-card  520x320  - standard cards.
* indfir-hero  700x560  - featured highlights with dark overlay.
* indfir-thumb 150x120  - sidebar list thumbnails.

After activation, run a regenerate thumbnails plugin so that older posts
have these sizes.

== Changelog ==

= 1.0.0 =
* Initial release.

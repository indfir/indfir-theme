<?php
/**
 * Reusable rendering helpers.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Icons
 * ---------------------------------------------------------------------- */

/**
 * Inline SVG icons. Keeps the theme free of an icon-font dependency.
 */
function indfir_icon( $name, $class = '' ) {
	$paths = array(
		'search'    => 'M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z',
		'menu'      => 'M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z',
		'close'     => 'M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z',
		'arrow-up'  => 'M7.41 15.41 12 10.83l4.59 4.58L18 14l-6-6-6 6z',
		'facebook'  => 'M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z',
		'instagram' => 'M12 2.16c3.2 0 3.58.01 4.85.07 3.25.15 4.77 1.69 4.92 4.92.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.15 3.23-1.66 4.77-4.92 4.92-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-3.26-.15-4.77-1.7-4.92-4.92-.06-1.27-.07-1.65-.07-4.85s.01-3.58.07-4.85C2.38 3.92 3.89 2.38 7.15 2.23 8.42 2.17 8.8 2.16 12 2.16zm0 5.68a4.16 4.16 0 1 0 0 8.32 4.16 4.16 0 0 0 0-8.32zm0 6.86a2.7 2.7 0 1 1 0-5.4 2.7 2.7 0 0 1 0 5.4zm4.34-7.05a.97.97 0 1 0 0-1.94.97.97 0 0 0 0 1.94z',
		'x'         => 'M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.65l-5.22-6.82-5.97 6.82H1.66l7.73-8.84L1.25 2.25h6.83l4.71 6.23 5.45-6.23zm-1.16 17.52h1.83L7.02 4.13H5.06l12.02 15.64z',
		'youtube'   => 'M21.58 7.19a2.5 2.5 0 0 0-1.77-1.77C18.25 5 12 5 12 5s-6.25 0-7.81.42a2.5 2.5 0 0 0-1.77 1.77C2 8.75 2 12 2 12s0 3.25.42 4.81a2.5 2.5 0 0 0 1.77 1.77C5.75 19 12 19 12 19s6.25 0 7.81-.42a2.5 2.5 0 0 0 1.77-1.77C22 15.25 22 12 22 12s0-3.25-.42-4.81zM10 15.02V8.98L15.2 12 10 15.02z',
		'whatsapp'  => 'M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.87 9.87 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm5.8 14.14c-.24.68-1.42 1.31-1.96 1.36-.5.05-1.14.07-1.83-.11a16.6 16.6 0 0 1-1.66-.61c-2.92-1.26-4.83-4.2-4.98-4.4-.14-.2-1.18-1.57-1.18-3s.75-2.13 1.02-2.42c.27-.29.58-.37.78-.37h.56c.18 0 .42-.07.66.5.24.59.83 2.02.9 2.17.07.15.12.32.02.51-.29.59-.6.77-.31 1.26.44.75 1.06 1.55 1.83 2.24.98.87 1.81 1.14 2.07 1.27.26.13.41.11.56-.07.15-.18.65-.76.82-1.02.17-.26.34-.22.58-.13.24.09 1.53.72 1.79.85.26.13.44.2.5.31.07.11.07.63-.17 1.31z',
		'link'      => 'M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7a5 5 0 0 0 0 10h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4a5 5 0 0 0 0-10z',
		'telegram'  => 'M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z',
		'linkedin'  => 'M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3V9zm7 0h3.8v1.71h.05c.53-.95 1.83-1.96 3.77-1.96 4.03 0 4.78 2.5 4.78 5.76V21h-4v-5.6c0-1.34-.03-3.06-1.9-3.06-1.9 0-2.19 1.45-2.19 2.96V21h-4V9z',
		'pinterest' => 'M12 2C6.48 2 2 6.48 2 12c0 4.24 2.64 7.86 6.36 9.32-.09-.79-.17-2.01.04-2.88.19-.78 1.22-4.97 1.22-4.97s-.31-.62-.31-1.54c0-1.45.84-2.53 1.88-2.53.89 0 1.32.67 1.32 1.47 0 .89-.57 2.23-.86 3.47-.25 1.04.52 1.89 1.54 1.89 1.85 0 3.27-1.95 3.27-4.76 0-2.49-1.79-4.23-4.34-4.23-2.96 0-4.69 2.22-4.69 4.51 0 .89.34 1.85.77 2.37.09.1.1.19.07.3-.08.31-.24.97-.28 1.11-.04.18-.15.22-.34.13-1.25-.58-2.03-2.41-2.03-3.88 0-3.16 2.29-6.06 6.61-6.06 3.47 0 6.17 2.47 6.17 5.78 0 3.45-2.17 6.22-5.19 6.22-1.01 0-1.97-.53-2.29-1.15l-.62 2.38c-.23.87-.83 1.96-1.24 2.62.94.29 1.92.44 2.95.44 5.52 0 10-4.48 10-10S17.52 2 12 2z',
		'moon'      => 'M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z',
		'sun'       => 'M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58a.996.996 0 0 0-1.41 0 .996.996 0 0 0 0 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L5.99 4.58zm12.37 12.37a.996.996 0 0 0-1.41 0 .996.996 0 0 0 0 1.41l1.29 1.29c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41l-1.29-1.29zm-13.78 0l-1.29 1.29a.996.996 0 1 0 1.41 1.41l1.29-1.29a.996.996 0 1 0-1.41-1.41zm12.37-12.37l-1.29 1.29a.996.996 0 1 0 1.41 1.41l1.29-1.29a.996.996 0 0 0-1.41-1.41z',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="%s" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="%s"/></svg>',
		esc_attr( $class ),
		esc_attr( $paths[ $name ] )
	);
}

/* -------------------------------------------------------------------------
 * Meta helpers
 * ---------------------------------------------------------------------- */

/**
 * First category of a post, rendered as a link.
 */
function indfir_category_label( $post_id = null, $chip = false ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$terms   = get_the_category( $post_id );

	if ( empty( $terms ) ) {
		return '';
	}

	$term = $terms[0];
	// Prefer a child category over "Uncategorized"-style parents when both exist.
	foreach ( $terms as $candidate ) {
		if ( $candidate->parent ) {
			$term = $candidate;
			break;
		}
	}

	$classes = array( 'if-cat' );
	if ( ! empty( $term->slug ) ) {
		$classes[] = 'if-cat--' . sanitize_html_class( $term->slug );
	}
	if ( $chip ) {
		$classes[] = 'if-cat--chip';
	}

	return sprintf(
		'<a class="%s" href="%s">%s</a>',
		esc_attr( implode( ' ', $classes ) ),
		esc_url( get_category_link( $term->term_id ) ),
		esc_html( $term->name )
	);
}

/**
 * Estimate reading time in minutes based on post content word count.
 */
function indfir_reading_time( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( (string) $content ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );

	/* translators: %d: reading time in minutes */
	return sprintf( __( '%d mnt baca', 'indfir' ), $minutes );
}

/**
 * Author + date line. $style: 'full' | 'date' | 'ago'.
 */
function indfir_meta_line( $style = 'full' ) {
	$date = get_the_date();

	if ( 'ago' === $style ) {
		/* translators: %s: human readable time difference. */
		$date = sprintf( __( '%s lalu', 'indfir' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
	}

	$read_time = indfir_reading_time();

	if ( 'date' === $style || 'ago' === $style ) {
		return sprintf(
			'<div class="if-meta"><time datetime="%s">%s</time><span class="sep">&middot;</span><span class="if-meta__read">%s</span></div>',
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( $date ),
			esc_html( $read_time )
		);
	}

	return sprintf(
		'<div class="if-meta"><a href="%s">%s</a><span class="sep">-</span><time datetime="%s">%s</time><span class="sep">&middot;</span><span class="if-meta__read">%s</span></div>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() ),
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( $date ),
		esc_html( $read_time )
	);
}

/* -------------------------------------------------------------------------
 * Card renderer
 * ---------------------------------------------------------------------- */

/**
 * Render one post card. Must be called inside the loop.
 *
 * @param array $args {
 *     @type string $size       lead|lg|md|sm|xs — controls title scale.
 *     @type bool   $thumb      Show the featured image.
 *     @type string $thumb_size Registered image size.
 *     @type string $ratio      16-9|4-3|1-1|'' — CSS aspect ratio class.
 *     @type bool   $overlay    Render as a dark overlay hero.
 *     @type bool   $row        Render thumbnail beside the text.
 *     @type bool   $cat        Show the category label.
 *     @type bool   $chip       Render the category as a filled chip.
 *     @type int    $excerpt    Excerpt word count, 0 to hide.
 *     @type string $meta       full|date|ago|none.
 *     @type string $rank       Ranking badge string (e.g. '01', '02').
 *     @type string $class      Extra classes.
 * }
 */
function indfir_card( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'size'       => 'md',
			'thumb'      => true,
			'thumb_size' => 'indfir-card',
			'ratio'      => '16-9',
			'overlay'    => false,
			'row'        => false,
			'cat'        => true,
			'chip'       => false,
			'excerpt'    => 0,
			'meta'       => 'date',
			'rank'       => '',
			'class'      => '',
		)
	);

	$classes = array( 'if-card', 'if-card--' . $args['size'] );
	if ( $args['overlay'] ) {
		$classes[] = 'if-card--overlay';
	}
	if ( $args['row'] ) {
		$classes[] = 'if-card--row';
	}
	if ( $args['ratio'] ) {
		$classes[] = 'if-ratio-' . $args['ratio'];
	}
	if ( $args['class'] ) {
		$classes[] = $args['class'];
	}
	$has_thumb = $args['thumb'] && has_post_thumbnail();
	?>
	<article <?php post_class( implode( ' ', $classes ) ); ?>>
		<?php if ( $has_thumb ) : ?>
			<a class="if-card__thumb" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php if ( ! empty( $args['rank'] ) ) : ?>
					<span class="if-card__rank"><?php echo esc_html( $args['rank'] ); ?></span>
				<?php endif; ?>
				<?php
				the_post_thumbnail(
					$args['thumb_size'],
					array(
						'loading' => 'lazy',
						'alt'     => the_title_attribute( array( 'echo' => false ) ),
					)
				);
				?>
			</a>
		<?php elseif ( $args['thumb'] ) : ?>
			<a class="if-card__thumb if-card__thumb--placeholder" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php if ( ! empty( $args['rank'] ) ) : ?>
					<span class="if-card__rank"><?php echo esc_html( $args['rank'] ); ?></span>
				<?php endif; ?>
				<div class="if-placeholder">
					<span class="if-placeholder__mark">indfir</span>
				</div>
			</a>
		<?php endif; ?>

		<div class="if-card__body">
			<?php
			if ( $args['cat'] ) {
				echo wp_kses_post( indfir_category_label( get_the_ID(), $args['chip'] ) );
			}
			?>

			<h3 class="if-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

			<?php if ( 'none' !== $args['meta'] ) : ?>
				<?php echo wp_kses_post( indfir_meta_line( $args['meta'] ) ); ?>
			<?php endif; ?>

			<?php if ( $args['excerpt'] ) : ?>
				<p class="if-card__excerpt">
					<?php echo esc_html( wp_trim_words( get_the_excerpt(), (int) $args['excerpt'], '&hellip;' ) ); ?>
				</p>
			<?php endif; ?>
		</div>
	</article>
	<?php
}

/* -------------------------------------------------------------------------
 * Queries
 * ---------------------------------------------------------------------- */

/**
 * Standard front-page query helper.
 *
 * @param int   $category Category ID (0 = all).
 * @param int   $count    Posts to retrieve.
 * @param array $exclude  Post IDs already used on the page.
 * @param string $orderby 'date' or 'views'.
 * @param bool  $require_thumb Whether posts must have a featured image.
 */
function indfir_block_query( $category = 0, $count = 5, $exclude = array(), $orderby = 'date', $require_thumb = false ) {
	$count   = max( 1, (int) $count );
	$exclude = array_map( 'absint', (array) $exclude );

	$base = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'fields'              => 'ids',
		'posts_per_page'      => $count,
	);

	if ( $category ) {
		$base['cat'] = (int) $category;
	}

	if ( $require_thumb && 'views' === $orderby ) {
		$base['meta_query'] = array(
			'relation' => 'AND',
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS',
			),
			array(
				'key'     => '_indfir_views',
				'compare' => 'EXISTS',
			),
		);
		$base['orderby'] = array(
			'meta_value_num' => 'DESC',
			'date'           => 'DESC',
		);
	} elseif ( 'views' === $orderby ) {
		$base['meta_key'] = '_indfir_views';
		$base['orderby']  = array(
			'meta_value_num' => 'DESC',
			'date'           => 'DESC',
		);
	} elseif ( $require_thumb ) {
		$base['meta_query'] = array(
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS',
			),
		);
	}

	// Pass 1 - posts no earlier block on this page has used.
	$args                 = $base;
	$args['post__not_in'] = $exclude;
	$ids                  = get_posts( $args );

	/*
	 * Pass 2 - ordering by the view counter silently drops every post with no
	 * _indfir_views meta, so a freshly activated theme would render an empty
	 * "Popular" block. Comment count is a reasonable stand-in until the counter
	 * has data of its own.
	 */
	if ( empty( $ids ) && 'views' === $orderby ) {
		$args = $base;
		if ( $require_thumb ) {
			$args['meta_query'] = array(
				array(
					'key'     => '_thumbnail_id',
					'compare' => 'EXISTS',
				),
			);
		} else {
			unset( $args['meta_key'] );
		}
		$args['orderby']      = array(
			'comment_count' => 'DESC',
			'date'          => 'DESC',
		);
		$args['post__not_in'] = $exclude;
		$ids                  = get_posts( $args );
	}

	/*
	 * Pass 3 - top up. Every block sits in a fixed grid, so a short result set
	 * leaves visibly empty columns rather than a smaller block. That happens
	 * easily when several blocks share one category (or none is configured and
	 * they all draw from the whole site). Showing a post twice reads far better
	 * than a half-empty row, so refill from the posts already used.
	 */
	if ( count( $ids ) < $count ) {
		$fill                   = $base;
		$fill['posts_per_page'] = $count - count( $ids );
		$fill['post__not_in']   = array_unique( array_merge( $exclude, $ids ) );
		$fill_ids               = get_posts( $fill );
		if ( ! empty( $fill_ids ) ) {
			$ids = array_merge( $ids, $fill_ids );
		}
	}

	if ( empty( $ids ) ) {
		// post__in with an empty array would return the whole site.
		return new WP_Query( array( 'post__in' => array( 0 ) ) );
	}

	return new WP_Query(
		array(
			'post_type'           => 'post',
			'post__in'            => $ids,
			'orderby'             => 'post__in',
			'posts_per_page'      => count( $ids ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

/**
 * Track which posts a page has already shown so blocks do not repeat.
 */
function indfir_used_ids( $ids = null ) {
	static $used = array();

	if ( null === $ids ) {
		return $used;
	}

	foreach ( (array) $ids as $id ) {
		$used[] = (int) $id;
	}

	$used = array_unique( $used );

	return $used;
}

/* -------------------------------------------------------------------------
 * Section header
 * ---------------------------------------------------------------------- */

/**
 * Section title with an optional "VIEW ALL" button.
 */
function indfir_section_head( $title, $category = 0, $modifier = '' ) {
	if ( ! $title ) {
		return;
	}
	?>
	<div class="if-head <?php echo esc_attr( $modifier ); ?>">
		<h2 class="if-head__title"><?php echo esc_html( $title ); ?></h2>
		<?php if ( $category ) : ?>
			<a class="if-btn" href="<?php echo esc_url( get_category_link( $category ) ); ?>">
				<?php esc_html_e( 'View All', 'indfir' ); ?>
			</a>
		<?php endif; ?>
	</div>
	<?php
}

/* -------------------------------------------------------------------------
 * Breadcrumbs
 * ---------------------------------------------------------------------- */

function indfir_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	// Defer to Yoast when it is active and rendering.
	if ( function_exists( 'yoast_breadcrumb' ) && yoast_breadcrumb( '<nav class="if-crumbs">', '</nav>', false ) ) {
		yoast_breadcrumb( '<nav class="if-crumbs">', '</nav>' );
		return;
	}

	echo '<nav class="if-crumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'indfir' ) . '">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'indfir' ) . '</a>';

	if ( is_singular( 'post' ) ) {
		$cats = get_the_category();
		if ( ! empty( $cats ) ) {
			echo '<span class="sep">/</span>';
			echo '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '">' . esc_html( $cats[0]->name ) . '</a>';
		}
		echo '<span class="sep">/</span><span>' . esc_html( wp_trim_words( get_the_title(), 8, '&hellip;' ) ) . '</span>';
	} elseif ( is_category() || is_tag() || is_tax() ) {
		echo '<span class="sep">/</span><span>' . esc_html( single_term_title( '', false ) ) . '</span>';
	} elseif ( is_search() ) {
		echo '<span class="sep">/</span><span>' . esc_html__( 'Hasil pencarian', 'indfir' ) . '</span>';
	} elseif ( is_page() ) {
		echo '<span class="sep">/</span><span>' . esc_html( get_the_title() ) . '</span>';
	}

	echo '</nav>';
}

/* -------------------------------------------------------------------------
 * Pagination
 * ---------------------------------------------------------------------- */

function indfir_pagination( $query = null ) {
	global $wp_query;
	$query = $query ? $query : $wp_query;

	if ( $query->max_num_pages < 2 ) {
		return;
	}

	$current = max( 1, get_query_var( 'paged' ) );
	?>
	<div class="if-pagination">
		<?php
		echo paginate_links(
			array(
				'total'     => $query->max_num_pages,
				'current'   => $current,
				'mid_size'  => 1,
				'end_size'  => 1,
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
				'type'      => 'list',
			)
		);
		?>
		<span class="if-pagination__count">
			<?php
			printf(
				/* translators: 1: current page, 2: total pages. */
				esc_html__( 'Halaman %1$d dari %2$d', 'indfir' ),
				(int) $current,
				(int) $query->max_num_pages
			);
			?>
		</span>
	</div>
	<?php
}

/* -------------------------------------------------------------------------
 * Social links
 * ---------------------------------------------------------------------- */

function indfir_social_links() {
	$networks = array(
		'facebook'  => get_theme_mod( 'indfir_social_facebook', '' ),
		'instagram' => get_theme_mod( 'indfir_social_instagram', '' ),
		'x'         => get_theme_mod( 'indfir_social_x', '' ),
		'youtube'   => get_theme_mod( 'indfir_social_youtube', '' ),
	);

	$networks = array_filter( $networks );
	if ( empty( $networks ) ) {
		return;
	}

	echo '<ul class="if-social">';
	foreach ( $networks as $name => $url ) {
		printf(
			'<li><a href="%s" target="_blank" rel="noopener noreferrer"><span class="screen-reader-text">%s</span>%s</a></li>',
			esc_url( $url ),
			esc_html( ucfirst( $name ) ),
			indfir_icon( $name ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- hardcoded SVG.
		);
	}
	echo '</ul>';
}

/* -------------------------------------------------------------------------
 * Share buttons
 * ---------------------------------------------------------------------- */

function indfir_share_buttons( $variant = 'inline' ) {
	if ( ! get_theme_mod( 'indfir_show_share', true ) ) {
		return;
	}

	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( get_the_title() );

	$links = array(
		'facebook'  => array( 'Facebook', 'https://www.facebook.com/sharer/sharer.php?u=' . $url ),
		'x'         => array( 'X', 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title ),
		'whatsapp'  => array( 'WhatsApp', 'https://api.whatsapp.com/send?text=' . $title . '%20' . $url ),
		'telegram'  => array( 'Telegram', 'https://t.me/share/url?url=' . $url . '&text=' . $title ),
		'linkedin'  => array( 'LinkedIn', 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url ),
		'pinterest' => array( 'Pinterest', 'https://pinterest.com/pin/create/button/?url=' . $url . '&description=' . $title ),
	);

	// The inline row keeps the three original networks; the rail shows the full set.
	if ( 'rail' !== $variant ) {
		$links = array_intersect_key( $links, array_flip( array( 'facebook', 'x', 'whatsapp' ) ) );
	}

	printf(
		'<div class="if-share if-share--%s">',
		esc_attr( $variant )
	);

	if ( 'rail' === $variant ) {
		printf(
			'<span class="if-share__label">%s</span>',
			esc_html__( 'Bagikan', 'indfir' )
		);
	}

	foreach ( $links as $icon => $data ) {
		printf(
			'<a class="if-share__btn if-share__btn--%s" href="%s" target="_blank" rel="noopener noreferrer" title="%s"><span class="screen-reader-text">%s</span>%s<span class="if-share__name">%s</span></a>',
			esc_attr( $icon ),
			esc_url( $data[1] ),
			esc_attr( $data[0] ),
			esc_html( $data[0] ),
			indfir_icon( $icon ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- hardcoded SVG.
			esc_html( $data[0] )
		);
	}

	printf(
		'<button type="button" class="if-share__btn if-share__btn--copy if-share__copy" data-url="%s" title="%s"><span class="screen-reader-text">%s</span>%s<span class="if-share__name">%s</span></button>',
		esc_url( get_permalink() ),
		esc_attr__( 'Salin tautan', 'indfir' ),
		esc_html__( 'Salin tautan', 'indfir' ),
		indfir_icon( 'link' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- hardcoded SVG.
		esc_html__( 'Salin', 'indfir' )
	);

	echo '</div>';
}

/* -------------------------------------------------------------------------
 * Related posts
 * ---------------------------------------------------------------------- */

function indfir_related_posts() {
	if ( ! get_theme_mod( 'indfir_show_related', true ) ) {
		return;
	}

	$cats = wp_get_post_categories( get_the_ID() );
	if ( empty( $cats ) ) {
		return;
	}

	$related = new WP_Query(
		array(
			'category__in'        => $cats,
			'post__not_in'        => array( get_the_ID() ),
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	if ( ! $related->have_posts() ) {
		return;
	}
	?>
	<section class="if-related">
		<?php indfir_section_head( __( 'Baca Juga', 'indfir' ), 0, 'if-head--plain' ); ?>
		<div class="if-related__grid">
			<?php
			while ( $related->have_posts() ) {
				$related->the_post();
				indfir_card(
					array(
						'size'  => 'sm',
						'ratio' => '16-9',
						'meta'  => 'date',
					)
				);
			}
			?>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}

/* -------------------------------------------------------------------------
 * Menu fallback
 * ---------------------------------------------------------------------- */

/**
 * Shown until a menu is assigned to the "primary" location: Home plus the
 * busiest categories, so a freshly activated theme still navigates.
 */
function indfir_menu_fallback() {
	$cats = get_categories(
		array(
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => 7,
			'hide_empty' => true,
			'parent'     => 0,
		)
	);

	echo '<ul class="if-nav__list">';
	printf(
		'<li class="%s"><a href="%s">%s</a></li>',
		is_front_page() ? 'current-menu-item' : '',
		esc_url( home_url( '/' ) ),
		esc_html__( 'Home', 'indfir' )
	);

	foreach ( $cats as $cat ) {
		printf(
			'<li class="%s"><a href="%s">%s</a></li>',
			is_category( $cat->term_id ) ? 'current-menu-item' : '',
			esc_url( get_category_link( $cat->term_id ) ),
			esc_html( $cat->name )
		);
	}
	echo '</ul>';
}

/* -------------------------------------------------------------------------
 * Breaking news ticker
 * ---------------------------------------------------------------------- */

/**
 * Renders an animated breaking news marquee below the header.
 */
function indfir_breaking_ticker() {
	$ticker_query = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 6,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	if ( ! $ticker_query->have_posts() ) {
		return;
	}

	$items = array();
	while ( $ticker_query->have_posts() ) {
		$ticker_query->the_post();
		$items[] = array(
			'url'   => get_permalink(),
			'title' => get_the_title(),
			'time'  => sprintf( __( '%s lalu', 'indfir' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ),
		);
	}
	wp_reset_postdata();

	if ( empty( $items ) ) {
		return;
	}
	?>
	<div class="if-ticker" id="if-ticker" aria-label="<?php esc_attr_e( 'Berita Terkini', 'indfir' ); ?>">
		<div class="if-wrap if-ticker__wrap">
			<div class="if-ticker__badge">
				<span class="if-ticker__pulse"></span>
				<span class="if-ticker__title"><?php esc_html_e( 'TERKINI', 'indfir' ); ?></span>
			</div>
			<div class="if-ticker__marquee">
				<div class="if-ticker__track">
					<?php
					// Loop twice to create an infinite, seamless continuous scrolling marquee
					for ( $loop = 0; $loop < 2; $loop++ ) :
						foreach ( $items as $item ) :
							?>
							<a class="if-ticker__item" href="<?php echo esc_url( $item['url'] ); ?>">
								<span class="if-ticker__bullet">&bull;</span>
								<span class="if-ticker__text"><?php echo esc_html( $item['title'] ); ?></span>
								<span class="if-ticker__time"><?php echo esc_html( $item['time'] ); ?></span>
							</a>
							<?php
						endforeach;
					endfor;
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}


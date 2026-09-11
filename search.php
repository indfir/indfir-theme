<?php
/**
 * Search results.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="if-archive-head">
	<div class="if-wrap">
		<?php indfir_breadcrumbs(); ?>
		<h1>
			<?php
			printf(
				/* translators: %s: search term. */
				esc_html__( 'Results for: %s', 'indfir' ),
				'<span>' . esc_html( get_search_query() ) . '</span>'
			);
			?>
		</h1>
		<p>
			<?php
			printf(
				/* translators: %d: number of results. */
				esc_html( _n( '%d article found.', '%d articles found.', (int) $GLOBALS['wp_query']->found_posts, 'indfir' ) ),
				(int) $GLOBALS['wp_query']->found_posts
			);
			?>
		</p>
		<?php get_search_form(); ?>
	</div>
</div>

<main id="if-content" class="if-main" style="padding-top:0">
	<div class="if-wrap">
		<div class="if-layout <?php echo is_active_sidebar( 'sidebar-main' ) ? '' : 'if-layout--full'; ?>">
			<div>
				<?php if ( have_posts() ) : ?>
					<div class="if-loop <?php echo is_active_sidebar( 'sidebar-main' ) ? '' : 'if-loop--grid3'; ?>">
						<?php
						$if_grid = ! is_active_sidebar( 'sidebar-main' );
						while ( have_posts() ) :
							the_post();
							indfir_card(
								array(
									'size'       => $if_grid ? 'md' : 'lg',
									'row'        => ! $if_grid,
									'thumb_size' => 'indfir-card',
									'ratio'      => '16-9',
									'meta'       => 'date',
									'excerpt'    => $if_grid ? 16 : 24,
								)
							);
						endwhile;
						?>
					</div>
					<?php indfir_pagination(); ?>
				<?php else : ?>
					<p><?php esc_html_e( 'No matches found. Try different keywords.', 'indfir' ); ?></p>
				<?php endif; ?>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</main>

<?php
get_footer();

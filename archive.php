<?php
/**
 * Category, tag, author, date archives.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="if-archive-head">
	<div class="if-wrap">
		<?php
		if ( get_theme_mod( 'indfir_show_crumbs', true ) ) {
			indfir_breadcrumbs();
		}
		the_archive_title( '<h1>', '</h1>' );
		the_archive_description( '<p>', '</p>' );
		?>
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
					<p><?php esc_html_e( 'No articles in this archive.', 'indfir' ); ?></p>
					<?php get_search_form(); ?>
				<?php endif; ?>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</main>

<?php
get_footer();

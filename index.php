<?php
/**
 * Fallback template: blog index and anything without a more specific template.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="if-content" class="if-main">
	<div class="if-wrap">
		<div class="if-layout <?php echo is_active_sidebar( 'sidebar-main' ) ? '' : 'if-layout--full'; ?>">
			<div>
				<?php if ( is_home() && ! is_front_page() ) : ?>
					<div class="if-head">
						<h1 class="if-head__title"><?php single_post_title(); ?></h1>
					</div>
				<?php endif; ?>

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
					<p><?php esc_html_e( 'No articles yet.', 'indfir' ); ?></p>
					<?php get_search_form(); ?>
				<?php endif; ?>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</main>

<?php
get_footer();

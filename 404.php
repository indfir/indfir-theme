<?php
/**
 * 404.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="if-content" class="if-main">
	<div class="if-wrap">
		<div class="if-404">
			<p class="if-404__code">404</p>
			<h1><?php esc_html_e( 'Page not found', 'indfir' ); ?></h1>
			<p><?php esc_html_e( 'The link may have changed or the article has been removed.', 'indfir' ); ?></p>
			<?php get_search_form(); ?>
		</div>

		<?php
		$if_recent = indfir_block_query( 0, 3 );
		if ( $if_recent->have_posts() ) :
			?>
			<section style="margin-top:20px">
				<?php indfir_section_head( __( 'Latest Articles', 'indfir' ), 0, 'if-head--center' ); ?>
				<div class="if-related__grid">
					<?php
					while ( $if_recent->have_posts() ) :
						$if_recent->the_post();
						indfir_card( array( 'size' => 'sm' ) );
					endwhile;
					?>
				</div>
			</section>
			<?php
		endif;
		wp_reset_postdata();
		?>
	</div>
</main>

<?php
get_footer();

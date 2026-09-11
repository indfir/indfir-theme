<?php
/**
 * Site footer.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

$if_has_widgets = is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' )
	|| is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' );
?>

<footer class="if-footer">
	<?php if ( has_nav_menu( 'footer' ) ) : ?>
		<div class="if-footer__nav">
			<div class="if-wrap">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'if-footer__menu',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
		</div>
	<?php endif; ?>

	<div class="if-wrap">
		<div class="if-footer__cols">
			<?php if ( $if_has_widgets ) : ?>
				<?php for ( $if_i = 1; $if_i <= 4; $if_i++ ) : ?>
					<div class="if-footer__col">
						<?php dynamic_sidebar( 'footer-' . $if_i ); ?>
					</div>
				<?php endfor; ?>
			<?php else : ?>

				<div class="if-footer__col">
					<h2 class="if-footer__title"><?php echo esc_html( get_theme_mod( 'indfir_footer_about_title', __( 'Website', 'indfir' ) ) ); ?></h2>
					<div class="if-footer__about">
						<?php
						$if_about = get_theme_mod( 'indfir_footer_about', '' );
						echo wp_kses_post( wpautop( $if_about ? $if_about : get_bloginfo( 'description' ) ) );
						?>
					</div>
					<?php if ( has_nav_menu( 'topbar' ) ) : ?>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'topbar',
								'container'      => false,
								'menu_class'     => 'if-footer__links',
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					<?php endif; ?>
				</div>

				<div class="if-footer__col">
					<h2 class="if-widget__title"><?php esc_html_e( 'Latest', 'indfir' ); ?></h2>
					<?php
					$if_latest = indfir_block_query( 0, 3 );
					if ( $if_latest->have_posts() ) :
						?>
						<ul class="if-list">
							<?php
							while ( $if_latest->have_posts() ) :
								$if_latest->the_post();
								?>
								<li>
									<h3 class="if-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<div class="if-meta">
										<?php echo wp_kses_post( indfir_category_label() ); ?>
										<span><?php echo esc_html( get_the_date() ); ?></span>
									</div>
								</li>
							<?php endwhile; ?>
						</ul>
						<?php
					endif;
					wp_reset_postdata();
					?>
				</div>

				<div class="if-footer__col">
					<h2 class="if-widget__title"><?php esc_html_e( 'Popular', 'indfir' ); ?></h2>
					<?php
					$if_pop = indfir_block_query( 0, 3, array(), 'views' );
					if ( $if_pop->have_posts() ) :
						?>
						<ul class="if-list">
							<?php
							while ( $if_pop->have_posts() ) :
								$if_pop->the_post();
								?>
								<li>
									<h3 class="if-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<div class="if-meta">
										<?php echo wp_kses_post( indfir_category_label() ); ?>
										<span><?php echo esc_html( get_the_date() ); ?></span>
									</div>
								</li>
							<?php endwhile; ?>
						</ul>
						<?php
					endif;
					wp_reset_postdata();
					?>
				</div>

				<div class="if-footer__col">
					<h2 class="if-widget__title"><?php esc_html_e( 'Browse', 'indfir' ); ?></h2>
					<ul class="if-list">
						<?php
						wp_list_categories(
							array(
								'title_li'   => '',
								'number'     => 8,
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => false,
							)
						);
						?>
					</ul>
				</div>

			<?php endif; ?>
		</div>
	</div>

	<div class="if-footer__bottom">
		<div class="if-wrap">
			<?php
			$if_copy = get_theme_mod( 'indfir_footer_copyright', '' );
			if ( $if_copy ) {
				echo wp_kses_post( $if_copy );
			} else {
				printf(
					'&copy; %s %s',
					esc_html( wp_date( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
			}
			?>
		</div>
	</div>
</footer>

<button class="if-top" aria-label="<?php esc_attr_e( 'Kembali ke atas', 'indfir' ); ?>">
	<?php echo indfir_icon( 'arrow-up' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</button>

<?php wp_footer(); ?>
</body>
</html>

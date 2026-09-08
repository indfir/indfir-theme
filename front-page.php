<?php
/**
 * Homepage: magazine blocks followed by the paginated latest-posts loop.
 *
 * On page 2 and beyond only the loop renders, so pagination behaves like a
 * normal archive.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

get_header();

/*
 * The front page may be the posts index or a static page. When it is a static
 * page the main query holds that page, not posts, so the "Terbaru" list has to
 * run its own query — otherwise the loop would print the static page's raw
 * content (on a site migrated from a page builder, that means raw shortcodes).
 */
$if_static_front = ( 'page' === get_option( 'show_on_front' ) );

if ( $if_static_front ) {
	$if_paged = max( 1, (int) get_query_var( 'page' ), (int) get_query_var( 'paged' ) );
	$if_latest = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 9,
			'paged'               => $if_paged,
			'ignore_sticky_posts' => true,
		)
	);
} else {
	global $wp_query;
	$if_paged  = max( 1, (int) get_query_var( 'paged' ) );
	$if_latest = $wp_query;
}

$if_show_blocks = ( $if_paged < 2 );
?>

<main id="if-content" class="if-home">

<?php if ( $if_show_blocks ) : ?>

	<?php
	/* ------------------------------------------------------------------
	 * Hero — breaking news, featured story, popular column
	 * --------------------------------------------------------------- */
	$if_hero = indfir_block_query( 0, 3 );

	if ( $if_hero->have_posts() ) :
		?>
		<section class="if-hero">
			<div class="if-wrap">
				<div class="if-hero__grid">

					<div class="if-hero__left">
						<div class="if-head if-head--plain">
							<h2 class="if-head__title"><?php echo esc_html( get_theme_mod( 'indfir_hero_label', __( 'Breaking news:', 'indfir' ) ) ); ?></h2>
							<?php if ( get_option( 'page_for_posts' ) ) : ?>
								<a class="if-btn" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">
									<?php esc_html_e( 'Read More', 'indfir' ); ?>
								</a>
							<?php endif; ?>
						</div>

						<?php
						$if_i = 0;
						$if_list_open = false;

						while ( $if_hero->have_posts() ) :
							$if_hero->the_post();
							indfir_used_ids( get_the_ID() );

							if ( 0 === $if_i ) {
								// Lead story: no image, large headline, excerpt.
								indfir_card(
									array(
										'size'    => 'lead',
										'thumb'   => false,
										'meta'    => 'ago',
										'excerpt' => 26,
										'class'   => 'if-card--nothumb',
									)
								);
								echo '<ul class="if-list" style="margin-top:26px">';
								$if_list_open = true;
							} else {
								echo '<li>';
								indfir_card(
									array(
										'size'  => 'sm',
										'thumb' => false,
										'cat'   => false,
										'meta'  => 'none',
									)
								);
								echo '<div class="if-meta">' . wp_kses_post( indfir_category_label() )
									. '<span class="sep"></span>'
									. esc_html( sprintf( __( '%s lalu', 'indfir' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ) )
									. '</div>';
								echo '</li>';
							}

							$if_i++;
						endwhile;

						if ( $if_list_open ) {
							echo '</ul>';
						}
						wp_reset_postdata();
						?>
					</div>

					<div class="if-hero__center">
						<?php
						$if_feature = indfir_block_query( 0, 1, indfir_used_ids(), 'date', true );
						if ( $if_feature->have_posts() ) :
							while ( $if_feature->have_posts() ) :
								$if_feature->the_post();
								indfir_used_ids( get_the_ID() );
								indfir_card(
									array(
										'size'       => 'lead',
										'thumb_size' => 'indfir-lead',
										'ratio'      => '4-3',
										'meta'       => 'date',
										'excerpt'    => 24,
									)
								);
							endwhile;
						endif;
						wp_reset_postdata();
						?>
					</div>

					<div class="if-hero__right">
						<div class="if-head if-head--plain">
							<h2 class="if-head__title"><?php echo esc_html( get_theme_mod( 'indfir_popular_label', __( 'Popular:', 'indfir' ) ) ); ?></h2>
						</div>
						<?php
						$if_pop = indfir_block_query( 0, 2, indfir_used_ids(), 'views', true );
						if ( $if_pop->have_posts() ) :
							echo '<div class="if-grid">';
							$if_pop_rank = 1;
							while ( $if_pop->have_posts() ) :
								$if_pop->the_post();
								indfir_used_ids( get_the_ID() );
								indfir_card(
									array(
										'size'  => 'sm',
										'ratio' => '16-9',
										'meta'  => 'none',
										'rank'  => sprintf( '%02d', $if_pop_rank ),
									)
								);
								$if_pop_rank++;
							endwhile;
							echo '</div>';
						endif;
						wp_reset_postdata();
						?>
					</div>

				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	/* ------------------------------------------------------------------
	 * Block 1 + 2 + 3 — main category beside two narrow category columns
	 * --------------------------------------------------------------- */
	$if_b1_title = get_theme_mod( 'indfir_block1_title', 'General' );
	$if_b1_cat   = (int) get_theme_mod( 'indfir_block1_cat', 0 );

	if ( $if_b1_title ) :
		// Pre-fetch side blocks (Block 2: Data/AI, Block 3: Astronomi)
		$if_sides = array();
		foreach ( array( 2, 3 ) as $if_slot ) {
			$if_def_title = ( 2 === $if_slot ) ? 'Data/AI' : 'Astronomi';
			$if_def_cat   = ( 2 === $if_slot ) ? 384 : 383;
			$if_title     = get_theme_mod( 'indfir_block' . $if_slot . '_title', $if_def_title );
			$if_cat       = (int) get_theme_mod( 'indfir_block' . $if_slot . '_cat', $if_def_cat );

			if ( ! $if_title ) {
				continue;
			}

			$if_q = indfir_block_query( $if_cat, 5, indfir_used_ids() );
			if ( $if_q->have_posts() ) {
				$if_sides[] = array(
					'title' => $if_title,
					'cat'   => $if_cat,
					'query' => $if_q,
				);
			} else {
				wp_reset_postdata();
			}
		}

		$if_side_count = count( $if_sides );
		$if_b3_class   = 'if-block-3';
		if ( 0 === $if_side_count ) {
			$if_b3_class .= ' if-block-3--full';
		} elseif ( 1 === $if_side_count ) {
			$if_b3_class .= ' if-block-3--one-side';
		}
		?>
		<section class="if-section if-section--soft">
			<div class="if-wrap">
				<div class="<?php echo esc_attr( $if_b3_class ); ?>">

					<div class="if-block-3__wrapper">
						<?php indfir_section_head( $if_b1_title, $if_b1_cat ); ?>
						<div class="if-block-3__main">
							<?php
							$if_b1 = indfir_block_query( $if_b1_cat, 8, indfir_used_ids() );
							$if_n  = 0;
							$if_bucket = array( array(), array() );

							while ( $if_b1->have_posts() ) {
								$if_b1->the_post();
								indfir_used_ids( get_the_ID() );
								$if_bucket[ $if_n % 2 ][] = get_the_ID();
								$if_n++;
							}
							wp_reset_postdata();

							foreach ( $if_bucket as $if_column ) {
								if ( empty( $if_column ) ) {
									continue;
								}
								echo '<div class="if-block-3__col">';
								$if_first = true;
								foreach ( $if_column as $if_pid ) {
									$if_q = new WP_Query(
										array(
											'p'         => $if_pid,
											'post_type' => 'post',
											'no_found_rows' => true,
										)
									);
									while ( $if_q->have_posts() ) {
										$if_q->the_post();
										if ( $if_first ) {
											indfir_card(
												array(
													'size'    => 'lg',
													'ratio'   => '4-3',
													'meta'    => 'full',
													'excerpt' => 20,
												)
											);
											echo '<ul class="if-list" style="margin-top:22px">';
											$if_first = false;
										} else {
											echo '<li>';
											indfir_card(
												array(
													'size'  => 'xs',
													'thumb' => false,
													'cat'   => false,
													'meta'  => 'date',
												)
											);
											echo '</li>';
										}
									}
									wp_reset_postdata();
								}
								if ( ! $if_first ) {
									echo '</ul>';
								}
								echo '</div>';
							}
							?>
						</div>
					</div>

					<?php
					foreach ( $if_sides as $if_side_data ) :
						$if_title = $if_side_data['title'];
						$if_cat   = $if_side_data['cat'];
						$if_q     = $if_side_data['query'];
						?>
						<div class="if-block-3__side">
							<?php indfir_section_head( $if_title, $if_cat, 'if-head--plain' ); ?>
							<?php
							$if_first = true;
							while ( $if_q->have_posts() ) :
								$if_q->the_post();
								indfir_used_ids( get_the_ID() );

								if ( $if_first ) {
									indfir_card(
										array(
											'size'    => 'md',
											'ratio'   => '16-9',
											'cat'     => false,
											'meta'    => 'full',
											'excerpt' => 18,
										)
									);
									echo '<ul class="if-list" style="margin-top:20px">';
									$if_first = false;
								} else {
									echo '<li>';
									indfir_card(
										array(
											'size'  => 'xs',
											'thumb' => false,
											'cat'   => false,
											'meta'  => 'date',
										)
									);
									echo '</li>';
								}
							endwhile;
							if ( ! $if_first ) {
								echo '</ul>';
							}
							wp_reset_postdata();
							?>
						</div>
						<?php
					endforeach;
					?>

				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	/* ------------------------------------------------------------------
	 * Block 4 — two overlay heroes + card row, with block 5 as a sidebar
	 * --------------------------------------------------------------- */
	$if_b4_title = get_theme_mod( 'indfir_block4_title', 'Teknologi' );
	$if_b4_cat   = (int) get_theme_mod( 'indfir_block4_cat', 0 );

	if ( $if_b4_title ) :
		$if_b4 = indfir_block_query( $if_b4_cat, 5, indfir_used_ids() );

		if ( $if_b4->have_posts() ) :
			?>
			<section class="if-section">
				<div class="if-wrap">
					<div class="if-block-feat">

						<div class="if-block-feat__main">
							<?php indfir_section_head( $if_b4_title, $if_b4_cat ); ?>

							<div class="if-block-feat__heroes">
								<?php
								$if_n = 0;
								$if_rest = array();
								while ( $if_b4->have_posts() ) {
									$if_b4->the_post();
									indfir_used_ids( get_the_ID() );

									if ( $if_n < 2 ) {
										indfir_card(
											array(
												'size'       => 'lg',
												'thumb_size' => 'indfir-hero',
												'ratio'      => '',
												'overlay'    => true,
												'chip'       => true,
												'meta'       => 'none',
											)
										);
									} else {
										$if_rest[] = get_the_ID();
									}
									$if_n++;
								}
								wp_reset_postdata();
								?>
							</div>

							<?php if ( ! empty( $if_rest ) ) : ?>
								<div class="if-block-feat__row">
									<?php
									foreach ( $if_rest as $if_pid ) {
										$if_q = new WP_Query(
											array(
												'p'             => $if_pid,
												'post_type'     => 'post',
												'no_found_rows' => true,
											)
										);
										while ( $if_q->have_posts() ) {
											$if_q->the_post();
											indfir_card(
												array(
													'size'  => 'sm',
													'ratio' => '16-9',
													'meta'  => 'date',
												)
											);
										}
										wp_reset_postdata();
									}
									?>
								</div>
							<?php endif; ?>
						</div>

						<?php
						$if_b5_title = get_theme_mod( 'indfir_block5_title', 'Sains' );
						$if_b5_cat   = (int) get_theme_mod( 'indfir_block5_cat', 0 );

						if ( $if_b5_title ) :
							$if_b5 = indfir_block_query( $if_b5_cat, 4, indfir_used_ids() );
							if ( $if_b5->have_posts() ) :
								?>
								<aside class="if-block-feat__side">
									<?php indfir_section_head( $if_b5_title, $if_b5_cat, 'if-head--plain' ); ?>
									<div class="if-grid">
										<?php
										while ( $if_b5->have_posts() ) :
											$if_b5->the_post();
											indfir_used_ids( get_the_ID() );
											indfir_card(
												array(
													'size'       => 'xs',
													'row'        => true,
													'thumb_size' => 'indfir-thumb',
													'ratio'      => '',
													'meta'       => 'none',
													'class'      => 'if-card--row-rev',
												)
											);
										endwhile;
										wp_reset_postdata();
										?>
									</div>
								</aside>
								<?php
							else :
								wp_reset_postdata();
							endif;
						endif;
						?>

					</div>
				</div>
			</section>
			<?php
		else :
			wp_reset_postdata();
		endif;
	endif;
	?>

	<?php
	/* ------------------------------------------------------------------
	 * Block 6 — full-width category showcase
	 * --------------------------------------------------------------- */
	$if_b6_title = get_theme_mod( 'indfir_block6_title', 'Trading & Kripto' );
	$if_b6_cat   = (int) get_theme_mod( 'indfir_block6_cat', 0 );

	if ( $if_b6_title ) :
		$if_b6 = indfir_block_query( $if_b6_cat, 8, indfir_used_ids() );

		if ( $if_b6->have_posts() ) :
			?>
			<section class="if-section if-section--soft if-block-wide">
				<div class="if-wrap">
					<?php indfir_section_head( $if_b6_title, $if_b6_cat, 'if-head--center' ); ?>

					<?php
					$if_n = 0;
					$if_top = array();
					$if_bottom = array();
					while ( $if_b6->have_posts() ) {
						$if_b6->the_post();
						indfir_used_ids( get_the_ID() );
						if ( $if_n < 3 ) {
							$if_top[] = get_the_ID();
						} else {
							$if_bottom[] = get_the_ID();
						}
						$if_n++;
					}
					wp_reset_postdata();
					?>

					<div class="if-block-wide__top">
						<?php
						foreach ( $if_top as $if_pid ) {
							$if_q = new WP_Query(
								array(
									'p'             => $if_pid,
									'post_type'     => 'post',
									'no_found_rows' => true,
								)
							);
							while ( $if_q->have_posts() ) {
								$if_q->the_post();
								indfir_card(
									array(
										'size'       => 'lg',
										'thumb_size' => 'indfir-lead',
										'ratio'      => '4-3',
										'meta'       => 'full',
									)
								);
							}
							wp_reset_postdata();
						}
						?>
					</div>

					<?php if ( ! empty( $if_bottom ) ) : ?>
						<div class="if-block-wide__bottom">
							<?php
							foreach ( $if_bottom as $if_pid ) {
								$if_q = new WP_Query(
									array(
										'p'             => $if_pid,
										'post_type'     => 'post',
										'no_found_rows' => true,
									)
								);
								while ( $if_q->have_posts() ) {
									$if_q->the_post();
									indfir_card(
										array(
											'size'  => 'xs',
											'ratio' => '16-9',
											'meta'  => 'none',
										)
									);
								}
								wp_reset_postdata();
							}
							?>
						</div>
					<?php endif; ?>
				</div>
			</section>
			<?php
		else :
			wp_reset_postdata();
		endif;
	endif;
	?>

<?php endif; // $if_show_blocks ?>

	<?php
	/* ------------------------------------------------------------------
	 * Latest posts — the main query, carries pagination
	 * --------------------------------------------------------------- */
	?>
	<section class="if-section">
		<div class="if-wrap">
			<div class="if-layout <?php echo is_active_sidebar( 'sidebar-main' ) ? '' : 'if-layout--full'; ?>">
				<div>
					<?php
					$if_latest_title = get_theme_mod( 'indfir_latest_title', __( 'Terbaru', 'indfir' ) );
					if ( $if_latest_title ) {
						indfir_section_head( $if_latest_title, 0 );
					}
					?>

					<?php if ( $if_latest->have_posts() ) : ?>
						<div class="if-loop if-loop--grid3">
							<?php
							while ( $if_latest->have_posts() ) :
								$if_latest->the_post();
								indfir_card(
									array(
										'size'       => 'md',
										'row'        => false,
										'thumb_size' => 'indfir-card',
										'ratio'      => '16-9',
										'meta'       => 'date',
										'excerpt'    => 16,
									)
								);
							endwhile;
							wp_reset_postdata();
							?>
						</div>

						<?php indfir_pagination( $if_latest, $if_paged ); ?>

					<?php else : ?>
						<p><?php esc_html_e( 'Belum ada artikel.', 'indfir' ); ?></p>
					<?php endif; ?>
				</div>

				<?php get_sidebar(); ?>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();

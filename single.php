<?php
/**
 * Single post.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="if-nowreading" id="if-nowreading" aria-hidden="true">
	<div class="if-nowreading__inner">
		<span class="if-nowreading__label"><?php esc_html_e( 'Now reading', 'indfir' ); ?></span>
		<span class="if-nowreading__title"><?php the_title(); ?></span>
	</div>
</div>

<main id="if-content" class="if-main if-main--single">
	<div class="if-wrap">
		<div class="if-read">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<aside class="if-read__rail" aria-label="<?php esc_attr_e( 'Share article', 'indfir' ); ?>">
					<?php indfir_share_buttons( 'rail' ); ?>
				</aside>

				<div class="if-read__body">
					<article <?php post_class(); ?>>

						<header class="if-entry__header">
							<?php
							if ( get_theme_mod( 'indfir_show_crumbs', true ) ) {
								indfir_breadcrumbs();
							}
							echo wp_kses_post( indfir_category_label() );
							?>

							<h1 class="if-entry__title"><?php the_title(); ?></h1>

							<?php if ( has_excerpt() ) : ?>
								<p class="if-entry__standfirst"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php endif; ?>

							<div class="if-entry__meta">
								<span>
									<?php esc_html_e( 'By', 'indfir' ); ?>
									<a href="#if-author" title="<?php esc_attr_e( 'View author profile', 'indfir' ); ?>">
										<?php the_author(); ?>
									</a>
								</span>
								<span class="sep">&middot;</span>
								<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
									<?php echo esc_html( get_the_date() ); ?>
								</time>
								<?php if ( get_the_modified_date() !== get_the_date() ) : ?>
									<span class="sep">&middot;</span>
									<span><?php printf( esc_html__( 'Updated %s', 'indfir' ), esc_html( get_the_modified_date() ) ); ?></span>
								<?php endif; ?>
								<?php if ( comments_open() ) : ?>
									<span class="sep">&middot;</span>
									<a href="#comments"><?php comments_number( esc_html__( 'No comments yet', 'indfir' ), esc_html__( '1 comment', 'indfir' ), esc_html__( '% comments', 'indfir' ) ); ?></a>
								<?php endif; ?>
								<span class="sep">&middot;</span>
								<span class="if-entry__read">⏱ <?php echo esc_html( indfir_reading_time() ); ?></span>
							</div>
						</header>

						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="if-entry__thumb">
								<?php the_post_thumbnail( 'full' ); ?>
								<?php if ( wp_get_attachment_caption( get_post_thumbnail_id() ) ) : ?>
									<figcaption><?php echo esc_html( wp_get_attachment_caption( get_post_thumbnail_id() ) ); ?></figcaption>
								<?php endif; ?>
							</figure>
						<?php endif; ?>

						<div class="if-entry__content">
							<?php
							the_content();
							wp_link_pages(
								array(
									'before' => '<div class="if-pagelinks">',
									'after'  => '</div>',
								)
							);
							?>
						</div>

						<?php
						$if_tags = get_the_tag_list( '', '', '' );
						if ( $if_tags ) :
							?>
							<div class="if-tags"><?php echo wp_kses_post( $if_tags ); ?></div>
						<?php endif; ?>

						<?php indfir_share_buttons(); ?>

						<?php if ( get_theme_mod( 'indfir_show_author', true ) && get_the_author_meta( 'description' ) ) : ?>
							<div class="if-author" id="if-author">
								<?php echo get_avatar( get_the_author_meta( 'ID' ), 74 ); ?>
								<div>
									<h2 class="if-author__name"><?php the_author(); ?></h2>
									<p class="if-author__bio"><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>
								</div>
							</div>
						<?php endif; ?>

						<?php
						if ( get_theme_mod( 'indfir_show_prevnext', true ) ) :
							$if_prev = get_previous_post();
							$if_next = get_next_post();
							if ( $if_prev || $if_next ) :
								?>
								<nav class="if-prevnext">
									<div class="if-prevnext__prev">
										<?php if ( $if_prev ) : ?>
											<div class="if-prevnext__label"><?php esc_html_e( 'Previous', 'indfir' ); ?></div>
											<a href="<?php echo esc_url( get_permalink( $if_prev ) ); ?>"><?php echo esc_html( get_the_title( $if_prev ) ); ?></a>
										<?php endif; ?>
									</div>
									<div class="if-prevnext__next">
										<?php if ( $if_next ) : ?>
											<div class="if-prevnext__label"><?php esc_html_e( 'Next', 'indfir' ); ?></div>
											<a href="<?php echo esc_url( get_permalink( $if_next ) ); ?>"><?php echo esc_html( get_the_title( $if_next ) ); ?></a>
										<?php endif; ?>
									</div>
								</nav>
								<?php
							endif;
						endif;
						?>

						<?php indfir_related_posts(); ?>

						<?php
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
						?>
					</article>
				</div>
				<?php
			endwhile;
			?>
		</div>
	</div>
</main>

<?php
get_footer();

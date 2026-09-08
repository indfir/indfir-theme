<?php
/**
 * Static page.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="if-content" class="if-main">
	<div class="if-wrap">
		<div class="if-layout if-layout--full">
			<article <?php post_class( 'if-layout--narrow' ); ?>>
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<header class="if-entry__header">
						<?php
						if ( get_theme_mod( 'indfir_show_crumbs', true ) ) {
							indfir_breadcrumbs();
						}
						?>
						<h1 class="if-entry__title"><?php the_title(); ?></h1>
					</header>

					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="if-entry__thumb"><?php the_post_thumbnail( 'full' ); ?></figure>
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
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
				<?php endwhile; ?>
			</article>
		</div>
	</div>
</main>

<?php
get_footer();

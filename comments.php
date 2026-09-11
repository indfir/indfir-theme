<?php
/**
 * Comments.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="if-comments">
	<?php if ( have_comments() ) : ?>
		<?php
		indfir_section_head(
			sprintf(
				/* translators: %d: comment count. */
				_n( '%d Comment', '%d Comments', (int) get_comments_number(), 'indfir' ),
				(int) get_comments_number()
			),
			0,
			'if-head--plain'
		);
		?>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 46,
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
			)
		);
		?>

		<?php if ( ! comments_open() ) : ?>
			<p><?php esc_html_e( 'Comments are closed.', 'indfir' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'        => __( 'Leave a Comment', 'indfir' ),
			'title_reply_before' => '<h2 class="if-widget__title">',
			'title_reply_after'  => '</h2>',
			'class_submit'       => 'if-btn',
		)
	);
	?>
</div>

<?php
/**
 * Comments.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="legal-card glass st-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="st-comments-title">
			<?php
			printf(
				/* translators: %s: comment count. */
				esc_html( _n( '%s comment', '%s comments', get_comments_number(), 'safetunnels' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>

		<ol class="st-comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 40,
				)
			);
			?>
		</ol>

		<?php the_comments_pagination(); ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_submit'  => 'legal-btn',
			'title_reply'   => __( 'Leave a comment', 'safetunnels' ),
			'comment_notes_before' => '',
		)
	);
	?>
</section>

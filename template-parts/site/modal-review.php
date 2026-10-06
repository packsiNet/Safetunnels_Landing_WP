<?php
/**
 * "Add Comment" modal — the markup twin of ReviewModal in the React app.
 * All state transitions are handled in assets/js/app.js.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="modal-overlay" id="st-review-modal" role="dialog" aria-modal="true" aria-labelledby="st-review-title" hidden>
	<div class="modal-box" data-st-stop>
		<button type="button" class="modal-close" data-st-close aria-label="<?php esc_attr_e( 'Close', 'safetunnels' ); ?>">
			<?php safetunnels_icon( 'ti-x' ); ?>
		</button>

		<div data-st-review-form>
			<div class="modal-header">
				<div class="modal-icon"><?php safetunnels_icon( 'ti-message-star' ); ?></div>
				<h2 id="st-review-title"><?php esc_html_e( 'Add Comment', 'safetunnels' ); ?></h2>
				<p><?php esc_html_e( 'Share your experience with others', 'safetunnels' ); ?></p>
			</div>

			<form class="modal-form" id="st-review-form" dir="ltr" novalidate>
				<div class="mfield">
					<label for="st-review-name">
						<?php esc_html_e( 'Full Name', 'safetunnels' ); ?> <span class="req-star">*</span>
					</label>
					<input type="text" id="st-review-name" name="fullName" placeholder="<?php esc_attr_e( 'e.g: Ali', 'safetunnels' ); ?>" maxlength="150" required />
				</div>

				<div class="mfield">
					<label id="st-review-rating-label">
						<?php esc_html_e( 'Your Score', 'safetunnels' ); ?> <span class="req-star">*</span>
					</label>
					<div class="star-rating" id="st-review-rating" role="radiogroup" aria-labelledby="st-review-rating-label">
						<?php for ( $st_n = 1; $st_n <= 5; $st_n++ ) : ?>
							<button type="button" class="star-btn" data-value="<?php echo esc_attr( $st_n ); ?>" role="radio" aria-checked="false"
								aria-label="<?php echo esc_attr( sprintf( /* translators: %d: star count. */ _n( '%d Star', '%d Stars', $st_n, 'safetunnels' ), $st_n ) ); ?>">
								<?php safetunnels_icon( 'ti-star' ); ?>
							</button>
						<?php endfor; ?>
					</div>
					<span class="field-hint" data-st-rating-hint hidden><?php esc_html_e( 'Please select a rating.', 'safetunnels' ); ?></span>
				</div>

				<div class="mfield">
					<label for="st-review-comment">
						<?php esc_html_e( 'Your Comment', 'safetunnels' ); ?>
						<span class="opt-label"><?php esc_html_e( '(optional)', 'safetunnels' ); ?></span>
					</label>
					<textarea id="st-review-comment" name="comment" rows="4" maxlength="1000" placeholder="<?php esc_attr_e( 'Write your experience...', 'safetunnels' ); ?>"></textarea>
					<span class="char-count" data-st-char-count>0 / 1000</span>
				</div>

				<div class="modal-error" data-st-error hidden>
					<?php safetunnels_icon( 'ti-alert-circle' ); ?>
					<span data-st-error-text></span>
				</div>

				<button type="submit" class="modal-submit-btn" data-st-submit disabled>
					<span data-st-submit-idle><?php safetunnels_icon( 'ti-send' ); ?> <?php esc_html_e( 'Send Comment', 'safetunnels' ); ?></span>
					<span data-st-submit-busy hidden><?php safetunnels_icon( 'ti-loader-2', 'spin' ); ?> <?php esc_html_e( 'Sending...', 'safetunnels' ); ?></span>
				</button>
			</form>
		</div>

		<div class="modal-success" data-st-review-success hidden>
			<div class="modal-success-ico"><?php safetunnels_icon( 'ti-circle-check' ); ?></div>
			<h3><?php esc_html_e( 'Your comment has been submitted!', 'safetunnels' ); ?></h3>
			<p><?php esc_html_e( 'Your comment has been submitted successfully and will be displayed after approval by the administrator.', 'safetunnels' ); ?></p>
			<button type="button" class="modal-done-btn" data-st-close><?php esc_html_e( 'Close', 'safetunnels' ); ?></button>
		</div>
	</div>
</div>

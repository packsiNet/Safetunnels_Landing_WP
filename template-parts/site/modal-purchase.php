<?php
/**
 * Checkout modal — the markup twin of PurchaseModal in the React app.
 *
 * Flow, unchanged from the original: paste the account key from the app →
 * verify it against the backend (so a typo fails before money moves) → hand
 * the browser to the plan's RevenueCat hosted Purchase Link with the key as
 * the app_user_id path segment.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="modal-overlay" id="st-purchase-modal" role="dialog" aria-modal="true" aria-labelledby="st-purchase-title" hidden>
	<div class="modal-box" data-st-stop>
		<button type="button" class="modal-close" data-st-close aria-label="<?php esc_attr_e( 'Close', 'safetunnels' ); ?>">
			<?php safetunnels_icon( 'ti-x' ); ?>
		</button>

		<div class="modal-header">
			<div class="modal-icon"><?php safetunnels_icon( 'ti-crown' ); ?></div>
			<h2 id="st-purchase-title" data-st-plan-name></h2>
			<p data-st-plan-price></p>
		</div>

		<form class="modal-form" id="st-purchase-form" dir="ltr" data-st-step="enter-key" novalidate>
			<div class="mfield">
				<label for="st-purchase-key">
					<?php esc_html_e( 'Your Account Key', 'safetunnels' ); ?> <span class="req-star">*</span>
				</label>
				<input type="text" id="st-purchase-key" name="userId" maxlength="36" required
					autocomplete="off" spellcheck="false"
					placeholder="e.g: 8f14e45f-ceea-4d1b-9a1c-2c2f4a1b3c4d" />
				<span class="field-hint">
					<?php
					printf(
						/* translators: %s: app name. */
						esc_html__( 'Open the %s app → Profile → copy your account key, then paste it here.', 'safetunnels' ),
						esc_html( safetunnels_opt( 'app_name' ) )
					);
					?>
				</span>
			</div>

			<div class="modal-error" data-st-error hidden>
				<?php safetunnels_icon( 'ti-alert-circle' ); ?>
				<span data-st-error-text></span>
			</div>

			<button type="submit" class="modal-submit-btn" data-st-verify disabled>
				<span data-st-verify-idle><?php safetunnels_icon( 'ti-arrow-right' ); ?> <?php esc_html_e( 'Continue', 'safetunnels' ); ?></span>
				<span data-st-verify-busy hidden><?php safetunnels_icon( 'ti-loader-2', 'spin' ); ?> <?php esc_html_e( 'Checking...', 'safetunnels' ); ?></span>
			</button>
		</form>

		<div class="modal-form" data-st-confirm hidden>
			<div class="modal-key-confirm" data-st-confirm-text></div>

			<div class="modal-error" data-st-error hidden>
				<?php safetunnels_icon( 'ti-alert-circle' ); ?>
				<span data-st-error-text></span>
			</div>

			<button type="button" class="modal-submit-btn" data-st-pay>
				<span data-st-pay-idle><?php safetunnels_icon( 'ti-credit-card' ); ?> <?php esc_html_e( 'Confirm &amp; Pay', 'safetunnels' ); ?></span>
				<span data-st-pay-busy hidden><?php safetunnels_icon( 'ti-loader-2', 'spin' ); ?> <?php esc_html_e( 'Redirecting to checkout...', 'safetunnels' ); ?></span>
			</button>

			<button type="button" class="modal-link-btn" data-st-reset>
				<?php esc_html_e( 'Use a different key', 'safetunnels' ); ?>
			</button>

			<p class="modal-secure-note">
				<?php safetunnels_icon( 'ti-lock' ); ?>
				<?php esc_html_e( 'Card details are entered on the payment provider\'s secure page — this site never sees them.', 'safetunnels' ); ?>
			</p>
		</div>
	</div>
</div>

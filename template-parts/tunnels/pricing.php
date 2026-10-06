<?php
/**
 * Pricing.
 *
 * The Premium card renders the default period server-side and carries the
 * whole period table as data attributes, so switching the toggle costs one
 * DOM update and no request — and a visitor with JavaScript disabled still
 * sees a real price and a working checkout link.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_periods = safetunnels_premium_periods();
$st_default = safetunnels_default_period();
$st_active  = $st_periods[ $st_default ];
$st_noads   = safetunnels_noads_plan();
?>
<section id="pricing">
	<div class="inner">
		<?php
		safetunnels_section_head(
			__( 'Pricing', 'safetunnels' ),
			esc_html__( 'Simple &amp; Transparent', 'safetunnels' ),
			esc_html__( 'Start free, upgrade when you need more. Cancel anytime.', 'safetunnels' )
		);
		?>

		<div class="pricing-grid pricing-grid-2">
			<div class="plan">
				<h3 class="plan-name"><?php esc_html_e( 'Free', 'safetunnels' ); ?></h3>
				<div class="plan-price">$0<sub> <?php esc_html_e( '/ mo', 'safetunnels' ); ?></sub></div>
				<div class="plan-per"><?php esc_html_e( 'No credit card needed', 'safetunnels' ); ?></div>
				<div class="plan-save"><?php esc_html_e( 'Forever free, no time limit', 'safetunnels' ); ?></div>
				<div class="plan-line"></div>

				<div class="plan-feats">
					<?php foreach ( safetunnels_free_features() as $st_feature ) : ?>
						<div class="pf<?php echo $st_feature[1] ? '' : ' pf-off'; ?>">
							<?php safetunnels_icon( $st_feature[1] ? 'ti-check' : 'ti-x' ); ?>
							<?php echo esc_html( $st_feature[0] ); ?>
							<?php if ( __( 'Includes ads', 'safetunnels' ) === $st_feature[0] ) : ?>
								<span class="pf-hint"><?php esc_html_e( '(remove below)', 'safetunnels' ); ?></span>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>

				<a class="plan-btn" href="<?php echo esc_url( safetunnels_apk_url() ); ?>" download
					<?php echo safetunnels_track_attrs( safetunnels_opt( 'apk_package' ), 'Tunnel Network APK' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<?php esc_html_e( 'Download Free', 'safetunnels' ); ?>
				</a>

				<button type="button" class="plan-lifetime" data-st-buy="noads">
					<?php safetunnels_icon( 'ti-infinity' ); ?>
					<span>
						<?php
						printf(
							/* translators: %s: price. */
							esc_html__( 'Go ad-free for life — %s one-time', 'safetunnels' ),
							'<b>' . esc_html( $st_noads['price'] ) . '</b>'
						);
						?>
					</span>
				</button>
			</div>

			<div class="plan featured" id="st-premium-plan" data-default-period="<?php echo esc_attr( $st_default ); ?>">
				<div class="plan-badge"><?php esc_html_e( 'Most Popular', 'safetunnels' ); ?></div>
				<h3 class="plan-name"><?php esc_html_e( 'Premium', 'safetunnels' ); ?></h3>

				<div class="plan-toggle" role="tablist" aria-label="<?php esc_attr_e( 'Premium billing period', 'safetunnels' ); ?>">
					<?php foreach ( $st_periods as $st_key => $st_period ) : ?>
						<button
							type="button"
							role="tab"
							data-st-period="<?php echo esc_attr( $st_key ); ?>"
							aria-selected="<?php echo $st_key === $st_default ? 'true' : 'false'; ?>"
							<?php echo $st_period['disabled'] ? ' disabled title="' . esc_attr__( 'Coming soon', 'safetunnels' ) . '"' : ''; ?>
							class="plan-toggle-btn<?php echo $st_key === $st_default ? ' active' : ''; ?><?php echo $st_period['disabled'] ? ' disabled' : ''; ?>"
						>
							<?php echo esc_html( $st_period['label'] ); ?>
							<?php if ( $st_period['disabled'] ) : ?>
								<span class="plan-toggle-soon"><?php esc_html_e( 'Soon', 'safetunnels' ); ?></span>
							<?php endif; ?>
						</button>
					<?php endforeach; ?>
				</div>

				<div class="plan-price">
					<span data-st-price><?php echo esc_html( $st_active['price'] ); ?></span><sub> <span data-st-per><?php echo esc_html( $st_active['per'] ); ?></span></sub>
				</div>

				<div class="plan-per">
					<span data-st-cadence><?php echo esc_html( $st_active['cadence'] ); ?></span>
					<span class="plan-per-eq" data-st-weekly<?php echo $st_active['weekly_eq'] ? '' : ' hidden'; ?>>
						<?php echo $st_active['weekly_eq'] ? ' · ' . esc_html( $st_active['weekly_eq'] ) : ''; ?>
					</span>
				</div>

				<div class="plan-save">
					<?php if ( $st_active['badge'] ) : ?>
						<span class="plan-save-badge" data-st-badge><?php echo esc_html( $st_active['badge'] ); ?></span>
					<?php else : ?>
						<span class="plan-save-badge" data-st-badge hidden></span>
						<span data-st-badge-fallback><?php esc_html_e( 'Full access, no commitment', 'safetunnels' ); ?></span>
					<?php endif; ?>
				</div>

				<div class="plan-line"></div>

				<div class="plan-feats">
					<?php foreach ( safetunnels_premium_features() as $st_feature ) : ?>
						<div class="pf">
							<?php safetunnels_icon( 'ti-check' ); ?> <?php echo wp_kses_post( $st_feature ); ?>
						</div>
					<?php endforeach; ?>
				</div>

				<p class="plan-feats-note">
					<?php esc_html_e( 'Every Premium plan unlocks the same full feature set — only the price and commitment change.', 'safetunnels' ); ?>
				</p>

				<button type="button" class="plan-btn" data-st-buy="premium" data-st-buy-period="<?php echo esc_attr( $st_default ); ?>">
					<?php esc_html_e( 'Get Premium —', 'safetunnels' ); ?> <span data-st-cta-price><?php echo esc_html( $st_active['price'] ); ?></span>
				</button>
			</div>
		</div>

		<p class="pricing-note">
			<?php esc_html_e( 'Secure card checkout on the web, or billing through Google Play. Cancel anytime, no questions asked.', 'safetunnels' ); ?>
		</p>
	</div>
</section>

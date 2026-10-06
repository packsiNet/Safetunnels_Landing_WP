<?php
/**
 * Template Name: SafeTunnels — Refund Policy
 *
 * A refund policy is not optional decoration: card processors and app stores
 * both expect a public, findable one, and its absence is a common reason for
 * a payment account review.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$app   = safetunnels_opt( 'app_name' );
$email = safetunnels_opt( 'support_email' );

$sections = array(
	'r1' => __( 'Scope', 'safetunnels' ),
	'r2' => __( 'Web card payments', 'safetunnels' ),
	'r3' => __( 'Google Play purchases', 'safetunnels' ),
	'r4' => __( 'Wrong account key', 'safetunnels' ),
	'r5' => __( 'What is not refundable', 'safetunnels' ),
	'r6' => __( 'How to request a refund', 'safetunnels' ),
	'r7' => __( 'Processing times', 'safetunnels' ),
);

get_header();
?>

<main id="content">
	<?php
	get_template_part(
		'template-parts/legal/hero',
		null,
		array(
			'eyebrow'  => __( 'Legal', 'safetunnels' ),
			'title'    => __( 'Refund Policy', 'safetunnels' ),
			'subtitle' => __( 'When a subscription can be refunded, how to ask, and how long the money takes to come back.', 'safetunnels' ),
		)
	);
	?>

	<div class="inner lg-content">
		<?php get_template_part( 'template-parts/legal/meta-bar', null, array( 'updated' => safetunnels_opt( 'legal_updated' ) ) ); ?>

		<div class="legal-layout">
			<?php
			get_template_part(
				'template-parts/legal/toc',
				null,
				array(
					'sections' => $sections,
					'label'    => __( 'Refund Policy sections', 'safetunnels' ),
				)
			);
			?>

			<div class="legal-sections">
				<section id="r1" class="legal-card glass">
					<h2><span class="legal-num">1</span> <?php esc_html_e( 'Scope', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( 'This policy covers every paid %s purchase: Premium subscriptions on any billing period, and the one-time lifetime ad removal. Which rules apply depends on where you paid.', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
				</section>

				<section id="r2" class="legal-card glass">
					<h2><span class="legal-num">2</span> <?php esc_html_e( 'Web card payments', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'If you paid by card on this website, you can request a full refund within 14 days of the charge, provided the plan has seen only incidental use. We do not require you to explain why.', 'safetunnels' ); ?></p>
					<p><?php esc_html_e( 'After 14 days, a subscription can still be cancelled at any time — it then simply stops renewing, and access continues until the end of the period you already paid for.', 'safetunnels' ); ?></p>
				</section>

				<section id="r3" class="legal-card glass">
					<h2><span class="legal-num">3</span> <?php esc_html_e( 'Google Play purchases', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'Purchases made through Google Play are billed by Google, so refunds follow Google Play\'s own policy and are requested through your Google Play order history. We cannot issue a refund for a Play purchase directly, but we will help you through the process and confirm the details Google asks for.', 'safetunnels' ); ?></p>
				</section>

				<section id="r4" class="legal-card glass">
					<h2><span class="legal-num">4</span> <?php esc_html_e( 'Wrong account key', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'Web checkout links your purchase to the account key you paste before paying, which is why we verify that key against our records first. If a payment still ended up on the wrong installation, contact us with the payment reference and the correct key — we move the entitlement rather than making you pay twice.', 'safetunnels' ); ?></p>
				</section>

				<section id="r5" class="legal-card glass">
					<h2><span class="legal-num">5</span> <?php esc_html_e( 'What is not refundable', 'safetunnels' ); ?></h2>
					<ul>
						<li class="no"><?php safetunnels_icon( 'ti-x' ); ?> <?php esc_html_e( 'Billing periods that have already been fully used', 'safetunnels' ); ?></li>
						<li class="no"><?php safetunnels_icon( 'ti-x' ); ?> <?php esc_html_e( 'Purchases where the service worked as described and the request falls outside the 14-day window', 'safetunnels' ); ?></li>
						<li class="no"><?php safetunnels_icon( 'ti-x' ); ?> <?php esc_html_e( 'Accounts suspended for violating the Terms of Service', 'safetunnels' ); ?></li>
					</ul>
					<p><?php esc_html_e( 'Statutory consumer rights in your country always take precedence over this section.', 'safetunnels' ); ?></p>
				</section>

				<section id="r6" class="legal-card glass">
					<h2><span class="legal-num">6</span> <?php esc_html_e( 'How to request a refund', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'Email us with the payment reference from your receipt, the account key the purchase was made for, and a one-line description of the problem. That is everything we need — there is no form to fill in.', 'safetunnels' ); ?></p>
					<div class="legal-cta-row">
						<a class="legal-btn" href="<?php echo esc_url( safetunnels_mailto( 'Refund Request' ) ); ?>">
							<?php safetunnels_icon( 'ti-receipt-refund' ); ?> <?php esc_html_e( 'Request a refund', 'safetunnels' ); ?>
						</a>
						<a class="legal-btn out" href="<?php echo esc_url( safetunnels_page_url( 'support' ) ); ?>">
							<?php safetunnels_icon( 'ti-headset' ); ?> <?php esc_html_e( 'Other support topics', 'safetunnels' ); ?>
						</a>
					</div>
				</section>

				<section id="r7" class="legal-card glass">
					<h2><span class="legal-num">7</span> <?php esc_html_e( 'Processing times', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: support email link. */
							esc_html__( 'We answer refund requests within 48 hours. Approved card refunds are issued immediately on our side and typically appear on your statement within 5–10 business days, depending on your bank. Questions about a specific request go to %s.', 'safetunnels' ),
							'<a class="inline-link" href="' . esc_url( safetunnels_mailto( 'Refund Question' ) ) . '">' . esc_html( $email ) . '</a>'
						);
						?>
					</p>
				</section>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();

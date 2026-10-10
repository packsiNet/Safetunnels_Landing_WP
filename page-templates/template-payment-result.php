<?php
/**
 * Template Name: SafeTunnels — Payment Result
 *
 * The landing page providers redirect to after checkout. Payment providers do
 * not agree on a parameter name or a vocabulary for the outcome, so this
 * checks the common ones (RevenueCat, Stripe-style `redirect_status`, plain
 * `success=true`) and falls back to success — the page is normally only
 * reached through a provider's *success* redirect, since a cancellation
 * usually never leaves the checkout at all.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only redirect target.
$raw = '';
foreach ( array( 'status', 'redirect_status', 'result', 'success' ) as $param ) {
	if ( isset( $_GET[ $param ] ) ) {
		$raw = strtolower( sanitize_text_field( wp_unslash( $_GET[ $param ] ) ) );
		break;
	}
}

$reference = '';
foreach ( array( 'transaction_id', 'order_id', 'app_user_id' ) as $param ) {
	if ( isset( $_GET[ $param ] ) ) {
		$reference = sanitize_text_field( wp_unslash( $_GET[ $param ] ) );
		break;
	}
}
// phpcs:enable WordPress.Security.NonceVerification.Recommended

$status = 'success';

if ( in_array( $raw, array( 'cancelled', 'canceled', 'cancel' ), true ) ) {
	$status = 'cancelled';
} elseif ( in_array( $raw, array( 'failed', 'failure', 'error', 'declined', 'false' ), true ) ) {
	$status = 'failed';
} elseif ( in_array( $raw, array( 'pending', 'processing' ), true ) ) {
	$status = 'pending';
}

$content = array(
	'success'   => array(
		'icon'  => 'ti-circle-check',
		'title' => __( 'Payment Successful!', 'safetunnels' ),
		'desc'  => __( 'Your SafeTunnels plan is now active. Open the app and refresh (or restart it) to see it applied — this can take a minute to sync.', 'safetunnels' ),
		'retry' => false,
	),
	'pending'   => array(
		'icon'  => 'ti-clock',
		'title' => __( 'Payment Processing', 'safetunnels' ),
		'desc'  => __( 'We\'re confirming your payment with our provider — this usually only takes a minute. Check the app shortly, or refresh this page.', 'safetunnels' ),
		'retry' => false,
	),
	'cancelled' => array(
		'icon'  => 'ti-circle-x',
		'title' => __( 'Checkout Cancelled', 'safetunnels' ),
		'desc'  => __( 'No charge was made. You can pick up right where you left off whenever you\'re ready.', 'safetunnels' ),
		'retry' => true,
	),
	'failed'    => array(
		'icon'  => 'ti-alert-triangle',
		'title' => __( 'Payment Failed', 'safetunnels' ),
		'desc'  => __( 'Something went wrong processing your payment and no charge was made. Please try again, or reach out if the problem continues.', 'safetunnels' ),
		'retry' => true,
	),
);

$result = $content[ $status ];

get_header();
?>

<main id="content">
	<?php
	get_template_part(
		'template-parts/legal/hero',
		null,
		array(
			'eyebrow'  => __( 'Checkout', 'safetunnels' ),
			'title'    => $result['title'],
			'subtitle' => $result['desc'],
		)
	);
	?>

	<div class="inner lg-content">
		<div class="pr-wrap glass">
			<div class="pr-icon pr-icon-<?php echo esc_attr( $status ); ?>">
				<?php safetunnels_icon( $result['icon'] ); ?>
			</div>

			<?php if ( $reference ) : ?>
				<div class="pr-ref">
					<?php esc_html_e( 'Reference:', 'safetunnels' ); ?> <span><?php echo esc_html( $reference ); ?></span>
				</div>
			<?php endif; ?>

			<div class="pr-actions">
				<a class="legal-btn" href="<?php echo esc_url( safetunnels_page_url( 'home' ) ); ?>">
					<?php safetunnels_icon( 'ti-home' ); ?>
					<?php
					printf(
						/* translators: %s: app name. */
						esc_html__( 'Back to %s', 'safetunnels' ),
						esc_html( safetunnels_opt( 'app_name' ) )
					);
					?>
				</a>

				<?php if ( $result['retry'] ) : ?>
					<a class="legal-btn out" href="<?php echo esc_url( safetunnels_page_url( 'pricing' ) . '#pricing' ); ?>">
						<?php safetunnels_icon( 'ti-refresh' ); ?> <?php esc_html_e( 'Try Again', 'safetunnels' ); ?>
					</a>
				<?php endif; ?>

				<a class="legal-btn out" href="<?php echo esc_url( safetunnels_page_url( 'support' ) ); ?>">
					<?php safetunnels_icon( 'ti-headset' ); ?> <?php esc_html_e( 'Get Support', 'safetunnels' ); ?>
				</a>
			</div>

			<?php if ( 'success' === $status ) : ?>
				<div class="pr-next">
					<h2><?php esc_html_e( 'What happens now', 'safetunnels' ); ?></h2>
					<ol>
						<li><?php esc_html_e( 'Open the SafeTunnels app on your phone.', 'safetunnels' ); ?></li>
						<li><?php esc_html_e( 'Pull to refresh the Profile screen, or restart the app.', 'safetunnels' ); ?></li>
						<li><?php esc_html_e( 'Your new plan appears — reconnect once and everything is unlocked.', 'safetunnels' ); ?></li>
					</ol>
					<p>
						<?php
						printf(
							/* translators: %s: support link. */
							esc_html__( 'Still on the old plan after a few minutes? %s and we will sort it out.', 'safetunnels' ),
							'<a class="inline-link" href="' . esc_url( safetunnels_mailto( 'Purchase not applied' ) ) . '">' . esc_html__( 'Email us the reference above', 'safetunnels' ) . '</a>'
						);
						?>
					</p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php
get_footer();

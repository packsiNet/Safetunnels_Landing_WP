<?php
/**
 * Template Name: SafeTunnels — Pricing
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_periods = safetunnels_premium_periods();
$st_noads   = safetunnels_noads_plan();

$st_compare = array(
	array( __( 'Banking &amp; public-service apps', 'safetunnels' ), true, true ),
	array( __( 'Unlimited bandwidth', 'safetunnels' ), true, true ),
	array( __( 'Specialized apps', 'safetunnels' ), false, true ),
	array( __( 'Streaming &amp; VOD apps', 'safetunnels' ), false, true ),
	array( __( 'General browser traffic', 'safetunnels' ), false, true ),
	array( __( 'Priority relay nodes', 'safetunnels' ), false, true ),
	array( __( 'Ad-free experience', 'safetunnels' ), false, true ),
	array( __( 'Credit card required', 'safetunnels' ), false, true ),
);

get_header();
?>

<main id="content">
	<?php
	get_template_part(
		'template-parts/site/page-hero',
		null,
		array(
			'eyebrow'  => __( 'Pricing', 'safetunnels' ),
			'title'    => __( 'Plans &amp; Pricing', 'safetunnels' ),
			'subtitle' => __( 'A free plan that never expires, Premium from under a dollar a week, and a one-time unlock if all you want is the ads gone.', 'safetunnels' ),
		)
	);

	get_template_part( 'template-parts/tunnels/pricing' );
	?>

	<section id="compare">
		<div class="inner">
			<?php
			safetunnels_section_head(
				__( 'Comparison', 'safetunnels' ),
				esc_html__( 'Free vs Premium', 'safetunnels' ),
				esc_html__( 'Everything that changes when you upgrade — nothing hidden in a footnote.', 'safetunnels' )
			);
			?>

			<div class="st-table-wrap glass">
				<table class="st-compare">
					<caption class="screen-reader-text"><?php esc_html_e( 'Feature comparison between the Free and Premium plans', 'safetunnels' ); ?></caption>
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Feature', 'safetunnels' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Free', 'safetunnels' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Premium', 'safetunnels' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $st_compare as $st_row ) : ?>
							<tr>
								<th scope="row"><?php echo wp_kses_post( $st_row[0] ); ?></th>
								<td>
									<?php if ( $st_row[1] ) : ?>
										<?php safetunnels_icon( 'ti-check', 'yes' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Included', 'safetunnels' ); ?></span>
									<?php else : ?>
										<?php safetunnels_icon( 'ti-x', 'no' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Not included', 'safetunnels' ); ?></span>
									<?php endif; ?>
								</td>
								<td>
									<?php if ( $st_row[2] ) : ?>
										<?php safetunnels_icon( 'ti-check', 'yes' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Included', 'safetunnels' ); ?></span>
									<?php else : ?>
										<?php safetunnels_icon( 'ti-x', 'no' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Not included', 'safetunnels' ); ?></span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<section id="all-plans">
		<div class="inner">
			<?php
			safetunnels_section_head(
				__( 'Every option', 'safetunnels' ),
				esc_html__( 'Pick the commitment that suits you', 'safetunnels' ),
				esc_html__( 'Identical features across every Premium period — longer commitments simply cost less per week.', 'safetunnels' )
			);
			?>

			<div class="st-plan-list">
				<?php foreach ( $st_periods as $st_key => $st_period ) : ?>
					<div class="st-plan-row glass<?php echo $st_period['disabled'] ? ' is-soon' : ''; ?>">
						<div class="st-plan-row-label">
							<strong><?php echo esc_html( $st_period['label'] ); ?></strong>
							<span><?php echo esc_html( $st_period['cadence'] ); ?></span>
						</div>
						<div class="st-plan-row-price">
							<?php echo esc_html( $st_period['price'] ); ?>
							<sub><?php echo esc_html( $st_period['per'] ); ?></sub>
							<?php if ( $st_period['weekly_eq'] ) : ?>
								<em><?php echo esc_html( $st_period['weekly_eq'] ); ?></em>
							<?php endif; ?>
						</div>
						<div class="st-plan-row-action">
							<?php if ( $st_period['disabled'] ) : ?>
								<span class="dtab-badge"><?php esc_html_e( 'Soon', 'safetunnels' ); ?></span>
							<?php else : ?>
								<button type="button" class="plan-btn" data-st-buy="premium" data-st-buy-period="<?php echo esc_attr( $st_key ); ?>">
									<?php esc_html_e( 'Buy', 'safetunnels' ); ?> <?php echo esc_html( $st_period['price'] ); ?>
								</button>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>

				<div class="st-plan-row glass">
					<div class="st-plan-row-label">
						<strong><?php esc_html_e( 'No Ads — Lifetime', 'safetunnels' ); ?></strong>
						<span><?php esc_html_e( 'One-time payment, stays on the Free plan', 'safetunnels' ); ?></span>
					</div>
					<div class="st-plan-row-price"><?php echo esc_html( $st_noads['price'] ); ?><sub> <?php esc_html_e( 'once', 'safetunnels' ); ?></sub></div>
					<div class="st-plan-row-action">
						<button type="button" class="plan-btn" data-st-buy="noads">
							<?php esc_html_e( 'Remove ads', 'safetunnels' ); ?>
						</button>
					</div>
				</div>
			</div>

			<div class="st-checkout-help glass">
				<h2><?php esc_html_e( 'How checkout works', 'safetunnels' ); ?></h2>
				<ol>
					<li><?php esc_html_e( 'Open the app → Profile → copy your account key.', 'safetunnels' ); ?></li>
					<li><?php esc_html_e( 'Choose a plan above and paste the key when asked — we check it against your account before any payment starts.', 'safetunnels' ); ?></li>
					<li><?php esc_html_e( 'Pay on the provider\'s secure page with any international card.', 'safetunnels' ); ?></li>
					<li><?php esc_html_e( 'Reopen the app — Premium activates within a minute.', 'safetunnels' ); ?></li>
				</ol>
				<p>
					<?php
					printf(
						/* translators: %s: refund policy link. */
						esc_html__( 'Charged in error? See our %s.', 'safetunnels' ),
						'<a class="inline-link" href="' . esc_url( safetunnels_page_url( 'refund' ) ) . '">' . esc_html__( 'refund policy', 'safetunnels' ) . '</a>'
					);
					?>
				</p>
			</div>
		</div>
	</section>

	<?php
	$st_billing = array();
	foreach ( safetunnels_faq_categories() as $st_category ) {
		if ( 'billing' === $st_category['key'] ) {
			$st_billing = $st_category['items'];
		}
	}

	get_template_part( 'template-parts/tunnels/faq', null, array( 'items' => $st_billing ) );
	get_template_part( 'template-parts/tunnels/cta' );
	?>
</main>

<?php
get_footer();

<?php
/**
 * Template Name: SafeTunnels — Cookie Policy
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$storage = array(
	array(
		'name'    => 'st_visit',
		'type'    => __( 'Session storage', 'safetunnels' ),
		'purpose' => __( 'Marks that this browsing session has already been counted, so one visit is never counted twice.', 'safetunnels' ),
		'life'    => __( 'Until the tab closes', 'safetunnels' ),
	),
	array(
		'name'    => 'wordpress_*',
		'type'    => __( 'Cookie', 'safetunnels' ),
		'purpose' => __( 'Set by WordPress only for signed-in administrators. Visitors never receive it.', 'safetunnels' ),
		'life'    => __( 'Session or 14 days', 'safetunnels' ),
	),
	array(
		'name'    => 'wp-settings-*',
		'type'    => __( 'Cookie', 'safetunnels' ),
		'purpose' => __( 'Remembers admin screen preferences. Signed-in administrators only.', 'safetunnels' ),
		'life'    => __( '1 year', 'safetunnels' ),
	),
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
			'title'    => __( 'Cookie Policy', 'safetunnels' ),
			'subtitle' => __( 'This site sets no advertising or tracking cookies. Here is the complete list of what it does store in your browser.', 'safetunnels' ),
		)
	);
	?>

	<div class="inner lg-content">
		<?php get_template_part( 'template-parts/legal/meta-bar', null, array( 'updated' => safetunnels_opt( 'legal_updated' ) ) ); ?>

		<div class="legal-sections">
			<section class="legal-card glass">
				<h2><?php esc_html_e( 'The short version', 'safetunnels' ); ?></h2>
				<div class="legal-callout">
					<?php safetunnels_icon( 'ti-cookie-off' ); ?>
					<p><?php esc_html_e( 'No advertising cookies, no third-party analytics, no cross-site tracking, and no consent banner — because there is nothing to consent to.', 'safetunnels' ); ?></p>
				</div>
				<p><?php esc_html_e( 'The website counts an anonymous visit once per browsing session and records which download link was clicked. Neither is tied to an identity, and neither uses a cookie that persists after you close the tab.', 'safetunnels' ); ?></p>
			</section>

			<section class="legal-card glass">
				<h2><?php esc_html_e( 'What is stored', 'safetunnels' ); ?></h2>
				<div class="st-table-wrap">
					<table class="st-compare">
						<thead>
							<tr>
								<th scope="col"><?php esc_html_e( 'Name', 'safetunnels' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Type', 'safetunnels' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Purpose', 'safetunnels' ); ?></th>
								<th scope="col"><?php esc_html_e( 'Lifetime', 'safetunnels' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $storage as $row ) : ?>
								<tr>
									<th scope="row"><code><?php echo esc_html( $row['name'] ); ?></code></th>
									<td><?php echo esc_html( $row['type'] ); ?></td>
									<td class="st-left"><?php echo esc_html( $row['purpose'] ); ?></td>
									<td><?php echo esc_html( $row['life'] ); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</section>

			<section class="legal-card glass">
				<h2><?php esc_html_e( 'Third parties', 'safetunnels' ); ?></h2>
				<p><?php esc_html_e( 'Choosing a paid plan sends you to our payment provider\'s own checkout page, which sets the cookies it needs to process the payment securely. That happens on their domain, under their privacy policy, and only after you decide to pay.', 'safetunnels' ); ?></p>
				<p><?php esc_html_e( 'The icon webfont used by this site is served from a public CDN, which receives the request as an ordinary file download and sets no cookies.', 'safetunnels' ); ?></p>
			</section>

			<section class="legal-card glass">
				<h2><?php esc_html_e( 'Clearing what is stored', 'safetunnels' ); ?></h2>
				<p><?php esc_html_e( 'Closing the tab clears the session entry. Clearing site data for this domain in your browser settings removes everything listed above, and nothing about the site stops working when you do.', 'safetunnels' ); ?></p>
				<p>
					<?php
					printf(
						/* translators: %s: privacy policy link. */
						esc_html__( 'For what the Android app itself handles, see the %s.', 'safetunnels' ),
						'<a class="inline-link" href="' . esc_url( safetunnels_page_url( 'privacy' ) ) . '">' . esc_html__( 'Privacy Policy', 'safetunnels' ) . '</a>'
					);
					?>
				</p>
			</section>
		</div>
	</div>
</main>

<?php
get_footer();

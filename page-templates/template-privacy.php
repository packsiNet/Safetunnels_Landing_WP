<?php
/**
 * Template Name: SafeTunnels — Privacy Policy
 *
 * Matches /tunnels/privacy version 1.1, updated 2026-10-07.
 * Fragment IDs follow the reference and retain existing support links.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$app       = safetunnels_opt( 'app_name' );
$developer = safetunnels_opt( 'developer_name' );
$email     = safetunnels_opt( 'support_email' );
$urls      = safetunnels_external_urls();

$sections = array(
	's1'  => __( 'Overview', 'safetunnels' ),
	's2'  => __( 'Information we handle', 'safetunnels' ),
	's3'  => __( 'How we use information', 'safetunnels' ),
	's4'  => __( 'Connection and traffic', 'safetunnels' ),
	's5'  => __( 'Ads and service providers', 'safetunnels' ),
	's6'  => __( 'Sharing and security', 'safetunnels' ),
	's7'  => __( 'Retention', 'safetunnels' ),
	's8'  => __( 'Children', 'safetunnels' ),
	's13' => __( 'Your choices and deletion', 'safetunnels' ),
	's14' => __( 'Changes', 'safetunnels' ),
	's15' => __( 'Contact', 'safetunnels' ),
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
			'title'    => __( 'Privacy Policy', 'safetunnels' ),
			'subtitle' => sprintf(
				/* translators: %s: app name. */
				__( 'How %s handles information needed to provide and support its connection service.', 'safetunnels' ),
				$app
			),
		)
	);
	?>

	<div class="inner lg-content">
		<?php
		get_template_part(
			'template-parts/legal/meta-bar',
			null,
			array(
				'version'        => safetunnels_opt( 'privacy_version' ),
				'updated'        => safetunnels_opt( 'privacy_updated' ),
				'show_effective' => false,
				'format_date'    => false,
			)
		);
		?>

		<div class="legal-layout">
			<?php
			get_template_part(
				'template-parts/legal/toc',
				null,
				array(
					'sections' => $sections,
					'label'    => __( 'Privacy Policy sections', 'safetunnels' ),
				)
			);
			?>

			<div class="legal-sections">
				<section id="s1" class="legal-card glass">
					<h2><span class="legal-num">1</span> <?php esc_html_e( 'Overview', 'safetunnels' ); ?></h2>
					<p><?php echo esc_html( $app ); ?><?php esc_html_e( ' is an Android app provided by ', 'safetunnels' ); ?><?php echo esc_html( $developer ); ?><?php esc_html_e( '. It creates an encrypted connection for supported apps and services. This policy explains information involved in operating the app, providing subscriptions, showing ads, and responding to support requests.', 'safetunnels' ); ?></p>
				</section>

				<section id="s2" class="legal-card glass">
					<h2><span class="legal-num">2</span> <?php esc_html_e( 'Information we handle', 'safetunnels' ); ?></h2>
					<ul>
						<li><strong><?php esc_html_e( 'Installation and account identifiers:', 'safetunnels' ); ?></strong><?php esc_html_e( ' a generated identifier and an internal account address used to recognize an installation and manage access. The internal address is not a mailbox for contacting you.', 'safetunnels' ); ?></li>
						<li><strong><?php esc_html_e( 'Plan and purchase information:', 'safetunnels' ); ?></strong><?php esc_html_e( ' subscription status and product or transaction information needed to grant paid features and resolve billing issues. Google Play handles payment card details.', 'safetunnels' ); ?></li>
						<li><strong><?php esc_html_e( 'Connection and usage information:', 'safetunnels' ); ?></strong><?php esc_html_e( ' connection configuration, server assignment, connection status, and bandwidth totals needed to operate the service and apply plan limits.', 'safetunnels' ); ?></li>
						<li><strong><?php esc_html_e( 'Preferences and support:', 'safetunnels' ); ?></strong><?php esc_html_e( ' settings you choose and information you send us, such as an email address and support message.', 'safetunnels' ); ?></li>
					</ul>
					<p><?php esc_html_e( 'We do not ask for your name, phone number, contacts, photos, messages, or precise location to use the connection service. Advertising providers may process other device information as explained below.', 'safetunnels' ); ?></p>
				</section>

				<section id="s3" class="legal-card glass">
					<h2><span class="legal-num">3</span> <?php esc_html_e( 'How we use information', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'We use this information to establish and maintain connections, apply plan limits, display usage, manage subscriptions, prevent misuse, troubleshoot problems, and respond to requests. We do not use connection traffic to target advertisements.', 'safetunnels' ); ?></p>
				</section>

				<section id="s4" class="legal-card glass">
					<h2><span class="legal-num">4</span> <?php esc_html_e( 'Connection and traffic', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'To provide an encrypted connection, ', 'safetunnels' ); ?><?php echo esc_html( $app ); ?><?php esc_html_e( ' uses Android\'s VpnService. When you enable the service and grant Android\'s connection permission, network data from the apps covered by your selected mode passes through an encrypted tunnel to the service endpoint. This access is used to route traffic for the connection feature. The app does not redirect traffic from other apps to alter advertising or generate advertising revenue.', 'safetunnels' ); ?></p>
					<p><?php esc_html_e( 'We record bandwidth totals for usage and plan management. We do not use those totals as a browsing history, and we do not retain the contents of traffic passing through the tunnel. Destination services may still receive information as part of a normal connection.', 'safetunnels' ); ?></p>
				</section>

				<section id="s5" class="legal-card glass">
					<h2><span class="legal-num">5</span> <?php esc_html_e( 'Ads and service providers', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'The free plan uses Google AdMob to display ads. Its SDK may collect or share device identifiers, IP address, approximate location inferred from that address, app interactions, and diagnostic data for advertising, measurement, and fraud prevention. See Google\'s', 'safetunnels' ); ?><?php esc_html_e( ' ', 'safetunnels' ); ?><a class="inline-link" href="<?php echo esc_url( $urls['google_privacy'] ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Privacy Policy', 'safetunnels' ); ?></a><?php esc_html_e( '. An ad-free entitlement removes ads within the app.', 'safetunnels' ); ?></p>
					<p><?php esc_html_e( 'Google Play handles in-app payments. RevenueCat helps process purchase events and subscription entitlements using an account identifier and purchase information. Infrastructure providers process data needed to host the connection and account systems.', 'safetunnels' ); ?></p>
				</section>

				<section id="s6" class="legal-card glass">
					<h2><span class="legal-num">6</span> <?php esc_html_e( 'Sharing and security', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'We share information with the providers described above when needed for ads, payments, subscriptions, hosting, and operation of the service, or when required by law. We do not sell user information. Data traveling between your device and the connection endpoint is encrypted. We use access controls and other reasonable safeguards for stored information, although no system can guarantee absolute security.', 'safetunnels' ); ?></p>
				</section>

				<section id="s7" class="legal-card glass">
					<h2><span class="legal-num">7</span> <?php esc_html_e( 'Retention', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'We keep account, subscription, connection, and usage information while needed to provide the service. After a deletion request, we remove associated data, subject to records we must keep for legal, payment, security, or fraud prevention purposes. Third-party providers may have separate retention practices.', 'safetunnels' ); ?></p>
				</section>

				<section id="s8" class="legal-card glass">
					<h2><span class="legal-num">8</span> <?php esc_html_e( 'Children', 'safetunnels' ); ?></h2>
					<p><?php echo esc_html( $app ); ?><?php esc_html_e( ' is not intended for children under 13. If you believe a child has provided information to us, contact us so we can review and remove it as appropriate.', 'safetunnels' ); ?></p>
				</section>

				<section id="s13" class="legal-card glass">
					<h2><span class="legal-num">9</span> <?php esc_html_e( 'Your choices and deletion', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'You can stop the connection in the app or revoke its permission in Android settings. You can request deletion of your account and associated data from the Profile section of the app or by emailing us below. Please include enough information to identify the account; we may need to verify the request. Deleting the account does not cancel a Google Play subscription. Manage or cancel it in Google Play.', 'safetunnels' ); ?></p>
					<div class="legal-cta-row">
						<a class="legal-btn" href="<?php echo esc_url( safetunnels_mailto( 'Account Deletion Request' ) ); ?>"><?php safetunnels_icon( 'ti-trash' ); ?><?php esc_html_e( ' Request Account Deletion', 'safetunnels' ); ?></a>
						<a class="legal-btn out" href="<?php echo esc_url( safetunnels_mailto( 'Privacy Question' ) ); ?>"><?php safetunnels_icon( 'ti-shield-question' ); ?><?php esc_html_e( ' Ask a Privacy Question', 'safetunnels' ); ?></a>
					</div>
				</section>

				<section id="s14" class="legal-card glass">
					<h2><span class="legal-num">10</span> <?php esc_html_e( 'Changes', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'We may update this policy when the app or our data practices change. The date at the top of this page shows the latest update. Material changes will be communicated through the app or this page as appropriate.', 'safetunnels' ); ?></p>
				</section>

				<section id="s15" class="legal-card glass">
					<h2><span class="legal-num">11</span> <?php esc_html_e( 'Contact', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'For privacy questions or deletion requests, contact ', 'safetunnels' ); ?><?php echo esc_html( $developer ); ?><?php esc_html_e( ' at', 'safetunnels' ); ?><?php esc_html_e( ' ', 'safetunnels' ); ?><a class="inline-link" href="<?php echo esc_url( safetunnels_mailto( 'Privacy Policy Question' ) ); ?>"><?php echo esc_html( $email ); ?></a><?php esc_html_e( '.', 'safetunnels' ); ?></p>
				</section>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();

<?php
/**
 * Template Name: SafeTunnels — Privacy Policy
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
	's2'  => __( 'No Account Registration Required', 'safetunnels' ),
	's3'  => __( 'Information We Do Not Collect', 'safetunnels' ),
	's4'  => __( 'Information We Collect', 'safetunnels' ),
	's5'  => __( 'How We Use This Information', 'safetunnels' ),
	's6'  => __( 'Your Network Traffic &amp; Browsing Activity', 'safetunnels' ),
	's7'  => __( 'Advertising — Google AdMob', 'safetunnels' ),
	's8'  => __( 'Data Sharing and Sale', 'safetunnels' ),
	's9'  => __( 'Third-Party Services', 'safetunnels' ),
	's10' => __( 'Data Security', 'safetunnels' ),
	's11' => __( 'Data Retention', 'safetunnels' ),
	's12' => __( 'Children\'s Privacy', 'safetunnels' ),
	's13' => __( 'Account Deletion &amp; Your Rights', 'safetunnels' ),
	's14' => __( 'Changes to This Policy', 'safetunnels' ),
	's15' => __( 'Contact Us', 'safetunnels' ),
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
				__( '%s is built to work without collecting who you are. This page explains exactly what data the app handles, why, and what it never touches.', 'safetunnels' ),
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
				'version' => safetunnels_opt( 'privacy_version' ),
				'updated' => safetunnels_opt( 'legal_updated' ),
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
					<p>
						<?php
						printf(
							/* translators: 1: app name, 2: developer, 3: app name, 4: app name. */
							esc_html__( '%1$s is an Android application developed by %2$s that provides secure, encrypted connectivity between your device and %3$s servers for supported apps and services. This Privacy Policy explains what information the app handles, how it is used, and the choices available to you. By installing or using %4$s, you agree to the practices described here.', 'safetunnels' ),
							esc_html( $app ),
							esc_html( $developer ),
							esc_html( $app ),
							esc_html( $app )
						);
						?>
					</p>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( 'We designed %s around a simple principle: the app should work without knowing who you are. We do not require a real name, email address, or phone number at any point.', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
				</section>

				<section id="s2" class="legal-card glass">
					<h2><span class="legal-num">2</span> <?php esc_html_e( 'No Account Registration Required', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( '%s does not require you to sign up with any personal information. When you first open the app, it automatically generates a random, anonymous internal identifier for your installation — you never enter a name, email, or phone number.', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
					<p><?php esc_html_e( 'To operate subscription management on our backend systems, the app also automatically generates a system-internal email address associated with that anonymous identifier. You never type, see, or need to remember this address — it is not used to contact you and is not a real, user-facing email account.', 'safetunnels' ); ?></p>
				</section>

				<section id="s3" class="legal-card glass">
					<h2><span class="legal-num">3</span> <?php esc_html_e( 'Information We Do Not Collect', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( '%s does not require, request, or collect any of the following:', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>

					<div class="legal-collect-grid">
						<div class="legal-collect-col no">
							<div class="legal-collect-title">
								<?php safetunnels_icon( 'ti-x' ); ?> <?php esc_html_e( 'Never collected', 'safetunnels' ); ?>
							</div>
							<ul>
								<?php
								$never = array(
									__( 'Your name', 'safetunnels' ),
									__( 'Phone number', 'safetunnels' ),
									__( 'Home or mailing address', 'safetunnels' ),
									__( 'Contacts', 'safetunnels' ),
									__( 'Messages', 'safetunnels' ),
									__( 'Photos or videos', 'safetunnels' ),
									__( 'Files stored on your device', 'safetunnels' ),
									__( 'Precise (GPS) location', 'safetunnels' ),
									__( 'Payment card information', 'safetunnels' ),
								);
								foreach ( $never as $item ) :
									?>
									<li class="no"><?php safetunnels_icon( 'ti-x' ); ?> <?php echo esc_html( $item ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>

						<div class="legal-collect-col yes">
							<div class="legal-collect-title">
								<?php safetunnels_icon( 'ti-check' ); ?> <?php esc_html_e( 'What we actually collect', 'safetunnels' ); ?>
							</div>
							<ul>
								<?php
								$collected = array(
									__( 'Anonymous device identifier', 'safetunnels' ),
									__( 'Subscription status', 'safetunnels' ),
									__( 'Aggregate traffic (bandwidth) usage', 'safetunnels' ),
									__( 'In-app preferences', 'safetunnels' ),
								);
								foreach ( $collected as $item ) :
									?>
									<li class="yes"><?php safetunnels_icon( 'ti-check' ); ?> <?php echo esc_html( $item ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>

					<p><?php esc_html_e( 'We never claim to collect “no data” — the anonymous identifier and usage figures above are real, but none of them identify you personally.', 'safetunnels' ); ?></p>
				</section>

				<section id="s4" class="legal-card glass">
					<h2><span class="legal-num">4</span> <?php esc_html_e( 'Information We Collect', 'safetunnels' ); ?></h2>
					<h3><?php esc_html_e( 'Anonymous device identifier', 'safetunnels' ); ?></h3>
					<p><?php esc_html_e( 'A randomly generated identifier created on first launch. It is used to identify your installation, restore an existing subscription, and prevent loss of access after replacing your device — without ever containing personal information.', 'safetunnels' ); ?></p>
					<h3><?php esc_html_e( 'Subscription status', 'safetunnels' ); ?></h3>
					<p><?php esc_html_e( 'Whether your installation is on the Free or Premium plan, so the app can unlock the correct features and enforce plan limits.', 'safetunnels' ); ?></p>
					<h3><?php esc_html_e( 'Traffic usage statistics', 'safetunnels' ); ?></h3>
					<p><?php esc_html_e( 'The amount of data (bandwidth) your device has transferred through the tunnel. This is stored strictly as a numeric total, not as a record of destinations or content.', 'safetunnels' ); ?></p>
					<h3><?php esc_html_e( 'Application preferences', 'safetunnels' ); ?></h3>
					<p><?php esc_html_e( 'Basic in-app settings, such as your selected connection or display preferences.', 'safetunnels' ); ?></p>
				</section>

				<section id="s5" class="legal-card glass">
					<h2><span class="legal-num">5</span> <?php esc_html_e( 'How We Use This Information', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'The limited data described above is used exclusively to:', 'safetunnels' ); ?></p>
					<ul>
						<?php
						$uses = array(
							__( 'Enforce Free plan bandwidth limits', 'safetunnels' ),
							__( 'Manage and restore Premium subscriptions', 'safetunnels' ),
							__( 'Show you your own usage statistics inside the app', 'safetunnels' ),
							__( 'Identify your device after a reinstall or replacement, so you don\'t lose access you\'ve paid for', 'safetunnels' ),
						);
						foreach ( $uses as $item ) :
							?>
							<li class="yes"><?php safetunnels_icon( 'ti-check' ); ?> <?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>

				<section id="s6" class="legal-card glass">
					<h2><span class="legal-num">6</span> <?php esc_html_e( 'Your Network Traffic &amp; Browsing Activity', 'safetunnels' ); ?></h2>
					<div class="legal-callout">
						<?php safetunnels_icon( 'ti-eye-off' ); ?>
						<p><?php esc_html_e( 'We do not inspect, monitor, log, or store the content of your network traffic.', 'safetunnels' ); ?></p>
					</div>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( '%s does not keep browsing history, does not record the websites or apps you visit through the tunnel, and does not inspect transmitted content. The only traffic-related figure we retain is a total bandwidth count used for plan enforcement, described in Section 4.', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
				</section>

				<section id="s7" class="legal-card glass">
					<h2><span class="legal-num">7</span> <?php esc_html_e( 'Advertising — Google AdMob', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( 'The Free version of %s displays advertisements served through Google AdMob. AdMob may collect and process information — such as advertising identifiers and device information — in accordance with Google\'s own Privacy Policy, independently of the data described in this document.', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
					<ul>
						<li>
							<?php safetunnels_icon( 'ti-external-link' ); ?>
							<a class="inline-link" href="<?php echo esc_url( $urls['google_privacy'] ); ?>" target="_blank" rel="noopener nofollow"><?php esc_html_e( 'Google Privacy Policy', 'safetunnels' ); ?></a>
						</li>
						<li>
							<?php safetunnels_icon( 'ti-external-link' ); ?>
							<a class="inline-link" href="<?php echo esc_url( $urls['google_admob'] ); ?>" target="_blank" rel="noopener nofollow"><?php esc_html_e( 'Google AdMob Policy', 'safetunnels' ); ?></a>
						</li>
					</ul>
					<p><?php esc_html_e( 'Upgrading to Premium removes all advertisements from the app.', 'safetunnels' ); ?></p>
				</section>

				<section id="s8" class="legal-card glass">
					<h2><span class="legal-num">8</span> <?php esc_html_e( 'Data Sharing and Sale', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( '%s does not sell user information to any third party, and does not share your data with advertisers beyond what is inherently required for Google AdMob to serve ads, as described in Section 7. We do not share VPN traffic with third parties.', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
				</section>

				<section id="s9" class="legal-card glass">
					<h2><span class="legal-num">9</span> <?php esc_html_e( 'Third-Party Services', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( '%s relies on the following third-party services only:', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
					<ul>
						<li class="yes"><?php safetunnels_icon( 'ti-brand-google' ); ?> <?php esc_html_e( 'Google Play Services', 'safetunnels' ); ?></li>
						<li class="yes"><?php safetunnels_icon( 'ti-ad' ); ?> <?php esc_html_e( 'Google AdMob', 'safetunnels' ); ?></li>
						<li class="yes"><?php safetunnels_icon( 'ti-credit-card' ); ?> <?php esc_html_e( 'RevenueCat and Stripe, for web card payments only', 'safetunnels' ); ?></li>
					</ul>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( '%s does not integrate Firebase Analytics, Crashlytics, or any other analytics or crash-reporting SDK beyond what is listed above.', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
				</section>

				<section id="s10" class="legal-card glass">
					<h2><span class="legal-num">10</span> <?php esc_html_e( 'Data Security', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( 'All traffic between the app and %s servers is encrypted in transit. Backend systems that store the anonymous identifier, subscription status, and usage totals are protected with restricted access controls, and we follow reasonable, current security practices to safeguard this information against unauthorized access, alteration, or disclosure.', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
				</section>

				<section id="s11" class="legal-card glass">
					<h2><span class="legal-num">11</span> <?php esc_html_e( 'Data Retention', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'We retain the anonymous identifier, subscription status, and traffic usage totals only for as long as needed to provide the service described in this policy, or until you request deletion as described in Section 13.', 'safetunnels' ); ?></p>
				</section>

				<section id="s12" class="legal-card glass">
					<h2><span class="legal-num">12</span> <?php esc_html_e( 'Children\'s Privacy', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( '%s is not directed toward children under the age of 13, and we do not knowingly collect information from children under 13. If you believe a child has provided information to us, please contact us and we will take appropriate action.', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
				</section>

				<section id="s13" class="legal-card glass">
					<h2><span class="legal-num">13</span> <?php esc_html_e( 'Account Deletion &amp; Your Rights', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'Users can permanently delete their account and associated data from within the app by using the “Delete Account” option in the Profile section. This immediately and permanently removes the data associated with your anonymous device identifier, including your subscription status and traffic usage totals.', 'safetunnels' ); ?></p>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( '%s does not retain any information that can be recovered on your behalf — there is no personal profile to restore. If you\'re unable to access the app, or have questions about how your information is handled, you can also contact us directly.', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
					<div class="legal-cta-row">
						<a class="legal-btn" href="<?php echo esc_url( safetunnels_mailto( 'Account Deletion Request' ) ); ?>">
							<?php safetunnels_icon( 'ti-trash' ); ?> <?php esc_html_e( 'Request Account Deletion', 'safetunnels' ); ?>
						</a>
						<a class="legal-btn out" href="<?php echo esc_url( safetunnels_mailto( 'Privacy Question' ) ); ?>">
							<?php safetunnels_icon( 'ti-shield-question' ); ?> <?php esc_html_e( 'Ask a Privacy Question', 'safetunnels' ); ?>
						</a>
					</div>
				</section>

				<section id="s14" class="legal-card glass">
					<h2><span class="legal-num">14</span> <?php esc_html_e( 'Changes to This Policy', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: 1: developer, 2: app name. */
							esc_html__( '%1$s may update this Privacy Policy from time to time to reflect changes in the app, our practices, or legal requirements. Material changes will be reflected by updating the “Last updated” date at the top of this page. Continued use of %2$s after changes are posted constitutes acceptance of the revised policy.', 'safetunnels' ),
							esc_html( $developer ),
							esc_html( $app )
						);
						?>
					</p>
				</section>

				<section id="s15" class="legal-card glass">
					<h2><span class="legal-num">15</span> <?php esc_html_e( 'Contact Us', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: 1: app name, 2: developer, 3: email link. */
							esc_html__( 'If you have questions about this Privacy Policy or how %1$s handles information, contact %2$s at %3$s.', 'safetunnels' ),
							esc_html( $app ),
							esc_html( $developer ),
							'<a class="inline-link" href="' . esc_url( safetunnels_mailto( 'Privacy Policy Question' ) ) . '">' . esc_html( $email ) . '</a>'
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

<?php
/**
 * Template Name: SafeTunnels — Terms of Service
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$app       = safetunnels_opt( 'app_name' );
$developer = safetunnels_opt( 'developer_name' );
$email     = safetunnels_opt( 'support_email' );

$sections = array(
	't1'  => __( 'Acceptance of Terms', 'safetunnels' ),
	't2'  => __( 'Service Description', 'safetunnels' ),
	't3'  => __( 'Subscriptions &amp; Plans', 'safetunnels' ),
	't4'  => __( 'Billing &amp; Payment', 'safetunnels' ),
	't5'  => __( 'Refund Policy', 'safetunnels' ),
	't6'  => __( 'Service Availability', 'safetunnels' ),
	't7'  => __( 'Acceptable Use', 'safetunnels' ),
	't8'  => __( 'Prohibited Activities', 'safetunnels' ),
	't9'  => __( 'Intellectual Property', 'safetunnels' ),
	't10' => __( 'Limitation of Liability', 'safetunnels' ),
	't11' => __( 'Termination', 'safetunnels' ),
	't12' => __( 'Changes to These Terms', 'safetunnels' ),
	't13' => __( 'Contact Information', 'safetunnels' ),
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
			'title'    => __( 'Terms of Service', 'safetunnels' ),
			'subtitle' => sprintf(
				/* translators: 1: app name, 2: developer. */
				__( 'These Terms govern your use of %1$s, provided by %2$s. Please read them before using the app.', 'safetunnels' ),
				$app,
				$developer
			),
		)
	);
	?>

	<div class="inner lg-content">
		<?php
		get_template_part(
			'template-parts/legal/meta-bar',
			null,
			array( 'version' => safetunnels_opt( 'terms_version' ) )
		);
		?>

		<div class="legal-layout">
			<?php
			get_template_part(
				'template-parts/legal/toc',
				null,
				array(
					'sections' => $sections,
					'label'    => __( 'Terms of Service sections', 'safetunnels' ),
				)
			);
			?>

			<div class="legal-sections">
				<section id="t1" class="legal-card glass">
					<h2><span class="legal-num">1</span> <?php esc_html_e( 'Acceptance of Terms', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: 1: app name, 2: privacy policy link. */
							esc_html__( 'By downloading, installing, or using %1$s, you agree to be bound by these Terms of Service. If you do not agree, please do not use the application. These Terms apply alongside our %2$s.', 'safetunnels' ),
							esc_html( $app ),
							'<a class="inline-link" href="' . esc_url( safetunnels_page_url( 'privacy' ) ) . '">' . esc_html__( 'Privacy Policy', 'safetunnels' ) . '</a>'
						);
						?>
					</p>
				</section>

				<section id="t2" class="legal-card glass">
					<h2><span class="legal-num">2</span> <?php esc_html_e( 'Service Description', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: 1: app name, 2: developer. */
							esc_html__( '%1$s is an Android application that establishes an encrypted connection between your device and servers operated by %2$s, enabling reliable access to supported apps and services. The available destinations and traffic types depend on your active plan, described in Section 3.', 'safetunnels' ),
							esc_html( $app ),
							esc_html( $developer )
						);
						?>
					</p>
				</section>

				<section id="t3" class="legal-card glass">
					<h2><span class="legal-num">3</span> <?php esc_html_e( 'Subscriptions &amp; Plans', 'safetunnels' ); ?></h2>
					<h3><?php esc_html_e( 'Free plan', 'safetunnels' ); ?></h3>
					<p><?php esc_html_e( 'The Free plan is available at no cost and requires no credit card. It includes unlimited bandwidth to supported banking and public-service apps, and displays advertisements. The Free plan does not include specialized apps, streaming apps, or general browser traffic.', 'safetunnels' ); ?></p>
					<h3><?php esc_html_e( 'Premium plan', 'safetunnels' ); ?></h3>
					<p><?php esc_html_e( 'Premium is a paid subscription available on weekly, monthly, quarterly and yearly terms. It unlocks the full catalog of supported apps, streaming and VOD apps, general browser traffic, unlimited bandwidth, priority relay nodes, and removes advertisements. Every period unlocks the same feature set — only the price and the commitment differ.', 'safetunnels' ); ?></p>
					<h3><?php esc_html_e( 'Lifetime ad removal', 'safetunnels' ); ?></h3>
					<p><?php esc_html_e( 'A one-time purchase that permanently removes advertisements while leaving the installation on the Free plan. It does not unlock Premium destinations or traffic types.', 'safetunnels' ); ?></p>
					<p><?php esc_html_e( 'We may adjust plan features, pricing, or limits over time. Material changes to a plan you are subscribed to will be communicated inside the app before taking effect.', 'safetunnels' ); ?></p>
				</section>

				<section id="t4" class="legal-card glass">
					<h2><span class="legal-num">4</span> <?php esc_html_e( 'Billing &amp; Payment', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'Premium subscriptions can be purchased in two ways: through Google Play, or by card on this website through our payment provider. Web payments are processed by RevenueCat Web Billing on Stripe infrastructure; card details are entered on the provider\'s own secure page and are never seen, handled, or stored by us.', 'safetunnels' ); ?></p>
					<p><?php esc_html_e( 'Subscriptions renew automatically at the end of each billing period unless cancelled in advance — through Google Play\'s subscription settings for Play purchases, or through the management link in your payment receipt for web purchases.', 'safetunnels' ); ?></p>
					<p><?php esc_html_e( 'A web purchase is linked to your installation by the account key you paste at checkout. Entering the wrong key links the purchase to a different installation, so we verify the key against our records before payment begins.', 'safetunnels' ); ?></p>
				</section>

				<section id="t5" class="legal-card glass">
					<h2><span class="legal-num">5</span> <?php esc_html_e( 'Refund Policy', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: refund policy link. */
							esc_html__( 'Refund eligibility depends on where the purchase was made: Google Play purchases follow Google\'s billing and refund policies, while web card payments are handled by us directly. Full details are on our %s.', 'safetunnels' ),
							'<a class="inline-link" href="' . esc_url( safetunnels_page_url( 'refund' ) ) . '">' . esc_html__( 'Refund Policy page', 'safetunnels' ) . '</a>'
						);
						?>
					</p>
				</section>

				<section id="t6" class="legal-card glass">
					<h2><span class="legal-num">6</span> <?php esc_html_e( 'Service Availability', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( 'We aim to keep %s available and performant, but we do not guarantee uninterrupted or error-free service. Access to specific apps or destinations may change over time as the supported catalog is updated, and the service may be temporarily unavailable for maintenance, upgrades, or reasons outside our control.', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
				</section>

				<section id="t7" class="legal-card glass">
					<h2><span class="legal-num">7</span> <?php esc_html_e( 'Acceptable Use', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( 'You agree to use %s only for lawful purposes and in a manner that does not infringe the rights of, or restrict or inhibit the use and enjoyment of the service by, any third party.', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
				</section>

				<section id="t8" class="legal-card glass">
					<h2><span class="legal-num">8</span> <?php esc_html_e( 'Prohibited Activities', 'safetunnels' ); ?></h2>
					<p><?php esc_html_e( 'You may not, and may not attempt to:', 'safetunnels' ); ?></p>
					<ul>
						<?php
						$prohibited = array(
							sprintf(
								/* translators: %s: app name. */
								__( 'Abuse, overload, or interfere with the operation of %s or its servers', 'safetunnels' ),
								$app
							),
							__( 'Reverse engineer, decompile, or disassemble the application, except where such restriction is prohibited by applicable law', 'safetunnels' ),
							__( 'Circumvent plan limits, subscription checks, or other access controls', 'safetunnels' ),
							__( 'Use the service to transmit unlawful, harmful, or infringing content', 'safetunnels' ),
							__( 'Resell, sublicense, or redistribute access to the service without our written consent', 'safetunnels' ),
						);
						foreach ( $prohibited as $item ) :
							?>
							<li class="no"><?php safetunnels_icon( 'ti-x' ); ?> <?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>

				<section id="t9" class="legal-card glass">
					<h2><span class="legal-num">9</span> <?php esc_html_e( 'Intellectual Property', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: 1: app name, 2: developer. */
							esc_html__( '%1$s, its logo, and all associated software are the property of %2$s. These Terms do not grant you any ownership rights in the application — only a limited, non-exclusive, non-transferable license to use it in accordance with these Terms.', 'safetunnels' ),
							esc_html( $app ),
							esc_html( $developer )
						);
						?>
					</p>
				</section>

				<section id="t10" class="legal-card glass">
					<h2><span class="legal-num">10</span> <?php esc_html_e( 'Limitation of Liability', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: 1: developer, 2: app name. */
							esc_html__( 'To the maximum extent permitted by applicable law, %1$s shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of, or inability to use, %2$s. The service is provided “as is” and “as available,” without warranties of any kind, express or implied.', 'safetunnels' ),
							esc_html( $developer ),
							esc_html( $app )
						);
						?>
					</p>
				</section>

				<section id="t11" class="legal-card glass">
					<h2><span class="legal-num">11</span> <?php esc_html_e( 'Termination', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: app name. */
							esc_html__( 'You may stop using %s and uninstall it at any time. We may suspend or terminate access to the service for any installation found to violate these Terms, including the prohibited activities listed in Section 8. Where a paid subscription is involved, termination does not entitle you to a refund beyond what is required by the applicable billing policy.', 'safetunnels' ),
							esc_html( $app )
						);
						?>
					</p>
				</section>

				<section id="t12" class="legal-card glass">
					<h2><span class="legal-num">12</span> <?php esc_html_e( 'Changes to These Terms', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: 1: developer, 2: app name. */
							esc_html__( '%1$s may update these Terms from time to time. Material changes will be reflected by updating the “Effective” date at the top of this page. Continued use of %2$s after changes are posted constitutes acceptance of the revised Terms.', 'safetunnels' ),
							esc_html( $developer ),
							esc_html( $app )
						);
						?>
					</p>
				</section>

				<section id="t13" class="legal-card glass">
					<h2><span class="legal-num">13</span> <?php esc_html_e( 'Contact Information', 'safetunnels' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: 1: developer, 2: email link, 3: website link. */
							esc_html__( 'Questions about these Terms can be directed to %1$s at %2$s or via %3$s.', 'safetunnels' ),
							esc_html( $developer ),
							'<a class="inline-link" href="' . esc_url( safetunnels_mailto( 'Terms of Service Question' ) ) . '">' . esc_html( $email ) . '</a>',
							'<a class="inline-link" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( wp_parse_url( home_url(), PHP_URL_HOST ) ) . '</a>'
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

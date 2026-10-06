<?php
/**
 * Template Name: SafeTunnels — Download
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="content">
	<?php
	get_template_part(
		'template-parts/site/page-hero',
		null,
		array(
			'eyebrow'  => __( 'Download', 'safetunnels' ),
			'title'    => sprintf(
				/* translators: %s: app name. */
				__( 'Download %s for Android', 'safetunnels' ),
				safetunnels_opt( 'app_name' )
			),
			'subtitle' => __( 'Install the APK directly or get it from Google Play. Free plan, unlimited bandwidth for supported apps, no account and no credit card.', 'safetunnels' ),
			'actions'  => array(
				array(
					'class'    => 'btn-main',
					'icon'     => 'ti-download',
					'label'    => __( 'Download APK', 'safetunnels' ),
					'url'      => safetunnels_apk_url(),
					'download' => true,
					'package'  => safetunnels_opt( 'apk_package' ),
				),
				array(
					'class' => 'btn-ghost',
					'icon'  => 'ti-crown',
					'label' => __( 'See Premium plans', 'safetunnels' ),
					'url'   => safetunnels_page_url( 'pricing' ),
				),
			),
		)
	);

	get_template_part( 'template-parts/tunnels/devices' );
	get_template_part( 'template-parts/tunnels/steps' );
	?>

	<section id="install-notes">
		<div class="inner">
			<?php
			safetunnels_section_head(
				__( 'Installing the APK', 'safetunnels' ),
				esc_html__( 'What Android will ask you', 'safetunnels' ),
				esc_html__( 'Sideloading an APK is normal — here is exactly what happens, step by step.', 'safetunnels' )
			);
			?>

			<div class="why-grid">
				<div class="why-card">
					<div class="why-ico"><?php safetunnels_icon( 'ti-file-download' ); ?></div>
					<h3><?php esc_html_e( '1 · Allow the download', 'safetunnels' ); ?></h3>
					<p><?php esc_html_e( 'Your browser may warn that APK files can be harmful. That warning appears for every APK; confirm the download to continue.', 'safetunnels' ); ?></p>
				</div>
				<div class="why-card">
					<div class="why-ico"><?php safetunnels_icon( 'ti-settings-check' ); ?></div>
					<h3><?php esc_html_e( '2 · Allow this source', 'safetunnels' ); ?></h3>
					<p><?php esc_html_e( 'Android asks permission to install apps from your browser. Enable it once — it applies only to that browser.', 'safetunnels' ); ?></p>
				</div>
				<div class="why-card">
					<div class="why-ico"><?php safetunnels_icon( 'ti-shield-lock' ); ?></div>
					<h3><?php esc_html_e( '3 · Approve the VPN prompt', 'safetunnels' ); ?></h3>
					<p><?php esc_html_e( 'On first connect, Android asks to allow a VPN connection. This is the standard VpnService prompt every VPN app must request.', 'safetunnels' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<?php
	get_template_part( 'template-parts/tunnels/pricing' );
	get_template_part( 'template-parts/tunnels/cta' );
	?>
</main>

<?php
get_footer();

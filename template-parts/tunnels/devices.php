<?php
/**
 * Availability / download panel.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_devices = safetunnels_device_content();
$st_android = $st_devices['android'];
$st_play    = safetunnels_opt( 'google_play_url' );
?>
<section id="download">
	<div class="inner">
		<?php
		safetunnels_section_head(
			__( 'Availability', 'safetunnels' ),
			esc_html__( 'Available on All Devices', 'safetunnels' ),
			esc_html__( 'One subscription, every platform — download and be connected in minutes.', 'safetunnels' )
		);
		?>

		<div class="device-tabs">
			<div class="dtab active">
				<?php safetunnels_icon( $st_android['icon'] ); ?> <?php esc_html_e( 'Android', 'safetunnels' ); ?>
			</div>
			<?php foreach ( $st_devices['soon'] as $st_soon ) : ?>
				<div class="dtab dtab-soon" title="<?php esc_attr_e( 'Coming soon', 'safetunnels' ); ?>">
					<?php safetunnels_icon( $st_soon['icon'] ); ?>
					<?php echo esc_html( $st_soon['label'] ); ?>
					<span class="dtab-badge"><?php esc_html_e( 'Soon', 'safetunnels' ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="glass">
			<div class="device-panel active">
				<div class="device-grid">
					<div class="device-visual">
						<?php if ( 'home' === safetunnels_current_page_key() ) : ?>
							<div class="download-phone" aria-hidden="true">
								<div class="phone-camera"></div>
								<div class="phone-brand"><?php safetunnels_icon( 'ti-route' ); ?> <?php echo esc_html( safetunnels_opt( 'brand_name' ) ); ?></div>
								<div class="phone-globe"><div class="phone-power"><?php safetunnels_icon( 'ti-power' ); ?></div></div>
								<div class="phone-status"><span class="pulse"></span> <?php esc_html_e( 'Active', 'safetunnels' ); ?></div>
								<div class="phone-protocol">WireGuard · AES-256</div>
								<div class="phone-metrics"><span><b>&lt;20ms</b><?php esc_html_e( 'Avg Latency', 'safetunnels' ); ?></span><span><b><?php echo esc_html( safetunnels_opt( 'stat_countries' ) ); ?></b><?php esc_html_e( 'Countries', 'safetunnels' ); ?></span></div>
								<div class="phone-home"></div>
							</div>
						<?php else : ?>
						<div class="device-icon"><?php safetunnels_icon( $st_android['icon'] ); ?></div>
						<?php endif; ?>
					</div>
					<div class="device-info">
						<h3><?php echo esc_html( $st_android['name'] ); ?></h3>
						<div class="req">
							<?php echo esc_html( $st_android['req'] ); ?>
							<?php if ( safetunnels_opt( 'apk_version' ) ) : ?>
								· <?php echo esc_html( 'v' . safetunnels_opt( 'apk_version' ) ); ?>
							<?php endif; ?>
							<?php if ( safetunnels_opt( 'apk_size' ) ) : ?>
								· <?php echo esc_html( safetunnels_opt( 'apk_size' ) ); ?>
							<?php endif; ?>
						</div>

						<div class="dsteps">
							<?php foreach ( $st_android['steps'] as $st_index => $st_step ) : ?>
								<div class="dstep">
									<div class="dstep-n"><?php echo esc_html( $st_index + 1 ); ?></div>
									<div class="dstep-t">
										<h4><?php echo esc_html( $st_step['title'] ); ?></h4>
										<p><?php echo esc_html( $st_step['sub'] ); ?></p>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<div class="dbtns">
							<a class="dbtn dbtn-play" href="<?php echo esc_url( $st_play ? $st_play : safetunnels_external_urls()['play_store'] ); ?>"
								target="_blank" rel="noopener"
								<?php echo safetunnels_track_attrs( safetunnels_opt( 'apk_package' ), 'Google Play' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<img src="<?php echo esc_url( safetunnels_asset( 'img/google-play.svg' ) ); ?>" width="24" height="24" alt="" /> <?php esc_html_e( 'Download from Google Play', 'safetunnels' ); ?>
							</a>
							<a class="dbtn out dbtn-apk" href="<?php echo esc_url( safetunnels_apk_url() ); ?>" download
								<?php echo safetunnels_track_attrs( safetunnels_opt( 'apk_package' ), 'Tunnel Network APK' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<?php safetunnels_icon( 'ti-download' ); ?> <?php esc_html_e( 'Download APK Directly', 'safetunnels' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

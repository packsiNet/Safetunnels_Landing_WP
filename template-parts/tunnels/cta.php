<?php
/**
 * Closing call to action.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="st-cta">
	<div class="inner">
		<div class="st-cta-box glass">
			<div class="st-cta-copy">
				<div class="sec-label"><?php esc_html_e( 'Get Started', 'safetunnels' ); ?></div>
				<h2 class="sec-title"><?php esc_html_e( 'Iran is two minutes away.', 'safetunnels' ); ?></h2>
				<p class="sec-sub">
					<?php esc_html_e( 'Install the app, tap connect, and your Iranian apps work again — free, with no card and no account to create.', 'safetunnels' ); ?>
				</p>
				<div class="hero-ctas">
					<a class="btn-main" href="<?php echo esc_url( safetunnels_apk_url() ); ?>" download
						<?php echo safetunnels_track_attrs( safetunnels_opt( 'apk_package' ), 'Tunnel Network APK' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php safetunnels_icon( 'ti-download' ); ?> <?php esc_html_e( 'Download for Android', 'safetunnels' ); ?>
					</a>
					<a class="btn-ghost" href="<?php echo esc_url( safetunnels_page_url( 'pricing' ) ); ?>">
						<?php safetunnels_icon( 'ti-crown' ); ?> <?php esc_html_e( 'Compare plans', 'safetunnels' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

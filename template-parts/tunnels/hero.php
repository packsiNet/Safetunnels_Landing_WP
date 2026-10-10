<?php
/**
 * Landing hero.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_heading_tag = isset( $args['h1'] ) && ! $args['h1'] ? 'h2' : 'h1';
?>
<section class="hero">
	<div class="inner">
		<div class="hero-grid">
			<div>
				<div class="hero-badge">
					<span class="pulse"></span>
					<?php
					printf(
						/* translators: %s: brand name. */
						esc_html__( '%s · Active', 'safetunnels' ),
						esc_html( safetunnels_opt( 'brand_name' ) )
					);
					?>
				</div>

				<<?php echo esc_attr( $st_heading_tag ); ?> class="headline">
					<?php esc_html_e( 'Tunnels to', 'safetunnels' ); ?>
					<span class="green"><?php esc_html_e( 'Iran', 'safetunnels' ); ?></span>
					<br />
					<?php esc_html_e( 'Without Limits', 'safetunnels' ); ?>
				</<?php echo esc_attr( $st_heading_tag ); ?>>

				<p class="hero-desc">
					<?php
					printf(
						/* translators: %s: brand name. */
						esc_html__( 'With %s, access all your Iranian apps from anywhere in the world. Only Iranian apps go through the tunnel — everything else uses your normal connection.', 'safetunnels' ),
						esc_html( safetunnels_opt( 'brand_name' ) )
					);
					?>
				</p>

				<div class="hero-ctas">
					<a class="btn-main" href="<?php echo esc_url( safetunnels_apk_url() ); ?>" download
						<?php echo safetunnels_track_attrs( safetunnels_opt( 'apk_package' ), 'Tunnel Network APK' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php safetunnels_icon( 'ti-download' ); ?> <?php esc_html_e( 'Download for Android', 'safetunnels' ); ?>
					</a>
					<a class="btn-ghost" href="#pricing">
						<?php safetunnels_icon( 'ti-eye' ); ?> <?php esc_html_e( 'View Plans', 'safetunnels' ); ?>
					</a>
				</div>

				<div class="hero-stats">
					<div>
						<div class="hstat-val"><?php echo esc_html( safetunnels_opt( 'stat_countries' ) ); ?></div>
						<div class="hstat-lbl"><?php esc_html_e( 'Countries', 'safetunnels' ); ?></div>
					</div>
					<div>
						<div class="hstat-val"><?php echo esc_html( safetunnels_opt( 'stat_encryption' ) ); ?></div>
						<div class="hstat-lbl"><?php esc_html_e( 'Encrypted', 'safetunnels' ); ?></div>
					</div>
					<div>
						<div class="hstat-val"><?php echo esc_html( safetunnels_opt( 'stat_support' ) ); ?></div>
						<div class="hstat-lbl"><?php esc_html_e( 'Support', 'safetunnels' ); ?></div>
					</div>
				</div>
			</div>

			<div class="hero-visual" aria-hidden="true">
				<?php if ( 'home' === safetunnels_current_page_key() ) : ?>
					<div class="network-art">
						<div class="network-halo"></div>
						<img class="network-globe" src="<?php echo esc_url( safetunnels_asset( 'img/network-globe.svg' ) ); ?>" width="560" height="560" alt="" fetchpriority="high" />
						<div class="network-shield">
							<svg viewBox="0 0 48 56" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24 3 44 11v16c0 12-9 21-20 26C13 48 4 39 4 27V11L24 3Z" stroke="currentColor" stroke-width="2.5"/><path d="m15 27 6 6 12-13" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</div>
						<div class="network-card network-security">
							<span class="network-card-icon"><?php safetunnels_icon( 'ti-lock' ); ?></span>
							<div><strong>AES-256</strong><span><?php esc_html_e( 'Encrypted', 'safetunnels' ); ?></span></div>
							<span class="network-check"><?php safetunnels_icon( 'ti-check' ); ?></span>
						</div>
						<div class="network-card network-speed">
							<span class="network-card-icon"><?php safetunnels_icon( 'ti-bolt' ); ?></span>
							<div><strong>&lt;20ms</strong><span><?php esc_html_e( 'Avg Latency', 'safetunnels' ); ?></span></div>
							<span class="network-bars"><b></b><b></b><b></b><b></b></span>
						</div>
						<div class="network-app network-app-one"><img src="<?php echo esc_url( safetunnels_asset( 'img/apps/appicon_blu.webp' ) ); ?>" width="44" height="44" alt="" /></div>
						<div class="network-app network-app-two"><img src="<?php echo esc_url( safetunnels_asset( 'img/apps/appicon_com_digikala.webp' ) ); ?>" width="44" height="44" alt="" /></div>
						<div class="network-app network-app-three"><img src="<?php echo esc_url( safetunnels_asset( 'img/apps/appicon_mellat.webp' ) ); ?>" width="44" height="44" alt="" /></div>
						<div class="network-caption"><span class="pulse"></span> WireGuard · <?php esc_html_e( 'Active', 'safetunnels' ); ?></div>
					</div>
				<?php else : ?>
				<div class="orb-wrap">
					<div class="orb-ring r1"></div>
					<div class="orb-ring r2"></div>
					<div class="orb-ring r3"></div>
					<div class="orb-dot"></div>
					<div class="orb-core"><?php safetunnels_icon( 'ti-shield-lock' ); ?></div>
					<div class="fbadge f1">
						<?php safetunnels_icon( 'ti-lock', 'fbadge-icon' ); ?>
						<div>
							<div class="fbadge-val">AES-256</div>
							<div class="fbadge-sub"><?php esc_html_e( 'Encrypted', 'safetunnels' ); ?></div>
						</div>
					</div>
					<div class="fbadge f2">
						<?php safetunnels_icon( 'ti-bolt', 'fbadge-icon' ); ?>
						<div>
							<div class="fbadge-val">&lt;20ms</div>
							<div class="fbadge-sub"><?php esc_html_e( 'Avg Latency', 'safetunnels' ); ?></div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

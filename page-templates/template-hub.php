<?php
/**
 * Template Name: Studio — Product Hub
 * @package SafeTunnels
 */
defined( 'ABSPATH' ) || exit;
get_header( 'studio' );
?>
<header class="studio-topbar studio-width">
	<a class="studio-brand" href="<?php echo esc_url( safetunnels_page_url( 'hub' ) ); ?>" aria-label="<?php esc_attr_e( 'Studio Hub home', 'safetunnels' ); ?>">
		<span class="studio-mark" aria-hidden="true"><b></b><b></b><b></b><b></b></span>
		<span>Studio<span class="studio-brand-light"> / Hub</span></span>
	</a>
	<a class="studio-toplink" href="#destinations"><span class="studio-live-dot" aria-hidden="true"></span><?php esc_html_e( 'Two products. One universe.', 'safetunnels' ); ?></a>
</header>

<main id="content" class="studio-width">
	<section class="hub-intro" aria-labelledby="hub-title">
		<div>
			<p class="studio-eyebrow"><span aria-hidden="true">✳</span> <?php esc_html_e( 'STUDIO APPS', 'safetunnels' ); ?></p>
			<h1 id="hub-title"><?php esc_html_e( 'Choose your', 'safetunnels' ); ?><br /><span><?php esc_html_e( 'destination.', 'safetunnels' ); ?></span></h1>
		</div>
		<div class="hub-intro-aside">
			<span class="hub-doodle" aria-hidden="true">↘</span>
			<p><?php esc_html_e( 'Two products, one universe.', 'safetunnels' ); ?><br /><?php esc_html_e( 'A more private connection. A little more play.', 'safetunnels' ); ?></p>
			<span class="hub-prompt"><?php esc_html_e( 'Pick a card. Make yourself at home.', 'safetunnels' ); ?></span>
		</div>
	</section>

	<section class="hub-destinations" id="destinations" aria-label="<?php esc_attr_e( 'Choose a product', 'safetunnels' ); ?>">
		<a class="hub-card hub-tunnel" href="<?php echo esc_url( safetunnels_page_url( 'home' ) ); ?>" aria-labelledby="hub-tunnel-title">
			<div class="hub-card-top"><span class="hub-category"><?php esc_html_e( 'NETWORK', 'safetunnels' ); ?> <span> / 01</span></span><span class="hub-status"><i aria-hidden="true"></i><?php esc_html_e( 'Active', 'safetunnels' ); ?></span></div>
			<div class="hub-tunnel-art" aria-hidden="true">
				<img src="<?php echo esc_url( safetunnels_asset( 'img/network-globe.svg' ) ); ?>" width="560" height="560" alt="" />
				<div class="hub-orbit hub-orbit-one"></div><div class="hub-orbit hub-orbit-two"></div>
				<div class="hub-shield"><svg viewBox="0 0 48 56" fill="none"><path d="M24 3 44 11v16c0 12-9 21-20 26C13 48 4 39 4 27V11L24 3Z" stroke="currentColor" stroke-width="2"/><path d="m15 27 6 6 12-13" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
				<span class="hub-art-label">AES-256</span><span class="hub-art-dot"></span>
			</div>
			<div class="hub-card-copy">
				<h2 id="hub-tunnel-title"><?php esc_html_e( 'Tunnel Network', 'safetunnels' ); ?></h2>
				<p><?php esc_html_e( 'Private, encrypted routing through a global mesh — built for speed, silence, and resilience.', 'safetunnels' ); ?></p>
				<ul class="hub-features">
					<li><?php esc_html_e( 'AES-256 end-to-end encryption', 'safetunnels' ); ?></li>
					<li><?php esc_html_e( '40+ global relay nodes', 'safetunnels' ); ?></li>
					<li><?php esc_html_e( 'Encrypted, privacy-first connection', 'safetunnels' ); ?></li>
					<li><?php esc_html_e( 'Sub-20ms adaptive routing', 'safetunnels' ); ?></li>
				</ul>
			</div>
			<div class="hub-card-bottom"><span><?php esc_html_e( 'Explore Tunnel Network', 'safetunnels' ); ?></span><span class="hub-enter" aria-hidden="true">↗</span></div>
		</a>

		<a class="hub-card hub-peeko" href="<?php echo esc_url( safetunnels_page_url( 'peeko_privacy' ) ); ?>" aria-labelledby="hub-peeko-title">
			<div class="hub-card-top"><span class="hub-category"><?php esc_html_e( 'GAME', 'safetunnels' ); ?> <span> / 02</span></span><span class="hub-status"><?php esc_html_e( 'Privacy', 'safetunnels' ); ?></span></div>
			<div class="hub-peeko-art" aria-hidden="true">
				<div class="hub-number-grid"><span>2</span><span>9</span><span>4</span><span>7</span><span class="hub-number-picked">5<small>✦</small></span><span>1</span><span>8</span><span>3</span><span>6</span></div>
				<span class="hub-spark hub-spark-one">✳</span><span class="hub-spark hub-spark-two">✧</span><span class="hub-game-orbit"></span>
			</div>
			<div class="hub-card-copy">
				<h2 id="hub-peeko-title"><?php esc_html_e( 'Peeko', 'safetunnels' ); ?><span class="hub-peeko-wordmark" aria-hidden="true">☺</span></h2>
				<p><?php esc_html_e( 'A number-finding game supported by ads. Read its privacy policy.', 'safetunnels' ); ?></p>
				<ul class="hub-features">
					<li><?php esc_html_e( 'No purchase plans', 'safetunnels' ); ?></li>
					<li><?php esc_html_e( 'Game progress stays on your device', 'safetunnels' ); ?></li>
					<li><?php esc_html_e( 'Interstitial ads through Google AdMob', 'safetunnels' ); ?></li>
				</ul>
			</div>
			<div class="hub-card-bottom"><span><?php esc_html_e( 'Read Peeko’s privacy policy', 'safetunnels' ); ?></span><span class="hub-enter" aria-hidden="true">↗</span></div>
		</a>
	</section>

	<div class="hub-signoff"><span class="hub-signoff-line" aria-hidden="true"></span><p><?php esc_html_e( 'Different worlds. The same attention to detail.', 'safetunnels' ); ?></p><span class="hub-signoff-line" aria-hidden="true"></span></div>
</main>

<footer class="studio-footer studio-width"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> · <?php echo esc_html( safetunnels_opt( 'developer_name' ) ); ?></span><span><?php esc_html_e( 'Studio Hub', 'safetunnels' ); ?></span><span class="studio-footer-note"><?php esc_html_e( 'Connection & play', 'safetunnels' ); ?> <span aria-hidden="true">✳</span></span></footer>
<?php get_footer( 'studio' ); ?>

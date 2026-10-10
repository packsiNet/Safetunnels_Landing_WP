<?php
/**
 * Template Name: Studio — Peeko Privacy
 * Content follows the PeekoPrivacyPage in the reference project.
 * @package SafeTunnels
 */
defined( 'ABSPATH' ) || exit;
$google_privacy = safetunnels_external_urls()['google_privacy'];
get_header( 'studio' );
?>
<header class="studio-topbar studio-width">
	<a class="studio-brand peeko-brand" href="<?php echo esc_url( safetunnels_page_url( 'hub' ) ); ?>"><span class="peeko-brand-icon" aria-hidden="true">5</span>Peeko</a>
	<a class="studio-back" href="<?php echo esc_url( safetunnels_page_url( 'hub' ) ); ?>"><span aria-hidden="true">↖</span> <?php esc_html_e( 'Back to Hub', 'safetunnels' ); ?></a>
</header>
<main id="content" class="peeko-policy studio-width">
	<div class="peeko-policy-intro">
		<p class="studio-eyebrow"><?php esc_html_e( 'Legal', 'safetunnels' ); ?></p>
		<h1><?php esc_html_e( 'Privacy Policy for Peeko', 'safetunnels' ); ?></h1>
		<p class="peeko-policy-date"><?php esc_html_e( 'Last updated: October 6, 2026', 'safetunnels' ); ?></p>
		<p><?php esc_html_e( 'Peeko is a number-finding game. This policy explains how information is handled when you use the Android app.', 'safetunnels' ); ?></p>
	</div>
	<div class="peeko-policy-layout">
		<nav class="peeko-policy-toc" aria-label="<?php esc_attr_e( 'Peeko privacy sections', 'safetunnels' ); ?>">
			<span><?php esc_html_e( 'On this page', 'safetunnels' ); ?></span>
			<a href="#peeko-device"><?php esc_html_e( 'On-device information', 'safetunnels' ); ?></a>
			<a href="#peeko-ads"><?php esc_html_e( 'Advertising', 'safetunnels' ); ?></a>
			<a href="#peeko-deletion"><?php esc_html_e( 'Retention and deletion', 'safetunnels' ); ?></a>
			<a href="#peeko-changes"><?php esc_html_e( 'Policy changes', 'safetunnels' ); ?></a>
			<a href="#peeko-contact"><?php esc_html_e( 'Contact', 'safetunnels' ); ?></a>
		</nav>
		<div class="peeko-policy-sections">
			<section id="peeko-device"><span class="peeko-section-num" aria-hidden="true">01</span><h2><?php esc_html_e( 'Information stored on your device', 'safetunnels' ); ?></h2><p><?php esc_html_e( 'Peeko stores the name you enter, game results and records, sound preference, and the number of games started on your device. These are used to display your name, save your progress, and run the game. Peeko does not create an online account or send this game profile to our server.', 'safetunnels' ); ?></p></section>
			<section id="peeko-ads"><span class="peeko-section-num" aria-hidden="true">02</span><h2><?php esc_html_e( 'Advertising', 'safetunnels' ); ?></h2><p><?php esc_html_e( 'Peeko uses Google AdMob to show interstitial ads. The Google Mobile Ads SDK may collect and share your IP address (which may indicate approximate location), app interaction information, diagnostic information, and device or other identifiers, including advertising identifiers. This information may be used for advertising, analytics, and fraud prevention. Data sent by the SDK is encrypted in transit. Google\'s handling of this information is described at', 'safetunnels' ); ?> <a href="<?php echo esc_url( $google_privacy ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $google_privacy ); ?></a>.</p></section>
			<section id="peeko-deletion"><span class="peeko-section-num" aria-hidden="true">03</span><h2><?php esc_html_e( 'Data retention and deletion', 'safetunnels' ); ?></h2><p><?php esc_html_e( 'Game information stored by Peeko remains on your device until you clear the app\'s storage or uninstall the app. Device backup settings may affect whether a copy is retained in your device backup. Peeko does not currently provide a way to request deletion of data processed by the advertising provider. For information about Google\'s data controls, visit', 'safetunnels' ); ?> <a href="<?php echo esc_url( $google_privacy ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $google_privacy ); ?></a>.</p></section>
			<section id="peeko-changes"><span class="peeko-section-num" aria-hidden="true">04</span><h2><?php esc_html_e( 'Changes to this policy', 'safetunnels' ); ?></h2><p><?php esc_html_e( 'We may update this policy if the app or its data practices change. The latest version will be posted on this page.', 'safetunnels' ); ?></p></section>
			<section id="peeko-contact"><span class="peeko-section-num" aria-hidden="true">05</span><h2><?php esc_html_e( 'Contact', 'safetunnels' ); ?></h2><p><?php esc_html_e( 'For questions about this policy, email', 'safetunnels' ); ?> <a href="mailto:info@safetunnels.net">info@safetunnels.net</a>.</p></section>
		</div>
	</div>
</main>
<footer class="studio-footer studio-width"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Peeko</span><a href="<?php echo esc_url( safetunnels_page_url( 'hub' ) ); ?>"><?php esc_html_e( 'Back to Hub', 'safetunnels' ); ?> <span aria-hidden="true">↗</span></a></footer>
<?php get_footer( 'studio' ); ?>

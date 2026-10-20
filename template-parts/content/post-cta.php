<?php
/**
 * Shared download and plans section displayed below every blog article.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="legal-card glass st-post-cta" aria-labelledby="st-post-cta-title">
	<h2 id="st-post-cta-title"><?php esc_html_e( 'Get started with SafeTunnels', 'safetunnels' ); ?></h2>
	<p><?php esc_html_e( 'Download SafeTunnels for Android and compare the available plans for accessing supported Iranian apps from abroad.', 'safetunnels' ); ?></p>
	<div class="legal-cta-row">
		<a class="legal-btn" href="<?php echo esc_url( safetunnels_page_url( 'download' ) ); ?>">
			<?php safetunnels_icon( 'ti-download' ); ?> <?php esc_html_e( 'Download for Android', 'safetunnels' ); ?>
		</a>
		<a class="legal-btn out" href="<?php echo esc_url( safetunnels_page_url( 'pricing' ) ); ?>">
			<?php safetunnels_icon( 'ti-credit-card' ); ?> <?php esc_html_e( 'View plans & pricing', 'safetunnels' ); ?>
		</a>
	</div>
</section>

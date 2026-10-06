<?php
/**
 * Blog sidebar.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;
?>
<aside class="st-blog-side">
	<div class="widget glass">
		<h2 class="widget-title"><?php esc_html_e( 'Get SafeTunnels', 'safetunnels' ); ?></h2>
		<p><?php esc_html_e( 'Free plan, unlimited bandwidth for supported apps, no account needed.', 'safetunnels' ); ?></p>
		<a class="legal-btn" href="<?php echo esc_url( safetunnels_apk_url() ); ?>" download
			<?php echo safetunnels_track_attrs( safetunnels_opt( 'apk_package' ), 'Tunnel Network APK' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php safetunnels_icon( 'ti-download' ); ?> <?php esc_html_e( 'Download for Android', 'safetunnels' ); ?>
		</a>
	</div>

	<?php if ( is_active_sidebar( 'blog-sidebar' ) ) : ?>
		<?php dynamic_sidebar( 'blog-sidebar' ); ?>
	<?php else : ?>
		<div class="widget glass">
			<h2 class="widget-title"><?php esc_html_e( 'Help Center', 'safetunnels' ); ?></h2>
			<ul>
				<li><a href="<?php echo esc_url( safetunnels_page_url( 'faq' ) ); ?>"><?php esc_html_e( 'FAQ', 'safetunnels' ); ?></a></li>
				<li><a href="<?php echo esc_url( safetunnels_page_url( 'support' ) ); ?>"><?php esc_html_e( 'Support', 'safetunnels' ); ?></a></li>
				<li><a href="<?php echo esc_url( safetunnels_page_url( 'pricing' ) ); ?>"><?php esc_html_e( 'Pricing', 'safetunnels' ); ?></a></li>
				<li><a href="<?php echo esc_url( safetunnels_page_url( 'apps' ) ); ?>"><?php esc_html_e( 'Iranian apps', 'safetunnels' ); ?></a></li>
			</ul>
		</div>
	<?php endif; ?>
</aside>

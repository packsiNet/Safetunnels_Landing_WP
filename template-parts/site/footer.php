<?php
/**
 * Site footer.
 *
 * The link block is deliberately complete rather than minimal: these are the
 * internal links that let a crawler reach every page on the site from any
 * page on the site.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_columns = array(
	array(
		'title' => __( 'Product', 'safetunnels' ),
		'links' => array(
			array( __( 'Download', 'safetunnels' ), safetunnels_page_url( 'download' ) ),
			array( __( 'Pricing', 'safetunnels' ), safetunnels_page_url( 'pricing' ) ),
			array( __( 'Iranian Apps', 'safetunnels' ), safetunnels_page_url( 'apps' ) ),
			array( 'دسترسی به اپ‌های ایرانی', safetunnels_page_url( 'iranian_access' ) ),
			array( 'تانل ایران چیست؟', safetunnels_page_url( 'iran_tunnel' ) ),
			array( __( 'Features', 'safetunnels' ), safetunnels_page_url( 'home' ) . '#features' ),
		),
	),
	array(
		'title' => __( 'Help', 'safetunnels' ),
		'links' => array(
			array( __( 'Support', 'safetunnels' ), safetunnels_page_url( 'support' ) ),
			array( __( 'FAQ', 'safetunnels' ), safetunnels_page_url( 'faq' ) ),
			array( __( 'Contact', 'safetunnels' ), safetunnels_page_url( 'contact' ) ),
			array( __( 'Blog', 'safetunnels' ), get_permalink( (int) get_option( 'page_for_posts' ) ) ),
		),
	),
	array(
		'title' => __( 'Legal', 'safetunnels' ),
		'links' => array(
			array( __( 'Privacy Policy', 'safetunnels' ), safetunnels_page_url( 'privacy' ) ),
			array( __( 'Terms of Service', 'safetunnels' ), safetunnels_page_url( 'terms' ) ),
			array( __( 'Refund Policy', 'safetunnels' ), safetunnels_page_url( 'refund' ) ),
			array( __( 'Cookie Policy', 'safetunnels' ), safetunnels_page_url( 'cookies' ) ),
		),
	),
);
?>
<footer>
	<div class="st-footer-top inner">
		<div class="st-footer-brand">
			<div class="footer-logo">
				<span class="flogo-ico"><?php safetunnels_icon( 'ti-route' ); ?></span>
				<span class="flogo-name"><?php echo esc_html( safetunnels_opt( 'brand_name' ) ); ?></span>
			</div>
			<p class="st-footer-tag">
				<?php esc_html_e( 'Every Iranian app, from anywhere in the world. Only Iranian traffic goes through the tunnel — everything else uses your normal connection.', 'safetunnels' ); ?>
			</p>
			<a class="st-footer-dl" href="<?php echo esc_url( safetunnels_apk_url() ); ?>" download
				<?php echo safetunnels_track_attrs( safetunnels_opt( 'apk_package' ), 'Tunnel Network APK' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php safetunnels_icon( 'ti-brand-android' ); ?> <?php esc_html_e( 'Download for Android', 'safetunnels' ); ?>
			</a>
		</div>

		<?php foreach ( $st_columns as $st_column ) : ?>
			<nav class="st-footer-col" aria-label="<?php echo esc_attr( $st_column['title'] ); ?>">
				<h2 class="st-footer-title"><?php echo esc_html( $st_column['title'] ); ?></h2>
				<ul>
					<?php foreach ( $st_column['links'] as $st_link ) : ?>
						<?php if ( ! $st_link[1] ) { continue; } ?>
						<li><a href="<?php echo esc_url( $st_link[1] ); ?>"><?php echo esc_html( $st_link[0] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endforeach; ?>

		<div class="st-footer-col">
			<h2 class="st-footer-title"><?php esc_html_e( 'Contact', 'safetunnels' ); ?></h2>
			<ul>
				<li>
					<a href="<?php echo esc_url( safetunnels_mailto( 'SafeTunnels Inquiry' ) ); ?>">
						<?php echo esc_html( safetunnels_opt( 'support_email' ) ); ?>
					</a>
				</li>
				<li><?php echo esc_html( safetunnels_opt( 'developer_name' ) ); ?></li>
				<li>
					<a href="<?php echo esc_url( home_url( '/wp-sitemap.xml' ) ); ?>"><?php esc_html_e( 'Sitemap', 'safetunnels' ); ?></a>
				</li>
			</ul>
		</div>
	</div>

	<div class="footer-row">
		<div class="footer-links">
			<a href="<?php echo esc_url( safetunnels_page_url( 'hub' ) ); ?>"><?php esc_html_e( '← Back to Hub', 'safetunnels' ); ?></a>
			<?php
			// The footer link row is a flex row of bare anchors, so the menu is
			// rendered by hand rather than through the list-based default walker.
			$st_legal_items = has_nav_menu( 'legal' ) ? wp_get_nav_menu_items( get_nav_menu_locations()['legal'] ) : array();

			if ( $st_legal_items ) {
				foreach ( (array) $st_legal_items as $st_legal_item ) {
					if ( (int) $st_legal_item->menu_item_parent ) {
						continue;
					}
					printf( '<a href="%s">%s</a>', esc_url( $st_legal_item->url ), esc_html( $st_legal_item->title ) );
				}
			} else {
				?>
				<a href="<?php echo esc_url( safetunnels_page_url( 'privacy' ) ); ?>"><?php esc_html_e( 'Privacy', 'safetunnels' ); ?></a>
				<a href="<?php echo esc_url( safetunnels_page_url( 'terms' ) ); ?>"><?php esc_html_e( 'Terms', 'safetunnels' ); ?></a>
				<a href="<?php echo esc_url( safetunnels_page_url( 'support' ) ); ?>"><?php esc_html_e( 'Support', 'safetunnels' ); ?></a>
				<a href="<?php echo esc_url( safetunnels_page_url( 'faq' ) ); ?>"><?php esc_html_e( 'FAQ', 'safetunnels' ); ?></a>
				<a href="<?php echo esc_url( safetunnels_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Contact', 'safetunnels' ); ?></a>
				<?php
			}
			?>
		</div>
		<div class="footer-copy">
			<?php
			printf(
				/* translators: 1: year, 2: brand, 3: developer. */
				esc_html__( '© %1$s %2$s · %3$s · All rights reserved', 'safetunnels' ),
				esc_html( gmdate( 'Y' ) ),
				esc_html( safetunnels_opt( 'brand_name' ) ),
				esc_html( safetunnels_opt( 'developer_name' ) )
			);
			?>
		</div>
	</div>
</footer>

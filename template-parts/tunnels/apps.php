<?php
/**
 * Iranian app carousel.
 *
 * Rendered server-side (not fetched) so all 62 apps and their outbound links
 * are in the HTML a crawler receives — the ItemList schema in inc/schema.php
 * describes exactly this markup.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_rows = safetunnels_app_rows();
?>
<section id="iranian-apps">
	<div class="inner">
		<?php
		safetunnels_section_head(
			__( 'Iranian Apps', 'safetunnels' ),
			esc_html__( 'Download Iranian Apps', 'safetunnels' ),
			esc_html__( 'From anywhere in the world — access, download and use the apps that matter most to Iranians.', 'safetunnels' )
		);
		?>

		<div class="apps-stats">
			<div>
				<div class="astat-val"><?php echo esc_html( safetunnels_app_count() ); ?>+</div>
				<div class="astat-lbl"><?php esc_html_e( 'Apps Available', 'safetunnels' ); ?></div>
			</div>
			<div>
				<div class="astat-val"><?php echo esc_html( safetunnels_app_category_count() ); ?></div>
				<div class="astat-lbl"><?php esc_html_e( 'Categories', 'safetunnels' ); ?></div>
			</div>
			<div>
				<div class="astat-val"><?php esc_html_e( 'Weekly', 'safetunnels' ); ?></div>
				<div class="astat-lbl"><?php esc_html_e( 'Updates', 'safetunnels' ); ?></div>
			</div>
		</div>

		<div class="apps-carousel-wrap">
			<button type="button" class="apps-arrow apps-arrow-l" data-st-scroll="left" aria-label="<?php esc_attr_e( 'Scroll left', 'safetunnels' ); ?>">
				<?php safetunnels_icon( 'ti-chevron-left' ); ?>
			</button>

			<div class="apps-scroll-outer" id="st-apps-scroll" tabindex="0" role="region" aria-label="<?php esc_attr_e( 'Iranian app catalogue', 'safetunnels' ); ?>">
				<div class="apps-rows-inner">
					<?php foreach ( $st_rows as $st_row ) : ?>
						<div class="apps-row">
							<div class="apps-row-label" dir="rtl"><?php echo esc_html( $st_row['label'] ); ?></div>

							<?php foreach ( $st_row['apps'] as $st_app ) : ?>
								<?php
								$st_icon    = safetunnels_app_icon( $st_app['key'] );
								$st_package = safetunnels_package_name( $st_app['key'], $st_app['name'] );
								?>
								<div class="app-card">
									<div class="app-ico">
										<?php if ( $st_icon ) : ?>
											<img src="<?php echo esc_url( $st_icon ); ?>"
												alt="<?php echo esc_attr( sprintf( /* translators: %s: app name. */ __( '%s app icon', 'safetunnels' ), $st_app['name'] ) ); ?>"
												width="44" height="44" loading="lazy" decoding="async" />
										<?php else : ?>
											<?php safetunnels_icon( 'ti-building-bank' ); ?>
										<?php endif; ?>
									</div>
									<div class="app-name" dir="rtl"><?php echo esc_html( $st_app['name'] ); ?></div>
									<div class="app-cat" dir="rtl"><?php echo esc_html( $st_app['cat'] ); ?></div>
									<a class="app-btn"
										href="<?php echo esc_url( $st_app['url'] ? $st_app['url'] : '#' ); ?>"
										<?php echo $st_app['url'] ? ' target="_blank" rel="noopener nofollow"' : ''; ?>
										<?php echo safetunnels_track_attrs( $st_package, $st_app['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
										<?php safetunnels_icon( 'ti-download' ); ?> <span dir="rtl">دانلود</span>
									</a>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<button type="button" class="apps-arrow apps-arrow-r" data-st-scroll="right" aria-label="<?php esc_attr_e( 'Scroll right', 'safetunnels' ); ?>">
				<?php safetunnels_icon( 'ti-chevron-right' ); ?>
			</button>
		</div>

		<?php if ( ! is_page_template( 'page-templates/template-apps.php' ) ) : ?>
			<p class="apps-more">
				<a class="btn-ghost" href="<?php echo esc_url( safetunnels_page_url( 'apps' ) ); ?>">
					<?php safetunnels_icon( 'ti-list-search' ); ?>
					<?php esc_html_e( 'Browse the full app catalogue', 'safetunnels' ); ?>
				</a>
			</p>
		<?php endif; ?>
	</div>
</section>

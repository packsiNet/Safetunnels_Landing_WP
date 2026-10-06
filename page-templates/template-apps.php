<?php
/**
 * Template Name: SafeTunnels — Iranian Apps
 *
 * The full catalogue, printed as text as well as carousel cards: the grouped
 * lists below are what actually make this page rank for individual app names.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_by_category = array();

foreach ( safetunnels_all_apps() as $st_app ) {
	$st_by_category[ $st_app['cat'] ][] = $st_app;
}

get_header();
?>

<main id="content">
	<?php
	get_template_part(
		'template-parts/site/page-hero',
		null,
		array(
			'eyebrow'  => __( 'Catalogue', 'safetunnels' ),
			'title'    => __( 'Iranian apps that work with SafeTunnels', 'safetunnels' ),
			'subtitle' => sprintf(
				/* translators: 1: app count, 2: category count. */
				__( '%1$d apps across %2$d categories — banking, neobanks, payments, brokerages, insurance, marketplaces, operators and public services — reachable from anywhere in the world.', 'safetunnels' ),
				safetunnels_app_count(),
				safetunnels_app_category_count()
			),
			'actions'  => array(
				array(
					'class'    => 'btn-main',
					'icon'     => 'ti-download',
					'label'    => __( 'Get the app', 'safetunnels' ),
					'url'      => safetunnels_apk_url(),
					'download' => true,
					'package'  => safetunnels_opt( 'apk_package' ),
				),
			),
		)
	);

	get_template_part( 'template-parts/tunnels/apps' );
	?>

	<section id="catalogue">
		<div class="inner">
			<?php
			safetunnels_section_head(
				__( 'By category', 'safetunnels' ),
				esc_html__( 'The complete list', 'safetunnels' ),
				esc_html__( 'Every app the tunnel currently routes, grouped by what it does. Links point to each publisher\'s own download page.', 'safetunnels' )
			);
			?>

			<div class="st-cat-grid">
				<?php foreach ( $st_by_category as $st_category => $st_apps ) : ?>
					<div class="st-cat-card glass">
						<h3 dir="rtl"><?php echo esc_html( $st_category ); ?>
							<span class="st-cat-count"><?php echo esc_html( count( $st_apps ) ); ?></span>
						</h3>
						<ul dir="rtl">
							<?php foreach ( $st_apps as $st_app ) : ?>
								<li>
									<?php if ( $st_app['url'] ) : ?>
										<a href="<?php echo esc_url( $st_app['url'] ); ?>" target="_blank" rel="noopener nofollow"
											<?php echo safetunnels_track_attrs( safetunnels_package_name( $st_app['key'], $st_app['name'] ), $st_app['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
											<?php echo esc_html( $st_app['name'] ); ?>
										</a>
									<?php else : ?>
										<span><?php echo esc_html( $st_app['name'] ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>

			<p class="st-cat-note">
				<?php esc_html_e( 'Missing an app you need? Tell us and we will look at adding it — the catalogue is updated weekly.', 'safetunnels' ); ?>
				<a class="inline-link" href="<?php echo esc_url( safetunnels_mailto( 'App request' ) ); ?>"><?php esc_html_e( 'Request an app', 'safetunnels' ); ?></a>
			</p>
		</div>
	</section>

	<?php get_template_part( 'template-parts/tunnels/cta' ); ?>
</main>

<?php
get_footer();

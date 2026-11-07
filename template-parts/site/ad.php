<?php
/** A responsive display ad; load the shared AdSense script through inc/ads.php. */
defined( 'ABSPATH' ) || exit;

if ( ! safetunnels_ads_enabled() ) {
	return;
}

$st_ad_units = array(
	'home'   => '8781840625',
	'footer' => '3310022097',
);
$st_ad_placement = $args['placement'] ?? '';
if ( ! isset( $st_ad_units[ $st_ad_placement ] ) ) {
	return;
}
?>
<aside class="st-ad st-ad--<?php echo esc_attr( $st_ad_placement ); ?>" aria-label="<?php esc_attr_e( 'Advertisement', 'safetunnels' ); ?>">
	<div class="st-ad__inner">
		<p class="st-ad__label"><?php esc_html_e( 'Advertisement', 'safetunnels' ); ?></p>
		<div class="st-ad__slot">
			<ins class="adsbygoogle"
				style="display:block"
				data-ad-client="ca-pub-2784419629304512"
				data-ad-slot="<?php echo esc_attr( $st_ad_units[ $st_ad_placement ] ); ?>"
				data-ad-format="auto"
				data-full-width-responsive="true"></ins>
		</div>
		<script>
			(window.adsbygoogle = window.adsbygoogle || []).push({});
		</script>
	</div>
</aside>

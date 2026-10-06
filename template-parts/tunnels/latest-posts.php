<?php
/**
 * Latest blog posts.
 *
 * Fresh, internally-linked content on the highest-authority page of the site
 * is the cheapest ranking signal available; the section simply disappears
 * when there are no posts yet.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_posts = get_posts(
	array(
		'numberposts'      => 3,
		'post_status'      => 'publish',
		'suppress_filters' => false,
	)
);

if ( ! $st_posts ) {
	return;
}
?>
<section id="guides">
	<div class="inner">
		<?php
		safetunnels_section_head(
			__( 'Guides', 'safetunnels' ),
			esc_html__( 'From the SafeTunnels Blog', 'safetunnels' ),
			esc_html__( 'Setup walkthroughs, troubleshooting, and what changed in the app.', 'safetunnels' )
		);
		?>

		<div class="st-post-grid">
			<?php foreach ( $st_posts as $st_post ) : ?>
				<article class="st-post-card st-reveal">
					<?php if ( has_post_thumbnail( $st_post ) ) : ?>
						<a class="st-post-thumb" href="<?php echo esc_url( get_permalink( $st_post ) ); ?>" tabindex="-1" aria-hidden="true">
							<?php echo get_the_post_thumbnail( $st_post, 'safetunnels-card', array( 'loading' => 'lazy' ) ); ?>
						</a>
					<?php endif; ?>
					<div class="st-post-body">
						<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $st_post ) ); ?>">
							<?php echo esc_html( get_the_date( '', $st_post ) ); ?>
						</time>
						<h3><a href="<?php echo esc_url( get_permalink( $st_post ) ); ?>"><?php echo esc_html( get_the_title( $st_post ) ); ?></a></h3>
						<p><?php echo esc_html( wp_trim_words( (string) $st_post->post_excerpt ? $st_post->post_excerpt : wp_strip_all_tags( $st_post->post_content ), 22 ) ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
/**
 * Fallback template — also serves the blog index and every archive.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

get_header();

$st_title = __( 'Blog', 'safetunnels' );
$st_sub   = get_bloginfo( 'description' );

if ( is_home() && get_option( 'page_for_posts' ) ) {
	$st_posts_page = (int) get_option( 'page_for_posts' );
	$st_title      = get_the_title( $st_posts_page );
	$st_sub        = (string) get_post_meta( $st_posts_page, '_safetunnels_description', true );
} elseif ( is_category() || is_tag() || is_tax() ) {
	$st_title = single_term_title( '', false );
	$st_sub   = wp_strip_all_tags( (string) term_description() );
} elseif ( is_author() ) {
	$st_title = get_the_author();
	$st_sub   = get_the_author_meta( 'description' );
} elseif ( is_year() || is_month() || is_day() ) {
	$st_title = get_the_archive_title();
	$st_sub   = '';
}
?>

<main id="content">
	<?php
	get_template_part(
		'template-parts/legal/hero',
		null,
		array(
			'eyebrow'  => is_home() ? __( 'Guides &amp; updates', 'safetunnels' ) : __( 'Archive', 'safetunnels' ),
			'title'    => $st_title,
			'subtitle' => $st_sub,
		)
	);
	?>

	<div class="inner lg-content">
		<div class="st-blog-layout">
			<div class="st-blog-main">
				<?php if ( have_posts() ) : ?>
					<div class="st-post-grid">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content/card' );
						endwhile;
						?>
					</div>

					<?php
					the_posts_pagination(
						array(
							'mid_size'  => 1,
							'prev_text' => esc_html__( 'Newer', 'safetunnels' ),
							'next_text' => esc_html__( 'Older', 'safetunnels' ),
						)
					);
					?>
				<?php else : ?>
					<div class="legal-card glass">
						<h2><?php esc_html_e( 'Nothing here yet', 'safetunnels' ); ?></h2>
						<p><?php esc_html_e( 'There are no posts to show. Try the Help Center in the meantime.', 'safetunnels' ); ?></p>
						<div class="legal-cta-row">
							<a class="legal-btn" href="<?php echo esc_url( safetunnels_page_url( 'faq' ) ); ?>">
								<?php safetunnels_icon( 'ti-help-circle' ); ?> <?php esc_html_e( 'Browse the FAQ', 'safetunnels' ); ?>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</main>

<?php
get_footer();

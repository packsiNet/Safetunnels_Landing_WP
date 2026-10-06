<?php
/**
 * Search results.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="content">
	<?php
	get_template_part(
		'template-parts/legal/hero',
		null,
		array(
			'eyebrow'  => __( 'Search', 'safetunnels' ),
			'title'    => sprintf(
				/* translators: %s: search term. */
				__( 'Results for “%s”', 'safetunnels' ),
				get_search_query()
			),
			'subtitle' => sprintf(
				/* translators: %d: result count. */
				_n( '%d match found.', '%d matches found.', (int) $GLOBALS['wp_query']->found_posts, 'safetunnels' ),
				(int) $GLOBALS['wp_query']->found_posts
			),
		)
	);
	?>

	<div class="inner lg-content">
		<div class="st-search-box glass">
			<?php get_search_form(); ?>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="st-post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content/card' );
				endwhile;
				?>
			</div>

			<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
		<?php else : ?>
			<div class="legal-card glass">
				<h2><?php esc_html_e( 'No matches', 'safetunnels' ); ?></h2>
				<p><?php esc_html_e( 'Nothing on the site matched that search. These pages cover most questions:', 'safetunnels' ); ?></p>
				<div class="nf-shortcuts">
					<a href="<?php echo esc_url( safetunnels_page_url( 'faq' ) ); ?>"><?php esc_html_e( 'FAQ', 'safetunnels' ); ?></a>
					<a href="<?php echo esc_url( safetunnels_page_url( 'support' ) ); ?>"><?php esc_html_e( 'Support', 'safetunnels' ); ?></a>
					<a href="<?php echo esc_url( safetunnels_page_url( 'pricing' ) ); ?>"><?php esc_html_e( 'Pricing', 'safetunnels' ); ?></a>
					<a href="<?php echo esc_url( safetunnels_page_url( 'download' ) ); ?>"><?php esc_html_e( 'Download', 'safetunnels' ); ?></a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();

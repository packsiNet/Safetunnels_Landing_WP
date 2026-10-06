<?php
/**
 * Single post.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<main id="content">
		<?php
		get_template_part(
			'template-parts/legal/hero',
			null,
			array(
				'eyebrow'  => get_the_date(),
				'title'    => get_the_title(),
				'subtitle' => get_the_excerpt(),
			)
		);
		?>

		<div class="inner lg-content">
			<div class="st-blog-layout">
				<div class="st-blog-main">
					<article <?php post_class( 'legal-card glass st-prose' ); ?>>
						<div class="st-post-meta">
							<span><?php safetunnels_icon( 'ti-user' ); ?> <?php the_author(); ?></span>
							<span><?php safetunnels_icon( 'ti-clock' ); ?> <?php echo esc_html( safetunnels_reading_time( get_the_content() ) ); ?></span>
							<?php if ( has_category() ) : ?>
								<span><?php safetunnels_icon( 'ti-folder' ); ?> <?php the_category( ', ' ); ?></span>
							<?php endif; ?>
						</div>

						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="st-post-hero"><?php the_post_thumbnail( 'full' ); ?></figure>
						<?php endif; ?>

						<?php the_content(); ?>

						<?php
						wp_link_pages(
							array(
								'before' => '<div class="st-page-links">',
								'after'  => '</div>',
							)
						);
						?>

						<?php if ( has_tag() ) : ?>
							<div class="st-tags"><?php the_tags( '', '' ); ?></div>
						<?php endif; ?>
					</article>

					<nav class="st-post-nav" aria-label="<?php esc_attr_e( 'Post navigation', 'safetunnels' ); ?>">
						<?php
						previous_post_link( '<span class="st-prev">%link</span>', '<i class="ti ti-arrow-left" aria-hidden="true"></i> %title' );
						next_post_link( '<span class="st-next">%link</span>', '%title <i class="ti ti-arrow-right" aria-hidden="true"></i>' );
						?>
					</nav>

					<?php
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					?>
				</div>

				<?php get_sidebar(); ?>
			</div>
		</div>
	</main>

	<?php
endwhile;

get_footer();

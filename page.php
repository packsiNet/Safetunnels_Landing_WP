<?php
/**
 * Generic page — used for any page the administrator adds that is not one of
 * the theme's registry pages.
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
				'title'    => get_the_title(),
				'subtitle' => (string) get_post_meta( get_the_ID(), '_safetunnels_description', true ),
			)
		);
		?>

		<div class="inner lg-content">
			<div class="legal-card glass st-prose">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<div class="st-page-links">',
						'after'  => '</div>',
					)
				);
				?>
			</div>
		</div>
	</main>

	<?php
endwhile;

get_footer();

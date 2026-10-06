<?php
/**
 * Post card used in archives.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'st-post-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="st-post-thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'safetunnels-card', array( 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>

	<div class="st-post-body">
		<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p><?php echo esc_html( get_the_excerpt() ); ?></p>
		<a class="st-post-more" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read more', 'safetunnels' ); ?> <?php safetunnels_icon( 'ti-arrow-right' ); ?>
		</a>
	</div>
</article>

<?php
/**
 * Site footer, floating actions and the shared modals.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$safetunnels_shell = safetunnels_shell();
?>
		<?php get_template_part( 'template-parts/site/footer' ); ?>
	</div><!-- .page -->

	<?php
	if ( 'tn' === $safetunnels_shell ) {
		get_template_part( 'template-parts/site/modal-review' );
		get_template_part( 'template-parts/site/modal-purchase' );
	}

	get_template_part( 'template-parts/site/back-to-top' );
	?>
</div><!-- shell -->

<?php wp_footer(); ?>
</body>
</html>

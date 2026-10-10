<?php
/**
 * Effective-date / version chips shown on the policy pages.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_version        = isset( $args['version'] ) ? $args['version'] : '';
$st_updated        = isset( $args['updated'] ) ? $args['updated'] : '';
$st_show_effective = ! isset( $args['show_effective'] ) || $args['show_effective'];
$st_format_date    = ! isset( $args['format_date'] ) || $args['format_date'];
?>
<div class="legal-meta-bar">
	<?php if ( $st_show_effective ) : ?>
	<span class="legal-chip">
		<?php safetunnels_icon( 'ti-calendar-event' ); ?>
		<?php
		printf(
			/* translators: %s: date. */
			esc_html__( 'Effective %s', 'safetunnels' ),
			esc_html( safetunnels_format_date( safetunnels_opt( 'legal_effective' ) ) )
		);
		?>
	</span>
	<?php endif; ?>

	<?php if ( $st_updated ) : ?>
		<span class="legal-chip">
			<?php safetunnels_icon( 'ti-refresh' ); ?>
			<?php
			printf(
				/* translators: %s: date. */
				esc_html__( 'Last updated %s', 'safetunnels' ),
				esc_html( $st_format_date ? safetunnels_format_date( $st_updated ) : $st_updated )
			);
			?>
		</span>
	<?php endif; ?>

	<?php if ( $st_version ) : ?>
		<span class="legal-chip">
			<?php safetunnels_icon( 'ti-git-branch' ); ?>
			<?php
			printf(
				/* translators: %s: version number. */
				esc_html__( 'Version %s', 'safetunnels' ),
				esc_html( $st_version )
			);
			?>
		</span>
	<?php endif; ?>
</div>

<?php
/**
 * Template Name: SafeTunnels — Contact
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
			'eyebrow'  => __( 'Get in Touch', 'safetunnels' ),
			'title'    => __( 'Contact Us', 'safetunnels' ),
			'subtitle' => sprintf(
				/* translators: 1: developer, 2: app name. */
				__( 'Have a question about %1$s and %2$s? Pick a reason below or write to us directly.', 'safetunnels' ),
				safetunnels_opt( 'developer_name' ),
				safetunnels_opt( 'app_name' )
			),
		)
	);
	?>

	<div class="inner lg-content">
		<div class="contact-grid">
			<div class="contact-card glass">
				<div class="contact-row">
					<div class="contact-ico"><?php safetunnels_icon( 'ti-building' ); ?></div>
					<div>
						<div class="contact-label"><?php esc_html_e( 'Developer', 'safetunnels' ); ?></div>
						<div class="contact-value"><?php echo esc_html( safetunnels_opt( 'developer_name' ) ); ?></div>
					</div>
				</div>

				<div class="contact-row">
					<div class="contact-ico"><?php safetunnels_icon( 'ti-world' ); ?></div>
					<div>
						<div class="contact-label"><?php esc_html_e( 'Website', 'safetunnels' ); ?></div>
						<div class="contact-value">
						<a href="<?php echo esc_url( safetunnels_page_url( 'home' ) ); ?>"><?php echo esc_html( safetunnels_page_url( 'home' ) ); ?></a>
						</div>
					</div>
				</div>

				<div class="contact-row">
					<div class="contact-ico"><?php safetunnels_icon( 'ti-mail' ); ?></div>
					<div>
						<div class="contact-label"><?php esc_html_e( 'Email', 'safetunnels' ); ?></div>
						<div class="contact-value">
							<a href="<?php echo esc_url( safetunnels_mailto( 'SafeTunnels Inquiry' ) ); ?>">
								<?php echo esc_html( safetunnels_opt( 'support_email' ) ); ?>
							</a>
						</div>
					</div>
				</div>

				<div class="response-note">
					<?php safetunnels_icon( 'ti-clock' ); ?>
					<?php esc_html_e( 'We aim to respond to all inquiries within 48 hours.', 'safetunnels' ); ?>
				</div>
			</div>

			<div class="contact-reasons">
				<?php foreach ( safetunnels_contact_reasons() as $reason ) : ?>
					<a class="reason-card" href="<?php echo esc_url( safetunnels_mailto( $reason['subject'] ) ); ?>">
						<div class="reason-ico"><?php safetunnels_icon( $reason['icon'] ); ?></div>
						<div class="reason-body">
							<h2><?php echo esc_html( $reason['title'] ); ?></h2>
							<p><?php echo esc_html( $reason['desc'] ); ?></p>
						</div>
						<?php safetunnels_icon( 'ti-chevron-right' ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

		<?php
		// Anything typed into the page editor — a contact form shortcode, for
		// instance — renders below the cards.
		$content = get_the_content( null, false, get_queried_object_id() );

		if ( trim( wp_strip_all_tags( (string) $content ) ) ) :
			?>
			<div class="st-prose glass spaced">
				<?php echo apply_filters( 'the_content', $content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<?php
		endif;
		?>
	</div>
</main>

<?php
get_footer();

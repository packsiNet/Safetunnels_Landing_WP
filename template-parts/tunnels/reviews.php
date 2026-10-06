<?php
/**
 * User reviews.
 *
 * Fetched on the server (cached) rather than in the browser, so the review
 * text and the aggregate rating are present in the initial HTML — which is
 * what makes the AggregateRating schema legitimate.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_reviews = safetunnels_get_reviews( 1, 9 );
$st_items   = isset( $st_reviews['items'] ) ? (array) $st_reviews['items'] : array();
$st_avg     = (float) $st_reviews['averageRating'];
$st_total   = (int) $st_reviews['totalCount'];
$st_colors  = safetunnels_avatar_colors();
?>
<section id="reviews">
	<div class="inner">
		<?php
		safetunnels_section_head(
			__( 'User Reviews', 'safetunnels' ),
			esc_html__( 'Iranians Around the World Say', 'safetunnels' )
		);
		?>

		<div class="reviews-header">
			<div class="reviews-agg">
				<div class="agg-num"><?php echo esc_html( $st_avg > 0 ? number_format_i18n( $st_avg, 1 ) : '—' ); ?></div>
				<div class="agg-detail">
					<?php safetunnels_stars( $st_avg ); ?>
					<div class="agg-count">
						<?php
						if ( $st_total > 0 ) {
							printf(
								/* translators: %s: review count. */
								esc_html( _n( 'Based on %s review', 'Based on %s reviews', $st_total, 'safetunnels' ) ),
								esc_html( number_format_i18n( $st_total ) )
							);
						} else {
							esc_html_e( 'Be the first to comment.', 'safetunnels' );
						}
						?>
					</div>
				</div>
			</div>

			<button type="button" class="write-review-btn" data-st-open="review">
				<?php safetunnels_icon( 'ti-pencil-plus' ); ?>
				<?php esc_html_e( 'Write a Review', 'safetunnels' ); ?>
			</button>
		</div>

		<?php if ( $st_items ) : ?>
			<div class="reviews-grid" id="st-reviews-grid">
				<?php foreach ( $st_items as $st_index => $st_review ) : ?>
					<?php
					$st_color   = $st_colors[ $st_index % count( $st_colors ) ];
					$st_initial = mb_substr( trim( (string) $st_review['fullName'] ), 0, 1 );
					$st_flag    = safetunnels_country_flag( isset( $st_review['countryCode'] ) ? $st_review['countryCode'] : '' );
					?>
					<article class="rev-card">
						<div class="rev-stars">
							<?php for ( $st_n = 1; $st_n <= 5; $st_n++ ) : ?>
								<i class="ti <?php echo $st_n <= (int) $st_review['rating'] ? 'ti-star-filled' : 'ti-star'; ?>" aria-hidden="true"></i>
							<?php endfor; ?>
							<span class="screen-reader-text">
								<?php
								printf(
									/* translators: %d: rating out of five. */
									esc_html__( 'Rated %d out of 5', 'safetunnels' ),
									(int) $st_review['rating']
								);
								?>
							</span>
						</div>

						<?php if ( ! empty( $st_review['comment'] ) ) : ?>
							<p class="rev-text">&ldquo;<?php echo esc_html( $st_review['comment'] ); ?>&rdquo;</p>
						<?php endif; ?>

						<div class="rev-author">
							<div class="rev-avatar" style="background: <?php echo esc_attr( $st_color ); ?>">
								<?php echo esc_html( $st_initial ); ?>
							</div>
							<div>
								<div class="rev-name"><?php echo esc_html( $st_review['fullName'] ); ?></div>
								<div class="rev-loc">
									<?php if ( $st_flag ) : ?>
										<span class="rev-flag"><?php echo esc_html( $st_flag ); ?></span>
									<?php endif; ?>
									<?php echo esc_html( isset( $st_review['countryCode'] ) ? $st_review['countryCode'] : '' ); ?>
								</div>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="reviews-empty" id="st-reviews-grid">
				<?php safetunnels_icon( 'ti-message-off' ); ?>
				<p><?php esc_html_e( 'No comments yet. Be the first!', 'safetunnels' ); ?></p>
				<button type="button" class="write-review-btn" data-st-open="review">
					<?php safetunnels_icon( 'ti-pencil-plus' ); ?>
					<?php esc_html_e( 'Register the first comment', 'safetunnels' ); ?>
				</button>
			</div>
		<?php endif; ?>
	</div>
</section>

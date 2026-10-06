<?php
/**
 * Small rendering helpers shared by the templates.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

/**
 * Prints a Tabler icon.
 *
 * @param string $name  Icon name without the `ti-` prefix is not accepted —
 *                      pass the full name, e.g. `ti-shield-lock`.
 * @param string $class Extra classes.
 * @return void
 */
function safetunnels_icon( $name, $class = '' ) {
	printf(
		'<i class="ti %s%s" aria-hidden="true"></i>',
		esc_attr( $name ),
		$class ? ' ' . esc_attr( $class ) : ''
	);
}

/**
 * The animated background used by both shells.
 *
 * @return void
 */
function safetunnels_stage() {
	?>
	<div class="stage" aria-hidden="true">
		<div class="blob b1"></div>
		<div class="blob b2"></div>
		<div class="blob b3"></div>
		<div class="blob b4"></div>
		<div class="stars"></div>
		<div class="grain"></div>
	</div>
	<?php
}

/**
 * Attributes for a tracked download link.
 *
 * Every download anchor carries the package name so the click reaches the
 * same `/api/stats/download-click` endpoint the React app posted to.
 *
 * @param string $package Package name.
 * @param string $label   Human label, used when there is no package key.
 * @return string
 */
function safetunnels_track_attrs( $package, $label = '' ) {
	return sprintf(
		' data-st-track="download" data-st-package="%s" data-st-label="%s"',
		esc_attr( $package ),
		esc_attr( $label )
	);
}

/**
 * Renders a five-star row.
 *
 * @param float $rating       Rating value.
 * @param bool  $allow_halves Render a half star.
 * @return void
 */
function safetunnels_stars( $rating, $allow_halves = true ) {
	$full = (int) floor( $rating );
	$half = $allow_halves && ( $rating - $full ) >= 0.5;

	echo '<div class="stars-row">';
	for ( $n = 1; $n <= 5; $n++ ) {
		if ( $n <= $full ) {
			$class = 'ti-star-filled';
		} elseif ( $half && $n === $full + 1 ) {
			$class = 'ti-star-half-filled';
		} else {
			$class = 'ti-star';
		}
		printf( '<i class="ti %s" aria-hidden="true"></i>', esc_attr( $class ) );
	}
	echo '</div>';
}

/**
 * Avatar colours, cycled per review — matches AVATAR_COLORS in the React app.
 *
 * @return string[]
 */
function safetunnels_avatar_colors() {
	return array( '#34D399', '#7DD3FC', '#A855F7', '#FB923C', '#F472B6', '#FBBF24' );
}

/**
 * Renders the breadcrumb trail. Hidden on the front page, where the only
 * crumb would be "Home".
 *
 * @return void
 */
function safetunnels_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	$trail = safetunnels_breadcrumb_trail();
	$last  = count( $trail ) - 1;

	echo '<nav class="st-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'safetunnels' ) . '"><ol>';

	foreach ( $trail as $index => $crumb ) {
		echo '<li>';
		if ( $index === $last ) {
			echo '<span aria-current="page">' . esc_html( $crumb['name'] ) . '</span>';
		} else {
			echo '<a href="' . esc_url( $crumb['url'] ) . '">' . esc_html( $crumb['name'] ) . '</a>';
			echo '<i class="ti ti-chevron-right" aria-hidden="true"></i>';
		}
		echo '</li>';
	}

	echo '</ol></nav>';
}

/**
 * Opens the page shell: background, skip link target and the sticky nav.
 *
 * @param string $shell `tn` or `lg`.
 * @return void
 */
function safetunnels_open_shell( $shell = '' ) {
	$shell = $shell ? $shell : safetunnels_shell();
	$class = 'tn' === $shell ? 'tn-page' : 'lg-page';

	echo '<div class="' . esc_attr( $class ) . '">';
	safetunnels_stage();
	echo '<div class="page">';

	get_template_part( 'template-parts/site/nav', null, array( 'shell' => $shell ) );
}

/**
 * Closes the page shell and prints the footer.
 *
 * @return void
 */
function safetunnels_close_shell() {
	get_template_part( 'template-parts/site/footer' );
	echo '</div>';
	get_template_part( 'template-parts/site/back-to-top' );
	echo '</div>';
}

/**
 * A section heading block (kicker + H2 + sub).
 *
 * @param string $label Kicker.
 * @param string $title Heading.
 * @param string $sub   Optional sub-heading.
 * @param string $tag   Heading tag.
 * @return void
 */
function safetunnels_section_head( $label, $title, $sub = '', $tag = 'h2' ) {
	$tag = in_array( $tag, array( 'h1', 'h2', 'h3' ), true ) ? $tag : 'h2';

	if ( $label ) {
		echo '<div class="sec-label">' . esc_html( $label ) . '</div>';
	}

	printf( '<%1$s class="sec-title">%2$s</%1$s>', esc_attr( $tag ), wp_kses_post( $title ) );

	if ( $sub ) {
		echo '<p class="sec-sub">' . wp_kses_post( $sub ) . '</p>';
	}
}

/**
 * Formats an ISO date the way the legal pages display it.
 *
 * @param string $date ISO date.
 * @return string
 */
function safetunnels_format_date( $date ) {
	$time = strtotime( $date );

	return $time ? date_i18n( get_option( 'date_format' ), $time ) : $date;
}

/**
 * Estimated reading time for the current post.
 *
 * @param string $content Content.
 * @return string
 */
function safetunnels_reading_time( $content ) {
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$minutes = max( 1, (int) ceil( $words / 220 ) );

	/* translators: %d: minutes. */
	return sprintf( _n( '%d min read', '%d min read', $minutes, 'safetunnels' ), $minutes );
}

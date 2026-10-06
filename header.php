<?php
/**
 * Document head, page shell and sticky navigation.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$safetunnels_shell = safetunnels_shell();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'safetunnels' ); ?></a>

<div class="<?php echo 'tn' === $safetunnels_shell ? 'tn-page' : 'lg-page'; ?>">
	<?php safetunnels_stage(); ?>

	<div class="page">
		<?php get_template_part( 'template-parts/site/nav', null, array( 'shell' => $safetunnels_shell ) ); ?>

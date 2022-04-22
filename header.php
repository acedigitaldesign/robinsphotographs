<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<!doctype html>
<html <?php language_attributes(); ?> data-javascript-disabled><?php // <- if JS enabled, 'javascript-notice.js' removes this ?>
<head>

<!-- Google Tag Manager -->

<!-- End Google Tag Manager -->

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=0"/>
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php 
	// Remember to change these preloads depending on the theme
	// Adding icons to preload seems to prevent weird characters sometimes appearing in place of icons
	?>
	<link rel="preload" as="font" href="<?php echo THEME_FONTS . 'ace-icons/ace-icons.woff'; ?>" type="font/woff" crossorigin="anonymous">
	<link rel="preload" as="font" href="<?php echo THEME_FONTS . 'nunito/Nunito-Regular.woff2'; ?>" type="font/woff2" crossorigin="anonymous">
	<link rel="preload" as="font" href="<?php echo THEME_FONTS . 'literata/Literata-SemiBold.woff2'; ?>" type="font/woff2" crossorigin="anonymous">

	<input type="text" value="0" data-backbutton-state style="display:none;" />
	<?php wp_head(); ?>
</head>


<body <?php body_class(); ?>>

	<noscript>
		<?php get_template_part('template-parts/javascript-notice'); ?>
	</noscript>

	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'robinsphotographs' ); ?></a>

		<?php get_template_part('template-parts/cookie-notice'); ?>
		<?php get_template_part('template-parts/popup-signup'); ?>

		<?php // get_template_part('template-parts/resource-popup'); // <- instantiates only if set to true in the post?>


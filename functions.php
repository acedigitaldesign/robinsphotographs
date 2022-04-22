<?php
/******************************************
Functions
******************************************/ 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Core
require get_template_directory() . '/inc/core/wp-defaults.php';
require get_template_directory() . '/inc/core/core-utilities.php';
require get_template_directory() . '/inc/core/core-components.php';
require get_template_directory() . '/inc/core/core-shortcodes.php';
require get_template_directory() . '/inc/core/enqueue-scripts-styles.php';


// Theme 
require get_template_directory() . '/inc/theme/class-footnotes.php';
require get_template_directory() . '/inc/theme/theme-utilities.php';
require get_template_directory() . '/inc/theme/theme-components.php';
require get_template_directory() . '/inc/theme/theme-post-types.php';
require get_template_directory() . '/inc/theme/theme-taxonomies.php';
require get_template_directory() . '/inc/theme/theme-shortcodes.php';

require get_template_directory() . '/inc/theme/woo-customs-cart.php';
require get_template_directory() . '/inc/theme/woocommerce-customisation.php';
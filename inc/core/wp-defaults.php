<?php
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @package Cartoon Kids
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'robinsphotographs_setup' ) ) :
	/**
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function robinsphotographs_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on this theme, change the slug used for the
		 * theme text domain in the function at the top of 'template-functions.php'. This 
		 * will change the text domain anywhere the 'theme_text_domain' function is used.
		 */
		load_theme_textdomain( 'robinsphotographs', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'robinsphotographs_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'robinsphotographs_setup' );



/******************************************
Content Width
******************************************/ 
function theme_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'theme_content_width', 600 );
}
add_action( 'after_setup_theme', 'theme_content_width', 0 );



/******************************************
Register default menus
******************************************/ 
function robinsphotographs_register_nav_menus() {
	register_nav_menus( array(
		'main-menu' => esc_html__( 'Main menu', 'robinsphotographs' ),
		'mobile-menu' => esc_html__( 'Mobile menu', 'robinsphotographs' )
	) );
}
add_action( 'after_setup_theme', 'robinsphotographs_register_nav_menus' );



/******************************************
Add custom class to registered menu items
******************************************/ 
function add_additional_class_on_li($classes, $item, $args) {
  if($args->add_li_class) {
      $classes[] = $args->add_li_class;
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'add_additional_class_on_li', 1, 3);



/******************************************
Initializes default sidebar
******************************************/ 
function theme_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'cartoonkids' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'cartoonkids' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'theme_widgets_init' );



/******************************************
Backend preferences
******************************************/ 
// Disables Gutenberg editor
// add_filter('use_block_editor_for_post', '__return_false', 10);

//Fixes auto html formatting in text editor
// remove_filter ('the_content', 'wpautop');

// Removes visual editor for all users
// add_filter( 'user_can_richedit' , '__return_false', 50 );

// Ascertains the current login in user role and appends it to body class in order to target css at specific user roles in wp admin area.
function user_role_admin_body_class( $classes ) {
  global $current_user;
  foreach( $current_user->roles as $role )
      $classes .= ' role-' . $role;
  return trim( $classes );
}
add_filter( 'admin_body_class', 'user_role_admin_body_class' );



// Add media sizes here to be removed
function remove_default_image_sizes( $sizes) {
  // unset( $sizes['large']); // Added to remove 1024
  // unset( $sizes['thumbnail']);
  // unset( $sizes['medium']);
  unset( $sizes['medium_large']);
  unset( $sizes['1536x1536']);
  unset( $sizes['2048x2048']);
  return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'remove_default_image_sizes');

// add_image_size( 'small', 300, 0 );




// show_admin_bar( true );


/******************************************
Deregister embed scripts
- stops another unnecessary script being loaded
- think it's something to do with rendering embeds like youtube videos in content
******************************************/ 
function my_deregister_scripts(){
	wp_dequeue_script( 'wp-embed' );
 }
 add_action( 'wp_footer', 'my_deregister_scripts' );




/******************************************
Disable Emojis
- stops another unnecessary script being loaded
- prevents emoji display (though think browser still renders, which is fine...)
******************************************/ 
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
 }
 add_action( 'init', 'disable_emojis' );
 
 /**
	* Filter function used to remove the tinymce emoji plugin.
	* 
	* @param array $plugins 
	* @return array Difference betwen the two arrays
	*/
 function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
	return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
	return array();
	}
 }
 
 /**
	* Remove emoji CDN hostname from DNS prefetching hints.
	*
	* @param array $urls URLs to print for resource hints.
	* @param string $relation_type The relation type the URLs are printed for.
	* @return array Difference betwen the two arrays.
	*/
 function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' == $relation_type ) {
	/** This filter is documented in wp-includes/formatting.php */
	$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
 
 $urls = array_diff( $urls, array( $emoji_svg_url ) );
	}
 
 return $urls;
 }



 /* EXTEND SUBNAV
******************************************/

class submenu_wrap extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat("\t", $depth);
			$output .= "\n$indent<div class='sub-menu'><ul class='sub-menu__list'>\n";
	}
	function end_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat("\t", $depth);
			$output .= "$indent</ul></div>\n";
	}
}


/**
 * Disable WooCommerce block styles (front-end).
 */
function themesharbor_disable_woocommerce_block_styles() {
  wp_dequeue_style( 'wc-blocks-style' );
}
add_action( 'wp_enqueue_scripts', 'themesharbor_disable_woocommerce_block_styles' );

 /**
	* Limit revisions
	* 
	* Source: http://wp-functions.com/wp-admin-functions/limit-wordpress-post-revisions/
	*/
// if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', 5);z
// if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', false);


 /**
	* Limit WP Blocks (speed up page load)
	* 
	* Source: https://rudrastyh.com/gutenberg/remove-woocommerce-blocks.html
	*/
// add_filter( 'allowed_block_types', 'misha_allowed_blocks' );

// function misha_allowed_blocks( $allowed_blocks ) {

// 	$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

//  	// specify all the blocks you would like to disable here
// 	unset( $registered_blocks[ 'woocommerce/all-reviews' ] );
// 	unset( $registered_blocks[ 'woocommerce/featured-category' ] );
// 	unset( $registered_blocks[ 'woocommerce/featured-product' ] );
// 	unset( $registered_blocks[ 'woocommerce/handpicked-products' ] );
// 	unset( $registered_blocks[ 'woocommerce/product-best-sellers' ] );
// 	unset( $registered_blocks[ 'woocommerce/product-categories' ] );
// 	unset( $registered_blocks[ 'woocommerce/product-category' ] );
// 	unset( $registered_blocks[ 'woocommerce/product-new' ] );
// 	unset( $registered_blocks[ 'woocommerce/product-on-sale' ] );
// 	unset( $registered_blocks[ 'woocommerce/products-by-attribute' ] );
// 	unset( $registered_blocks[ 'woocommerce/product-top-rated' ] );
// 	unset( $registered_blocks[ 'woocommerce/reviews-by-product' ] );
// 	unset( $registered_blocks[ 'woocommerce/reviews-by-category' ] );
// 	unset( $registered_blocks[ 'woocommerce/product-search' ] );
// 	unset( $registered_blocks[ 'woocommerce/product-tag' ] );
// 	unset( $registered_blocks[ 'woocommerce/all-products' ] );
// 	unset( $registered_blocks[ 'woocommerce/price-filter' ] );
// 	unset( $registered_blocks[ 'woocommerce/attribute-filter' ] );
// 	unset( $registered_blocks[ 'woocommerce/active-filters' ] );
// 	// unset( $registered_blocks[ 'woocommerce/active-filters' ] );


// 	// now $registered_blocks contains only blocks registered by plugins, but we need keys only
// 	$registered_blocks = array_keys( $registered_blocks );

// 	// merge the whitelist with plugins blocks
// 	$allowed_blocks = array_merge( 
// 		array(
// 			'core/image',
// 			'core/paragraph',
// 			'core/heading',
// 			'core/list',
// 		),
// 		$registered_blocks 
// 	);

// 	return $allowed_blocks;

// }


// add_filter( 'allowed_block_types', 'misha_allowed_block_types' );
 
// function misha_allowed_block_types( $allowed_blocks ) {
 
// 	return array(
// 		'core/image',
// 		'core/paragraph',
// 		'core/heading',
// 		'core/list'
// 	);
 
// }
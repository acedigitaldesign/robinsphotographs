<?php
/**
 * Enqueue scripts and styles.
 *
 * @package Cartoon Kids
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function theme_scripts() {

  // Create version codes based on the time of the files last update.
  // So will use cached version unless version is different (ie. if the file has changed)
  // Source: https://developer.wordpress.org/reference/functions/wp_enqueue_script/
  $my_css_version = date("ymd-Gis", filemtime( get_template_directory() . '/assets/dist/css/style.css' ));
  $my_js_version = date("ymd-Gis", filemtime( get_template_directory() . '/assets/dist/js/app-min.js' ));

	wp_enqueue_style( 'theme-style', get_template_directory_uri() . '/assets/dist/css/style.css', $my_css_version );
	wp_enqueue_script( 'app-js', get_template_directory_uri() . '/assets/dist/js/app-min.js', array ('jquery'), $my_js_version, true );
	// wp_localize_script('app-js', 'ajax_object', array( 
	// 	'ajax_url' => admin_url( 'admin-ajax.php' ),
  //   'ajax_filter_security' => wp_create_nonce( 'ajax-filter-submission-security-nonce' )
	// ));

  // wp_enqueue_script( 'facebook-javascript-sdk', 'https://connect.facebook.net/en_US/sdk.js', array(), null );
  wp_enqueue_script( 'connectbox', 'https://www.islonline.com/static/v7/scripts/islonline-join.jquery.min.js', array('jquery'), null );

  // mailerlite
  wp_enqueue_script( 'mailerlite', 'https://static.mailerlite.com/js/w/webforms.min.js?v0c75f831c56857441820dcec3163967c', array('jquery'), null );


	// Comment reply form - When click reply, instantiates the comment form beneath the comment
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}


  // Enqueue any custom styles and scripts specified in posts/pages
  $custom_style = esc_html(get_field('custom_style'));
  $custom_script = esc_html(get_field('custom_script'));

  if ( $custom_style ) {
    $post_type = get_post_type_object(get_post_type());
    $post_type_label = strtolower($post_type->label);
    $custom_stylesheet = get_template_directory_uri() . '/assets/dist/css/' . $post_type_label . '/' . $custom_style;
    
    if ( check_file_exists($custom_stylesheet) ) {
      wp_enqueue_style( 'custom-style', $custom_stylesheet);
    }

  }

  if ( $custom_script ) {
    $post_type = get_post_type_object(get_post_type());
    $post_type_label = strtolower($post_type->label);
    $custom_script = get_template_directory_uri() . '/assets/dist/js/' . $post_type_label . '/' . $custom_script;

    if ( check_file_exists($custom_script) ) {
      wp_enqueue_script( 'custom-script', $custom_script);
      wp_localize_script('custom-script', 'customScriptVars', array(
        "Colour" => "Color",
        "colour" => "colour",
        "colourise" => "colorize",
      ));
    }
  
  }


	// Pagespeed improvement: Remove Gutenberg Block Library CSS from loading on the frontend 
	// wp_dequeue_style( 'wp-block-library' );
	// wp_dequeue_style( 'wp-block-library-theme' );
	// wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS


	// Pagespeed improvement: Move jQuery to footer (to prevent render blocking)
	wp_deregister_script('jquery'); // <- unregister the jQuery at first
	wp_register_script('jquery', includes_url('/js/jquery/jquery.js'), false, null, true);  // <-register to footer (the last function argument should be true)
	wp_enqueue_script('jquery');  
	
	// Localise
  
}
add_action( 'wp_enqueue_scripts', 'theme_scripts' );


// Admin Styles
function load_admin_style() {
	// Create version codes based on the time of the files last update.
  // So will use cached version unless version is different (ie. if the file has changed)
  // Source: https://developer.wordpress.org/reference/functions/wp_enqueue_script/
  $admin_css_version = date("ymd-Gis", filemtime( get_template_directory() . '/assets/dist/css/style-admin.css' ));
  wp_enqueue_script( 'admin-js', get_template_directory_uri() . '/assets/dist/js/app-admin-min.js', array ('jquery'), 1.0, true );

  wp_enqueue_style( 'admin-style', get_template_directory_uri() . '/assets/dist/css/style-admin.css', $admin_css_version );
	
  wp_localize_script('admin-js', 'adminScriptVars', array( 
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'content_verification_security' => wp_create_nonce("aj_content_verification_nonce"),
    'theme_prefix' => THEME_PREFIX, // <- specified so JS can display correct UID prefix in 'assign-universal-id-context.js'. Defined in core-utilities.php.
    'alert_error_500' => __('Error: Please try again in a few minutes.', 'robinsphotographs'),
    'alert_error_timeout' => __('Timed Out: Please try again in a few minutes.', 'robinsphotographs'),
    'alert_error_409' => __('Woof Woof Woof! Suspicious activity detected... Please try again in a few minutes.', 'robinsphotographs'),
    'alert_error_403' => __('Access to the server is currently denied. Please try again in a few minutes.', 'robinsphotographs'),
    'alert_error_invalid_security' => __('Error: Please refresh the page or logging in again and try again.', 'robinsphotographs'),
    'alert_success' => __( 'Success!', 'robinsphotographs')
	));
}
add_action( 'admin_enqueue_scripts', 'load_admin_style' );


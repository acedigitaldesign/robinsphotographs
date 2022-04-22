<?php 
/******************************************
Register Custom Post Types
******************************************/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Testimonials
add_action( 'init', 'create_testimonials_cpt' );

function create_testimonials_cpt() {
    $labels = array(
      'name' => __( "Testimonials" ),
      'singular_name' => __( "Testimonial" )
    );
    $args = array(
      'labels' => $labels,
      'supports' => array( 'title', 'revisions', 'custom-fields'),
      'hierarchical' => true,
      'public' => true,
      'has_archive' => false,
      // 'capability_type' => 'page',
      'menu_icon' => 'dashicons-format-quote',
      'show_in_rest' => true
      
    );

    register_post_type( "testimonial", $args);
}

// FAQs 
add_action( 'init', 'create_faqs_cpt' );

function create_faqs_cpt() {
    $labels = array(
      'name' => __( "FAQs" ),
      'singular_name' => __( "FAQ" )
    );
    $args = array(
      'labels' => $labels,
      'supports' => array( 'title', 'editor', 'revisions', 'custom-fields'),
      'hierarchical' => true,
      'public' => true,
      'has_archive' => false,
      // 'capability_type' => 'page',
      'menu_icon' => 'dashicons-editor-help',
      'show_in_rest' => true
      
    );

    register_post_type( "faq", $args);
}


// Resources
add_action( 'init', 'create_resources_cpt' );

function create_resources_cpt() {
    $labels = array(
      'name' => __( "Resources" ),
      'singular_name' => __( "Resource" )
    );
    $args = array(
      'labels' => $labels,
      'supports' => array(  'title', 'revisions', 'thumbnail', 'custom-fields'),
      'hierarchical' => true,
      'public' => true,
      'has_archive' => false,
      // 'capability_type' => 'page',
      'menu_icon' => 'dashicons-admin-links',
      'show_in_rest' => true
      
    );

    register_post_type( "resource", $args);
}


// In Admin Dashboard, order FAQs by date rather than title
function wpse_81939_post_types_admin_order( $wp_query ) {
  if (is_admin()) {

    // Get the post type from the query
    $post_type = $wp_query->query['post_type'];

    if ( $post_type == 'faq') {

      $wp_query->set('orderby', 'date');

      $wp_query->set('order', 'ASC');
    }
  }
}
add_filter('pre_get_posts', 'wpse_81939_post_types_admin_order');
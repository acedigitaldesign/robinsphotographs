<?php
/******************************************
Theme Utilities
******************************************/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


add_filter('body_class', 'custom_body_class');
function custom_body_class($classes) {
    if (is_page(148)) // <- Start a session
        $classes[] = 'start-session';
    return $classes;
}

/******************************************
Register default menus
******************************************/ 
function footer_register_nav_menus() {
	register_nav_menus( array(
		'footer-menu' => esc_html__( 'Footer menu', 'robinsphotographs' ),
	) );
}
add_action( 'after_setup_theme', 'footer_register_nav_menus' );




/**
 * Selective disabling of block editor
 */
add_filter('use_block_editor_for_post_type', 'prefix_disable_gutenberg', 10, 2);
function prefix_disable_gutenberg($current_status, $post_type)
{
        // post type keys
		$exclusions = array('faq', 'resource', 'testimonial');

		for ( $i = 0; $i < count($exclusions); $i++ ) {
			if ($post_type === $exclusions[$i] ) return false;
			return $current_status;
		}
}



/**
 * Add UID column to posts dashboard
 * Remove author column
 */
add_filter( 'manage_posts_columns', 'set_posts_columns' );
function set_posts_columns($columns) {
	unset($columns['author']);
	unset($columns['tags']);
	unset($columns['comments']);
	$columns['uid'] = __( 'UID', 'robinsphotographs' );
	return $columns;
}


/**
 * Move UID column first
 */
add_filter('manage_posts_columns', 'column_order');
function column_order($columns) {
  $n_columns = array();
  $before = 'categories'; // move before this
 
  foreach($columns as $key => $value) {
    if ($key==$before){
      $n_columns['uid'] = '';
    }
    $n_columns[$key] = $value;
  }
  return $n_columns;
}

/**
 * Set UID in UID column
 */
add_action('manage_posts_custom_column', 'set_uid_columns_content', 10, 2);
function set_uid_columns_content($column_name) {
	$prefix = THEME_PREFIX;
	$post_type = get_post_type_object(get_post_type());
	$post_type_name = esc_html($post_type->labels->singular_name);
	$uid_field = get_field( "uid" );

  if ( $column_name == 'uid' && $uid_field ) {
    echo $prefix . '-' . $post_type_name . '-' . $uid_field;
  }
}
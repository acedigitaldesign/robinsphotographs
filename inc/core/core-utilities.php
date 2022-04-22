<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/******************************************
Theme global variables
* When initializing a new theme, set theme name in WP 'General Settings'
******************************************/
$directoryName = get_template_directory();

$themeTitle =  get_bloginfo( 'name' ); // <- Set in backend General Settings
$themeSlug = substr($directoryName, strrpos( $directoryName, '/') + 1); // <- Derived from the name of the active theme directory

define('SITE_TITLE',  get_bloginfo( 'name' )); // <- Set in backend General Settings
define('THEME_SLUG',  substr($directoryName, strrpos( $directoryName, '/') + 1)); // <- Derived from the name of the active theme directory
define('THEME_PREFIX', 'RP'); // <- Used as prefix on theme files and on UID and page/post content files. Must be accurate else content won't load content
define('CONTACT_PHONE', '01751 432142');
define('CONTACT_EMAIL', 'support@robinsphotographs.com'); // <- Used in various places eg. Cookies and Privacy Policy. Not using admin email because thats usually mine on client sites
define('CONTACT_FACEBOOK', 'robinsphotographsLtd'); // <- Used in various places eg. Cookies and Privacy Policy. Not using admin email because thats usually mine on client sites

define('THEME_DIR',    '/wp-content/themes/' . THEME_SLUG);
define('THEME_CSS',    THEME_DIR . '/assets/dist/scss/');
define('THEME_IMAGES', THEME_DIR . '/assets/dist/images/');
define('THEME_JS',     THEME_DIR . '/assets/dist/js/');
define('THEME_FONTS',  THEME_DIR . '/assets/dist/fonts/');

$themeDirectory = '/wp-content/themes/' . THEME_SLUG;
$themeDirectory__css = $themeDirectory . '/assets/dist/scss/';
$themeDirectory__images = $themeDirectory . '/assets/dist/images/';
$themeDirectory__js = $themeDirectory . '/assets/dist/js/';
$themeDirectory__fonts = $themeDirectory . '/assets/dist/fonts/';




/******************************************
Function - Image src + srcset attribute
******************************************/ 
function image_src($imageName, $customImageDirectory = false ) {
	global $themeDirectory__images;
  $imageDirectory = ($customImageDirectory != false) ? $customImageDirectory : $themeDirectory__images;
  
  return $imageDirectory . $imageName;
}

function image_srcset($imageName, $imageNameRetina, $customImageDirectory = false) {

	if($imageName == null || $imageNameRetina == null) {
		error_log( "Error - Please specify a value for the following parameters: '&dollar;imageName' and &dollar;imageNameRetina parameters" );
	} 
	global $themeDirectory__images;
	$imageDirectory = ($customImageDirectory != false) ? $customImageDirectory : $themeDirectory__images;
	return $imageDirectory . $imageName . ' 1x, ' . $imageDirectory . $imageNameRetina . ' 2x';
}


/******************************************
Function - Retrieve User Name
(eg. used in comment form to display logged in as notice...)
******************************************/ 
function getUserName() {
	if(is_user_logged_in() ) {

		$firstName = wp_get_current_user()->user_firstname;
		$nickName = wp_get_current_user()->nickname;
		$userName = wp_get_current_user()->user_login;

		if( $firstName ) return $firstName;
		if( $nickName ) return $nickName;
		if( $userName ) return $userName;
	}
}

/******************************************
Function - Get Video ID
// Retrieves only the video ID from youtube url
// Parameter = field ID of ACF video URL fields
// Eg. tutorial posts video field
******************************************/
function videoID($fieldID) {
  $videoURL = get_field($fieldID);
  parse_str( parse_url( $videoURL, PHP_URL_QUERY ), $my_array_of_vars );
  echo $my_array_of_vars['v'];
}
	

/******************************************
Entry Meta
******************************************/
function initialize_entry_meta($post_id = false) {
  
  $id = ($post_id) ? $post_id : get_the_id();

  $title = get_the_title($id);
	$post_author_user_id = get_post_field('post_author', $id);
	$user_avatar = get_avatar_url($post_author_user_id);
	$user_firstname = get_the_author_meta('first_name', $post_author_user_id);
	$user_lastname = get_the_author_meta('last_name', $post_author_user_id);
	$user_nickname = get_the_author_meta('nickname', $post_author_user_id);
  $post_date = ace_get_post_date($id);
  // $summary = get_field('post_summary', $id);
  // $post_type = get_post_type($id);
  // $chapter_number = initialize_chapter($id);
  
  // context vars
  // $data = get_the_terms($id, 'context');
  // $context_name = $data[0]->name;
  // $context_slug = $data[0]->slug;
  // $context_url = get_term_link($context_slug,'context');

  // else {
  //   $data = get_post_type_object( $post_type );
  //   $context_name = $data->labels->name;
  //   $context_slug = $data->rewrite['slug'];
  //   $context_url = get_post_type_archive_link($context_slug);
  // }

  $arr['title']             = $title;
  $arr['author-url']        = $user_avatar;
  $arr['author-nickname']   = $user_nickname;
	$arr['author-firstname']  = $user_firstname;
  $arr['author-lastname']   = $user_lastname;
  $arr['author-fullname']   = ($user_lastname) ? $user_firstname . " " . $user_lastname : $user_firstname; 
	$arr['post-date']         = $post_date;
	// $arr['context-name']      = $context_name;
  // $arr['context-slug']      = $context_slug;
  // $arr['context-url']       = $context_url;
  // $arr['summary']           = $summary;

	return $arr;
}



/******************************************
Initialize Entry Title
- Construct title, with or without the 'Chapter...' prefix
******************************************/
function initialize_entry_title($post_id = false) {

  $target_post = ($post_id) ? $post_id : get_the_id();
  // $chapter_number = initialize_chapter($target_post);

  if($chapter_number) {
    $title = $chapter_number['full'] . ': ' . get_the_title($target_post);
  }
  else {
    $title = get_the_title($target_post);
  }

  return $title;
}


/******************************************
Get Featured Image
******************************************/
function initialize_featured_image($post_id = false, $size = 'full') {

  $target_post = ($post_id) ? $post_id : get_the_id();

  // 1. Set Variables:
  $is_series = get_field('series_toggle', $target_post);
  $series_connection_type = get_field('series_connection_type', $target_post);
  $use_series_featured_image = get_field('series_featured_image_override', $target_post);

  // if part of a series AND a chapter AND set to use the series featured image
  if($is_series && $series_connection_type == 'chapter' && $use_series_featured_image) {
    $postID = get_field('series_parent', $target_post);
  }
  // else use the target_post ID from which to derive the featured image
  else {
    $postID = $target_post;
  }

  
  $featured_image['chapter-tag'] = ($is_series && $series_connection_type == 'chapter') ? initialize_chapter_tag($target_post) : null;
  $featured_image['id'] = get_field('featured_image', $postID);
  // src var (first check if it exists on the server - if not, set to false)
  $image_src = wp_get_attachment_image_src($featured_image['id'], $size)[0];
  $featured_image['src'] = check_file_exists($image_src) ? $image_src : false;

  $featured_image['srcset'] = wp_get_attachment_image_srcset($featured_image['id']);
  $featured_image['alt'] = get_post_meta($featured_image['id'], '_wp_attachment_image_alt', TRUE);
  $featured_image['listing-offset'] = get_field('featured_image_listing_offset', $postID);

  return $featured_image;
}



class FeaturedImage
{
    public function __construct($post_id = false, $size = 'full')
    {
        $post_id = ($post_id) ? $post_id : get_the_id();

        if (has_post_thumbnail($post_id)) {
            $this->id = get_post_thumbnail_id($post_id);
            $this->src = wp_get_attachment_image_src($this->id, $size)[0];
            $this->srcset = wp_get_attachment_image_srcset($this->id);
            $this->alt = get_post_meta($this->id, '_wp_attachment_image_alt', true);
        }
    }
}

class HeroImage extends FeaturedImage
{
    public function __construct()
    {
        FeaturedImage::__construct($post_id = false, $size = 'full');
        $this->heroOffset = get_field('hero_image_vertical_offset', $post_id);
    }
}


/******************************************
Taxonomies
- List of relevant taxonomies
- Used to generate taxonomies admin menu
- And to generate terms featured in post overviews
******************************************/
function taxonomies_array() {
  $taxonomies = array(
    // 'context',
    'category',
    // 'post_tag',
    // 'interest',
    // 'technique',
    // 'skill-level',
    // 'software',
    // 'sector',
    // 'art-supplies',
    // 'tech',
    // 'artist',
    // 'studio',
    // 'resource',
  );
  return $taxonomies;
}


/******************************************
Initialize terms
- get all terms associated with a post
- will get terms for current post or a specified post (targeted with its id)
- returns array of all terms, each as an array with associated values
******************************************/
function initialize_terms($post_id = false) {
  // Generate list of taxonomies associated with the post
  // echo 'hi';
  $taxonomies = taxonomies_array();
  $postID = ($post_id) ? $post_id : get_the_id();
  $terms = array();

  // Iterate over each of the taxonomies
  foreach ($taxonomies as $tax) { 
    $term_list = get_the_terms($postID, $tax);


    if(!empty($term_list)) {
      $length = count($term_list);

      // Iterate over each term in the taxonomy and push it in to an array
      for ($i = 0; $i < $length; $i++) {
        $term_id = $term_list[$i]->term_id;
        $term_name = $term_list[$i]->name;
        $term_slug = $term_list[$i]->slug;
        $term_permalink = get_term_link($term_id);
        $term_taxonomy = $term_list[$i]->taxonomy;

        $terms[$term_slug] = array(
          'id'   => $term_id,
          'name' => $term_name,
          'slug' => $term_slug,
          'permalink' => $term_permalink,
          'taxonomy' => $term_taxonomy
        );
      }
    }
  }
  return $terms;


}




/******************************************
Generate Next post data
- if part of a series, gets next and previous in series
- if not, gets next and previous chronological post of same post type
- Used, for example, in the entry-header 
******************************************/
function initialize_next_entry_meta() {

  static $next_post = null;

  if ( $next_post === null ) { // <- If not, WP should use cache instead...

    // Initialize next post id:

      // If post is part of a series:
      if(get_field('series_toggle') && get_field('series_parent')) {
        $current_post['id'] = get_the_id();
        $series_chapters = get_field('series_chapters', get_field('series_parent'));
        $current_post_index =  array_search($current_post['id'], $series_chapters);

        if($current_post_index == max(array_keys($series_chapters))) {
          $next_post_id  = $series_chapters[0];
        }
        else {
          $next_post_id  = $series_chapters[$current_post_index + 1];
        }
      }

      // Else if not in a series:
      else {
        $next_post_object = get_adjacent_post(false, '', false);

          if(!empty($next_post_object)) {
            $next_post_id = $next_post_object->ID;
          }
          // If no next post (ie. this is the most recent) then set next post as the earliest post in the same post type
          else {
            $args = array(
              'post_type' => get_post_type(),
              'numberposts' => -1,
              'posts_per_page' => 1,
              'order' => 'ASC'
          );
            $last_post_object = get_posts($args);
            $next_post_id = $last_post_object[0]->ID;
          }
        }


    // Next Post vars
    $next_post['id'] = $next_post_id;
    $next_post['url'] = get_permalink($next_post['id']);
    $next_post['title'] = initialize_entry_title($next_post['id']);
    $next_post['summary'] = get_field('post_summary', $next_post['id']);
    $next_post['thumbnail'] = initialize_featured_image($next_post['id']);

    }

  return $next_post;

  };


/******************************************
Generate Previous post data
- if part of a series, gets next and previous in series
- if not, gets next and previous chronological post of same post type
- Used, for example, in the entry-header 
******************************************/
function initialize_previous_entry_meta() {

  static $previous_post = null;

  if ( $previous_post === null ) { // <- If not, use WP should use cache instead...

    // Initialize previous post id:

      // If post is part of a series:
      if(get_field('series_toggle') && get_field('series_parent')) {
        $current_post['id'] = get_the_id();
        $series_chapters = get_field('series_chapters', get_field('series_parent'));
        $current_post_index =  array_search($current_post['id'], $series_chapters);
    
        if($current_post_index == 0) {
          $previous_post_id  = end($series_chapters);
          reset($series_chapters);
        }
        else {
          $previous_post_id  = $series_chapters[$current_post_index - 1];
        }

      }

      // Else if not in a series:
      else {
        $previous_post_object = get_adjacent_post(false, '', true);
  
        if(!empty($previous_post_object)) {
          $previous_post_id = $previous_post_object->ID;
        }
        // If no previous post (ie. this post is the oldest) then set previous post as the newest post in the same post type
        else {
          $args = array(
            'post_type' => get_post_type(),
            'numberposts' => -1,
            'posts_per_page' => 1,
            'order' => 'DSC'
          );
          $first_post_object = get_posts($args);
          $previous_post_id = $first_post_object[0]->ID;
        }

      }
    

    // Previous Post Vars
    $previous_post['id'] = $previous_post_id;
    $previous_post['url'] = get_permalink($previous_post['id']);
    $previous_post['title'] = initialize_entry_title($previous_post['id']);
    $previous_post['summary'] = get_field('post_summary', $previous_post['id']);
    $previous_post['thumbnail'] = initialize_featured_image($previous_post['id']);

    }

  return $previous_post;

  };




/******************************************
Get Primary Category
 * Makes post Primary Category (available when Yoast is installed) 
 * to be called and used at various points in the post 
 * (eg featured header, related posts etc)
******************************************/
// SHOW YOAST PRIMARY CATEGORY, OR FIRST CATEGORY
function getPrimaryCategory() {
	$category = get_the_category();
	// If post has a category assigned.
	if ($category){

		$category_id = '';
		$category_display = '';
		$category_link = '';
		
		if ( class_exists('WPSEO_Primary_Term') )
		{
			// Show the post's 'Primary' category, if this Yoast feature is available and one is set
			$wpseo_primary_term = new WPSEO_Primary_Term( 'category', get_the_id() );
			$wpseo_primary_term = $wpseo_primary_term->get_primary_term();
			$term = get_term( $wpseo_primary_term );
			if (is_wp_error($term)) { 
				// Default to first category (not Yoast) if an error is returned
				$category_id = $category[0]->term_id;
				$category_display = $category[0]->name;
				$category_link = get_category_link( $category[0]->term_id );
			} else { 
				// Yoast Primary category
				$category_id = $term->term_id;
				$category_display = $term->name;
				$category_link = get_category_link( $term->term_id );
			}
		} 
		else {
			// Default, display the first category in WP's list of assigned categories
			$category_id = $category[0]->term_id;
			$category_display = $category[0]->name;
			$category_link = get_category_link( $category[0]->term_id );
		}
		// Display category
		if ( !empty($category_display) ){
			$primary_category['id'] = $category_id;
			$primary_category['url'] = $category_link;
			$primary_category['title'] = $category_display;
		}
		return $primary_category;
		}
	}


/******************************************
Admin Email
******************************************/
function adminEmail() {
	$themeAdminEmail = 'richard@acedigitaldesign.com';
	return $themeAdminEmail;
}


/******************************************
Multi-Purpose Close Button
// $selector = JS selector of the element it closes
// $class = class through which to add contextual styling
******************************************/ 
function close_button($selector, $class) {
  ob_start(); 
  echo sprintf('<div class="%1$s %2$s"></div>', $selector, $class );
  echo ob_get_clean();
}

/******************************************
Get All image sizes
******************************************/
function get_all_image_sizes() {
    global $_wp_additional_image_sizes;

    $default_image_sizes = get_intermediate_image_sizes();

    foreach ( $default_image_sizes as $size ) {
        $image_sizes[ $size ][ 'width' ] = intval( get_option( "{$size}_size_w" ) );
        $image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
        $image_sizes[ $size ][ 'crop' ] = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
    }

    if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
        $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
    }

    return $image_sizes;
}


/******************************************
Print array
// Quickly print arrays
******************************************/
function print_array($arr) {
  echo '<pre>';
  print_r($arr);
  echo '</pre>';
} 


// Establishes the archive link for given post type
// Very useful if post type does not have an archive, will find another suitable one, usually from the slug
// Used mainly in breadcrumbs
if (!function_exists('get_archive_link')) {
  function get_archive_link( $post_type ) {
    global $wp_post_types;
    $archive_link = false;
    if (isset($wp_post_types[$post_type])) {
      $wp_post_type = $wp_post_types[$post_type];
      if ($wp_post_type->publicly_queryable)
        if ($wp_post_type->has_archive && $wp_post_type->has_archive!==true)
          $slug = $wp_post_type->has_archive;
        else if (isset($wp_post_type->rewrite['slug']))
          $slug = $wp_post_type->rewrite['slug'];
        else
          $slug = $post_type;
      $archive_link = get_option( 'siteurl' ) . "/" . $slug . "/";
    }
    return apply_filters( 'archive_link', $archive_link, $post_type );
  }
}





/******************************************
Get Full URL
- Gets the full url for current page, including query strings (in case of on search pages etc)
******************************************/
function get_full_url() {
  // global $wp;
  // $url = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
  $url = home_url() . '/' . ltrim(esc_url($_SERVER['REQUEST_URI']), '/');
  return $url;
};






/******************************************
Media size array
// Used to get quick access to media sizes of specified ID
******************************************/
function media_size_array($imageID) {
	$media = array(
		'thumbnail' => wp_get_attachment_image_src( $imageID , 'thumbnail' )[0],
		'medium' => wp_get_attachment_image_src( $imageID , 'medium' )[0],
		'medium_large' => wp_get_attachment_image_src( $imageID , 'medium_large' )[0],
		'large' => wp_get_attachment_image_src( $imageID , 'large' )[0],
		'full' => wp_get_attachment_image_src( $imageID , 'full' )[0]
	);
	return $media;
}





/******************************************
Load Admin area functions
******************************************/
add_action('admin_head', 'admin_only');

function admin_only() {

    if( !is_admin() )
      return;

    global $current_screen;
    $admin_post_type = $current_screen->post_type;

    if( $admin_post_type == 'post' || $admin_post_type == 'page' ) {
      add_filter('acf/prepare_field/key=field_5f0588503f494', 'post_edit_content_verification');
    }
    else return;
}



/******************************************
Post edit content file verification
******************************************/
// define the actions for the two hooks created, first for logged in users and the next for logged out users
add_action("wp_ajax_post_edit_content_verification", "post_edit_content_verification");
add_action("wp_ajax_nopriv_post_edit_content_verification", "please_login");

// define the function to be fired for logged in users
function post_edit_content_verification($field) {
  
  // Check if action was fired via Ajax call. If yes, JS code will be triggered, else the user is redirected to the post page
  if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    // nonce check for an extra layer of security, the function will exit if it fails
    if ( !wp_verify_nonce( $_POST['security'], "aj_content_verification_nonce")) {
      exit("Woof Woof Woof");
    }
    $ajax_request = true;
    $uid_input = $_POST['uid_input_value'];
    $uid = $_POST['uid'];
    $post_type = $_POST['post_type'];
    $verification_btn = '';
  }
  else {
    $ajax_request = false;
    $uid_input = get_field('universal_id') ? get_field('universal_id') : '';
    $post_type = get_post_type(); 
    $uid = THEME_PREFIX . ucfirst($post_type) . $uid_input;
    $verification_btn = '<a class="js-content-file-verification-btn content-status__verification-btn button">Locate Content</a>';
  }

  // If Universal ID input field is empty:
  if(empty($uid_input)) {
    $status['data-value'] = 'pending-uid';
    $status['title'] = 'Universal ID required';
    $status['description'] = 'A Universal ID is required before the content file can be located. Please specify and try again.';
  }
  else {
    // Construct directory path:
    $upload_dir = wp_upload_dir();
    $folder_name = '_' . $post_type . 's';
    $dir = $upload_dir['basedir'] . '/' . $folder_name . '/';

    // Construct content filename:
    $filename = $uid . '.php';

    // Construct content file path
    $file_path = $dir . $filename;
    // $file_path_alt = $dir . '_' . $filename; // <- Sometimes may put a preceding underscore in file name to get it to sit at top of finder window...

    if (file_exists($file_path)) {   
      $status['data-value'] = 'success';
      $status['title'] = 'Content file located';
      $status['description'] = $file_path;
    }
    else {
      $status['data-value'] = 'error';
      $status['title'] = 'Cannot locate content file';
      $status['description'] = 'Expected content path: ' . $file_path;
    }
  }

  ob_start(); ?>
  <div class="content-status__message" data-content-status-message="<?php echo $status['data-value']; ?>" >
    <div class="content-status__message-title"><?php echo $status['title']; ?></div>
    <div class="content-status__message-description"><?php echo $status['description']; ?></div>
  </div><?php echo $verification_btn ?> <?php 

  if(!$ajax_request) {
    $field['message'] = ob_get_clean();
    return $field;
  }
  else {
    $response = ob_get_clean();
    die($response);
  }
}

function please_login() {
  echo "You must be logged in to perform this action. Please log in and try again.";
  die();
}



/******************************************
Check File Exists
- checks if a file exists at the specified file path
- eg. often used to check if images exist on the server
******************************************/
function check_file_exists($url) {
  if(strpos($url, 'wp-content') === false) {
    return false;
  }
  else {
    if ($url[0] == '/') {
      $relative_path = strstr($url, '/wp-content');
    }
    else {
      $relative_path = strstr($url, 'wp-content');
    }
    
    if(file_exists($_SERVER["DOCUMENT_ROOT"] . '/' . $relative_path)) {
      return true;
    } 
    else {
      return false;
    }
  }
}


/******************************************
Strip double or single quotes
- strips leading or trailing single or double quotes
- leaves in tact any other quotes
- useful for quotes shortcode
- courtesy of Steve Chambers (https://stackoverflow.com/a/25353877)
******************************************/ 
function stripQuotes($text) {
  return preg_replace('/^(\'(.*)\'|"(.*)")$/', '$2$3', $text);
} 





/******************************************
Page Slug Body Class
******************************************/
function add_slug_body_class( $classes ) {
  global $post;
  if ( isset( $post ) ) {
  $classes[] = $post->post_name;
  }
  return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );
  

/******************************************
Custom Content
- Generates path to custom content files
- eg. page php files
******************************************/
function get_custom_content($post_id = false) {

  $id = $post_id ? $post_id : get_the_id();

  $custom_content_file = false;

  // Construct path:
  $upload_dir = wp_upload_dir();
  $truncated_upload_dir = strstr($upload_dir['basedir'], 'wp-content'); // <-strips out everything before wp-content
  $post_type_obj = get_post_type_object(get_post_type());
  $folder_name = strtolower($post_type_obj->label);
  
  $file = $truncated_upload_dir . '/' . $folder_name . '/' . esc_html(get_field('custom_content', $id));

  // checks if there is an extension
      $path_parts = pathinfo($file);

      // if file has extension
      if ( isset($path_parts['extension']) ) {

        // check it exists on server
        if(check_file_exists($file)) {

          // if exists, include the file
          $custom_content_file = $file ; 
        }
      }
      // if no file extension, try a few different extensions in an attempt to locate it
      else {
        $extensions_to_try = ['.php', '.html'];
        $file_found = false;

        foreach( $extensions_to_try as $ext ) {

          // check it exists on server
          if(check_file_exists($file . $ext)) {
   
            // if exists, include the file
            $custom_content_file = $file . $ext;
            break;

          }
        }
      }

  return $custom_content_file;
};


/******************************************
Load Post Content
- The post content is an external php file
- dynamically retrieves and prints in post instead of using WP 'the_content' function
- used instead of having to paste generated html from VS code file in to WP Post editor
- find it always opens up doubt as to which file is the most up to date
- and despite good intentions, small tweaks are inevitably done in post editor 
- which instantly makes offline file redundant
- and much easier interpreting and editing html/php in VS code than WP post editor
- better to just have one file thats sourced
- hence why keeping external documents of content and dynamically sourcing them using this function...
******************************************/ 
function the_custom_content() {
  $file = get_custom_content();
  if ( $file ) {
    include($file);
  }
  else {
    echo 'Could not locate external content file. Please check and try again.';
  }
}

/******************************************
Date difference
- returns number of days between 2 dates
- used eg. to highlight new posts (posts published in last 7 days etc)
******************************************/
function dateDifference($date_1 , $date_2 ) {
  
  $datetime1 = date_create($date_1);
  $datetime2 = date_create($date_2);

  $interval = date_diff($datetime1, $datetime2);

  return $interval->format('%a');

}

add_shortcode('moddate', 'rp_temp_moddate'); 

function rp_temp_moddate() {
  $var = ace_get_post_date()['full'];
  echo $var;
}
/******************************************
Initialize date published or updated
- checks the post for which is most recent, the published date, modified date or the modified date of the content file
- returns an array with the appropriate prefix, date and both, concatenated
******************************************/
function ace_get_post_date($post_id = false) {

  // vars
  $id = ($post_id) ? $post_id : get_the_id();
  $publish_date = get_the_date( 'U' );
  $modified_date = get_the_modified_date( 'U' );
  $content_source = esc_html(get_field('content_source'));
  $custom_content = esc_html(get_field('custom_content'));

  if ($content_source == "external"  && $custom_content) {
    $content_file_date = date('U', filemtime(get_custom_content($id)));
    // Determines the most recent of the post modified date or the modified date of the content file
    $modified_date = max($modified_date, $content_file_date);    
  }

  // Creates an array with final publish and modified dates
  $dateArr = array(
    'Published' => $publish_date,
    'Updated' => $modified_date
  );

  // Evaluates which is more recent, publish or modified date
  $value = max($dateArr);
  $key = array_search($value, $dateArr);

  // Assigns the final variables, prefix and date
  $prefix = $key;
  $date = date('d F Y', $value);

  // Packages in to an array
  $arr = array(
    'prefix' => $prefix,
    'date' => $date,
    'full' => $prefix . ': ' . $date
  );

  return $arr;
}


/*****************************************************
SEO Meta
 - Generates my own SEO meta tags without need for bloated plugins
 - Based on: https://digwp.com/2013/08/basic-wp-seo/
******************************************************/ 
function initialize_seo_meta() {

  /*************************************
  SEO: Initialize Variables
  **************************************/
  global $page, $paged, $post;
  $customSeoOverride = get_field('seo_custom_settings');
  $output = '';
  $postType = get_post_type();

  // Title
  $customSeoTitle = get_field('seo_custom_title');
  if ($customSeoOverride && $customSeoTitle) {
    $seo['title'] = $customSeoTitle;
  }
  // else $seo['title'] = trim(wp_title('', false));
  else {
    $seo['title'] = get_the_title();
  }

  // Description
  $postSummary = strip_tags(get_field('post_summary'));
  $customSeoDescription = strip_tags(get_field('seo_custom_description'));
  if ($customSeoOverride && $customSeoDescription) $seo['description'] = $customSeoDescription;
  elseif ($postSummary) $seo['description'] = $postSummary;
  else $seo['description'] = get_bloginfo('description', 'display');
  
  // Misc vars
  $seo['url'] = get_full_url();
  $seo['site-name'] = get_bloginfo('name', 'display');
  $seo['cat'] = single_cat_title('', false);
  $seo['tag'] = single_tag_title('', false);
  $seo['search'] = get_search_query();
  $seo['canonical'] = $seo['url'];
  $seo['published-time'] = date('c', get_post_time());
  $seo['modified-time'] = date('c', get_the_modified_date('U'));
  // Page number
  if ($paged >= 2 || $page >= 2) {
    $page_number = ' | ' . sprintf('Page %s', max($paged, $page));
  }
  else {
    $page_number = '';
  }


  /*************************************
  SEO: Initialize Meta Tags
  **************************************/ 
  // Title
  if (is_home() || is_front_page()) $seo['meta-title'] = $seo['site-name']    . ' | '          . $seo['description'];
  elseif (is_singular())            $seo['meta-title'] = $seo['title']        . ' | '          . $seo['site-name'];
  elseif (is_tag())                 $seo['meta-title'] = 'Tag Archive: '      . $seo['tag']    . ' | '                . $seo['site-name'];
  elseif (is_category())            $seo['meta-title'] = 'Category Archive: ' . $seo['cat']    . ' | '                . $seo['site-name'];
  elseif (is_archive())             $seo['meta-title'] = 'Archive: '          . $seo['title']  . ' | '                . $seo['site-name'];
  elseif (is_search())              $seo['meta-title'] = 'Search: '           . $seo['search'] . ' | '                . $seo['site-name'];
  elseif (is_404())                 $seo['meta-title'] = '404 - Not Found: '  . $seo['url']    . ' | '                . $seo['site-name'];
  else                              $seo['meta-title'] = $seo['site-name']    . ' | '          . $seo['description'];

  $output .= "\t\t" . '<title>' . esc_attr($seo['meta-title'] . $page_number) . '</title>' . "\n";

  // Description
	$output .= '<meta name="description" content="' . esc_attr($seo['description']) . '">' . "\n";


	// Robots
	if (is_category() || is_tag()) {
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		if ($paged > 1) {
			$output .=  "\t\t" . '<meta name="robots" content="noindex,follow">' . "\n";
		} else {
			$output .=  "\t\t" . '<meta name="robots" content="index,follow">' . "\n";
		}
	} else if (is_home() || is_singular()) {
		$output .=  "\t\t" . '<meta name="robots" content="index,follow">' . "\n";
	} else {
		$output .= "\t\t" . '<meta name="robots" content="noindex,follow">' . "\n";
  }
  
  
  // Canonical
  $output .= "\t\t" . '<link rel="canonical" href="' . $seo['canonical'] . '">';


  // Open Graph tags
  $share_custom_settings = get_field('share_custom_settings');
  $share_custom_title = get_field('share_custom_title');
  $share_custom_description = strip_tags(get_field('share_custom_description'));
  $share_custom_image = strip_tags(get_field('share_custom_image'));

  $share_custom_facebook_settings = get_field('share_custom_facebook_settings');
  $share_custom_facebook_title = get_field('share_custom_facebook_title');
  $share_custom_facebook_description = strip_tags(get_field('share_custom_facebook_description'));
  $share_custom_facebook_image = strip_tags(get_field('share_custom_facebook_image'));

  if ($share_custom_facebook_settings && $share_custom_facebook_title) $og['title'] = $share_custom_facebook_title;
  elseif ($share_custom_settings && $share_custom_title) $og['title'] = $share_custom_title;
  else $og['title'] = $seo['title'];

  if ($share_custom_facebook_settings && $share_custom_facebook_description) $og['description'] = $share_custom_facebook_description;
  elseif ($share_custom_settings && $share_custom_description) $og['title'] = $share_custom_description;
  else $og['description'] = $seo['description'];

  if ($share_custom_facebook_settings && $share_custom_facebook_image) $og['description'] = $share_custom_facebook_image;
  elseif ($share_custom_settings && $share_custom_image) $og['title'] = $share_custom_image;
  else $og['description'] = $seo['description'];

    // Start og meta compile:
    $output .= "\t\t" . '<meta property="og:locale" content="en_US">' . "\n";
    $output .= "\t\t" . '<meta property="og:type" content="article">' . "\n";
    $output .= "\t\t" . '<meta property="og:title" content="' . $og['title'] . '">' . "\n";

    if($postType != 'page') {
      $output .= "\t\t" . '<meta property="og:description" content="' . $og['description'] . '">' . "\n";
    }
    // $og['image'] = if($share_custom_facebook_image) ? $og['image'] = {

    // }
    // $output .= "\t\t" . '<meta property="og:image" content="' .  . '" />' . "\n";
    $output .= "\t\t" . '<meta property="og:url" content="' . $seo['url'] . '">' . "\n";
    $output .= "\t\t" . '<meta property="og:site_name" content="' . $seo['site-name'] . '">' . "\n";
    
    if($postType == 'tutorial' || $postType == 'guides' || $postType == 'inspiration' || $postType == 'product') {
      $output .= "\t\t" . '<meta property="article:section" content="' . ucfirst($postType) . '">' . "\n";
    }

    $output .= "\t\t" . '<meta property="article:published_time" content="' . $seo['published-time'] . '">' . "\n";

    if ($seo['modified-time'] > $seo['published-time']) {
      $output .= "\t\t" . '<meta property="article:modified_time" content="' . $seo['modified-time'] . '">' . "\n";
      $output .= "\t\t" . '<meta property="og:updated_time" content="' . $seo['modified-time'] . '">' . "\n";
    }

  // Twitter cards
  $customTwitterSettings = get_field('share_custom_twitter_settings');
  $customTwitterTitle = get_field('share_custom_twitter_title');
  $customTwitterDescription = get_field('share_custom_twitter_description');

  $twitterCard['type'] = 'summary_large_image';

  if ($customTwitterSettings && $customTwitterTitle) $twitterCard['title'] = $customTwitterTitle;
  else $twitterCard['title'] = $seo['title'];

  if ($customTwitterSettings && $customTwitterDescription) $twitterCard['description'] = $customTwitterDescription;
  else $twitterCard['description'] = $seo['description'];

  $output .= "\t\t" . '<meta name="twitter:card" content="' . $twitterCard['type'] . '">' . "\n";
  $output .= "\t\t" . '<meta name="twitter:title" content="' . $twitterCard['title'] . '">' . "\n";
  $output .= "\t\t" . '<meta name="twitter:description" content="' . $twitterCard['description'] . '">' . "\n";
  

	echo $output;
}
// add_action('wp_head', 'initialize_seo_meta', 1);


// $og = array();
// $og['url'] = get_full_url();
// $og['locale'] = "en_US";
// $og['type'] = 'article';
// // $og['title'] = get_share_meta()['title'];



function get_share_meta() {

  $share = array(
    'general'   => array(),
    'twitter'  => array(),
    'pinterest' => array()
  );
  $seo = get_seo_meta();

  // General vars
  $share_custom_settings = get_field('share_custom_settings');
  $share_custom_title = get_field('share_custom_title');
  $share_custom_description = get_field('share_custom_description');
  $share_custom_image = get_field('share_custom_image');

  // Twitter vars
  $twitter_custom_settings = get_field('share_custom_twitter_settings');
  $twitter_title = get_field('share_custom_twitter_title');
  $twitter_description = get_field('share_custom_twitter_description');
  $twitter_image = get_field('share_custom_twitter_image');

  // Pinterest vars
  $pinterest_custom_settings = get_field('share_custom_pinterest_settings');
  $pinterest_title = get_field('share_custom_pinterest_title');
  $pinterest_description = get_field('share_custom_pinterest_description');
  $pinterest_image = get_field('share_custom_pinterest_image');


  // Initialize share meta:

  // General
  // General Title
  if($share_custom_settings && $share_custom_title) {
    $share['general']['title'] = $share_custom_title;
  }
  else {
    $share['general']['title'] = $seo['title'];
  }

  // General Description
  if($share_custom_settings && $share_custom_description) {
    $share['general']['description'] = $share_custom_description;
  }
  else {
    $share['general']['description'] = $seo['description'];
  }

  // General Image
  if($share_custom_settings && $share_custom_image) {
    $share['general']['image'] = $share_custom_image;
  }
  else {
    $share['general']['image'] = get_the_post_thumbnail_url();
  }




  // Twitter
  // Twitter Title
  if($twitter_custom_settings && $twitter_title) {
    $share['twitter']['title'] = $twitter_title;
  }
  else {
    $share['twitter']['title'] = $share['general']['title'];
  }

  // Twitter Description
  if($twitter_custom_settings && $twitter_description ) {
    $share['twitter']['description'] = $twitter_description;
  }
  else {
    $share['twitter']['description'] = $share['general']['description'];
  }

  // Twitter Image
  if($twitter_custom_settings && $twitter_image) {
    $share['twitter']['image'] = $twitter_image;
  }
  else {
    $share['twitter']['image'] = $share['general']['image'];
  }



  // Pinterest
  // Pinterest Title
  if($pinterest_custom_settings && $twitter_title) {
    $share['pinterest']['title'] = $pinterest_title;
  }
  else {
    $share['pinterest']['title'] = $share['general']['title'];
  }

  // Pinterest Description
  if($pinterest_custom_settings && $pinterest_description ) {
    $share['pinterest']['description'] = $pinterest_description;
  }
  else {
    $share['pinterest']['description'] = $share['general']['description'];
  }

  // Pinterest Image
  if($pinterest_custom_settings && $pinterest_image) {
    $share['pinterest']['image'] = $pinterest_image;
  }
  else {
    $share['pinterest']['image'] = $share['general']['image'];
  }


  return $share;
}


  // // Pinterest
  // if($pinterest_custom_settings) {

  //   // Pinterst Title
  //   if($twitter_title) {
  //     $share['pinterest']['title'] = $pinterest_title;
  //   }
  //   // Pinterest Description
  //   if($pinterest_description) {
  //     $share['pinterest']['description'] = $pinterest_description;
  //   }
  //   // Pinterest Image
  //   if($pinterest_image) {
  //     $share['pinterest']['image'] = $pinterest_image;
  //   }

  // }
  // else {
  //   $share['pinterest']['title'] = $share['general']['title'];
  //   $share['pinterest']['description'] = $share['general']['description'];
  //   $share['pinterest']['image'] = $share['general']['image'];
  // }

function get_seo_meta() {
  $seo = array();
  $seo_custom_settings = get_field('seo_custom_settings');
  $seo_custom_title = get_field('seo_custom_title');
  $seo_custom_description = strip_tags(get_field('seo_custom_description'));
  $post_summary = strip_tags(get_field('post_summary'));

  // SEO Title
  if($seo_custom_settings && $seo_custom_title) {
    $seo['title'] = $seo_custom_title;
  }
  else {
    $seo['title'] = get_the_title();
  }

  // SEO Description
  if($seo_custom_settings && $seo_custom_description) {
    $seo['description'] = $seo_custom_description;
  }
  elseif($post_summary) {
    $seo['description'] = $post_summary;
  }
  else {
    $seo['description'] = get_bloginfo('description', 'display');
  }

  // SEO misc vars
  $seo['url'] = get_full_url();
  $seo['site-name'] = get_bloginfo('name', 'display');
  $seo['cat'] = single_cat_title('', false);
  $seo['tag'] = single_tag_title('', false);
  $seo['search'] = get_search_query();
  $seo['canonical'] = $seo['url'];
  $seo['published-time'] = date('c', get_post_time());
  $seo['modified-time'] = date('c', get_the_modified_date('U'));
  // $seo['og-url'] = $seo['url'];
  // $seo['og-title'] = $share['general']['title'];
  // $seo['og-description'] = $share['general'];
  // $seo['og-image'] = ;

  // SEO meta title
  if (is_home() || is_front_page()) $seo['meta-title'] = $seo['site-name'] . ' | ' . $seo['description'];
  elseif (is_singular())            $seo['meta-title'] = $seo['title'] . ' | ' . $seo['site-name'];
  elseif (is_tag())                 $seo['meta-title'] = 'Tag Archive: ' . $seo['tag'] . ' | ' . $seo['site-name'];
  elseif (is_category())            $seo['meta-title'] = 'Category Archive: ' . $seo['cat'] . ' | ' . $seo['site-name'];
  elseif (is_archive())             $seo['meta-title'] = 'Archive: ' . $seo['title'] . ' | ' . $seo['site-name'];
  elseif (is_search())              $seo['meta-title'] = 'Search: ' . $seo['search'] . ' | ' . $seo['site-name'];
  elseif (is_404())                 $seo['meta-title'] = '404 - Not Found: ' . $seo['url'] . ' | ' . $seo['site-name'];
  else                              $seo['meta-title'] = $seo['site-name'] . ' | ' . $seo['description'];

  return $seo;
}
// <meta property="og:url"                content="http://www.nytimes.com/2015/02/19/arts/international/when-great-minds-dont-think-alike.html" />
// <meta property="og:type"               content="article" />
// <meta property="og:title"              content="When Great Minds Do't Think Alike" />
// <meta property="og:description"        content="How much does culture influence creative thinking?" />
// <meta property="og:image"              content="http://static01.nyt.com/images/2015/02/19/arts/international/19iht-btnumbers19A/19iht-btnumbers19A-facebookJumbo-v2.jpg" />


function instantiate_seo_tags() {
  // VARS
  global $page, $paged, $post;
  $seo = get_seo_meta();
  $share = get_share_meta();
  $output = '';
  $postType = get_post_type();

  if ($paged >= 2 || $page >= 2) {
    $page_number = ' | ' . sprintf('Page %s', max($paged, $page));
  }
  else {
    $page_number = '';
  }


  // INITIALIZE TAGS
  // Title and description
  $output .= "\t\t" . '<title>' . esc_attr($seo['meta-title'] . $page_number) . '</title>' . "\n";
  $output .= '<meta name="description" content="' . esc_attr($seo['description']) . '">' . "\n";

  // Robots
  if (is_category() || is_tag()) {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    if ($paged > 1) {
      $output .=  "\t\t" . '<meta name="robots" content="noindex,follow">' . "\n";
    } 
    else {
      $output .=  "\t\t" . '<meta name="robots" content="index,follow">' . "\n";
    }
  } 
  else if (is_home() || is_singular()) {
    $output .=  "\t\t" . '<meta name="robots" content="index,follow">' . "\n";
  } 
  else {
    $output .= "\t\t" . '<meta name="robots" content="noindex,follow">' . "\n";
  }

  // Canonical
  $output .= "\t\t" . '<link rel="canonical" href="' . $seo['canonical'] . '">'; 

  // OG tags
  $output .= "\t\t" . '<meta property="og:locale" content="en_US">' . "\n";
  $output .= "\t\t" . '<meta property="og:type" content="article">' . "\n";
  $output .= "\t\t" . '<meta property="og:title" content="' . $share['general']['title'] . '">' . "\n";
  $output .= "\t\t" . '<meta property="og:description" content="' . $share['general']['description'] . '">' . "\n";
  $output .= "\t\t" . '<meta property="og:image" content="' . $share['general']['image'] . '" />' . "\n";
  $output .= "\t\t" . '<meta property="og:url" content="' . $seo['url'] . '">' . "\n";
  $output .= "\t\t" . '<meta property="og:site_name" content="' . $seo['site-name'] . '">' . "\n";

  // Publish meta
  // $output .= "\t\t" . '<meta property="article:section" content="' . ucfirst($postType) . '">' . "\n";
  $output .= "\t\t" . '<meta property="article:published_time" content="' . $seo['published-time'] . '">' . "\n";

  if ($seo['modified-time'] > $seo['published-time']) {
    $output .= "\t\t" . '<meta property="article:modified_time" content="' . $seo['modified-time'] . '">' . "\n";
    $output .= "\t\t" . '<meta property="og:updated_time" content="' . $seo['modified-time'] . '">' . "\n";
  }

  // Twitter cards
  $output .= "\t\t" . '<meta name="twitter:card" content="' . 'summary_large_image' . '">' . "\n";
  $output .= "\t\t" . '<meta name="twitter:title" content="' . $share['twitter']['title'] . '">' . "\n";
  $output .= "\t\t" . '<meta name="twitter:description" content="' . $share['twitter']['description'] . '">' . "\n";
  $output .= "\t\t" . '<meta name="twitter:image" content="' . $share['twitter']['image'] . '">' . "\n";

  echo $output;
}

// add_action('wp_head', 'instantiate_seo_tags', 1);




/*****************************************************
Content layout styles
- used on pages to customise options
- classes applied in page templates
******************************************************/ 
function initialize_content_layout_styles($additional_classes = false) {
  $arr = array();
  $classes = null;
  $layout = get_field('styles_layout') == 'narrow' ? 'narrow-width mlr-auto' : null;
  $text_align = get_field('styles_text_align') == 'center' ? 'text-align-m-center' : null;
  array_push($arr, $layout, $text_align, $additional_classes);

  $classes = trim(implode(" ", $arr));
  

  return $classes;
}


/******************************************
Browser Support Class
******************************************/ 
function browserSupportClass() {
	echo $_SERVER['HTTP_USER_AGENT']; 
	//using get_browser() to display capabilities of the user browser 
	$mybrowser = get_browser(); 
	print_r($mybrowser); 
}



// function my_nav_menu_submenu_css_class( $classes ) {
//   $classes[] = 'my-new-submenu-class';
//   return $classes;
// }
// add_filter( 'nav_menu_submenu_css_class', 'my_nav_menu_submenu_css_class' );



/******************************************
Theme options page
******************************************/ 
// if (function_exists('acf_add_options_page')) {

//     // Add a top menu page
//     acf_add_options_page(
//         array(
//             'page_title' => 'Theme Options',
//             'menu_title' => 'Theme Options',
//             'menu_slug'  => 'theme-options',
//             'redirect'   => false,
//             'capability' => 'administrator',
//             'position'   => 5.4
//         )
//     );
// }

function get_breadcrumbs() {
  $crumbs = array(
    0 => '<a href="' . home_url() . '" rel="nofollow">Home</a>'
  );

	if (is_category() || is_single()) {
		$crumbs[1] = '<a href="/blog/" rel="nofollow" itemprop="item">Blog</a>';
    if (is_single()) {
      $crumbs[2] = get_the_title();
    }
  } 
  elseif (is_page()) {
    $crumbs[1] = get_the_title();
  } 
  elseif (is_search()) {
    $crumbs[1] = "Search Results for: ";
    $crumbs[2] = the_search_query();
  }

  $breadcrumbs = '<ol class="breadcrumbs__list">';
  $i = 1;
  foreach ($crumbs as $crumb) {
    if ($crumb) {
        $breadcrumbs .= '<li class="breadcrumbs__item" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">';
        $breadcrumbs .= '<span itemprop="name">' . $crumb . '</span>';
        $breadcrumbs .= '<meta itemprop="position" content="' . $i . '">';
        $breadcrumbs .= '</span></li>';
        if ($i < count($crumbs)) {
          $breadcrumbs .= '<li class="breadcrumbs__separator"><span>/</span></li>';
          $i++;
        }
    }
  }
  $breadcrumbs .= '</ol>';

  return $breadcrumbs;
    
}



// Strip opening and closing quotes from string
function stripEnclosingQuotes($str) {

  $quote_types = array('"', '\'');
  $str = esc_html(trim($str));

  for ( $i = 0; $i < count($quote_types); $i++ ) {
    if ($str[0] === $quote_types[$i] && $str[-1] === $quote_types[$i]) {
        $str = substr($str, 1); // remove first char
        $str = substr($str, 0, -1); // remove last char
    }
  }
  return $str;
  
}



/******************************************
Run URL through some validation
- Converts to an actual url if only prepended with www.
- Checks whether internal or externally pointed
******************************************/
function initialize_url_validation($input) {
  $arr = array();
  $internal_host = $_SERVER['HTTP_HOST'];
  $input_components = parse_url($input);
  
  $host_pattern = '#^http(s)?://#';
  $www_pattern = '/^www\./';

  // For the purpose of internal / external link checking
  // Need to isolate just the domain of the url, without http/s or www.
  // So compares with domain of the site
  // If same, then an internal link but if not then its external

  // 1. If prefixed with http:// or https://
  if(isset($input_components['host'])) {

    $input_host = $input_components['host'];
  
    // If has a www as well, strip it out
    if(preg_match($www_pattern, $input_host)) {
      $input_domain = preg_replace($www_pattern, '', $input_host);
    }
    // Otherwise, just strip out the http:// or https://
    else {
      $input_domain = preg_replace($host_pattern, '', $input_host);
    }
  }
  // 2. If NOT prefixed with http:// or https://
  else {
    // Then if there is still a www. at the start, strip it out
    if(preg_match($www_pattern, $input_components['path'])) {
      $input = 'http://' . $input_components['path'];
      $input_components = parse_url($input);
      $input_host = $input_components['host'];
      $input_domain = preg_replace($www_pattern, '', $input_host);
    }
    // If no www either then we have to assume it was intended as a relative URL (so setting this to internal host)
    else {
      $input_domain = $internal_host;
    }
  }
 
  if(strcasecmp($input_domain, $internal_host) == 0 ) {
    // is internal;
    $arr['internal-link'] = true;
    $arr['external-link'] = false;
  }
  else {
    // is external;
    $arr['internal-link'] = false;
    $arr['external-link'] = true;
  }

  $arr['url'] = $input;

  return $arr;

}





function get_header_styles() {

  $header_theme = get_field('header_theme');
  $header_transparent_bg = get_field('header_transparent_bg');
  $arr = array();

  // Set classes
  $arr[] =  ($header_theme) ? "is-" . $header_theme : "is-light"; // default: "is-light"
  $arr[] = ($header_transparent_bg) ? "is-transparent" : "";

  $classes = implode(" ", $arr);

  return $classes;
  
}


function get_content_styles() {
    
  // might also want to add justification at some point. For now, will default this to center
  $content_width = get_field('content_width');
  $content_classes = get_field('custom_content_classes');
  if ( ! $content_width && ! $content_classes ) return;

  $arr = array();
  $width_class_map = array(
    "full"    => "",
    "wide"    => "container",
    "narrow"  => "container is-narrow"
  );

  // Set width classes
  $arr[] = $width_class_map[$content_width];
  $classes = implode(" ", $arr);

  // Add custom classes
  $classes .= " " . $content_classes;

  return $classes;
}
<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/******************************************
Content Image
******************************************/ 
function image($parms=[]) {
  
  // ID or IDs of image attachment:
  $ids     = !is_array($parms['id'])               ? array($parms['id'])     : $parms['id'];
  $url     = isset($parms['url'])                  ? $parms['url']           : null; 
  $wrapper_class = isset($parms['wrapper-class'])  ? $parms['wrapper-class'] : null; 
  $image_class = isset($parms['image-class'])      ? $parms['image-class']   : null; 
  $float   = isset($parms['float'])                ? $parms['float']         : null;    // <- Valid values: 'left', 'right'
  $width   = isset($parms['width'])                ? $parms['width']         : null;    // <- Desired width (px but don't add units). Leave empty for actual image size. Height is automatically calculated if not set
  $height   = isset($parms['height'])              ? $parms['height']        : null;    // <- Desired width (px but don't add units). Leave empty for actual image size. Height is automatically calculated if not set
  // $margin_bottom  = isset($parms['margin-bottom']) ? $parms['margin-bottom'] : true;    // <- Valid values: true, false.
  $layout  = isset($parms['layout'])               ? $parms['layout']        : null;    // <- Valid values: 'row', 'column'
  $border  = isset($parms['border'])               ? $parms['border']        : true;
  $lightbox = isset($parms['lightbox'])            ? $parms['lightbox']      : true;  // <- Valid values: true, false
  $wrapper_tag = isset($parms['container-tag'])    ? $parms['container-tag'] : null;  // <- Valid values: true, false
  $attachment_size = isset($parms['attachment-size']) ? $parms['attachment-size'] : 'large';


  
  if( $ids ): 
    
    ob_start();

    foreach($ids as $id) :
    // Variables
    $image['src']     = wp_get_attachment_image_src($id, $attachment_size)[0];
    $image['srcset']  = wp_get_attachment_image_srcset($id);
    $image['alt']     = get_post_meta($id, '_wp_attachment_image_alt', TRUE);
    $image['layout']  = $layout ? '-' . $layout : null;
    $image['border']  = $border === true ? 'border' : null;
    $image['container-tag'] = $wrapper_tag ? $wrapper_tag : 'div';
    // $image['margin-bottom']  = $margin_bottom === true ? 'mb-8' : $margin_bottom; // 2rem

    // Container Classes - concatenates if in an array:
    if(is_array($wrapper_class)) {
      $image['wrapper-class'] = join(" ", $wrapper_class);
    }
    else {
      $image['wrapper-class'] = $wrapper_class;
    }


    // Image Classes - concatenates if in an array:
    if(is_array($image_class)) {
      $image['image-class'] = join(" ", $image_class);
    }
    else {
      $image['image-class'] = $image_class;
    }

    // Set the URL (either to url parm or lightbox img)
    if($url) {
      $image['url'] = $url;
    }
     elseif($lightbox) {
      $image['url'] = wp_get_attachment_image_src($id, 'full')[0];
    }
     else {
      $image['url'] = false;
    }
    

    // // Set bottom margins
    // if(!$margin_bottom) {
    //   $image['margin'] = 'ml-5';
    // }
    // elseif($margin == 'right') {
    //   $image['margin'] = 'mr-5';
    // }

    
    // SET WIDTH AND HEIGHTS of IMG TAG:

      // No explicit width or height set
      if(!$width && !$height) {
        $image['width'] = wp_get_attachment_image_src($id, $attachment_size)[1]; // '1' = width
        $image['height'] = round(($image['width']/wp_get_attachment_image_src($id, $attachment_size)[1]) * wp_get_attachment_image_src($id, $attachment_size)[2]); // <- auto calculating height from width
      }
      // Width set but no height
      else if($width && !$height) {
        $width_unit_arr = preg_split('/(?<=[0-9])(?=[^0-9]+)/i', $width); // splitting out number from unit
        $image['width'] = $width_unit_arr[0];
        $image['height'] = round(($image['width']/wp_get_attachment_image_src($id, $attachment_size)[1]) * wp_get_attachment_image_src($id, $attachment_size)[2]); // <- auto calculating height from width
      }
      // Height set but no width
      else if(!$width && $height) {
        $height_unit_arr = preg_split('/(?<=[0-9])(?=[^0-9]+)/i', $height); // splitting out number from unit
        $image['height'] = $height_unit_arr[0];
        $image['width'] = round(($image['height']/wp_get_attachment_image_src($id, $attachment_size)[2]) * wp_get_attachment_image_src($id, $attachment_size)[1]); // <- auto calculating height from width
      }
      // Both width and height set
      else {
        $width_unit_arr = preg_split('/(?<=[0-9])(?=[^0-9]+)/i', $width); // splitting out number from unit
        $image['width'] = $width_unit_arr[0];

        $height_unit_arr = preg_split('/(?<=[0-9])(?=[^0-9]+)/i', $height); // splitting out number from unit
        $image['height'] = $height_unit_arr[0];


      // THIS WAS CALCULATING WHETHER A % OR PX VALUE GIVEN, HOWEVER THINK WE SHOULD ALWAYS PUT IT IN PX; % SHOULD ONLY BE ASSIGNED ON IMAGE WRAPPER. PLUS IF WIDTH ATTRIBUTE LEFT BLANK, ALL IMAGES WILL AUTOMATICALLY TRY AND FILL THE CONTAINER. THOUGHT IT WAS NECESSARY TO CONVERT 100% IN TO A SPECIFIC PIXEL VALUE TO STOP BROWSER MOVING AROUND AS IT LOADS IMAGES BUT LOOKS LIKE THIS ISNT TOTALLY NECESSARY NOW AS CSS LOADS PRETTY QUICK....
      // $value_unit_arr = preg_split('/(?<=[0-9])(?=[^0-9]+)/i', $width);
      // if(!empty($value_unit_arr[1]) && $value_unit_arr[1] == '%') {
      //   $image['width'] = ceil(720 * ($value_unit_arr[0]/100)); // <- 720 is the width of entry-content
      // }
      // else {
      //   $image['width'] = $value_unit_arr[0];
      // }
    }
    

    if($float) {
      $image['float'] = 'float-m-' . $float; // no float on mobile
    }

    // END variables


    // Start HTML:
    ?>

<?php // 1. Image wrap (just easier if has one) ?>
    <<?php echo $image['container-tag'];?> class="image-wrapper<?php 
            if(!empty($image['float'])) echo ' ' . $image['float'];
            if(!empty($image['margin'])) echo ' ' . $image['margin'];
            if(!empty($image['layout'])) echo ' ' . $image['layout'];
            if(!empty($image['wrapper-class'])) echo ' ' . $image['wrapper-class'];
            // if(!empty($image['margin-bottom'])) echo ' ' . $image['margin-bottom'];?>">


  <?php // 2. Optional link wrap ?>
    <?php if($image['url']) : ?>
      <a class="image-link" href="<?php echo esc_url($image['url']); ?>">
    <?php endif; ?>


      <?php // 3. Image itself ?>
        <img 
          class="<?php
          if(!empty($image['border'])) echo $image['border'] . ' '; // <- Adds borderless class if border set to anything other than true
          if(!empty($image['image-class'])) echo $image['image-class'] . ' ';
          if($lightbox) echo 'lightbox'?>" 

          src="<?php echo esc_url($image['src']); ?>" 
          srcset="<?php echo $image['srcset']; ?>" 
          sizes="(max-width: 320px) 300px, (min-width: 321px) and (max-width: 600px) 600px, 900px" 
          alt="<?php echo esc_attr($image['alt']); ?>" 
          width="<?php echo $image['width']; ?>" 
          height="<?php echo $image['height']; ?>"
        />
      <?php // End image ?>   


    <?php // End link ?>  
    <?php if($image['url']) : ?>
      </a>
    <?php endif; ?>
 

  <?php // End parent wrap ?>   
  </<?php echo $image['container-tag'];?>>


  <?php endforeach;

endif;

echo ob_get_clean();

}


/******************************************
Callouts
- eg. notes, tips, warning etc
- valid $type values are the callout variant class names (without dash prefix), so add more as necessary
- currently accepted values for $type: note
******************************************/ 
function callout($type, $string) {
  
  ob_start(); ?>

  <div class="callout <?php echo '-' . strtolower($type); ?>">
    <div class="sub-container">
      <!-- <div class="callout__title" role="heading"><?php // echo ucfirst($type) . ':'; ?></div>  -->
      <p class="callout__text">
        <span class="callout__title"><?php echo ucfirst($type) . ':'; ?></span><?php 
        echo $string; ?>
      </p>
    </div>
  </div>

  <?php echo ob_get_clean(); 
}


/******************************************
Generate Breadcrumbs
******************************************/
function instantiate_breadcrumb_items() {

// Vars
global $post;
$id = $post->ID;
$crumbs = array();

$crumb1 = false;
$crumb2 = false;
$crumb3 = false;

$crumb_parent_link_map = array(
  'post'    => '/blog',
  'gallery' => '/gallery'
);

$crumb1 = array(
  'label' => 'Home',
  'link'  => '/'
);

if(is_page($id)) {

  if( $post->post_parent ) {
    $crumb2 = array(
      'label' => get_the_title($post->post_parent),
      'link'  => get_permalink($post->post_parent)
    );
    $crumb3 = array(
      'label' => get_the_title($id),
    );
  }

  else {
    $crumb2 = array(
      'label' => get_the_title($id),
    );
  }
}

else if(is_single($id)) {

  if(is_product($id)) {
    $product = wc_get_product($id);

    // Book
    if(has_term('books', 'product_cat', $id)) {
      $crumb2 = array(
        'label' => "Books",
        'link'  => "/books"
      );
    }
    // Free Activity
    else if($product->is_downloadable('yes') && 
      ($product->get_regular_price() == 0 || $product->get_regular_price() == null || $product->get_sale_price() != null)) {
    
      $crumb2 = array(
        'label' => "Free Activities",
        'link'  => "/free-activities"
      );
    }
    // For now, just do shop (as currently no other type of product so should never appear)
    else {
      $crumb2 = array(
        'label' => "Shop",
        'link'  => "/shop"
      );
    }
    $crumb3 = array(
      'label' => get_the_title($id),
    );
  }
  else {
    $crumb2 = array(
      'label' => get_post_type_object(get_post_type($id))->labels->singular_name,
      'link'  => get_archive_link(get_post_type_object(get_post_type($id))->name)
    );
    $crumb3 = array(
      'label' => get_the_title($id),
    );
  }
}


// Add all 3 crumbs to breadcrumbs array
array_push($crumbs, $crumb1, $crumb2, $crumb3);

// Remove any empty items
$crumbs = array_filter($crumbs);

// Initialize html
ob_start();
$length = count($crumbs);
for ($i = 0; $i < $length; $i++) : ?>

  <?php if($i < ($length - 1) ) : ?>

    <span class="breadcrumbs__item">
      <a class="breadcrumbs__link" href="<?php echo $crumbs[$i]['link']; ?>"><?php
        echo $crumbs[$i]['label'];
      ?></a>
    </span>

    <span class="breadcrumbs__separator"></span>
  
  <?php else : // Last crumb ?>

    <span class="breadcrumbs__item -last"><?php
      echo $crumbs[$i]['label'];
    ?></span>

  <?php endif; 

endfor;

echo ob_get_clean();
}






/******************************************
Share buttons
******************************************/
function initialize_share_buttons($parms = []) {

  // $parm vars
  $facebook = isset($parms['facebook']) ? false : true;
  $twitter = isset($parms['twitter']) ? false : true;
  $pinterest = isset($parms['pinterest']) ? false : true;
  $email = isset($parms['email']) ? false : true;
  $whatsapp = isset($parms['whatsapp']) ? false : true;

  // vars
  $currentPageURL = get_full_url(false);
  $currentPageURLEndoded = urlencode($currentPageURL);
  $share = get_share_meta();

  ob_start(); ?>

  <div class="share-buttons ">


    <?php // Facebook
    if($facebook) : ?>
    <div class="share-buttons__item -facebook">
      <a href="#" 
        id="js-share-facebook-link" 
        class="icon icon-only -facebook" 
        data-href="<?php echo $currentPageURL; ?>" 
        data-layout="button_count" 
        title="Share on Facebook"></a>
    </div>
    <?php endif; ?>


    <?php // Twitter
    if($twitter) : ?>
    <div class="share-buttons__item -twitter">
      <a href="https://twitter.com/share"
        class="js-share-twitter-link || icon icon-only -twitter" 
        title="Share on Twitter"></a>
    </div>
    <?php endif; ?>


    <?php // Pinterest
    if($pinterest) : ?>
    <div class="share-buttons__item -pinterest">
      <a href="https://www.pinterest.com/pin/create/button/" 
        class="icon icon-only -pinterest-full"
        data-pin-custom="true" 
        data-pin-do="buttonPin" 
        data-pin-media="<?php echo $share['pinterest']['image']; ?>"
        data-pin-id="0000001"
        title="Share on Pinterest"></a>
    </div>
    <?php endif; ?>


    <?php // Email
    if($email) : ?>
    <div class="share-buttons__item -email">
      <a href="mailto:?subject=Check out this post on Animation Juice&amp;body=Hey, I thought you might like this post:%0D%0A%0D%0A<?php echo $currentPageURLEndoded; ?>" 
        class="icon icon-only -envelope-full" 
        target="_blank" 
        title="Share by Email"></a>
    </div>
    <?php endif; ?>


    <?php // Whatsapp
    if($whatsapp) : ?>
    <div class="share-buttons__item -whatsapp">
      <a href="https://api.whatsapp.com/send?text=Hey, I thought you might like this post: <?php echo $currentPageURLEndoded; ?>" 
        class="js-share-whatsapp-link || icon icon-only -whatsapp-full" 
        target="_blank" 
        title="Share on Whatsapp"></a>
    </div>
    <?php endif; ?>

  </div>

  <!-- <script>
document.getElementById('js-share-facebook-link').onclick = function() {
  window.open('https://www.facebook.com/sharer/sharer.php', '_blank', "width=200,height=100");
};
</script> -->

  <?php 
    echo ob_get_clean();

}




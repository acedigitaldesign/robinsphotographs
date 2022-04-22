<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
// ID or IDs of image attachment:
  $ids     = !is_array($args['id'])               ? array($args['id'])     : $args['id'];
  $url     = isset($args['url'])                  ? $args['url']           : null; 
  $wrapper_class = isset($args['wrapper-class'])  ? $args['wrapper-class'] : null; 
  $image_class = isset($args['image-class'])      ? $args['image-class']   : null; 
  $float   = isset($args['float'])                ? $args['float']         : null;    // <- Valid values: 'left', 'right'
  $width   = isset($args['width'])                ? $args['width']         : null;    // <- Desired width (px but don't add units). Leave empty for actual image size. Height is automatically calculated if not set
  $height   = isset($args['height'])              ? $args['height']        : null;    // <- Desired width (px but don't add units). Leave empty for actual image size. Height is automatically calculated if not set
  // $margin_bottom  = isset($args['margin-bottom']) ? $args['margin-bottom'] : true;    // <- Valid values: true, false.
  $layout  = isset($args['layout'])               ? $args['layout']        : null;    // <- Valid values: 'row', 'column'
  $border  = isset($args['border'])               ? $args['border']        : true;
  $lightbox = isset($args['lightbox'])            ? $args['lightbox']      : true;  // <- Valid values: true, false
  $wrapper_tag = isset($args['container-tag'])    ? $args['container-tag'] : null;  // <- Valid values: true, false
  $attachment_size = isset($args['attachment-size']) ? $args['attachment-size'] : 'large';


  
  if( $ids ): 
    
    // ob_start();

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
    <<?= $image['container-tag'] ?> class="image-wrapper<?php 
            if(!empty($image['float'])) echo ' ' . $image['float'];
            if(!empty($image['margin'])) echo ' ' . $image['margin'];
            if(!empty($image['layout'])) echo ' ' . $image['layout'];
            if(!empty($image['wrapper-class'])) echo ' ' . $image['wrapper-class'];
            // if(!empty($image['margin-bottom'])) echo ' ' . $image['margin-bottom'];?>">


  <?php // 2. Optional link wrap ?>
    <?php if($image['url']) : ?>
      <a class="image-link" href="<?= esc_url($image['url']); ?>">
    <?php endif; ?>


      <?php // 3. Image itself ?>
        <img 
          class="<?php
          if(!empty($image['border'])) echo $image['border'] . ' '; // <- Adds borderless class if border set to anything other than true
          if(!empty($image['image-class'])) echo $image['image-class'] . ' ';
          if($lightbox) echo 'lightbox'?>" 

          src="<?= esc_url($image['src']) ?>" 
          srcset="<?= $image['srcset'] ?>" 
          sizes="(max-width: 320px) 300px, (min-width: 321px) and (max-width: 600px) 600px, 900px" 
          alt="<?= esc_attr($image['alt']) ?>" 
          width="<?= $image['width'] ?>" 
          height="<?= $image['height'] ?>"
        />
      <?php // End image ?>   


    <?php // End link ?>  
    <?php if($image['url']) : ?>
      </a>
    <?php endif; ?>
 

  <?php // End parent wrap ?>   
  </<?= $image['container-tag']?>>


  <?php endforeach;

endif;
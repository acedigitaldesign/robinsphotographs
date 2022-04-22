<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Init thumbnail attachment id
$thumbnail_ID = get_post_thumbnail_id();
if ( ! has_post_thumbnail() ) {
  $thumbnail_ID = 514; // <- Placeholder attachment id
}

// Set defaults
$default_args = array(
  "src" => wp_get_attachment_image_src($thumbnail_ID, 'full')[0],
  "srcset" => wp_get_attachment_image_srcset($thumbnail_ID),
  "alt" => get_post_meta($thumbnail_ID, '_wp_attachment_image_alt', TRUE),
  "sizes" => "(max-width: 349px) 300px, (max-width: 600px) and (min-width: 350px) 600px, 700px",
  "position_y" => get_field("featured_img_vertical_position") 
);

// Merge passed-in args with defaults
$image = wp_parse_args($args, $default_args);

?>

<div class="featured-image" 
     style="background-image: url('<?= $image['src'] ?>'); background-position-Y: <?= $image['position_y'] ?>%;" 
     role="img" 
     aria-label="<?= $image['alt'] ?>"
    >
  <!-- <img 
    class="featured-image__img" 
    itemprop="thumbnailUrl" 
    src="<? // $image['src'] ?>" 
    srcset="<? // $image['srcset'] ?>" 
    sizes=<? // $image['sizes'] ?>
    alt="<? // $image['alt']; ?>"> -->
</div>







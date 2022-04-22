<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php 
$testimonial_star_rating = get_field("testimonial_star_rating", esc_html($args['id']));
$testimonial_text = get_field("testimonial_text", esc_html($args['id']));
$testimonial_author = get_field("testimonial_author", esc_html($args['id']));
$star_rating = array(
  "user-rating" => get_field("testimonial_star_rating", esc_html($args['id'])),
  "max-rating" => 5,
  "rating-type" => 'testimonial'
);
?>

<div class="testimonial <?= $args['class'] ?>">
  <?php get_template_part( 'template-parts/star-rating', null, $star_rating) ?>
  <div class="testimonial__content">
    "<?= stripEnclosingQuotes($testimonial_text) ?>"
  </div>
  <div class="testimonial__author">
    <?= "- " . $testimonial_author ?>
  </div>
</div>

<?php 





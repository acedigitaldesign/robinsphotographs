<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php 
// Set defaults
$default_args = array(
  "posts_per_page" => '-1',
  "order" => 'ASC'
);

// Merge passed-in args with defaults
$faq = wp_parse_args($args, $default_args);

// Start FAQs accordions
$query_args = array(
  'post_type' => 'faq',
  'posts_per_page' => $faq['posts_per_page'],
  'order' => $faq['order'],
);

$the_query = new WP_Query( $query_args );
if($the_query->have_posts() ) : ?>

  <div class="faqs mlr-m-auto">

  <?php while ( $the_query->have_posts() ) : 
    $the_query->the_post(); ?>

      <div class="accordion" data-accordion>
        <div class="accordion__toggle" data-accordion-toggle><strong><?php the_title() ?></strong></div>
        <div class="accordion__content" data-accordion-content>
          <?php the_content() ?>
        </div>
      </div>

    <?php endwhile; 
    wp_reset_postdata(); ?>

  </div>

<?php endif;
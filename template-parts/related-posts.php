<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
// Set defaults
$default_args = array(
  "heading" => "You might also like...",
  "section-class" => "mb-s-10"
);

// Merge passed-in args with defaults
$related = wp_parse_args($args, $default_args);

?>

<section class="related-posts <?= $related['section-class'] ?>">

  <header class="related-posts__header">
    <h2 class="related-posts__heading h3"><?= $related["heading"] ?></h2>
    <a href="<?= get_permalink(427) ?>" class="related-posts__link icon-arrow-long-right">All Articles</a>
  </header>

  <div class="related-posts__posts">
    <?php 
      // Start Post cards
        $args = array(
          'post_type' => 'post',
          'posts_per_page' => '3',
          'post__not_in' => array(get_the_id())
          // 'order' => 'ASC'
        );

      $the_query = new WP_Query( $args );
      if($the_query->have_posts() ) : ?>

        <div class="row ">

        <?php while ( $the_query->have_posts() ) : 
          $the_query->the_post(); ?>

          <div class="col-l-4 col-m-6">
            <?php get_template_part('template-parts/post-card') ?>
          </div>
          
          <?php
          endwhile; 
          wp_reset_postdata(); ?>

        </div><?php // End Row ?>

      <?php endif; // End Post Cards?>
  </div>
  
</section>

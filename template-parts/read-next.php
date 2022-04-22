<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
  
  <?php 
    // Get Context term
    $context_term = get_the_terms($post->ID, 'context')[0]->name;

    // Get Category terms and put the slugs into their own array
    $terms = initialize_terms();
    $term_slugs = array();
    foreach ($terms as $term) {
      array_push($term_slugs, $term['slug']);
    }

    $args = array(
      'post_type' => 'post',
      'post__not_in' => array(get_the_ID()), // <- Exclude this post from query
      'posts_per_page' => '4',

      'tax_query' => array(
        'relation' => 'AND',
        array(
          'taxonomy' => 'context',
          'field'    => 'term_id',
          'terms'    => $context_term
        ),
        array(
          'taxonomy' => 'category',
          'field' => 'slug',
          'terms' => $term_slugs
        )
      )
    );

  // Then the HTML for the new query is output
  $the_query = new WP_Query( $args );
  if($the_query->have_posts() ) : ?>

    <div class="read-next">
    <div class="read-next__heading">Read next</div>
    <ul class="read-next__list"> 

     <?php while ( $the_query->have_posts() ) : 
        $the_query->the_post(); 

        ob_start(); ?>

          <li class="read-next__item">
            <a><?php echo get_the_title(); ?></a>
          </li>

      <?php

        echo ob_get_clean();
      endwhile; 
      wp_reset_postdata();  ?>

  </ul>
  <a href="/articles" class="read-next__all-articles-link">All Articles</a>
  </div>

  <?php endif; ?>
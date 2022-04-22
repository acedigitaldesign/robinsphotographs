<?php
/******************************************
Template name: Home
@package Robins Photographs
******************************************/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php get_header(); ?>
  <?php while ( have_posts() ) : the_post(); ?>

  <main>
    <?php the_custom_content(); ?>
  </main>

  <?php endwhile; // End of the loop. ?>

<?php get_footer();
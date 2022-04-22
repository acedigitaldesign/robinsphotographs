<?php
/******************************************
Template name: Cart
@package Robins Photographs
******************************************/ 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php get_header(); ?>

  <?php while ( have_posts() ) : the_post(); 
  
  get_template_part('template-parts/site-header');

  ?>
	
  <main>
    <div class="content container pt-s-5 pt-xs-4">
      <h1 class="text-align-center mb-m-9 mb-5"><?= get_the_title() ?></h1>
      <?php the_content(); ?>
    </div>
  </main>

  <?php endwhile; // End of the loop. ?>

<?php get_footer();
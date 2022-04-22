<?php
/******************************************
Template name: Checkout
@package Robins Photographs
******************************************/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php get_header(); ?>

  <?php while ( have_posts() ) : the_post(); 
  
  get_template_part('template-parts/site-header-simple');

  ?>
	
  <main class="container pt-0">
    <?php the_content(); ?>
  </main>


  <?php endwhile; // End of the loop. ?>

<footer id="colophon" class="site-footer">
  <?php //get_template_part('template-parts/copyrights'); ?>
</footer>

  <?php wp_footer(); ?>

</div> <!-- End of #page -->
</body>
</html>

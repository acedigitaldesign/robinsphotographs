<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Cartoon Kids
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<footer id="colophon" class="site-footer">
    
    <?php
      global $post;
      if ( $post->ID != 22 ) {
        get_template_part('template-parts/site-footer');
      }
    ?>
    <?php // get_template_part('template-parts/prefooter'); ?>
    <?php // get_template_part('template-parts/next-previous'); ?>
    <?php get_template_part('template-parts/copyrights'); ?>

  </footer>

  <?php wp_footer(); ?>

</div> <!-- End of #page -->
</body>
</html>

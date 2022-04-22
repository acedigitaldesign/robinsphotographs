<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Robins Photographs
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();

    get_template_part('template-parts/site-header'); 

  ?>
  
  <main>
    
      
      <?php get_template_part('template-parts/entry-header', get_post_type() ); ?>      

      <div class="container pt-0">
      <article>
        <?php the_content(); ?>
      </article>
      
      <?php get_template_part('template-parts/entry-footer'); ?>
      <?php get_template_part('template-parts/signup-featured'); ?>
      <?php get_template_part('template-parts/related-posts'); ?>


      <?php	// If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) :
          comments_template();
        endif; ?>

      </div>

<?php endwhile; // End of the loop. ?>

</main>

<?php // get_template_part('template-parts/signup-post-bottom'); ?>

<?php get_footer();
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
<?php // get_template_part('template-parts/hero'); ?>

<?php while ( have_posts() ) : the_post();

    get_template_part('template-parts/site-header'); 

  ?>
  
  <main>
    <div class="container pt-0 pb-m-10">

      
      <?php get_template_part('template-parts/entry-header', get_post_type() ); ?>      

      <div class="row grid-spacing-l-7">
        <div class="col-l-8 col-xs-12">
          <article>

            <?php the_content(); ?>
            <?php // get_template_part('template-parts/entry-footer'); ?>
          </article>

          <?php // include( locate_template( 'template-parts/read-next.php', false, false ) ); ?>
          <?php // include( locate_template( 'template-parts/related-posts.php', false, false ) ); ?>

          <?php	// If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
              comments_template();
            endif; ?>
        </div>
        <div class="col-l-4 col-xs-12">
          <aside>
            <div class="module module-restoration">
              <div class="sub-container">
                <?php image([
                "id"=>10, 
                "width"=>170, 
                "height"=>"auto", 
                "border"=>false, 
                "lightbox"=>false,
                "wrapper-class"=>"mb-3"
                ]);?>
                <h3 class="module-restoration__title">We digitally repair your old photographs</h3>
                <a class="button button-primary">Learn More</a>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </div>

<?php endwhile; // End of the loop. ?>

</main>

<?php // get_template_part('template-parts/signup-post-bottom'); ?>

<?php get_footer();
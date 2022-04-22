<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Robins Photographs
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<?php get_header(); ?>

  <?php while ( have_posts() ) : the_post();

  switch (get_the_id()) {
    case 22: get_template_part('template-parts/site-header-simple'); break;
    default: get_template_part('template-parts/site-header' ); break;
  }
  ?>

  <?php // get_template_part('template-parts/breadcrumbs-bar' ); ?>
  
  <main>
    <div class="content <?= get_content_styles() ?>">

      <?php    
      $content_source = esc_html(get_field('content_source'));
      $custom_content = esc_html(get_field('custom_content'));

      if ( $content_source == "external"  && $custom_content ) {
        the_custom_content();
      }
      else { ?>
        <h1 class="text-align-s-center mb-6"><?= get_the_title() ?></h1>
        <?php the_content();
      }
      ?>

    </div>
  </main>

  <?php endwhile; // End of the loop. ?>

<?php get_footer();
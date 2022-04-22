<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php 
// Check rows exists.
if( have_rows('image_credits') ): ?>

<div class="image-credits">
  <h4 class="image-credits__heading">Image Credits</h4>
  <ul class="image-credits__list list-unstyled">

  <?php
  // Loop through rows.
  while( have_rows('image_credits') ) : the_row();

      // Load sub field value.
      $image_name = stripQuotes(get_sub_field('credit_image_name'));
      $source = get_sub_field('credit_source');
      $source_url = get_sub_field('credit_source_url');

      // if any image credits
      if ( $image_name || $source || $source_url ) : ?>

      <li class="image-credits__item"> 
      
      <?php
        // set url link text
        if ( $image_name ) {
          $link_text = $image_name;
        }
        elseif ( $source ) {
          $link_text = $source;
        }
        else {
          $link_text = $source_url;
        }

        // set anchor html
        $anchor_html = '<a class="image-credits__link" href="' . $source_url . '" rel="noopener nofollow" target="_blank">' . $link_text . '</a>';

        if ( $source_url ) {
          echo $anchor_html;
          if ( $source && $link_text != $source ) {
            echo ' - ' . $source;
          }
        }
        elseif ( $image_name ) {
          echo $image_name;
          if ( $source ) {
            echo ' - ' . $source;
          }
        }
        elseif ( $source ) {
          echo $source;
        }

        ?>

    </li>

    <?php endif; ?>
  <?php endwhile; ?>

</ul>
</div>

<?php endif;

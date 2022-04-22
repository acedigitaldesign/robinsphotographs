<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php 
// Check rows exists.
if( have_rows('footnotes') ): ?>

<div class="footnotes">
  <h4 class="footnotes__heading">Footnotes</h4>
  <ul class="footnotes__list list-unstyled">

  <?php
  // Loop through rows.
  $i = 1;
  while( have_rows('footnotes') ) : the_row();

      // Load sub field value.
      $text = get_sub_field('footnote_text');
      $title = stripQuotes(get_sub_field('footnote_source_title'));
      $source = get_sub_field('footnote_source');
      $source_url = get_sub_field('footnote_source_url');
      

      // if any footnotes credits
      if ( $text || $title || $source || $source_url ) : ?>

    <li id="footnote-<?= $i ?>" class="footnotes__item" data-id="footnote-<?= $i ?>"> 
      
      <?php
        // set url link text
        if ( $title ) {
          $link_text = $title;
        }
        elseif ( $source ) {
          $link_text = $source;
        }
        else {
          $link_text = $source_url;
        }

        // set anchor html
        $anchor_html = '<a class="footnotes__link" href="' . $source_url . '" rel="noopener nofollow" target="_blank">' . $link_text . '</a>';

        echo '<span class="footnote__marker"><a class="link-primary" href="#footnote-marker-' . $i . '">[' . $i . ']</a></span> ';

        // echo '<div class="footnote__body">';

        if ( $text ) {
          echo '<span class="footnote__text">' . $text . '</span> ';
        }
        if ( $title || $source || $source_url ) {
          echo '<span class="footnote__source">Source: ';
          
          if ( $source_url ) {
            echo $anchor_html;
            if ( $source && $link_text != $source ) {
              echo ' - ' . $source;
            }
          }
          elseif ( $title ) {
            echo $title;
            if ( $source ) {
              echo ' - ' . $source;
            }
          }
          elseif ( $source ) {
            echo $source;
          }
          echo '</span>';
        }

        // echo '</div>';
        
        $i++;

        ?>

    </li>

    <?php endif; ?>
  <?php endwhile; ?>

</ul>
</div>

<?php endif;
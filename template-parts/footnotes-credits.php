<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if( ! have_rows('footnotes') && ! have_rows ('image_credits') ) return; ?>

<aside class="footnotes-credits" data-accordion data-footnotes>
  <h3 class="h4 footnotes-credits__toggle" data-accordion-toggle>Footnotes & Image Credits</h3>
  <div class="footnotes-credits__content" data-accordion-content>
    <?php get_template_part('template-parts/footnotes') ?>
    <?php get_template_part('template-parts/image-credits') ?>
  </div>
</aside>
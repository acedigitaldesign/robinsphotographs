<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="entry-header">

  <div class="entry-header__title container pb-0">
    <h1 class="display-5"><?= get_the_title() ?></h1>
  </div>
  
  <div class="entry-header__content container ptb-0">
    <?php 
      get_template_part('template-parts/entry-meta');
      get_template_part('template-parts/share-buttons');
      get_template_part('template-parts/affiliate-disclosure');
      get_template_part('template-parts/featured-image');
    ?>
    <div class="separator-s-center mt-6 mb-3"></div>
  </div>
    
</div>
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="popup-signup__container container" data-popup="signup" data-active="false">
  <div class="popup-signup">

    <div class="popup-signup__close" data-close></div>

    <?php $header_img = wp_get_attachment_image_src(365, 'medium')[0] ?>
    <div class="popup-signup__header-image" style="background-image: url('<?= $header_img ?>');"></div>

    <div class="sub-container">
      <?php get_template_part('template-parts/mailerlite-form-newsletter') ?>
    </div>

  </div>
</div>

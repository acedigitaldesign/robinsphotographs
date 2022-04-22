<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<section class="copyrights">
  <div class="container ptb-4 opacity-8">
    <div class="row">

      <div class="col-m-7">
        <p class="copyrights__copyright-notice">Copyright © <?php echo date("Y") . " " . get_bloginfo('name');?>.  All Rights Reserved.
        <span>Website by <span class="copyrights__developer-link"><a href="https://acedigitaldesign.com">Ace Digital Design</a></span>.</span></p>
      </div>

      <div class="col-m-5 | text-align-m-right copyrights__developer">
        <div class="image-wrapper text-align-s-right mb-0">
          <img src="<?= image_src('footer-payment-gateway-logos.png') ?>" srcset="<?= image_srcset('footer-payment-gateway-logos.png', 'footer-payment-gateway-logos.png') ?>" alt="payment gateway logos" width="210" height="21">
        </div> 
      </div>
      
    </div>

  </div>
</section>
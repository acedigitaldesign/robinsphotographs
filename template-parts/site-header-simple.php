<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<header class="site-header-simple">
  <nav role="navigation">
    <div class="site-header-simple__menu">
      <div class="container ptb-0">
          <?php image([
            "id"=>8, 
            "url"=>"/", 
            "width"=>170, 
            "height"=>"auto", 
            "border"=>false, 
            "lightbox"=>false,
            "wrapper-class"=>"mb-0"
            ]);?>
          <?php // echo do_shortcode('[woo_multi_currency_layout10 flag_size=0.3]'); ?>
      </div>

    </div>
  </nav>
</header>
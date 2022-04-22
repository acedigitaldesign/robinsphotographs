<?php
/******************************************
Mobile Menu 02: Side Panel
// Is an independant menu separate from menu on larger screens.
// Panel appears from the side of the viewport.
******************************************/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="mobile-menu" data-mobile-menu="inactive">
  <div class="container pt-m-6 pt-s-5">

    <div class="mobile-menu__close" data-mobile-menu-close></div>

    <?php image(["id"=>9, "url"=>"/", "width"=>170, "height"=>"auto",  "border"=>false,  "lightbox"=>false,"wrapper-class"=>"logo__light text-align-left"]); // light ?>

    <!-- <nav class="mobile-menu__nav"> -->
        <?php
          wp_nav_menu( array(
              'theme_location' => 'mobile-menu',
              'container'      => false,
              'menu_class'     => 'mobile-menu__nav-list',
              // 'add_li_class'   => 'primary-menu__nav-item',
              'add_li_class'   => false,
              'walker' => new submenu_wrap()
            )
          );
        ?>
    <!-- </nav> -->

    <a class="mobile-menu__cta button button-primary button-small mt-5 | icon icon-arrow-circle-full-right icon-nudge-top-n2" href="<?= get_permalink(309) ?>">Start Your Photo Restoration</a>
  </div>
</div>
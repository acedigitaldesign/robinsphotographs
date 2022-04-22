<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php $header_classes = get_header_styles(); ?>

<?php get_template_part('template-parts/mobile-menu'); ?>

<header class="site-header <?= $header_classes ?>">
  <nav role="navigation">

    <!-- <div class="secondary-menu">
    </div> -->
    
    <div class="primary-menu">
      <div class="primary-menu__row container ptb-0">
        <ul class="primary-menu__nav-list -left">
          <li class="primary-menu__logo">
            <?php
            image(["id"=>8, "url"=>"/", "width"=>170, "height"=>"auto",  "border"=>false,  "lightbox"=>false,"wrapper-class"=>"mb-0 logo__light"]); // light
            image(["id"=>9, "url"=>"/", "width"=>170, "height"=>"auto",  "border"=>false,  "lightbox"=>false,"wrapper-class"=>"mb-0 logo__dark"]); // dark
            
            ?>
          </li>
        </ul>

        <?php
          wp_nav_menu( array(
              'theme_location' => 'main-menu',
              'container'      => false,
              'menu_class'     => 'primary-menu__nav-list -center',
              // 'add_li_class'   => 'primary-menu__nav-item',
              'add_li_class'   => false,
              'walker' => new submenu_wrap()
            )
          );
        ?>

        <ul class="primary-menu__nav-list -right">
          <?php if ( ! WC()->cart->is_empty() ) : ?>
          <li class="primary-menu__cart">
            <a href="<?= WC()->cart->get_cart_url() ?> " class="icon-shopping-cart-empty icon-only icon-size-7 p-1"><span class="cart-icon__counter"><?= count(WC()->cart->get_cart_contents()) ?></span></a>
          </li>
          <?php endif; ?>
          <li class="primary-menu__mobile-menu-toggle">
            <button class="js-mobile-menu-toggle icon-burger-menu icon-size-7 icon-only p-1" data-mobile-menu-toggle ></button>
          </li>
          <li class="primary-menu__cta">
            <a class="button button-outline-primary | icon icon-arrow-circle-full-right icon-nudge-top-n2" href="<?= get_permalink(22) ?>">Start Your Repair</a>
            <a class="button button-outline-light | icon icon-arrow-circle-full-right icon-nudge-top-n2" href="<?= get_permalink(22) ?>">Start Your Repair</a>
          </li>
        </ul>

      </div>
    </div>

  </nav>

  <?php 
    // Only hide breadcrumbs if specifically set to FALSE 
    // ('true' or 'undefined' should show them by default)
    if (get_field("breadcrumbs") || get_field("breadcrumbs") === null) {
      get_template_part('template-parts/breadcrumbs-bar');
    }
  ?>

</header>
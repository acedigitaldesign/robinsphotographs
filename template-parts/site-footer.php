<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<section class="footer pt-xs-5">
  <div class="footer__container container">
    <div class="footer__logo">
      <?php image([
          "id"=>9, 
          "url"=>"/", 
          "width"=>180, 
          "height"=>"auto", 
          "border"=>false, 
          "lightbox"=>false,
          "wrapper-class"=>"mb-6 text-align-s-center text-align-xs-left"
          ]);?>
    </div>
    <div class="separator-s-center opacity-2 mb-s-8"></div>

    <div class="footer__row row grid-spacing-m-2">

        <div class="footer__column-one col-m-5 col-xs-12 mb-s-4">
          <h3 class="footer__column-heading">Who we are</h3>
          <p><strong>Robin's Photographs is an online hub for people interested in restoring and preserving their past.</strong></p>
          <p>As well as providing expert <strong><a href="#">digital photo restoration</a></strong> services, we also write articles on genealogy, family hisotory, DIY photo restoration and the latest techniques in researching and documenting your family tree.</p>
          <div class="footer__articles-link">
            <a href="<?= get_permalink(427) ?>" class="icon-arrow-long-right">View Articles</a>
          </div>
        </div>


        <div class="footer__column-two col-m-3 col-xs-12 pb-8">
          <h3 class="footer__column-heading">Quick links</h3>
          <?php
            wp_nav_menu( array(
                'theme_location' => 'footer-menu',
                'container'      => false,
                'menu_class'     => 'footer__list',
                // 'add_li_class'   => 'primary-menu__nav-item',
                'add_li_class'   => 'footer__list-item',
                'walker' => new submenu_wrap()
              )
            );
          ?>
        </div>


        <div class="footer__column-three col-m-4 col-xs-12 pb-8">
          <h3 class="footer__column-heading">Subscribe</h3>
          <p>Never miss out! Join our newsletter to receive the <strong>latest articles and exclusive offers</strong> directly to your inbox 🤩</p>
          <button class="button button-light button-full icon-envelope-full" data-popup-trigger="signup">Free Newsletter</button>
        </div>

    </div>
  </div>
</section>

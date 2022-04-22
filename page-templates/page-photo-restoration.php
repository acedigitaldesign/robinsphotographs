<?php
/******************************************
Template name: Photo Restoration
@package Robins Photographs
******************************************/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php get_header(); ?>
  <?php while ( have_posts() ) : the_post(); ?>

  <?php get_template_part('template-parts/site-header') ?>

  <main>
    <?php // Hero ?>
    <section id="start-here" class="start-here">
      <svg class="hero__curve-divider" preserveAspectRatio="none" viewBox="0 0 375 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><rect id="robins-photographs-divider-curve-1" x="0" y="0" width="375" height="54" style="fill:none;"/><path d="M0,22.869c157.379,41.951 284.926,38.229 375,-22.869l-0,54l-375,0l0,-31.131Z" style="fill:#fff;"/></svg>

      <div class="container pt-xl-0">

        <div class="row">
          <div class="col-m-7 col-xs-12">
            <header>
              <h1 class="text-light display-4">Restore Your Past</h1>
            </header>
            <p class="lead text-light">We’re the repair shop for old and damaged photographs. Our team of passionate photo repairers lovingly restore and repair your old photos for you to cherish for many more years to come.</p>
          </div>
        </div>

        <div class="d-flex">
          <div class="but1">
            <a href="<?= get_permalink(22) ?>" class="button button-primary button-large button-full | icon-arrow-circle-full-right">Start Now</a>
          </div>
          <div class="but2">
            <a href="#" class="button button-outline-light button-large button-full">Learn More</a>
          </div>
        </div>
        
      </div>

    </section>
    
    <?php // How it works ?>
    <section id="how-it-works" class="photo-restoration__how-it-works text-align-s-center">
      <div class="container">
        <header>
          <h2 class="mb-2" >How it works</h2>
        </header>
        <p class="lead mlr-m-auto mb-s-7 mb-xs-0">Our photo restoration service is best in class and best yet - it's incredibly straightforward! Here's how it works:</p>

        <div class="how-it-works__steps text-align-s-center mb-4">
          <div class="row">
            <div class="col-m-4">
              <div class="how-it-works__step">
                <div class="how-it-works__step-number">1</div>
                <p class="how-it-works__step-description">You upload your photos <a href="<?= get_permalink(22) ?>" class="link-primary">(here)</a></p>
              </div>
            </div>
            <div class="col-m-4">
              <div class="how-it-works__step">
                <div class="how-it-works__step-number">2</div>
                <p class="how-it-works__step-description">Our experts digitally restore them</p>
              </div>
            </div>
            <div class="col-m-4">
              <div class="how-it-works__step">
                <div class="how-it-works__step-number">3</div>
                <p class="how-it-works__step-description">You receive back the digitally restored copies</p>
              </div>
            </div>
          </div>
        </div>

        <div class="beaf__container mb-8"><?= do_shortcode('[bafg id="308"]') ?></div>

        <?php get_template_part('template-parts/testimonial', null, array("id"=>340, "class"=>"mb-6")) ?>

        <div>
          <a href="<?= get_permalink(22) ?>" class="button button-primary | icon-arrow-circle-full-right ">Start Your Restoration</a>
        </div>

      </div>
    </section>

    <?php // Portfolio ?>
    <section id="portfolio" class="photo-restoration__portfolio text-align-s-center">
      <div class="container">
        <header class="mb-m-8 mb-xs-5">
          <div class="subheading text-light">Show me more</div>
          <h2 class="mb-2" >Our Portfolio</h2>
          <p class="lead mlr-m-auto">Our photo restorers are master craftsmen. We're incredibly proud of the work we achieve together.</p>
        </header>
        <div class="row mb-4 beaf__row mb-6">
          <div class="col-s-6 col-xs-12">
            <div class="beaf__container">
              <?= do_shortcode('[bafg id="371"]') ?>
            </div>
          </div>
          <div class="col-s-6 col-xs-12">
            <div class="beaf__container mb-m-7 mb-xs-5">
              <?= do_shortcode('[bafg id="370"]	') ?>
            </div>
            <div class="beaf__container">
              <?= do_shortcode('[bafg id="369"]') ?>
            </div>
          </div>
        </div>
        <div>
          <a href="<?= get_permalink(22) ?>" class="button button-light | icon-arrow-circle-full-right ">Upload Your Photos</a>
        </div>
      </div>
    </section>

    <?php // Testimonials ?>
    <section id="testimonials" class="photo-restoration__testimonials text-align-s-center">
      <div class="container">
        <header class="mb-l-12 mb-m-8 mb-xs-5">
          <div class="subheading">What people say</div>
          <h2 class="mb-2" >Testimonials</h2>
          <p class="lead mlr-m-auto">Great work speaks for itself - but so do happy customers! Here's a sample of what people say about us.</p>
        </header>

        <div class="row grid-spacing-m-3 justify-content-s-center justify-content-xs-start">
          <div class="col-m-6">
            <?php get_template_part('template-parts/testimonial', null, array("id"=>909)) ?>
          </div>
          <div class="col-m-6">
            <?php get_template_part('template-parts/testimonial', null, array("id"=>343)) ?>
          </div>
          <div class="col-m-12 mt-m-6 mb-3">
            <?php get_template_part('template-parts/testimonial', null, array("id"=>345)) ?>
          </div>
        </div>

        <div class="mt-l-8 mb-l-8">
          <a href="<?= get_permalink(22) ?>" class="button button-primary | icon-arrow-circle-full-right ">Start Your Restoration</a>
        </div>
      </div>
    </section>

    <?php // Who is Robin ?>
    <section id="who-is-robin" class="who-is-robin text-dark">

      <div class="container">
        <div class="row">
          <div class="col-m-7">
            <header>
              <div class="subheading text-light">Yorkshire quality</div>
              <h2 class="mb-0" >Who is Robin?</h2>
            </header>
            <p class="lead">Robin Butler is a photography expert, family history oracle and local legend.</p>
            <p class="text-dark">His passion and enthusiasm for preserving the past is the inspiration behind this website. In partnership with his grandson, Richard, they run this website with a network of expert photo restorers.</p>

            <div class="team mb-5">
              <div class="team__member text-align-center">
                <?php get_template_part('template-parts/image', null, array('id' => 360, 'border' => false, 'width' => 180, 'wrapper-class' => 'team__member-photo mb-2')) ?>
                <h3 class="team__member-name">Robin Butler</h3>
                <p class="team__member-role">Founder</p>
              </div>
              
              <div class="team__member text-align-center">
                <?php get_template_part('template-parts/image', null, array('id' => 359, 'border' => false, 'width' => 180, 'wrapper-class' => 'team__member-photo mb-2')) ?>
                <h3 class="team__member-name">Richard Butler</h3>
                <p class="team__member-role">Lead Photo Restorer</p>
              </div>

              <div class="team__qualifier">
                <div class="team__qualifier-text">(Grandson!)</div>
                <div class="team__qualifier-arrow">
                  <svg width="53px" height="50px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><rect id="robins-photographs-dotted-arrow-01" x="0" y="0" width="52.117" height="49.337" style="fill:none;"/><clipPath id="_clip1"><rect id="robins-photographs-dotted-arrow-011" serif:id="robins-photographs-dotted-arrow-01" x="0" y="0" width="52.117" height="49.337"/></clipPath><g clip-path="url(#_clip1)"><path d="M9.879,30.192l-9.441,8.55c-0.274,0.248 -0.433,0.599 -0.438,0.969c-0.005,0.37 0.143,0.725 0.41,0.981l8.624,8.274c0.531,0.509 1.376,0.492 1.885,-0.039c0.51,-0.531 0.492,-1.376 -0.039,-1.885l-7.591,-7.284c-0,0 8.38,-7.59 8.38,-7.59c0.545,-0.494 0.587,-1.337 0.093,-1.883c-0.494,-0.545 -1.338,-0.587 -1.883,-0.093Zm1.587,11.686c1.517,0.164 3.406,0.232 5.532,0.132c0.735,-0.034 1.304,-0.659 1.27,-1.394c-0.035,-0.735 -0.66,-1.304 -1.395,-1.27c-1.968,0.093 -3.717,0.032 -5.121,-0.119c-0.732,-0.079 -1.39,0.451 -1.469,1.183c-0.078,0.732 0.452,1.39 1.183,1.468Zm10.992,-0.461c1.733,-0.295 3.534,-0.705 5.352,-1.256c0.704,-0.213 1.103,-0.958 0.89,-1.662c-0.214,-0.705 -0.959,-1.103 -1.663,-0.89c-1.708,0.518 -3.399,0.902 -5.026,1.179c-0.726,0.124 -1.214,0.813 -1.091,1.538c0.124,0.726 0.813,1.215 1.538,1.091Zm10.509,-3.214c1.645,-0.764 3.266,-1.664 4.827,-2.718c0.61,-0.413 0.771,-1.242 0.359,-1.852c-0.412,-0.609 -1.242,-0.77 -1.852,-0.358c-1.441,0.974 -2.938,1.804 -4.457,2.509c-0.667,0.31 -0.958,1.104 -0.648,1.771c0.31,0.668 1.103,0.958 1.771,0.648Zm9.146,-6.223c1.297,-1.26 2.514,-2.667 3.621,-4.234c0.425,-0.601 0.282,-1.434 -0.319,-1.859c-0.601,-0.424 -1.434,-0.281 -1.859,0.32c-1.01,1.429 -2.119,2.712 -3.301,3.861c-0.528,0.513 -0.54,1.358 -0.027,1.885c0.513,0.528 1.358,0.54 1.885,0.027Zm6.411,-9.047c0.748,-1.6 1.399,-3.327 1.933,-5.19c0.203,-0.708 -0.207,-1.447 -0.914,-1.65c-0.708,-0.202 -1.447,0.207 -1.649,0.915c-0.494,1.721 -1.094,3.317 -1.786,4.795c-0.312,0.666 -0.024,1.461 0.643,1.773c0.666,0.311 1.461,0.024 1.773,-0.643Zm3.081,-10.578c0.251,-1.721 0.414,-3.538 0.479,-5.457c0.025,-0.736 -0.551,-1.353 -1.287,-1.378c-0.735,-0.025 -1.353,0.552 -1.378,1.287c-0.061,1.816 -0.215,3.535 -0.453,5.163c-0.106,0.729 0.399,1.406 1.127,1.512c0.729,0.106 1.406,-0.399 1.512,-1.127Zm0.409,-10.909c-0.004,-0.064 -0.008,-0.128 -0.011,-0.192c-0.044,-0.735 -0.677,-1.296 -1.411,-1.252c-0.735,0.044 -1.295,0.676 -1.251,1.411c0.003,0.062 0.007,0.123 0.01,0.184c0.042,0.735 0.673,1.298 1.407,1.256c0.735,-0.042 1.298,-0.673 1.256,-1.407Z" style="fill:#d2ac95;"/></g></svg>
                </div>
              </div>
            </div>

            <?php 
            $quote_args = array(
              'text' => '"From my humble beginnings as a blacksmith in Yorkshire, [England], I never imagined I\'d be helping people from all around the world restore and preserve their past. It\'s a huge success, but no wonder really - Yorkshire quality is second to none!"',
              'author' => 'Robin Butler',
              'class' => 'text-light'
            );
            get_template_part('template-parts/quote', null, $quote_args) ?>

            <div>
              <a href="<?= get_permalink(22) ?>" class="button button-light | icon-arrow-circle-full-right ">Try Our Restoration Service</a>
            </div>
          </div>

          <div class="col-m-5"></div>
        </div>
      </div>

      <?php // section bg ?>
      <div class="row bg-row">
        <div class="col-m-7"></div>
        <div class="col-m-5 bg-img"></div>
      </div>

    </section>

    <?php // Order Now ?>
    <section id="order-now" class="photo-restoration__order-now">
      <div class="container">
        <div class="row">
          <div class="col-m-4">
            <?php get_template_part('template-parts/restoration-guarantee') ?>
          </div>
          <div class="col-m-8">
            <header>
              <h2 class="mb-2 display-6">Restore your old photos today!</h2>
            </header>

            <?php $product = wc_get_product( 20 ) ?>
            <div class="flex align-items-start mb-4">

            <div>
              <p class="price"><span class="price__prefix">From only <s class="price__regular"><?= wc_price($product->get_regular_price()); ?></s></span> <span class="price__sale"><?= wc_price($product->get_sale_price()); ?></span> <span class="price__qualifier">(per photo)</span></p>
            </div>
            <!-- <div class="d-inline-block"> -->
              <div class="badge is-full is-small | ml-4 | icon-star-rounded-full icon-before icon-mr-4 icon-nudge-top-n1 ">
              <?= "Save " . round((($product->get_sale_price() / $product->get_regular_price()) * 100), 0) . "%"; ?>
              <!-- </div> -->
            </div>

            </div>
            

    
            <p>Our expert restorers are digital magicians. They can repair even the most hopeless of old photographs<p>

            <ul class="icon-list icon-tick-thin">
              <li>Beautiful photo restoration by professional photo restorers</li>
              <li>Our expert restorers are digital</li>
              <li>30 day money back guarantee</li>
            </ul>

            <div>
              <a href="<?= get_permalink(22) ?>" class="button button-dark | icon-arrow-circle-full-right ">Start Your Restoration</a>
            </div>

          </div>
        </div>
       
      </div>
    </section>

    <?php // FAQs ?>
    <section id="faqs" class="photo-restoration__faqs">
      <div class="container pt-m-5 pt-xs-0">
        <header class="mb-m-8 mb-xs-5 text-align-m-center">
          <h2 class="mb-2">Frequently Asked Questions</h2>
          <p class="lead mlr-m-auto">Great work speaks for itself - but so do happy customers! Here's a sample of what people say about us.</p>
        </header>
        
        <?php 
        // Start FAQs accordions
          $args = array(
            'post_type' => 'faq',
            'posts_per_page' => '-1',
            'order' => 'ASC'
          );
      
        $the_query = new WP_Query( $args );
        if($the_query->have_posts() ) : ?>

          <div class="faqs mlr-m-auto">

          <?php while ( $the_query->have_posts() ) : 
            $the_query->the_post(); 

              get_template_part('template-parts/faq', null, array("question"=>get_the_title(), "answer"=>get_field("faq_answer")));

            endwhile; 
            wp_reset_postdata(); ?>

          </div>

        <?php endif; // End FAQs accordions?>

        <div class="text-align-m-center mtb-10">
          <a href="<?= get_permalink(22) ?>" class="button button-primary | icon-arrow-circle-full-right ">Start Your Restoration</a>
        </div>

      </div>
    </section>
  </main>

  <?php endwhile; // End of the loop. ?>

<?php get_footer();
<?php 
/******************************************
Theme Components
******************************************/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/******************************************
Context
- Prints the context of the post 
- or a specified context
- Pass in a class to apply 'context-icon' mixin
******************************************/
function instantiate_context($custom_class = false, $context = false, $post_id = false) {

  $id = ($post_id) ? $post_id : get_the_id();
  $class = ($custom_class) ? $custom_class : 'context-link';
  // $id = isset($parms['id'])           ? $parms['id'] : get_the_id();
  // $context = isset($parms['context']) ? $parms['context'] : false;
  // $class = isset($parms['class'])     ? $parms['class']   : false;

  // If there is a context specified:
  if($context) {

    // if($context == 'tools-tech') {
    //   $context_url    = '/#';
    //   $context_class  = '-tools-tech';
    //   $context_name   = 'Tools & Tech';
    // }
    // else {
      $data = get_the_terms($id, 'context');
      $context_name = $data[0]->name;
      $context_slug = $data[0]->slug;
      $context_url = get_term_link($context_slug,'context');
      $context_class  = 'context-' . $data[0]->rewrite['slug'];
    // }
    
  }

  // Else if not, refer to the context of the current post:
  else {
    $entryMeta = initialize_entry_meta($id);
    // $context_url = $entryMeta['context-url']; // <- correct but for now, as there's so few posts just gonna send to articles page hashtag
    $context_url = '/articles#' . $entryMeta['context-slug']; 
    $context_class = 'context-' . $entryMeta['context-slug'];
    $context_name = $entryMeta['context-name'];
  }

  ob_start(); ?>

  <span class="<?php echo $class . ' ' . $context_class; ?>">
    <a href="<?php echo $context_url; ?>">
      <?php echo $context_name; ?>
    </a>
  </span>

  <?php echo ob_get_clean();
}



/******************************************
Entry Content: Quote
******************************************/ 
function quote($parms=[]) {

  $quote['text'] = isset($parms['text']) ? $parms['text'] : null; 
  $quote['author'] = isset($parms['author']) ? $parms['author'] : null; 
  $quote['role'] = isset($parms['role']) ? $parms['role'] : null; 
  $quote['source'] = isset($parms['source']) ? $parms['source'] : null;
  $quote['wrapper-class'] = isset($parms['wrapper-class']) ? $parms['wrapper-class'] : null;

  if(isset($parms['source-url'])) {
    $url_validation = initialize_url_validation($parms['source-url']);
    $quote['source-url'] = $url_validation['url'];
  }
  else {
    $quote['source-url'] = null;
  }

if($quote['text']): ?>

  <blockquote class="quote <?= $quote['wrapper-class'] ?>">
    <p class="quote__text">"<?= stripQuotes($quote['text']); ?>"</p>
    <?php if($quote['author']): ?>
      <footer class="quote__footer">

        <div class="quote__author-info">
          <cite class="quote__author"><?= $quote['author']; ?></cite>

          <?php if($quote['role']) : ?>
            <div class="quote__role"><?= $quote['role']; ?></div>

        </div>
        <?php endif; // <- End 'role' check 

        // If there is a source:
        if($quote['source']) : ?>
          <div class="quote__source">Source: <cite><?php

            // If there is a source url
            if($quote['source-url']) : ?>

              <a href="<?= $quote['source-url']; ?>" 
                 class="quote__link"<?php 
                 if($url_validation['external-link']) echo ' target="_blank" rel="noopener"'; ?>
              >
                <?= $quote['source']; ?>
              </a><?php

            else :
              echo $quote['source'];

            endif; // <- End source url check ?>

          </cite></div> <?php

        endif; // <- End source check ?> 

      </footer>
    <?php endif; // <- End 'author' check ?>
  </blockquote>

<?php endif; 

}





/******************************************
Initialize entry listings
- Must pass $args wherever used
******************************************/ 
function initialize_entry_listings($args) {

  // Then the HTML for the new query is output
  $the_query = new WP_Query( $args );
  if($the_query->have_posts() ) : 
    
    ob_start();
    ?>

    <div class="entry-listings mb-8">
    <ul class="entry-listings__list"> 

    <?php while ( $the_query->have_posts() ) : 
        $the_query->the_post(); ?>

          <li class="entry-listings__item">
            <a href="<?php echo get_permalink();?>"><?php echo get_the_title(); ?><?php

              // Appends 'New' to entry listing to highlight posts published within the last 7 days
              $published_date = date('Y-m-d', strtotime(get_the_date())) ;
              $todays_date = date('Y-m-d', time()) ;

              if(dateDifference($published_date, $todays_date) < 7 ) : ?>

                <span class="entry-listings__flag">New</span><?php

              endif;?>
            
            </a>
          </li>

      <?php

      endwhile; 
      wp_reset_postdata();  ?>

    </ul>
    </div>

  <?php
  return ob_get_clean();

  else :
    return false;
  endif;
}








/******************************************
Initialize signup forms
- the signup sections mainly found at bottom of post and pages
- can customize title, heading, button text and icons
******************************************/ 
function initialize_signup_form($parms=[]) {

  $title = isset($parms['title'])             ? $parms['title']       : "Stay Updated";
  $description = isset($parms['description']) ? $parms['description'] : null; 
  $buttonText = isset($parms['button-text'])  ? $parms['button-text'] : "Free Newsletter"; 
  $buttonIcon = isset($parms['button-icon'])  ? $parms['button-icon'] : "icon icon-envelope"; 


  ob_start() ?>
  <div class="signup-box">
    <div class="sub-container pb-1">
      <h2 class="mb-4"><?php echo $title; ?></h2>
      <div class="row">
        
        <div class="col-m-6 col-s-12">
          <p><?php echo $description; ?></p>
        </div>

        <div class="col-m-6 col-s-12 pb-m-6">
          <form action="https://cartoondraw.us19.list-manage.com/subscribe/post?u=37af2e96813b1f39a41539386&amp;id=828fc01b14" method="post" name="" class="js-subscribe-form subscribe-form" target="_self" novalidate>
            <!-- <div class="row"> -->
            <!-- <div class="subscribe-form__field -name">
              <input class="js-subscribe-form-input-name subscribe-form__input" type="text" value="" name="FNAME" placeholder="First name" class="required" id="mce-FNAME" aria-required="true" required>
            </div> -->
              <!-- <div class="col-s-12 pb-m-4 pb-s-3"> -->
                <div class="form__field">
                  <input class="js-subscribe-form-input-email form__input -email" type="email" value="" name="EMAIL" placeholder="Email address" class="required email" id="mce-EMAIL" aria-required="true" required>
                </div>

                <div id="mce-responses" class="clear">
                  <div class="response" id="mce-error-response" style="display:none"></div>
                  <div class="response" id="mce-success-response" style="display:none"></div>
                </div>    
              <!-- </div> -->

              <!-- <div class="col-s-12 pb-m-4 pb-s-3"> -->
                <!-- Human or robot? -->
                <!-- <div style="position: absolute; left: -5000px;" aria-hidden="true">
                  <input class="security-input" type="text" name="b_37af2e96813b1f39a41539386_828fc01b14" tabindex="-1" value="">
                </div> -->
                <!-- End -->
                
                <button class="button button-primary button-full <?php echo $buttonIcon;?>" type="submit" value="" name="subscribe" id="mc-embedded-sidebar-subscribe" class="subscribe-form__submit-btn"><?php echo $buttonText;?></button>
              <!-- </div> -->
            <!-- </div> -->
          </form>
        </div>

      </div>
    </div>
  </div>

<?php return ob_get_clean();
}



/******************************************
Initialize Review Summary
- returns array of field data
******************************************/
function initialize_resource_details($id = false) {

  $arr = array();
  // Rating
  $arr['rating'] = get_field('resource_rating', $id);
  $arr['star-rating'] = initialize_star_rating($id);
  
  // Title 
  $arr['title'] = get_the_title($id);

  // Description (essentially a 1 sentence overview)
  $arr['description'] = get_field('resource_description', $id);

  // Key Points (converted to array)
  $key_points_textarea = get_field('resource_key_points', $id);
  if($key_points_textarea) {
    $arr['key-points'] = explode("\n", str_replace("\r", "", $key_points_textarea));
  }

  // Resource image (id)
  $arr['image'] = get_post_thumbnail_id($id);

  // URL
  $arr['url'] = get_field('resource_url', $id);

  // CTA text
  $arr['cta-text'] = get_Field('resource_cta_text', $id);

  return $arr;
}




/******************************************
Initialize Star Rating
- returns resource star rating html 
******************************************/
function initialize_star_rating($id = false) {

  $resource_rating = get_field('resource_rating', $id);
  $rating_map = array(
    1  => 0,
    2 => 40,
    3 => 60,
    4 => 75,
    5 => 85
  );
  
  ob_start(); ?>

  <!-- <div class="star-rating"> -->

    <?php
    $rating_key = array_keys($rating_map);
    $rating_map_size = sizeof($rating_key); 
    
    for ($i = 0; $i < $rating_map_size; $i++) :
      if($resource_rating == 0) {
        $star_type = 'star-rounded';
      }
      else if($resource_rating >= $rating_map[$rating_key[$i]]) {
        $star_type = 'star-rounded-full';
      }
      else {
        $star_type = 'star-rounded';
      }
      
      echo '<div class="star-rating__star -' . $star_type . '"></div>';

    endfor; ?>

  <!-- </div> -->

  <?php return ob_get_clean();
}


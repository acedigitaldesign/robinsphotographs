<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php 
  $currentPageURL = get_full_url(false);
  $currentPageURLEndoded = urlencode($currentPageURL);
?>

<div class="js-share-buttons share-buttons" data-share-buttons>

  <div class="share-buttons__item -facebook">
    <a href="https://www.facebook.com/sharer?u=<?php echo $currentPageURL;?>&amp;t=<?php the_title();?>"
       class="js-share-facebook-link" 
       data-href="<?php echo $currentPageURL; ?>" 
       data-layout="button_count" 
       title="Share on Facebook"
       target="_blank" 
       rel="noopener noreferrer"></a>
  </div>

  <div class="share-buttons__item -twitter">
    <a href="https://twitter.com/share"
       class="js-share-twitter-link" 
       title="Share on Twitter"></a>
  </div>

  <div class="share-buttons__item -pinterest">
    <a href="https://www.pinterest.com/pin/create/button/" 
       data-pin-custom="true" 
       data-pin-do="buttonPin" 
       data-pin-media="<?php // echo $share['image']; ?>"
       data-pin-id="0000001"
       title="Share on Pinterest"></a>
  </div>
    
  <div class="share-buttons__item -email">
    <a href="mailto:?subject=Check out this post on Animation Juice&amp;body=Hey, I thought you might like this post:%0D%0A%0D%0A<?php echo $currentPageURLEndoded; ?>" 
       target="_blank" 
       title="Share by Email"></a>
  </div>

  <div class="share-buttons__item -whatsapp">
    <a href="https://api.whatsapp.com/send?text=Hey, I thought you might like this post: <?php echo $currentPageURLEndoded; ?>" 
       class="js-share-whatsapp-link" 
       target="_blank" 
       title="Share on Whatsapp"></a>
  </div>
</div>
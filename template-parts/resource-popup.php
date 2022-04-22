<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php 
  // Only instantiates if resource popup enabled
  if(get_field('resource_popup_toggle')) :

  $associated_resource_id = get_field('associated_posts_resources')[0];
  $associated_resource = initialize_resource_details($associated_resource_id);

?>

<div class="resource-popup" data-resource-popup="inactive">
  <span class="resource-popup__close-btn" data-resource-popup-close="false"></span>
  <a href="<?php echo $associated_resource['url']; ?>" class="resource-popup__link-wrap">

    <?php image([
      'id'            => $associated_resource['image'], 
      'width'         => 75, 
      'lightbox'      => false, 
      'wrapper-class' => 'resource-popup__image',
      'border'        => false
      ]);
    ?>
  
    <div class="resource-popup__text-wrap">
      <div class="resource-popup__title"><?php echo $associated_resource['title'] ?></div>
      <div class="resource-popup__cta-text"><?php echo $associated_resource['cta-text']; ?></div>
    </div>

  </a>
</div>

<?php endif; ?>
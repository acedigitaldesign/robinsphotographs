<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php 
  $is_series = get_field('series_toggle');
  $series_connection_type = get_field('series_connection_type');

  // If this is a chapter in a series
  if($is_series && $series_connection_type == 'chapter') : 
    $series_parent = get_field('series_parent'); 
    $series_url = get_permalink($series_parent);
    $series_title = get_the_title($series_parent); ?>

    <div class="series-notice">
      <span>
        <span class="series-notice__prefix">Part of the series: </span>
          <a href="<?php echo $series_url; ?>" class="series-notice__link">
            <?php echo $series_title; ?>
          </a>
      </span>
    </div>
<?php endif; ?>
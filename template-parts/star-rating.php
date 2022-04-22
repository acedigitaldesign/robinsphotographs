<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="star-rating" aria-label="This <?= $args['rating-type'] ?> rating is <?= $args['user-rating']?> out of <?= $args['max-rating'] ?>">
  <?php for ($i = 1; $i <= $args['max-rating']; $i++) :
    if ( $i <= $args['user-rating'] ) :
      $star_class = "star-rounded-full";
    else :
        $star_class = "star-rounded-outline";
    endif; ?>
    <div class="star-rating__star -is-<?= $star_class ?>"></div>
  <?php endfor; ?>
</div>

<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Init thumbnail attachment id
$thumbnail_ID = get_post_thumbnail_id();
if ( ! has_post_thumbnail() ) {
  $thumbnail_ID = 514; // <- Placeholder attachment id
}

// Set defaults
$default_args = array(
  "image_src" => wp_get_attachment_image_src($thumbnail_ID)[0],
  "image_srcset" => wp_get_attachment_image_srcset($thumbnail_ID),
  "image_alt" => get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', TRUE),
  "primary_category" => get_the_category()[0], // <- first term in array is the rank math primary term
  "title" => get_the_title(), 
  "excerpt" => get_the_excerpt(),
  "permalink" => get_permalink()
);

// Merge passed-in args with defaults
$card = wp_parse_args($args, $default_args);

?>

<article class="post-card">
  
  <a href="<?= $card['permalink']; ?>" class="post-card__link">
    <figure class="post-card__image">
      <img 
        src="<?= $card['image_src']; ?>" 
        srcset="<?= $card['image_srcset']; ?>" 
        alt="<?= $card['image_alt']; ?>">
    </figure>

    <div class="post-card__content sub-container">

      <div class="post-card__info">
        <div class="post-card__category"><?= $card['primary_category']->name ?></div>
        <h2 class="h3 post-card__title"><?= $card['title'] ?></h3>
        <p class="post-card__excerpt"><?= $card['excerpt']; ?></p>
      </div>

      <footer class="post-card__footer">
        <div class="post-card__cta | icon-arrow-long-right">Read Now</div>
      </footer>

    </div>
  </a>

</article>

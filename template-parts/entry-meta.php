<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
// Set defaults
$default_args = array(
  "author_url" => get_permalink(235), // <- About page ID
  "author_avatar" => get_avatar( get_the_author_meta( 'ID' ), 30, '', 'author-profile-avatar'),
  "author_name" => get_the_author(),
  "post_date" => ace_get_post_date(),
  "terms" => initialize_terms()
);

// Merge passed-in args with defaults
$meta = wp_parse_args($args, $default_args);

?>

<div class="entry-meta">
  <div class="entry-meta__info">
    <span class="avatar-wrapper">
      <a href="<?= $meta['author_url'] ?>" class="avatar-link mr-1">
        <?= $meta['author_avatar'] ?>
      </a>
    </span>
    <span>by </span>
    <span>
      <a class="entry-meta__author-link" href="<?= $meta['author_url'] ?>"><?= $meta['author_name'] ?></a>
    </span>
    <span class="mlr-1">|</span>
    <span class="entry-meta__date-prefix"><?= $meta['post_date']['prefix'] ?>:</span>
    <span class="entry-meta__date"><?= $meta['post_date']['date'] ?></span>
  </div>

  <div class="entry-meta__categories">
    <span class="entry-meta__label">Categories: </span>
    <ul class="entry-meta__category-list">
    <?php 

      // Iterate over each term in the taxonomy and echo it out as a list item
      foreach ($meta['terms'] as $term) : ?>
        <li class="entry-meta__category-item"><a href="<?= $term['permalink']; ?>" class="link-primary"><?= $term['name']; ?></a></li>
      <?php endforeach; ?>

    </ul>
  </div>
</div> 
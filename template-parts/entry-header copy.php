<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php 
  $current_post = initialize_entry_meta();
  // $next_post = initialize_next_entry_meta();
  // $previous_post = initialize_previous_entry_meta();
?>

<div class="entry-header">
    <div class="entry-header__content">
    
        <!-- <div class="entry-header__nav"> 
          <span class="entry-header__nav-item -previous">
            <a href="<?php // echo $previous_post['url']; ?>" title="Previous Post"><span class="screen-reader-text">Previous Post</span></a>
          </span>
          <span class="entry-header__nav-item -next">
            <a href="<?php // echo $next_post['url']; ?>" title="Next Post"><span class="screen-reader-text">Next Post</span></a>
          </span>
        </div> -->

      <div class="entry-header__title">
        <h1 class="entry-header__title-heading">
          <?= $current_post['title']; ?>
        </h1>
      </div>

      <div class="entry-meta">
        <div class="entry-meta__info">
          <span class="avatar-wrapper">
            <a href="/about" class="avatar-link mr-1">
              <?= get_avatar( get_the_author_meta( 'ID' ), 30, '', 'richard-butler-profile-avatar'); ?>
            </a>
          </span>
          <span>by </span>
          <span>
            <a class="entry-meta__author-link" href="/about"><?= $current_post['author-fullname']; ?></a>
          </span>
          <span class="mlr-1">|</span>
          <span><?= $current_post['post-date']['prefix']; ?></span>
          <span class="entry-meta__date"><?= $current_post['post-date']['date']; ?></span>
        </div>

        <div class="entry-meta__categories">
          <span class="entry-meta__label">Categories: </span>
          <ul class="entry-meta__category-list">
          <?php 
    
            $terms = initialize_terms();
            // Iterate over each term in the taxonomy and echo it out as a list item
            foreach ($terms as $term) : ?>
              <li class="entry-meta__category-item"><a href="<?= $term['permalink']; ?>" class="link-primary"><?= $term['name']; ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div> 

      <?php // get_template_part('template-parts/series-notice'); ?>
      <?php // get_template_part('template-parts/share-buttons'); ?>
      <?php // get_template_part('template-parts/video-embed'); // <- Ignores if no video URL ?>
      <?php // get_template_part('template-parts/featured-image'); ?>
      <?php // get_template_part('template-parts/overview'); ?>
      <?php // get_template_part('template-parts/toc'); ?>
      <?php get_template_part('template-parts/affiliate-disclosure'); ?>

      <!-- <div class="separator"></div> -->




    </div>
</div>
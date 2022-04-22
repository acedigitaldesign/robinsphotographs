<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Set defaults
$default_args = array(
  "text" => null,
  "author" => null,
  "role" => null,
  "source" => null,
  "class" => null,
  "source_url" => null
);

// Merge passed-in args with defaults
$quote = wp_parse_args($args, $default_args);


if(isset($args['source_url'])) {
  $url_validation = initialize_url_validation($args['source_url']);
  $quote['source_url'] = $url_validation['url'];
}

if($quote['text']): ?>

  <blockquote class="quote <?= $quote['class'] ?>">
    <p class="quote__text">"<?= stripQuotes($quote['text']); ?>"</p>
    <?php if($quote['author']): ?>
      <footer class="quote__footer">

        <div class="quote__author-info">
          <cite class="quote__author">
            <?= $quote['author']; ?>
          </cite>

          <?php 
          // Add role
          if($quote['role']) : ?>
            <div class="quote__role">
              <?= $quote['role']; ?>
            </div>
          <?php endif; // <- End 'role' check ?>

        </div>

        <?php 
        // If there is a source:
        if($quote['source']) : ?>
          <div class="quote__source">Source: <cite><?php

            // If there is a source url
            if($quote['source_url']) : ?>

              <a href="<?= $quote['source_url']; ?>" 
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
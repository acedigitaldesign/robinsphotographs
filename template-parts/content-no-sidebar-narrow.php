<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="container pt-l-10 pt-s-6">
  <div class="row">
    <div class="col-l-2 mb-0"></div>
    <main class="col-l-8 col-s-12">
      <?php get_template_part('template-parts/content/' . $post->post_name );?>
    </main>
    <div class="col-l-2 mb-0"></div>
    
  </div>
</div>
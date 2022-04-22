<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function ace_share_buttons_shortcode() {
    ob_start();
    get_template_part('template-parts/share-buttons');
    return ob_get_clean();
  }
add_shortcode('sharebuttons', 'ace_share_buttons_shortcode');
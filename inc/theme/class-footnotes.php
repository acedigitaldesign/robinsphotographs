<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Footnotes {

  public static $items = array();

  public static function construct($atts, $content) {
    $atts = shortcode_atts( array(
      "title" => null,
      "url" => null,
      "source" => null,
    ), $atts, 'footnote' );

    static $i = 1;
    $arr = array('index' => $i, 'content' => $content);
    $footnote_item = wp_parse_args($arr, $atts);
    array_push(self::$items, $footnote_item);

    ob_start();
    get_template_part('template-parts/footnotes-marker', null, array('index'=>$i));
    $i++;
    return ob_get_clean();
  }
}
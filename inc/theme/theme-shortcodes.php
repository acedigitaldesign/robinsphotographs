<?php
/******************************************
RP Shortcodes
******************************************/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/******************************************
Resources
// inits resource (eg. affiliate product)
******************************************/
add_shortcode( 'rp_resource', 'rp_the_resource' );

function rp_the_resource($atts) { 
  $a = shortcode_atts( array(
    'id' => null,
    'cta' => 'View price on Amazon',
    'class' => 'link-primary icon-arrow-long-right',
    'wrap' => null,
), $atts, 'rp_resource' );

// Resource ID
$id = esc_attr($a['id']);

// Resource link attributes array
$atts_arr = array(
  'class' => $a['class'],
  'href' => get_field('resource_link', $id),
  'data-resource' => $id,
  'target' => '_blank',
  'rel' => 'sponsored'

);
// Concat attribute and value
foreach( $atts_arr as $key => $value ) {
  $atts_list[] = $key . '=' . '"' . $value . '"';
}
// Turn attributes into string
$atts_str = implode(" ", $atts_list);

// Construct link
$resource_link = '<a ' . $atts_str . '>' . $a['cta'] . '</a>';

// Add html wrapper, if any
if ( $a['wrap'] ) {
  $wrap = $a['wrap'];
  $wrap_html_open = '<' . $wrap . '>';
  $wrap_html_close = '</' . $wrap . '>';
  $resource_html = $wrap_html_open . $resource_link . $wrap_html_close;
}
else {
  $resource_html = $resource_link;
}

return $resource_html;

}

/******************************************
// Display woo product price
******************************************/
add_shortcode( 'woocommerce_product_price', 'displayPriceProduct' );
function displayPriceProduct( $atts ) {
  $atts = shortcode_atts( array(
      'id' => null,
  ), $atts, 'woocommerce_product_price' );

  $html = '';

  if( intval( $atts['id'] ) > 0 && function_exists( 'wc_get_product' ) ){
       $_product = wc_get_product( $atts['id'] );
       $html = wc_price( $_product->get_sale_price() ); // <- currently using sale price
  }
  return $html;
}




/******************************************
Quote
******************************************/
add_shortcode( 'quote', 'displayQuote' );
function displayQuote( $atts ) {
  $atts = shortcode_atts( array(
    "text" => null,
    "author" => null,
    "role" => null,
    "source" => null,
    "class" => null,
    "source_url" => null
  ), $atts, 'quote' );
  
  ob_start();
    get_template_part('template-parts/quote', null, $atts);
  return ob_get_clean();
  
}

/******************************************
Article Key Points
// Displays key-points list
// Compiled from headings with 'key-points' class
******************************************/
add_shortcode( 'key-points', 'display_key_points' );
function display_key_points( $atts ) {
  $atts = shortcode_atts( array(
    "depth" => 2
  ), $atts, 'key-points' );

  ob_start();
  
  $content = get_the_content();
  $extracted_data = array(); // to add the id and text content of each keypoint
  $keypoint_html = array(); // to add the create li html for each keypoint

  // match headings with class: 'key-point'
  $pattern = '/<h[1-6]*[^>]*class="[^"]*\bkey-point\b[^"]*"[^>]*>*.*<\/h[1-6]>/i';
  preg_match_all($pattern, $content, $matches);

  // if no matching headings, exit
  if(empty($matches[0])) return;

  // iterates over each match and extracts id and text content into an array
  $i = 0;
  foreach ($matches[0] as $heading ) {
    /* $pattern = '/<([^\s]+).*?id="([^"]*?)".*?>(.+?)<\/\1>/i'; */ // <- pattern extracts id and nested content (text AND and nested wrapper tags) of heading tags

    $pattern = '/id="([^"]*?)".*?/'; // <- extracts ID
    preg_match_all($pattern, $heading, $matches);
    // print_array($matches);
    $extracted_data[$i] = array(
      'id' => $matches[1][0],
      'title' => strip_tags($heading) // essentially just gets text content, not any wrapper tags
    );
    $i++;
  }

  // create html for each keypoint
  foreach ( $extracted_data as $keypoint ) {
    $html = '<li class="key-point__item">';
    $html .= '<a class="key-point__link link-primary" href="#' . $keypoint['id'] . '">';
    $html .= $keypoint['title'];
    $html .= '</a></li>';
    
    array_push($keypoint_html, $html);
  }

  // Implode array, each item on a new line
  $keypoint_items = implode("\n",$keypoint_html);

  // print summary list
  echo '<ol>' . $keypoint_items . '</ol>';

  return ob_get_clean();

}


/******************************************
Footnotes
// Instantiate Footnote markers
// Index is the ID of the footnote ACF field at bottom of post edit
// Recommended to set 'index' but if not will attempt to auto index
******************************************/
add_shortcode( 'footnote', 'construct_footnote_marker');
function construct_footnote_marker($atts) {
  $atts = shortcode_atts( array(
    "index" => null
  ), $atts, 'key-points' );

  // auto index (incase 'index' isn't explicitly set)
  static $i = 1;
  $arr = wp_parse_args(array('auto-index' => $i), $atts);

  ob_start();
  get_template_part('template-parts/footnotes-marker', null, $arr);
  $i++;
  return ob_get_clean();

}


/******************************************
Numbered headings
// Auto adds number prefix to headings with class 'numbered-heading'
// Used because it's slightly more useful than manually setting the number
// eg. Compiling key-points (a numbered list so shouldnt have number as part the title)
// Just fundamentally, numbers shouldnt be hardcoded as part of title
******************************************/
function rp_add_number_to_numbered_headings( $content ) {

  // match headings with class: 'numbered-heading'
  $pattern = '/<h[1-6]*[^>]*class="[^"]*\bnumbered-heading\b[^"]*"[^>]*>*.*<\/h[1-6]>/i';
  preg_match_all($pattern, $content, $matches);

  // if no matching headings, exit
  if (empty($matches[0])) return $content;

  // iterates over each match and extracts id and text content into an array
  $i = 1;
  foreach ($matches[0] as $heading ) {

    // Add number and aria data
    $pos = strpos($heading, '>'); // find last characer of opening heading tag
    if ($pos !== false) {
        // add number
        $str_to_insert = '<span class="numbered-heading__number">' . $i . '.</span> ';
        $new_str = substr_replace($heading, $str_to_insert, $pos+1, 0);

        // add aria data
        $title = strip_tags($heading);
        $str_to_insert = 'role="listitem" aria-labelledby="' . $i . '. ' . $title . '" ';
        $new_str = substr_replace($new_str, $str_to_insert, $pos, 0);

        // replace heading in content
        $content = str_replace( $heading, $new_str, $content );
    }

    $i++;
  }
  return $content;
}
add_filter( 'the_content', 'rp_add_number_to_numbered_headings', 100 );




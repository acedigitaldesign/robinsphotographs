<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php 
/*AutoTOC function written by Alex Freeman
* Modified by Richard Butler *
* Released under CC-by-sa 3.0 license
* http://www.10stripe.com/  */

ob_start();

// Initialize the variables in the target file
$filename = custom_content();
$depth = 3;

if(check_file_exists($filename)) {
  
  // read in the file
  $file = fopen($filename,"r");
  $html_string = fread($file, filesize($filename));
  fclose($file);


  // get the headings down to the specified depth
  $pattern = '/<h[2-'.$depth.']*[^>]*>.*?<\/h[2-'.$depth.']>/';
  $temp_var = preg_match_all($pattern,$html_string,$winners);

  // if no matching headings, exit
  if(empty($winners[0])) return;

  $converted_headings = array();



  // Iterate over each heading in order 
  // This is in order to convert each to the appropriate TOC li item (or nested li) 
  // Also sets the anchor href to the original heading ID
  for($i = 0; $i < count($winners[0]); $i++) {

      // First need to establish current and next heading levels so can compare the two
      // Need to compare them to see whether to create a nested UL (if next one is an H3, for example)

      // Current heading: if it's a h2, label it 2
      $current_heading = (strpos($winners[0][$i], '</h2>')) ? 2 : 3;

      // Next heading: if it's a h2, label it 2
      if(isset($winners[0][$i+1])) {
        $next_heading = (strpos($winners[0][$i+1], '</h2>')) ? 2 : 3;
      }
      // ...if it's the last heading and therefore there is no next heading:
      else {
        $next_heading = null;
      }


      // Establish opening heading tag replacement HTML:
      $opening_html = '<li class="toc-item">';

      // Establish closing heading tag replacement HTML:
      // If I'm the last heading:
      if($next_heading === null) {

        // Then if I'm an H2, close me off as normal
        if($current_heading == 2) {
          $closing_html = '</a></div></li>';
        }
        // Else if an H3, close of the nested UL tag too
        else {
          $closing_html = '</a></div></li></ul>';
        }
      }

      // If I'm the same heading as the next heading, act as normal
      else if($current_heading === $next_heading) {
        $closing_html = '</a></div></li>';
      }

      // If I'm an H2 and the next heading is a H3, initialize opening of a UL tag
      else if($current_heading < $next_heading) {
        $opening_html = '<li class="js-accordion-item accordion-item toc-item ">'; // <-modified opening html
        $closing_html = '</a><span class="js-accordion-activator accordion-activator"></span></div><ul class="js-accordion-content accordion-content toc-sub-section ">';
      }

      // If I'm an H3 and the next heading is a H2, initialize closing of the nested UL tag
      else if($current_heading > $next_heading) {
        $closing_html = '</a></div></li></ul>';
      }


    $heading = $winners[0][$i];
    $heading = str_replace('id="','><div class="toc-link-wrap"><a href="#', $heading);
    $heading = preg_replace('/<\s*h\s*([1-6])\s*>/', $opening_html, $heading); // <- The \s* get rid of whitepsace, incase any extra occurs in the heading tags
    $heading = preg_replace('/<\/h[1-6]>/', $closing_html, $heading);


  // Added converted heading in to array
  array_push($converted_headings, $heading);

  } 

  // Implode array, each item on a new line
  $toc_items = implode("\n",$converted_headings);

}
else {
  $toc_items = false;
}
?>



<div class="toc">
  <div class="toc-heading">On this page</div>
  <ul class="toc-list"> 
    <?php if($toc_items) echo $toc_items; ?>
  </ul>
</div>

<?php echo ob_get_clean(); ?>
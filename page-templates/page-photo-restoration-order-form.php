<?php
/******************************************
Template name: Photo Restoration Order Form
@package Robins Photographs
******************************************/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php get_header(); ?>

  <?php while ( have_posts() ) : the_post(); 
  
  get_template_part('template-parts/site-header-simple');
  ?>
	
  <main class="container pt-4">
    <div class="eform-progress-bar" data-eform-progress-bar=""></div>
    <?= do_shortcode('[ipt_fsqm_form id="6"]') ?>

  </main>

  <?php get_template_part('template-parts/help-popup') ?>
  
  <?php endwhile; // End of the loop. ?>

<footer id="colophon" class="site-footer">
  
  <?php 
    // Currency exchange rate info used by CRCY plugin
    $default_currency = "GBP";
    $exchange_rates = array(
      "AUD" => array(
        "currencySymbol" => "$",
        "rate" => ""
      ),
      "EUR" => array(
        "currencySymbol" => "€",
        "rate" => ""
      ),
      "CAD" => array(
        "currencySymbol" => "$",
        "rate" => ""
      ),
      "GBP" => array(
        "currencySymbol" => "£",
        "rate" => ""
      ),
      "USD" => array(
        "currencySymbol" => "$",
        "rate" => ""
      ));
    foreach($exchange_rates as $currency => $value) {
      if ($currency != $default_currency) {
        $shortcode = '[woo_multi_currency_rates currencies="' . $currency . '"]';
        $rate_html = do_shortcode($shortcode); // spits out a load of html tags
        $rate_html = strip_tags($rate_html); // strip out all tags
        $rate_str = substr($rate_html, strpos($rate_html, "=") + 1); // removes rate prefix
        $rate_value = floatval($rate_str); // converts rate to a float
        $exchange_rates[$currency]["rate"] = $rate_value; // add rate value to exchange rates array
      }
      else {
        $exchange_rates[$currency]["rate"] = 1; // shortcode won't output anything if it's the default currency, so manually inputting 1;
      }
    }
    // echo '<script type="text/javascript">var exchange_rates = ' . json_encode($exchange_rates) . ';</script>';
  ?>
  <?php //get_template_part('template-parts/copyrights'); ?>

</footer>

<script type="text/javascript">
  var exchange_rates = <?=  json_encode($exchange_rates) ?>;
</script>

<?php wp_footer(); ?>

</div> <!-- End of #page -->
</body>
</html>

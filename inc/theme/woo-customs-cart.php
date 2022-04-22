<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function rp_add_cart_container_open() {
  $html = '<div class="row grid-spacing-m-3">';
  $html .= '<div class="cart__col1 col-m-7 col-xs-12">';
  echo $html;
}
add_action( 'woocommerce_before_cart', 'rp_add_cart_container_open', 1 );


function rp_add_cart_columns() {
  $html = '</div>';
  $html .= '<div class="cart__col2 col-m-5 col-xs-12">';
  echo $html;
}
add_action( 'woocommerce_before_cart_collaterals', 'rp_add_cart_columns', 1 );


function rp_add_cart_container_close() {
  $html = '</div></div>'; // close second col and parent row
  echo $html;
}
add_action( 'woocommerce_after_cart', 'rp_add_cart_container_close', 1 );



function rp_add_cart_totals_container_open() {
  $html = '<div class="sub-container">';
  echo $html;
}
add_action( 'woocommerce_before_cart_totals', 'rp_add_cart_totals_container_open', 100 );

function rp_add_cart_totals_container_close() {
  $html = '</div>';
  echo $html;
}
add_action( 'woocommerce_after_cart_totals', 'rp_add_cart_totals_container_close', 1 );





// Button
/*Proceed to Checkout*/
remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 ); 
add_action('woocommerce_proceed_to_checkout', 'sm_woo_custom_checkout_button_text',20);

function sm_woo_custom_checkout_button_text() {
    $checkout_url = WC()->cart->get_checkout_url();
  ?>
       <a href="<?php echo $checkout_url; ?>" class="checkout-button alt wc-forward button button-primary button-large button-full mb-2 | icon-arrow-circle-full-right" target="_self"><?php  _e( 'Proceed to Checkout', 'woocommerce' ); ?></a> 
  <?php
} 


/**
 * Excellent snippet to update header cart icon counter when adding and deleting cart icons via ajax (usually on cart page) - no custom js required!
 * Reference here:
 * https://iconicwp.com/blog/update-custom-cart-count-html-ajax-add-cart-woocommerce/
 */
function iconic_cart_count_fragments( $fragments ) {

    $cart_count = count(WC()->cart->get_cart_contents());
    $visibility_class = "";

    if ( $cart_count == 0 || ! $cart_count) {
      $visibility_class = "is-hidden";
    }

    $fragments['.primary-menu__cart .cart-icon__counter'] = '<span class="cart-icon__counter ' . $visibility_class . '">' . count(WC()->cart->get_cart_contents()) . '</span>';
    
    return $fragments;

}

add_filter( 'woocommerce_add_to_cart_fragments', 'iconic_cart_count_fragments', 10, 1 );
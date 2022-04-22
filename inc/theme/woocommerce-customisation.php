<?php
if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
 }

// Removes Woocommerce style sheets
// https://docs.woocommerce.com/document/disable-the-default-stylesheet/
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );


// Unrequire certain fields (this needs to come before removing fields)
add_filter( 'woocommerce_default_address_fields', 'bbloomer_required_woo_checkout_fields', 1);
  
function bbloomer_required_woo_checkout_fields( $fields ) {
   global $woocommerce;
   $country = $woocommerce->customer->get_country();

   // $fields['billing']['billing_phone']['required'] = false;
   // unset( $fields[ 'billing_first_name' ]['required'] );
   unset( $fields[ 'billing_billing_phone' ]['required'] );

   if($country !== 'US'){
      // $fields['billing']['billing_state']['required'] = false;
      // $fields['shipping']['shipping_state']['required'] = false;
      unset( $fields[ 'billing_billing_state' ]['required'] );
      unset( $fields[ 'shipping_shipping_state' ]['required'] );
   }
    return $fields;
}

// // Unrequire contact fields
// add_filter( 'woocommerce_checkout_fields' , 'rphotos_override_billing_checkout_fields', 20, 1 );
// function rphotos_override_billing_checkout_fields( $fields ) {
//    unset( $fields[ 'first_name' ]['required'] );
//     return $fields;
// }

// Always remove these fields
add_filter( 'woocommerce_checkout_fields', 'bbloomer_remove_woo_checkout_fields' );
  
function bbloomer_remove_woo_checkout_fields( $fields ) {
   unset($fields['billing']['billing_company']);
   unset($fields['billing']['billing_phone']);
   unset($fields['billing']['billing_address_2']);
   unset($fields['shipping']['shipping_address_2']);
   add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
   return $fields;
}


// Move Email field to top
add_filter( 'woocommerce_billing_fields', 'bbloomer_move_checkout_email_field' );
 
function bbloomer_move_checkout_email_field( $address_fields ) {
    $address_fields['billing_email']['priority'] = 1;
    $address_fields['billing_country']['priority'] = 61;
   //  $address_fields['shipping_country']['priority'] = 61;
    return $address_fields;
}

// Move shipping fields
add_filter( 'woocommerce_shipping_fields', 'rp_move_shipping_fields' );
 
function rp_move_shipping_fields( $shipping_fields ) {
    $shipping_fields['shipping_country']['priority'] = 61;
   //  $address_fields['shipping_country']['priority'] = 61;
    return $shipping_fields;
}

// Move labels inside checkout fields
add_filter( 'woocommerce_checkout_fields', 'bbloomer_labels_inside_checkout_fields', 9999 );
   
function bbloomer_labels_inside_checkout_fields( $fields ) {
   foreach ( $fields as $section => $section_fields ) {
      foreach ( $section_fields as $section_field => $section_field_settings ) {
         $fields[$section][$section_field]['placeholder'] = $fields[$section][$section_field]['label'];
         // $fields[$section][$section_field]['label'] = '';
      }
   }
   return $fields;
}

// Free shipping
add_filter( 'woocommerce_cart_shipping_method_full_label', 'bbloomer_add_0_to_shipping_label', 10, 2 );
   
function bbloomer_add_0_to_shipping_label( $label, $method ) {

// if shipping rate is 0, concatenate ": $0.00" to the label
if ( ! ( $method->cost > 0 ) ) {
$label .= ': Free';
} 
 
// return original or edited shipping label
return $label;
 
}

// Toggle shipping details visibility
add_filter( 'woocommerce_cart_shipping_method_full_label', 'rp_toggle_shipping_summary_visibility', 20, 2 );
   
function rp_toggle_shipping_summary_visibility( $label, $method ) {

$shipping = get_cart_shipping_requirement();
if ( ! $shipping ) {
   $method->label = "";
   $label = 'None';
} 
 
// return original or edited shipping label
return $label;
 
}


add_filter( 'woocommerce_checkout_fields' , 'bbloomer_simplify_checkout_virtual' );
 
function bbloomer_simplify_checkout_virtual( $fields ) {
   $shipping = get_cart_shipping_requirement();
    if( ! $shipping ) {
       unset($fields['shipping']['shipping_first_name']);
       unset($fields['shipping']['shipping_last_name']);
       unset($fields['shipping']['shipping_address_1']);
       unset($fields['shipping']['shipping_address_2']);
       unset($fields['shipping']['shipping_city']);
       unset($fields['shipping']['shipping_postcode']);
       unset($fields['shipping']['shipping_country']);
       unset($fields['shipping']['shipping_state']);
       add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false');
     }
     
     return $fields;
}

function get_cart_shipping_requirement() {
   $photo_restoration_product_id = 20;
   $shipping = false;

   foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
      // If product is not virtual...
      if ( ! $cart_item['data']->is_virtual() ) {

         // But the non-virtual product is the photo restoration product...
         if ($cart_item['product_id'] == $photo_restoration_product_id) {

            // And if the photo restoration product has variation set to physical prints...
             if ($cart_item['eform_attr'][1]['value'] != 0) {

                 // Then shipping is required
                 $shipping = true;
             }
         }
         else $shipping = true; // if a physical product, set shipping to true
      }
   }
   return $shipping;
}


function get_order_shipping_requirement($order) {
   $items = $order->get_items();
   $photo_restoration_product_id = 20;
   $shipping = false;

   foreach ($items as $item) {
       $order_item = wc_get_product($item->get_product_id());

       // If product is not virtual...
       if (! $order_item->is_virtual()) {

         // But the non-virtual product is the photo restoration product...
         if ($item->get_product_id() == $photo_restoration_product_id) {

            // And if the photo restoration product has variation set to physical prints...
            if ($item->get_meta('Prints') == "Yes") {

               // Then shipping is required
               $shipping = true;
            }
         } 
         else {
            $shipping = true; // if a physical product, set shipping to true
         } 
      }
   }
   return $shipping;
}

// add_filter( 'woocommerce_ship_to_different_address_checked', '__return_true');

// Move payment section from review to after customer details
// remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
// add_action( 'woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 10 );

// Move coupon code from top to under payments
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form' );
// add_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form');

remove_action('woocommerce_checkout_terms_and_conditions','wc_checkout_privacy_policy_text', 20);
remove_action('woocommerce_checkout_terms_and_conditions','wc_terms_and_conditions_page_content', 30);
add_action('woocommerce_review_order_after_payment','wc_checkout_privacy_policy_text');
add_action('woocommerce_review_order_after_payment','wc_terms_and_conditions_page_content');

add_action( 'wp', 'rp_apply_custom_checkout_styles', 1);

function rp_apply_custom_checkout_styles() {


   add_action( 'woocommerce_checkout_before_customer_details', function() {

      $logo_attr = array(
         'src' => wp_get_attachment_image_src(8, 'full')[0],
         'srcset' => wp_get_attachment_image_srcset(8),
      );
      
      $logo = '<div class="image-wrapper checkout__logo text-align-left mb-0">';
      $logo .= '<a class="image-link" href="/">';
      $logo .= wp_get_attachment_image(8, array('170', 'auto'), false, $logo_attr);
      $logo .= '</a>';
      $logo .= '</div>'; // end logo

      $header = '<header class="checkout__header">';
      $header .= $logo;
      $header .= '</header>';

      $html = '<div class="checkout__row row grid-spacing-l-2">';
      $html .= '<div class="checkout__col1 col-m-7 col-xs-12">';
      // $html .= $header;
      $html .= '<div class=checkout__customer-details>';
      
      echo $html;
   });
   add_action( 'woocommerce_checkout_before_order_review_heading', function() {
      $html = '</div>'; // end checkout__customer-details
      $html .= '</div>'; // end col1
      $html .= '<div class="checkout__col2 col-m-5 col-xs-12 mt-m-n6">';
      $html .= '<div class="order-summary sub-container">';
      $html .= '<div class="order-summary__add-photos">';
      $html .= '<a href="' . get_permalink(22) . '" target="_self">Add Photos</a>';
      $html .= '</div>'; // End add photos div

      echo $html;
   });
   add_action( 'woocommerce_checkout_after_order_review', function() {

      $html = '</div>'; // end checkout__order-review
      $html = '</div>'; // end col2
      $html .= '</div>'; // end checkout__container
      echo $html;
   });
   
}



// Button
add_filter( 'woocommerce_order_button_html', 'misha_custom_button_html' );

function misha_custom_button_html( $button_html ) {
	$button_html = str_replace( 'class="button', 'class="button button-primary button-large button-full mb-2 | icon-shopping-trolley', $button_html );
	return $button_html;
}

// add_filter('woocommerce_checkout_fields', 'addBootstrapToCheckoutFields' );
// function addBootstrapToCheckoutFields($fields) {
//     foreach ($fields as &$fieldset) {
//         foreach ($fieldset as &$field) {
//             // if you want to add the form-group class around the label and the input
//             $field['class'][] = 'form-group'; 

//             // add form-control to the actual input
//             $field['input_class'][] = 'form-control';
//         }
//     }
//     return $fields;
// }

// Change field placeholder texts
add_filter('woocommerce_default_address_fields', 'override_default_address_checkout_fields', 20, 1);
function override_default_address_checkout_fields( $address_fields ) {
   //  $address_fields['first_name']['placeholder'] = 'Fornavn';
   //  $address_fields['last_name']['placeholder'] = 'Efternavn';
    $address_fields['address_1']['placeholder'] = 'Address';
   //  $address_fields['address_2']['placeholder'] = 'Apartment, suite, etc. (optional)';
   //  $address_fields['state']['placeholder'] = 'Stat';
   //  $address_fields['postcode']['placeholder'] = 'Postnummer';
   //  $address_fields['city']['placeholder'] = 'By';
    return $address_fields;
}


add_filter('woocommerce_form_field_args','wc_form_field_args',10, 3);

function wc_form_field_args($args, $key, $value = null) {

   if ( $args['id'] == 'billing_state' || $args['id'] == 'shipping_state' ) {
      $args['class'][] = 'form-row-first';
   }
   if ( $args['id'] == 'billing_postcode' || $args['id'] == 'shipping_postcode') {
      $args['class'][] = 'form-row-last';
   }
   if ( $args['id'] == 'billing_city' || $args['id'] == 'shipping_city' ) {
      $args['class'][] = 'form-row-first';
   }
   if ( $args['id'] == 'billing_country' || $args['id'] == 'shipping_country' ) {
      $args['class'][] = 'form-row-last';
   }


   return $args;
}

// Change headings
add_filter('woocommerce_form_field', 'add_headings_between_form_fields', 10, 4);

function add_headings_between_form_fields($field, $key, $args, $value = null) {

  if (is_checkout() & $key === 'billing_first_name') {
    $a = '<h3 class="form-row form-row-wide checkout__section-heading" id="checkout__billing">Billing address</h3>';
    return $a . $field;
  }
  if (is_checkout() & $key === 'shipping_first_name') {
    $a = '<h3 class="form-row form-row-wide checkout__section-heading pt-3" id="checkout__shipping">Shipping address</h3>';
    return $a . $field;
  }
  return $field;

}

// Add payment method heading
add_action( 'woocommerce_review_order_before_payment', function() {
 echo '<h3 id="payment-options" class="checkout__section-heading">' . esc_html__( 'Payment Options' ) . '</h3>';
} );


//Change the Billing Details checkout label to Contact Information
function wc_billing_field_strings( $translated_text, $text, $domain ) {
   switch ( $translated_text ) {
   case 'Billing details' :
   $translated_text = __( 'Contact information', 'woocommerce' );
   break;
   }
   return $translated_text;
}
add_filter( 'gettext', 'wc_billing_field_strings', 20, 3 );


// After submit button
add_action( 'woocommerce_review_order_after_payment', function() {
   $html = '<p class="secure-checkout text-align-center mb-2 | icon-lock-full icon-before icon-size-4 icon-nudge-top-n2">SSL Secure Checkout</p>';
   $html .= '<div class="payment-networks">';
   $html .= '<div class="payment-network__item -is-paypal"><img src="' . image_src('paypal-logo-small.jpg') . '"></div>';
   $html .= '<div class="payment-network__item -is-visa"><img src="' . image_src('visa-logo-small.jpg') . '"></div>';
   $html .= '<div class="payment-network__item -is-mastercard"><img src="' . image_src('mastercard-logo-small.jpg') . '"></div>';
   $html .= '<div class="payment-network__item -is-amex"><img src="' . image_src('amex-logo-small.jpg') . '"></div>';
   $html .= '</div>'; // end payment-networks

   echo $html;
}, 1 );



// Thank you page redirect (for photo restoration product)
add_action( 'template_redirect', 'misha_redirect_depending_on_product_id' );

function misha_redirect_depending_on_product_id(){

	/* do nothing if we are not on the appropriate page */
	if( !is_wc_endpoint_url( 'order-received' ) || empty( $_GET['key'] ) ) {
		return;
	}
	
	$order_id = wc_get_order_id_by_order_key( $_GET['key'] );
	$order = wc_get_order( $order_id );

	foreach( $order->get_items() as $item ) {
		if( $item['product_id'] == 20 ) {
			wp_redirect( '/photo-restoration-order-received/?key=' . $_GET['key'] );
			exit;
		}
	}
	
}


// Force clearing of cart after order payment
function ace_clear_cart() {
   WC()->cart->empty_cart();
}
   // add_action( 'woocommerce_payment_complete', 'ace_clear_cart', 12 );
   // add_action( 'woocommerce_payment_complete_order_status_processing', 'ace_clear_cart', 12 );
   // add_action( 'woocommerce_payment_complete_order_status_completed', 'ace_clear_cart', 12 );


/**
 * Disallow direct access to checkout page if order complete and thus cart empty
 * 
 * If customer manually enters checkout url with nothing in cart, attempts to show their order
 * It's confusing, especially as payment button still visible...
 * Better to just redirect away from checkout if nothing in cart to checkout with. 
 * Especially as customer has an email with the summary of their order in...
 *
 */
// add_action( 'template_redirect', 'ace_checkout_redirect_if_cart_empty' );

// function ace_checkout_redirect_if_cart_empty(){

// 	/* do nothing if we are not on the appropriate page */
// 	if( !is_wc_endpoint_url( 'checkout' ) ) {
// 		return;
// 	}
	
// 	if ( WC()->cart->cart_contents_count == 0 ) {
//       wp_redirect( '/cart/' );
//       exit;
//    }
// }


add_filter( 'woocommerce_price_trim_zeros', '__return_true' );




// // Redirect to shop link (home instead of 'shop')
// add_filter( 'woocommerce_return_to_shop_redirect', 'woo_custom_redirect_url' );

// function woo_custom_redirect_url(){
//    return get_permalink(22);
// }

add_filter('woocommerce_return_to_shop_text', 'prefix_store_button');
/**
 * Change 'Return to Shop' text on button
 */
function prefix_store_button() {
        $store_button = "Start Your Restoration"; // Change text as required

        return $store_button;
}



function custom_shop_page_redirect() {
   if( is_shop() ){
       wp_redirect( get_permalink(309) );
       exit();
   }
}
add_action( 'template_redirect', 'custom_shop_page_redirect');



add_filter('woocommerce_checkout_update_order_review_expired', 'rp_checkout_expired', 10, 1);

function rp_checkout_expired($true) {
   // echo "HELLOOO";
   return $true;
}
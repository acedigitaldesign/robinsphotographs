<?php
/******************************************
Template name: Order Received
@package Robins Photographs
******************************************/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php get_header(); ?>

  <?php while ( have_posts() ) : the_post(); 
  
  get_template_part('template-parts/site-header-simple');

  $order_id = wc_get_order_id_by_order_key( $_GET['key'] );
	$order = wc_get_order( $order_id );
  $items = $order->get_items();
  $shipping = get_order_shipping_requirement($order);
?>
	
  <main class="container pt-0 is-narrow">
    <div class="order-received__header text-align-center">
      <h1>Thank you!</h1>
      <p class="font-size-6 mb-3">Your order number is <strong><?= $order_id ?></strong></p>
      <div class="image-wrapper">
        <img src="<?= image_src('vector-illustration-order-recieved-success.jpg') ?>" srcset="<?= image_srcset('vector-illustration-order-recieved-success.jpg', 'vector-illustration-order-recieved-success@2x.jpg') ?>" alt="vector illustration depicting a successfully recieved order" width="280" height="194">
      </div> 

      <div class="order-summary sub-container" data-accordion="open">
        <h3 class="order-received__order-summary-heading" data-accordion-toggle>Order Summary</h3>
        <div data-accordion-content>
        <table class="shop_table woocommerce-checkout-review-order-table" style="position: static; zoom: 1;">
          <tbody>
            <?php foreach ( $items as $item ) : ?>
              <tr class="cart_item">
                <td class="product-name">
                  <?= $item['name'] ?> x <strong class="product-quantity"><?= $item['quantity'] ?></strong>
                  <dl class="variation">
                    <dt class="variation-Colorise">Colourise:</dt>
                    <dd class="variation-Colorise"><p><?= $item->get_meta('Colourise') ?></p></dd>
                    <dt class="variation-Prints">Prints:</dt>
                    <dd class="variation-Prints"><p><?= $item->get_meta('Prints') ?></p></dd>
                    <?php if ( $item->get_meta('Prints') == "Yes" ) : ?>
                    <dt class="variation-Sizes">Sizes:</dt>
                    <dd class="variation-Sizes"><p>10x8:5</p></dd>
                    <?php endif ?>
                    <dt class="variation-ProcessingSpeed">Processing Speed:</dt>
                    <dd class="variation-ProcessingSpeed"><p><?= $item->get_meta('Processing Speed') ?></p></dd>
                  </dl>
                </td>
                <td class="product-total">
                  <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?= get_woocommerce_currency_symbol( $order->get_currency() )  ?></span><?= wc_format_decimal( $item->get_total(), 2) ?></span>					
                </td>
              </tr>

            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr class="cart-subtotal">
              <th>Subtotal</th>
              <td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?= get_woocommerce_currency_symbol( $order->get_currency() ) ?></span><?= wc_format_decimal($order->get_subtotal(), 2)?></span></td>
            </tr>
            <tr class="woocommerce-shipping-totals shipping">
              <th>Shipping</th>
              <td data-title="Shipping">
                <ul id="shipping_method" class="woocommerce-shipping-methods">
                  <li>
                    <input type="hidden" name="shipping_method[0]" data-index="0" id="shipping_method_0_flat_rate3" value="flat_rate:3" class="shipping_method">
                    <label for="shipping_method_0_flat_rate3">
                      <?php 
                        if ( ! $shipping ) {
                          $html = "None";
                        }
                        else {
                          $html = $order->get_shipping_method() . ":";
                          $html .= ( $order->get_shipping_total() == 0 ) ? "Free" : wc_format_decimal( $order->get_shipping_total() );
                        }
                        echo $html;
                      ?>
                    </label>					
                  </li>
                </ul>
              </td>
            </tr>
            <tr class="order-total">
              <th>Total</th>
              <td><strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?= get_woocommerce_currency_symbol( $order->get_currency() ) ?></span><?= wc_format_decimal( $order->get_total(), 2) ?></span></strong> </td>
            </tr>
          </tfoot>
        </table>
        </div> <!-- end accordion content -->
      </div>
    </div>

    <?php the_content(); ?>

  </main>

  <aside class="container pt-0">
    <div class="separator"></div>
    <?php get_template_part('template-parts/related-posts', false, array("heading" => "You may be interested in...")); ?>
  </aside>
  


  <?php endwhile; // End of the loop. ?>

  <?php get_footer();
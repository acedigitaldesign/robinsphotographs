const WOO = {
  init() {
    WOO.moveCheckoutErrorNotices();
    WOO.reconfigureReturnToShopLinks();
    WINDOW_EVENTS.eventTypes.pageshow.cacheLoadMethods.arr.push(WOO.monitorAjaxCalls);
  },

  monitorAjaxCalls() {
    // if click browser back on order received page, reloads checkout
    // but woo replaces all content with a 'session expired' link
    // The link doesn't seem to be affected by filters
    // So when clicking back on browser, running this method to watch for the cessation of AJAX calls and then checks to see if there are any of those pesky 'Back to Shop' links that needs reconfiguring...
    jQuery(document).ajaxStop(function() {
      WOO.reconfigureReturnToShopLinks();
    });
  },

  moveCheckoutErrorNotices() {
    jQuery('body').on('checkout_error', function(){

      if (jQuery('ul.woocommerce-error').length) {
        jQuery('.woocommerce-NoticeGroup.woocommerce-NoticeGroup-checkout').insertAfter('form.checkout .checkout__customer-details');//where you want to place 
      }
      
    });

  },

  reconfigureReturnToShopLinks() {
    // Ultimately, this probably won't be needed when actually have a 'Shop'
    // Setting 'Shop' page to home page messes up the content inside testionials...weird...
    // And actually, the link has target="_blank" on it which is annoying so still might use this to dynamically alter these link attributes...
    let links = document.querySelectorAll(".wc-backward");
    if ( ! links ) return 
    for ( let i = 0; i < links.length; i++ ) {
      links[i].setAttribute('href', '/start-repair/');
      links[i].setAttribute('target', '_self');
      links[i].classList.add('link-primary');
      links[i].classList.add('icon-arrow-long-right');
      links[i].textContent = "Start your repair";
    }
  }
}

jQuery(document).ready(function(){
  WOO.init();
});



// dunno what this is...
// jQuery(".woocommerce-billing-fields p").removeClass("eh_pe_checkout_fields_hide");
// jQuery(".woocommerce-billing-fields p").removeClass("eh_pe_checkout_fields_fill");
// jQuery(".woocommerce-billing-fields .eh_pe_address").hide();
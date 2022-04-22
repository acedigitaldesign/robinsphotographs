/******************************************
JS - Loaders
******************************************/ 
const SPINNER = {

  target: null,

  init(targetSelector, variationClass, customClass) {
    if(targetSelector == null) { 
      throw new Error('Function "appendLoader" requires the targetSelector parameter to be specified, eg: ".js-overlay"');
    }
    let loaderVariationClass = null;

    if(variationClass == null) { 
      loaderVariationClass = 'spinner-loader-default';
    }
    else {
      loaderVariationClass = variationClass;
    }

    // Build loader container
    SPINNER.target =  targetSelector;
    const spinnerContainer = document.createElement('div');
    spinnerContainer.removeAttribute("class");
    spinnerContainer.setAttribute("data-spinner-container");
    jQuery(spinnerContainer).addClass('spinner-container');
    jQuery(spinnerContainer).addClass(loaderVariationClass);
    jQuery(spinnerContainer).addClass(customClass);
    
  
    // Build spinner
    const spinner = document.createElement('div');
    spinner.removeAttribute("class");
    spinner.setAttribute("data-spinner");
    spinner.classList.add('spinner');
  
    // Add spinner to loader
    jQuery(spinnerContainer).prepend(spinner);

    // Initialize function to add loader to DOM
    SPINNER.add(spinnerContainer);
  },
  
  add(loader) {
    jQuery(SPINNER.target).addClass('position-relative disable').prepend(loader);

    // Fades in the loader
    // Requires 'display: none' set on the .loader class
    // setTimeout(function(){
    //   jQuery(loader).fadeTo(200, 1);
    // }, 50);
    // jQuery( ".filter-results  > .row" ).removeClass('disable').fadeTo( 200 , 1).html(response);
  },

  remove() {
    jQuery('[data-spinner-container]').remove();
    jQuery(SPINNER.target).removeClass('position-relative disable');
  }
  
  
}
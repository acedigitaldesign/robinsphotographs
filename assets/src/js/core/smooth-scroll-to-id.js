/******************************************
JS - Smooth Scroll to ID
******************************************/
const smoothScrollToID = {

  // If URL has a hash upon page load, this initiates the smooth scroll to the hash
  initializeOnPageLoad() {
    if (window.location.hash) {
      
      // Ensures the hash does not point to out of date link...
      if(document.querySelector(window.location.hash) == undefined) {
        
        if(history.pushState) {
          history.replaceState(null, null, window.location.href.split(/[?#]/)[0]);
        } else {
          location.hash = window.location.href.split(/[?#]/)[0];
        }
        return;
      }
      scroll(0,0);
      // takes care of some browsers issue
      setTimeout(function(){
        scroll(0,0);
      }, 1);

      // smooth scroll to the anchor id
      setTimeout(function(){
      var position = jQuery(window.location.hash).offset().top - TOOLBOX.scrollTop.offset.value;
      jQuery('html, body').animate({ scrollTop: position }, 1000, 'easeInOutCubic');
      }, 500);

    }
  },

  initializeListeners(scrollLinkSelector) {
    // Checks whether a scrollLinkSelector argument is provided. This is so that the function can be called safely for specific generated links after DOM load, eg. Alert links "View Comment" etc
    if(scrollLinkSelector == null) { 
      throw new Error('Function "smoothScroll" requires the scrollLinkSelector parameter to be specified (as a string, eg. ".js-alert-text a")');
    }
    jQuery(scrollLinkSelector).on('click', smoothScrollToID.initialize);
   },
   
  initialize(event) {
    var trigger = event.currentTarget;
    var hash = (trigger.hash !== "") ? trigger.hash : null;
    var hrefURL = trigger.href.split(/[?#]/)[0];
    var hrefURLwithoutProtocol = hrefURL.replace(/^(?:https?:\/\/)?(?:www\.)?/i, "");
    var currentHostwithoutProtocol = window.location.host.replace(/^(?:https?:\/\/)?(?:www\.)?/i, "");
    var currentPath = window.location.pathname;
    var currentURL = currentHostwithoutProtocol + currentPath;
    var URLmatch = (currentURL == hrefURLwithoutProtocol) ? true : false;

    if(hash !== null && URLmatch == true) {
      // Prevent default anchor click behavior
      event.preventDefault();

      var targetId = hash;
      var offset = (TOOLBOX.scrollTop.position < jQuery(targetId).offset().top) ? TOOLBOX.scrollTop.offset.value - 3 : TOOLBOX.scrollTop.offset.value - 2;
      var position = (jQuery(targetId).offset().top - offset); // <- Specifies end target position from the top of the viewport

      // Using jQuery's animate() method to add smooth page scroll
      jQuery('html, body').animate({ scrollTop: position }, 600, 'easeInOutCubic', function(){
        
          if(history.pushState) {
            history.replaceState(null, null, targetId);
          } else {
            location.hash = targetId;
          }

          smoothScrollToID.additionalCallbacks.run();

        } );
        
      } // End if hash has a value check
        
    }, // End on Click event function
        
  additionalCallbacks: {
    array: [],
    run() {
      var array = smoothScrollToID.additionalCallbacks.array;
      if (array === undefined || array.length == 0) return;
      for (var i = 0; i < smoothScrollToID.additionalCallbacks.array.length; i++) {
        smoothScrollToID.additionalCallbacks.array[i]();
      }
    }
  }
  
}


jQuery(document).ready(function(){
  smoothScrollToID.initializeOnPageLoad();
  smoothScrollToID.initializeListeners('a');
});
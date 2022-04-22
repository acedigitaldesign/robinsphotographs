/******************************************
JS - Window Events
// Assign functions to be executed on the respective window event
******************************************/
const WINDOW_EVENTS = {

  eventTypes: {

    pageshow: {
      init(e) {
        // if multiple functions call pageLoadedFromCache method it wont work as it sets an input value in DOM which will be detected falsely if multiple methods checking...
        WINDOW_EVENTS.eventTypes.pageshow.checkPageLoadedFromCache(e); // <- must only ever be called once!
      },
      checkPageLoadedFromCache(e) {

        // Multiple detection methods needed due to browser inconsistencies using pageshow event
    
        // Detection method 1;
        // Doesn't work in Chrome but does in FF
        function cacheLoadDetectionMethod1(e) {
          return ( e.persisted) ? true : false;
        }
        // Detection method 2:
        // Doesn't work in FF but does in Chrome
        function cacheLoadDetectionMethod2() {
          let backButton = document.querySelector("[data-backbutton-state]");
          if ( backButton.value == 0 ) {
            // Page has been loaded for the first time - Set marker
            backButton.value = 1;
            return false;
          } else {
            // Back button has been fired
            return true;
          }
        }
        
        // if page loaded from cache (ie. user clicked back button)
        if ( cacheLoadDetectionMethod1(e) || cacheLoadDetectionMethod2() ) {
          
          // Run pageload from cache functions
          // Has to be set in annoymous function else won't work in Edge...
          setTimeout(function() {
            WINDOW_EVENTS.eventTypes.pageshow.cacheLoadMethods.run();
          }, 50);
        }
        else {
          return false;
        }
      },

      cacheLoadMethods: {
       arr: [],
       run() {
        if(WINDOW_EVENTS.eventTypes.pageshow.cacheLoadMethods.arr.length == 0) return;

        for (var i = 0; i < WINDOW_EVENTS.eventTypes.pageshow.cacheLoadMethods.arr.length; i++) {
          WINDOW_EVENTS.eventTypes.pageshow.cacheLoadMethods.arr[i]();
        }
      }
    }


  //   resizeAndReorient: {
  //     array: [],
  //     run() {
  //       if(WINDOW_EVENTS.eventTypes.resizeAndReorient.array.length == 0) return;
  //       for (var i = 0; i < WINDOW_EVENTS.eventTypes.resizeAndReorient.array.length; i++) {
  //         WINDOW_EVENTS.eventTypes.resizeAndReorient.array[i]();
  //       }
  //     }
  //   },

  //   scroll: {
  //     array: [],
  //     run() {
  //       if(WINDOW_EVENTS.eventTypes.scroll.array.length == 0) return;
  //       for (var i = 0; i < WINDOW_EVENTS.eventTypes.scroll.array.length; i++) {
  //         WINDOW_EVENTS.eventTypes.scroll.array[i]();
  //       }
  //     }
  //   },
    }

  }

}

window.addEventListener('pageshow', WINDOW_EVENTS.eventTypes.pageshow.init ); 
// window.addEventListener('resize', WINDOW_EVENTS.eventTypes.resizeAndReorient.run);
// window.addEventListener('orientationchange', WINDOW_EVENTS.eventTypes.resizeAndReorient.run);
// window.addEventListener('scroll', WINDOW_EVENTS.eventTypes.scroll.run);


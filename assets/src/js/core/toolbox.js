/******************************************
JS - Tools
// Collection of helper functions inside the tools object
******************************************/
const TOOLBOX = {

  pageLoadFromCache: false,
  // Returns selector with unwanted spaces
  // If cant find selector, returns undefined
  trim(selector) {
    if(selector == null || selector == "" || selector == undefined) {
      return undefined;
    }
    else {
      return selector.trim();
    }
  },

  // Pass comma separated list of timeout variable names
  clearTimeouts() { 
    for (var i = 0; i < arguments.length; i++) {
      clearTimeout(arguments[i]); 
    }
  },

  // Check browser/device type. Pass arguments to check as an iterative array. Valid args are found in initalize index. Eg: TOOLBOX.browserCheck.initialize(['oldIpad', 'samsungBrowser'])
  browserCheck: {
    match: 0,

    initialize(args) {
      var index = {
        'oldIpad'         : TOOLBOX.browserCheck.methods.oldIpad,
        'samsungBrowser'  : TOOLBOX.browserCheck.methods.samsungBrowser,
        'oldGalaxyTab'    : TOOLBOX.browserCheck.methods.oldGalaxyTab
      }

      for(var i = 0; i < args.length; i++) {
        for(var key in index) {
          if(key == args[i]) {
            index[key]();
          }
        }
      }
      if(TOOLBOX.browserCheck.match > 0) return true;
      else return false;
    },

    methods: {

      oldIpad() {
        var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        if(iOS == true && window.devicePixelRatio == 1) {
          return TOOLBOX.browserCheck.match++;
        }
      },

      samsungBrowser() {
        var isSamsungBrowser = navigator.userAgent.match(/SamsungBrowser/i);
        if(isSamsungBrowser) {
          return TOOLBOX.browserCheck.match++;
        }
      },

      oldGalaxyTab() {
        var ua = navigator.userAgent;
        if( ua.indexOf("Android") >= 0 ) {
          var androidversion = parseFloat(ua.slice(ua.indexOf("Android")+8)); 
          if (androidversion < 4.5) {
            return TOOLBOX.browserCheck.match++
          }
        }
      }
    }
  },

  setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    document.cookie = name+"="+value+expires+"; path=/;";
  },

  getCookie(name) {
    // Split cookie string and get all individual name=value pairs in an array
    var cookieArr = document.cookie.split(";");

    // Loop through the array elements
    for(var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");
        
        /* Removing whitespace at the beginning of the cookie name
        and compare it with the given string */
        if(name == cookiePair[0].trim()) {
            // Decode the cookie value and return
            return decodeURIComponent(cookiePair[1]);
        }
    }
    
    // Return null if not found
    return null;
  },
  // getAllCookies() {
  //   // Split cookie string and get all individual name=value pairs in an array
  //   var cookieArr = document.cookie.split(";");

  //   // Loop through the array elements
  //   for(var i = 0; i < cookieArr.length; i++) {
  //       var cookiePair = cookieArr[i].split("=");
        
  //       /* Removing whitespace at the beginning of the cookie name
  //       and compare it with the given string */
  //       if(name == cookiePair[0].trim()) {
  //           // Decode the cookie value and return
  //           return decodeURIComponent(cookiePair[1]);
  //       }
  //   }
  // },

  // Units takes a true or false argument
  getScrollHeight(content, units) {
    return content.scrollHeight + ((units === undefined || units === true) ? "px" : null);
  },

  // Calculates the current scroll position (top of the viewport) from the top of the document
  scrollTop: {

    recalculate() {
      TOOLBOX.scrollTop.position = jQuery(window).scrollTop();
    },

    position: jQuery(window).scrollTop(),

    // Desired offset from top of screen when scrolling to IDs (currently set to 10%)
    offset: {
      value: Math.round(jQuery(window).height() * .1), 
      recalculate() { 
        TOOLBOX.scrollTop.offset.value = Math.round(jQuery(window).height() * .1); 
      } 
    }
  },


  sumArray(arr) {
    let sum = arr.reduce(function(a, b) {
      return a + b;
    }, 0);
    return parseInt(sum);
  }, 

  // Shorthand to push functions into windowEvents arrays
  // addToWindowEvents(eventType, targetFunction) {
  //   var eventTypes = Object.getOwnPropertyNames(windowEvents.eventTypes);
  //   if(eventTypes.indexOf(eventType) < 0 || eventType === undefined) {
  //     throw new Error('Please specify an eventType. Permitted arguments are the property names in the object: windowEvents.eventTypes');
  //   }
  //   if(targetFunction === undefined) {
  //     throw new Error('Please specify a valid function name');
  //   }
    
  //   return windowEvents.eventTypes[eventType].array.push(targetFunction);
  // },

  // Shorthand to push functions into windowEvents arrays
  strip(html){
    var doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || "";
  }

}


jQuery(document).ready(function(){
  
  document.addEventListener('scroll', TOOLBOX.scrollTop.recalculate);
  document.addEventListener('resizeAndReorient', TOOLBOX.scrollTop.offset.recalculate);
});

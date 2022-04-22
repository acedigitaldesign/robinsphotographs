/******************************************
JS - Cookie Notice
******************************************/
const COOKIE_NOTICE = {
  popup: "",
  overlay: "",
  button: "",
  showTimeout: null,
  destroyTimeout: null,
  unauthorisedCookies: {},

  init() {
    COOKIE_NOTICE.popup = document.querySelector('[data-cookie-notice]');
    COOKIE_NOTICE.overlay = document.querySelector('[data-cookie-notice-overlay]');
    COOKIE_NOTICE.button = document.querySelector('[data-cookie-notice-button]');

    // Push method to arr of functions that run if the page is loaded from cache (ie by clicking back button
    WINDOW_EVENTS.eventTypes.pageshow.cacheLoadMethods.arr.push(COOKIE_NOTICE.checkCookieOnCacheLoad);

    // Cookie notice isn't instantiated in DOM if cookie set
    // No need to run if popup not in DOM (ie. cookie already identified server side )
    if ( ! COOKIE_NOTICE.popup ) return;

    // COOKIE_NOTICE.recordUnauthorisedCookies();
    COOKIE_NOTICE.addListeners();
    COOKIE_NOTICE.activate();

  },

  addListeners() {
    COOKIE_NOTICE.overlay.addEventListener('click', COOKIE_NOTICE.vibrate);
    COOKIE_NOTICE.button.addEventListener('click', COOKIE_NOTICE.setCookieAndDismissNotice);
  },

  activate() {
    // set on timeout so it's not immediately visible; Mitigates 'flash' if removed on cacheLoad
    COOKIE_NOTICE.showTimeout = setTimeout(function() {
      COOKIE_NOTICE.popup.setAttribute('data-active', 'true');
    }, 300);

  },
  deactivate() {
    COOKIE_NOTICE.popup.setAttribute('data-active', 'false');
    // COOKIE_NOTICE.restoreUnauthorisedCookies();
    COOKIE_NOTICE.destroyTimeout = setTimeout(function() {
      COOKIE_NOTICE.destroy();
    }, 300);
  },

  vibrate() {
    if (COOKIE_NOTICE.popup.getAttribute('data-cookie-notice') == 'vibrating') return; // do not proceed if notice already vibrating

    COOKIE_NOTICE.popup.setAttribute('data-cookie-notice', 'vibrating');

    setTimeout(function() {
      COOKIE_NOTICE.popup.setAttribute('data-cookie-notice', "");
    }, 600); // Timeout same duration as css vibrate animation
  },
  
  setCookieAndDismissNotice() {
    if (COOKIE_NOTICE.popup.getAttribute('data-cookie-notice') == 'vibrating') return; // do not proceed if notice already vibrating

    TOOLBOX.setCookie('acecookieconsent','accepted', 365);
    COOKIE_NOTICE.deactivate();
    // COOKIE_NOTICE.restoreUnauthorisedCookies();
  },

  destroy() {
    jQuery(COOKIE_NOTICE.popup).remove();
    jQuery(COOKIE_NOTICE.overlay).remove();
  },

  checkCookieOnCacheLoad() {
    window.clearTimeout(COOKIE_NOTICE.showTimeout);
    if ( ! COOKIE_NOTICE.popup ) return; // nothing to do if popup not in DOM (ie. cookie already identified server side )
    if ( TOOLBOX.getCookie("acecookieconsent") == "accepted" ) {
      COOKIE_NOTICE.deactivate();
      // COOKIE_NOTICE.restoreUnauthorisedCookies();
      
    }
    else {
      // COOKIE_NOTICE.recordUnauthorisedCookies();
      COOKIE_NOTICE.addListeners();
      COOKIE_NOTICE.activate();
    }
  }

  // recordUnauthorisedCookies() {
  //   let cookies = document.cookie.split(";");     
  //   for (let i = 0; i < cookies.length; i++) {
  //     let cookie = cookies[i].split("=");
  //     COOKIE_NOTICE.unauthorisedCookies[cookie[0].trim()] = cookie[1];
  //  }
  //  console.log(COOKIE_NOTICE.unauthorisedCookies);
  //  setTimeout(function() {
  //    for ( const prop in COOKIE_NOTICE.unauthorisedCookies) {
  //     COOKIE_NOTICE.removeUnauthorisedCookies(prop);
  //    }
  //   }, 500);
  // }

  // removeUnauthorisedCookies(name) {
  //   // This function will attempt to remove a cookie from all paths.
  //   var pathBits = location.pathname.split('/');
  //   var pathCurrent = ' path=';
  //   var name = COOKIE_NOTICE.unauthorisedCookies;

  //   // do a simple pathless delete first.
  //   document.cookie = name + '=; expires=Thu, 01-Jan-1970 00:00:01 GMT;';

  //   for (var i = 0; i < pathBits.length; i++) {
  //       pathCurrent += ((pathCurrent.substr(-1) != '/') ? '/' : '') + pathBits[i];
  //       document.cookie = name + '=; expires=Thu, 01-Jan-1970 00:00:01 GMT;' + pathCurrent + ';';
  //   }
  // },

  // restoreUnauthorisedCookies() {
  //   if ( COOKIE_NOTICE.unauthorisedCookies.length == 0 ) return;
  //   for (const prop in COOKIE_NOTICE.unauthorisedCookies)  {
  //     TOOLBOX.setCookie(prop,COOKIE_NOTICE.unauthorisedCookies[prop], 1);
  //   }
  // }

}

jQuery(document).ready(function(){
  COOKIE_NOTICE.init();
});

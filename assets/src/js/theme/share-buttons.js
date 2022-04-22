/****************************************************
JS: Social Buttons
- Slide in when moving down the post (down past the static socials at top)
- Slide back out when returning to top (when the top static socials are in view again)
****************************************************/
const SHARE_BUTTONS = {

  init() {
    if ( ! document.querySelector("[data-share-buttons]") ) return;
    SHARE_BUTTONS.instantiateSticky();
    // SHARE_BUTTONS.initializeShareScripts();
    window.addEventListener('scroll', SHARE_BUTTONS.initializeSticky );
    window.addEventListener('resize', SHARE_BUTTONS.initializeSticky);
    window.addEventListener('orientationchange', SHARE_BUTTONS.initializeSticky);
  },

  // Create new div for sticky socials and clone the content of the static socials into it, adding relevant classes etc
  instantiateSticky() {
    var socialButtons = document.querySelector("[data-share-buttons]");
    var socialButtonsSticky = document.createElement("div");
    socialButtonsSticky.setAttribute('data-share-buttons-sticky', "");
    socialButtonsSticky.setAttribute('data-active', 'false');
    socialButtonsSticky.classList.add('share-buttons__sticky');
    socialButtonsSticky.innerHTML = socialButtons.innerHTML;
    document.body.appendChild(socialButtonsSticky);
  },

  // On scroll, listens for and then trigger position to show/hide sticky socials
  // Set to open after scroll past first set of share buttons in entry header
  // Set to close when second set of share buttons in entry footer come in to view
  initializeSticky() {
    var mq = window.matchMedia( "(min-width: 900px)" );
    var el = document.querySelector('[data-share-buttons-sticky]');

    // if window width is at greater than or equal to 900px:
    if (mq.matches) {
      var openTrigger = jQuery("[data-share-buttons]").first();
      var closeTrigger = jQuery("[data-share-buttons]").last();
      var openTriggerElementTop = openTrigger.offset().top;
      var openTriggerElementHeight = openTrigger.first().outerHeight();
      var closeTriggerElementTop = closeTrigger.offset().top;
      var windowHeight = jQuery(window).height();
      var openTriggerPosition = openTriggerElementTop + openTriggerElementHeight;
      var closeTriggerPosition = closeTriggerElementTop - windowHeight;

      if(TOOLBOX.scrollTop.position < openTriggerPosition || TOOLBOX.scrollTop.position > closeTriggerPosition) {
      // if(TOOLBOX.scrollTop.position < openTriggerPosition) { 
        el.setAttribute('data-active', 'false');
      }
      else {
        el.setAttribute('data-active', 'true');
      }
    }

    // if window width is 899px or less, hide the share buttons:
    else {
      el.setAttribute('data-active', 'false');
    }
  },

  initializeShareScripts() {
    // Facebook:
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); 
      js.id = id;
      js.async = true;
      js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));


    // Pinterest:
    (function(d) {
      var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
      p.type = 'text/javascript';
      p.async = true;
      p.src = '//assets.pinterest.com/js/pinit.js';
      f.parentNode.insertBefore(p, f);
    }(document));


    // Twitter:
    window.twttr = (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0],
        t = window.twttr || {};
      if (d.getElementById(id)) return t;
      js = d.createElement(s);
      js.id = id;
      // js.async = true;
      js.src = "https://platform.twitter.com/widgets.js";
      fjs.parentNode.insertBefore(js, fjs);
    
      t._e = [];
      t.ready = function(f) {
        t._e.push(f);
      };
    
      return t;
    }(document, "script", "twitter-wjs"));



    // Share Dialogue Popup Functionality
    (function($) {
      $('.js-share-twitter-link').click(function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        window.open(href, "Twitter", "height=285,width=550,resizable=1");
      });
      $('.js-share-facebook-link').click(function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        window.open(href, "Facebook", "height=269,width=550,resizable=1");
      });
      $('.js-share-whatsapp-link').click(function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        window.open(href, "Whatsapp", "height=450,width=550,resizable=1");
      });
    })(jQuery);
  }
}


jQuery(document).ready(function() {
  SHARE_BUTTONS.init();
});

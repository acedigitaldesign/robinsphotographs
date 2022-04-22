/******************************************
JS - Popups
******************************************/
const POPUP = {

  target: {
    selector: null,
    element: null,
    isPreloaded: null,
    source: null,
    hasOverlay: null,
    isActive: null,
    hideTimeout: null
  },

  init() {
    jQuery('[data-popup]').hide();
    POPUP.addListeners();
  },

  // Add popup trigger listeners
  addListeners(element) {
    let scope = (element === undefined) ? document : element;
    let popupTrigger = scope.querySelectorAll('[data-popup-trigger]');
    if ( ! popupTrigger ) return;
    for (let i = 0; i < popupTrigger.length; i++) {
      popupTrigger[i].addEventListener('click', POPUP.prepare);
    }
  },

  prepare(e) {

    POPUP.reset(e);
    // POPUP.oldDeviceSupport.init(); // <- If an old iDevice, run the support functions
    POPUP.constructTarget(e);

    if(POPUP.target.source == 'local') {
      POPUP.activate(); // activate popup if already loaded in DOM
    }
    else {
      POPUP.load(); // else load via AJAX into DOM
    }
  },

  reset(e) {
    e.preventDefault();
    window.clearTimeout(POPUP.target.hideTimeout);
  },

  constructTarget(e) {
    let popup = TOOLBOX.trim(e.target.getAttribute('data-popup-trigger')); 
    let source = TOOLBOX.trim(e.target.getAttribute('data-popup-source')); 
    let overlay = TOOLBOX.trim(e.target.getAttribute('data-popup-overlay'));
    let selector = '[data-popup="' + popup + '"]';

    POPUP.target.selector = selector;
    POPUP.target.element = document.querySelector(selector);
    POPUP.target.isPreloaded = (POPUP.target.element == undefined) ? false : true;
    POPUP.target.source = (source == undefined || POPUP.target.isPreloaded == true) ? 'local' : source;
    POPUP.target.hasOverlay = (overlay == undefined || overlay !== 'false') ? true : false;
  },

  load() {
    let popupData = {
      action: 'robinsphotographsAjaxPopup',
      popup: POPUP.target.source,
      security: ajax_subscribe_object.security
    }
    jQuery.ajax({
      type: "POST",
      data: popupData,
      url: ajax_object.ajax_url,

      beforeSend() {
        if( POPUP.target.hasOverlay ) OVERLAY.activate();
        SPINNER.append('body', '-lightbox -fixed');
      },

      error(response) {
        throw new Error(response);
      },

      success(popupHTML) {
        SPINNER.remove();
        POPUP.appendToDOM(popupHTML);
        // activePopup = new POPUP.ActivePopup(selector, source, hasOverlay);
        setTimeout(POPUP.activate, 70); // Fractional delay to allow older devices to catch up
        // popup.activate();

      } // <- End of successful ajax post logic

    }); // <- End of ajax method

  },

  activate() {
    // POPUP.rescale();
    // document.addEventListener('resizeAndReorient', POPUP.rescale);
    // if(activePopup.hasForm) form.initialize(activePopup.form);
    jQuery('[data-popup]').show();
    if( POPUP.target.hasOverlay && OVERLAY.active === false) OVERLAY.activate();
    OVERLAY.activeContent.push(POPUP.deactivate);
    POPUP.target.element.setAttribute('data-active', 'true');
    POPUP.addCloseBtnListener();
  },

  /* Assigns close button click listeners */
  addCloseBtnListener() {
  let closeBtn = POPUP.target.element.querySelectorAll('[data-close]');
  if( ! closeBtn ) return;
  for (let i = 0; i < closeBtn.length; i++) {
    closeBtn[i].addEventListener('click', POPUP.deactivate);
  }
  },

  /* Popup close functionality */
  deactivate() {
    // if(POPUP.target.hasOverlay) OVERLAY.deactivate();
    POPUP.target.element.setAttribute('data-active', 'false');
    OVERLAY.element.setAttribute('data-overlay', 'inactive');

    // set timeout to hide popup once disappeared so point events don't interfere with main DOM elements. 
    // delay is equal to transition time in CSS
    POPUP.target.hideTimeout = setTimeout(function() {
      jQuery('[data-popup]').hide();
    }, 800); // <- delay matches max transition time in css
  },

  rescale() {
    // if(TOOLBOX.oldIpadCheck() === true) return;
    if( jQuery(POPUP.target.selector + ' input:focus' ).length > 0 ) return;
    var margin = 18; // <- Currently equivalent of 1.2rem, same as $margin__phone sass variable
    var elMinHeight = jQuery(POPUP.target.selector).height() + (margin * 2);
    var elMinWidth = parseInt(jQuery(POPUP.target.selector).css('min-width'));
    var viewportWidth = window.innerWidth - (margin * 2);
    var viewportHeight = window.innerHeight - (margin * 2);
    var scale = popupScale();
  
    // Derives the popup scale value:
    function popupScale() {
      var minScaleValue = Math.min(
        viewportWidth / elMinWidth,  
        viewportHeight / elMinHeight
      );
      var scale = (minScaleValue > 1) ? 1 : minScaleValue; // <- If viewport is smaller in either dimension than the minimum dimensions of the popup, then set the scale value
      return scale;
    }
  
    jQuery(".js-popup-container").css({transform: "scale(" + scale + ")"});
  },

  /* Handles the insertion of the dynamically loaded popup into the DOM */
  appendToDOM(popupHTML) {
    var tempDiv = document.createElement('div'); // 1. Create a temporary div
    tempDiv.innerHTML = popupHTML; // 2. Insert popup HTML into temp div
    var popup = jQuery(tempDiv).contents(); // 3. Grabs the temp div contents
    jQuery(tempDiv).replaceWith(popup); // 4. Replaces parent temp div with it's children - essentially leaving just the popup html
    jQuery(popup).appendTo('.js-popup-container'); // 5. Insert popup just before site header, next to other preloaded dialogs (eg. mobile menu and alerts). Now, don't have to dynamically load this div any more until page refreshes!
  },

  /* New active popup constructor*/
  ActivePopup(selector, source, overlay) {
    // this.hideTimeout;
    this.selector = selector;
    this.element = document.querySelector(this.selector);
    this.source = source;
    this.hasOverlay = overlay;
    this.hasForm = (this.element.querySelector('form') !== undefined) ? true : false;
    this.form = (this.hasForm) ? this.element.querySelector('form') : null;

    this.activate = function() {
      this.element.classList.add('-is-active');
    }
  },




  /******************************************
  JS - Popups: Functions - Old Device Support
  ******************************************/
  /******************************************
  Brief Explanation
  // There are 2 reasons for this support:
  // 1. Bouncing in and out of display when focusing on an input field (when popup or its parent is set to fixed)
  // 2. Flashing content overlay when focusing on an input

  // 1. Bouncing in and out of display when focusing on (ie. tapping in to) an input field:
  // If left set at fixed, and you focus on an input field, the popup bounces out of display and back in again...
  // Very distracting and offputting for a would-be subscriber hence the need to create a better user experience.
  // Therefore much of these support functions roles are to change popupContainer to absolute positioning (from fixed) and then manually manage the popups subsequent position in the viewport (everything you would normally get for free using position: fixed)

  // 2. Flashing content overlay:
  // On long DOMs, for some unknown reason, when you tap into the input field, the page seems to be repainting/reflowing the DOM
  // So if content overlay is set to top: 0, it will repaint from the top. (so it seems...)
  // However, if popup invoked at the bottom of the DOM, eg. from prefooter button, there is a small flash as the content overlay disappears and reappears as its painted
  // This does not happen at the top of the DOM but is evidenced further by the fact the flashing on and off gets progressively worse the further you are down the page. (the opposite effect is seen when position set to bottom: 0)
  // Hence the need to create a much smaller content overlay height (eg. 2000px) so doesn't have much to repaint
  // The reason it's set to 2000px in height is that it gives some visual buffer either side of the viewport when scrolling - if set to 100vh, it jutters badly when scrolling up and down
  // Therefore one of these functions handle the content overlays positioning, centering it visually in the viewport.
  ******************************************/

  oldDeviceSupport: {
    /* If old devices, changes popupContainer to absolute positioning */
    init() {
      // if (TOOLBOX.oldIpadCheck() === false) return; // <- Prevents firing if not an old device
      if (TOOLBOX.browserCheck.initialize(['oldIpad', 'samsungBrowser']) === false) return; // <- Prevents firing if not an old device

      // windowEvents.eventTypes.resizeAndReorient.array.push(popup.oldDeviceSupport.overlayPositionCalculator);
      // windowEvents.eventTypes.scroll.array.push(popup.oldDeviceSupport.overlayPositionCalculator);
      // windowEvents.eventTypes.resizeAndReorient.array.push(popup.oldDeviceSupport.screenTurnCorrector);

      TOOLBOX.addToWindowEvents('resizeAndReorient', POPUP.oldDeviceSupport.overlayPositionCalculator);
      TOOLBOX.addToWindowEvents('resizeAndReorient', POPUP.oldDeviceSupport.screenTurnCorrector);
      TOOLBOX.addToWindowEvents('scroll', POPUP.oldDeviceSupport.overlayPositionCalculator);

      jQuery(".popup-container").css({
        position: "absolute",
        top: TOOLBOX.scrollTop.position
      });

      POPUP.oldDeviceSupport.overlayPositionCalculator(); 
    },

    /* Handles content overlay positioning on old iDevices */
    overlayPositionCalculator() {
      // if (TOOLBOX.oldIpadCheck() === false) return; // <- Prevents firing if not an old device
      if (TOOLBOX.browserCheck.initialize(['oldIpad', 'samsungBrowser']) === false) return; // <- Prevents firing if not an old device
      OVERLAY.element.style.height = "2000px";
      var overlayHeight = OVERLAY.element.offsetHeight;
      var viewportHeight = window.innerHeight;
      var overlayOffset = (overlayHeight - viewportHeight) / 2;
      var documentHeight = Math.floor(document.body.offsetHeight);

      // If content overlay near top, sets it to 0 (so as to prevent DOM height being extended due upward and therefore a load of white space showing)
      if(TOOLBOX.scrollTop.position < overlayOffset) {
        jQuery(OVERLAY.selector).css({position: "absolute", top: 0});
        return;
      }
      // Conversely, if content overlay near bottom, sets positions a fixed height away from bottom (so as to prevent DOM height being extended due downward and therefore a load of white space showing)
      else if(TOOLBOX.scrollTop.position >= (documentHeight - overlayOffset - viewportHeight)) {
        jQuery(OVERLAY.selector).css({position: "absolute", top: documentHeight - overlayHeight});
        return;
      }
      // If neither near the top or bottom, it will stay visually fixed to the center of the viewport
      jQuery(OVERLAY.selector).css({position: "absolute", top: TOOLBOX.scrollTop.position - overlayOffset + "px"});
    },

    /* Handles the repositioning of the popup when turning device orientation */
    screenTurnCorrector() {
      // if (TOOLBOX.oldIpadCheck() === false) return; // <- Prevents firing if not an old device
      if (TOOLBOX.browserCheck.initialize(['oldIpad', 'samsungBrowser']) === false) return; // <- Prevents firing if not an old device
      var popupContainer = document.querySelector('.popup-container');
      var popupHeight = popupContainer.offsetHeight;
      var popupTopPosition = parseInt(popupContainer.style.top);
      var popupBottomPosition = popupTopPosition + popupHeight;
      var documentHeight = Math.floor(document.body.offsetHeight);
      var viewportHeight = window.innerHeight;

      // Below, correction calculations repeated 10 times on screen turn because just once doesn't always apply them correctly...:
      var x = 0;
      var intervalID = setInterval(function () { 

        // On reorientation, if popup position is greater than DOM height, reposition back into DOM
        if(popupBottomPosition > documentHeight) {
          popupContainer.style.top = documentHeight - popupHeight + 'px';
        }
        // If popup is totally outside (above) the viewport:
        if(popupBottomPosition < TOOLBOX.scrollTop.position + 100) {
          popupContainer.style.top = TOOLBOX.scrollTop.position + 'px';
        }

        // If in PORTRAIT mode AND popup is more than half way down the page (therefore greatly obscured by keyboard):
        if (window.matchMedia("(orientation: portrait)").matches && popupTopPosition > TOOLBOX.scrollTop.position + (viewportHeight / 2) - 100) {
          popupContainer.style.top = TOOLBOX.scrollTop.position + 'px';
        }

        if (++x === 10) {
          window.clearInterval(intervalID); // <- once repeated calc 10 times, stops
        }

      }, 100);
    }
  }
}

jQuery(document).ready(function(){
  POPUP.init();
});
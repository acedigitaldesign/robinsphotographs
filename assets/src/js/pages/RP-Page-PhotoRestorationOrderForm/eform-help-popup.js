/******************************************
JS - eForm Help Popup
******************************************/
const HELP_POPUP = {
  element: "",
  slides: "",
  footer: "",
  navDots: "",
  startBtn: "",
  nextBtn: "",
  dismissBtn: "",
  currentSlide: 0,
  hideTimeout: "",
  cookie: {
    name: "rp_upload_form_help_popup",
    value: "viewed"
  },

  init() {
    jQuery("[data-help-popup-container]").hide(); // make sure doesn't interfere with DOM element
    HELP_POPUP.element = document.querySelector("[data-help-popup]");
    HELP_POPUP.slides = HELP_POPUP.element.querySelectorAll("[data-slide]");
    HELP_POPUP.footer = HELP_POPUP.element.querySelector("[data-help-popup-footer]");
    HELP_POPUP.setSlideNumbers();
    HELP_POPUP.buildNavDots();
    HELP_POPUP.buildDismissButton();
    HELP_POPUP.buildStartButton();
    HELP_POPUP.buildNextButton();
    HELP_POPUP.initHelpTriggers();
    HELP_POPUP.addListeners();
    HELP_POPUP.activate();
  },

  // Add listeners
  addListeners() {
    // dot nav
    for (let i = 0; i < HELP_POPUP.navDots.length; i++) {
      HELP_POPUP.navDots[i].addEventListener('click', HELP_POPUP.changeSlide);
    }
    // Next button
    HELP_POPUP.nextBtn.addEventListener('click', HELP_POPUP.changeSlide);

    // close buttons
    let closeBtns = HELP_POPUP.element.querySelectorAll("[data-close]");
    for (let i = 0; i < closeBtns.length; i++) {
      closeBtns[i].addEventListener('click', HELP_POPUP.deactivate);
    }
    // Help Triggers
    let helpTriggers = document.querySelectorAll("[data-help-trigger]");
    for (let i = 0; i < helpTriggers.length; i++) {
      helpTriggers[i].addEventListener('click', HELP_POPUP.activate);
    }

  },

  activate(e) {
    // Won't activate if both a cookie is set and is NOT voluntarily triggered.
    if ( TOOLBOX.getCookie(HELP_POPUP.cookie.name) && e === undefined) return; 

    // If triggered, set current slide
    if ( e ) {
      HELP_POPUP.clearHideTimeout(); // clear hide timeout so if quickly activated again within timeout it won't disappear...
      let targetSlide = e.target.getAttribute("data-help-slide");
      HELP_POPUP.currentSlide = targetSlide;
    }

    // Init popup data
    HELP_POPUP.updateCTAs();
    HELP_POPUP.beforeAfterImgBugFix();
    HELP_POPUP.setDotStates(HELP_POPUP.currentSlide);
    HELP_POPUP.setSlidePositions(HELP_POPUP.currentSlide);
    HELP_POPUP.setNextButtonTarget(HELP_POPUP.currentSlide);

    // Activate overlay and popup
    jQuery("[data-help-popup-container]").show();
    OVERLAY.activate();
    OVERLAY.activeContent.push(HELP_POPUP.deactivate);
    HELP_POPUP.element.setAttribute('data-active', 'true');
  },

  deactivate() {
    TOOLBOX.setCookie(HELP_POPUP.cookie.name, HELP_POPUP.cookie.value, 365);
    OVERLAY.element.setAttribute('data-overlay', 'inactive');
    HELP_POPUP.element.setAttribute('data-active', 'false');

    // set timeout to hide popup once disappeared so point events don't interfere with main DOM elements. 
    // delay is equal to transition time in CSS
    HELP_POPUP.hideTimeout = setTimeout(function() {
      jQuery("[data-help-popup-container]").hide();
    }, 800);

  },

  clearHideTimeout() {
    window.clearTimeout(HELP_POPUP.hideTimeout);
  },
  
  buildNavDots() {
    if ( !(HELP_POPUP.slides.length > 1) ) return; // No need for dots if only one slide

    let nav_dots_container = document.createElement('div');
    let dot_list = document.createElement('ul');

    // set container class
    nav_dots_container.setAttribute('class', 'help-popup__nav-dot-container');

    for (let i = 0; i < HELP_POPUP.slides.length; i++) {
      let dot_item = document.createElement('li');
      let dot_link = document.createElement('a');

      // Set dot item class
      dot_list.setAttribute('class', 'help-popup__nav-dot-list');
      dot_item.setAttribute('class', 'help-popup__nav-dot-item');
      
      // Set link attributes
      dot_link.setAttribute('href', "#");
      dot_link.setAttribute('data-slide-target', i);

      dot_item.appendChild(dot_link);
      dot_list.appendChild(dot_item);
    }
    // Add list to dot container and dot container to popup
    nav_dots_container.appendChild(dot_list);
    HELP_POPUP.footer.appendChild(nav_dots_container);

    // Set dots elements
    HELP_POPUP.navDots = dot_list.querySelectorAll("a");
  },

  buildStartButton() {
    if ( !(HELP_POPUP.slides.length > 1) ) return; // No need for nav if only one slide

    let startBtn = document.createElement('a');
    startBtn.setAttribute('class', 'help-popup__start-btn | button button-primary button-small');
    startBtn.setAttribute('href', '#');
    startBtn.setAttribute('data-close', "");
    startBtn.textContent = 'Start Now';

    HELP_POPUP.footer.appendChild(startBtn); // add to DOM (popup footer)
    HELP_POPUP.startBtn = startBtn; // assign start btn to global prop
  },

  buildNextButton() {
    if ( !(HELP_POPUP.slides.length > 1) ) return; // No need for nav if only one slide

    let nextBtn = document.createElement('a');
    nextBtn.setAttribute('class', 'help-popup__next-btn | button button-primary button-small');
    nextBtn.setAttribute('href', '#');
    nextBtn.textContent = 'Next';

    HELP_POPUP.footer.appendChild(nextBtn); // add to DOM (popup footer)
    HELP_POPUP.nextBtn = nextBtn; // assign next btn to global prop
  },

  buildDismissButton() {
    if ( !(HELP_POPUP.slides.length > 1) ) return; // No need for nav if only one slide

    let dismissBtn = document.createElement('a');
    dismissBtn.setAttribute('class', 'help-popup__dismiss-btn');
    dismissBtn.setAttribute('href', '#');
    dismissBtn.setAttribute('data-close', "");
    dismissBtn.textContent = 'Dismiss';

    HELP_POPUP.footer.appendChild(dismissBtn); // add to DOM (popup footer)
    HELP_POPUP.dismissBtn = dismissBtn; // assign next btn to global prop
  },

  setNextButtonTarget(target) {
    target = parseInt(target); // converts to number for next and previous target calcs
    let lastSlide = HELP_POPUP.slides.length - 1;
    let nextTarget = (target == lastSlide) ? lastSlide : target + 1; // calc new target. If last slide, set to last slide

    HELP_POPUP.nextBtn.setAttribute("data-slide-target", nextTarget); // next
  },

  updateCTAs() {
    let lastSlide = HELP_POPUP.slides.length - 1;

    // if first slide, changes 'next' button text to 'Take the tour'
    if ( HELP_POPUP.currentSlide == 0 ) {
      HELP_POPUP.nextBtn.textContent = "Take the tour";
      jQuery(HELP_POPUP.dismissBtn).show();
    }
    else {
      HELP_POPUP.nextBtn.textContent = "Next";
      jQuery(HELP_POPUP.dismissBtn).hide();
    }

    // if last slide, swaps out 'next' button for 'start now' cta
    if ( HELP_POPUP.currentSlide != lastSlide ) {
      jQuery(HELP_POPUP.startBtn).hide();
      jQuery(HELP_POPUP.nextBtn).show();
    }
    else {
      jQuery(HELP_POPUP.startBtn).show();
      jQuery(HELP_POPUP.nextBtn).hide();
    }
  },

  setSlideNumbers() {
    for (let i = 0; i < HELP_POPUP.slides.length; i++) {
      HELP_POPUP.slides[i].setAttribute("data-slide", i);
    }
  },

  setSlidePositions(target) {

    for (let i = 0; i < HELP_POPUP.slides.length; i++) {

      let slide = HELP_POPUP.slides[i].getAttribute("data-slide");

      // current slide same as target slide (only )
      if ( slide == target ) {
        HELP_POPUP.slides[i].setAttribute("data-position", "current");
      }
      // current slide AFTER target
      else if ( slide > target ) {
        HELP_POPUP.slides[i].setAttribute("data-position", "right");
      }
      // current slide BEFORE target
      else {
        HELP_POPUP.slides[i].setAttribute("data-position", "left");
      }
    }
  },

  setDotStates(target) {
    // clear states
    for (let i = 0; i < HELP_POPUP.navDots.length; i++) {
      HELP_POPUP.navDots[i].setAttribute("data-active", "false");
    }
    // set states
    let i = target;
    HELP_POPUP.navDots[i].setAttribute("data-active", "true");
  },

  changeSlide(e) {
    let targetSlide = e.target.getAttribute("data-slide-target");

    if ( HELP_POPUP.currentSlide == targetSlide ) return;

    HELP_POPUP.setDotStates(targetSlide);
    HELP_POPUP.setSlidePositions(targetSlide);
    HELP_POPUP.setNextButtonTarget(targetSlide);
    HELP_POPUP.currentSlide = targetSlide; // update current slide
    HELP_POPUP.updateCTAs();
  },

  initHelpTriggers() {
    // jQuery(".help-popup__trigger").appendTo("#ipt_fsqm_form_6_tab_0");

    let panels = document.querySelectorAll(".ipt_fsqm_form_tab_panel");

    // key names must correspond exactly to tab names of eform
    // helpSlide value is the appropriate help slide number (numbering starts from 0)
    let helpSlides = { 
      "Upload" : {
        "helpSlide" : 1
      },
      "Colour" : {
        "helpSlide" : 3
      },
      "Prints" : {
        "helpSlide" : 4
      },
      "Speed" : {
        "helpSlide" : 5
      },
      "Review" : {
        "helpSlide" : 2
      }
    }

    // Adds panel id corresponding to panel name
    for (let i = 0; i < panels.length; i++) {
      let label = panels[i].querySelector(".ipt_uif_divider_text_inner").textContent;
      label = label.replace(/\s/g, "");
      if ( helpSlides[label] ) {
        helpSlides[label]["panelID"] = "#" + panels[i].getAttribute("id");
      }
      
    }

    // Build help triggers for each panel and append to respective panel
    for (const prop in helpSlides)  {
      let trigger = document.createElement("div");
      trigger.setAttribute('data-help-trigger', "");
      trigger.setAttribute('data-help-slide', helpSlides[prop]["helpSlide"]);
      trigger.setAttribute('class', 'help-popup__trigger');

      let panel = document.querySelector(helpSlides[prop]["panelID"]);
      panel.appendChild(trigger);
    }
  },
  
  beforeAfterImgBugFix() {
    // for whatever reason (that haven't quite pinpointed), img slider (Before & After plugin) effectively disappears when hiding and showing popup
    // think it's something to do with jquery hiding popup and resizing the screen...don't know exactly...
    // Anyhow, this fixes it by reestablishing the default values of the before/after slider each time slider activated

    let imgWidth = 200; // desired img width

    // init elems
    let imgSliderContainer = HELP_POPUP.element.querySelector("[data-help-before-after-img]");
    let imgSlider = HELP_POPUP.element.querySelector(".slider-308");
    let imgBefore = imgSlider.querySelector(".twentytwenty-before");
    let imgAfter = imgSlider.querySelector(".twentytwenty-after");
    let handle = imgSlider.querySelector(".twentytwenty-handle");

    // get intrinsic width and height of images
    let intrinsicImgWidth = imgBefore.naturalWidth;
    let intrinsicImgHeight = imgBefore.naturalHeight;

    // calc desired height of based on desired width
    let imgHeight = (intrinsicImgHeight / intrinsicImgWidth) * imgWidth;

    // set container width
    imgSliderContainer.style.width = imgWidth + "px";

    // set (ie. restablish) img slider values
    jQuery(imgSlider).attr('style','height: ' + imgHeight + 'px', 'max-width: 200px');
    jQuery(imgBefore).attr('style','clip: rect(0px, ' + imgWidth/2 + 'px, ' + imgHeight + 'px, 0px)');
    jQuery(imgAfter).attr('style','clip: rect(0px, ' + imgWidth + 'px, ' + imgHeight + 'px, ' + imgWidth/2 + 'px)');
    jQuery(handle).attr('style','left: ' + imgWidth/2 + 'px');
  }
  
}

jQuery(document).ready(function(){
  HELP_POPUP.init();
});
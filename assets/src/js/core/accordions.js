/******************************************
Javascript - Accordions
******************************************/
const ACCORDIONS = {

  openState: "",
  closedState: "",
  triggers: "",
  all: "",

  init() {
    ACCORDIONS.openState = 'open';
    ACCORDIONS.closedState = 'closed';
    ACCORDIONS.all = document.querySelectorAll('[data-accordion]');
    ACCORDIONS.triggers = document.querySelectorAll('[data-accordion-toggle]');
    ACCORDIONS.setAccordionStates();
    ACCORDIONS.initClosedAccordions();
    ACCORDIONS.initTriggerListeners();
  },

  initTriggerListeners() {
    for (var i = 0; i < ACCORDIONS.triggers.length; i++) {
      ACCORDIONS.triggers[i].addEventListener('click', ACCORDIONS.activate)
    }
  },

  // ensure all accordions have state (eg. if accordion state not already explicitly set, it will set it to closed by default)
  setAccordionStates() {
    for (var i = 0; i < ACCORDIONS.all.length; i++) {
      if ( ! ACCORDIONS.all[i].getAttribute("data-accordion")  ) {
        ACCORDIONS.all[i].setAttribute("data-accordion", ACCORDIONS.closedState);
      }
    }
  },

  // on page load, instantly close all accordions not specifically set to 'open' 
  initClosedAccordions() {
    for (var i = 0; i < ACCORDIONS.all.length; i++) {
      if ( ACCORDIONS.all[i].getAttribute("data-accordion") == ACCORDIONS.openState ) continue;
      let accordionsToClose = ACCORDIONS.all[i].querySelector("[data-accordion-content]");
      jQuery(accordionsToClose).slideUp(0); 
    }
  },

  activate(e) {
    let targetAccordion = jQuery(e.target).closest("[data-accordion]")[0];
    let targetAccordionContent = targetAccordion.querySelector("[data-accordion-content]");

    // If clicked on an open accordion, simply close it
    if( targetAccordion.getAttribute("data-accordion") == ACCORDIONS.openState ) {
      targetAccordion.setAttribute("data-accordion", ACCORDIONS.closedState);
      
      jQuery(targetAccordionContent).slideUp(200);
      return;
    }

    // Otherwise, if clicked on a closed accordion, close the active sibling
    let siblingAccordions = jQuery(targetAccordion).siblings("[data-accordion]");

    if(siblingAccordions) {

      for (var i = 0; i < siblingAccordions.length; i++) {

        if ( siblingAccordions[i].getAttribute('data-accordion') != ACCORDIONS.openState ) continue; // if not open, ignore it

        let openContent = siblingAccordions[i].querySelector("[data-accordion-content]");
        siblingAccordions[i].setAttribute('data-accordion', ACCORDIONS.closedState);
        if ( openContent ) {
          jQuery(openContent).slideUp(200);
        }
      }
    }

    // Open target accordion
    targetAccordion.setAttribute('data-accordion', ACCORDIONS.openState);
    jQuery(targetAccordionContent).slideDown(200);
  }


  
}

jQuery(document).ready(function(){
  ACCORDIONS.init();
});


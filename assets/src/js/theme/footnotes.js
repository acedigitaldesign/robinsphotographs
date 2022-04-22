const FOOTNOTE = {

  markers: null,

  init() {
    FOOTNOTE.markers = document.querySelectorAll('[data-footnote-marker]');

    if ( FOOTNOTE.markers.length == 0 ) return;

    FOOTNOTE.removeObsoleteMarkers();
    FOOTNOTE.addListeners();
  },

  addListeners() {
    for ( let i = 0; i < FOOTNOTE.markers.length; i++ ) {
      FOOTNOTE.markers[i].addEventListener('click', FOOTNOTE.popover.init);
    }
    window.addEventListener('scroll', FOOTNOTE.popover.setPosition);
    window.addEventListener('resize', FOOTNOTE.popover.setPosition);
  },

  removeObsoleteMarkers() {
    for ( let i = 0; i < FOOTNOTE.markers.length; i++ ) {
      let targetID = FOOTNOTE.markers[i].getAttribute('data-target');
      let target = document.querySelector('[data-id=' + targetID + ']');
      if ( ! target ) {
        jQuery(FOOTNOTE.markers[i]).hide();
      }
    }
  },

  get(e) {
    let target = e.target.getAttribute('data-target');
    let footnote = document.querySelector('[data-id=' + target + ']');
    let footnoteContent = footnote.innerHTML;
    return footnoteContent;
  },

  popover : {
    container: null,
    el: null,
    trigger: null,
    showTimeout: null,
    destroyTimeout: null,

    init(e) {
      FOOTNOTE.popover.disableTriggers();
      FOOTNOTE.popover.trigger = e.target;

      if ( ! FOOTNOTE.popover.container ) {
        FOOTNOTE.popover.create(e);
      }
      clearTimeout(FOOTNOTE.popover.showTimeout);
      clearTimeout(FOOTNOTE.popover.destroyTimeout);

      FOOTNOTE.popover.activate();

      OVERLAY.activate('is-footnote-overlay');
      OVERLAY.activeContent.push(FOOTNOTE.popover.deactivate);
    },

    create(e) {
      let popoverContainer = document.createElement('div');
      popoverContainer.classList.add('popover-container');
      popoverContainer.classList.add('container');
      
      let popover = document.createElement('div');
      popover.classList.add('popover');
      popover.classList.add('is-footnote');
      popover.classList.add('footnotes__item');
  
      // let popoverContent = document.createElement('div');
      // popoverContent.classList.add('popover__content');
  
      // let pointer = document.createElement('div');
      // pointer.classList.add('popover__pointer');
  
      let content = FOOTNOTE.get(e);
  
      FOOTNOTE.popover.container = popoverContainer;
      FOOTNOTE.popover.el = popover;
  
      // popoverContent.innerHTML = content;
      // popoverContent.appendChild(pointer);
      // popover.appendChild(popoverContent);
      popover.innerHTML = content;
      popoverContainer.appendChild(popover);
  
      FOOTNOTE.popover.setPosition(e);
  
      return document.body.appendChild(popoverContainer);
    },

    activate() {
      FOOTNOTE.popover.showTimeout = setTimeout(function() {
        FOOTNOTE.popover.el.setAttribute('data-active', 'true');
      }, 50);
    },

    deactivate() {
      if ( ! FOOTNOTE.popover.container ) return;

      clearTimeout(FOOTNOTE.popover.showTimeout);
      clearTimeout(FOOTNOTE.popover.destroyTimeout);
  
      FOOTNOTE.popover.el.setAttribute('data-active', 'false');
      FOOTNOTE.popover.destroyTimeout = setTimeout(function() {
  
        jQuery(FOOTNOTE.popover.container).remove();
        FOOTNOTE.popover.container = null;
        FOOTNOTE.popover.enableTriggers();
        
      }, 300);
  
      
    },

    enableTriggers() {
      for ( let i = 0; i < FOOTNOTE.markers.length; i++ ) {
        FOOTNOTE.markers[i].setAttribute('data-disabled', 'false');
      }
    },

    disableTriggers() {
      for ( let i = 0; i < FOOTNOTE.markers.length; i++ ) {
        FOOTNOTE.markers[i].setAttribute('data-disabled', 'true');
      }
    },

    setPosition() {
      if ( ! FOOTNOTE.popover.container ) return;
      let triggerTop = jQuery(FOOTNOTE.popover.trigger).offset().top; //get the offset top of the element
      let x = triggerTop - jQuery(window).scrollTop();
      jQuery(FOOTNOTE.popover.container).css({"top": x });
    }

  }

  // openFootnotes() {
  //   // unless open, scroll to target won't work because footnotes by default are hidden
  //   // when footnote marker clicked, if footnotes are closed, then this opens them and thus allowing the scroll to work correctly
  //   // currently unused as using popovers now...
  //   let footnotes = document.querySelector('[data-footnotes]');
  //   if( footnotes.getAttribute('data-accordion') == 'closed' ) {
  //     jQuery('[data-accordion-toggle].footnotes-credits__toggle').click();
  //   }
  // },


}

jQuery(document).ready(function() {
  FOOTNOTE.init();
});

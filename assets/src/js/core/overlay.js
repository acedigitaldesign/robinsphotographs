/******************************************
JS - Overlay
******************************************/
const OVERLAY = {
  element: document.createElement('div'),
  selector: '[data-overlay]',
  active: false,
  activeContent: [],
  defaultClasses: 'overlay',
  removeCustomClassTimeout: null,

  initialize() {
    OVERLAY.element.setAttribute('class', OVERLAY.defaultClasses); 
    OVERLAY.element.setAttribute('data-overlay', 'inactive');
    document.body.appendChild(OVERLAY.element); // <- Append content overlay to DOM
    OVERLAY.element.addEventListener('click', OVERLAY.deactivate);
  },

  activate(customClass) {
    clearTimeout(OVERLAY.removeCustomClassTimeout);

    if ( customClass ) {
      OVERLAY.element.classList.add(customClass);
    }
    OVERLAY.status = true;
    OVERLAY.element.setAttribute('data-overlay', 'active');

  },

  deactivate() {
    for (var i = 0; i < OVERLAY.activeContent.length; i++) {
      OVERLAY.activeContent[i]();
    }
    OVERLAY.element.setAttribute('data-overlay', 'inactive');
    OVERLAY.activeContent = [];
    OVERLAY.status = false;

    OVERLAY.removeCustomClassTimeout = setTimeout(function() {
      OVERLAY.element.setAttribute('class', OVERLAY.defaultClasses); 
    }, 500);
  }
}

// jQuery(document).ready(function(){
  // Some other JS files utilise this but need access immediatley, otherwise don't work...
  // So took it out of ready function and all ok....
  OVERLAY.initialize();
// });

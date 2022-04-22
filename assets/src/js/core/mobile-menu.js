/******************************************
JS - Mobile Menu 02
// Side Panel
// Is an independant menu separate from menu on larger screens.
// Panel appears from the side of the viewport.
******************************************/
const MOBILE_MENU = {

  element: null,
  toggle: null,
  closeBtn: null,

  init() {
    if ( ! document.querySelector('[data-mobile-menu]') ) return; // terminates if no mobile-menu (ie. pages with simple headers)

    MOBILE_MENU.element = document.querySelector('[data-mobile-menu]');
    MOBILE_MENU.toggle = document.querySelector('[data-mobile-menu-toggle]');
    MOBILE_MENU.closeBtn = document.querySelector('[data-mobile-menu-close]');
    MOBILE_MENU.initializeListeners();
  },



  initializeListeners() {
    MOBILE_MENU.toggle.addEventListener('click', MOBILE_MENU.activate);
    MOBILE_MENU.closeBtn.addEventListener('click', MOBILE_MENU.deactivate);
  },

  activate() {
    OVERLAY.activate();
    OVERLAY.activeContent.push(MOBILE_MENU.deactivate);
    MOBILE_MENU.element.setAttribute('data-mobile-menu', 'active');
    MOBILE_MENU.closeBtn.addEventListener('click', MOBILE_MENU.deactivate);
  },

  deactivate() {
    OVERLAY.element.setAttribute('data-overlay', 'inactive');
    MOBILE_MENU.element.setAttribute('data-mobile-menu', 'inactive');
    MOBILE_MENU.closeBtn.removeEventListener('click', MOBILE_MENU.deactivate);
  }

}

jQuery(document).ready(function(){
  MOBILE_MENU.init();
});

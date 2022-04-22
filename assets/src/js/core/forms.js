const FORMS = {
  init() {
    FORMS.setCF7SelectsFirstOptionAsPlaceholder();
  },

  setCF7SelectsFirstOptionAsPlaceholder() {
    let selects = document.querySelectorAll('.wpcf7-select');
    if ( selects.length == 0 ) return; 
    for ( let i = 0; i < selects.length; i++ ) {
      let firstOption = selects[i].querySelector('option:first-of-type');
      firstOption.setAttribute('disabled', "");
    }
  }
}

jQuery(document).ready(function(){
  FORMS.init();
});


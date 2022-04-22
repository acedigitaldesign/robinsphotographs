/******************************************
JS - Admin - Assign Universal ID Context
- Determine post type
- and maps relevant context to universal ID prefix 
- Don't know how to do it in php
- and this way means don't have to have 6 ACF fields...
******************************************/

/* global adminScriptVars */ // <- silence codekit warning about can't find object. Defined in wp_localize_script in wp_enqueue_scripts_styles.php

const UNIVERSAL_ID = {

  init() {
    UNIVERSAL_ID.assign_input_prefix();
  },

  context_code_map: { 
    'post'        : 'Post',
    'page'        : 'Page'
    // 'inspiration' : 'INS',
    // 'product'     : 'PRD',
    // 'news'        : 'NWS',
    // 'page'        : 'PAG'
  }, 

  initialize_input_prefix() {
    var themePrefix = adminScriptVars.theme_prefix;
    var postType = admin_helpers.initialize_post_type();
    var inputPrefix = themePrefix + '-' + this.context_code_map[postType] + '-';
    return inputPrefix;
  }, 

  assign_input_prefix() {
    if(jQuery(".js-universal-id .acf-input-prepend").length) {
      
      var prefix = this.initialize_input_prefix();
      jQuery( ".js-universal-id .acf-input-prepend" ).html( prefix );
    }
  }
}

jQuery(document).ready(function() {
  UNIVERSAL_ID.init();
});

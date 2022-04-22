/******************************************
JS - Admin Helper Functions
******************************************/

const admin_helpers = {

  initialize_post_type() {

    var postType = null;
  
      // Look to see what type of post type we're working determined from body class list
      var attrs = jQuery( 'body' ).attr( 'class' ).split( ' ' );
      jQuery( attrs ).each(function() {
  
        if ( 'post-type-' === this.substr( 0, 10 ) ) {
  
          postType = this.split( 'post-type-' );
          postType = postType[ postType.length - 1 ];
  
        }
      });

    return postType;
  }
}

// jQuery(document).ready(function() {
//   admin_helpers.initialize_post_type();
// });

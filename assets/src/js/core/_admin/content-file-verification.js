const content_file_verification = {

  initializeListener() {
    var button = document.querySelector('.js-content-file-verification-btn');
    if(button) {
      button.addEventListener('click', this.submit );
    }

  },

  submit(event) {
  /* global ajax_object */

  // 1. Resets and pre checks
  event.preventDefault();
  jQuery('input').blur(); // <- Ensures the dismissal of any onscreen keyboards (to ensure fixed positioning of the alerts is not impaired on old devices...

  // 2. Initialize data
  var uid_input = document.querySelector('.js-universal-id input');
  var uid = UNIVERSAL_ID.initialize_input_prefix() + uid_input.value;
  var post_type = admin_helpers.initialize_post_type();

  // console.log(uid_input);
  // 3. Push all data into array
  var data = {
    action           :  'post_edit_content_verification',
    uid_input_value  :  uid_input.value,
    uid              :  uid,
    post_type        :  post_type,
    security         :  ajax_object.content_verification_security
  }
  // 4. Convert data into url params
  var dataSerialized = jQuery.param(data);

  // 5. Perform ajax post request...
  var message_container = document.querySelector('.js-content-status > .acf-input');

  jQuery.ajax({
    type: "POST",
    data: dataSerialized,
    url: ajax_object.ajax_url,

      beforeSend: function(){
        // message_container.classList.add('-is-loading'); // Prevents clicking on button again whilst loading
        message_container.setAttribute('data-loading', 'true'); // Prevents clicking on button again whilst loading
        SPINNER.init('.js-content-status'); // Add form loader overlay
        jQuery(message_container).fadeTo(150, 0.5);

      },
      // Complete actions (ie. code that fires regardless of whether ajax returns error or success)
      complete: function() {
        SPINNER.remove();
        jQuery(message_container).fadeTo(150, 1);
        setTimeout( function() {
          message_container.setAttribute('data-loading', 'false');
        }, 150); // Allows click events again, only after fading animation finished though...
      },

      // Failed ajax request...
      error: function (request, status) {
        let statusMessage = jQuery("[data-content-status-message]");

        if( status == 500 ){
          statusMessage.replaceWith(ajax_object.alert_error_500);
        } 
        else if( status == 'timeout' ){
          statusMessage.replaceWith(ajax_object.alert_error_timeout );
        } 
        else if( status == 409 ){
          statusMessage.replaceWith(ajax_object.alert_error_409 );
        } 
        else if( status == 403 ){
          statusMessage.replaceWith(ajax_object.alert_error_403 );
        } 
        else if( request.responseText == '-1' ) {
          statusMessage.replaceWith(ajax_object.alert_error_invalid_security);
        } 
        else {
          // process WordPress errors
          var wpErrorHtml = request.responseText.split("<p>"),
            wpErrorStr = wpErrorHtml[1].split("</p>");

            statusMessage.replaceWith(wpErrorStr[0]);
        }
      },

      // Successfull ajax request...
      success: function ( $response ) {
        let statusMessage = jQuery("[data-content-status-message]");
        statusMessage.replaceWith($response);
      }
      
    }); // <- END of ajax method

  } // <- END Submit method

} // <- End of content_file_verification object

jQuery(document).ready(function() {
  content_file_verification.initializeListener();
});
/******************************************
JS - Javascript Notice
 - if javascript enabled, removes the data-javascript-disabled from the html tag in the header
 - removing it makes the javascript-notice styles obsolete (ie. resumes scrolling)
******************************************/
jQuery(document).ready(function(){
  let el = document.querySelector('[data-javascript-disabled]');
  el.removeAttribute('data-javascript-disabled');
});
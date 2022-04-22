/******************************************
JS - Admin - Move Universal ID Required Asterisk
- Typically the asterisk of required ACF fields is on the field label
- However, showing the label for this field is redundant, design wise.
- Therefore this JS moves required field asterisk on to metabox title instead
- And then hides the required field label
******************************************/
const admin_acf_asterisk_mover = {

  selectors: [
    '#acf-group_5ee21f740f58e',
    '#acf-group_5f083bdc0278e'
  ],

  initialize_asterisk_html() {
    var asterisk = document.createElement("span");
    asterisk.classList.add('acf-required');
    asterisk.innerHTML = ' *';
    return asterisk;
  }, 

  initialize_asterisks_move() {
    for(var i = 0; i < this.selectors.length; i++) {
      var postbox = document.querySelector(this.selectors[i]);

      if(postbox) {
        var label = postbox.querySelector('.acf-label');
        // console.log(label);

        // Move asterisk from required field label to postbox title
        var heading = postbox.querySelector('h2');
        heading.classList.add('-is-required');
        heading.appendChild(this.initialize_asterisk_html());

        // Hide required field label
        var p = label.querySelectorAll('p');
        if(p.length > 0) {
          label.querySelector('label').style.display = 'none'; // <- If label contains instructions, only hide the label tag (so as to maintain instructions bottom margin)
        }
        else {
          label.style.display = 'none'; // <- If no instructions, hide everything
        }
      }
    }
  }
};

jQuery( document ).ready(function() {
  admin_acf_asterisk_mover.initialize_asterisks_move();
});

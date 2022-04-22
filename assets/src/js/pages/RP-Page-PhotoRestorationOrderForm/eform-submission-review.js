/******************************************
JS - eForm Submission Review
Manages calculations and updates for order summary (last page of submission form)
******************************************/
const EFORM_REVIEW = {
  panel: {
    id: "",
    el: ""
  },

  dataTargets: {
    base: 'data-',
    propPaths: [],
    tempStr: "" // <- used to temporarily construct prop strings before pushing to propPaths arr (needs to be outside funciton. Ideally it would be in function but im not currently smart enough to figure that out...)
  },

  data: {
    restoration: {
      label: "Photo Restoration",
      price: EFORM.getPriceHTML(EFORM.prices.restoration)
    },
    quantity: {
      label: "Photos Uploaded",
      value: "No"
    },
    colour: {
      label: "Colourise:",
      option: null,
      price: null
    },
    prints: {
      label: "Prints:",
      option: "No",
      price: null,
      sizes: {
        label: "Sizes:",
        value: null
      }
    },
    speed: {
      label: "Processing Speed:",
      option: "Standard",
      price: null
    },
    subtotal: {
      label: "Subtotal",
      price: null
    },
    total: {
      label: "Total",
      price: null
    }
  },

  nextPanel: {
    id: null,
    timeout: null
  },

  init() {
    EFORM_REVIEW.initPanelData();
    EFORM_REVIEW.constructTargetPropPaths(EFORM_REVIEW.data);
    EFORM_REVIEW.getNextPanel();
    EFORM_REVIEW.addListeners();
    EFORM_REVIEW.updateDataObj();
  },

  addListeners() {
    let previousBtn = document.querySelector('.ipt_fsqm_form_button_prev');
    let nextBtn = document.querySelector('.ipt_fsqm_form_button_next');

    previousBtn.addEventListener('click', EFORM_REVIEW.getNextPanel);
    nextBtn.addEventListener('click', EFORM_REVIEW.getNextPanel);
    nextBtn.addEventListener('click', EFORM_REVIEW.refreshSummaryTable); // only on next
  },

  initPanelData() {
    let panelID = EFORM.getPanelID('Review');
    EFORM_REVIEW.panel.id = panelID;
    EFORM_REVIEW.panel.el = document.getElementById(panelID);
  },

  getNextPanel() {
    // console.log(EFORM_REVIEW.nextPanel.id);
    let panels = document.querySelectorAll('.ipt_fsqm_form_tab_panel');
    let nextPanelID = null;

    EFORM_REVIEW.nextPanel.timeout = setTimeout(function() {

      for (let i = 0; i < panels.length; i++ ) {
        if(panels[i].getAttribute("aria-hidden") == "false") {
          if ( i + 1 < panels.length ) {
            // if there is a next panel, get next panel
            nextPanelID = panels[i+1].getAttribute('id'); 
          }
          else {
            // else if no more panels, set to null
            nextPanelID = null
          }
        
          EFORM_REVIEW.nextPanel.id = nextPanelID;
          // console.log(EFORM_REVIEW.nextPanel.id);

          return;
        } 
      }
    }, 80) // slight delay needed to ensure eform has updated to current tab
  },

  // Functions to gather all data
  getData: {
    colour: {
      colourOption() {
        let panel = document.getElementById(EFORM.getPanelID('Colour'));
        return EFORM_REVIEW.getOption(panel);
      },
      colourPrice() {
        let option = EFORM_REVIEW.getData.colour.colourOption();
        return EFORM.prices.colour[option]
      },
      colourPriceHTML() {
        let price = EFORM_REVIEW.getData.colour.colourPrice();
        let html = "";
        if ( price == 0 ) {
          html = "-";
        }
        else {
          html = EFORM.getPriceHTML(price);
        }
        return html;
      }
    },

    prints: {
      printsOption() {
        let panel = document.getElementById(EFORM.getPanelID('Prints'));
        return EFORM_REVIEW.getOption(panel);
      },
      sizeQty() {
        // Returns array of quantitys, in order respectively matching to size options
        let option = EFORM_REVIEW.getData.prints.printsOption();
        if ( option == "No") return false;

        let panel = document.getElementById(EFORM.getPanelID('Prints'));
        let prints = panel.querySelectorAll('.ipt_uif_sda_elem');
        let qty_arr = [];
        for ( let i = 0; i < prints.length; i++ ) {
          let quantity = prints[i].querySelectorAll('.check_me')[1];
          quantity = jQuery(quantity).val();
          qty_arr[i] = parseInt(quantity);
        }
        return qty_arr;
        
      },
      sizeOptions() {
        let option = EFORM_REVIEW.getData.prints.printsOption();
        if ( option == "No") return "None";

        let panel = document.getElementById(EFORM.getPanelID('Prints'));
        let prints = panel.querySelectorAll('.ipt_uif_sda_elem');
        let size_arr = [];
        if ( ! prints ) return size_arr;
        for ( let i = 0; i < prints.length; i++ ) {
          let size = prints[i].querySelectorAll('.check_me')[0];
          size = jQuery(size).val();
          let qty = EFORM_REVIEW.getData.prints.sizeQty()[i];
          size_arr[i] = size + ' (' + qty + ')';
        }
        return size_arr.join(", ");
      },
      totalPrintsQty() {
        let qtyArr = EFORM_REVIEW.getData.prints.sizeQty();
        if ( ! qtyArr ) return 0;

        let totalQty = TOOLBOX.sumArray(qtyArr);
        return totalQty;
      },
      printsPrice() {
        let option = EFORM_REVIEW.getData.prints.printsOption();
        let price = EFORM_REVIEW.getData.prints.totalPrintsQty() * EFORM.prices.prints[option];
        return price;
      },
      printsPriceHTML() {
        let price = EFORM_REVIEW.getData.prints.printsPrice();
        let html = "";
        if ( price == 0 ) {
          html = "-";
        }
        else {
          html = EFORM.getPriceHTML(price);
          let qualifierPrice = EFORM.getPriceHTML(EFORM.prices.prints["Yes"]);
          html += '<div class="qualifier">(' + qualifierPrice + ' per print)</div>';
        }
        return html;
      }

    },
    speed: {
      speedOption() {
        let panel = document.getElementById(EFORM.getPanelID('Speed'));
        let checkedOption = panel.querySelector('.check_me:checked');
        if ( ! checkedOption || checkedOption.getAttribute('value') == 0 ) return "Standard";
        return "Priority";
      },
      speedPrice() {
        let option = EFORM_REVIEW.getData.speed.speedOption();
        return EFORM.prices.speed[option]
      },
      speedPriceHTML() {
        let price = EFORM_REVIEW.getData.speed.speedPrice();
        let html = "";
        if ( price == 0 ) {
          html = "-";
        }
        else {
          html = EFORM.getPriceHTML(price);
        }
        return html;
      }
    },

    subtotal: {
      subtotalPrice() {
        let itemPrices = [
          EFORM.prices.restoration,
          EFORM_REVIEW.getData.colour.colourPrice(),
          EFORM_REVIEW.getData.prints.printsPrice(),
          EFORM_REVIEW.getData.speed.speedPrice()
        ];
        // Adds up all values in array 
        // (prices are in an array simply to make easier to read...)
        let subtotalPrice = TOOLBOX.sumArray(itemPrices);
        return subtotalPrice;   
      },
      subtotalPriceHTML() {
        let subtotal = EFORM_REVIEW.getData.subtotal.subtotalPrice();
        return EFORM.getPriceHTML(subtotal);
      }
    },

    total: {
      totalPrice() {
        let totalPrice = EFORM_REVIEW.getData.subtotal.subtotalPrice() * EFORM_REVIEW.data.quantity.value;
        return totalPrice;
      },
      totalPriceHTML() {
        let total = EFORM_REVIEW.getData.total.totalPrice();
        return EFORM.getPriceHTML(total);
      }
    }  
  },

  getOption(panel) {
    let checkedOption = panel.querySelector('.check_me:checked');
    if ( ! checkedOption || checkedOption.getAttribute('value') == 0 ) return "No";
    return "Yes";
    // if no checked option or option = 0 return "No", otherwise "Yes"
  },

  constructTargetPropPaths(obj) {
    // Iterates over data object and constructs data attribute keys from properties
    // ie. the property "label" in the nested "colour" obj would have a data attr of "data-colour-label"
    // These data attributes are used in submission-review table as data injection targets

    for (const prop in obj) {
      if (typeof obj[prop] === 'object' && obj[prop] !== null) {
        EFORM_REVIEW.dataTargets.tempStr += prop + '-';
        EFORM_REVIEW.constructTargetPropPaths(obj[prop]);
      }
      else {
        let str = EFORM_REVIEW.dataTargets.tempStr + prop;
        EFORM_REVIEW.dataTargets.propPaths.push(str);
      }
    }
    EFORM_REVIEW.dataTargets.tempStr = "";
  },

  fetchFromObject(obj, prop) {
    // property not found
    if(typeof obj === 'undefined') return false;
    
    // index of next property split
    var _index = prop.indexOf('-')

    // property split found; recursive call
    if(_index > -1){
        // get object at property (before split), pass on remainder
        return EFORM_REVIEW.fetchFromObject(obj[prop.substring(0, _index)], prop.substr(_index+1));
    }
    
    // no split; get property
    return obj[prop];
  },

  updateDataObj() {
    // Quantity
    EFORM_REVIEW.data.quantity.value = EFORM.getQuantity();
    // Colour
    EFORM_REVIEW.data.colour.option = EFORM_REVIEW.getData.colour.colourOption();
    EFORM_REVIEW.data.colour.price = EFORM_REVIEW.getData.colour.colourPriceHTML();
    // Prints & Sizes
    EFORM_REVIEW.data.prints.option = EFORM_REVIEW.getData.prints.printsOption();
    EFORM_REVIEW.data.prints.price = EFORM_REVIEW.getData.prints.printsPriceHTML();
    EFORM_REVIEW.data.prints.sizes.value = EFORM_REVIEW.getData.prints.sizeOptions();
    EFORM_REVIEW.data.prints.sizes.quantity = EFORM_REVIEW.getData.prints.totalPrintsQty();
    // Speed
    EFORM_REVIEW.data.speed.option = EFORM_REVIEW.getData.speed.speedOption();
    EFORM_REVIEW.data.speed.price = EFORM_REVIEW.getData.speed.speedPriceHTML();
    // Subtotal
    EFORM_REVIEW.data.subtotal.price = EFORM_REVIEW.getData.subtotal.subtotalPriceHTML();
    // Total
    EFORM_REVIEW.data.total.price = EFORM_REVIEW.getData.total.totalPriceHTML();
  },

  refreshSummaryTable() {
    // Dont init unless next tab contains the Review summary 
    if ( EFORM_REVIEW.panel.id != EFORM_REVIEW.nextPanel.id ) return;

    EFORM_REVIEW.updateDataObj();

    // Add content
    for ( let i = 0; i < EFORM_REVIEW.dataTargets.propPaths.length; i++ ) {

      // construct data selector eg. [data-colour-label] etc
      let prop = EFORM_REVIEW.dataTargets.propPaths[i];
      let selector = '[' + EFORM_REVIEW.dataTargets.base + prop + ']';

      let el = EFORM_REVIEW.panel.el.querySelector(selector);
      let data = EFORM_REVIEW.fetchFromObject(EFORM_REVIEW.data, prop);
      // console.log(selector);
      // console.log(el);
      if ( el ) {
        el.innerHTML = data;
      }
    }

    // show/hide sizing option
    if ( EFORM_REVIEW.data.prints.option == "No" ) {
      jQuery('[data-sizes]').hide();
    }
    else {
      jQuery('[data-sizes]').show();
    }
  }

}


jQuery(document).ready(function(){
  EFORM_REVIEW.init();
});
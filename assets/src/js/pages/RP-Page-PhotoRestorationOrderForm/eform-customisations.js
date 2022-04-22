/******************************************
JS - eForm Customisations
******************************************/
const EFORM = {
  prices: {
    restoration: 29,
    colour: {
      "Yes" : 15,
      "No" : 0
    },
    prints: {
      "Yes" : 9,
      "No" : 0
    },
    speed: {
      "Standard" : 0,
      "Priority" : 15
    }
  },

  init() {
    EFORM.addListeners();
    EFORM.amendOptionLabels();
    EFORM.localisePrices();
    EFORM.amendSubmitButtonCTA();
    // EFORM.initQuantityInputFocus();
    // EFORM.disableSubmitButton();
  },

  // Add listeners
  addListeners() {
    // file upload button
    let uploadButton = document.querySelector('.fileinput-button');
    uploadButton.addEventListener('click', EFORM.assignColSpan100ToUploadButton);
    uploadButton.addEventListener('click', EFORM.monitorImageUploadProgress);
    uploadButton.addEventListener('click', EFORM.hideUploadErrorMsg);

    // Next / Previous buttons
    let nextPrevBtns = document.querySelectorAll('#ipt_fsqm_form_6_button_container .primary-button');
    for ( let i = 0; i < nextPrevBtns.length; i++ ) {
      // nextPrevBtns[i].addEventListener('click', EFORM.localiseTotalPricePerPhoto);
      nextPrevBtns[i].addEventListener('click', EFORM.revealSubmitButton);
      // nextPrevBtns[i].addEventListener('click', EFORM.disableSubmitButton);
    }

    // main option buttons (eg, colorise / no colorise)
    let extraOptions = document.querySelectorAll('.eform-label-with-tabindex');
    for ( let i = 0; i < extraOptions.length; i++ ) {
      // extraOptions[i].addEventListener('click', EFORM.localiseTotalPricePerPhoto);
    }
    // // final panel options
    // let finalPanel = EFORM.getFinalPanel();
    // let finalPanelOptions = finalPanel.querySelectorAll('input[type=radio]');
    // for ( let i = 0; i < finalPanelOptions.length; i++ ) {
    //   finalPanelOptions[i].addEventListener('change', EFORM.enableSubmitButton);
    // }
  },

  monitorImageUploadProgress() {

    // Disable buttons on ajax start (ie. when upload initiated)
    jQuery(document).ajaxStart(function() {
      let navButtons = document.querySelectorAll("#ipt_fsqm_form_6_button_container .ipt_uif_button");
      for (let i = 0; i < navButtons.length; i++) {
        navButtons[i].classList.add("ui-button-disabled");
        navButtons[i].classList.add("ui-state-disabled"); // setting individually to cater to IE11...
        navButtons[i].setAttribute("disabled", "");
      }
    });

    // Enable buttons on ajax finished (ie. when upload finished)
    jQuery(document).ajaxStop(function() {
      let navButtons = document.querySelectorAll("#ipt_fsqm_form_6_button_container .ipt_uif_button");
      for (let i = 0; i < navButtons.length; i++) {
        navButtons[i].classList.remove("ui-button-disabled");
        navButtons[i].classList.remove("ui-state-disabled"); // setting individually to cater to IE11...
        navButtons[i].removeAttribute("disabled");

      }
      EFORM.updateQuantity();
    });
  },

  updateQuantity() {
    let quantity = EFORM.getQuantity();
    let quantityInput = document.querySelector("#ipt_fsqm_form_6_freetype_9_value");
    quantityInput.value = quantity;
  },

  getQuantity() {
    let quantityInput = document.querySelector("#ipt_fsqm_form_6_freetype_9_value");
    let uploadQuantity = document.querySelectorAll(".ipt_fsqm_fileuploader_list .files tr").length;
    let maxQuantity = parseInt(quantityInput.getAttribute("max"));
    let totalQuantity = "";
    
    // If batch uploaded more than max quantity, sets to max quantity instead of quantity selected
    if ( uploadQuantity <= maxQuantity ) {
      totalQuantity = uploadQuantity;
    }
    else if ( uploadQuantity > maxQuantity ) {
      totalQuantity = maxQuantity;
    }

    return totalQuantity;
  },

  assignColSpan100ToUploadButton() {
    jQuery(".ipt_fsqm_fileuploader_list thead > tr > td").attr('colspan',100);
  },

  amendOptionLabels() {
    let labels = {
      "Colour" : {
        "panelID" : EFORM.getPanelID("Colour"),
        "option1" : 'No, restore only',
        "option2" : 'Yes, restore and colourise' + '<div class="eform__price-qualifier">(+' + EFORM.getPriceHTML(EFORM.prices.colour["Yes"]) + ' per photo)</div><div class="eform__popular-flag">Popular</div>'
      },
      "Prints" : {
        "panelID" : EFORM.getPanelID("Prints"),
        "option1" : 'No, digital only',
        "option2" : 'Yes, digital and prints' + '<div class="eform__price-qualifier">(+' + EFORM.getPriceHTML(EFORM.prices.prints["Yes"]) + ' per print)</div>'
      },
      "Sizes" : {
        "panelID" : EFORM.getPanelID("Prints"),
        "label" : 'Please select your desired photo print sizes:'
      },
      "Speed" : {
        "panelID" : EFORM.getPanelID("Speed"),
        "option1" : 'Standard: 7-10 days',
        "option2" : 'Priority: 3-5 days' + '<div class="eform__price-qualifier">(+' + EFORM.getPriceHTML(EFORM.prices.speed["Priority"]) + ' per photo)</div><div class="eform__popular-flag">Popular</div>'
      }
    }

    for (const prop in labels)  {
      let panel = document.getElementById(labels[prop]["panelID"]);

      if(prop == "Sizes") {
        let oldLabel = panel.querySelector(".ipt-eform-repeatable-container .ipt_uif_container_label");
        jQuery(oldLabel).html(labels[prop]["label"]);
      }
      else {
        let oldLabels = panel.querySelectorAll(".ipt_uif_thumbselect_wrap .ui-widget-header");
        jQuery(oldLabels[0]).html(labels[prop]["option1"]);
        jQuery(oldLabels[1]).html(labels[prop]["option2"]);
      }

    }
    
  }, // end amendOptionLabels

  // Get panel id from a particular panel heading (specified in labels var)
  getPanelID(panel) {
    let panels = document.querySelectorAll('.ipt_fsqm_form_tab_panel');
    let panelID = "";
    for (let i = 0; i < panels.length; i++) {
      let el = panels[i].querySelector(".ipt_fsqm_main_heading");
      let heading = el.innerText.replace(/\s/g, "");
      if(heading == panel) {
        panelID = panels[i].getAttribute("id");
        return panelID;
      }
    }
  },

  getPriceHTML(price) {
    let priceInt = parseFloat(price);
    let html = '<span class="eform__currency-symbol" data-currency-symbol>£</span>';
    html += '<span class="eform__price" data-currency-total>' + priceInt + '</span>';
    return html;
  },

  localisePrices() {
    let userCurrency = TOOLBOX.getCookie('wmc_current_currency');

    if ( userCurrency == "GBP" ) return; // No need to do any localisation

    let currencySymbols = document.querySelectorAll('[data-currency-symbol]');
    let prices = document.querySelectorAll('[data-currency-total]');

    for ( const currency in window.exchange_rates )  {
      if ( currency == userCurrency ) {
        for ( let i = 0; i < currencySymbols.length; i++ ) {
          currencySymbols[i].innerHTML = window.exchange_rates[currency]['currencySymbol'];
        }
        for ( let i = 0; i < prices.length; i++ ) {
          let defaultPrice = parseFloat(prices[i].innerHTML);
          let exchangedPrice = defaultPrice * window.exchange_rates[currency]['rate'];
          prices[i].innerHTML = (Math.round((exchangedPrice + Number.EPSILON) * 100) / 100).toFixed(2); // rounded to exactly 2 decimals
        }
      }

    } // end for
  }, // end localisePrices

  localiseTotalPricePerPhoto() {
    let userCurrency = TOOLBOX.getCookie('wmc_current_currency');

    if ( userCurrency == "GBP" ) return; // No need to do any localisation

    let panel = document.getElementById(EFORM.getPanelID('Review'));
    let mathPrice = panel.querySelector('.ipt_uif_mathematical_input');
    let mathPriceDisplay = document.querySelector('.ipt_uif_mathematical_span');
    
      for ( const currency in window.exchange_rates )  {
        if ( currency == userCurrency ) {
            setTimeout(function() {
              let defaultPrice = parseFloat(mathPrice.getAttribute("value"));
              let exchangedPrice = defaultPrice * window.exchange_rates[currency]['rate'];
              mathPriceDisplay.innerHTML = (Math.round((exchangedPrice + Number.EPSILON) * 100) / 100).toFixed(2); // rounded to exactly 2 decimals
          }, 10);
        }
  
      } // end for
  }, // end localiseTotalPricePerPhoto

  amendSubmitButtonCTA() {
    let submitBtn = document.querySelector('.ipt_fsqm_form_button_submit');
    // Change text
    submitBtn.textContent = "Add to Cart";
    // Add icon (classes added in separate lines for old browser support)
    submitBtn.classList.add("icon-shopping-cart-empty");
    submitBtn.classList.add("icon-nudge-top-n2");
  },

  revealSubmitButton() {
    let finalPanel = EFORM.getFinalPanel();
    let nextBtn = document.querySelector('.ipt_fsqm_form_button_next'); 
    let submitBtn = document.querySelector('.ipt_fsqm_form_button_submit');

    // // Initial values
    // nextBtn.setAttribute("style", "display: block;");
    // submitBtn.setAttribute("style", "display: none;");

    setTimeout(function() {
      if(finalPanel.getAttribute("aria-hidden") == "false") {
        nextBtn.setAttribute("style", "display: none;");
        submitBtn.setAttribute("style", "display: block;");
      } 
      else {
        nextBtn.setAttribute("style", "display: block;");
        submitBtn.setAttribute("style", "display: none;");
      }
    }, 30);

  },

  initQuantityInputFocus() {
    let quantityInput = document.querySelector("#ipt_fsqm_form_6_mcq_9 input");

    let i = 0;
    let intervalFunc = setInterval(function() {
      
      quantityInput.focus();
      quantityInput.setAttribute("data-focus-visible-added", "");
      quantityInput.classList.add("focus-visible");

      if (i++ == 10) {
        window.clearInterval(intervalFunc);
      }
    }, 500); // don't know why it needs repeating but doing it just once doesn;t always work; won't always focus but doing it a few times seems to work...
  },

  // disableSubmitButton() {
  //   let submit = document.getElementById("ipt_fsqm_form_6_button_submit");
  //   submit.classList.add("ui-button-disabled");
  //   submit.classList.add("ui-state-disabled"); // setting individually to cater to IE11...
  //   submit.setAttribute("disabled");
  // },

  // enableSubmitButton(e) {
  //   if(e.target.checked) {
  //     let submit = document.getElementById("ipt_fsqm_form_6_button_submit");
  //     submit.classList.remove("ui-button-disabled");
  //     submit.classList.remove("ui-state-disabled"); // setting individually to cater to IE11...
  //     submit.removeAttribute("disabled");
  //   }
  // },
  
  // Upload error message persists, regardless of whether mistake is corrected
  // This dismisses the error when upload button clicked
  hideUploadErrorMsg() {
    jQuery(".ipt_fsqm_file_upload_6_6__uploader_wrapformError").click();
  },

  getFinalPanel() {
    let panels = document.querySelectorAll('.ipt_fsqm_form_tab_panel');
    let finalPanel = panels[panels.length- 1];
    return finalPanel;
  }
}

jQuery(document).ready(function(){
  EFORM.init();
});
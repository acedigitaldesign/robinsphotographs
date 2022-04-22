/******************************************
JS - eForm Progress Bar
******************************************/
const EF_PROGRESS_BAR = {
  init() {
    let ef_progress_bar = document.querySelector('[data-eform-progress-bar]');
    let panels = document.querySelectorAll('.ipt_fsqm_form_tab_panel');
    let headings = EF_PROGRESS_BAR.getSectionHeadings(panels);
    let steps = [];

    EF_PROGRESS_BAR.addListeners();
    
    // Add each step to steps array
    for (let i = 0; i < panels.length; i++) {
      steps.push(EF_PROGRESS_BAR.buildStep(i, headings[i], panels.length));
    }
    // Append each step to progress bar
    for (let i = 0; i < steps.length; i++) {
      ef_progress_bar.appendChild(steps[i]);
    }
  },

  buildStep(stepNumber, heading, length) {
    const elem = document.createElement('div');
    const number = document.createElement('div');
    const label = document.createElement('div');
    const line = document.createElement('div');

    // container setup
    elem.setAttribute('class', 'eform-progress-bar__step');
    elem.setAttribute('data-step', stepNumber);
    if(stepNumber == 0) {
      elem.setAttribute('data-active', "true");
    } 
    else {
      elem.setAttribute('data-active', "false");
    }

    // assign step number and label
    number.setAttribute('class', 'eform-progress-bar__number');
    number.textContent = stepNumber + 1;
    label.setAttribute('class', 'eform-progress-bar__label');
    label.textContent = heading;

    // Add line
    line.setAttribute('class', 'eform-progress-bar__line');

    // append number and label in to container
    elem.appendChild(number);
    elem.appendChild(label);
    if(stepNumber != length-1) {
      elem.appendChild(line); // adds dotted line to all but last step
    }

    return elem;
  },

  // Add listeners to next and previous buttons
  addListeners() {
    document.querySelector('#ipt_fsqm_form_6_button_prev').addEventListener('click', EF_PROGRESS_BAR.updateProgressBar);
    document.querySelector('#ipt_fsqm_form_6_button_next').addEventListener('click', EF_PROGRESS_BAR.updateProgressBar);
  },

  // gets the new active panel
  getActivePanel() {
      let activePanel = document.querySelector('.ipt_fsqm_main_tab [aria-hidden="false"]');
      let id = activePanel.getAttribute('id');
      let position = id.lastIndexOf('_') + 1;
      let panelNumber = parseInt(id.slice(position));
    return panelNumber;
  },

  updateProgressBar() {
    // Needs a fraction second delay to register the NEW active panel, not the panel which was active when clicked.
    setTimeout(function() {
      let activePanel = EF_PROGRESS_BAR.getActivePanel();
      let steps = document.querySelectorAll('.eform-progress-bar__step');
      // cycles through each progress step, compares it against active panel, setting data attribute true or false as appropriate
      for (let i = 0; i < steps.length; i++) {
        if(steps[i].getAttribute('data-step') == activePanel) {
          steps[i].setAttribute('data-active', 'true')
        }
        else {
          steps[i].setAttribute('data-active', 'false')
        }
      }
    }, 10); 
  },

  // Gets heading text of each panel. Ultimately used for progress bar labels
  getSectionHeadings(panels) {
    let headings = [];
    for (let i = 0; i < panels.length; i++) {
      let el = panels[i].querySelector(".ipt_fsqm_main_heading");
      let heading = el.innerText;
      headings.push(heading);
    }
    return headings;
  }
  
}

jQuery(document).ready(function(){
  EF_PROGRESS_BAR.init();
});
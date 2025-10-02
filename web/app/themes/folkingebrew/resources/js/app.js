import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

import flatpickr from "flatpickr";
import header from "./header.js";
import slider from "./slider.js";
import openingHours from "./opening-hours.js";
import forms from "./forms.js";

const initFlatpickr = () => {
  const dateInputs = document.querySelectorAll('.flatpickr-input');

  dateInputs.forEach((input) => {
    const config = {
      dateFormat: "Y-m-d",
      allowInput: true,
      altInput: true,
      altFormat: "d-m-Y",
      placeholder: "Select a date...",
    };

    // Check for no past dates setting
    if (input.dataset.noPastDates === 'true') {
      config.minDate = "today";
    }

    flatpickr(input, config);
  });
};

const init = async (err) => {
  if (err) {
    console.error(err);
  }

  header();
  openingHours();
  slider();
  forms();
  initFlatpickr();
};

init();

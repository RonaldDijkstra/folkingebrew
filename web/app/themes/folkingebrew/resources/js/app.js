import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

import header from "./header.js";
import slider from "./slider.js";
import openingHours from "./opening-hours.js";

const init = async (err) => {
  if (err) {
    console.error(err);
  }

  header();
  openingHours();
  slider();
};

init();

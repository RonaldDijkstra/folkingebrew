import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

import header from "./header.js";
import slider from "./slider.js";

const init = async (err) => {
  if (err) {
    console.error(err);
  }

  header();
  slider();
};

init();

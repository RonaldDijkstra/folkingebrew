import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

import header from "./header.js";

const init = async (err) => {
  if (err) {
    console.error(err);
  }

  await header();
};

init();

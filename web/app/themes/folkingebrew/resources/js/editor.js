import domReady from '@wordpress/dom-ready';

import openingHours from "./opening-hours.js";
import slider from "./slider.js";

domReady(() => {
  // Array of initialization functions to run when content is loaded/changed
  const initFunctions = [
    openingHours,
    slider,
    // Add more functions here as needed
    // exampleFunction,
  ];

  // Function to run all initialization functions
  const runInitFunctions = () => {
    initFunctions.forEach(fn => fn());
  };

  // Run immediately for any existing content
  runInitFunctions();

  // Watch for dynamically added content in the block editor
  const observer = new MutationObserver(() => {
    runInitFunctions();
  });

  // Observe the editor canvas for changes
  const editorCanvas = document.querySelector('.editor-styles-wrapper, .block-editor-writing-flow');

  if (editorCanvas) {
    observer.observe(editorCanvas, {
      childList: true,
      subtree: true
    });
  } else {
    // Fallback: observe entire body if we can't find the editor
    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  }
});

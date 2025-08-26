import domReady from '@wordpress/dom-ready';
// import { registerBlockStyle, unregisterBlockStyle } from '@wordpress/blocks';

/**
 * Function to process elements with data-type starting with "acf"
 */
const processAcfElements = () => {
  const acfElements = document.querySelectorAll('[data-type^="acf"]');

  acfElements.forEach((element) => {
    const fieldsContainer = element.querySelector(
      '.acf-block-fields.acf-fields',
    );

    if (fieldsContainer && !fieldsContainer.querySelector('.title-element')) {
      const title = element.getAttribute('data-title');

      if (title) {
        const titleElement = document.createElement('h3');
        titleElement.textContent = title;
        titleElement.classList.add('title-element');

        fieldsContainer.insertBefore(titleElement, fieldsContainer.firstChild);
      }
    }
  });
};

/**
 * Debounce function to limit the rate at which the process function is called
 */
const debounce = (func, delay) => {
  let timeout;
  return (...args) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), delay);
  };
};

domReady(() => {
  processAcfElements();

  console.log('hello');

  const observer = new MutationObserver(
    debounce((mutationsList) => {
      for (const mutation of mutationsList) {
        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
          processAcfElements();
        }
      }
    }, 200),
  );

  observer.observe(document.body, {
    childList: true,
    subtree: true,
  });
});


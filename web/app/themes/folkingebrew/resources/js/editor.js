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

    if (fieldsContainer) {
      // Try to get custom block name first, fallback to data-title
      let title = null;

      // Check if we're in the block editor and can access the block data
      if (typeof wp !== 'undefined' && wp.data && wp.data.select('core/block-editor')) {
        const blockId = element.getAttribute('data-block');
        if (blockId) {
          const block = wp.data.select('core/block-editor').getBlock(blockId);
          if (block && block.attributes && block.attributes.metadata && block.attributes.metadata.name) {
            title = block.attributes.metadata.name;
          }
        }
      }

      // Fallback to data-title if no custom name is set
      if (!title) {
        title = element.getAttribute('data-title');
      }

      if (title) {
        let titleElement = fieldsContainer.querySelector('.title-element');

        if (!titleElement) {
          // Create new title element if it doesn't exist
          titleElement = document.createElement('h3');
          titleElement.classList.add('title-element');
          fieldsContainer.insertBefore(titleElement, fieldsContainer.firstChild);
        }

        // Update the title text
        titleElement.textContent = title;
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

  // Listen for block editor changes to update titles immediately
  if (typeof wp !== 'undefined' && wp.data) {
    let previousBlocks = [];

    const updateTitlesOnChange = () => {
      if (wp.data.select('core/block-editor')) {
        const currentBlocks = wp.data.select('core/block-editor').getBlocks();

        // Check if blocks have changed (including metadata changes)
        const blocksChanged = JSON.stringify(currentBlocks) !== JSON.stringify(previousBlocks);

        if (blocksChanged) {
          // Small delay to ensure DOM is updated
          setTimeout(() => {
            processAcfElements();
          }, 100);

          previousBlocks = JSON.parse(JSON.stringify(currentBlocks));
        }
      }
    };

    // Subscribe to store changes
    wp.data.subscribe(updateTitlesOnChange);

    // Also update when blocks are selected/deselected (covers rename scenarios)
    wp.data.subscribe(() => {
      updateTitlesOnChange();
    });
  }
});

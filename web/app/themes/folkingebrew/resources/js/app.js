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

const initQuantityButtons = () => {
  document.addEventListener('click', (e) => {
    if (e.target.classList.contains('qty-btn')) {
      const button = e.target;
      const quantityInput = button.parentElement.querySelector('input[type="number"]');

      if (!quantityInput) return;

      const currentValue = parseInt(quantityInput.value) || 1;
      const min = parseInt(quantityInput.min) || 1;
      const max = parseInt(quantityInput.max) || 999;

      if (button.classList.contains('minus')) {
        if (currentValue > min) {
          quantityInput.value = currentValue - 1;
        }
      } else if (button.classList.contains('plus')) {
        if (currentValue < max) {
          quantityInput.value = currentValue + 1;
        }
      }
    }
  });
};

const initProductGallery = () => {
  const galleryButtons = document.querySelectorAll('.product-gallery-thumb');
  const mainImage = document.querySelector('.product-main-image');

  if (!mainImage || galleryButtons.length === 0) return;

  galleryButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const thumbImg = button.querySelector('img');
      if (!thumbImg) return;

      // Get the full-size image URL from the thumbnail
      const fullSrc = thumbImg.dataset.fullSrc || thumbImg.src.replace('-150x150', '');
      const fullSrcset = thumbImg.dataset.fullSrcset || '';
      const alt = thumbImg.alt;

      // Update main image
      const mainImg = mainImage.querySelector('img');
      if (mainImg) {
        mainImg.src = fullSrc;
        if (fullSrcset) {
          mainImg.srcset = fullSrcset;
        }
        mainImg.alt = alt;
      }

      // Update active state
      galleryButtons.forEach(btn => btn.classList.remove('ring-1', 'ring-primary'));
      button.classList.add('ring-1', 'ring-primary');
    });
  });
};

const initProductVariations = () => {
  console.log('initProductVariations called');

  const productContainer = document.querySelector('[data-product-variations]');
  console.log('Product container:', productContainer);

  if (!productContainer) {
    console.log('No product container found');
    return;
  }

  const variationsData = productContainer.dataset.productVariations;
  console.log('Variations data (raw):', variationsData);

  if (!variationsData) {
    console.log('No variations data found');
    return;
  }

  let variations = [];
  try {
    variations = JSON.parse(variationsData);
  } catch (e) {
    console.error('Failed to parse variations data:', e);
    return;
  }

  console.log('Loaded variations:', variations);

  if (variations.length === 0) {
    console.log('No variations found');
    return;
  }

  const variationButtons = document.querySelectorAll('.variation-option');
  const variationIdInput = document.getElementById('variation_id');
  const priceDisplay = document.getElementById('product-price');
  const addToCartButton = document.querySelector('button[name="add-to-cart"]');
  const selectedAttributes = {};

  console.log('Variation buttons found:', variationButtons.length);

  // Handle variation option clicks
  variationButtons.forEach((button) => {
    button.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();

      // Don't allow clicking disabled buttons
      if (button.disabled) {
        console.log('Button is disabled, ignoring click');
        return;
      }

      const attribute = button.dataset.attribute;
      const value = button.dataset.value;

      console.log('Clicked:', attribute, '=', value);

      // Remove selection from siblings
      const siblings = document.querySelectorAll(`[data-attribute="${attribute}"]`);
      siblings.forEach(sibling => {
        sibling.classList.remove('selected', 'bg-black', 'text-white', 'border-black');
        sibling.classList.add('border-gray-300');
      });

      // Select this button
      button.classList.add('selected', 'bg-black', 'text-white', 'border-black');
      button.classList.remove('border-gray-300');
      selectedAttributes[attribute] = value;

      // Update hidden input
      const hiddenInput = document.querySelector(`input[name="${attribute}"]`);
      if (hiddenInput) {
        hiddenInput.value = value;
      }

      console.log('Selected attributes:', selectedAttributes);

      // Find matching variation
      updateVariation();
    });
  });

  function updateVariation() {
    console.log('Finding variation for:', selectedAttributes);

    // Find matching variation
    const matchingVariation = variations.find(variation => {
      console.log('Checking variation:', variation.attributes);

      // Check if all selected attributes match this variation
      const matches = Object.keys(selectedAttributes).every(attr => {
        const variationValue = variation.attributes[attr];
        const selectedValue = selectedAttributes[attr];
        console.log(`  ${attr}: variation="${variationValue}", selected="${selectedValue}"`);
        return variationValue === '' || variationValue === selectedValue;
      });

      return matches;
    });

    console.log('Matching variation:', matchingVariation);

    if (matchingVariation) {
      // Update variation ID
      if (variationIdInput) {
        variationIdInput.value = matchingVariation.variation_id;
      }

      // Update price
      if (priceDisplay && matchingVariation.price_html) {
        priceDisplay.innerHTML = matchingVariation.price_html;
      }

      // Enable/disable add to cart button based on stock and purchasability
      if (addToCartButton) {
        const canPurchase = matchingVariation.is_in_stock && matchingVariation.is_purchasable;
        console.log('Can purchase?', canPurchase, '(in_stock:', matchingVariation.is_in_stock, ', purchasable:', matchingVariation.is_purchasable, ')');

        if (canPurchase) {
          addToCartButton.disabled = false;
          addToCartButton.classList.remove('opacity-50', 'cursor-not-allowed');
          addToCartButton.textContent = addToCartButton.dataset.originalText || addToCartButton.textContent;
        } else {
          addToCartButton.disabled = true;
          addToCartButton.classList.add('opacity-50', 'cursor-not-allowed');
          if (!matchingVariation.is_in_stock) {
            if (!addToCartButton.dataset.originalText) {
              addToCartButton.dataset.originalText = addToCartButton.textContent;
            }
            addToCartButton.textContent = 'Out of stock';
          }
        }
      }

      // Update quantity field based on variation stock
      const qtyInput = document.getElementById('quantity');
      if (qtyInput && matchingVariation.max_qty > 0) {
        qtyInput.max = matchingVariation.max_qty;
      }
    } else {
      // No matching variation
      if (variationIdInput) {
        variationIdInput.value = '';
      }

      // Check if all attributes are selected
      const requiredAttributes = document.querySelectorAll('[data-attribute]');
      const uniqueAttributes = [...new Set(Array.from(requiredAttributes).map(el => el.dataset.attribute))];
      const allSelected = uniqueAttributes.every(attr => selectedAttributes[attr]);

      console.log('All selected?', allSelected);

      if (allSelected && addToCartButton) {
        addToCartButton.disabled = true;
        addToCartButton.classList.add('opacity-50', 'cursor-not-allowed');
      }
    }
  }

  // Disable add to cart button initially for variable products
  if (addToCartButton && variations.length > 0) {
    addToCartButton.disabled = true;
    addToCartButton.classList.add('opacity-50', 'cursor-not-allowed');
  }

  // Mark options where ALL variations are out of stock
  // Build a map of which attribute values have at least one in-stock variation
  const availableOptions = {};

  variations.forEach(variation => {
    const isAvailable = variation.is_in_stock && variation.is_purchasable;

    Object.entries(variation.attributes).forEach(([attr, value]) => {
      if (value) {
        if (!availableOptions[attr]) {
          availableOptions[attr] = {};
        }
        if (!availableOptions[attr][value]) {
          availableOptions[attr][value] = false;
        }
        // If this variation is available, mark this option as available
        if (isAvailable) {
          availableOptions[attr][value] = true;
        }
      }
    });
  });

  console.log('Available options:', availableOptions);

  // Now disable buttons for options that have no available variations
  Object.entries(availableOptions).forEach(([attr, values]) => {
    Object.entries(values).forEach(([value, isAvailable]) => {
      if (!isAvailable) {
        const button = document.querySelector(`.variation-option[data-attribute="${attr}"][data-value="${value}"]`);
        if (button) {
          button.classList.add('opacity-50', 'line-through');
          button.disabled = true;
          button.title = 'Out of stock';
          console.log(`Disabled button: ${attr} = ${value}`);
        }
      }
    });
  });
};

const initAddToCartViewCart = () => {
  // Track which products have already had view cart links added to prevent duplicates
  const processedProducts = new Set();

  // Helper function to add view cart link (used by all handlers - primarily for variable products)
  const addViewCartLink = (productId) => {
    // Check if already processed
    if (processedProducts.has(productId)) {
      return false;
    }

    // Mark as processed immediately to prevent duplicates
    processedProducts.add(productId);

    // Find the product actions wrapper
    const wrapper = document.querySelector(`.product-actions-wrapper-${productId}`);
    if (!wrapper) return false;

    // Check if link already exists
    const existingLink = wrapper.querySelector('a[href*="cart"]');
    if (existingLink && existingLink.textContent.includes('View cart')) {
      return false;
    }

    // Reset the button text
    const addToCartButton = wrapper.querySelector('.add_to_cart_button, button[name="add-to-cart"]');
    if (addToCartButton) {
      const originalText = addToCartButton.getAttribute('data-original-text');
      if (originalText) {
        addToCartButton.textContent = originalText;
        addToCartButton.removeAttribute('data-original-text');
      }
    }

    // Get cart URL
    const cartUrl = window.wc_add_to_cart_params?.cart_url || '/cart/';

    // Create view cart link
    const viewCartLink = document.createElement('a');
    viewCartLink.href = cartUrl;
    viewCartLink.className = 'view-cart-link text-primary hover:text-primary/80 font-normal underline transition-colors duration-200';
    viewCartLink.textContent = window.wc_add_to_cart_params?.i18n_view_cart || 'View cart';

    // Add the link to the wrapper
    wrapper.appendChild(viewCartLink);

    return true;
  };

  // Handle beer variant form submissions via AJAX
  document.addEventListener('submit', async (e) => {
    if (e.target.classList.contains('beer-variant-cart-form')) {
      e.preventDefault();

      const form = e.target;
      const button = form.querySelector('button[type="submit"]');
      const productId = form.querySelector('[name="product_id"]')?.value;

      if (!button || !productId) return;

      // Disable button and show loading state
      const originalText = button.textContent;
      button.disabled = true;
      button.textContent = 'Adding...';

      try {
        // Get form data
        const formData = new FormData(form);

        // Submit via AJAX
        const response = await fetch(window.location.href, {
          method: 'POST',
          body: formData,
          credentials: 'same-origin'
        });

        if (response.ok) {
          // Reset button state
          button.disabled = false;
          button.textContent = originalText;

          // Trigger WooCommerce events for cart counter update
          // The added_to_cart event listener will handle adding the view cart link
          document.body.dispatchEvent(new CustomEvent('added_to_cart', {
            detail: { product_id: productId }
          }));

          // Trigger fragment refresh
          if (window.jQuery) {
            window.jQuery(document.body).trigger('wc_fragment_refresh');
          }
        } else {
          // Error
          button.disabled = false;
          button.textContent = originalText;
          alert('Failed to add product to cart. Please try again.');
        }
      } catch (error) {
        console.error('Error adding to cart:', error);
        button.disabled = false;
        button.textContent = originalText;
        alert('Failed to add product to cart. Please try again.');
      }
    }
  });

  // Add loading state to add to cart buttons
  document.addEventListener('click', (e) => {
    const button = e.target;
    if (button.classList.contains('ajax_add_to_cart') ||
        (button.getAttribute('name') === 'add-to-cart' && button.getAttribute('type') === 'submit')) {
      // Only set loading state if not already set
      if (!button.getAttribute('data-original-text')) {
        button.setAttribute('data-original-text', button.textContent);
        button.textContent = 'Adding...';
      }
    }
  });

  // Listen for WooCommerce's added_to_cart event
  document.body.addEventListener('added_to_cart', (event) => {
    const productId = event.detail?.productId || event.detail?.product_id;
    if (productId) {
      // Delay to let WooCommerce add its default link first
      setTimeout(() => {
        const wrapper = document.querySelector(`.product-actions-wrapper-${productId}`);
        if (!wrapper) return;

        // Reset the button text
        const addToCartButton = wrapper.querySelector('.add_to_cart_button, button[name="add-to-cart"]');
        if (addToCartButton) {
          const originalText = addToCartButton.getAttribute('data-original-text');
          if (originalText) {
            addToCartButton.textContent = originalText;
            addToCartButton.removeAttribute('data-original-text');
          }
        }

        // Check if WooCommerce already added its link (for simple products)
        const wcLink = wrapper.querySelector('.added_to_cart.wc-forward');
        if (wcLink) {
          // WooCommerce added the link, just style it
          wcLink.classList.remove('button', 'wc-forward');
          wcLink.classList.add('text-primary', 'hover:text-primary/80', 'font-normal', 'underline', 'transition-colors', 'duration-200');
          processedProducts.add(productId);
        } else {
          // No WooCommerce link, add our own (for variable products)
          addViewCartLink(productId);
        }
      }, 100);
    }
  });

  // Also handle jQuery trigger for older WooCommerce versions
  if (window.jQuery) {
    window.jQuery(document.body).on('added_to_cart', (event, fragments, cart_hash, $button) => {
      if ($button) {
        const productId = $button.data('product_id');
        if (productId) {
          setTimeout(() => {
            const wrapper = document.querySelector(`.product-actions-wrapper-${productId}`);
            if (!wrapper) return;

            // Reset the button text
            const addToCartButton = wrapper.querySelector('.add_to_cart_button, button[name="add-to-cart"]');
            if (addToCartButton) {
              const originalText = addToCartButton.getAttribute('data-original-text');
              if (originalText) {
                addToCartButton.textContent = originalText;
                addToCartButton.removeAttribute('data-original-text');
              }
            }

            // Check if WooCommerce already added its link
            const wcLink = wrapper.querySelector('.added_to_cart.wc-forward');
            if (wcLink) {
              // WooCommerce added the link, just style it
              wcLink.classList.remove('button', 'wc-forward');
              wcLink.classList.add('text-primary', 'hover:text-primary/80', 'font-normal', 'underline', 'transition-colors', 'duration-200');
              processedProducts.add(productId);
            } else {
              // No WooCommerce link, add our own
              addViewCartLink(productId);
            }
          }, 100);
        }
      }
    });
  }
};

const initCartCounter = () => {
  const el = document.querySelector('[data-cart-count]');
  if (!el) return;

  const setCount = (n) => {
    const num = Number(n) || 0;
    el.textContent = num;
    el.classList.toggle('hidden', num === 0);
  };

  // 1) WooCommerce Blocks: reactively subscribe to cart changes
  function initBlocksSubscription() {
    const wpData = window.wp && window.wp.data;
    const STORE = 'wc/store/cart';
    if (!wpData || !wpData.select || !wpData.select(STORE)) return false;

    let last = -1;
    const update = () => {
      try {
        const sel = wpData.select(STORE);
        if (sel.isResolving && sel.isResolving('getCart')) return; // wait until ready

        // Try multiple methods to get cart count
        let next = null;
        if (sel.getCartItemsCount) {
          next = sel.getCartItemsCount();
        } else if (sel.getCartData) {
          const cartData = sel.getCartData();
          next = cartData?.itemsCount || cartData?.items_count;
        }

        if (typeof next === 'number' && next !== last) {
          last = next;
          setCount(next);
        }
      } catch { /* noop */ }
    };

    update();                 // set initial
    const unsubscribe = wpData.subscribe(update); // react to changes

    // Also poll periodically when on cart page as extra fallback
    if (document.body.classList.contains('woocommerce-cart')) {
      setInterval(update, 1000);
    }

    return true;
  }

  // 2) Classic fragments (simple fallback for non-Blocks flows)
  function initClassicFragments() {
    document.body.addEventListener('wc_fragments_refreshed', () => {
      const frag = document.querySelector('span[data-cart-count]');
      if (frag) setCount(frag.textContent);
    });

    // Cart page specific events (vanilla)
    document.body.addEventListener('updated_cart_totals', () => {
      fetchOnceFromStoreAPI();
    });

    document.body.addEventListener('updated_wc_div', () => {
      fetchOnceFromStoreAPI();
    });

    document.body.addEventListener('removed_from_cart', () => {
      fetchOnceFromStoreAPI();
    });

    // jQuery events (if jQuery is available)
    if (window.jQuery) {
      window.jQuery(document.body).on('updated_cart_totals updated_wc_div removed_from_cart wc_fragment_refresh', () => {
        fetchOnceFromStoreAPI();
      });
    }
  }

  // 3) Last-ditch: read from Store API once and on Blocks' custom event
  async function fetchOnceFromStoreAPI() {
    try {
      const url = (window.wc?.wcSettings?.storeApi?.root) || '/?wc-store/v1/cart';
      const res = await fetch(url, { credentials: 'same-origin' });
      const data = await res.json();
      if ('items_count' in data) setCount(data.items_count);
    } catch { /* noop */ }
  }

  // WooCommerce Blocks custom events
  document.addEventListener('wc-blocks_added_to_cart', (e) => {
    const n = e?.detail?.cart?.items_count;
    if (typeof n === 'number') setCount(n);
  });

  document.addEventListener('wc-blocks_removed_from_cart', (e) => {
    const n = e?.detail?.cart?.items_count;
    if (typeof n === 'number') setCount(n);
  });

  // Generic cart update event for blocks
  document.addEventListener('wc-blocks_cart_update', () => {
    fetchOnceFromStoreAPI();
  });

  // MutationObserver fallback for cart block changes
  function watchCartBlock() {
    const cartBlock = document.querySelector('.wp-block-woocommerce-cart, .wc-block-cart');
    if (!cartBlock) return;

    let debounceTimer;
    const observer = new MutationObserver(() => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        fetchOnceFromStoreAPI();
      }, 300);
    });

    observer.observe(cartBlock, {
      childList: true,
      subtree: true,
      attributes: false
    });
  }

  // Boot
  const hasBlocks = initBlocksSubscription();
  initClassicFragments();
  if (!hasBlocks) fetchOnceFromStoreAPI();

  // Watch for cart block updates if we're on cart page
  if (document.body.classList.contains('woocommerce-cart')) {
    // Wait a bit for cart to render
    setTimeout(watchCartBlock, 500);
  }
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
  initQuantityButtons();
  initProductGallery();
  initProductVariations();
  initAddToCartViewCart();
  initCartCounter();
};

init();

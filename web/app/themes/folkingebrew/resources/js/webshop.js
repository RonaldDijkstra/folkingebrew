export default function webshop() {
  window.addEventListener('DOMContentLoaded', () => {
    initAddToCart();
  });
}

function initAddToCart() {
  const form = document.getElementById('add-to-cart-form');
  const button = document.getElementById('add-to-cart-button');
  const successMessage = document.getElementById('add-to-cart-message');
  const errorMessage = document.getElementById('add-to-cart-error');
  const sizeSelect = document.getElementById('product-size');

  if (!form || !button || !successMessage || !errorMessage) return;

  const originalButtonText = button.textContent;
  const productId = button.value;
  const isVariable = form.dataset.isVariable === 'true';

  // Handle size selection for variable products
  if (isVariable && sizeSelect) {
    const quantityInput = document.getElementById('quantity');
    const stockInfo = document.getElementById('stock-info');

    sizeSelect.addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];

      if (selectedOption.value && selectedOption.dataset.inStock === 'true') {
        button.disabled = false;

        // Update max quantity based on selected variation
        const maxQty = parseInt(selectedOption.dataset.maxQuantity) || 9999;
        if (quantityInput) {
          quantityInput.max = maxQty;

          // Reset quantity to 1 if current value exceeds max
          if (parseInt(quantityInput.value) > maxQty) {
            quantityInput.value = Math.min(1, maxQty);
          }
        }

        // Show stock information
        if (stockInfo) {
          if (maxQty < 10) {
            stockInfo.textContent = `Only ${maxQty} in stock`;
            stockInfo.classList.remove('hidden');
          } else {
            stockInfo.classList.add('hidden');
          }
        }
      } else {
        button.disabled = true;
        if (quantityInput) {
          quantityInput.value = 1;
        }
        if (stockInfo) {
          stockInfo.classList.add('hidden');
        }
      }
    });
  }

  form.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData();

    // For variable products, send variation as product
    if (isVariable && sizeSelect) {
      const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
      const variationId = selectedOption.dataset.variationId;

      if (!variationId || !selectedOption.value) {
        errorMessage.classList.remove('hidden');
        successMessage.classList.add('hidden');
        setTimeout(() => errorMessage.classList.add('hidden'), 5000);
        return;
      }

      // For variations, send variation_id as product_id
      formData.append('product_id', variationId);
      formData.append('variation_id', variationId);

      // Add the size attribute
      const attributeName = sizeSelect.name;
      formData.append(attributeName, selectedOption.value);
    } else {
      formData.append('product_id', productId);
    }

    formData.append('quantity', document.getElementById('quantity').value);

    button.disabled = true;
    button.textContent = 'Adding...';

    fetch('/?wc-ajax=add_to_cart', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.error || data.error_message) {
        // Show error message
        errorMessage.classList.remove('hidden');
        successMessage.classList.add('hidden');

        // Hide error after 8 seconds
        setTimeout(() => {
          errorMessage.classList.add('hidden');
        }, 8000);
      } else if (data.fragments || !data.error) {
        // Show success message
        successMessage.classList.remove('hidden');
        errorMessage.classList.add('hidden');
        document.getElementById('quantity').value = 1;

        // Trigger WooCommerce fragments refresh
        if (data.fragments) {
          Object.keys(data.fragments).forEach(key => {
            const element = document.querySelector(key);
            if (element) {
              element.innerHTML = data.fragments[key];
            }
          });
        }
        document.body.dispatchEvent(new Event('wc_fragment_refresh'));

        // Hide message after 10 seconds
        setTimeout(() => {
          successMessage.classList.add('hidden');
        }, 10000);
      }
    })
    .catch(error => {
      console.error('Error:', error);

      // Show error message
      errorMessage.classList.remove('hidden');
      successMessage.classList.add('hidden');

      setTimeout(() => {
        errorMessage.classList.add('hidden');
      }, 10000);
    })
    .finally(() => {
      button.disabled = false;
      button.textContent = originalButtonText;
    });
  });
}
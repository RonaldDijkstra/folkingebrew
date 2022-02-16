window.addEventListener('DOMContentLoaded', () => {
	if (document.querySelector('#quantity')) {
		const input = document.querySelector('#quantity');

	  document.querySelector('.quantity-minus').addEventListener('click', () => {
	    input.focus();
	    let count = parseInt(input.value, 10) - 1;
	    count = count < 1 ? 1 : count;
	    input.value = count;
	    setItemQuantity(count);
	  })

	  document.querySelector('.quantity-plus').addEventListener('click', () => {
	    input.focus();
	    let count = parseInt(input.value, 10) + 1;
	    input.value = count;
	    setItemQuantity(count);
	  })

	  input.addEventListener('change', () => {
	    const count = input.value;
	    setItemQuantity(count);
	  })
	};

	function setItemQuantity(count) {
  	document.querySelector('.snipcart-add-item').dataset.itemQuantity = count;
	}

  if (document.querySelector('#size')) {
    document.querySelector('#size').addEventListener('change', () => {
      const size = document.querySelector('#size').value;
      document.querySelector('.snipcart-add-item').dataset.itemCustom1Value = size;
    })
  };

  if (document.querySelector('.product-thumbnail')) {
	  document.querySelectorAll('.product-thumbnail').forEach(item => {
	    item.addEventListener('click', event => {
	      const productImage = document.querySelector('#product-image');
	      productImage.src = item.querySelector('img').src;

	      const activeItem = document.querySelector('.thumbnail-active');
	      activeItem.classList.remove('thumbnail-active');
	      item.classList.add('thumbnail-active');
	    })
	  })
	};

	if (document.querySelector('.toggle')) {
		document.querySelectorAll('.toggle').forEach(item => {
			item.addEventListener('click', event => {
				event.preventDefault;

				item.classList.toggle('active');
				const next = item.nextSibling.nextSibling;
				next.classList.toggle('hidden');
				// animate
			})
		})
	};
});

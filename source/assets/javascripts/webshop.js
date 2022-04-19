function setItemQuantity(count) {
  document.querySelector('.snipcart-add-item').dataset.itemQuantity = count;
}

window.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector('#quantity')) {
    const input = document.querySelector('#quantity');

    document.querySelector('#quantity-minus').addEventListener('click', () => {
      input.focus();
      let count = parseInt(input.value, 10) - 1;
      count = count < 1 ? 1 : count;
      input.value = count;
      setItemQuantity(count);
    });

    document.querySelector('#quantity-plus').addEventListener('click', () => {
      input.focus();
      const count = parseInt(input.value, 10) + 1;
      input.value = count;
      setItemQuantity(count);
    });

    input.addEventListener('change', () => {
      const count = input.value;
      setItemQuantity(count);
    });
  }

  if (document.querySelector('#size')) {
    document.querySelector('#size').addEventListener('change', () => {
      const size = document.querySelector('#size').value;
      document.querySelector('.snipcart-add-item').dataset.itemCustom1Value = size;
    });
  }

  if (document.querySelector('.product-thumbnail')) {
    document.querySelectorAll('.product-thumbnail').forEach((item) => {
      item.addEventListener('click', () => {
        const productImage = document.querySelector('#product-image');
        const imgSource = item.querySelector('img').src
        productImage.srcset = `${imgSource}?fm=webp&h=960&w=960&auto=enhance&fit=max&dpr=1 1x,
                               ${imgSource}?fm=webp&h=960&w=960&auto=enhance&fit=max&dpr=2 2x,
                               ${imgSource}?fm=webp&h=960&w=960&auto=enhance&fit=max&dpr=3 3x`;
        productImage.src = imgSource

        const activeItem = document.querySelector('.thumbnail-active');
        activeItem.classList.remove('thumbnail-active');
        item.classList.add('thumbnail-active');
      });
    });
  }
});

export default function webshop() {
  $(document).ready(() => {

    document.querySelector('.quantity-minus').addEventListener('click', () => {
      const input = document.querySelector('#quantity');
      input.focus();
      let count = parseInt(input.value, 10) - 1;
      count = count < 1 ? 1 : count;
      input.value = count;
      document.querySelector('.snipcart-add-item').dataset.itemQuantity = count;
    });

    document.querySelector('.quantity-plus').addEventListener('click', () => {
      const input = document.querySelector('#quantity');
      input.focus();
      let count = parseInt(input.value, 10) + 1;
      input.value = count;
      document.querySelector('.snipcart-add-item').dataset.itemQuantity = count;
    });

    document.querySelector('#quantity').addEventListener('change', () => {
      const count = document.querySelector('#quantity').value;
      document.querySelector('.snipcart-add-item').dataset.itemQuantity = count;
    });

    document.querySelector('#size').addEventListener('change', () => {
      const size = document.querySelector('#size').value;
      document.querySelector('.snipcart-add-item').dataset.itemCustom1Value = size;
    });

    document.querySelectorAll('.product-thumbnail').forEach(item => {
      item.addEventListener('click', event => {
        const productImage = document.querySelector('#product-image');
        productImage.src = item.querySelector('img').src;

        const activeItem = document.querySelector('.thumbnail-active');
        activeItem.classList.remove('thumbnail-active');
        item.classList.add('thumbnail-active');
      })
    })

    $('.toggle').click(function toggleProductDetails(e) {
      e.preventDefault();

      const $this = $(this);

      $this.toggleClass('active');
      $this.next().slideToggle(350);
    });
  });
}

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

    // $('.quantity-minus').click(function quantityMinus() {
    //   const $input = $(this).parent().find('input');
    //   // $input.focus();
    //   let count = parseInt($input.val(), 10) - 1;
    //   count = count < 1 ? 1 : count;
    //   $input.val(count);
    //   $input.change();
    //   return false;
    // });

    // $('.quantity-plus').click(function quantityPlus() {
    //   const $input = $(this).parent().find('input');
    //   $input.focus();
    //   const max = $input.attr('max');
    //   const currentValue = parseInt($input.val(), 10);

    //   if (max !== 'undefined' && currentValue < max) {
    //     $input.val();
    //   } else {
    //     $input.val(parseInt($input.val(), 10) + 1);
    //   }

    //   $input.change();
    //   return false;
    // });

    document.querySelector('#quantity').addEventListener('change', () => {
      const count = document.querySelector('#quantity').value;
      document.querySelector('.snipcart-add-item').dataset.itemQuantity = count;
    });

    // $('#quantity').change(function changeDataQuantity() {
    //   const $button = $(this).parents('.product-content').find('.snipcart-add-item');

    //   $button.attr('data-item-quantity', $(this).val());
    // });

    $('#size').change(function changeDataSize() {
      const $button = $(this).parents('.product-content').find('.snipcart-add-item');

      $button.attr('data-item-custom1-value', $(this).val());
    });

    $('.product-thumbnail').click(function activeProductImage() {
      $('#product-image').attr('src', $(this).attr('src'));

      $(document).find('.thumbnail-active').removeClass('thumbnail-active');
      $(this).parents('button').addClass('thumbnail-active');
    });

    $('.toggle').click(function toggleProductDetails(e) {
      e.preventDefault();

      const $this = $(this);

      $this.toggleClass('active');
      $this.next().slideToggle(350);
    });
  });
}

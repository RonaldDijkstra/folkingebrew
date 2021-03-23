// Update data item quantity with current number
$(document).ready(function() {
  $('input.product-quantity-input').change(function(){
    var $input = $(this).parents('.product-quantity').find('input.product-quantity-input');
    var $button = $(this).parents('.product-controls').find('.buy-button');

    console.log($button);

    $max = $input.attr('max');

    var count = parseFloat($input.val());

    $button.attr('data-item-quantity', count);

    return false;
  });
});

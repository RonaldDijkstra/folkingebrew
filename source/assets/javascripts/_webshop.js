// Update data item quantity with current number
$(document).ready(function() {
  $('#quantity').change(function(){
    var $button = $(this).parents('.product-controls').find('.buy-button');

    $button.attr('data-item-quantity', $(this).val());

    return false;
  });
});

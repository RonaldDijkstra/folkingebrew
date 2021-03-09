// Product +/-
$(document).ready(function() {
  $('button.quantity-adjust').click(function () {
    var $input = $(this).parents('.product-quantity').find('input.product-quantity-input');

    $max = $input.attr('max');

    var count = parseFloat($input.val());

    if($(this).hasClass('min')) {
      var min = count - 1;
      min = min < 1 ? 1 : min;
      $input.val(min);
    }
    else {
      var plus = count + 1;
      if (plus > $max) {
        plus = count;
        $input.val(plus);
      }
      else {
        $input.val(plus);
      }
    }

    $input.change();
    return false;
  });
});

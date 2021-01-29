/////////////////// product +/-
$(document).ready(function() {
  $('button.quantity-adjust').click(function () {
      var $input = $(this).parents('.product-quantity').find('input.product-quantity-input');
    if($(this).hasClass('min')) {
      var count = parseFloat($input.val()) - 1;
      count = count < 1 ? 1 : count;
      if (count < 2) {
        $(this).addClass('dis');
      }
      else {
        $(this).removeClass('dis');
      }
      $input.val(count);
    }
    else {
      var count = parseFloat($input.val()) + 1
      $input.val(count);
      if (count > 1) {
        $(this).parents('.num-block').find(('.minus')).removeClass('dis');
      }
    }

    $input.change();
    return false;
  });

  var x = document.getElementById("quantity").max;

  console.log(x)

});
// product +/-

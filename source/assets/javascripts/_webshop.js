// Product +/-
$(document).ready(function() {
  $('button.quantity-adjust').click(function () {
    var $input = $(this).parents('.product-quantity').find('input.product-quantity-input');
    var $button = $(this).parents('.product-controls').find('.buy-button');

    console.log($button);

    $max = $input.attr('max');

    var count = parseFloat($input.val());

    if($(this).hasClass('min')) {
      var min = count - 1;
      min = min < 1 ? 1 : min;
      $input.val(min);
      $button.attr('data-item-quantity', min);
    }
    else {
      var plus = count + 1;
      if (plus > $max) {
        plus = count;
        $input.val(plus);
        $button.attr('data-item-quantity', plus);
      }
      else {
        $input.val(plus);
        $button.attr('data-item-quantity', plus);
      }
    }

    $input.change();
    return false;
  });
});

// Change quantity
// window.addEventListener('DOMContentLoaded', function(event) {
//     document.addEventListener('change', function(evt){
//         console.log(evt.target.classList)
//         if(evt.target.classList.contains('qty')){
//             var button = evt.target.parentNode.querySelector('.buy-button')
//             var qty = parseInt(evt.target.value)
//             button.setAttribute('data-item-quantity', qty)
//             // var label = button.innerHTML
//             // label = label.replace(/\d+/, evt.target.value)
//             // if(qty > 1){
//             //     label = label.replace(/copy/, "copies")
//             // }
//             // else{
//             //     label = label.replace(/copies/, "copy")
//             // }
//             // button.innerHTML = label;
//         }
//     })
// });

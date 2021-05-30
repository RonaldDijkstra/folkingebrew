$(document).ready(function() {
  $('#quantity').change(function(){
    var $button = $(this).parents('.product-content').find('.buy-button');

    $button.attr('data-item-quantity', $(this).val());
  });

   $('#size').change(function(){
    var $button = $(this).parents('.product-content').find('.buy-button');

    $button.attr('data-item-custom1-value', $(this).val());
  });

  $(".product-thumbnail").click(function(){
    $(".product-image").attr("src",$(this).attr("src"));

    //$(this).parents('button').attr('class', 'active');
  });
});


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

    $(document).find(".active").attr('class', '');
    $(this).parents('button').attr('class', 'active');
  });

  $('.Show').click(function() {
    $('#sizes-table').show(0);
    $('.Show').hide(0);
    $('.Hide').show(0);
  });

  $('.Hide').click(function() {
      $('#sizes-table').hide(0);
      $('.Show').show(0);
      $('.Hide').hide(0);
  });

  // $('.toggle').click(function() {
  //     $('#sizes-table').toggle('slow');
  // });
});

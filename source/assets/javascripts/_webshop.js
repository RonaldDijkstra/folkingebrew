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
    $("#product-image").attr("src",$(this).attr("src"));

    $(document).find(".active").attr('class', '');
    $(this).parents('button').attr('class', 'active');
  });

  $('.toggle').click(function(e) {
    e.preventDefault();

    var $this = $(this);

    if ($this.next().hasClass('show')) {
        $this.parent().find('.toggle').removeClass('active');
        $this.next().removeClass('show');
        $this.next().slideUp(350);
    } else {
        $this.parent().find('.toggle').toggleClass('active');
        $this.next().toggleClass('show');
        $this.next().slideToggle(350);
    }
  });
});

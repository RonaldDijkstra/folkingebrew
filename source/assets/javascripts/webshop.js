export default function webshop() {  
  $(document).ready(function() {
    $('.quantity-minus').click(function () {
      var $input = $(this).parent().find('input');
      $input.focus();
      var count = parseInt($input.val()) - 1;
      count = count < 1 ? 1 : count;
      $input.val(count);
      $input.change();
      return false;
    });

    $('.quantity-plus').click(function () {
      var $input = $(this).parent().find('input');
      $input.focus();
      var max = $input.attr('max');
      var oldValue = parseFloat($input.val());

      if (oldValue >= max) {
        var change = oldValue;
      } else {
        var change = $input.val(parseInt($input.val()) + 1);
      }

      $input.change();
      return false;
    });

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

      $(document).find(".thumbnail-active").removeClass('thumbnail-active');
      $(this).parents('button').addClass('thumbnail-active');
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
}

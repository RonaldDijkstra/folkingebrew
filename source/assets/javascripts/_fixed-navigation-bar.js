 $(window).scroll(function(){
  if ($(this).scrollTop() > 217) {
    $('#navigation').addClass('fixed');
    $('body').addClass('increase-height');
  } else {
    $('#navigation').removeClass('fixed');
    $('body').removeClass('increase-height');
  }
});

// Sticky Header
$(window).scroll(function () {

  if ($(window).scrollTop() > 100) {
    $('.header').addClass('sticky');
  } else {
    $('.header').removeClass('sticky');
  }
});

// Mobile Navigation
$('.mobile-toggle').click(function () {
  if ($('.header').hasClass('open-nav')) {
    $('.header').removeClass('open-nav');
  } else {
    $('.header').addClass('open-nav');
  }
});

$('.header li a').click(function () {
  if ($('.header').hasClass('open-nav')) {
    $('.navigation').removeClass('open-nav');
    $('.header').removeClass('open-nav');
  }
});

$(document).ready(function(){
  $('#menu-toggle').on('click', function(){
    $('body').toggleClass('nav-open no-scroll');
    return false;
  });
});

$(window).scroll(function() {
    var navbarHeight = $('.site-header').outerHeight()
    var scroll = $(window).scrollTop();

    if (scroll >= navbarHeight) {
        $(".site-header").addClass("not-at-the-top").removeClass("at-the-top");
    } else {
        $(".site-header").addClass("at-the-top").removeClass("not-at-the-top");
    }
});

// Hide Header on on scroll down
var didScroll;
var lastScrollTop = 0;
var delta = 1;
var navbarHeight = $('.site-header').outerHeight();

$(window).scroll(function(event){
    didScroll = true;
});

setInterval(function() {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 100);

function hasScrolled() {
    var st = $(this).scrollTop();

    // Make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;

    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight){
        // Scroll Down
        $('.site-header').removeClass('show-nav').addClass('hide-nav');
    } else {
        // Scroll Up
        if(st + $(window).height() < $(document).height()) {
            $('.site-header').removeClass('hide-nav').addClass('show-nav');
        }
    }

    lastScrollTop = st;
}

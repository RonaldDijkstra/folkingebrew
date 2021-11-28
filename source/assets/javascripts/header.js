export default function header() {
  // Variables

  const $navbarHeight = $('.site-header').outerHeight();
  let $didScroll;
  let $lastScrollTop = 0;
  const $delta = 1;
  const $window = $(window);

  // Mobile navigation toggle adds nav-open and no-scroll

  $(document).ready(() => {
    $('#menu-toggle').on('click', () => {
      $('body').toggleClass('nav-open no-scroll');
      return false;
    });
  });

  $window.scroll(() => {
    const $scroll = $window.scrollTop();

    if ($scroll >= $navbarHeight / 10) {
      $('body').addClass('not-at-the-top').removeClass('at-the-top');
    } else {
      $('body').addClass('at-the-top').removeClass('not-at-the-top');
    }
  });

  $window.scroll(() => {
    $didScroll = true;
  });

  function hasScrolled() {
    const $st = $window.scrollTop();

    if (Math.abs($lastScrollTop - $st) <= $delta) return;

    if ($st > $lastScrollTop && $st > $navbarHeight) {
      // Scroll Down
      $('.site-header').removeClass('show-nav').addClass('hide-nav');
    } else if ($st + $window.height() < $(document).height()) {
      $('.site-header').removeClass('hide-nav').addClass('show-nav');
    }

    $lastScrollTop = $st;
  }

  setInterval(() => {
    if ($didScroll) {
      hasScrolled();
      $didScroll = false;
    }
  }, 100);
}

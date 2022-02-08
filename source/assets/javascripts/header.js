export default function header() {
  // Variables
  const $navbarHeight = $('.site-header').outerHeight();
  let $didScroll;
  let $lastScrollTop = 0;
  const $delta = 1;
  const $window = $(window);
  const menuToggle = document.getElementById('menu-toggle');

  // Mobile navigation toggle
  // Vanilla JS
  document.addEventListener("DOMContentLoaded", function(event) {
    menuToggle.addEventListener('click', () => {
      document.body.classList.toggle('mobile-navigation-open');
      document.body.classList.toggle('overflow-hidden');
      return false;
    });
  });

  // Add or remove at the top class at body
  $window.scroll(() => {
    const $scroll = $window.scrollTop();

    if ($scroll >= $navbarHeight / 10) {
      $('body').addClass('not-at-the-top').removeClass('at-the-top');
    } else {
      $('body').addClass('at-the-top').removeClass('not-at-the-top');
    }
  });

  // Did scroll?
  $window.scroll(() => {
    $didScroll = true;
  });

  // If the user scrolled, hide the nav
  function hasScrolled() {
    const $st = $window.scrollTop();

    if (Math.abs($lastScrollTop - $st) <= $delta) return;

    if ($st > $lastScrollTop && $st > $navbarHeight) {
      // Scroll Down
      $('.site-header').removeClass('opacity-100').addClass('opacity-0');
    } else if ($st + $window.height() < $(document).height()) {
      $('.site-header').removeClass('opacity-0').addClass('opacity-100');
    }

    $lastScrollTop = $st;
  }

  // Scroll depth for did scroll
  setInterval(() => {
    if ($didScroll) {
      hasScrolled();
      $didScroll = false;
    }
  }, 100);
}

export default function header() {
  // Variables
  const navbar = document.querySelector('.site-header');
  const navbarHeight = navbar.offsetHeight;
  let didScroll;
  let lastScrollTop = 0;
  const delta = 1;
  const $window = $(window);
  const documentBody = document.body;
  const menuToggle = document.getElementById('menu-toggle');

  // Mobile navigation toggle
  // Vanilla JS
  document.addEventListener('DOMContentLoaded', function(event) {
    menuToggle.addEventListener('click', () => {
      documentBody.classList.toggle('mobile-navigation-open');
      documentBody.classList.toggle('overflow-hidden');
      return false;
    });
  });

  // Add or remove at the top class at body
  document.addEventListener('scroll', function(event) {
    const scroll = window.scrollY;
    didScroll = true;

    if (scroll >= navbarHeight / 10) {
      documentBody.classList.add('not-at-the-top');
      documentBody.classList.remove('at-the-top');
    } else {
      documentBody.classList.add('at-the-top');
      documentBody.classList.remove('not-at-the-top');
    }
  });

  // If the user scrolled, hide the nav
  function hasScrolled() {
    const scroll = window.scrollY;

    if (Math.abs(lastScrollTop - scroll) <= delta) return;

    if (scroll > lastScrollTop && scroll > navbarHeight) {
      // Scroll Down
      $('.site-header').removeClass('opacity-100').addClass('opacity-0');
    } else if (scroll + $window.height() < $(document).height()) {
      $('.site-header').removeClass('opacity-0').addClass('opacity-100');
    }

    lastScrollTop = scroll;
  }

  // Scroll depth for did scroll
  setInterval(() => {
    if (didScroll) {
      hasScrolled();
      didScroll = false;
    }
  }, 100);
}

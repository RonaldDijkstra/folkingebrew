export default function header() {
  const navbar = document.querySelector('#site-header');
  const navbarHeight = navbar.offsetHeight;
  const documentBody = document.body;
  const menuToggle = document.querySelector('#menu-toggle');
  let lastScrollTop = 0;
  let scrollTimeout;

  window.addEventListener('DOMContentLoaded', () => {
    menuToggle.addEventListener('click', () => {
      documentBody.classList.toggle('menu-open');
      documentBody.classList.toggle('overflow-hidden');
      documentBody.classList.toggle('md:overflow-visible');
      return false;
    });
  });

  document.addEventListener('scroll', () => {
    const scroll = window.scrollY;

    // Handle at-the-top class for transparent header
    if (scroll >= navbarHeight / 10) {
      documentBody.classList.remove('at-the-top');
    } else {
      documentBody.classList.add('at-the-top');
    }

    // Clear any existing timeout
    if (scrollTimeout) {
      clearTimeout(scrollTimeout);
    }

    // Handle header visibility with smooth transitions
    if (scroll > lastScrollTop && scroll > navbarHeight) {
      // Scrolling down - hide navbar
      documentBody.classList.add('header-hidden');
    } else if (scroll < lastScrollTop) {
      // Scrolling up - show navbar
      documentBody.classList.remove('header-hidden');
    }

    // Show navbar when user stops scrolling (after 150ms of no scroll)
    scrollTimeout = setTimeout(() => {
      if (scroll > navbarHeight) {
        documentBody.classList.remove('header-hidden');
      }
    }, 150);

    lastScrollTop = scroll;
  });
}

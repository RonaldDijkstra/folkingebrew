export default function header() {
  const navbar = document.querySelector('#site-header');
  const navbarHeight = navbar.offsetHeight;
  const windowHeight = window.innerHeight;
  const documentBody = document.body;
  const documentHeight = documentBody.scrollHeight;
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

    if (scroll >= navbarHeight / 10) {
      documentBody.classList.remove('at-the-top');
    } else {
      documentBody.classList.add('at-the-top');
    }

    // Clear any existing timeout
    if (scrollTimeout) {
      clearTimeout(scrollTimeout);
    }

    if (scroll > lastScrollTop && scroll > navbarHeight) {
      // Scrolling down - hide navbar
      navbar.classList.add('absolute');
      navbar.classList.add('opacity-0');
      navbar.classList.remove('fixed');
      navbar.classList.remove('opacity-100');
    } else if (scroll < lastScrollTop) {
      // Scrolling up - show navbar
      navbar.classList.add('fixed');
      navbar.classList.add('opacity-100');
      navbar.classList.remove('absolute');
      navbar.classList.remove('opacity-0');
    }

    // Show navbar when user stops scrolling (after 150ms of no scroll)
    scrollTimeout = setTimeout(() => {
      if (scroll > navbarHeight) {
        navbar.classList.add('fixed');
        navbar.classList.add('opacity-100');
        navbar.classList.remove('absolute');
        navbar.classList.remove('opacity-0');
      }
    }, 10);

    lastScrollTop = scroll;
  });
}

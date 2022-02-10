export default function header() {
  // Variables
  const navbar = document.querySelector('.site-header');
  const navbarHeight = navbar.offsetHeight;
  let lastScrollTop = 0;
  const delta = 1;
  const windowHeight = window.innerHeight;
  const documentBody = document.body;
  const documentHeight = documentBody.scrollHeight;
  const menuToggle = document.getElementById('menu-toggle');

  window.addEventListener('DOMContentLoaded', () => {
    // Mobile navigation toggle
    menuToggle.addEventListener('click', () => {
      documentBody.classList.toggle('mobile-navigation-open');
      documentBody.classList.toggle('overflow-hidden');
      return false;
    });
  });

  document.addEventListener('scroll', () => {
    const scroll = window.scrollY;

    // Add and remove top classes
    if (scroll >= navbarHeight / 10) {
      documentBody.classList.add('not-at-the-top');
      documentBody.classList.remove('at-the-top');
    } else {
      documentBody.classList.add('at-the-top');
      documentBody.classList.remove('not-at-the-top');
    }

    // Show and hide navigation bar
    if (Math.abs(lastScrollTop - scroll) <= delta) return;

    if (scroll > lastScrollTop && scroll > navbarHeight) {
      navbar.classList.remove('opacity-100');
      navbar.classList.add('opacity-0');
    } else if (scroll + windowHeight < documentHeight) {
      navbar.classList.remove('opacity-0');
      navbar.classList.add('opacity-100');
    }

    lastScrollTop = scroll;
  });
}

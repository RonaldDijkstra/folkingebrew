export default function header() {
  const navbar = document.querySelector('.site-header');
  const navbarHeight = navbar.offsetHeight;
  const windowHeight = window.innerHeight;
  const documentBody = document.body;
  const documentHeight = documentBody.scrollHeight;
  const menuToggle = document.querySelector('#menu-toggle');
  let lastScrollTop = 0;

  window.addEventListener('DOMContentLoaded', () => {
    menuToggle.addEventListener('click', () => {
      documentBody.classList.toggle('mobile-navigation-open');
      documentBody.classList.toggle('overflow-hidden');
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

    if (scroll > lastScrollTop && scroll > navbarHeight) {
      navbar.classList.add('hidden');
    } else if (scroll + windowHeight < documentHeight) {
      navbar.classList.remove('hidden');
    }

    lastScrollTop = scroll;
  });
}

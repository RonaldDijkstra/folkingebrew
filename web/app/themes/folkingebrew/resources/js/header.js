export default function header() {
  const navbar = document.querySelector('#site-header');
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

    if (scroll > lastScrollTop && scroll > navbarHeight) {
      navbar.classList.add('absolute');
      navbar.classList.add('opacity-0');
      navbar.classList.remove('static');
      navbar.classList.remove('opacity-100');
    } else if (scroll + windowHeight < documentHeight) {
      navbar.classList.add('static');
      navbar.classList.add('opacity-100');
      navbar.classList.remove('absolute');
      navbar.classList.remove('opacity-0');
    }

    lastScrollTop = scroll;
  });
}

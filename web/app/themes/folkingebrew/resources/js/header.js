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

    // Handle touch devices (like iPad) for submenu interaction
    const submenuParents = document.querySelectorAll('.nav-item-with-submenu');

    submenuParents.forEach((parent) => {
      const link = parent.querySelector('a');
      const submenu = parent.querySelector('.submenu');
      let touchStarted = false;

      link.addEventListener('touchstart', (e) => {
        // Check if we're on a tablet or larger screen (not mobile)
        if (window.innerWidth >= 768) {
          // If submenu is not visible, prevent navigation and show submenu
          if (!parent.classList.contains('touch-open')) {
            e.preventDefault();

            // Close all other open submenus
            submenuParents.forEach((otherParent) => {
              if (otherParent !== parent) {
                otherParent.classList.remove('touch-open');
              }
            });

            // Open this submenu
            parent.classList.add('touch-open');
            touchStarted = true;
          }
        }
      });

      // Handle click event
      link.addEventListener('click', (e) => {
        // On touch devices with screen >= 768px
        if (window.innerWidth >= 768 && touchStarted) {
          // If submenu is now open, allow second click to navigate
          if (parent.classList.contains('touch-open')) {
            // Let the link navigate normally
            return true;
          } else {
            // Prevent navigation on first click
            e.preventDefault();
          }
        }
      });
    });

    // Close submenus when clicking outside
    document.addEventListener('touchstart', (e) => {
      if (!e.target.closest('.nav-item-with-submenu')) {
        submenuParents.forEach((parent) => {
          parent.classList.remove('touch-open');
        });
      }
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

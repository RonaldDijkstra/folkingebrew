import { Swiper, Navigation, Pagination, Autoplay } from 'swiper';

export default function thePub() {
  document.addEventListener('DOMContentLoaded', () => {
    // Map day of week number (0-6) to the index of the row in the table (0-4)
    const dayMapping = {
      0: 4,
      3: 0,
      4: 1,
      5: 2,
      6: 3
    };

    const currentDayOfWeek = new Date().getDay();

    if (Object.prototype.hasOwnProperty.call(dayMapping, currentDayOfWeek)) {
      // Use a more specific selector for the pub hours table
      const tableRows = document.querySelector('.pub-hours-table tbody').querySelectorAll('tr');

      const currentDayRow = tableRows[dayMapping[currentDayOfWeek]];

      currentDayRow.classList.add('font-bold');

      const dayCell = currentDayRow.querySelector('td:first-child');
      dayCell.innerHTML += ' <span class="text-green-600">(Today)</span>';
    }

    // Initialize testimonials Swiper
    const testimonialsSwiper = document.querySelector('.testimonials-swiper');
    if (testimonialsSwiper) {
      new Swiper('.testimonials-swiper', {
        modules: [Navigation, Pagination, Autoplay],
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        breakpoints: {
          768: {
            slidesPerView: 2,
            spaceBetween: 40,
          },
          1024: {
            slidesPerView: 3,
            spaceBetween: 50,
          },
        },
      });
    }
  });
}

import { Swiper } from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

export default function slider() {
  // Find all review sliders (using querySelectorAll to handle multiple instances)
  const reviewSliders = document.querySelectorAll('.review-slider');

  reviewSliders.forEach((reviewSlider) => {
    // Check if Swiper is already initialized on this element
    if (reviewSlider.swiper) {
      return; // Already initialized, skip
    }

    // Make sure the element has slides
    const slides = reviewSlider.querySelectorAll('.swiper-slide');
    if (slides.length === 0) {
      return; // No slides yet, skip
    }

    new Swiper(reviewSlider, {
      modules: [Navigation, Pagination, Autoplay],
      slidesPerView: 1,
      spaceBetween: 30,
      loop: false,
      autoplay: false,
      pagination: {
        el: reviewSlider.querySelector('.swiper-pagination'),
        clickable: true,
      },
      navigation: {
        nextEl: reviewSlider.querySelector('.swiper-button-next'),
        prevEl: reviewSlider.querySelector('.swiper-button-prev'),
      },
      breakpoints: {
        640: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 2,
          spaceBetween: 30,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 40,
        },
      },
    });
  });
}

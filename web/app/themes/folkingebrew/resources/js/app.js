import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

import flatpickr from "flatpickr";
import header from "./header.js";
import slider from "./slider.js";
import openingHours from "./opening-hours.js";
import forms from "./forms.js";

const initFlatpickr = () => {
  const dateInputs = document.querySelectorAll('.flatpickr-input');

  dateInputs.forEach((input) => {
    const config = {
      dateFormat: "Y-m-d",
      allowInput: true,
      altInput: true,
      altFormat: "d-m-Y",
      placeholder: "Select a date...",
    };

    // Check for no past dates setting
    if (input.dataset.noPastDates === 'true') {
      config.minDate = "today";
    }

    flatpickr(input, config);
  });
};

const initQuantityButtons = () => {
  document.addEventListener('click', (e) => {
    if (e.target.classList.contains('qty-btn')) {
      const button = e.target;
      const quantityInput = button.parentElement.querySelector('input[type="number"]');

      if (!quantityInput) return;

      const currentValue = parseInt(quantityInput.value) || 1;
      const min = parseInt(quantityInput.min) || 1;
      const max = parseInt(quantityInput.max) || 999;

      if (button.classList.contains('minus')) {
        if (currentValue > min) {
          quantityInput.value = currentValue - 1;
        }
      } else if (button.classList.contains('plus')) {
        if (currentValue < max) {
          quantityInput.value = currentValue + 1;
        }
      }
    }
  });
};

const initProductGallery = () => {
  const galleryButtons = document.querySelectorAll('.product-gallery-thumb');
  const mainImage = document.querySelector('.product-main-image');

  if (!mainImage || galleryButtons.length === 0) return;

  galleryButtons.forEach((button) => {
    button.addEventListener('click', () => {
      const thumbImg = button.querySelector('img');
      if (!thumbImg) return;

      // Get the full-size image URL from the thumbnail
      const fullSrc = thumbImg.dataset.fullSrc || thumbImg.src.replace('-150x150', '');
      const fullSrcset = thumbImg.dataset.fullSrcset || '';
      const alt = thumbImg.alt;

      // Update main image
      const mainImg = mainImage.querySelector('img');
      if (mainImg) {
        mainImg.src = fullSrc;
        if (fullSrcset) {
          mainImg.srcset = fullSrcset;
        }
        mainImg.alt = alt;
      }

      // Update active state
      galleryButtons.forEach(btn => btn.classList.remove('ring-1', 'ring-primary'));
      button.classList.add('ring-1', 'ring-primary');
    });
  });
};

const init = async (err) => {
  if (err) {
    console.error(err);
  }

  header();
  openingHours();
  slider();
  forms();
  initFlatpickr();
  initQuantityButtons();
  initProductGallery();
};

init();

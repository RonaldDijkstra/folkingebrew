export default function ageGate() {
  window.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.accordion')) {
      document.querySelectorAll('.accordion-button').forEach((item) => {
        item.addEventListener('click', (event) => {
          event.preventDefault();

          item.classList.toggle('active');
          const next = item.nextSibling.nextSibling;
          next.classList.toggle('hidden');
          item.querySelector('.accordion-icon').classList.toggle('rotate-180');
          // animate
        });
      });
    }
  });
}

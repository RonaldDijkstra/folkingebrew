import Cookies from 'js-cookie';

export default function ageGate() {
  window.addEventListener('DOMContentLoaded', () => {
    const ageGateBox = document.querySelector("[data-rel='age-gate']");
    const consentButton = document.querySelector('.age-gate-consent');
    const cookieName = 'age_consent';
    const cookieValue = Cookies.get(cookieName);

    if (!ageGateBox) {
      return false;
    }

    if (cookieValue !== 'true') {
      ageGate.classList.remove('hidden');
      ageGate.classList.add('block', 'no-scroll');
    }

    function closeOverlay() {
      ageGate.classList.remove('block', 'no-scroll');
      ageGate.classList.add('hidden');

      Cookies.set(cookieName, true);
    }

    consentButton.addEventListener('click', closeOverlay);

    return false;
  });
}

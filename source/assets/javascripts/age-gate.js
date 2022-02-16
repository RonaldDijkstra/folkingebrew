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
      ageGateBox.classList.remove('hidden');
      ageGateBox.classList.add('block', 'no-scroll');
    }

    function closeOverlay() {
      ageGateBox.classList.remove('block', 'no-scroll');
      ageGateBox.classList.add('hidden');

      Cookies.set(cookieName, true);
      console.log("We've set a cookie to remember your age, but only for a day!")
    }

    consentButton.addEventListener('click', closeOverlay);

    return false;
  });
}

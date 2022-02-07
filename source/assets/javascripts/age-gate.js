import Cookies from 'js-cookie';

export default function header() {
  $(document).ready(() => {
    const ageGate = document.querySelector("[data-rel='age-gate']");
    const consentButton = document.querySelector('.age-gate-consent');
    const cookieName = 'age_consent';
    const cookieValue = Cookies.get(cookieName);

    if (!ageGate) {
      return false;
    }

    if (cookieValue !== 'true') {
      ageGate.classList.add('block', 'no-scroll');
    }

    function closeOverlay() {
      ageGate.classList.remove('show');

      Cookies.set(cookieName, true);
    }

    consentButton.addEventListener('click', closeOverlay);

    return false;
  });
}

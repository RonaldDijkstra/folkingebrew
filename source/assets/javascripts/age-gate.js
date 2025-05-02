import Cookies from 'js-cookie';

function loadZenChefWidget() {
  const script = document.createElement('script');
  script.id = 'zenchef-sdk';
  script.async = true;
  script.src = 'https://sdk.zenchef.com/v1/sdk.min.js';
  document.body.appendChild(script);

  const config = document.createElement('div');
  config.className = 'zc-widget-config';
  config.setAttribute('data-restaurant', '375897');
  config.setAttribute('data-open', '');
  config.setAttribute('data-lang', 'en');
  document.body.appendChild(config);
}

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
    } else {
      loadZenChefWidget();
    }

    function closeOverlay() {
      ageGateBox.classList.remove('block', 'no-scroll');
      ageGateBox.classList.add('hidden');

      Cookies.set(cookieName, true);
      loadZenChefWidget();
    }

    consentButton.addEventListener('click', closeOverlay);

    return false;
  });
}

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

  // Only auto-open on homepage and /the-pub page
  const path = window.location.pathname;
  const shouldAutoOpen = (path === '/' || path === '/the-pub/') && window.innerWidth > 960;
  config.setAttribute('data-open', shouldAutoOpen ? 'true' : '');
  config.setAttribute('data-lang', navigator.language.split('-')[0]);
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
      ageGateBox.classList.add('block');
      document.body.classList.add('no-scroll');
    } else {
      loadZenChefWidget();
    }

    function closeOverlay() {
      ageGateBox.classList.remove('block');
      ageGateBox.classList.add('hidden');
      document.body.classList.remove('no-scroll');

      Cookies.set(cookieName, true);
      loadZenChefWidget();
    }

    consentButton.addEventListener('click', closeOverlay);

    return false;
  });
}

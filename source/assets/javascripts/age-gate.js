import Cookies from 'js-cookie';

export default function header() {
	$(document).ready(function() {
	  var ageGate = document.querySelector("[data-rel='age-gate']");
	  var consentButton = document.querySelector("[data-rel='age-gate-consent']");
	  var cookieName = "age_consent";
	  var cookieValue = Cookies.get(cookieName);

	  if (!ageGate) {
	    return false;
	  }

	  if (cookieValue !== "true") {
	    ageGate.classList.add("show");
	  }

	  consentButton.addEventListener("click", closeOverlay);

	  function closeOverlay() {
	    ageGate.classList.remove("show");

	    Cookies.set(cookieName, true);
	  }
	});
}
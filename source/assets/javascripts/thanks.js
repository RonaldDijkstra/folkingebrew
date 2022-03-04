window.addEventListener('DOMContentLoaded', () => {
  const urlParams = {};

  window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, (m, key, value) => {
    urlParams[key] = decodeURIComponent(value);
  });

  const messageEl = document.querySelector('#thanks-message');
  let message;

  if (urlParams.newsletter) {
    switch (urlParams.newsletter) {
      case 'subscribed':
        message = 'You are now subscribed to our newsletter. To complete the sign up process, click on the link in the email we just sent you.';
        break;
      case 'confirmed':
        message = 'Your subscription for our newsletter has been confirmed. Thank you for subscribing!';
        break;
      case 'unsubscribed':
        message = 'You have been unsubscribed from our newsletter. Sorry to see you go.';
        break;
      default:
        break;
    }

    messageEl.innerHTML = message || '';
  }
});

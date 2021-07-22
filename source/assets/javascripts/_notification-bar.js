$(document).ready(function() {
  var notificationBar = document.querySelector("#NotificationBar");
  var closeButton = document.querySelector("#NotificationClose");
  var cookieName = "notification-2close_" + notificationBar.getAttribute('data-rel');
  var cookieValue = Cookies.get(cookieName);

  if (!notificationBar) {
    return false;
  }

  if (cookieValue !== "true") {
    notificationBar.classList.add("show");
  }

  closeButton.addEventListener("click", closeNotificationBar);

  function closeNotificationBar() {
    notificationBar.classList.remove("show");

    Cookies.set(cookieName, true);
  }
});

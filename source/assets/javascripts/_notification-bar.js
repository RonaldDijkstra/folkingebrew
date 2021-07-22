$(document).ready(function() {
  var notificationBar = document.querySelector("#NotificationBar");
  var closeButton = document.querySelector("#NotificationClose");
  var cookieName = "notification_close_" + notificationBar.getAttribute('data-rel');
  var cookieValue = Cookies.get(cookieName);

  if (!notificationBar) {
    return false;
  }

  if (cookieValue !== "true") {
    notificationBar.classList.add("notification-active");
  }

  closeButton.addEventListener("click", closeNotificationBar);

  function closeNotificationBar() {
    notificationBar.classList.remove("notification-active");

    Cookies.set(cookieName, true);
  }
});

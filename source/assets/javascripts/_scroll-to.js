$(function() {
  "use strict";

  var offsetTop = -$(".global-navigation").height() * 1.5;

  $("a[data-scroll-to]").on("click", function () {
    $.scrollTo(this.hash, 400, {
      offset: offsetTop,
    });

    return false;
  });
});

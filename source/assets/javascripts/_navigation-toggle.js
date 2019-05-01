$(document).ready(function(){
  $('#mobile-toggle').on('click', function(){
    $('.site-navigation').toggleClass('navigation-open');
    return false;
  });
});

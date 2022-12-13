$(window).load(function () {
  $.preloadCssImages();
}); 

$(document).ready(
  function() {
    $("#header span").hide();
    $("#navigation li").hover(
      function() {
        $("#navigation li span").hide();
        $(this).find("span").fadeIn("fast");
      },
      function(e) {
        $("#navigation li span").fadeOut("fast");
      }
    );
  }
);


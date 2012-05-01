// requires jQuery
function setExpanded(image, expand) {
  if(expand == undefined) expand = !(image.attr("expanded") == "true");
  if(expand) {
    image.animate({ marginTop: 0 }, "fast", undefined,
                  function() { $(this).animate({ height: 200, marginRight: -100, padding: 10 }, "fast") });
    image.parent().parent().children(".player").children(".details").fadeIn();
  } else {
    image.animate({ height: 100, marginRight: 0, padding: 2 }, "fast", undefined,
                      function() { $(this).animate({ marginTop: -50 }, "fast") });
    image.parent().parent().children(".player").children(".details").fadeOut("fast");
  }
  image.attr("expanded", expand ? "true" : "false");
}

$(document).ready(function() {
  $(".thumbnail img").click(function () { setExpanded($(this)) });
  $(".player object").click(function() { setExpanded($(this).parent().parent().children(".thumbnail").children("img"), true); });
});

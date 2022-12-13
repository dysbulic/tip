// requires jQuery
function hoverRank(holder, rank) {
  holder.children(":lt(" + rank + ")").addClass("selected").empty().append("&#x2605;");
  holder.children(":eq(" + rank + ")").removeClass("selected").empty().append("&#x2606;");
  holder.children(":gt(" + rank + ")").removeClass("selected").empty().append("&#x2606;");
}

function setRank(holder, rank) {
  holder.attr("rank", rank);
  ratingURL = window.location.toString();
  jQuery.get(ratingURL, {task:"rate", rating:rank})
}

$(document).ready(function() {
  $(".rating").hover(function() { $(this).addClass("selecting"); },
                     function() { $(this).removeClass("selecting");
                                  hoverRank($(this), $(this).attr("rank"));
                                });

  $(".rating").each(function() {
    stars = $(this).text().split('');
    $(this).empty();
    rank = 0;
    for(i = 0; i < stars.length; i++) {
      if(stars[i].charCodeAt(0) == parseInt("2605", 16)) { rank++; }
      else if(stars[i].charCodeAt(0) == parseInt("2606", 16)) { }
      else { continue; }
      elm = $("<span>" + stars[i] + "</span>");
      elm.attr("rank", $(this).children().size() + 1);
      elm.hover(function() { hoverRank($(this).parent(), $(this).attr("rank")); }, function() {});
      elm.click(function() { setRank($(this).parent(), $(this).attr("rank")); });
      $(this).append(elm);
    }
    $(this).attr("rank", rank);
  });
});

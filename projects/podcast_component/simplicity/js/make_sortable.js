// requires jQuery
$(document).ready(function() {
  uplink = $('<a class="up">&#x25B2;</a>');
  uplink.click(function() { alert("hi"); });
  uplink.hover(function() { alert("hi"); $(this).addClass("active"); },
               function() { $(this).removeClass("active"); });
  downlink = $('<a class="down">&#x25BC;</a>');
  downlink.hover(function() { $(this).addClass("active"); },
                 function() { $(this).removeClass("active"); });
  $("th.sortable").append(uplink);
  $("th.sortable").append(downlink);
});

var months = { "Jan" : 1, "Feb" : 2, "Mar" : 3, "Apr" : 4,
               "May" : 5, "Jun" : 6, "Jul" : 7, "Aug" : 8,
               "Sep" : 9, "Oct" : 10, "Nov" : 11, "Dec" : 12 };
var startYear = 2009;
var endYear = 2015;
var span = 1000;

function toPosition(date) {
    elms = date.split(" ");
    offset = (parseInt(elms[0]) - 1) / 365.25 + ((months[elms[1]] - 1) / 12 + parseInt(elms[2]) - startYear);
    pos = span * offset / (endYear - startYear);
    return pos;
}

$(document).ready(function () {
    // Position elements
    $(".event").each(function() {
        $(this).css("left", toPosition($(this).find(".date").text()));
    });
});

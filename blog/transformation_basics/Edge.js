function Edge(start, end) {
    var $line = $.tip.$('<line/>');

    function setPoints() {
        var intersect = { start: start.nearestJoin(end.center),
                          end: end.nearestJoin(start.center) };

        $line.attr({ x1: intersect.start.x, y1: intersect.start.y,
                    x2: intersect.end.x,   y2: intersect.end.y });
    }

    $.each( [ start, end ], function(idx, elem) {
        $(elem.center).props().change(setPoints);
    });

    setPoints();

    this.$svg = $line;
}

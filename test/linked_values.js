function Circle() {
    var values = { center: { x: 150, y: 150 }, radius: 50 };
    var border = $.tip.$('<circle/>');

    $(values.center).link(border.get(0), { x: 'cx', y: 'cy' });
    $(values).link(border.get(0), { radius: 'r' });

    $(this).expose(values);

    var bbox = { get x() { return values.center.x - values.radius },
                 set x(val) { values.center.x = val + values.radius },
                 get y() { return values.center.y - values.radius },
                 set y(val) { values.center.y = val + values.radius },
                 get width() { return values.radius * 2 },
                 set width(val) { values.radius = val / 2 },
                 get height() { return values.radius * 2 },
                 set height(val) { values.radius = val / 2 } };

    $(values.center).change('x', function() { $(bbox).propTrigger('x', 'change') });
    $(values.center).change('y', function() { $(bbox).propTrigger('y', 'change') });
    $(values).change('radius', function() { $(bbox).propTrigger([ 'width', 'height' ], 'change') });

    var boundary = $.tip.$('<rect/>');
    $(bbox).link(boundary.get(0));

    this.svg = $.tip.$('<g/>').append(border).append(boundary);
    this.border = border;
}

$(function() {
    $('#dataobj').attr('width', $('#data').outerWidth());
    var dataRow = $.tip.$('<html:tr/>');
    $('#data').children('tbody').append(dataRow);

    var circle = new Circle();
    $('svg').append(circle.svg);

    $('#data tr').children('th').each(function() {
        var elem = $.tip.$('<html:input type="text"/>');
        var base = circle;
        
        var prop;
        for(var props = $(this).text().split('.');
            props.length > 0 && (prop = props.shift());
           ) {
            if(props.length > 0) {
                base = base[prop];
            }
        }
        var map = {};
        map[prop] = 'value';
        $(base).link(elem.get(0), map);
        // The first edit isn't triggering a change in jQuery 1.4
        elem.data("_change_data", elem.val());
        dataRow.append($.tip.$('<html:td/>').append(elem));
    });

    

    circle.border.drag(function(event) {
        $(this).attr({ cx: event.user.x, cy: event.user.y });
    });
});

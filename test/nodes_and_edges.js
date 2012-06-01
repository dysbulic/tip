function Node(name) {
    var x, y, radius;
    var center = { get x() { return x },
                   set x(val) { x = val },
                   get y() { return y },
                   set y(val) { y = val } };
    
    [ center.x, center.y, radius ] = [ 50, 50, 50 ];

    this.__defineGetter__('center', function() { return center; });
    this.__defineSetter__('center', function(val) { [ center.x, center.y ] = [ val.x, val.y ]; });

    this.__defineGetter__('radius', function() { return radius });

    var circle = $.tip.$('<circle/>').attr({ cx: center.x, cy: center.y, r: this.radius });

    $.each({ cx: 'x', cy: 'y' }, function(attr, prop) {
        var attribute = circle.get(0).attributes.getNamedItem(attr);
        $(center).change(prop, function(val) { attribute.nodeValue = val; });
    });

    var text = $.tip.$('<text/>');
    var textHolder;
    text.attr('x', center.x);

    this.__defineGetter__('name', function() { return name; });
    this.__defineSetter__('name', function(val) {
        text.text(val);
        // The bounding box can't be computed unless the text is in the document
        var noParent = text.parent().length == 0;
        if(noParent) {
            if(!textHolder) {
                textHolder = $.tip.$('<g class="holder" style="visibility: hidden"/>');
                $('svg').append(textHolder);
            }
            textHolder.append(text);
        }
        var bbox = text.get(0).getBBox();
        text.attr('y', center.y + parseFloat(bbox.height) / 2);
        if(noParent) {
            text.remove();
        }
    });

    $.each([ 'x', 'y' ], function(idx, attr) {
        if(!text.attr(attr)) {
            text.attr(attr, ''); // getNamedItem not set unless attribute is defined
        }
        var attribute = text.get(0).attributes.getNamedItem(attr);
        $(center).change(attr, function(val, oldVal) {
            attribute.nodeValue = parseFloat(attribute.nodeValue) + (val - oldVal);
        });
    });

    this.name = name;

    this.svg = $.tip.$('<g/>').append(circle).append(text);

    this.svg.drag(center);
}

function Edge(start, end) {
    var line = $.tip.$('<line/>');

    function setPoints() {
        var delta = { x: end.center.x - start.center.x, y: end.center.y - start.center.y };
        var length = Math.sqrt(Math.pow(delta.x, 2) + Math.pow(delta.y, 2));

        line.attr({ x1: start.center.x + delta.x * start.radius / length,
                    y1: start.center.y + delta.y * start.radius / length,
                    x2: end.center.x - delta.x * (end.radius + 1) / length, // Marker overhangs the line
                    y2: end.center.y - delta.y * (end.radius + 1) / length });
    }

    $.each( [ start, end ], function(idx, elem) {
            $.each( [ 'x', 'y' ], function(idx, axis) {
                $(elem.center).change(axis, setPoints);
            });
    });

    setPoints();

    this.svg = line;
}

$.tip.ready(function() {
    var A = new Node('A');
    A.center = { x: 50, y: 100 };

    var B = new Node('B');
    B.center = { x: 200, y: 200 };

    var e = new Edge(A, B);

    A.center = { x: 150, y: 100 };

    B.svg.click(function() {
        $(A.center).animate({ x: '+=50', y: 50 }, 1000)
                   .animate({ x: 50, y: '-=50' }, 1000)
                   .animate({ x: 150, y: 100 }, 1000);
    });

    $('svg').append(A.svg);
    $('svg').append(B.svg);
    $('svg').append(e.svg);
});

function Vertex(name) {
    var values = { center: { x: 50, y: 50 }, radius: 50 };

    // The vertex has a circular border
    var $border = $.tip.$('<circle/>');
    $(values.center).props().link($border, { x: 'cx', y: 'cy' });
    $(values).prop('radius').link($border.prop('r'));

    // Bounding box
    values.bbox = { get x() { return values.center.x - values.radius },
                    get y() { return values.center.y - values.radius },
                    get width() { return values.radius * 2 },
                    get height() { return values.radius * 2 } };

    $(values.center).props([ 'x', 'y' ]).change(function() {
        $(values.bbox).prop(this.name).trigger('change');
    });

    $(this).expose(values);

    // Text holder for the name of the vertex
    var $text = $.tip.$('<html:p/>');
    this.__defineGetter__('name', function() { return $text.text(); });
    this.__defineSetter__('name', function(val) { $text.text(val); });

    this.name = name;

    var $textContainer = $.tip.$('<foreignObject/>');
    $textContainer.append($text);
    $(values.bbox).props().link($textContainer);

    this.$svg = $.tip.$('<g class="vertex"/>').append($border).append($textContainer);
    this.$svg.$border = $border; // ToDo: encapsulate better

    this.nearestJoin = function(to) {
        var delta = { x: this.center.x - to.x, y: this.center.y - to.y };
        var length = Math.sqrt(Math.pow(delta.x, 2) + Math.pow(delta.y, 2));

        if(length == 0) {
            return to;
        } else {
            return { x: this.center.x - delta.x * this.radius / length,
                     y: this.center.y - delta.y * this.radius / length };
        }
    }

    this.children = [];
    this.edges = [];
    this.addChild = function(node) {
        this.children.push(node);
        node.parent = this;

        var edge = new Edge(this, node);
        $.tip.$('#').append(edge.$svg);
        this.edges.push(edge);
    }

    this.toString = function() { return '[Vertex: ' + this.name + ' ' +
                                 '(' + this.center.x + ',' + this.center.y + ')]'; }
}

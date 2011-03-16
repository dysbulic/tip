function Blob(content) {
    var obj = this;

    var values = { bbox: { x: 0, y: 0, height: 100, width: 100 }};
    values.center = { get x() { return values.bbox.x + values.bbox.width / 2 },
                      set x(val) { values.bbox.x = val - values.bbox.width / 2 },
                      get y() { return values.bbox.y + values.bbox.height / 2 },
                      set y(val) { values.bbox.y = val - values.bbox.height / 2 } };

    $(this).expose(values);

    var $border = $.tip.$('<rect/>');
    $(values.bbox).props().link($border);

    var $text = $.tip.$('<html:p/>');
    this.__defineGetter__('content', function() { return $text.text(); });
    this.__defineSetter__('content', function(val) { $text.text(val); });

    this.content = content;
    
    // Chrome doesn't show with 'requredExtension'
    //var $textContainer = $.tip.$('<foreignObject requiredExtensions="http://www.w3.org/1999/xhtml"/>');
    var $textContainer = $.tip.$('<foreignObject/>');
    $textContainer.append($text);
    $(values.bbox).props().link($textContainer);

    this.$svg = $.tip.$('<g class="blob"/>').append($border).append($textContainer);
    this.$svg.$border = $border;

    this.nearestJoin = function(to) {
        // This is only works for the degenerate case in the current graphs
        if ( to.x < values.bbox.x ) {
            return { x: values.bbox.x, y: values.bbox.y + values.bbox.height / 2 };
        } else {
            return { x: values.bbox.x + values.bbox.height / 2, y: values.bbox.y };
        }
    }

    this.toString = function() { return '[Blob: ' + this.content + ']'; }
}

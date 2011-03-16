(function() {
    var rowNeighbor = [];
    var depth = 0;
    var currentVertex;
    var drawDown = true; // Draw tree down or to the right

    function receiveMessage(message) {
        var node;
        switch ( message.type ) {
        case 'enter':
            node = new Vertex(message.content);
            break;
        case 'text':
            node = new Blob(message.content);
            break;
        case 'exit':
            // Back up a level and exit
            depth--;
            if ( currentVertex.parent ) {
                currentVertex = currentVertex.parent;
            }
            return;
        default:
            throw 'Unknown message type: ' + message.type;
        }

        var neighbor = rowNeighbor[depth];
        if ( neighbor ) {
            // Avoid colliding with neighbors
            if( drawDown ) {
                // ToDo: link to make dynamic if elements move
                node.center.x = neighbor.bbox.x + node.bbox.width * 2;
            } else {
                node.center.y = neighbor.bbox.y + node.bbox.height * 2;
            }
        }
        if ( currentVertex ) {
            if( drawDown ) {
                node.center = { x: Math.max(node.center.x, currentVertex.center.x),
                                y: currentVertex.center.y + (currentVertex.bbox.height * 1.75) };
            } else {
                node.center = { x: currentVertex.center.x + (currentVertex.bbox.width * 1.75),
                                y: Math.max(node.center.y, currentVertex.center.y) };
            }
            currentVertex.addChild(node);
        }
        rowNeighbor[depth++] = node;

        $.tip.$('#').append(node.$svg);
        currentVertex = node;

        return node;
    }

    // Do a depth first traversal and set the positions
    function traverse(node, pre, post) {
        if( pre !== undefined ) {
            pre.call(node);
        }
        if( node.children && node.children.length > 0 ) {
            $.each(node.children, function(idx, child) {
                traverse(child, pre, post);
            });
        }
        if( post !== undefined ) {
            post.call(node);
        }
    }
    
    //$.tip.ready(function() {
    $(function() {
        var tree = [ { "html": [ { "head": [ { "title": "Greeting" } ] },
                                 { "body": [ { "p": "Hi" },
                                             { "p": "There" },
                                             { "p": "You" } ] }
                               ] } ];
        tree = [ { "html": [ { "head": [ { "title": "Greeting" } ],
                               "body": [ { "p": "Hi" },
                                         { "p": "There" },
                                         { "p": "You" } ]
                             } ] } ];
        
        function genTree(tree) {
            var node;
            $.each(tree, function(idx, elem) {
                $.each(elem, function(prop, val) {
                    node = receiveMessage(new Message('enter', prop));
                    if( $.isArray(val) ) {
                        genTree(val);
                    } else {
                        receiveMessage(new Message('text', val));
                        receiveMessage(new Message('exit'));
                    }
                    receiveMessage(new Message('exit'));
                });
            });
            return node;
        }

        var root = genTree(tree);

        // Center positions and calculate the bounding box
        var topLeft = root.bbox;
        var bottomRight = root.bbox;

        traverse(root, undefined, function() {
            if( this.children && this.children.length > 0 ) {
                var axis = drawDown ? 'x' : 'y';
                var delta = this.children[-1].center[axis] - this.children[0].center[axis];
                this.center[axis] = this.children[0].center[axis] + delta / 2;
            }
            
            topLeft = { x: Math.min(topLeft.x, this.bbox.x),
                        y: Math.min(topLeft.y, this.bbox.y) };
            bottomRight = { x: Math.max(bottomRight.x, this.bbox.x + this.bbox.width),
                            y: Math.max(bottomRight.y, this.bbox.y + this.bbox.height) };
        });

        var pad = 20;

        $.tip.$('#').attr('viewBox', ((topLeft.x - pad) + ' ' + (topLeft.y - pad) + ' ' +
                                      (bottomRight.x - topLeft.x + pad * 2) + ' ' +
                                      (bottomRight.y - topLeft.y + 150 + pad * 2)));

        var speed = 500;

        var $messages = $.tip.$('<g id="messages"/>').attr('transform', 'translate(0,' + (bottomRight.y + 50) + ')');
        $.tip.$('#').append($messages);
        var count = 0;
        var width = 75;
        var messageQueue = [], maxMessages = 7;
        function message(type, node) {
            $messages.animate({ transform: 'translate(+=' + (width + pad) + ', +=0)' }, { clear: false, duration: speed });
            var $message = $.tip.$('<g/>').attr('class', 'message ' + type)
                .attr('transform', 'translate(-' + (++count * pad + count * width) + ')')
                .append($.tip.$('<rect/>').attr({ width: width, height: 100, rx: 0.7, ry: 0.7 }))
                .append($.tip.$('<foreignObject/>').attr({ x: 0, y: 0, width: width, height: 100 })
                        .append($.tip.$('<html:h2/>').text(type && (type[0].toUpperCase() + type.substring(1))))
                        .append($.tip.$('<html:p/>').text(node))).hide();
            messageQueue.push($message);
            $messages.append($message);
            $message.fadeIn(speed);
            if( messageQueue.length >= maxMessages ) {
                // Chrome and Opera both return a 0 offset(Width|Height), making .is(':hidden') == true
                // so it isn't possible to use fadeOut()
                messageQueue[messageQueue.length - maxMessages].animate({ opacity: 0 }, speed);
            }
            return $message;
        }

        var hilightColor = 'rgb(252, 252, 164)';
        var treeActiveColor = '#7094FF';

        var $base = $.tip.$('<svg:g/>');

        traverse(root,
                 function() {
                     if( this.$svg.$border ) {
                         var $border = this.$svg.$border;
                         var name = this.name;
                         // Text nodes have no name and don't have an exit animation
                         var color = name ? treeActiveColor : $border.css('fill');
                         $base.queue(function() {
                             message(name && 'enter', name || 'text');
                             $border
                                 .animate({ fill: hilightColor }, speed / 2)
                                 .animate({ fill: color }, speed / 2,
                                          function() { $base.dequeue() });
                         });
                     }
                 },
                 function() {
                     if( this.$svg.$border ) {
                         var $border = this.$svg.$border;
                         var name = this.name;
                         var origColor = $border.css('fill');
                         $base.queue(function() {
                             // If not a text node
                             if( name ) {
                                 message('exit', name);
                                 $border
                                     .animate({ fill: hilightColor }, speed / 2)
                                     .animate({ fill: origColor }, speed / 2,
                                              function() { $base.dequeue() });
                             } else {
                                 $base.dequeue();
                             }
                                 
                         });
                     }
                 });
        
    });
})();

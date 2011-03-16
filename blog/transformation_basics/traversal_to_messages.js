(function() {
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
                    node = messageToTree(new Message('enter', prop));
                    if( $.isArray(val) ) {
                        genTree(val);
                    } else {
                        messageToTree(new Message('text', val));
                        messageToTree(new Message('exit'));
                    }
                    messageToTree(new Message('exit'));
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

        var pad = 10;

        $.tip.$('#').attr('viewBox', ((topLeft.x - pad) + ' ' + (topLeft.y - pad) + ' ' +
                                      (bottomRight.x - topLeft.x + pad * 2) + ' ' +
                                      (bottomRight.y - topLeft.y + 100 + pad * 4)));

        var speed = 500;

        var $messages = $.tip.$('<g id="messages"/>').attr('transform', 'translate(0,' + (bottomRight.y + 2 * pad) + ')');
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

        function genMessages() {
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
        }

        $.tip.$('#').append($.tip.$('<tip:button type="play" id="go"/>'));
        $.tip.button();
        $('#go').click(genMessages);
    });
})();

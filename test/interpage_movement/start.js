var tixel = new List( {
    name: 'time',
    data: 'hourglass icon.svg',
    child: new List( {
        spring: new List( { data: 'spring icon.svg' } ),
        summer: new List( { data: 'summer icon.svg' } ),
        autumn: new List( { data: 'fall icon.svg' } ),
        winter: new List( { data: 'winter icon.svg' } ),
    } ),
} );

window.__defineGetter__( 'size', function() {
    return {
        width: window.innerWidth,
        height: window.innerHeight,
    }
} );

window.__defineGetter__( 'center', function() {
    return {
        x: window.size.width / 2,
        y: window.size.height/ 2,
    }
} );

$(window).bind( 'load', function() {
    window.__defineGetter__( 'view', function() {
        var viewBox = $.__.$('#').attr( 'viewBox' ).split( /\s+/ );
        var box = new List( {
            origin: {
                x: parseFloat( viewBox[0] ),
                y: parseFloat( viewBox[1] ),
            },
            size: {
                width: viewBox[2],
                height: parseFloat( viewBox[3] ),
            },
        } );
        return box.merge( {
            center: {
                x: box.origin.x + box.size.width / 2,
                y: box.origin.y + box.size.height / 2,
            }
        } )
    } )
} )

/* Firefox 3.5 allows <use/> documents with external references, but
 * not <images/> Chrome allow the opposite, but improperly sizes the
 * images.
 */
function newImage( href, interface ) {
    var $base = $.__.$('<g/>');
    interface.set( '$base', $base );
    $.get( href, function( data ) {
        var $svg = $(document.adoptNode(data.documentElement));
        new List( ['x', 'y', 'width', 'height'] )
            .each( function(val) {
                if( $base.attr( val ) !== undefined ) {
                    $svg.attr( val, $image.attr( val ) );
                }
            });
        //$base.append( $svg );
        $svg.click( function() {
            alert( href );
        } );
        $.__.$('#').append( $svg );
        interface.set( '$frame', $svg );
        redraw();
    })
    return $base
}

function Agent( resource ) {
    var interface = new List( );
    var $frame = newImage( item.get( 'data' ), interface );
    interface.merge( {
        $item: $.__.$('<' + tags.item + '/>').attr( {
            id: name,
        } ).append(
            interface.$base
        ),
    } );
    interface.$base.bind( 'load', function() {
        var win = $(this).get(0).contentWindow;
        interface.set( 'win', win );
        if( win && win.agents && win.agents.register ) {
            win.agents.register( interface );
        }
    } );
}

$(window).bind( 'load', function() {
    var tags = ( { html: { frame: 'iframe',
                           item: 'li',
                           list: 'ol',
                         },
                   svg: { frame: 'image',
                          item: 'g',
                          list: 'g',
                        } } )[ $.__.$('#').get(0).nodeName ];
    tixel.transform( {
        pre: function( item, result ) {
            if( item.get( 'data' ) ) {
                result.add( new Agent( item.get( 'data' ) ) )
            }
            if( item.child ) {
                result.context.push( new Edge() );
                result.context.push( new () );

            }
        },
        post: function( item, result ) {
            if( item.get( 'data' ) ) {
                
            }
        },
    } );
    tixel.traverse( function( item, depth ) {
        if( item.child ) {
            var $list = $.__.$('<' + tags.list + '/>');
            item.interface.$item.append( $list );
            item.child.each( function( child ) {
                $list.append( child.interface.$item );
            } );
        }
    } );
    var $tixel = $.__.$('<' + tags.list + '/>').append(
        tixel.deref( 'interface.$item' )
    );
    $.__.$('#').append( $tixel );
} );

var loopCount = 0;
function redraw( ) {
    ++loopCount;
    tixel.traverse( function draw( item, trav ) {
        if( item.interface && item.interface.$frame ) {
            var css = new List();
            var size = new List( {
                width: 300, height: 300,
            } );
            if( trav.index == 1 && trav.depth == 1 ) {
                css.merge( {
                    x: window.view.center.x - size.width / 2,
                    y: window.view.center.y - size.height / 2,
                    width: size.width,
                    height: size.height,
                } );
            } else {
                var radius = 300;
                var angle = 3 * Math.PI / 4;
                var theta = ( -angle / 2
                              + angle * (trav.index - 1) / 4
                              + 2 * Math.PI * loopCount / 128 );
                var small = size.div( 2 );
                var center = {
                    x: window.view.center.x + radius * Math.cos( theta ),
                    y: window.view.center.y + radius * Math.sin( theta ),
                };
                css.merge( {
                    x: center.x - small.width / 2,
                    y: center.y - small.height / 2,
                    width: small.width,
                    height: small.height,
                } );
            }
            item.interface.$frame.attr( css.asMap );
        }
    } );

    var state = new List( {
        __: window.__,
        me: new List( {
            name: 'hub:' + window.__.timestamp,
            size: window.size,
            center: window.center,
        } ),
        you: new List( {
            size: new List( window.size ).div( 2 ),
            center: window.center,
            digit: 5,
        } ),
    } );
    //win.negotiate.frame( state );
    //console.log('☸:' + state.deref( 'you.' ));
}

$(window).bind( 'load', function() {
    if( true ) {
        $.__.$('#').click( redraw );
    } else {
        var interval = setInterval( redraw, 10 );
        $.__.$('#').click( function clk() {
            clearInterval( interval );
            $(this).unbind( 'click', clk );
            $(this).click( redraw );
        } );
    }
} );

/*
var wins = new List();
var ports = new List();
wins.on( 'set', function( id, win ) {
    ports.set( id, new DocumentPort( win ) );
} );

var dirs = new List( {
    up: '↑',
    down: '↓',
    left: '←',
    right: '→'
} );
window.init = function init( win ) {
    win.stamp = win.stamp || window.timestamp;
    if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
        console.log( '☸:' + win.stamp );
    }
    
    var id = wins.id( { stamp: win.stamp } );
    if( id ) {
        win.setBitField( ({
            up: '1100010',
            down: '0011100',
            left: '0000111',
            right: '0110001',
        })[ id ] );
        var port = ports.get( id );
        var view = port.get( 'xml:#@viewBox:x([^ ]+)y([^ ]+)width([^ ]+)height([^ ]+)' );
    }
}

function add( map ) {
    map.each( function( key, id ) {
        // Must differ from location.pathname in Firefox
        var path = 'seven segment.svg';
        var $frame = $('<iframe/>').attr( {
            id: id,
            src: path
        } );
        $frame.bind( 'load', function() {
            var win = $(this).get(0).contentWindow;
            wins.set( id, win );
            init( win );
        } );
        $('body').append( $frame );
    } );
}
$( function() {
    if( window.parent == window ) {
        //add( dirs );
    } else if( typeof window.parent.init == 'function' ) {
                window.parent.init( window );
    } else {
        $('body').append( $('<p/>').text( '☠' ) );
    }
} ); 
*/

if( window.__ === undefined ) {
    var tiproot = new RouterPort();
    window.__defineGetter__( '__', function() { return tiproot } );
    if( ! __.silent && typeof console != 'undefined' ) console.log( '☸:set:__' );
}

function RouterPort() {
    var points = {};
    
    function route( msg, point ) {
        if( ! __.silent ) point.log( '☸:msg:' + msg );

        var key = new MutableString( msg );
        if( key.chomp( /(☸):/ ) ) {
            var token = new MutableString( key['::'] || key );
            var type = new MutableString( token[':'] );
            if( type.empty && token.empty ) {
                DefaultScript( point );
            } else {
                console.log( '☸:route:' + type + ':type:' + points[ type ] );
                while( typeof points[ type ] == 'string' ) {
                    type = points[ type ];
                }
                if( points[ type ] ) {
                    var out = ( typeof points[ type ].pass == 'function'
                                && points[ type ].pass( this, [ point, token, key ] )
                                || points[ type ] );
                    if( out && typeof out.apply == "function" ) {
                        out.apply( out, [ point, token, key ] );
                    }
                }
            }
        }
    }
    this.route = route;

    route.add = function( name, action ) {
        if( ! __.silent && typeof console != 'undefined' ) console.log( '☸:route:add:' + name );
        points[ name ] = action;
    }

    route.get = function( name ) {
        return points[ name ];
    }

    route.map = function( name ) {
        return points[ name ];
    }
    
    function connect( point ) {
        point.onMessage( route );
        point.msg = point.asString + '::' + this.asString + ':helo';
        return point;
    }
    this.connect = connect;

    //get asString() { return 'α' },
    this.__defineGetter__( 'asString', function() { return 'α' } );
}

if( ! __.silent && typeof console != 'undefined' ) console.log( '⊛:helo' );

window.addEventListener( 'connect', function( evt ) {
    __.connect( { point: evt.points[0],
                  listeners: [],
                  set post( msg ) { point.postMessage( msg ) },
                  onMessage: function( l ) {
                      listeners.push( l );
                  },
                  listener: function( msg ) {
                      listeners.each( [ msg.data ] );
                  }
                } );
    __.connect.point.addEventListener( 'message', connect.listener, false );
    __.connect.point.start();
},
false );

window.addEventListener( 'message', function( msg ) {
    if( ! __.silent && typeof console != 'undefined' ) {
        console.log( '⊛:msg:' + msg.data );
    }
},
false );

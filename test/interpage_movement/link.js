__.entangle = function() {
    var args = Array.prototype.slice.call( arguments );
    var out = {};
    
    args.each( function( arg ) {
        var obj = arg;
        for( var prop in arg ) {
            if( typeof obj[ prop ] == 'function' ) {
                out[ prop ] = function() {
                    return arguments.callee.orig
                        .apply( out, arguments );
                }
                out[ prop ].prop = prop;
                out[ prop ].orig = obj[ prop ];
            } else {
                var get = obj.__lookupGetter__( prop );
                var set = obj.__lookupSetter__( prop );
                
                if( ! __.silent && console.log != 'undefined' ) {
                    console.log( 'θ:link:' + prop + ':'
                                 + ':get:∃:' + ( get !== undefined )
                                 + ':set:∃:' + ( set !== undefined )
                                 + ':val:∃:' + ( obj[ prop ] !== undefined ) );
                }
                
                if( ! get && ! set ) {
                    get = function( ) { 
                        var obj = arguments.callee.obj;
                        var prop = arguments.callee.prop;
                        return obj[ prop ]
                    };
                    set = function( val ) { 
                        var obj = arguments.callee.obj;
                        var prop = arguments.callee.prop;
                        obj[ prop ] = val;
                    };
                    [ get, set ].each( function( spec ) {
                        spec.obj = obj;
                        spec.prop = prop;
                    } );
                }
                if( get ) {
                    out.__defineGetter__( prop, get );
                }
                if( set ) {
                    out.__defineSetter__( prop, set );
                }
            }
        }
    } );
    return out;
}

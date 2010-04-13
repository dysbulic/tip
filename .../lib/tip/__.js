( function() {
    var store = {
        get : function( key ) {
            var str = localStorage[ key ]
            return JSON.parse( val )
        },
        set : function( key, val ) {
            var str = JSON.stringify( val )
            localStorage[ key ] = str
        },
        let : function( key, val ) {
            if( get( key ) === undefined ) {
                set( key, val )
            }
        },
    }
    if( window.__lookupGetter__( '__' ) === undefined ) {
        window.__defineGetter__( '__', function() {
            return store
        } )
    }
} )()

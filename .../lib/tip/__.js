( function() {
    var store = {
    }
    if( window.__lookupGetter__( '__' ) === undefined ) {
        window.__defineGetter__( '__', function() {
            return store
        } )
    }
} )()

function Pointer( store, get, set ) {
    this.__defineGetter__( 'val', function() {
        return get.apply( store, arguments )
    } )
    this.__defineSetter__( 'val', function() {
        return set.apply( store, arguments )
    } )
}

function List( init ) {
    var keys = []
    var positions = {}
    var store = []

    function add( val, key ) {
        // New key
        if( positions[ key ] === undefined ) {
            var idx = store.push( val )
            keys.push( key )
            console.log( idx )
        }
    }
    this.add = add

    function get( key ) {
        return store[ position[ key ] ] 
    }
    this.get = get

    function each( f ) {
        var self = this
        keys.each( function( key ) {
            f.apply( self, [ key, get( key ) ] )
        } )
    }
    this.each = each

    // Getters and setters are copied
    for( prop in init ) {
        var get = init.__lookupGetter__( prop )
        var set = init.__lookupSetter__( prop )

        if( get || set ) {
            var ptr = new Pointer( store, get, set )
            this.__defineGetter__( prop, function() { return ptr.val } )
            this.__defineSetter__( prop, function( val ) { return ptr.val = val } )
        } else {
            add( init[ prop ], prop )
        }
    }
}

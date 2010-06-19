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
    var position = {}
    var store = []
    var self = this

    function add( val, key ) {
        if( position[ key ] === undefined ) { // New key
            keys.push( key )
            position[ key ] = store.push( val ) - 1
            self.__defineGetter__( key, function() { return get( key ) } )
            self.__defineSetter__( key, function( val ) { return set( key, val ) } )
        }
 
    }
    this.add = add

    function get( key ) {
        return store[ position[ key ] ]
    }
    this.get = get

    function set( key, val ) {
        return store[ position[ key ] ] = val
    }
    this.get = get

    function vals() {
        var vals = []
        each( function( val, key ) {
            vals.push( val )
        } )
        return vals
    }
    this.vals = vals

    function join( sep ) {
        return vals().join( sep )
    }
    this.join = join

    function each( f ) {
        var self = this
        console.log( typeof get )
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

List.by = {
    ids : function( ids ) {
        var list = new List
        ids.each( function( id ) {
            list.add( null, id )
        } )
        return list
    },
}

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
    self.add = add

    function get( key ) {
        return store[ position[ key ] ]
    }
    self.get = get

    function set( key, val ) {
        return store[ position[ key ] ] = val
    }
    self.set = set

    function vals() {
        var vals = []
        each( function( val, key ) {
            vals.push( val )
        } )
        return vals
    }
    self.vals = vals

    function join( sep ) {
        return vals().join( sep )
    }
    self.join = join

    function each( f ) {
        var selfʻ = this
        keys.each( function( key ) {
            f.apply( selfʻ, [ key, self.get( key ) ] )
        } )
    }
    this.each = each

    for( prop in init ) {
        // Indexed from 1
        if( init instanceof Array
            && ( prop instanceof Number || typeof prop == 'number'
                 || ( typeof prop == 'string' && /[+-]*[0-9]/.test( prop ) ) ) ) {
            prop = parseInt( prop ) + 1
        }
        if( init.hasOwnProperty( prop ) ) {
            var get = init.__lookupGetter__( prop )
            var set = init.__lookupSetter__( prop )
            
            // Getters and setters are copied
            if( get || set ) {
                var ptr = new Pointer( store, get, set )
                this.__defineGetter__( prop, function() { return ptr.val } )
                this.__defineSetter__( prop, function( val ) { return ptr.val = val } )
            } else {
                add( init[ prop ], prop )
            }
        }
    }
}

var list = new List
list.add( 3, 'test' )
console.log( list.test )

List.by = {
    ids : function( ids ) {
        var list = new List
        ids.each( function( id ) {
            list.add( null, id )
        } )
        return list
    },
}

List.as = {
    List : function() {
        var val = ( arguments.length == 0
                    ? undefined
                    : ( arguments.length == 1
                        ? arguments[ 0 ]
                        : Array.prototype.slice.apply( this, arguments ) ) )
        if( val instanceof List ) return val
        if ( val === undefined || val === null || val === true || val === false )
            return val
        val = ( val instanceof Number || val instanceof String ) ? [ val ] : val
        if( val instanceof Array || val instanceof Object )
            return new List( val )
        throw "Wha' Happen?"
    },
}

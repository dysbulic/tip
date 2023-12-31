( function() {
    Array.prototype.each = function each( f, args ) {
        if( typeof f == 'function' ) {
            for( var i = 0; i < this.length; i++ ) {
                ( function( item, args ) {
                    f.apply( this, [ item, i, args ] )
                } ).apply(
                    this,
                    [ this[i], args ]
                )
            }
        }
    }

    Array.prototype.hcae = function( f ) {
        if( typeof f == 'function' ) {
            for( var i = this.length; i >= 0; i-- ) {
                ( function( item, args ) {
                    f.apply( this, [ item, i, args ] )
                } ).apply(
                    this,
                    [ this[i], args ]
                )
            }
        }
    }

    String.prototype.each = Array.prototype.each
} )()

Array.prototype.__defineGetter__( 'empty', function() {
    return this.length == 0
} )

//Array.prototype.__defineGetter__( 1, function() {
//    return ! this.empty && this[ 0 ]
//} )

Array.prototype.__defineGetter__( -1, function() {
    return this.length > 0 && this[ this.length - 1 ] || undefined
} )

Array.prototype.__defineSetter__( -1, function( val ) {
    if( ! this.empty ) {
        this[ this.length - 1 ] = val
    }
} )

//Array.prototype.clone = function() { return this.slice(0) };

;( function() {
    var hasOwnProperty = Array.prototype.hasOwnProperty
    var props = {}
    for( prop in Array.prototype ) {
        props[ prop ] = true
    }
    Array.prototype.hasOwnProperty = function( prop ) {
        return ( this[ prop ]
                 && props[ prop ] !== true
                 && hasOwnProperty.apply( this, arguments ) )
    }
} )()

if( NodeList.prototype != null ) {
    NodeList.prototype.each = Array.prototype.each
}

if( NamedNodeMap.prototype != null ) {
    NamedNodeMap.prototype.each = function( f ) {
        Array.prototype.each.apply( this, [ function( attr, idx ) {
            f.apply( attr, [ attr.nodeValue, attr.name ] )
        } ] )
    }
}

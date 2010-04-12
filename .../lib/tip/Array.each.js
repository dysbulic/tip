Array.prototype.each = function( f, args ) {
    if( typeof f == 'function' ) {
        for( var i = 0; i < this.length; i++ ) {
            ( function( item, args ) {
                var scope = f.scope || item;
                f.apply( scope, [ item, i, args ] );
            } ).apply(
                this,
                [ this[i], args ]
            )
        }
    }
    if( f instanceof Array ) {
        this.each( function( val, idx ) {
            if( val && val.apply ) {
                var scope = val.scope || scope || this;
                val.apply( scope, f );
            }
        } )
    }
}

Array.prototype.hcae = function( f ) {
    if( f.apply ) {
        for( var i = this.length; i >= 0; i-- ) {
            var scope = f.scope || scope || this;
            f.apply( scope, [ this[i], i, this ] );
        }
    }
    if( f instanceof Array ) {
        this.hcae( function( val, idx ) {
            if( val && typeof val == 'function' ) {
                var scope = val.scope || scope || this;
                val.apply( scope, f );
            }
        } )
    }
}

Array.prototype.__defineGetter__( 'empty', function() {
    return this.length === 0;
} );

Array.prototype.__defineGetter__( -1, function() {
    return this.length > 0 && this[this.length - 1] || undefined;
} );

Array.prototype.__defineSetter__( -1, function( val ) {
    if( ! this.empty ) {
        this[ this.length - 1 ] = val;        
    }
} );

//Array.prototype.clone = function() { return this.slice(0) };

NodeList.prototype.each = Array.prototype.each;

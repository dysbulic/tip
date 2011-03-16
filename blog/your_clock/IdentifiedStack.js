function IdentifiedStack( init ) {
    this.map = {};
    this.list = [];
    
    init = init || {};
    
    this.add = function( map ) {
        if( map.timestamp && map.timestamp.call ) {
            var id = map.timestamp.call( this );
            
            this.contents[ id ] = map;
        }
        return id;
    }

    this.get = function( key ) {
        return this.contents[ key ];
    }

    this.each = function( f ) {
        $.each( this.list, f );
    }

    var push = this.push;
    this.push = function( val ) {
        //push( val );
        this.list.push( val );
        var idx = this.list.length;
        this.map[ idx ] = val;
        return idx;
    }
}
IdentifiedStack.prototype = new Array;

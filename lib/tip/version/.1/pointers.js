function Pointer( obj ) {
    this.__defineGetter__( 'val', function() {
        return obj instanceof Pointer ? obj.val : obj
    } )
}

function MapPointer( map, key ) {
    this.valueOf = function() {
        return map[ key ]
    }
}

function AccessPointer( val, key ) {
    var get = val.__lookupGetter__( key );
    var set = val.__lookupSetter__( key );

    if( get && set ) {
        this.__defineGetter__( 'val', function() {
            return get.apply( this, arguments );
        } );
        this.viable = true;
    }
}
AccessPointer.prototype = new Pointer();

function UnknownPointer( key ) {
    this.__defineGetter__( 'val', function() {
        console.log( 'unknown:' + key );
        return undefined;
    } );
}
UnknownPointer.prototype = new Pointer();

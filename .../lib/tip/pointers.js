function Pointer( obj ) {
    this.__defineGetter__( 'val', function() {
        return obj instanceof Pointer ? obj.val : obj
    } )
}

function MapPointer( map, key ) {
    var pMap = new Pointer( map );
    var pKey = new Pointer( key );
    this.__defineGetter__( 'val', function() {
        var ret = ( pMap.val instanceof List
                    ? pMap.val.get( pKey.val )
                    : ( pKey.val !== undefined
                        && pMap.val[ pKey.val ] ) )
        if( ret instanceof Pointer ) {
            ret = ret.val
        }
        return ret
    } )
    
    this.__defineGetter__( 'viable', function() {
        return ( pMap.val
                 && pMap.val[ pKey.val ] !== undefined )
        } )
}
MapPointer.prototype = new Pointer();

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

( function() {
    var mirrors = {};

    __.route.add( 'point',
                  function( point, token, key ) {
                      var id = token['::'];
                      mirrors[ id ] = mirrors[ id ] || new Mirror( token );
                      mirrors[ id ].add( point, token, key );
                  } );
    __.route.map( '⭑', 'point' );
    
    __.points = __.points || new IdentifiedStack();
    
    function Mirror( key ) {
        var points = new IdentifiedStack();

        this.add = function( mirror, token, key ) {
            if( typeof console != 'undefined' ) console.log( '⭑:' + mirror.asString + ":" + token );
            points.add( mirror.uid, mirror );
            mirror.onMessage( function( msg ) {
                points.each( function( point ) {
                    if( point != mirror ) {
                        point.msg = msg;
                    }
                } );
            } );
        }
        
        this.apply = function() {
        }
        
        this.enter = function( key, cache ) {
            this.ids[ key ] = cache;
        }
    }
} )();

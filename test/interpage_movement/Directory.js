__.dirs = __.dirs || new IdentifiedStack();

__.scripts.add( 'dir:', function( point, path ) {
    var found = false, done = false;
    while( ! done ) {
        point.log('t:path:' + path);
        done = true;
        __.dirs.each( function( dir, key ) {
            var regex = new RegExp( '(' + key + ')(:|$)' );
            if( ! found && path.chomp( regex ) ) {
                found = true;
                point.log('t:d:k:' + dir + ":" + key);
                if( typeof dir == "string"
                    || dir instanceof String ) {
                    path.prepend( dir );
                    done = false;
                    found = false;
                } else {
                    map( point, dir );
                }
            }
        } );
        if( ! found && path.chomp( 'close:' ) ) {
            window.close();
        }
    }
    if( ! found ) {
        map( point, __.dirs.get( ( Math.random() < .5
                                  ? __.coin.toss.head 
                                  : __.coin.toss.tail ) ) );
    }
} );

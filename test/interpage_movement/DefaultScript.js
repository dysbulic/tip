__.scripts.add( 'default', DefaultScript );

function DefaultScript( point ) {
    point.msg = 'clear';
    map( point, __.directions.corporeal );
}

function map( point, elems ) {
    var str = ( elems.asString && elems.asString
                || JSON.stringify( elems ) );
    point.msg = 'add:' + str;
    var positions = new IdentifiedStack( {
        up: '50%0%', down: '50%,-0%',
        left: '0%,50%', right: '-0%,50%'
    } );
    elems.each( function( dir, idx ) {
        var addr = 'id:' + idx + ':';
        if( positions[idx] !== undefined ) {
            point.msg = addr + 'pos:' + positions[idx];
        }
        point.msg = addr + 'go:new:win:☸:script:dir:' + dir;
    } );
    [ 'up', 'down' ].each( function( val ) {
        var addr = 'id:' + val + ':';
        point.msg = addr + 'css:margin-left:-.5em'; 
    } );
    [ 'left', 'right' ].each( function( val ) {
        var addr = 'id:' + val + ':';
        point.msg = addr + 'css:margin-top:-.5em'; 
    } );
}

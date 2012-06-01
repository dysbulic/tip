var id = 'doc:' + window.__.timestamp;

window.negotiate = window.negotiate || new List();
window.negotiate.frame = function( state ) {
    state.swap( 'me', 'you' );
    //console.log( '⪋:' + state.get( 'me' ).get( 'center' ));
    state.merge( { me: {
        name: id,
    } } );
    state.swap( 'me', 'you' );
}
window.negotiate.frame = function() {
    return new List( {
        name: id,
        win: window,
    } );
}

function DocumentPort() {
    
}

$( function() {
});

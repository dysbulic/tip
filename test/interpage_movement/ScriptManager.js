( function() {
    var scripts = new IdentifiedStack();
    var id = '䷀';

    __.scripts = {
        get asString() { return id },
        add: function( name, script ) {
            scripts.add( script, name );
        },
        get: scripts.get
    };

    __.route.add( 'script',
                  function( token, point ) {
                      if( ! __.silent ) point.log( id + ':' + token );
                      if( token.chomp( 'add:' ) ) {
                          var name = token[':'];
                          var json = token['{}'];
                          if( name && json ) {
                              __.scripts.add( name,
                                              JSON.parse( json.asString ) )
                          }
                      }
                      for( var done = false;
                           ! done; ) {
                          done = true;
                          scripts.each( function( script, key ) {
                              if( token.chomp( key ) ) {
                                  if( script && typeof script.apply == "function" ) {
                                      if( ! __.silent ) point.log( id + ':' + token + ':apply' );
                                      done = done && script.apply( script, [ point, token, key ] );
                                  }
                              }
                          } );
                      }
                  } );
} ) ();

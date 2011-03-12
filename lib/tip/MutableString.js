function MutableString( str ) {
    if( str instanceof MutableString ) {
        str = str.asString;
    } if( str === false || str === undefined ) {
        str = "";
    }
    
    this.chomp = function( lead ) {
        if( typeof lead == "number" ) {
            var old = str;
            str = str.substring( lead );
            return new MutableString( old );
        } else if( typeof lead == "string"
                   || lead instanceof String ) {
            if( lead == str.substring( 0, lead.length ) ) {
                return this.chomp( lead.length );
            }
        } else if( lead.exec !== undefined ) {
            var match = lead.exec( str );
            if( match ) {
                str = str.replace( lead, "" );
                return new MutableString( match[1] );
            }
        } else {
            if( typeof console != 'undefined' ) {
                console.log( "MutableString:comp:type:" + typeof lead );
            }
        }
        return false;
    }

    this.prepend = function( prefix ) {
        str = prefix + str;
    }

    this.append = function( suffix ) {
        str += suffix;
    }

    this.__defineGetter__( 'clone', function() {
        return new MutableString( str );
    } );

    this.__defineGetter__( 'asString', function() {
        return str;
    } );

    this.toString = function() { return this.asString; }
    this.toJSON = function() { return this.asString.toJSON(); }
    this.valueOf = function() { return this.asString; }

    this.seek = function( char ) {
        var idx = 0;
        while( idx < str.length && str[ ++idx ] != char );
        return ( str[ idx ] == char
                 ? this.chomp( idx - 1 )
                 : null );
    }
    
    this.find = function() {
        // seek + 1
        throw 'undefined';
    }

    this.__defineGetter__( ':', function() {
        return this.chomp( /([^:]+)(:|␄)/ );
    } );

    this.__defineGetter__( '.', function() {
        return this.chomp( /([^\.]+)(\.|␄|$)/ );
    } );

    this.__defineGetter__( '::', function() {
        return this.chomp( /^(.*?)(::|␄)/ );
    } );

    this.__defineGetter__( 'empty', function() {
        return str.length == 0;
    } );
    
    this.__defineGetter__( '(', function() {
        return this.seek( '(' );
    } );
                           
    function nest( open, close ) {
        if( str[0] == open ) {
            var depth = 1;
            for( var idx = 1; idx < str.length; idx++ ) {
                switch( str.charAt( idx ) ) {
                case open:
                    depth++;
                    break;
                case close:
                    depth--;
                    if( depth == 0 ) {
                        return this.chomp( idx );
                    }
                    break;
                }
            }
        }
        return false;
    }

    this.__defineGetter__( '{}', function() {
        return nest( '{', '}' );
    } );

    this.__defineGetter__( '()', function() {
        return nest( '(', ')' );
    } );

    this.__defineGetter__( '1', function() {
        return str[ 0 ];
    } );

    this.__defineGetter__( '-1', function() {
        return str[ str.length - 1 ];
    } );
}
MutableString.prototype = new String;

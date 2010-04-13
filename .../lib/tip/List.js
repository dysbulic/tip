if( ! Array.prototype.each ) {
    if( typeof console != 'undefined' ) console.log( "missing:Array.each" );
}

function List( ) {
    var keys = [];
    var itms = {};
    var store = this;
    var id = 0;
    var self = this;

    function add( itm, uid ) {
        var key = uid || id++;
        if( keys.indexOf( key ) < 0 ) {
            ptr( key, itm );
            keys.push( key );
            //console.log('a:'+key+':'+get(key));
        }
        return key;
    }

    function ptr( key, itm ) {
        if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
            console.log( 'θ:⒧:' + key + ' ⬌ ' + itm );
        }
        var getter = function() {
            var key = arguments.callee.key
            return get( key )
        }
        getter.key = key
        self.__defineGetter__( key, getter );
        return itms[ key ] = new Pointer( itm );
    }
    
    function swap( a, b ) {
        var tmp = itms[ a ];
        itms[ a ] = itms[ b ];
        itms[ b ] = tmp;
        return this;
    }

    function merge( obj ) {
        if( obj !== undefined ) {
            if( typeof obj == 'string' && obj.test( /^{.*}$/ )  ) {
                obj = JSON.parse( obj );
            }
            if( obj.each ) {
                obj.each( function( val, key ) {
                    set.apply( self, [ key, val ] )
                } )
            } else {
                for( prop in obj ) {
                    var cur = get( prop )
                    if( cur instanceof List ) {
                        cur.merge.apply( cur, [ obj[ prop ] ] )
                    } else {
                        set.apply( self, [ prop, obj[prop] ] )
                    }
                }
            }
        }
        return this
    }
    merge.apply( this, arguments );

    function entangle( obj ) {
        if( obj !== undefined ) {
            if( typeof obj == 'string' && obj.test( /^{.*}$/ )  ) {
                obj = JSON.parse( obj );
            }
            if( obj.each ) {
                obj.each( function( val, key ) {
                    var ptr = new MapPointer( obj, key );
                    add( ptr, key );
                } );
            } else {
                for( prop in obj ) {
                    var cur = get( prop );
                    if( cur instanceof List ) {
                        cur.merge( obj[ prop ] );
                    } else {
                        var ptr = new MapPointer( obj, prop );
                        add( ptr, prop );
                    }
                }
            }
        }
        return this
    }
    
    function join() {
        throw undefined
    }

    function on( key, f ) {
        var orig = this[ key ];
        this[ key ] = function() {
            var self = arguments.callee;
            var args = Array.prototype.slice.call( arguments );

            if( f.pre ) f.pre.apply( this, arguments );
            if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
                console.log( self.asString + ':' + key );
            }
            var ret = orig.apply( this, arguments );
            if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
                console.log( self.asString + 'ʹ:' + key + ':' + ret );
            }
            try {
                args.push( ret );
                f.apply( this, args );
            } catch( e ) {
                if( e && e['return'] ) {
                    ret = e['return'];
                } else {
                    throw e;
                }
            }
            return ret;
        }
        this[ key ].__defineGetter__( 'asString', function() {
            return orig.asString + 'ʹ';
        } );
    }

    function deref( id, regex ) {
        regex = regex || new RegExp( '^([^\.]+)\.' );
        return get( id, regex )
    }

    function get( id, regex ) {
        if( regex ) {
            var path = new MutableString( id );
            var step = get( path.chomp( regex ) );
            if( step && step.get ) {
                return step.get( path, regex );
            }
        }

        var ptr;
        if( typeof id == 'number' ) {
            if( id > 0 ) {
                id = id - 1      // index to offset
            } else if( id < 0 ) {
                id += this.count // end-based index
            }
            ptr = new MapPointer( itms, keys[ id ] );
        } else if( typeof id == 'string' ||
                   id instanceof String ) {
            ptr = itms[ id ];
        }

        var itm = ptr !== undefined ? ptr.val : undefined 
        return itm
    }
    
    function set( key, itm ) {
        var self = arguments.callee;
        if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
            console.log( self.asString + ':' + key + ':' + itm );
        }
        var uid = add( itm, key );
        if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
            console.log( self.asString
                         + ':' + key + ':' + itm
                         + '=' + uid + ':' + ( get
                                               ? get( uid )
                                               : '¬get'
                                             )
                       )
        }
        return this;
    }

    set.__defineGetter__( 'asString', function() { return 'θ:⒧:↧'; } );
    
    function let( key, itm ) {
        if( this.get( key ) === undefined ) {
            this.set( key, itm );
        }
    }

    function each( f ) {
        keys.each( function( key, idx ) {
            if( key !== undefined ) {
                f.apply( this, [ get( key ), key ] )
            }
        } );
    }

    // true if each relationship and value in a is in b
    function isSubobjectOf( a, b ) {
        var found = true;
        if( typeof a == 'undefined' && typeof b == 'undefined' ) {
            // nothing matches itself
        } else if( typeof a == 'object' && typeof b == 'object' ) {
            for( var prop in a ) {
                found = found && isSubobjectOf( a[ prop ],
                                                b[ prop ] );
                if( ! found ) break;
            }
        } else if( typeof a == 'function' && typeof b == 'function' ) {
            // functions always match
        } else {
            found = found && a == b;
        }
        return found;
    }

    this.id = function( key ) {
        try {
            this.each( function( obj, id ) {
                if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
                    console.log( 'θ:⒧:⊆:⇄' + id );
                }
                if( isSubobjectOf( key, obj ) ) {
                    throw id;
                }
            } );
        } catch( e ) {
            if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
                console.log( 'θ:⒧:⊆:e:⇄:' + e );
            }
            return e;
        }
        return undefined;
    }

    this.trans = function( func ) {
        var next = {};
        this.each( function() {
            func.apply( next, arguments )
        } );
        return next;
    }

    this.map = function( func, type ) {
        type = type || 'straight';
        return this.map[ type ].apply( this, arguments );
    }

    this.map.straight = function( func ) {
        var out = new List();
        this.each( function( val, key ) {
            out.set( key, func.apply( this, [ val, key ] ) )
        } );
        return out;
    }

    this.map.tangle = function( func ) {
        var out = new List();
        this.each( function( val, key ) {
            if( typeof val == 'function' ) {
                out.set( key, val );
            } else {
                out.set( key, func.apply( this, [ val, key ] ) )
            }
        } );
        return out;
    }

    // ToDo:
    this.map.mirror = function( func ) {
        var out = new List();
        var self = this;
        this.each( function( val, key ) {
            var get = self.__lookupGetter__( key );
            var set = self.__lookupSetter__( key );
            if( get && set ) {
                out.__defineGetter__( get );
                out.__defineSetter__( set );
            } else {
                out.set( key, func.apply( self, [ val, key ] ) )
            }
        } );
        return out;
    }

    this.div = function( num ) {
        return this.map( function( val ) {
            return typeof val == 'number' && val / num || val
        }, 'mirror' )
    }
    
    var exports = {
        add : add,
        on : on,
        swap : swap,
        merge : merge,
        deref : deref,
        get : get,
        set : set,
        let : let,
        each : each,
        get count() { return keys.length },
        get vals() {
            var vals = []
            this.each( function( val, key ) {
                vals.push( val )
            } )
            return vals
        },
        get clone() { return new List( this ) },
        get asMap() {
            var map = {}
            this.each( function( itm, key ) {
                map[key] = itm
            } )
            return map
        },
        set top( itm ) { this.add( itm ); return this },
        get top( ) { return this.get( -1 ) },
        get pop( ) {
            var out = this.get( keys.pop() )
            return out
        },
        get asString() {
            try {
                return 'List'; //JSON.stringify( itmLst )
            } catch(e) {
                console.log( '!:θ:⒧:asString:' )
            }
        },
        traverse : traverse,
    }
    this.__defineGetter__( '__', function() { return exports } )

    // Checks if a variable with
    // the given name is defined
    function exists( variable ) {
        var key = new MutableString( variable );
        var baseId = key['.'];
        eval( 'var val = ' + baseId );
        console.log( 'p:' + baseId + val );
        while( val && ( prop = key['.'] ) ) {
            val = val[ prop ];
        }
        return key.empty && val !== undefined;
    }

    function traverse( f, depth, index ) {
        depth = depth || 1;
        index = index || 1;
        f( self, { depth: depth, index: index } );
        var subindex = 0;
        this.each( function( item, key ) {
            if( item instanceof List ) {
                item.traverse( f, depth + 1, ++subindex )
            }
        } );
    }
}
List.prototype = new Array

NamedNodeMap.prototype.each = function( f ) {
    Array.prototype.each.apply( this, [ function( attr, idx ) {
        f.apply( attr, [ attr.nodeValue, attr.name ] )
    } ] )
}

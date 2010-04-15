if( ! Array.prototype.each ) {
    if( typeof console != 'undefined' ) console.log( "missing:Array.each" )
}

function List( ) {
    var keys = []
    var itms = {}
    var store = this
    var id = 0
    var count = 0

    function add( itm, uid ) {
        var key = uid !== undefined ? uid : ++id
        if( keys.indexOf( key ) < 0 ) {
            ptr( key, itm )
            keys.push( key )
            ++count
            //console.log('a:'+key+':'+get(key));
        }
        return key
    }

    function ptr( key, itm ) {
        if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
            console.log( 'θ:⒧:' + key + ' ⬌ ' + itm )
        }
        var getter = function() {
            var key = arguments.callee.key
            return get( key )
        }
        getter.key = key
        store.__defineGetter__( key, getter )
        return itms[ key ] = new Pointer( itm )
    }
    
    function swap( a, b ) {
        var tmp = itms[ a ]
        itms[ a ] = itms[ b ]
        itms[ b ] = tmp
        return this
    }

    function merge( obj ) {
        if( obj !== undefined ) {
            if( typeof obj == 'string' && obj.test( /^{.*}$/ )  ) {
                obj = JSON.parse( obj )
            }
            if( obj.each ) {
                obj.each( function( val, key ) {
                    set.apply( store, [ key, val ] )
                } )
            } else {
                for( prop in obj ) {
                    var cur = get( prop )
                    if( cur instanceof List ) {
                        cur.merge.apply( cur, [ obj[ prop ] ] )
                    } else {
                        set.apply( store, [ prop, obj[prop] ] )
                    }
                }
            }
        }
        return this
    }
    merge.apply( this, arguments )

    function entangle( obj ) {
        if( obj !== undefined ) {
            if( typeof obj == 'string' && obj.test( /^{.*}$/ )  ) {
                obj = JSON.parse( obj )
            }
            if( obj.each ) {
                obj.each( function( val, key ) {
                    var ptr = new MapPointer( obj, key )
                    add( ptr, key )
                } )
            } else {
                for( prop in obj ) {
                    var cur = get( prop )
                    if( cur instanceof List ) {
                        cur.merge( obj[ prop ] )
                    } else {
                        var ptr = new MapPointer( obj, prop )
                        add( ptr, prop )
                    }
                }
            }
        }
        return this
    }
    
    function join() {
        throw undefined
    }

    function impress( ) {
        var store = this
        var args = Array.prototype.slice.call( arguments )
        each( function( val, key ) {
            if( ( key == 'impress'
                  && typeof val == 'function' ) ) {
                val.apply( store, args )
            }
            if( ( val.impress
                  && typeof val.impress == 'function' ) ) {
                val.impress.apply( store, args )
            }
        } )
    }

    function on( key, f ) {
        var orig = this[ key ]
        this[ key ] = function() {
            var self = arguments.callee
            var args = Array.prototype.slice.call( arguments )

            if( f.pre ) f.pre.apply( this, arguments )
            if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
                console.log( self.asString + ':' + key )
            }
            var ret = orig.apply( this, arguments )
            if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
                console.log( self.asString + 'ʹ:' + key + ':' + ret )
            }
            try {
                args.push( ret )
                f.apply( this, args )
            } catch( e ) {
                if( e && e['return'] ) {
                    ret = e['return']
                } else {
                    throw e;
                }
            }
            return ret;
        }
        this[ key ].__defineGetter__( 'asString', function() {
            return orig.asString + 'ʹ'
        } )
    }

    function deref( id, regex ) {
        regex = regex || new RegExp( '^([^\.]+)\.' )
        return get( id, regex )
    }

    function get( id, regex ) {
        if( regex ) {
            var path = new MutableString( id )
            var step = get( path.chomp( regex ) )
            if( step && step.get ) {
                return step.get( path, regex )
            }
        }

        var ptr
        if( typeof id == 'number' ) {
            if( id > 0 ) {
                id = id - 1      // index to offset
            } else if( id < 0 ) {
                id += this.count // end-based index
            }
            ptr = new MapPointer( itms, keys[ id ] )
        } else if( typeof id == 'string' ||
                   id instanceof String ) {
            ptr = itms[ id ]
        }

        var itm = ptr !== undefined ? ptr.val : undefined 
        return itm
    }
    
    function set( key ) {
        if( key.each ) {
            key.each( function( val, key ) { del( key ) } )
        }
        return let.apply( this, arguments )
    }
    
    function let( key, itm ) {
        if( key.each ) {
                console.log( key )
            key.each( function( val, key ) {
                console.log( key )
                let( key, val )
            } )
        } else {
            var current = get( key )
            if( current === undefined ) {
                var uid = add( itm, key )
                current = get( key )
            }
            return current
        }
    }

    function del( key ) {
        var idx = keys.indexOf( key )
        if( idx >= 0 && keys[ idx ] !== undefined ) {
            keys[ idx ] = undefined
            --count
        }
    }

    function each( f ) {
        keys.each( function( key, idx ) {
            if( key !== undefined ) {
                f.apply( f, [ get( key ), key ] )
            }
        } )
    }

    function copy( src, dst ) {
        if( dst[0] == '.' ) {
            dst = src + dst
        }
        itms[ dst ] = itms[ src ]
    }

    // true if each relationship and value in a is in b
    function isSubobjectOf( a, b ) {
        var found = true
        if( typeof a == 'undefined' && typeof b == 'undefined' ) {
            // nothing matches itself
        } else if( typeof a == 'object' && typeof b == 'object' ) {
            for( var prop in a ) {
                found = found && isSubobjectOf( a[ prop ],
                                                b[ prop ] )
                if( ! found ) break
            }
        } else if( typeof a == 'function' && typeof b == 'function' ) {
            // functions always match
        } else {
            found = found && a == b
        }
        return found;
    }

    this.id = function( key ) {
        try {
            this.each( function( obj, id ) {
                if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
                    console.log( 'θ:⒧:⊆:⇄' + id )
                }
                if( isSubobjectOf( key, obj ) ) {
                    throw id;
                }
            } )
        } catch( e ) {
            if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
                console.log( 'θ:⒧:⊆:e:⇄:' + e )
            }
            return e;
        }
        return undefined;
    }

    this.trans = function( func ) {
        var next = {}
        this.each( function() {
            func.apply( next, arguments )
        } )
        return next
    }

    this.map = function( func, type ) {
        type = type || 'straight'
        return this.map[ type ].apply( this, arguments )
    }

    this.map.straight = function( func ) {
        var out = new List()
        this.each( function( val, key ) {
            out.set( key, func.apply( this, [ val, key ] ) )
        } )
        return out
    }

    this.map.tangle = function( func ) {
        var out = new List()
        this.each( function( val, key ) {
            if( typeof val == 'function' ) {
                out.set( key, val )
            } else {
                out.set( key, func.apply( this, [ val, key ] ) )
            }
        } )
        return out
    }

    // ToDo:
    this.map.mirror = function( func ) {
        var out = new List()
        var self = this
        this.each( function( val, key ) {
            var get = self.__lookupGetter__( key )
            var set = self.__lookupSetter__( key )
            if( get && set ) {
                out.__defineGetter__( get )
                out.__defineSetter__( set )
            } else {
                out.set( key, func.apply( self, [ val, key ] ) )
            }
        } )
        return out
    }

    this.div = function( num ) {
        return this.map( function( val ) {
            return typeof val == 'number' && val / num || val
        }, 'mirror' )
    }
    
    var exports = {
        add : add,
        on : on,
        del : del,
        swap : swap,
        merge : merge,
        deref : deref,
        get : get,
        set : set,
        let : let,
        each : each,
        impress : impress,
        get count() { return count },
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
        get top( ) { return this.get( -1 ) },
        set top( itm ) { this.add( itm ); return this },
        get pop( ) {
            var out = this.get( keys.pop() )
            --count
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
        var key = new MutableString( variable )
        var baseId = key['.']
        eval( 'var val = ' + baseId )
        console.log( 'p:' + baseId + val )
        while( val && ( prop = key['.'] ) ) {
            val = val[ prop ]
        }
        return key.empty && val !== undefined
    }

    function traverse( f, depth, index ) {
        depth = depth || 1
        index = index || 1
        f.apply( this, { depth: depth, index: index } )
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

function Sublist() {
}
Sublist.prototype = new List

function Suplist() {
}
Suplist.prototype = new List

if( ! Array.prototype.each ) {
    if( typeof console != 'undefined' ) console.log( "missing:Array.each" )
}

function List( ) {
    var keys = []
    var sublists = {}
    var store = this
    var val = store
    var id = 0
    var count = 0

    this.valueOf = function() {
        return ( val instanceof List && val != store
                 ? val.__.val
                 : val )
    }

    this.toString = function() {
        return 'List'
    }

    function add( itm, uid ) {
        var key = uid === undefined ? ++id : uid
        if( keys.indexOf( key ) < 0 ) {
            keys.push( key )
        }
        if( key == '' ) {
            val = itm
        } else {
            console.log( 's:' + uid + ' = ' + itm )
            var subkey = key.substring( 0, key.indexOf( '.' ) )
            subkey = subkey == '' ? key : subkey // next to last list
            var remaining = ( subkey == ''
                              ? ''
                              : key.substring( subkey.length + 1 ) )

            var sublist = sublists[ subkey ]
            if( itm instanceof List ) {
                if( sublist === undefined ) {
                    sublist = itm
                    store.__defineGetter__( subkey, function() {
                        return sublists[ subkey ]
                    } )
                } else {
                    itm.__.each( function() {
                        sublist.__.add.apply( this, arguments )
                    } )
                }
            } else {
                if( sublist === undefined ) {
                    sublist = new List
                }
                sublist.__.add( itm, remaining )
            }
            sublists[ subkey ] = sublist
        }
        return key
    }

    function merge( obj ) {
        if( obj !== undefined ) {
            if( typeof obj == 'string' && obj.test( /^{.*}$/ )  ) {
                obj = JSON.parse( obj )
            }
            if( obj instanceof Array ) {
                obj.each( function( val ) {
                    add( val )
                } )
            } else if( obj instanceof List ) {
                obj.__.each( function( ) {
                    add.apply( store, arguments )
                } )
            } else {
                for( prop in obj ) {
                    var cur = get( prop )
                    if( cur instanceof List ) {
                        cur.__.merge.apply( cur, [ obj[ prop ] ] )
                    } else {
                        set.apply( store, [ prop, obj[ prop ] ] )
                    }
                }
            }
        }
        return this
    }
    merge.apply( this, arguments )

    function get( id ) {
        if( ( id instanceof Array && id.length == 0 )
            || id == '' ) {
            return val
        }
        if( typeof id.shift == 'function' ) {
            var sublist = get( id.shift() )
            if( sublist !== undefined ) {
                return sublist.__.get( id )
            }
        }

        var sublist
        if( typeof id == 'number' ) {
            if( id > 0 ) {
                id = id - 1      // index to offset
            } else if( id < 0 ) {
                id += this.count // end-based index
            }
            sublist = sublists[ keys[ id ] ]
        } else if( typeof id =='string'
                   || id instanceof String ) {
            var path = id.split( '.' )
            sublist = ( path.length == 1
                        ? sublists[ id ]
                        : get( path ) )
        }
        sublist = sublist && sublist.valueOf()
        return sublist
    }
    
    function set( key, itm ) {
        return add( itm, key )
    }
    
    function let( key, itm ) {
        var current = get( key )
        if( current === undefined ) {
            var uid = add( itm, key )
            current = get( uid )
        }
        return current
    }

    function del( key ) {
        if( key instanceof List ) {
            key.__.each( function( val, key ) {
                del( key )
            } )
        }
        throw 'unimplemented'
        var idx = keys.indexOf( key )
        if( idx >= 0 && keys[ idx ] !== undefined ) {
            keys[ idx ] = itms[ keys[ idx ] ] = undefined
            --count
        }
    }

    function each( f ) {
        keys.each( function( key, idx ) {
            if( key !== undefined ) {
                var val = get( key )
                f.apply( val, [ val, key ] )
            }
        } )
    }

    function join() {
        console.log( keys )
        console.log( sublists['x'].valueOf() )
        return Array.prototype.join.apply( exports.vals, arguments )
    }

    function traverse( f, depth, index ) {
        depth = depth || 1
        index = index || 1
        f.apply( this, { depth: depth, index: index } )
        var subindex = 0
        this.__.each( function( item, key ) {
            if( item instanceof List ) {
                item.traverse( f, depth + 1, ++subindex )
            }
        } )
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

    function id( key ) {
        try {
            each( function( obj, id ) {
                if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
                    console.log( 'θ:⒧:⊆:⇄' + id )
                }
                if( isSubobjectOf( key, obj ) ) {
                    throw id
                }
            } )
        } catch( e ) {
            if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
                console.log( 'θ:⒧:⊆:e:⇄:' + e )
            }
            return e
        }
        return undefined
    }

    var exports = {
        add : add,
        del : del,
        merge : merge,
        get : get,
        set : set,
        let : let,
        each : each,
        join : join,
        get count() { return count },
        get val() { return val },
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
                map[ key ] = itm
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
}
List.prototype = new Array

function Sublist() {
}
Sublist.prototype = new List

function Suplist() {
}
Suplist.prototype = new List

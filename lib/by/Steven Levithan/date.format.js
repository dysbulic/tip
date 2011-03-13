/**
 * Accepts a date, a mask, or a date and a mask.
 * Returns a formatted version of the given date.
 * The date defaults to the current date/time.
 * The mask defaults to dateFormat.masks.default.
 */
( function() {
    var Date_toString = Date.prototype.toString
    Date.prototype.toString = function( ) {
        return ( arguments.length == 0
                 ? Date_toString
                 : format ).apply( this, arguments )
    }

    // Common format strings
    var masks = {
        'default':      'ddd mmm dd yyyy HH:MM:ss',
        shortDate:      'm/d/yy',
        mediumDate:     'mmm d, yyyy',
        longDate:       'mmmm d, yyyy',
        fullDate:       'dddd, mmmm d, yyyy',
        shortTime:      'h:MM TT',
        mediumTime:     'h:MM:ss TT',
        longTime:       'h:MM:ss TT Z',
        isoDate:        'yyyy-mm-dd',
        isoTime:        'HH:MM:ss',
        isoDateTime:    'yyyy-mm-dd HH:MM:ssTT',
        isoUtcDateTime: 'UTC:yyyy-mm-dd HH:MM:ssZ'
    }

    function format( mask ) {
        var lexemes = {
            date : this,
            H : {
                name : [ 'hour', '24-hour time' ],
                valueOf : function( ) { return lexemes.date.getUTCHours() },
                divides : 'd',
            },
            h : {
                name : [ undefined, '12-hour time' ],
                valueOf : function() { return lexemes.H % 12 + 1 },
                is : 'h',
            },
            M : {
                name : 'minute',
                valueOf : function( ) { return lexemes.date.getUTCMinutes() },
                divides : 'H',
            },
            s : {
                name : 'second',
                valueOf : function( ) { return lexemes.date.getUTCSeconds() },
                divides : 'm',
            },
            m : {
                name : 'millisecond',
                valueOf : function( ) { return lexemes.date.getUTCMilliseconds() },
                divides : 's',
            },
            d : {
                name : 'day',
                valueOf : function( ) { return lexemes.date.getUTCDate() + 1 },
                divides : 'month',
            },
            w : {
                name : 'week',
                types : {
                    english : {
                        short : [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ],
                        long : [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ],
                    },
                },
                valueOf : [
                    function( ) { return lexemes.date.getUTCDay() + 1 },
                ],
                divides : 'month',
            },
            n : {
                name : 'month',
                types : {
                    english : {
                        short : [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ],
                        long : [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ],
                    }
                },
                valueOf : function( ) { return lexemes.date.getUTCMonth() + 1 },
                divides : 'year',
            },
            jjjj : {
                name : [ 'Julian year', 'year' ],
                valueOf : function( ) { return lexemes.date.getUTCFullYear() },
            },
            p : {
                name : 'period',
                valueOf : function() { return lexemes.H < 12 ? 'a' : 'p' },
            },
            o : {
                name : 'offset',
                valueOf : function( ) {
                    var off = lexemes.date.getTimezoneOffset()
                    // Convert from minutes to hours
                    return ( ( off > 0 ? '-' : '+' )
                             + pad( Math.floor( Math.abs( off ) / 60 ) * 100 + Math.abs( off ) % 60, 4 ) )
                },
            },
            S : {
                name : 'ordinal suffix',
                valueOf : function() {
                    var d = lexemes.d
                    return ( [ 'th', 'st', 'nd', 'rd' ]
                             [ d % 10 > 3 ? 0 : ( d % 100 - d % 10 != 10 ) * d % 10 ] )
                },
            },
        }

        function column( val, width, pad ) {
            val = ( typeof val == 'string' || val instanceof String
                    ? val : new String( val ) )
            width = typeof width == 'number' ? width : 0
            pad = typeof pad == 'string' ? pad : ' '

            var reps = Math.max( 0, Math.round( ( width - val.length ) / pad.length ) )
            var column = ( ( new Array( reps + 1 ) ).join( pad ) + val )
            return column
        }

        [ 'j', 'n', 'd', 'H', 'h', 'M', 's' ].each( function( ltr ) {
            var dual = ltr + ltr
            lexemes[ dual ] = lexemes[ dual ] || {
                valueOf : function( ) {
                    return column.apply( this, [ lexemes[ ltr ].valueOf(), dual.length, '0' ] )
                },
            }
        } )
        ;
        [ 'd', 'n' ].each( function( ltr ) {
            var dual = ltr + ltr
            var triple = dual + ltr
            var quad = dual + dual

            var type = {}
            type[ triple ] = 'short'
            type[ quad ] = 'long'
            ;
            [ triple, quad ].each( function( lex ) {
                lexemes[ lex ] = lexemes[ lex ] || function( ) {
                    return this[ ltr ].types[ type[ lex ] ][ this.lang ][ this - 1 ]
                }
            } )
        } )
        ;
        [ 'm' ].each( function( ltr ) {
            var dual = ltr + ltr
            var triple = dual + ltr

            lexemes[ triple ] = lexemes[ triple ] || {
                valueOf : function( ) {
                    return column.apply( this, [ lexemes[ ltr ].valueOf(), triple.length, '0' ] )
                },
            }
        } )
        ;
        [ 'j' ].each( function( ltr ) {
            var dual = ltr + ltr

            lexemes[ dual ] = lexemes[ dual ] || {
                valueOf : function( ) {
                    return column.apply( this, [ lexemes[ ltr ].valueOf() % 100, dual.length, '0' ] )
                },
            }
        } )

        var tokens = new RegExp( ( '([ndHhMs])\\1?'
                                   + '|m{1,3}'
                                   + '|j{2,4}'
                                   + '|\"[^\"]*\"'
                                   + '|\'[^\']*\'' ),
                                 'g' )
        return mask.replace( tokens, function( lex ) {
            return ( lex in lexemes
                     ? lexemes[ lex ].valueOf( )
                     : lex )
        })
    }
} )()
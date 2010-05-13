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
                valueOf : function( ) { return this.date.getUTCHour() },
                divides : 'd',
            },
            h : {
                name : [ undefined, '12-hour time' ],
                valueOf : function() { return this.H % 12 + 1 },
                is : 'h',
            },
            M : {
                name : 'minute',
                valueOf : function( ) { return this.date.getUTCMinutes() },
                divides : 'H',
            },
            s : {
                name : 'second',
                valueOf : function( ) { return this.date.getUTCSecond() },
                divides : 'm',
            },
            m : {
                name : 'millisecond',
                valueOf : function( ) { return this.date.getUTCMilliseconds() },
                divides : 's',
            },
            d : {
                name : 'day',
                valueOf : function( ) { return this.date.getUTCDay() + 1 },
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
                    function( ) { return this.date.getUTCDay() + 1 },
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
                valueOf : function( ) { return date.getUTCMonth() + 1 },
                divides : 'year',
            },
            j : {
                name : [ 'Julian year', 'year' ],
                valueOf : function( ) { return date.getUTCYear() },
            },
            p : {
                name : 'period',
                valueOf : function() { return this.H < 12 ? 'a' : 'p' },
            },
            o : {
                name : 'offset',
                valueOf : function() {
                    return ( ( o > 0 ? '-' : '+' )
                             + pad( Math.floor( Math.abs( o ) / 60 ) * 100 + Math.abs( o ) % 60, 4 ) )
                },
            },
            S : {
                name : 'ordinal suffix',
                valueOf : function() {
                    var d = this.d
                    return ( [ 'th', 'st', 'nd', 'rd' ]
                             [ d % 10 > 3 ? 0 : ( d % 100 - d % 10 != 10 ) * d % 10 ] )
                },
            },
        }

        function pad( val, len, chr ) {
            val = ( typeof val == 'string' || val instanceof String
                        ? val : new String( val ) )
            len = len || 2
            chr = chr === undefined ? ' ' : chr

            return ( ( new Array( ( val.length - len ) / len ) ).join( chr )
                     + val )
        }

        [ 'H', 'h', 'm', 'n', 's' ].each( function( ltr ) {
            var dual = ltr + ltr
            lexemes[ dual ] = lexemes[ dual ] || function( ) {
                return pad.apply( this, [ this[ ltr ], dual.length, '0' ] )
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
        
        var tokens = new RegExp( ( '([HhMmn])\1??'
                                   + '|j{2,4}'
                                   + '|\"[^\"]*\"'
                                   + '|\'[^\']*\'' ),
                                 'g' )
        return mask.replace( tokens, function( lex ) {
            console.log( lex )
            return 's'
/*            return ( lex in lexemes
                     ? lexemes[ lex ]
                     : lex.slice( 1, lex.length - 1 ) )
*/
        })
    }
} )()

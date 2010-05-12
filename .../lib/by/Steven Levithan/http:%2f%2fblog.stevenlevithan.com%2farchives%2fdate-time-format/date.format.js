/**
 * Accepts a date, a mask, or a date and a mask.
 * Returns a formatted version of the given date.
 * The date defaults to the current date/time.
 * The mask defaults to dateFormat.masks.default.
 */
( function() {
    // Internationalization strings
    var i18n = {
        day : {
            short : {
                
            },
            },
        ],
        month : {
            short : {
                en : [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ]
            },
            long : {
                en : [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ]
            },
        }
    }
    
    var Date_toString = Date.prototype.toString
    Date.prototype.toString = function( ) {
        return ( arguments.length == 1
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

    function format( str ) {
        var lexemes = {
            date : this,
            H : {
                name : [ 'hour', '24-hour time' ],
                val : function( ) { return this.date.getUTCHour() },
                divides : 'd',
            },
            h : {
                name : [ undefined, '12-hour time' ],
                val : function() { return this.H % 12 + 1 },
                is : 'h',
            },
            M : {
                name : 'minute',
                val : function( ) { return this.date.getUTCMinutes() },
                divides : 'H',
            },
            s : {
                name : 'second',
                val : function( ) { return this.date.getUTCSecond() },
                divides : 'm',
            },
            m : {
                name : 'millisecond',
                val : function( ) { return this.date.getUTCMilliseconds() },
                divides : 's',
            },
            d : {
                name : 'day',
                val : function( ) { return this.date.getUTCDay() + 1 },
                divides : 'month',
            },
            w : {
                name : 'week',
                ids : {
                    english : {
                        short : [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ],
                        long : [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ],
                    },
                }

                val : [
                    function( ) { return this.date.getUTCDay() + 1 },
                    
                divides : 'month',
                double : function( ) {
                    return i18n.week.short[ this.lang ][ this[ ltr ] - 1 ]
            },
            n : {
                name : 'month',
                ids : {
                    english : {
                        short : [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ],
                        long : [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ],
                    }
                },
                val : function( ) { return date.getUTCMonth() + 1 },
                divides : 'year',
            },
            j : {
                name : [ 'Julian year', 'year' ],
                val : function( ) { return date.getUTCYear() },
            },
            p : {
                name : 'period',
                
                val : function() { return this.H < 12 ? 'a' : 'p' },
            },
            o : {
                name : 'offset' return ( ( o > 0 ? '-' : '+' )
                                  + pad( Math.floor( Math.abs( o ) / 60 ) * 100 + Math.abs( o ) % 60, 4 ) )
                       },
                
            },
            get S()    {
                var d = this.d
                return ( [ 'th', 'st', 'nd', 'rd' ]
                         [ d % 10 > 3 ? 0 : ( d % 100 - d % 10 != 10 ) * d % 10 ] )
            }

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

        [ 'd', 'n' ].each( function( ltr ) {
            var dual = ltr + ltr
            var triple = dual + ltr
            var quad = dual + dual

            lexemes[ triple ] = lexemes[ triple ] || function( ) {
                this[ ltr ][ 'triple' ]
            }

            lexemes[ quad ] = lexemes[ quad ] || function( ) {
                return i18n.day.long[ this.lang ][ this[ ltr ] - 1 ]
            }
        } )
        


        var tokens = new RegExp( ( '[Hh{1,4}'
                                   + '|m{1,4}'
                                   + '|yy(?:yy)?'
                                   + '|([HhMsTt])\1?'
                                   + '|[LloSZ]'
                                   + '|\"[^\"]*\"'
                                   + '|\'[^\']*\'' ),
                                 'g' )
        //var timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g
        //var timezoneClip = /[^-+\dA-Z]/g


        /*
            // You can't provide utc if you skip other args (use the "UTC:" mask prefix)
            if( arguments.length == 1 && typeof date == 'string' && !/\d/.test( date ) ) {
                mask = date
                date = undefined
            }

            // Passing date through Date applies Date.parse, if necessary
            date = date ? new Date( date ) : new Date
            if( isNaN( date ) ) {
                throw SyntaxError( 'invalid date' )
            }

            mask = String(dF.masks[mask] || mask || dF.masks['default']);

            // Allow setting the utc argument via the mask
            if (mask.slice(0, 4) == 'UTC:') {
                mask = mask.slice(4);
                utc = true;
            }
*/

        var flags = {
            lang : 'en',

            return mask.replace( token, function ($0) {
                return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
            })
        }
    }
} )()

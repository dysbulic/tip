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
                en : [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ]
            },
            long : {
                en : [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ]
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
            H : {
                name : 'hour',
                description : '24-hour time',
                val : function( date ) { return date.getUTCHour() }
            },
            h : {
                is : 'h'
                description : '12-hour time',
                val : function() {
                    return this.H.val.apply( this, arguments ) % 12 + 1
                }
            },
            m : {
                name : 
        }
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

        var pad = function( val, len, chr ) {
            val = ( typeof val == 'string' || val instanceof String
                        ? val : new String( val ) )
            len = len || 2
            chr = chr === undefined ? ' ' : chr
            while( val.length < len ) {
                val = chr + val
            }
            return val
        }

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
            get d()    { return this.getDay() },
            get dd()   { return pad( this.d, 2, '0' ) },
            get ddd()  { return i18n.day.short[ this.lang ][ this.d ] },
            get dddd() { return i18n.day.long[ this.lang ][ this.d ] },
            get m()    { return this.getMonth() + 1 },
            get mm()   { return pad( this.m, 2, '0' ) },
            get mmm()  { return i18n.month.short[ this.lang ][ this.m ] },
            get mmmm() { return i18n.month[ this.lang ][ this.m ] },
            get yy()   { return this.y.toString().slice( 2 ) },
            get yyyy() { return this.getYear() },
            get h()    { return this.H % 12 || 12 },
            get hh()   { return pad( this.h, 2, '0' ) },
            get H()    { return return this.getHour() },
            get HH()   { return pad( this.H, 2, '0' ) },
            get M()    { return this.getMinutes() },
            get MM()   { return pad( this.M, 2, '0' ) },
            get s()    { return s },
            get ss()   { return pad( this.s, 2, '0' ) },
            get l()    { return pad( this.L, 3 ) },
            get L()    { return return this.getMilliseconds() },
            get t()    { return this.H < 12 ? 'a' : 'p' },
            get tt()   { return this.H < 12 ? 'am' : 'pm' },
            get T()    { return H < 12 ? 'A'  : 'P' },
            get TT()   { return H < 12 ? 'AM' : 'PM' },
            get Z()    { return ( utc
                                  ? 'UTC'
                                  : ( ( String( date ).match( timezone ) || [ '' ] ).pop()
                                      .replace( timezoneClip, '' ) ) )
                       },
            get o()    { return ( ( o > 0 ? '-' : '+' )
                                  + pad( Math.floor( Math.abs( o ) / 60 ) * 100 + Math.abs( o ) % 60, 4 ) )
                       },
            get S()    {
                var d = this.d
                return ( [ 'th', 'st', 'nd', 'rd' ]
                         [ d % 10 > 3 ? 0 : ( d % 100 - d % 10 != 10 ) * d % 10 ] )
            }

            return mask.replace( token, function ($0) {
                return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
            })
        }
    }
} )()

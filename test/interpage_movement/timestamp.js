if( window.__.timestamp === undefined &&
    window.__.__lookupGetter__( 'timestamp' ) === undefined ) {
    window.__.__defineGetter__( 'timestamp', function() {
        if( typeof silent != 'undefined' && ! silent && typeof console != 'undefined' ) {
            console.log( 'θ:$:timestamp' );
        }
        var now = new Date();
        now = { year: now.getFullYear(),
                month: now.getMonth(),
                day: now.getDay(),
                hour: now.getHours(),
                minute: now.getMinutes(),
                second: now.getSeconds(),
                millisecond: now.getMilliseconds() };
        for( attr in now ) {
            now[attr] = now[attr].toString();
            if( now[attr].length < 2 ) {
                now[attr] = '0' + now[attr];
            }
        }
        return ( now.year + '/' + now.month + '/'
                 + now.day + '@' + now.hour
                 + ':' + now.minute + ':' + now.second
                 + '.' + now.millisecond );
    } );
}

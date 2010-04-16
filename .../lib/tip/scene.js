var scene = new List()
;
( function() {
    function once() {
        // ToDo: capture metrics about execution times
        //        and attempt to learn impression.display.time
        //        which is the time that the first 'display'
        //        event will pass through
        state.__.let( 'tixels', new List( ) )
        state.__.set( new List( {
            'time.impression.configuration.start' : ( new Date() ).getTime(),
            'time.impression.display.predicted' : ( new Date() ).getTime() + 20,
            'state.impression' : 'configuration',
        } ) )
        var count = 0
        do {
            state.__.set( new List( {
                'impression.configuration.done' : true,
                'impression.loop.count' : ++count,
            } ) )
            //console.log( 'Starting: once: config:' + state.__.get('impression.configuration.done') )
            this.__.impress.apply( state )
            //console.log( 'Finished: once: config:' + state.__.get('impression.configuration.done') )
        } while( count < 100
                 && ! state[ 'impression.configuration.done' ] )
        state.__.set( {
            'impression.display.time' : ( new Date() ).getTime(),
            'impression.configuration.time' : (
                ( new Date() ).getTime()
                    + state.__.get( 'tixel.capture.frequency.mHz' )
            ),
            'impression.state' : 'display',
        } )
        do {
            state.__.set( new List( {
                'impression.display.done' : true,
                'impression.loop.count' : ++count,
            } ) )
            this.__.impress.apply( state )
            //console.log( 'Finished: once: display:' + state.__.get('impression.display.done') )
        } while( ++count < 100
                 && ! state[ 'impression.display.done' ] )
        
        var tixels = state.__.get( 'tixels' )
        if( ! tixels )
            throw 'no tixels'
        var time = state.__.get( 'impression.display.time' )
        
        tixels.__.each( function( txl ) {
            if( time > txl.time.start && time <= txl.time.end
                || time >= txl.time.end && ! txl.done ) {
                txl.time.delta = ( txl.time.delta
                                             || txl.time.end - txl.time.start )
                var offset = ( time >= txl.time.end
                               ? 1
                               : ( ( time - txl.time.start )
                                   / txl.time.delta ) )
                txl.val.delta = ( txl.val.delta
                                  || txl.val.end - txl.val.start )
                var val = txl.val.start + offset * txl.val.delta
                if( txl.prop.each ) {
                    txl.prop.each( function( prop ) {
                                  txl.obj[ prop ] = val
                    } )
                } else {
                    txl.obj[ txl.prop ] = val
                }
                
                txl.obj[ txl.prop ] = 
                    txl.val.start + offset * txl.val.delta
                txl.done = offset === 1
            }
        } )
        
        return state
    }
    
    var interval = {
        id: undefined,
    }
    
    scene.__defineGetter__( 'once', once )
    scene.__defineGetter__( 'go', function() {
        if( interval.id === undefined ) {
            var self = this
            interval.id =
                setInterval( function() {
                    once.apply( self, arguments )
                },
                             state.__.get( 'tixel.capture.frequency.mHz' ) )
        }
        return this
    } )
    scene.__defineGetter__( 'stop', function() {
        if( interval.id !== undefined ) {
            clearInterval( interval.id )
            interval.id = undefined
        }
        return this
    } )
} )()

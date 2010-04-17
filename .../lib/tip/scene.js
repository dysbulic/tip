var scene = new List()
;
( function() {
    function once() {
        var state = new List()
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

        function config( ) {
            var $this = $(this)
            state = state || {}
            if( state.__ && typeof state.__.set == 'function' ) {
                state.__.set( 'caller', this )
            }
            var cfg = $this.data( 'config' )
            if( typeof cfg == 'function'
                && cfg != undefined ) {
                cfg.apply( state, arguments )
            }
        }
        $.__.$('#').each( function traverse( ) {
            config.apply( this, arguments )
            $(this).children().each( traverse ) 
        } )

        state.__.set( {
            'impression.display.time' : ( new Date() ).getTime(),
            'impression.configuration.time' : (
                ( new Date() ).getTime()
                    + state.__.get( 'tixel.capture.frequency.mHz' )
            ),
            'impression.state' : 'display',
        } )

        function display( ) {
            var $this = $(this)
            var scr = $this.data( 'display' )
            if( typeof scr == 'function'
                && scr != undefined ) {
                scr.apply( this, arguments )
            }
        }
        $.__.$('#').each( function traverse( ) {
            display.apply( this, arguments )
            $(this).children().each( traverse ) 
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

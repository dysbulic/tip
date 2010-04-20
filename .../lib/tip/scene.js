var scene = new List()
;
( function() {
    var state = new List( {
        'tixel.capture.frequency.mHz' : .01 * 1000,
        'time.epoch' : ( new Date() ).getTime(),
    } )

    state.__.set( 'tixel.to.scene.time.ratio', 1 )
    scene.__defineGetter__( 'rate', function( ) {
        return state.__.get( 'tixel.to.scene.time.ratio' )
    } )
    scene.__defineSetter__( 'rate', function( val ) {
        state.__.set( 'tixel.to.scene.time.ratio', val )
    } )

    function once() {
        // ToDo: capture metrics about execution times
        //        and attempt to learn impression.display.time
        //        which is the time that the next 'display'
        //        iteration will pass through
        var time = {
            get now() { return ( new Date() ).getTime() }
        }

        function config( ) {
            var $this = $(this)
            var cfg = $this.data( 'config' )
            if( typeof cfg == 'function' ) {
                var local = $this.data( 'state' ) || ( function() {
                    var state = new List()
                    state.$parent = $this
                    state.$parent.data( 'state', state )
                    return state
                } )()

                var now = time.now
                local.__.let( 'time.epoch', now )
            
                //console.log(state.__.get( 'time.epoch' ))
                local.__.set( 'display.time.offset',
                              ( ( now - local.__.get( 'time.epoch' ) )
                                * state.__.get( 'tixel.to.scene.time.ratio' ) ) )

                cfg.apply( local, arguments )
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

        function show( ) {
            var $this = $(this)
            var state = $this.data( 'state' )
            var display = $this.data( 'display' )
            if( typeof display == 'function'
                && state != undefined ) {
                display.apply( state, arguments )
            }
        }
        $.__.$('#').each( function traverse( ) {
            show.apply( this, arguments )
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
            var freq = state.__.get( 'tixel.capture.frequency.mHz' ) || 100
            interval.id =
                setInterval( function() {
                    once.apply( self, arguments )
                },
                             freq )
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

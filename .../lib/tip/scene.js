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
            var state = $this.data( 'state' ) || new List()

            //state.__.let.lazy( 'time.epoch', time.__lookupGetter__( 'now' ) )
            var now = time.now
            state.__.let( 'time.epoch', now )

            var time = {
                offset : ( ( now - state.__.get( 'time.epoch' ) )
                           * state.__.get( 'tixel.to.scene.time.ratio' ) ),
            }
            
            state.__.set( new List( {
                'display.time.offset' : time.offset,
            } ) )

            state.$parent = $this
            state.$parent.data( 'state', state )
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
            var state = $this.data( 'state' ) || new List()
            state.$parent = $this
            state.$parent.data( 'state', state )
            var scr = $this.data( 'display' )
            if( typeof scr == 'function'
                && scr != undefined ) {
                scr.apply( state, arguments )
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

( function() {
    // An object passed the getter to the setter
    // reveals the nature of the assignment and
    // thereby that it is looking for a default
    //
    // Maybe.
    var mimis = window.mimis = window.mimis || {}

    var report = mimis.report || {}

    report.missing = report.missing || function( name ) {
        /*
    var name = arguments.callee.name
    console.log( name )
    name = arguments.callee.callee.name
    console.log( name )
        */
        print 'Error: expected and not found at ' + name
        return name
    }
    

    // Returns:
    //  true if all arguments exist
    //  otherwise an array of elements
    //  that do
    var exists = mimis.exists || function() {
        var args = arguments
        var out = []
        args.each( function( idx, arg ) {
            arg !== undefined && ( function() {
                out.push( arg )
            } )
        } )
        return ( out.length == args.length
                 ? true
                 : ( args.length == 1
                     ? false
                     : out ) )
    }

    if( ! exists( $ ) ) {
        return report.missing( '$' )
    }

    $.extend = $.extend || function() {
        /* ToDo:
        arguments.each( function( idx, elem ) {
            var config = this
            $.isArray( config ) && {
                target.config.map( function() {
                    $.extend
            var target = .target || {}
        } )
        */
        return report.missing( '$.extend' )
    }

    mimis = $.extend( true, mimis, ( function() {
        return {
            run : function() {
                return runCommand.apply( this, arguments )
            },
        }
    } ) )
} )()

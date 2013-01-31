(function( window, $, undefined ) {
    var $holder
    var tileIdx = 0
    var contentIdx = 0
    
    $.fn.fader = function( options ) {
        $holder = $(this)
        var content = []
        var tiles = []
        $(this).children( 'li:first' ).css( {
            'margin-left': options.marginFirst
        } )
        $holder.children( 'li' ).each( function() {
            $(this).css( {
                width: options.itemWidth
            } )
            content.push( $(this).children() )
            if( content.length <= options.tileCount ) {
                tiles.push( $(this) )
            } else {
                $(this).hide()
            }
        } )
        contentIdx = options.tileCount % content.length

        var randIdx = []
        for(var i = 0; i < content.length; i++) {
            randIdx.push( i )
        }

        randIdx.sort( function() { return 0.5 - Math.random() } )

        for( var i = 0; i < tiles.length; i++ ) {
            tiles[ i ].children().remove()
            tiles[ i ].append( content[randIdx[i]] )
        }

        if( tiles.length > 0 ) {
            setTimeout(
                function fade() {
                    tiles[ tileIdx ].fadeOut( options.fadeSpeed, function() {
                        tiles[ tileIdx ].children().remove()
                        tiles[ tileIdx ].append( content[randIdx[contentIdx]] )
                        tiles[ tileIdx ].fadeIn( options.fadeSpeed )
                        tileIdx = ( tileIdx + 1 ) % options.tileCount
                        contentIdx = ( contentIdx + 1 ) % content.length
                        setTimeout( fade, options.timeout )
                    } )
                },
                options.timeout )
        }
    }
})(window, jQuery)

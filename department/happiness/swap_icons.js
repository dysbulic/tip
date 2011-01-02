$( function() {
    function getMatrix( transform ) {
        if( transform instanceof SVGMatrix ) {
            return transform
        }

        var mat = document.documentElement.createSVGMatrix();
        // If unspecified return the identity matrix
        if( ! transform ) {
            mat.a = 1;
            mat.e = 1;
            return mat;
        }

        var trans = transform.split( /[ ,()]/ );
        trans = trans.filter( function( elem ) {
            return !elem.match( /[ ,()]/ ) && elem != '';
        } )
        for( i = 0; i < trans.length; i++) {
            if( trans[i] == 'matrix' ) {
                mat.a = parseFloat( trans[1] );
                mat.b = parseFloat( trans[2] );
                mat.c = parseFloat( trans[3] );
                mat.d = parseFloat( trans[4] );
                mat.e = parseFloat( trans[5] );
                mat.f = parseFloat( trans[6] );
                i += 6;
            } else if( trans[i] == 'translate' ) {
                mat.c = parseFloat( trans[1] );
            }
        }
        return mat;
    }

//     $.fx.step[ 'transform' ] = function(fx) {
//         if(!fx.transformInit) {
//             fx.$elem = $(fx.elem)
//             //fx.start = fx.elem.getScreenCTM();
//             fx.start = getMatrix( fx.$elem.attr('transform') );
//             fx.end = getMatrix( fx.end );
//             //fx.end = getRGB(fx.end);
//             fx.transformInit = true;
//         }


//         fx.$elem.attr( 'transform', 'matrix(' + ( ( fx.start.a + (fx.end.a - fx.start.a) * fx.pos ) + ','
//                                                  + ( fx.start.b + (fx.end.b - fx.start.b) * fx.pos ) + ','
//                                                  + ( fx.start.c + (fx.end.c - fx.start.c) * fx.pos ) + ','
//                                                  + ( fx.start.d + (fx.end.d - fx.start.d) * fx.pos ) + ','
//                                                  + ( fx.start.e + (fx.end.e - fx.start.e) * fx.pos ) + ','
//                                                  + ( fx.start.f + (fx.end.f - fx.start.f) * fx.pos ) ) + ')' );
//         console.log(fx.$elem.attr('transform'));
//     };


    console.log( 'exe' );
    var $root = $(document.documentElement);
    $root.find( '[class="menuitem"]' ).each( function() {
        var $item = $(this);
        $item.click( function() {
            $item.data( 'showsub', ! $item.data( 'showsub' ) )
            $item.parents().andSelf().each( function() {
                var $parent = $(this);
                
                if( $item.data( 'showsub' ) ) {
                    $parent.data( 'transform', $parent.attr( 'transform' ) );
                    $parent.animate( {
                        transform : 'matrix(1,0,0,1,0,0)',
                    } );
                } else {
                    console.log( $parent.data( 'transform' ) );
                    $parent.animate( {
                        transform : $parent.data( 'transform' ),
                    } );
                }
      //console.log( document.createElementNS( 'http://www.w3.org/2000/svg', 'g' ) );
      //$root.append( $('<g/>').append( $root.children() ) );
      //$root.append( document.createElementNS( 'http://www.w3.org/2000/svg', 'g' ) );
      //$root.get( 0 ).appendChild( document.createElementNS( 'http://www.w3.org/2000/svg', 'svg:g' ) );
      //console.log( $root.children().size() );
            } );
        } );
    } )
} )

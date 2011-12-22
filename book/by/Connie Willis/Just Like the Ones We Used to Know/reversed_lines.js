/**
 *  Duplicate HTML tree to two foreignObject children of a SVG
 *  inserted into the hierarchy. Scale(-1,1) and translate one copy to
 *  be a mirror image. Interleave two clipping paths to make every
 *  other line reversed.
 */
$( function() {
       var $body = $('body')
       var $svg = $( document.createElementNS( 'http://www.w3.org/2000/svg', 'svg' ) )
       $svg.attr( {
		      width : $body.width(),
		      height : $body.height(),
		      viewBox : "0 0 " + $body.width() + " " + $body.height(),
		  } )
       var $forward = $( document.createElementNS( 'http://www.w3.org/2000/svg', 'foreignObject' ) )
       $forward.attr( {
			  x : 0, y : 0,
			  width : $body.width(), height : $body.height(),
			  viewBox : "0 0 " + $body.width() + " " + $body.height(),
		      } )
       var $reverse = $( document.createElementNS( 'http://www.w3.org/2000/svg', 'foreignObject' ) )
       $reverse.attr( {
			  x : -$body.width(), y : 0,
			  width : $body.width(), height : $body.height(),
			  transform : 'scale(-1,1)',
			  viewBox : "0 0 " + $body.width() + " " + $body.height(),
		      } )

       var $holder = $('<div/>')
       $holder.append( $body.children() )
       $body.prepend( $svg )

       $forward.append( $holder.children().clone( true ) )
       $reverse.append( $holder.children() )
       $svg.append( $forward )
       $svg.append( $reverse )
   } )
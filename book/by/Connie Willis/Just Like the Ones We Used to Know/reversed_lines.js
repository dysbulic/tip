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
       $svg.append( $forward )
       
       var $newBody = $( document.createElementNS( 'http://www.w3.org/1999/xhtml', 'body' ) )
       $newBody.append( $svg )

       var $html = $( document.createElementNS( 'http://www.w3.org/1999/xhtml', 'html' ) )
       $html.append( $('head, body') )
       $('html').append( $newBody )
       $forward.append( $html )
   } )
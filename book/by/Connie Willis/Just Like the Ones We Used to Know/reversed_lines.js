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

       var $defs = $( document.createElementNS( 'http://www.w3.org/2000/svg', 'defs' ) )
       $svg.prepend( $defs )

       var $cPFwd = $( document.createElementNS( 'http://www.w3.org/2000/svg', 'clipPath' ) )
       $cPFwd.attr( { id : 'forwardClip' } )
       $defs.append( $cPFwd )

       var $cPRev = $( document.createElementNS( 'http://www.w3.org/2000/svg', 'clipPath' ) )
       $cPRev.attr( { id : 'reverseClip' } )
       $defs.append( $cPRev )

       var boxStart = 0
       var lineHeight = 20
       var maxHeight = $body.height()
       while( boxStart < maxHeight ) {
	   var $fwd = $( document.createElementNS( 'http://www.w3.org/2000/svg', 'rect' ) )
	   $fwd.attr( {
			   x : 0, y : boxStart,
			   width : $body.width(), height : lineHeight,
		       } )
	   var $rev = $( document.createElementNS( 'http://www.w3.org/2000/svg', 'rect' ) )
	   $rev.attr( {
			   x : 0, y : boxStart + lineHeight,
			   width : $body.width(), height : lineHeight,
		       } )

	   $cPFwd.append( $fwd )
	   $cPRev.append( $rev )

	   boxStart += 2 * lineHeight
       }
	   
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

       var $fwdClip = $( document.createElementNS( 'http://www.w3.org/2000/svg', 'g' ) )
       $svg.append( $fwdClip.append( $forward ) )

       var $revClip = $( document.createElementNS( 'http://www.w3.org/2000/svg', 'g' ) )
       $svg.append( $revClip.append( $reverse ) )
		    
       function keyHandler( keyCode ) {
	   switch( String.fromCharCode( keyCode ) ) {
	   case "f": // Forward
	       $forward.show()
	       $reverse.hide()
	       $fwdClip.css( { 'clip-path' : 'none' } )
	       break;
	   case "r": // Reverse
	       $forward.hide()
	       $reverse.show()
	       $revClip.css( { 'clip-path' : 'none' } )
	       break;
	   case "i": // Interlaced
	       $forward.show()
	       $reverse.show()
	       $fwdClip.css( { 'clip-path' : 'url(#forwardClip)' } )
	       $revClip.css( { 'clip-path' : 'url(#reverseClip)' } )
	       break;
	   default:
	       console.log( "Unknown: " +
			    "(" + keyCode + "): " +
			    String.fromCharCode( keyCode ) )
	   }
       }
          
       $(document).keypress( function( evt ) { keyHandler( evt.which ) } )
       keyHandler( "i".charCodeAt(0) )
   } )
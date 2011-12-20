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

       var $html = $( '<html/>' ).append( $('head') )
       $forward.append( $html )
       
       $('html').append( $('<body/>').append( $svg ) )
   } )
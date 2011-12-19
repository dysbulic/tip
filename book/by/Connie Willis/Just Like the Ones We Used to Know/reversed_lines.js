$( function() {
       var maxWidth = 500
       

       var $body = $("body")
       var $svg = $( document.createElementNS( "http://www.w3.org/2000/svg", "svg" ) )
       $svg.attr( {
		     width : "100%",
		     height : "100%",
		     viewBox : "0 0 500 500",
		 } )
       $svg.css( {
		     position : "fixed"
		 } )
       $bg = $( document.createElementNS( "http://www.w3.org/2000/svg", "rect" ) )
       $bg.attr( {
		     x : 0,
		     y : 0,
		     width : $body.width(),
		     height : $body.height(),
		 } )
       $bg.css( {
		    fill: "lightblue",
		} )
       $bg.get(0).style.fill = "lightblue"
       $svg.append( $bg )

       $body.prepend( $svg )

       var $lines = []

       $('p').slice( 0, 9 ).each(
	   function walkPara() {
	       console.log( this )

	       var text = this.textContent.split(" ")
	       var idx = 0
	       
	       var $line
	       var $last
	       
	       while( idx < text.length ) {
		   if( ! $line ) {
		       $line = $( document.createElementNS( "http://www.w3.org/2000/svg", "text" ) )
		       $svg.append( $line )
		       if( $lines.length % 2 == 0 ) {
			   $line.attr( {
					   x : -530,
					   y : 17 * $lines.length + 20,
					   transform : "scale(-1, 1)",
				       } )
		       } else {
			   $line.attr( {
					   x : 20,
					   y : 17 * $lines.length + 20,
				       } )
		       }
		       $lines.push( $line )
		   }
		   var orig = $line.text()
		   if( orig.length > 0 ) {
		       orig += " "
		   }
		   $line.text( orig + text[idx++] )
		   
		   if( orig.length > 0 &&
		       $line.get(0).getBBox().width > maxWidth ) {
			   $line.text( orig )
			   $line = undefined
			   --idx
		       }
	       }			
	   } )
   } )
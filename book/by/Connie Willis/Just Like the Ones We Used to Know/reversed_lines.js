$( function() {
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
		     width : 500,
		     height : 500,
		 } )
       $bg.css( {
		    fill: "red",
		} )
       $svg.append( $bg )
       var $body = $("body")
       $body.prepend( $svg )
       console.log( $body )
} )
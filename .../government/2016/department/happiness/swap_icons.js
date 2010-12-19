$( function() {
  console.log( 'exe' );
  var $root = $(document.documentElement);
  $root.click( function() { console.log('click') } )
  $root.find( '[class="menuitem"]' ).each( function() {
    $(this).click( function() {
      console.log(this.getScreenCTM());
      
      //console.log( document.createElementNS( 'http://www.w3.org/2000/svg', 'g' ) );
      //$root.append( $('<g/>').append( $root.children() ) );
      $root.append( $(document.createElementNS( 'http://www.w3.org/2000/svg', 'g' )).css( {
        display : 'none',
          } ) )//.append( $root.children() ) );
      //$root.append( document.createElementNS( 'http://www.w3.org/2000/svg', 'g' ) );
      //$root.get( 0 ).appendChild( document.createElementNS( 'http://www.w3.org/2000/svg', 'svg:g' ) );
      //console.log( $root.children().size() );
    } );
  } );
} )

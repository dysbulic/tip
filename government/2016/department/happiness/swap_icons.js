$( function() {
  console.log( 'exe' );
  var $root = $(document.documentElement);
  $root.click( function() { console.log('click') } )
  $root.find( '[class="menuitem"]' ).each( function() {
    var $item = $(this);
    var $transform = $(document.createElementNS( 'http://www.w3.org/2000/svg', 'g' ));
    $item.after( $transform );
    $transform.append( $item );
    var clickCount = 0;
    $item.click( function() {
      clickCount++;
      console.log(this.getScreenCTM());
      var matrix = this.getScreenCTM().inverse();
      $transform.attr( {
        transform : 'matrix(' + ( matrix.a + ',' + matrix.b + ',' + matrix.c + ',' +
                                  matrix.d + ',' + matrix.e + ',' + matrix.f ) + ')',
      } );
      //console.log( document.createElementNS( 'http://www.w3.org/2000/svg', 'g' ) );
      //$root.append( $('<g/>').append( $root.children() ) );
      //$root.append( document.createElementNS( 'http://www.w3.org/2000/svg', 'g' ) );
      //$root.get( 0 ).appendChild( document.createElementNS( 'http://www.w3.org/2000/svg', 'svg:g' ) );
      //console.log( $root.children().size() );
    } );
  } );
} )

function MutableString( str ) {
  this.toString = function() { return str; }
  this.valueOf = function() { return str; }

  this.chomp = function( lead ) {
    if( typeof lead == "string" ) {
      if( lead == str.substring( 0, lead.length ) ) {
        str = str.substring( lead.length );
        return true;
      }
    } else if( lead instanceof RegExp ) {
      str = str.replace( lead, "" );
      return true;
    } else {
      console.log( "Unknown Chomp Type: " + typeof lead );
    }
    return false;
  }
}
MutableString.prototype = new String;

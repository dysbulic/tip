/* Parses the head of a HTML document looking
 * for a meta tag with a cover.
 */
if( WScript.arguments.length > 0 ) {
    var filename = WScript.arguments.item(0)
    var fs = new ActiveXObject( 'Scripting.FileSystemObject' )

    if( fs.FileExists( filename ) ) {
        var file = fs.GetFile( filename )
        var input = file.OpenAsTextStream( )//fs.ForReading )

        // Extract the document head
        var head = ''
        var line = ''
        while( !input.AtEndOfStream && !line.match( /<\/head>/ ) ) {
            var line = input.ReadLine()
            head += line
        }
        input.close()

        var metaExp = new RegExp( '<meta'
                                  + '\\s+name\\s*=\\s*"cover"'
                                  + '\\s+content="([^"]*)"'
                                  + '[^>]*/?>' )
        // Infinite loop
        //var metaExp = new RegExp( '<meta[^>]*>' )
        //while(( match = metaExp.exec( head )) != null ) {
        if(( match = head.match( metaExp )) != null ) {
            WScript.echo( match[1] )
        }
    }
}

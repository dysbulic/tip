package jhu.welch.atis.utils;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.nio.charset.Charset;
import org.apache.log4j.Logger;

public class FileIO {
    private static FileIO instance = null;
    public static final Charset UTF8 = Charset.forName( "UTF-8" );
    private static Logger log = Logger.getLogger( FileIO.class );
	
    /**
     * Get an instance of FileIO object
     * 
     * @return FileIO Return a FileIO object.
     */
    public static FileIO getInstance() {
        if( instance == null ) {
            instance = new FileIO();
        }
	
        return instance;
    }
    
    /**
     * Private construction - Singleton design 
     */
    private FileIO(){ 
    }
    
    /**
     * Write a String buffer to a file.
     * 
     * @param outf A output file name.
     * @param buf String buffer. 
     */
    public static void Write( String filename, String buf ) {
        Write( filename, buf.toCharArray() );
    }
    
    /**
     * 
     * Write a character buffer to a file. 
     * 
     * @param outf A output file name.
     * @param buf Character buffer. 
     * 
     */
    public static void Write( String filename, char[] buf ) { 
        if( filename == null ) { 
            throw new NullPointerException( "filename is null" );
        }
        
        if( buf == null ) {
            throw new NullPointerException( "buf is null" );
        }

        log.debug( "Outputting to: " + filename );

        File output = new File( filename );
        File dir = output.getParentFile();

        if( dir != null ) {
            dir.mkdirs();
        }
        
        FileWriter writer = null;
        
        try {
            writer = new FileWriter( output );
            writer.write( buf );
        } catch (IOException e) {
            log.error( e );
            throw new RuntimeException( e.getMessage(), e );
        } finally {
            try {
                writer.close();
            } catch( IOException e ) {
            }
        }
    }
}

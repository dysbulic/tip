package jhu.welch.atis.utils;

import java.io.FileWriter;
import java.io.IOException;
import org.apache.log4j.Logger;

public class FileIO {
    private static FileIO instance = null;
    public static final String UTF8 = "UTF-8";
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
        
        FileWriter fileWriter = null;
        
        try {
            fileWriter = new FileWriter( filename );
            fileWriter.write( buf );
        } catch (IOException e) {
            log.error( e );
            e.printStackTrace();
            throw new RuntimeException( e.getMessage(), e );
        } finally {
            try {
                fileWriter.close();
            } catch( IOException e ) {
            }
        }
    }
}

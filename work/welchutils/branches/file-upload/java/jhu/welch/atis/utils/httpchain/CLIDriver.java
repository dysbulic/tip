package jhu.welch.atis.utils.httpchain;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.sql.Date;
import java.text.SimpleDateFormat;

import org.apache.http.client.ClientProtocolException;
import org.apache.log4j.Logger;

import jhu.welch.atis.utils.httpchain.HttpChainsCaller;

public class CLIDriver {
    private static final String AY_LONG_TIMEFORMAT = "M/dd/yyyy h:mm:ss a";
    private static final String UTF8 = "UTF-8";
    private static final String AYTIMEPARAM = "aytimeparam";

    private static Logger log = Logger.getLogger( CLIDriver.class );
	
    /**
     * @param args
     * @throws IOException
     * @throws ClientProtocolException
     */
    public static void main( String[] args ) throws ClientProtocolException, IOException {
        CLIDriver client = new CLIDriver();
        HttpChainsCaller hcc = HttpChainsCaller.getInstance();

        log.debug( "Starting HttpChainsCaller" );

        if( args.length == 0 ) {
            args = new String[] { "conf/urlchains.xml" };
        }
        
        for( String filename : args ) {
            log.debug( "Loading config: " + filename );
            hcc.loadConfig( filename );
            hcc.putUrlVar( AYTIMEPARAM, genereateLoginParamTime() );
            hcc.runall();
        }
    }

    /**
     * 
     * format: 4/26/2011 10:50:30 AM
     * 
     * @return String Return a String time in "M/dd/yyyy h:mm:ss a" format 
     */
    private static String genereateLoginParamTime() {
        Date now = new Date( System.currentTimeMillis() );		
        SimpleDateFormat format = new SimpleDateFormat( AY_LONG_TIMEFORMAT);
        
        String rtn="";
        
        try {
            rtn = URLEncoder.encode(format.format(now), UTF8);
        } catch( UnsupportedEncodingException e ) {
            e.printStackTrace();
        }
        
        return rtn;
    }
}

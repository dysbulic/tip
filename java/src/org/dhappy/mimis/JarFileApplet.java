package org.dhappy.mimis;

import java.util.logging.Logger;
import java.util.logging.Level;

import java.util.List;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.IOException;

import javax.swing.JApplet;

public class JarFileApplet extends JApplet {
    private static Logger log =
	Logger.getLogger( JarFileApplet.class.getName() );

    public void init() {
	log.info( "Initialized: " + JarFileApplet.class.getName() );
    }

    public void start() {
	InputStream stream = null;
	BufferedReader reader = null;
	String script = "/bin/hello/world/js";
	String line;
	try {
	    try {
		stream = JarFileApplet.class.getResourceAsStream( script );
		if( stream == null ) {
		    log.log( Level.WARNING, "Could not get: " + script );
		} else {
		    reader = new BufferedReader( new InputStreamReader( stream ) );
		    while( null != (line = reader.readLine() ) ) {
			log.info( line );
		    }
		}
	    } finally {
		if( reader != null ) {
		    reader.close();
		}
		if( stream != null ) {
		    stream.close();
		}
	    }
	} catch( IOException ioe ) {
		log.log( Level.WARNING, ioe.getMessage() );
	}
    }
	

    public void stop() {
	log.info( "Stop: " + JarFileApplet.class.getName() );
    }

    public void destroy() {
	log.info( "Destroying: " + JarFileApplet.class.getName() );
    }

    public static void main( String[] args ) {
	log.info( "Instantiated: " + JarFileApplet.class.getName() );
    }
}

package org.dhappy.mimis;

import java.util.logging.Logger;
import java.util.logging.Level;

import java.util.List;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.IOException;

import javax.script.ScriptEngineManager;
import javax.script.ScriptEngineFactory;
import javax.script.ScriptEngine;
import javax.script.ScriptException;

import javax.swing.JApplet;

public class BridgeApplet extends JApplet {
    private static Logger log =
	Logger.getLogger( BridgeApplet.class.getName() );

    public void init() {
	log.info( "Initialized: " + BridgeApplet.class.getName() );
    }

    public void start() {
	InputStream stream = null;
	BufferedReader reader = null;
	String script = "/test/setter/rhino";
	String line;
	try {
	    try {
		stream = BridgeApplet.class.getResourceAsStream( script );
		if( stream == null ) {
		    log.log( Level.WARNING, "Could not get: " + script );
		} else {
		    ScriptEngineManager engines = new ScriptEngineManager();
		    ScriptEngine js = engines.getEngineByName( "javascript" );
		    js.put( "hostApplet", this );

		    reader = new BufferedReader( new InputStreamReader( stream ) );

		    try {
			js.eval( reader );
		    } catch( ScriptException se ) {
			log.log( Level.WARNING, se.getMessage(), se );
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
	    log.log( Level.WARNING, ioe.getMessage(), ioe );
	}
    }
	
    public void callback( String id ) {
	log.info( "Called Back: " + id );
    }

    public void stop() {
	log.info( "Stop: " + BridgeApplet.class.getName() );
    }

    public void destroy() {
	log.info( "Destroying: " + BridgeApplet.class.getName() );
    }

    public static void main( String[] args ) {
	log.info( "Instantiated: " + BridgeApplet.class.getName() );
    }
}

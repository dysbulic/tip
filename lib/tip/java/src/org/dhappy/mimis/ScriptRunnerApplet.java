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

import org.mozilla.javascript.tools.shell.Main;

import javax.swing.JApplet;

public class ScriptRunnerApplet extends JApplet {
    private static Logger log =
	Logger.getLogger( ScriptRunnerApplet.class.getName() );

    ScriptEngineManager engines = new ScriptEngineManager();
    ScriptEngine js = engines.getEngineByName( "javascript" );

    public void init() {
	log.info( "Initialized: " + ScriptRunnerApplet.class.getName() );
    }

    public void start() {
	String script = this.getParameter( "script" );
	if( script != null ) {
	    eval( script );
	}
    }

    public void eval( String script ) {
	InputStream stream = null;
	BufferedReader reader = null;
	
	String line;
	try {
	    try {
		stream = ScriptRunnerApplet.class.getResourceAsStream( script );
		if( stream == null ) {
		    log.log( Level.WARNING, "Could not get: " + script );
		} else {
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
	    log.warning( ioe.getMessage() );
	}
    }
    

    public void stop() {
	log.info( "Stop: " + ScriptRunnerApplet.class.getName() );
    }

    public void destroy() {
	log.info( "Destroying: " + ScriptRunnerApplet.class.getName() );
    }

    public static void main( String[] args ) {
	log.info( "Instantiated: " + ScriptRunnerApplet.class.getName() );
    }
}

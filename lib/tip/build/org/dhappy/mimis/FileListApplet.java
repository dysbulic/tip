package org.dhappy.mimis;

import java.util.logging.Logger;
import java.util.logging.Level;

import java.util.List;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.IOException;

import java.security.AccessController;
import java.security.PrivilegedAction;

import javax.script.ScriptEngineManager;
import javax.script.ScriptEngineFactory;
import javax.script.ScriptEngine;
import javax.script.ScriptException;

import javax.swing.JApplet;

import netscape.javascript.JSObject;

public class FileListApplet extends JApplet {
    private static Logger log =
	Logger.getLogger( FileListApplet.class.getName() );

    public void init() {
	log.entering( FileListApplet.class.getName(), "init" );
    }

    public void start() {
	final JApplet applet = this;
	AccessController.doPrivileged( new PrivilegedAction() {
		public Object run() {
		    InputStream stream = null;
		    BufferedReader reader = null;
		    final String script = "/bin/file/list/rhino";
		    try {
			stream = this.getClass().getResourceAsStream( script );
			if( stream == null ) {
			    log.warning( "Could not get: " + script );
			} else {
			    ScriptEngineManager engines = new ScriptEngineManager();
			    ScriptEngine js = engines.getEngineByName( "javascript" );
			    
			    js.put( "hostApplet", applet );
			    js.put( "window", JSObject.getWindow( applet ) );
			    
			    reader = new BufferedReader( new InputStreamReader( stream ) );
			    
			    try {
				js.eval( reader );
			    } catch( ScriptException se ) {
				log.warning( se.getMessage() );
			    }
			}
		    } finally {
			try {
			    if( reader != null ) {
				reader.close();
			    }
			    if( stream != null ) {
				stream.close();
			    }
			} catch( IOException ioe ) {
			    log.warning( ioe.getMessage() );
			}
		    }
		    return null;
		}
	    } );
    }
    
    public String[] ls() {
	log.entering( FileListApplet.class.getName(), "ls" );
	return ls( "c:/My Documents" );
    }

    public InputStream getResourceAsStream( String path ) {
	return getClass().getResourceAsStream( path );
    }

    public String[] ls( String location ) {
	log.entering( FileListApplet.class.getName(), "ls", location );
	log.info( "LS called" );
	return new String[] { location }; 
    }

    public void stop() {
	log.entering( FileListApplet.class.getName(), "stop" );
    }

    public void destroy() {
	log.entering( FileListApplet.class.getName(), "destroy" );
	log.info( "Destroying: " + FileListApplet.class.getName() );
    }

    public static void main( String[] args ) {
	log.entering( FileListApplet.class.getName(), "main" );
    }
}

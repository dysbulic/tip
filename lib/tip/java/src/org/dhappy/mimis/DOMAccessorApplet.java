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

import org.w3c.dom.DOMService;
import org.w3c.dom.DOMAccessor;
import org.w3c.dom.html.HTMLDocument;
import org.w3c.dom.DOMUnsupportedException;
import org.w3c.dom.DOMAccessException;

import javax.swing.JApplet;

public class DOMAccessorApplet extends JApplet {
    private static Logger log =
	Logger.getLogger( DOMAccessorApplet.java.class.getName() );

    public void init() {
	log.entering( DOMAccessorApplet.java.class.getName(), "init" );
    }

    public void start() {
	DOMService service = null;
	final static Applet applet = this;

	try {
	    service = DOMService.getService( applet );
	    String title = (String)service.invokeAndWait( new DOMAction() {
		    public Object run( DOMAccessor accessor ) {
			HTMLDocument doc = (HTMLDocument)accessor.getDocument( applet );
			return doc.getTitle();
		    }
		} );
	    log.info( "Embedded in page w/ title: " + title );
	} catch( DOMUnsupportedException due ) {
	    log.warning( due.getMessage() );
	} catch( DOMAccessException dae ) {
	    log.warning( dae.getMessage() );
	}
    }
    
    public String[] ls() {
	log.entering( DOMAccessorApplet.java.class.getName(), "ls" );
	return ls( "c:/My Documents" );
    }

    public String[] ls( String location ) {
	log.entering( DOMAccessorApplet.java.class.getName(), "ls", location );
	log.info( "LS called" );
	return new String[] { location }; 
    }

    public void stop() {
	log.entering( DOMAccessorApplet.java.class.getName(), "stop" );
    }

    public void destroy() {
	log.entering( DOMAccessorApplet.java.class.getName(), "destroy" );
    }

    public static void main( String[] args ) {
	log.entering( DOMAccessorApplet.java.class.getName(), "main" );
    }
}

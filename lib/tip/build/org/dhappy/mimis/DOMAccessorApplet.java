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

import com.sun.java.browser.dom.DOMService;
import com.sun.java.browser.dom.DOMAction;
import com.sun.java.browser.dom.DOMAccessor;
import com.sun.java.browser.dom.DOMUnsupportedException;
import com.sun.java.browser.dom.DOMAccessException;

import org.w3c.dom.html.HTMLDocument;

import javax.swing.JApplet;

public class DOMAccessorApplet extends JApplet {
    private static Logger log =
	Logger.getLogger( DOMAccessorApplet.class.getName() );

    public void init() {
	log.entering( DOMAccessorApplet.class.getName(), "init" );
    }

    public void start() {
	DOMService service = null;
	final JApplet applet = this;

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
	log.entering( DOMAccessorApplet.class.getName(), "ls" );
	return ls( "c:/My Documents" );
    }

    public String[] ls( String location ) {
	log.entering( DOMAccessorApplet.class.getName(), "ls", location );
	log.info( "LS called" );
	return new String[] { location }; 
    }

    public void stop() {
	log.entering( DOMAccessorApplet.class.getName(), "stop" );
    }

    public void destroy() {
	log.entering( DOMAccessorApplet.class.getName(), "destroy" );
    }

    public static void main( String[] args ) {
	log.entering( DOMAccessorApplet.class.getName(), "main" );
    }
}

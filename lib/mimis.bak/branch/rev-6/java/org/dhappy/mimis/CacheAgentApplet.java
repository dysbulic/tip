package org.dhappy.mimis;

import netscape.javascript.JSObject;
import netscape.javascript.JSException;

import javax.swing.JApplet;

import java.util.logging.Logger;

import javax.script.ScriptEngineManager;
import javax.script.ScriptEngine;
import javax.script.ScriptException;

/**
 * Applet to connect browser environment to JVM.
 */
public class CacheAgentApplet extends JApplet {
    private static Logger log =
	Logger.getLogger( CacheAgentApplet.class.getName() );
    
    public void init() {
        log.info( "Initializing Applet" );

        final JApplet container = this;

        JSObject window = JSObject.getWindow( container );
	JSObject mimis = (JSObject)window.getMember( "mimis" );
	Object applet = mimis.getMember( "applet" );

	((JSObject)applet).call( "ready", new Object[] {} );
    }

    public Object loadJob( JSObject config ) {
	ScriptEngineManager mgr = new ScriptEngineManager();
	ScriptEngine jsEngine = mgr.getEngineByName("JavaScript");
	try {
	    return jsEngine.eval( (String)config.getMember( "script" ) );
	} catch (ScriptException ex) {
	    log.warning( "Running Script" );
	}
	return null;
    }
}
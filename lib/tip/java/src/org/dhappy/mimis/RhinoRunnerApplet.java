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

/*
import net.sourceforge.htmlunit.corejs.javascript.Context;
import net.sourceforge.htmlunit.corejs.javascript.Script;
import net.sourceforge.htmlunit.corejs.javascript.Scriptable;
*/

import org.mozilla.javascript.Context;
import org.mozilla.javascript.Script;
import org.mozilla.javascript.Scriptable;

import javax.swing.JApplet;

public class RhinoRunnerApplet extends JApplet {
    private static Logger log =
	Logger.getLogger( RhinoRunnerApplet.class.getName() );

    public void init() {
	log.info( "Initialized: " + RhinoRunnerApplet.class.getName() );
    }

    public void start() {
	InputStream stream = null;
	BufferedReader reader = null;
	String script = this.getParameter( "script" );
	if( script == null ) {
	    script = "/bin/hello/world/js";
	}
	log.info( "Using script: " + script );
	
	String line;
	try {
	    try {
		stream = RhinoRunnerApplet.class.getResourceAsStream( script );
		if( stream == null ) {
		    log.log( Level.WARNING, "Could not get: " + script );
		} else {
		    reader = new BufferedReader( new InputStreamReader( stream ) );

		    Context context = Context.getCurrentContext();
		    // compileReader( java.io.Reader in, java.lang.String sourceName,
		    //                int lineno, java.lang.Object securityDomain )
		    Script compiledScript = context.compileReader( reader, script, 0, null );
		    Scriptable scope = context.initStandardObjects();

		    Main rhino = new Main();

		    rhino.evaluateScript( compiledScript, context, scope );
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
	

    public void stop() {
	log.info( "Stop: " + RhinoRunnerApplet.class.getName() );
    }

    public void destroy() {
	log.info( "Destroying: " + RhinoRunnerApplet.class.getName() );
    }

    public static void main( String[] args ) {
	log.info( "Instantiated: " + RhinoRunnerApplet.class.getName() );
    }
}

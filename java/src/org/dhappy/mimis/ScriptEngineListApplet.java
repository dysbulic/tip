package org.dhappy.mimis;

import java.util.logging.Logger;

import java.util.List;

import javax.swing.JApplet;

import javax.script.ScriptEngineManager;
import javax.script.ScriptEngineFactory;

public class ScriptEngineListApplet extends JApplet {
    private static Logger log =
	Logger.getLogger( ScriptEngineListApplet.class.getName() );

    public void init() {
	log.info( "Initialized: " + ScriptEngineListApplet.class.getName() );
    }

    public void start() {
	ScriptEngineManager mgr = new ScriptEngineManager();
	List<ScriptEngineFactory> factories = 
	    mgr.getEngineFactories();
	for( ScriptEngineFactory factory : factories ) {
	    log.info( "ScriptEngineFactory Info" );

	    String engName = factory.getEngineName();
	    String engVersion = factory.getEngineVersion();
	    String langName = factory.getLanguageName();
	    String langVersion = factory.getLanguageVersion();
	    log.info( "\tScript Engine: " + engName + " (" + engVersion + ")" );

	    List<String> engNames = factory.getNames();
	    for(String name: engNames) {
		log.info( "\tEngine Alias: " + name );
	    }
	    log.info( "\tLanguage: " + langName + " (" + langVersion + ")" );
	}
    }

    public void stop() {
	log.info( "Stop: " + ScriptEngineListApplet.class.getName() );
    }

    public void destroy() {
	log.info( "Destroying: " + ScriptEngineListApplet.class.getName() );
    }

    public static void main( String[] args ) {
	log.info( "Instantiated: " + ScriptEngineListApplet.class.getName() );
    }
}

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

public class FilesystemListApplet extends JApplet {
    private static Logger log =
	Logger.getLogger( FilesystemListApplet.class.getName() );

    public void init() {
	log.info( "Initialized: " + FilesystemListApplet.class.getName() );
    }

    public void start() {
    }
    
    public String[] list() {
	return list( "c:/My Documents" );
    }

    public String[] list( String location ) {
	return new String[] { location }; 
    }

    public void stop() {
	log.info( "Stop: " + FilesystemListApplet.class.getName() );
    }

    public void destroy() {
	log.info( "Destroying: " + FilesystemListApplet.class.getName() );
    }

    public static void main( String[] args ) {
	log.info( "Instantiated: " + FilesystemListApplet.class.getName() );
    }
}

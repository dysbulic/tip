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
	log.entering( FilesystemListApplet.class.getName(), "init" );
    }

    public void start() {
    }
    
    public String[] ls() {
	log.entering( FilesystemListApplet.class.getName(), "ls" );
	return ls( "c:/My Documents" );
    }

    public String[] ls( String location ) {
	log.entering( FilesystemListApplet.class.getName(), "ls", location );
	log.info( "LS called" );
	return new String[] { location }; 
    }

    public void stop() {
	log.entering( FilesystemListApplet.class.getName(), "stop" );
    }

    public void destroy() {
	log.entering( FilesystemListApplet.class.getName(), "destroy" );
	log.info( "Destroying: " + FilesystemListApplet.class.getName() );
    }

    public static void main( String[] args ) {
	log.entering( FilesystemListApplet.class.getName(), "main" );
    }
}

package org.dhappy.mimis;

import java.util.logging.Logger;

import javax.swing.JApplet;

public class LoggingApplet extends JApplet {
    private static Logger log =
	Logger.getLogger( LoggingApplet.class.getName() );

    public void init() {
	log.info( "Initialized: " + LoggingApplet.class.getName() );
    }

    public void start() {
	log.info( "Start: " + LoggingApplet.class.getName() );
    }

    public void stop() {
	log.info( "Stop: " + LoggingApplet.class.getName() );
    }

    public void destroy() {
	log.info( "Destroying: " + LoggingApplet.class.getName() );
    }

    public static void main( String[] args ) {
	log.info( "Instantiated: " + LoggingApplet.class.getName() );
    }
}

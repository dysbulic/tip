package org.himinbi.media.protocol.c2d;

import java.awt.Dimension;
import javax.media.Time;
import javax.media.MediaLocator;
import javax.media.protocol.*;
import java.io.IOException;
import org.apache.log4j.*;

public class DataSource extends PushBufferDataSource {
    protected boolean connected = false;
    protected Time duration = DURATION_UNBOUNDED;
    protected CanvasImageStream[] stream = null;
    static Logger log = Logger.getLogger("c2d.DS");

    {
        //BasicConfigurator.configure();
	log.debug("DataSource created");
    }

    public DataSource() {
	this(new BezierCanvas(new Dimension(100, 100)));
	log.info("No argument constructor used; expected to be standalone");
    }

    public DataSource(CanvasImageStream stream) {
	this.stream = new CanvasImageStream[1];
	this.stream[0] = stream;
    }

    public String getContentType() {
	if(!connected){
	    throw new Error("Data source not connected");
	}
	return ContentDescriptor.RAW;
    }

    public void connect() throws IOException {
	log.info("Connected");
	connected = true;
    }

    public void disconnect() {
	log.info("Disconnected");
	try {
	    if(stream[0].isRunning()) {
		log.error("Stream was not stopped before source disconnected");
		stop();
	    }
        } catch(IOException e) {
	}
	connected = false;
    }

    public void start() throws IOException {
	log.info("Started");
        if(!connected) {
            throw new Error("DataSource must be connected before it can be started");
	}
        if(!stream[0].isRunning()) {
	    stream[0].setRunning(true);
	} else {
	    log.error("Start called on running stream.");
	}
    }

    public void stop() throws IOException {
	log.info("Stopped");
	stream[0].setRunning(false);
    }

    public Object[] getControls() {
	return stream[0].getControls();
    }

    public Object getControl(String controlType) {
	return stream[0].getControl(controlType);
    }

    public Time getDuration() {
	return duration;
    }

    public PushBufferStream[] getStreams() {
	return stream;
    }
}

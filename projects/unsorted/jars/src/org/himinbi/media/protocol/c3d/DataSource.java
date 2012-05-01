package org.himinbi.media.protocol.c3d;

import javax.media.Time;
import javax.media.MediaLocator;
import javax.media.protocol.*;
import java.io.IOException;

public class DataSource extends PushBufferDataSource {
    protected boolean connected = false;
    protected Time duration = DURATION_UNBOUNDED;
    protected StreamingCanvas3D[] stream = null;

    public DataSource(StreamingCanvas3D stream) {
	this.stream = new StreamingCanvas3D[1];
	this.stream[0] = stream;
    }

    public String getContentType() {
	if(!connected){
	    throw new Error("Error: DataSource not connected");
	}
	return ContentDescriptor.RAW;
    }
    
    public void connect() throws IOException {
	System.out.println("Data source connected");
	connected = true;
    }
    
    public void disconnect() {
	System.out.println("Data source disconnected");
	try {
	    if(stream[0].isRunning()) {
		System.out.println("Stream was not stopped before source disconnected");
		stop();
	    }
        } catch(IOException e) {
	}
	connected = false;
    }
    
    public void start() throws IOException {
	System.out.println("Data source started");
        if(!connected) {
            throw new Error("DataSource must be connected before it can be started");
	}
        if(!stream[0].isRunning()) {
	    stream[0].setRunning(true);
	} else {
	    System.out.println("Start called on running stream.");
	}
    }
    
    public void stop() throws IOException {
	System.out.println("Data source stopped");
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

package org.himinbi.media.util;

import java.io.*;
import java.util.*;
import java.awt.Dimension;
//Logging classes used in debugging. Commented out for alpha release.
//import org.apache.log4j.*;
import javax.media.*;
import javax.media.control.*;
import javax.media.protocol.*;
import javax.media.datasink.*;
import javax.media.format.VideoFormat;

public class MovieMaker implements Runnable, ControllerListener, DataSinkListener {
    DataSource source;
    MediaLocator out;
    Thread me;
    boolean recording = false;
    //static Category cat = Category.getInstance("MovieMaker");
    Processor processor;
    ContentDescriptor outputContentDescriptor = new ContentDescriptor(FileTypeDescriptor.QUICKTIME);

    {
        //cat.debug("MovieMaker created:");
    }

    public MovieMaker() {
	this(null, null);
    }

    public MovieMaker(DataSource source) {
	this(source, null);
    }

    public MovieMaker(DataSource source, MediaLocator out) {
	setDataSource(source);
	setOutputLocator(out);
    }

    public void setDataSource(DataSource source) {
	if(recording) {
	    //cat.error("Set source while running");
	}
	this.source = source;
    }

    public void setOutputLocator(MediaLocator out) {
	if(recording) {
	    //cat.error("Set output while running");
	}
	this.out = out;
    }

    public void start() {
	setRecording(true);
    }

    public void stop() {
	setRecording(false);
    }

    public void setRecording(boolean recording) {
	if(recording) {
	    if(this.recording) {
		//cat.error("Started while running");
	    }
	    synchronized(this) {
		if(me == null || !me.isAlive()) {
		    me = new Thread(this);
		    me.start();
		}
	    }
	} else if(processor != null && processor.getState() == Processor.Started) {
	    processor.stop();
	    processor.close();
	}
	this.recording = recording;
    }

    public boolean isRecording() {
	return recording;
    }

    public void run() {
	try {
	    source.connect();
	} catch(IOException e) {
	    throw new Error("Could not connect source");
	}

	try {
	    processor = Manager.createProcessor(source);
	} catch (Exception e) {
	    throw new Error("Could not create processor");
	}

	//cat.debug("Created processor for the data source");
	
	processor.addControllerListener(this);

	processor.configure();
	if(!waitForState(processor, Processor.Configured)) {
	    throw new Error("Failed to configure the processor");
	}

	//cat.debug("Processor configured");
	
	processor.setContentDescriptor(outputContentDescriptor);

	processor.realize();
	if(!waitForState(processor, processor.Realized)) {
	    throw new Error("Failed to realize the processor");
	}

	//cat.debug("Processor realized");

	DataSink dsink;
	try {
	    dsink = Manager.createDataSink(processor.getDataOutput(), out);
	} catch(NoDataSinkException e) {
	    throw new Error("Failed to create a DataSink for: " + out);
	}

	//cat.debug("Created datasink for: " + out);

	try {
	    dsink.open();
	} catch(IOException e) {
	    throw new Error("IOException opening data sink");
	}

	//cat.debug("Datasink opened");

	dsink.addDataSinkListener(this);

	try {
	    dsink.start();
	    processor.start();
	} catch (IOException e) {
	    throw new Error("IO error during processing");
	}

	//cat.debug("Processor and data sink started");
       
	waitForDataSinkDone();

	try {
	    dsink.close();
	} catch (Exception e) {
	}

	processor.removeControllerListener(this);

	//cat.debug("Done processing");
	recording = false;
    }

    Object processorSync = new Object();
    boolean processorDone = false;
    boolean processorSuccess = true;

    /**
     * Block until the processor has transitioned to the given state.
     * Return false if the transition failed.
     */
    boolean waitForState(Processor processor, int state) {
	processorDone = false;
	synchronized(processorSync) {
	    try {
		while(processor.getState() < state && !processorDone) {
		    processorSync.wait();
		}
	    } catch(InterruptedException e) {
	    }
	}
	return processorSuccess;
    }
    
    public void controllerUpdate(ControllerEvent evt) {
	//cat.debug("Recieved controller update: " + evt);
	synchronized(processorSync) {
	    if(evt instanceof ConfigureCompleteEvent ||
	       evt instanceof RealizeCompleteEvent ||
	       evt instanceof PrefetchCompleteEvent) {
		processorDone = true;
		processorSuccess = true;
	    } else if(evt instanceof ResourceUnavailableEvent) {
		processorDone = true;
		processorSuccess = false;
	    } else if(evt instanceof EndOfMediaEvent) {
		evt.getSourceController().stop();
		evt.getSourceController().close();
	    }
	    processorSync.notifyAll();
	}
    }

    Object dataSinkSync = new Object();
    boolean dataSinkDone = false;
    boolean dataSinkSuccess = true;

    /**
     * Block until file writing is done. 
     */
    boolean waitForDataSinkDone() {
	dataSinkDone = false;
	synchronized(dataSinkSync) {
	    try {
		while(!dataSinkDone) {
		    dataSinkSync.wait();
		}
	    } catch(InterruptedException e) {
	    }
	}
	return dataSinkSuccess;
    }


    /**
     * Event handler for the file writer.
     */
    public void dataSinkUpdate(DataSinkEvent evt) {
	//cat.debug("DataSink event recieved: " + evt);
	synchronized(dataSinkSync) {
	    if(evt instanceof EndOfStreamEvent) {
		dataSinkDone = true;
		dataSinkSuccess = true;
	    } else if(evt instanceof DataSinkErrorEvent) {
		dataSinkDone = true;
		dataSinkSuccess = false;
	    }
	    dataSinkSync.notifyAll();
	}
    }
}

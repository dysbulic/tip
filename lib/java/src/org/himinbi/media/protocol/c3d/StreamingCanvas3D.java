package org.himinbi.media.protocol.c3d;

import java.lang.reflect.*;
import javax.media.j3d.*;
import javax.vecmath.*;
import java.awt.*;
import java.awt.event.*;
import java.awt.image.BufferedImage;
import java.io.*;
import java.util.*;
import javax.media.*;
import javax.media.util.ImageToBuffer;
import javax.media.format.*;
import javax.media.protocol.*;

import org.himinbi.util.*;
import org.himinbi.media.util.*;

public class StreamingCanvas3D extends Canvas3D implements PushBufferStream {
    int sequenceNumber = 0;
    float frameRate;
    Raster raster;
    BufferedImage image;
    Format format;
    long baseTimeStamp = new Date().getTime();
    Control[] controls = new Control[0];
    ContentDescriptor contentDescriptor = new ContentDescriptor(ContentDescriptor.RAW);
    BufferTransferHandler transferHandler;// = new FakeTransferHandler();
    boolean bufferPainted = false;
    boolean frameWanted = false;
    Runner runner;
    
    {
	addComponentListener(new ComponentAdapter() {
		public void componentResized(ComponentEvent e) {
		    resetFormat();
		    raster = new Raster(new Point3f(),
					Raster.RASTER_COLOR,
					0, 0,
					getSize().width, getSize().height,
					new ImageComponent2D
					    (ImageComponent.FORMAT_RGB,
					     new BufferedImage(getSize().width <= 0 ? 1 : getSize().width,
							       getSize().height <= 0 ? 1 : getSize().height,
							       BufferedImage.TYPE_INT_RGB)),
					null);
		    image = raster.getImage().getImage();
		}
	    });
    }
    
    public StreamingCanvas3D(GraphicsConfiguration config) {
	this(config, 20);
    }

    public StreamingCanvas3D(GraphicsConfiguration config, float frameRate) {
	super(config);
	try {
	    runner = new Runner(this, getClass().getMethod("pushFrame", (Class[])null));
	} catch(NoSuchMethodException e) {
	    e.printStackTrace(System.err);
	}
	setFrameRate(frameRate);
    }

    public void setFrameRate(float frameRate) {
	this.frameRate = frameRate <= 0 ? 1 : frameRate;
	runner.setInterval((long)(1000 / frameRate));
	resetFormat();
   }

    public float getFrameRate() {
	return frameRate;
    }

    private void resetFormat() {
	synchronized(this) {
	    format = new RGBFormat(getSize(), getSize().width * getSize().height * 3,
				   Format.intArray,
				   frameRate,
				   32,                    // Bits per pixel
				   0xFF0000,              // Red mask
				   0x00FF00,              // Green mask
				   0x0000FF,              // Blue mask
				   1,                     // Pixel stride
				   getSize().width,       // Line stride
				   Format.FALSE,          // Format flipped
				   Format.NOT_SPECIFIED); // Endian
	}
    }

    public void postSwap() {
	if(isRunning() || frameWanted) {
	    paintBuffer();
	}
    }

    public void paintBuffer() {
	synchronized(image) {
	    getGraphicsContext3D().readRaster(raster);
	    // The image is not painted unless this is called,
	    //  so even though we already have a reference we
	    //  have to call it.
	    raster.getImage().getImage();
	    bufferPainted = true;
	    if(frameWanted) {
		frameWanted = false;
		synchronized(this) {
		    notifyAll();
		}
	    }
	}
    }

    public void pushFrame() {
	if(transferHandler != null) {
	    transferHandler.transferData(this);
	} else {
	    System.err.println("Frame dropped for null transfer handler");
	}
    }

    /* PushBufferDataSource methods
     */

    public Format getFormat() {
	return format;
    }

    public ContentDescriptor getContentDescriptor() {
	return contentDescriptor;
    }
    
    public long getContentLength() {
	return LENGTH_UNKNOWN;
    }

    public boolean endOfStream() {
	return false;
    }
    
    public void read(Buffer buffer) throws IOException {
	while(!bufferPainted) {
	    synchronized(this) {
		try {
		    frameWanted = true;
		    wait();
		} catch(InterruptedException e) {
		}
	    }
	}
	synchronized(image) {
	    buffer.copy(ImageToBuffer.createBuffer(image, frameRate));
	  
	    long timeStamp = new Date().getTime() - baseTimeStamp;
	    long calculatedTimeStamp = (long)(sequenceNumber * 1000000 * (1000 / frameRate));
	    System.out.println(buffer.getSequenceNumber() + ":" + sequenceNumber + " ts: " +
			       timeStamp + " -> " +
			       calculatedTimeStamp +
			       ": d: " + (timeStamp - calculatedTimeStamp) +
			       ": b:" + buffer.getTimeStamp());
	    buffer.setTimeStamp(timeStamp);
            buffer.setSequenceNumber(sequenceNumber);
	    sequenceNumber++;
	}
    }
    
    public void setTransferHandler(BufferTransferHandler transferHandler) {
	System.out.println("Setting transfer handler to: " + transferHandler);
	this.transferHandler = transferHandler;
    }

    public void setRunning(boolean running) {
	runner.setRunning(running);
	if(!running) {
	    bufferPainted = false;
	}
    }

    public boolean isRunning() {
	return runner.isRunning();
    }

    public Object[] getControls() {
	return controls;
    }

    public Object getControl(String controlType) {
	try {
	    Class cls = Class.forName(controlType);
	    Object cs[] = getControls();
	    for(int i = 0; i < cs.length; i++) {
		if(cls.isInstance(cs[i])) {
		    return cs[i];
		}
	    }
	} catch(Exception e) {
	}
	return null;
    }
}

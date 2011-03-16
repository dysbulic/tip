package org.himinbi.media.protocol.c2d;

import java.awt.*;
import java.awt.event.*;
import java.awt.image.*;
import javax.swing.*;
import javax.media.*;
import javax.media.format.*;
import javax.media.protocol.*;
import java.io.IOException;
import org.himinbi.util.*;
import org.himinbi.media.util.*;
import org.apache.log4j.*;

public abstract class CanvasImageStream extends JPanel implements PushBufferStream {
    int sequenceNumber = 0;
    float frameRate;
    BufferedImage image = new BufferedImage(1, 1, BufferedImage.TYPE_INT_RGB);
    Format format;
    Control[] controls = new Control[0];
    ContentDescriptor contentDescriptor = new ContentDescriptor(ContentDescriptor.RAW);
    BufferTransferHandler transferHandler;// = new FakeTransferHandler();
    Runner runner;
    static Logger log = Logger.getLogger("CIS");

    {
        log.debug("CanvasImageStream created:");
	addComponentListener(new ComponentAdapter() {
		public void componentResized(ComponentEvent e) {
		    resetFormat();
		    image = new BufferedImage(getSize().width, getSize().height, BufferedImage.TYPE_INT_RGB);
		}
	    });
	try {
	    runner = new Runner(this, getClass().getMethod("pushFrame", (Class[])null));
	} catch(NoSuchMethodException e) {
	}
    }

    public CanvasImageStream() {
	this(20);
    }

    public CanvasImageStream(MediaLocator locator) {
	if(locator != null && locator.getRemainder().length() > 0) {
	    try {
		setFrameRate(Float.parseFloat(locator.getRemainder()));
	    } catch(NumberFormatException e) {
	    }
	}
    }

    public CanvasImageStream(float frameRate) {
	setFrameRate(frameRate);
    }

    public void setFrameRate(float frameRate) {
	this.frameRate = frameRate <= 0 ? 1 : frameRate;
	log.info("Set framerate to: " + this.frameRate + " and interval to: " + (1000 / frameRate));
	runner.setInterval((long)(1000 / frameRate));
	resetFormat();
    }

    private void resetFormat() {
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
	log.debug("Format set to:" + format);
    }

    public void paint(Graphics g) {
	synchronized(image) {
	    paintBuffer(image.createGraphics());
	    ((Graphics2D)g).drawImage(image, null, 0, 0);
	}
    }

    public abstract void paintBuffer(Graphics2D g);

    public ContentDescriptor getContentDescriptor() {
	return contentDescriptor;
    }

    public long getContentLength() {
	return LENGTH_UNKNOWN;
    }

    public boolean endOfStream() {
	return false;
    }

    public Format getFormat() {
	return format;
    }

    public void read(Buffer buffer) throws IOException {
	log.info("Reading: " + sequenceNumber);
	synchronized(image) {
	    Object data = buffer.getData();
	    int dataLength = image.getWidth() * image.getHeight() * 3;
	    if(data == null || data.getClass() != Format.intArray || ((int[])data).length <= dataLength) {
		data = new int[dataLength];
		buffer.setData(data);
	    }
	    buffer.setOffset(0);
	    buffer.setHeader(null);
	    buffer.setFormat(format);
	    buffer.setLength(dataLength);
	    buffer.setFlags(buffer.getFlags() | Buffer.FLAG_KEY_FRAME);

	    buffer.setTimeStamp((long)(sequenceNumber * (1000 / frameRate) * 1000000));
	    buffer.setSequenceNumber(sequenceNumber);

	    image.getRGB(0, 0,                                // Start Y, Y
                         image.getWidth(), image.getHeight(), // Width, Height
                         (int[])data,                         // Destination
                         buffer.getOffset(),                  // Offset
                         image.getWidth());                   // Scanline stride
	    sequenceNumber++;
	}
    }

    public void setTransferHandler(BufferTransferHandler transferHandler) {
	log.debug("Setting transfer handler to: " + transferHandler);
	this.transferHandler = transferHandler;
    }

    public void setRunning(boolean running) {
	runner.setRunning(running);
    }

    public boolean isRunning() {
	return runner.isRunning();
    }

    public void pushFrame() {
	log.debug("Pushing frame: " + sequenceNumber);
	if(transferHandler != null) {
	    transferHandler.transferData(this);
	} else {
	    log.error("Dropped frame for lack of a transfer handler");
	}
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

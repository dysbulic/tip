package org.himinbi.media.util;

import com.sun.image.codec.jpeg.*;
import java.io.*;
import java.text.NumberFormat;
import java.text.DecimalFormat;
import java.awt.*;
import java.awt.image.BufferedImage;
import javax.swing.*;
import javax.media.*;
import javax.media.format.*;
import javax.media.protocol.*;
import org.apache.log4j.*;

public class FakeTransferHandler implements BufferTransferHandler {
    String filePrefix = "FrameCapture_";
    String filePostfix = ".jpeg";
    NumberFormat format = new DecimalFormat("00000");
    static Category cat = Category.getInstance("FTH");

    {
        cat.debug("FakeTransferHandler created:");
    }

    public FakeTransferHandler() {
    }

    public void transferData(PushBufferStream stream) {
	try {
	    Buffer buffer = new Buffer();
	    stream.read(buffer);
	    if(!buffer.isEOM()) {
		Dimension size = ((VideoFormat)buffer.getFormat()).getSize();
		cat.info("Writing file: " + buffer.getSequenceNumber() + ": <" + size.width + ", " + size.height + ">");
		BufferedImage image = new BufferedImage(size.width, size.height,  BufferedImage.TYPE_INT_RGB);
		image.setRGB(0, 0, size.width, size.height, (int[])buffer.getData(), 0, size.width);
		FileOutputStream out =
		    new FileOutputStream(filePrefix + format.format(buffer.getSequenceNumber()) + filePostfix);
		JPEGImageEncoder encoder = JPEGCodec.createJPEGEncoder(out);
		encoder.getDefaultJPEGEncodeParam(image).setQuality(.3f, false);
		encoder.encode(image);
		out.close();
	    }
	} catch(IOException e) {
	}
    }
}

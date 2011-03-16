package org.himinbi.app;

import java.io.*;
import java.util.*;
import java.awt.Dimension;

import javax.media.*;
import javax.media.control.*;
import javax.media.protocol.*;
import javax.media.datasink.*;
import javax.media.format.VideoFormat;

import org.himinbi.media.util.*;

/**
 * This program takes a list of JPEG image files and convert them into
 * a QuickTime movie.
 */
public class JpegImagesToMovie {
    public void run(int width, int height, int frameRate, Vector inFiles, MediaLocator outML) {
	ImageDataSource ids = new ImageDataSource(width, height, frameRate, inFiles);
	//org.apache.log4j.BasicConfigurator.configure();
	new MovieMaker(ids, outML).start();
    }

    public static void main(String args[]) {
	if (args.length == 0)
	    prUsage();

	// Parse the arguments.
	int i = 0;
	int width = -1, height = -1, frameRate = 1;
	Vector inputFiles = new Vector();
	String outputURL = null;

	while (i < args.length) {

	    if (args[i].equals("-w")) {
		i++;
		if (i >= args.length)
		    prUsage();
		width = new Integer(args[i]).intValue();
	    } else if (args[i].equals("-h")) {
		i++;
		if (i >= args.length)
		    prUsage();
		height = new Integer(args[i]).intValue();
	    } else if (args[i].equals("-f")) {
		i++;
		if (i >= args.length)
		    prUsage();
		frameRate = new Integer(args[i]).intValue();
	    } else if (args[i].equals("-o")) {
		i++;
		if (i >= args.length)
		    prUsage();
		outputURL = args[i];
	    } else {
		inputFiles.addElement(args[i]);
	    }
	    i++;
	}

	if (outputURL == null || inputFiles.size() == 0)
	    prUsage();

	// Check for output file extension.
	if (!outputURL.endsWith(".mov") && !outputURL.endsWith(".MOV")) {
	    System.err.println("The output file extension should end with a .mov extension");
	    prUsage();
	}

	if (width < 0 || height < 0) {
	    System.err.println("Please specify the correct image size.");
	    prUsage();
	}

	// Check the frame rate.
	if (frameRate < 1)
	    frameRate = 1;

	// Generate the output media locators.
	MediaLocator oml;

	if ((oml = createMediaLocator(outputURL)) == null) {
	    System.err.println("Cannot build media locator from: " + outputURL);
	    System.exit(0);
	}

	new JpegImagesToMovie().run(width, height, frameRate, inputFiles, oml);
    }

    static void prUsage() {
	System.err.println("Usage: java JpegImagesToMovie -w <width> -h <height> -f <frame rate> -o <output URL> <input JPEG file 1> <input JPEG file 2> ...");
	System.exit(-1);
    }

    /**
     * Create a media locator from the given string.
     */
    static MediaLocator createMediaLocator(String url) {

	MediaLocator ml;

	if (url.indexOf(":") > 0 && (ml = new MediaLocator(url)) != null)
	    return ml;

	if (url.startsWith(File.separator)) {
	    if ((ml = new MediaLocator("file:" + url)) != null)
		return ml;
	} else {
	    String file = "file:" + System.getProperty("user.dir") + File.separator + url;
	    if ((ml = new MediaLocator(file)) != null)
		return ml;
	}

	return null;
    }


    /**
     * A DataSource to read from a list of JPEG image files and
     * turn that into a stream of JMF buffers.
     * The DataSource is not seekable or positionable.
     */
    class ImageDataSource extends PullBufferDataSource {

	ImageSourceStream streams[];

	ImageDataSource(int width, int height, int frameRate, Vector images) {
	    streams = new ImageSourceStream[1];
	    streams[0] = new ImageSourceStream(width, height, frameRate, images);
	}

	public void setLocator(MediaLocator source) {
	}

	public MediaLocator getLocator() {
	    return null;
	}

	/**
	 * Content type is of RAW since we are sending buffers of video
	 * frames without a container format.
	 */
	public String getContentType() {
	    return ContentDescriptor.RAW;
	}

	public void connect() {
	}

	public void disconnect() {
	}

	public void start() {
	}

	public void stop() {
	}

	/**
	 * Return the ImageSourceStreams.
	 */
	public PullBufferStream[] getStreams() {
	    return streams;
	}

	/**
	 * We could have derived the duration from the number of
	 * frames and frame rate.  But for the purpose of this program,
	 * it's not necessary.
	 */
	public Time getDuration() {
	    return DURATION_UNKNOWN;
	}

	public Object[] getControls() {
	    return new Object[0];
	}

	public Object getControl(String type) {
	    return null;
	}
    }


    /**
     * The source stream to go along with ImageDataSource.
     */
    class ImageSourceStream implements PullBufferStream {

	Vector images;
	int width, height;
	VideoFormat format;

	int nextImage = 0;	// index of the next image to be read.
	boolean ended = false;

	public ImageSourceStream(int width, int height, int frameRate, Vector images) {
	    this.width = width;
	    this.height = height;
	    this.images = images;

	    format = new VideoFormat(VideoFormat.JPEG,
				new Dimension(width, height),
				Format.NOT_SPECIFIED,
				Format.byteArray,
				(float)frameRate);
	}

	/**
	 * We should never need to block assuming data are read from files.
	 */
	public boolean willReadBlock() {
	    return false;
	}

	/**
	 * This is called from the Processor to read a frame worth
	 * of video data.
	 */
 	public void read(Buffer buf) throws IOException {

	    // Check if we've finished all the frames.
	    if (nextImage >= images.size()) {
		// We are done.  Set EndOfMedia.
		System.err.println("Done reading all images.");
		buf.setEOM(true);
		buf.setOffset(0);
		buf.setLength(0);
		ended = true;
		return;
	    }

	    String imageFile = (String)images.elementAt(nextImage);
	    nextImage++;

	    System.err.println("  - reading image file: " + imageFile);

	    // Open a random access file for the next image. 
	    RandomAccessFile raFile;
	    raFile = new RandomAccessFile(imageFile, "r");

	    byte data[] = null;

	    // Check the input buffer type & size.

	    if (buf.getData() instanceof byte[])
		data = (byte[])buf.getData();

	    // Check to see the given buffer is big enough for the frame.
	    if (data == null || data.length < raFile.length()) {
		data = new byte[(int)raFile.length()];
		buf.setData(data);
	    }

	    // Read the entire JPEG image from the file.
	    raFile.readFully(data, 0, (int)raFile.length());

	    System.err.println("    read " + raFile.length() + " bytes.");

	    buf.setOffset(0);
	    buf.setLength((int)raFile.length());
	    buf.setFormat(format);
	    buf.setFlags(buf.getFlags() | buf.FLAG_KEY_FRAME);

	    // Close the random access file.
	    raFile.close();
	}

	/**
	 * Return the format of each video frame.  That will be JPEG.
	 */
	public Format getFormat() {
	    return format;
	}

	public ContentDescriptor getContentDescriptor() {
	    return new ContentDescriptor(ContentDescriptor.RAW);
	}

	public long getContentLength() {
	    return 0;
	}

	public boolean endOfStream() {
	    return ended;
	}

	public Object[] getControls() {
	    return new Object[0];
	}

	public Object getControl(String type) {
	    return null;
	}
    }
}

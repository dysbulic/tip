package com.xith.java3d.overlay;

import javax.media.j3d.*;
import javax.vecmath.*;
import java.net.*;
import java.awt.*;
import java.awt.image.*;
import java.awt.image.Raster;
import java.awt.event.*;
import java.awt.geom.*;
import java.awt.color.*;
import java.util.*;

/**
 * Copyright:  Copyright (c) 2000,2001
 * Company:    Teseract Software, LLP
 * @author David Yazel
 */

public class OverlayUtilities {
    /**
     * Return an appropriate buffered image for the size and format.
     */
    public static BufferedImage createBufferedImage( Dimension size, boolean hasAlpha ) {
	int numBytes = (hasAlpha ? 4 : 3);
	int[] numBit = new int[numBytes];
	int[] bandOffset = new int[numBytes];

	for(int i = 0; i < numBytes; i++) {
	    numBit[i] = 8;
	    bandOffset[i] = i;
	}

	ColorSpace colorSpace = ColorSpace.getInstance(ColorSpace.CS_sRGB);
	int transparencyType = (hasAlpha ? Transparency.TRANSLUCENT : Transparency.OPAQUE);
	ColorModel colorModel =
	    new ComponentColorModel(colorSpace,            // Color space
				    numBit,                // Bits per color
				    hasAlpha,              // Has alpha
				    false,                 // Alpha premultiplied
				    transparencyType,      // Transparency type
				    DataBuffer.TYPE_BYTE); // Type of transfer buffer
	WritableRaster raster =
	    Raster.createInterleavedRaster(DataBuffer.TYPE_BYTE,    // Type of raster
					   size.width, size.height, // Size
					   size.width * numBytes,   // Scanline stride
					   numBytes,                // Pixel stride
					   bandOffset,              // Band offsets
					   null);                   // Location (null = 0,0)
	
	return new BufferedImage(colorModel, // Color model
				 raster,     // Raster
				 false,      // Alpha premultiplied
				 null);      // Hashtable of properties
    }
    
    /**
     * Loads an array of image filenames into an array of BufferedImages. Images
     * must be loaded in relation to an ImageObserver, this is what the component
     * provides. The loading uses the MediaTracker to make sure that the image is
     * loaded correctly so it has to be Component rather than ImageObserver,
     */
    public static BufferedImage[] loadImages(URL[] filename, Component observer, boolean alphaInImage) {
	return loadImages(filename, observer, alphaInImage, new Dimension());
    }
	
    /**
     * Returns the maximum width and height in maxSize
     */
    public static BufferedImage[] loadImages(URL[] filename, Component observer,
					     boolean alphaInImage, Dimension maxSize) {
	BufferedImage[] buffer = new BufferedImage[filename.length];
	MediaTracker tracker = new MediaTracker(observer);

	int id = 0;
	int timeout = 5000;

	for (int i = filename.length - 1; i >= 0; i--) {
	    if(filename[i] != null) {
		Image image = Toolkit.getDefaultToolkit().getImage(filename[i]);

		// This has to be done for the images to load correctly

		tracker.addImage(image, id);
		try {
		    tracker.waitForID(id, timeout);
		} catch (InterruptedException e) {
		    System.err.println("Error: " + filename[i] + " interrupted while loading");
		}
		tracker.removeImage(image, id);

		Dimension imageSize = new Dimension(image.getWidth(observer),
						    image.getHeight(observer));

		maxSize.width = Math.max(maxSize.width, imageSize.width);
		maxSize.height = Math.max(maxSize.height, imageSize.height);

		buffer[i] = OverlayUtilities.createBufferedImage(imageSize, alphaInImage);
		((Graphics2D)buffer[i].getGraphics()).drawImage(image, null, observer);
	    }
	}
	return buffer;
    }

    /**
     * Will update the background color on a set of Overlays and not allow and of
     * them to update until all have been set. Intended to set the background on 
     * grouping classes like an OverlayScroller.
     */
    public static void setBackgroundColor(OverlayBase[] overlay, Color backgroundColor) {
	int i = 0;
	for (i = overlay.length - 1; i >= 0; i--) {
	    overlay[i].getUpdateManager().setUpdating(false);
	    overlay[i].setBackgroundColor(backgroundColor);
	}

	for (i = overlay.length - 1; i >= 0; i--) {
	    overlay[i].getUpdateManager().setUpdating(true);
	}
    }

    /**
     * Subdivides an area into a closest fit set of Rectangle with sides that are
     * powers of 2. All elements will be less than max and greater than the minimum
     * value by threshhold.
     */
    public static Vector subdivide(Dimension dimension, int threshhold, int max) {
	Vector cols = components(dimension.width, threshhold, max);
	Vector rows = components(dimension.height, threshhold, max);
	Vector parts = new Vector();
	
	int i = 0, j = 0;
	int x = 0, y = 0;
	for(i = 0; i < rows.size(); i++) {
	    for(j = 0, x = 0; j < cols.size(); j++) {
		parts.add(new Rectangle(x, y,
					((Integer)cols.get(j)).intValue(),
					((Integer)rows.get(i)).intValue()));
		x += ((Integer)cols.get(j)).intValue();
	    }
	    y += ((Integer)rows.get(i)).intValue();
	}
	return parts;
    }	
    
    /**
     * Breaks an integer into powers of 2. The returned vector contains a set of
     * Integers that if summed would be a closest fit to to value. Each returned
     * Integer is not greater than a power of 2 by more than threshhold and is not
     * greater than max.
     */
    public static Vector components( int value, int threshhold, int max ) {
	Vector components = new Vector();
	while (value > 0) {
	    int p = Math.min(optimalPower(value, threshhold, max), value);
	    components.add(new Integer(p));
	    value -= p;
	}
	return components;
    }

    /**
     * Returns an optimal power of two for the value given. 
     * return the largest power of 2 which is less than or equal to the value, OR
     * it will return a larger power of two as long as the difference between
     * that and the value is not greater than the threshhold.
     */
    public static int optimalPower(int value, int threshhold, int max) {
	int optimal = 1;
	value = Math.min(value, max);
	while(optimal * 2 - value <= threshhold) {
	    optimal *= 2;
	}
	return optimal;
    }

    /**
     * Return the smallest power of 2 greater than value
     */    
    public static int smallestPower(int value) {
	int n = 1;
	while (n < value) {
	    n *= 2;
	}
	return n;
    }

    /**
     * Builds a Shape3D with the specified appearanace covering the specified rectangle.
     */
    public static Shape3D buildShape(Appearance appearance, Rectangle bounds) {
	Texture texture = appearance.getTexture();

	QuadArray planeGeometry =
	    new QuadArray(4, (texture == null
			      ? QuadArray.COORDINATES
			      : QuadArray.COORDINATES | QuadArray.TEXTURE_COORDINATE_2));

	float[] verticies = {
	    bounds.x + bounds.width, bounds.y,                 0.0f,
	    bounds.x + bounds.width, bounds.y + bounds.height,  0.0f,
	    bounds.x,               bounds.y + bounds.height,  0.0f,
	    bounds.x,               bounds.y,                 0.0f};

	planeGeometry.setCoordinates(0, verticies);

	if(texture != null) {
	    Point2D.Float textureRatio =
		new Point2D.Float((float)(bounds.getWidth() / texture.getWidth()),
				  (float)(bounds.getHeight() / texture.getHeight()));
	
	    float[] textureCoordinates = {
		textureRatio.x, 0.0f,
		textureRatio.x, textureRatio.y,
		0.0f,           textureRatio.y,
		0.0f,           0.0f};
	    
	    planeGeometry.setTextureCoordinates(0, 0, textureCoordinates);
	}
	
	Shape3D shape = new Shape3D();
	shape.setGeometry(planeGeometry);
	shape.setAppearance(appearance);
	
	return shape;
    }

    /**
     * Will offset the rectangle within the dimension according to the criteria in
     * the array. The elements in relativePosition are set according to the format
     * of Overlay#setRelativePosition().
     */
    public static void repositonBounds(Rectangle bounds, int[] relativePosition, 
				      Dimension canvasSize, Dimension offset) {
	switch(relativePosition[Overlay.X_PLACEMENT]) {
	case Overlay.PLACE_RIGHT:
	    bounds.x = canvasSize.width - bounds.width - offset.width;
	    break;
	case Overlay.PLACE_LEFT:
	    bounds.x = offset.width;
	    break;
	case Overlay.PLACE_CENTER:
	    bounds.x = (int)(canvasSize.width / 2 - bounds.width / 2) - offset.width;
	    break;
	}

	// this buffer is upside down relative to the screen. 0, 0 is the lower left

	switch(relativePosition[Overlay.Y_PLACEMENT]) {
	case Overlay.PLACE_TOP:
	    bounds.y = offset.height;
	    break;
	case Overlay.PLACE_BOTTOM:
	    bounds.y = canvasSize.height - bounds.height - offset.height;
	    break;
	case Overlay.PLACE_CENTER:
	    bounds.y = (int)(canvasSize.height / 2 - bounds.height / 2) - offset.height;
	    break;
	}
    }
}

package com.xith.java3d.overlay;

import javax.media.j3d.*;
import java.awt.image.*;
import java.awt.image.Raster;
import java.awt.geom.*;
import java.awt.*;

/**
 * A SubOverlay is one of the pieces which displays a portion of the
 * overlay.  This is used internally by Overlay and should not be referenced
 * directly.
 *
 * Copyright:  Copyright (c) 2000,2001
 * Company:    Teseract Software, LLP
 * @author David Yazel
 */

class SubOverlay {
    /**
     * Represents that a buffer being activated or updated sould be the next
     * avaiable one.
     */
    public final static int NEXT_BUFFER = -1;

    BufferedImage[] buffer;
    ImageComponent2D[] bufferHolder;
    int numBuffers;

    int activeBufferIndex = 0; // this is the index of the currently active buffer
    
    Texture2D texture;        // texture mapped to one double buffer
    Shape3D shape;            // textured quad used to hold geometry
    OverlayBase overlay;      // the owner of this sub-overlay
    Rectangle space;          // The part of the overlay covered by this suboverlay
    
    int transferBuffer[];     // used for transferring scan lines from main image to sub-image

    /**
     * Creates a double buffered suboverlay for the specified region. 
     */
    protected SubOverlay( OverlayBase overlay, Rectangle space ) {
	this(overlay, space, 2);
    }

    /**
     * Creates the suboverlay for the specified region with the specified number of buffers.
     */
    protected SubOverlay( OverlayBase overlay, Rectangle space, int numBuffers ) {
	this.overlay = overlay;
	this.space = space;
	this.numBuffers = numBuffers;
	buffer = new BufferedImage[numBuffers];
	bufferHolder = new ImageComponent2D[numBuffers];

	transferBuffer = new int[space.width];

	// create the two buffers
	
	boolean hasAlpha = overlay.hasAlphaComponent();

	int imageComponentType = (hasAlpha 
				  ? ImageComponent2D.FORMAT_RGBA
				  : ImageComponent2D.FORMAT_RGB);
	Dimension textureSize = new Dimension(OverlayUtilities.smallestPower(space.width),
					      OverlayUtilities.smallestPower(space.height));
	
	for(int i = numBuffers - 1; i >= 0; i--) {
	    buffer[i] = OverlayUtilities.createBufferedImage(textureSize, hasAlpha);
	    bufferHolder[i] = new ImageComponent2D(imageComponentType, buffer[i], true, true);
	}
		
	Appearance appearance = new Appearance();

	appearance.setPolygonAttributes(overlay.getPolygonAttributes());
	appearance.setRenderingAttributes(overlay.getRenderingAttributes());
	appearance.setTextureAttributes(overlay.getTextureAttributes());
	appearance.setTransparencyAttributes(overlay.getTransparencyAttributes());
	
	Material material = new Material();
	material.setLightingEnable(false);
	appearance.setMaterial(material);

	texture = new Texture2D(Texture.BASE_LEVEL,
				(hasAlpha ? Texture.RGBA : Texture.RGB),
				textureSize.width, textureSize.height);
	texture.setBoundaryModeS(Texture.CLAMP);
	texture.setBoundaryModeT(Texture.CLAMP);
	texture.setMagFilter(Texture.FASTEST);
	texture.setMinFilter(Texture.FASTEST);
	texture.setImage(0, bufferHolder[activeBufferIndex]);
	texture.setCapability(Texture.ALLOW_IMAGE_WRITE);

	appearance.setTexture(texture);

	shape = buildShape(appearance, space);
    }
    
    /**
     * Simple function to return the smallest power of 2 which
     * the value can be contained within
     */    
    public static int smallestPower( int value ) {
	int n = 1;
	while (n < value) {
	    n *= 2;
	}
	return n;
    }

    /**
     * Builds a Shape3D with the specified appearanace covering the specified rectangle.
     */
    public static Shape3D buildShape(Appearance appearance, Rectangle space) {
	Texture texture = appearance.getTexture();

	QuadArray planeGeometry =
	    new QuadArray(4, (texture == null
			      ? QuadArray.COORDINATES
			      : QuadArray.COORDINATES | QuadArray.TEXTURE_COORDINATE_2));

	float[] verticies = {
	    space.x + space.width, space.y,                 0.0f,
	    space.x + space.width, space.y + space.height,  0.0f,
	    space.x,               space.y + space.height,  0.0f,
	    space.x,               space.y,                 0.0f};

	planeGeometry.setCoordinates(0, verticies);

	if(texture != null) {
	    Point2D.Float textureRatio =
		new Point2D.Float((float)(space.getWidth() / texture.getWidth()),
				  (float)(space.getHeight() / texture.getHeight()));
	
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
     * Draws the portion of fullOverlayImage corresponding to space into the
     * buffer at bufferIndex.
     */
    public void updateBuffer(BufferedImage fullOverlayImage, int bufferIndex) {
	/* Ok, I have neve done this sort of thing before so I am going to have to step
	 * through it. The image coming in is the entire overlay. There are two
	 * problems with its current form. A it is too big and B the scan lines are
	 * the reverse of what they need to be.
	 *
	 * So, we are going to read in lines from a subsection of the image and then
	 * write them into the buffer in the opposite order of what they came in.
	 */
	Dimension size = new Dimension(fullOverlayImage.getWidth(), fullOverlayImage.getHeight());

	if(bufferIndex == NEXT_BUFFER) {
	    bufferIndex = getNextBufferIndex();
	}

	synchronized(buffer[bufferIndex]) {
	    // For each line in the output buffer
	    for (int scanLine = 0; scanLine < space.height; scanLine++) {
		// Copy the appropriate line out of the buffer
		fullOverlayImage.getRGB(space.x, size.height - space.y - space.height + scanLine,
					transferBuffer.length, 1,
					transferBuffer,
					0,
					size.width);
		
		// Put the line into the output
		buffer[bufferIndex].setRGB(0, space.height - scanLine - 1,
					   transferBuffer.length, 1,
					   transferBuffer,
					   0,
					   size.width);
	    }
	}
    }

    /**
     * Returns the index of the next buffer in line to be painted
     */
    public int getNextBufferIndex() {
	return (activeBufferIndex + 1) % numBuffers;
    }

    /**
     * This will change the buffer being displayed. It does not write anything,
     * only switched the image so it must be used carefully. It is intended for
     * use where more than one buffer has been prepped ahead of time. If you
     * do this without having the buffers preprepped then you will get strange
     * things.
     */
    public void setActiveBufferIndex(int activeBufferIndex) {
	if(activeBufferIndex == NEXT_BUFFER) {
	    activeBufferIndex = getNextBufferIndex();
	}

	if(this.activeBufferIndex != activeBufferIndex) {
	    this.activeBufferIndex = activeBufferIndex;
	    texture.setImage(0, bufferHolder[activeBufferIndex]);
	}
    }

    /**
     * Return the shape
     */
    public Shape3D getShape() {
	return shape;
    }
}

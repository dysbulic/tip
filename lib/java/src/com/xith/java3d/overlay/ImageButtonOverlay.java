package com.xith.java3d.overlay;

import com.xith.java3d.testbed.*;
import java.applet.*;
import javax.swing.*;
import java.awt.*;
import java.awt.image.*;
import java.awt.event.*;
import javax.media.j3d.*;
import javax.vecmath.*;
import com.sun.j3d.utils.geometry.ColorCube;
import com.sun.j3d.utils.applet.MainFrame;

/**
 * @author Will Holcomb
 */

public class ImageButtonOverlay extends OverlayBase {
    public final static int INACTIVE_IMAGE = 0;
    public final static int ACTIVE_IMAGE = 1;
    public final static int CLICKED_IMAGE = 2;
    public final static int MOUSEOVER_IMAGE = 3;
    
    final static int numBuffers = 4;
    boolean[] hasImage = new boolean[numBuffers];

    boolean mouseOver = false;
    boolean stuck = false;
    boolean clicked = false;

    public ImageButtonOverlay(Canvas3D canvas, Rectangle imageSpace, BufferedImage[] image) {
	this(canvas, imageSpace, true, false, image);
    } 

    public ImageButtonOverlay(Canvas3D canvas, Rectangle imageSpace,
			      boolean clipAlpha, boolean blendAlpha,
			      BufferedImage[] image) {
	super(canvas, imageSpace, clipAlpha, blendAlpha, null, numBuffers);

	for(int i = image.length - 1; i >= 0; i--) {
	    hasImage[i] = image[i] != null;
	    if(hasImage[i]) {
		updateBuffer(image[i], i);
	    }
	}

	setActiveBuffer(INACTIVE_IMAGE);

	addMouseListener(new MouseListener() {
		public void mouseEntered(MouseEvent e) {
		    mouseOver = true;
		    switchButtons();
		}

		public void mouseExited(MouseEvent e) {
		    mouseOver = false;
		    clicked = false;
		    switchButtons();
		}
		
		public void mousePressed(MouseEvent e) {
		    clicked = true;
		    switchButtons();
		}
		
		public void mouseReleased(MouseEvent e) {
		    clicked = false;
		    switchButtons();
		}

		public void mouseClicked(MouseEvent e) {
		    stuck = !stuck;
		    switchButtons();
		}
	    });
	}
    
    /**
     * Called on a state change to update the buttons
     */
    protected synchronized void switchButtons() {
	if(mouseOver && clicked && hasImage[CLICKED_IMAGE]) {
	    setActiveBuffer(CLICKED_IMAGE);
	} else if(mouseOver && hasImage[MOUSEOVER_IMAGE]) {
	    setActiveBuffer(MOUSEOVER_IMAGE);
	} else if(stuck && hasImage[ACTIVE_IMAGE]) {
	    setActiveBuffer(ACTIVE_IMAGE);
	} else if(hasImage[INACTIVE_IMAGE]) {
	    setActiveBuffer(INACTIVE_IMAGE);
	} else {
	    System.err.println("No images to choose from in ImageButtonOverlay");
	}
    }

    public void repaint() {
	// Prevent painting
    }
}

package com.xith.java3d.testbed;

import com.xith.java3d.testbed.*;
import java.applet.*;
import javax.swing.*;
import java.awt.*;
import java.awt.image.*;
import java.awt.event.*;
import java.util.*;
import java.net.*;
import javax.media.j3d.*;
import javax.vecmath.*;
import com.sun.j3d.utils.geometry.ColorCube;
import com.sun.j3d.utils.applet.MainFrame;

import com.xith.java3d.overlay.*;

/**
 * Title:        Simple applet to test various overlay implementations
 * Description:
 * Copyright:    Copyright (c) 2000,2001
 * Company:      Teseract Software, LLP
 * @author David Yazel
 */

public class OverlayTester extends TestBed{
    public void addNodes(BranchGroup sceneRoot, TransformGroup viewTransform) {
	Font font = new Font("Helvetica", Font.BOLD, 12);
	Color color = Color.red;
	
	Overlay fps = new LabelOverlay(canvas, new Rectangle(10, 10, 100, 20), "FPS") {
		public void initialize() {
		    super.initialize();
		    Behavior behavior = new Behavior() {
			    long lastTime = 0;
			    long lastFrameCount = 0;
			    View view = getCanvas().getView();
			    WakeupCriterion wakeup = new WakeupOnElapsedTime(1000);

			    public void initialize(){
				wakeupOn(wakeup);
				lastTime = System.currentTimeMillis();
			    }

			    public void processStimulus(Enumeration criteria) {
				long currentTime = System.currentTimeMillis();
				long currentFrameCount = view.getFrameNumber();
				
				setText("Frames: " + ((currentFrameCount - lastFrameCount) * 1000
						      / (currentTime - lastTime)));

				lastTime = currentTime;
				lastFrameCount = currentFrameCount;

				wakeupOn(wakeup);
			    }
			};
		    behavior.setSchedulingBounds(new BoundingSphere());
		    getRoot().addChild(behavior);
		}
	    };
	viewTransform.addChild(fps.getRoot());
	
	LabelOverlay[] line = new LabelOverlay[5];
	for (int i = line.length - 1; i >= 0; i--) {
	    line[i] = new LabelOverlay(canvas,
				       new Rectangle(0, 0, 500, 16),
				       "", font, color);
	}
	final OverlayScroller scroller = new OverlayScroller(canvas,
						       new Dimension(10, 20),
						       line, new Insets(5, 25, 5, 25));
	scroller.setRelativePosition(Overlay.PLACE_LEFT, Overlay.PLACE_BOTTOM);
	viewTransform.addChild(scroller.getRoot());

	ChatManager chatManager = new ChatManager(scroller);
	canvas.addKeyListener(chatManager);

	canvas.addKeyListener(new KeyAdapter() {
		public void keyPressed(KeyEvent e) {
		    switch(e.getKeyCode()) {
		    case KeyEvent.VK_F1:
			Color backgroundColor = new Color((float)Math.random(),
							  (float)Math.random(),
							  (float)Math.random(),
							  (float)Math.random());
			int i = 0;
			scroller.setUpdating(false);
			for (i = scroller.getNumLines() - 1; i >= 0; i--) {
			    ((LabelOverlay)scroller.getLine(i)).setBackgroundColor(backgroundColor);
			}
			for (i = 4 - 1; i >= 0; i--) {
			    scroller.getBorder(i).setBackgroundColor(backgroundColor);
			}
			scroller.setUpdating(true);
			break;
		    case KeyEvent.VK_F2:
			((LabelOverlay)scroller.getLine(0)).setText("I'm afraid I can't let you do that Will.", 1);
			((LabelOverlay)scroller.getLine(0)).waitForTyping();
			break;
		    }
		}
	    });

	// This expects the images to be packed in a jar. If you unpack it and
	// are trying to run it change the path to ../../../images

	URL[] filename = new URL[4];
	filename[ImageButtonOverlay.INACTIVE_IMAGE] =
	    getClass().getResource("/images/overlay/mankin_inactive.gif");
	filename[ImageButtonOverlay.ACTIVE_IMAGE] = 
	    getClass().getResource("/images/overlay/mankin_active.gif");
	filename[ImageButtonOverlay.MOUSEOVER_IMAGE] =
	    getClass().getResource("/images/overlay/mankin_mouseover.gif");
	filename[ImageButtonOverlay.CLICKED_IMAGE] =
	    getClass().getResource("/images/overlay/mankin_clicked.gif");

	Dimension maxSize = new Dimension();
	boolean alphaInImage = true;
	BufferedImage[] buffer = OverlayUtilities.loadImages(filename, canvas, alphaInImage, maxSize);

	ImageButtonOverlay button = new ImageButtonOverlay(canvas, 
							   new Rectangle(new Point(10, 20), maxSize),
							   alphaInImage, false,
							   buffer);
	button.setRelativePosition(Overlay.PLACE_RIGHT, Overlay.PLACE_BOTTOM);
	viewTransform.addChild(button.getRoot());
    }
    
    public static void main(String[] args) throws Exception {
	new MainFrame(new OverlayTester(), 600, 600);
    }
}

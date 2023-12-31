package org.himinbi.j3d.hud;

import java.applet.Applet;
import java.awt.*;
import java.awt.image.BufferedImage;
import java.awt.event.*;
import com.sun.j3d.utils.applet.MainFrame;
import com.sun.j3d.utils.geometry.ColorCube;
import com.sun.j3d.utils.universe.SimpleUniverse;
import javax.media.j3d.*;
import javax.vecmath.Point3d;
import javax.vecmath.Vector3d;
import com.sun.j3d.utils.behaviors.mouse.MouseRotate;
import com.sun.j3d.utils.behaviors.mouse.MouseZoom;

public class OffscreenHUD extends Applet {
    boolean printElapsedTime = true;
    boolean offscreen = true;
    double cubeSize = 1;
    double renderStartTime = 0;

    Canvas screenCanvas;
    Canvas3D offscreenCanvas;
    Checkbox printTime;
    BufferedImage buffer;

    public OffscreenHUD(boolean offs) {
	this.offscreen = offs;

	offscreenCanvas = new Canvas3D(SimpleUniverse.getPreferredConfiguration(), offscreen) {
		double initialTime = 0;
		double finalTime = 0;

		public void preRender() {
		    if(printElapsedTime) {
			initialTime = System.currentTimeMillis();
			System.out.print(" Render: " + initialTime);
			if(offscreen) {
			    System.out.print(" (" + (initialTime - renderStartTime) + ")");
			}
		    }
		}

		public void postRender() {
		    if(printElapsedTime) {
			finalTime = System.currentTimeMillis();
			System.out.print(" - " + finalTime +
					 " (" + (finalTime - initialTime) + ")");
			if(offscreen) {
			    System.out.println(" / (" + (finalTime - renderStartTime) + ")");
			} else {
			    System.out.println("");
			}
		    }
		}
	    };

	printElapsedTime = offscreen;
	printTime = new Checkbox("Print Elapsed Time",
				 printElapsedTime);
	
	View view = new View();
        view.setPhysicalBody(new PhysicalBody());
        view.setPhysicalEnvironment(new PhysicalEnvironment());
        view.addCanvas3D(offscreenCanvas);
        
	ViewPlatform viewPlatform = new ViewPlatform();
        view.attachViewPlatform(viewPlatform);

	BranchGroup sceneRoot = new BranchGroup();
	sceneRoot.addChild(viewPlatform);

	Transform3D initialCubePosition = new Transform3D();
	initialCubePosition.setTranslation(new Vector3d(0, 0, -cubeSize * 5));

	TransformGroup cubeMovement = new TransformGroup(initialCubePosition);
	cubeMovement.setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	cubeMovement.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);

	BoundingSphere bounds = new BoundingSphere(new Point3d(0, 0, 0), 100);
	
	MouseRotate rotateBehavior = new MouseRotate(cubeMovement);
        rotateBehavior.setSchedulingBounds(bounds);
        cubeMovement.addChild(rotateBehavior);

	MouseZoom zoomBehavior = new MouseZoom(cubeMovement);
        zoomBehavior.setSchedulingBounds(bounds);
        cubeMovement.addChild(zoomBehavior);

	sceneRoot.addChild(cubeMovement);

	Transform3D yAxis = new Transform3D();
	Alpha rotationAlpha = new Alpha(-1, 4000);

	TransformGroup cubeRotation = new TransformGroup();
	cubeRotation.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);

	RotationInterpolator rotator =
	    new RotationInterpolator(rotationAlpha, cubeRotation, yAxis,
				     0, (float)(Math.PI * 2));
	rotator.setSchedulingBounds(bounds);
	cubeRotation.addChild(rotator);

	cubeMovement.addChild(cubeRotation);
	cubeRotation.addChild(new ColorCube(cubeSize));

        sceneRoot.compile();

	VirtualUniverse universe = new VirtualUniverse();
        Locale locale = new Locale(universe);
	locale.addBranchGraph(sceneRoot);
	
	setLayout(new BorderLayout());

	printTime.addItemListener(new ItemListener() {
		public void itemStateChanged(ItemEvent e) {
		    printElapsedTime = printTime.getState();
		}
	    });
	add(BorderLayout.SOUTH, printTime);

	if(!offscreen) {
	    add(BorderLayout.CENTER, offscreenCanvas);
	} else {
	    screenCanvas = new Canvas() {
		    double renderStopTime = 0;
		    
		    public void paint(Graphics g) {
			if(printElapsedTime) {
			    renderStartTime = System.currentTimeMillis();;
			    System.out.println("Draw Start: " + renderStartTime);
			}
			offscreenCanvas.renderOffScreenBuffer();
			offscreenCanvas.waitForOffScreenRendering();
			if(printElapsedTime) {
			    renderStopTime = System.currentTimeMillis();;
			    System.out.println("Draw Stop: " + renderStopTime +
					       " (" + (renderStopTime - renderStartTime) + ")");
			}
			g.drawImage(buffer, 0, 0, this);
			g.setColor(Color.white);
			g.drawString("Drawn using Canvas.getGraphics()", 10, 12);
		    }
		};

	    screenCanvas.addMouseListener(new MouseAdapter() {
		    public void mousePressed(MouseEvent e) {
			screenCanvas.repaint();
		    }
		});
	    
	    screenCanvas.addComponentListener(new ComponentAdapter() {
		    public void componentResized(ComponentEvent e) {
			int width = Math.max(1, screenCanvas.getSize().width);
			int height = Math.max(1, screenCanvas.getSize().height);
			// I pulled this from the Screen3D documentation
			double pixelToScreenRatio = 0.0254 / 90;
			
			offscreenCanvas.getScreen3D().setSize(width, height);
			offscreenCanvas.getScreen3D().setPhysicalScreenWidth(width * pixelToScreenRatio);
			offscreenCanvas.getScreen3D().setPhysicalScreenHeight(height * pixelToScreenRatio);
			
			buffer = new BufferedImage(width, height, BufferedImage.TYPE_INT_ARGB);
			
			ImageComponent2D renderBuffer =
			    new ImageComponent2D(ImageComponent.FORMAT_RGBA, buffer, true, true);
			renderBuffer.setCapability(ImageComponent2D.ALLOW_IMAGE_READ);
			offscreenCanvas.setOffScreenBuffer(renderBuffer);
		    }
		});

	    add(BorderLayout.CENTER, screenCanvas);
	}
    }

    public static void main(String[] args) {
	boolean offscreen = args.length == 0;
	new MainFrame(new OffscreenHUD(offscreen), 512, 512);
    }
}

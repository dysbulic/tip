package org.himinbi.j3d.hud;

import java.applet.Applet;
import java.awt.*;
import java.awt.event.*;
import com.sun.j3d.utils.applet.MainFrame;
import com.sun.j3d.utils.geometry.ColorCube;
import com.sun.j3d.utils.universe.SimpleUniverse;
import javax.media.j3d.*;
import javax.vecmath.Point3d;
import javax.vecmath.Vector3d;
import com.sun.j3d.utils.behaviors.mouse.MouseRotate;
import com.sun.j3d.utils.behaviors.mouse.MouseZoom;

public class ImageDrawHUD extends Applet {
    HUDCanvas3D canvas = new HUDCanvas3D();
    Scrollbar scrollbar = new Scrollbar(Scrollbar.VERTICAL,
					canvas.getDrawMode(),
					1,
					HUDCanvas3D.DRAW_NONE,
					HUDCanvas3D.DRAW_ALL + 1);
    Checkbox printTime = new Checkbox("Print Elapsed Time",
				      canvas.getPrintElapsedTime());
    double cubeSize = 1;

    public ImageDrawHUD() {
	View view = new View();
        view.setPhysicalBody(new PhysicalBody());
        view.setPhysicalEnvironment(new PhysicalEnvironment());
        view.addCanvas3D(canvas);
        
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
	sceneRoot.addChild(rotator);

	cubeMovement.addChild(cubeRotation);
	cubeRotation.addChild(new ColorCube(cubeSize));

        sceneRoot.compile();

	VirtualUniverse universe = new VirtualUniverse();
        Locale locale = new Locale(universe);
	locale.addBranchGraph(sceneRoot);

	scrollbar.addAdjustmentListener(new AdjustmentListener() {
		public void adjustmentValueChanged(AdjustmentEvent e) {
		    canvas.setDrawMode(scrollbar.getValue());
		}
	    });
	printTime.addItemListener(new ItemListener() {
		public void itemStateChanged(ItemEvent e) {
		    canvas.setPrintElapsedTime(printTime.getState());
		}
	    });
	setLayout(new BorderLayout());
	add(BorderLayout.CENTER, canvas);
	add(BorderLayout.SOUTH, printTime);
	add(BorderLayout.WEST, scrollbar);
    }
    
    public static void main(String[] args) {
	new MainFrame(new ImageDrawHUD(), 512, 512);
    }
}

class HUDCanvas3D extends Canvas3D {
    public final static int DRAW_GRAPHICS = Integer.parseInt("1", 2);
    public final static int DRAW_JGRAPHICS_WAIT = Integer.parseInt("10", 2);
    public final static int DRAW_JGRAPHICS_NOWAIT = Integer.parseInt("100", 2);
    public final static int DRAW_NONE = 0;
    public final static int DRAW_ALL = (DRAW_GRAPHICS | DRAW_JGRAPHICS_WAIT | DRAW_JGRAPHICS_NOWAIT);
    int drawMode = DRAW_ALL;
    
    boolean printElapsedTime = true;

    J3DGraphics2D jg2;
    Graphics2D g2;
    
    int lineHeight = 12;
    int margin = 10;
    Color color = Color.white;

    public HUDCanvas3D() {
	this(SimpleUniverse.getPreferredConfiguration());
    }

    public HUDCanvas3D(GraphicsConfiguration configuration) {
	super(configuration);
    }
    
    public void postRender() {
	if(jg2 == null) {
	    jg2 = getGraphics2D();
	}

	if(g2 == null) {
	    g2 = (Graphics2D)getGraphics();
	}
	
	double initialTime = 0, currentTime = 0, lastTime = 0;
	if(printElapsedTime) {
	    lastTime = initialTime = System.currentTimeMillis();
	    System.out.print("Draw:");
	}

	int lineCount = 0;

	if((drawMode & DRAW_GRAPHICS) == DRAW_GRAPHICS) {
	    g2.setColor(color);
	    g2.drawString("Drawn using Component.getGraphics()",
			  margin, lineHeight * ++lineCount);
	    if(printElapsedTime) {
		currentTime = System.currentTimeMillis();
		System.out.print(" g2d:" + (currentTime - lastTime));
		lastTime = currentTime;
	    }
	    
	}

	if((drawMode & DRAW_JGRAPHICS_WAIT) == DRAW_JGRAPHICS_WAIT) {
	    jg2.setColor(color);
	    jg2.drawString("Drawn using Canvas3D.getGraphics2D(), waiting on flush",
			   margin, lineHeight * ++lineCount);
	    jg2.flush(true);
	    if(printElapsedTime) {
		currentTime = System.currentTimeMillis();
		System.out.print(" jg2d(w):" + (currentTime - lastTime));
		lastTime = currentTime;
	    }
	}
	
	if((drawMode & DRAW_JGRAPHICS_NOWAIT) == DRAW_JGRAPHICS_NOWAIT) {
	    jg2.setColor(color);
	    jg2.drawString("Drawn using Canvas3D.getGraphics2D(), not waiting on flush",
			   margin, lineHeight * ++lineCount);
	    jg2.flush(false);
	    if(printElapsedTime) {
		currentTime = System.currentTimeMillis();
		System.out.print(" jg2d(nw):" + (currentTime - lastTime));
		lastTime = currentTime;
	    }
	}
	if(printElapsedTime) {
	    currentTime = System.currentTimeMillis();
	    System.out.println(" fr(ms/f):" + (currentTime - initialTime));
	}

    }

    public int getDrawMode() {
	return drawMode;
    }

    public void setDrawMode(int drawMode) {
	this.drawMode = drawMode;
    }

    public boolean getPrintElapsedTime() {
	return printElapsedTime;
    }
    
    public void setPrintElapsedTime(boolean printElapsedTime) {
	this.printElapsedTime = printElapsedTime;
    }
}

package org.himinbi.j3d.test;

import com.sun.j3d.utils.applet.*;
import com.sun.j3d.utils.universe.*;
import com.sun.j3d.utils.behaviors.mouse.*;
import java.awt.*;
import java.awt.event.*;
import javax.media.j3d.*;
import javax.vecmath.*;
import javax.swing.*;
import org.himinbi.j3d.shape.Star;

public class LineTest extends JApplet implements AdjustmentListener {
    Star star;
    JScrollBar starIndex;

    public LineTest() {
	GridBagLayout layout = new GridBagLayout();
	GridBagConstraints gridbag = new GridBagConstraints();
	getContentPane().setLayout(layout);
	
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	gridbag.weightx = 1;
	gridbag.weighty = 1;
	gridbag.fill = GridBagConstraints.BOTH;
	
	Canvas3D canvas = createCanvas();
	layout.setConstraints(canvas, gridbag);
	getContentPane().add(canvas);
	
	gridbag.weightx = 0;
	gridbag.weighty = 0;

	starIndex = new JScrollBar(Adjustable.HORIZONTAL, star.getIndex(), 1, -1, star.getNumIndices());
	layout.setConstraints(starIndex, gridbag);
	getContentPane().add(starIndex);
	starIndex.addAdjustmentListener(this);
    }
    
    private Canvas3D createCanvas() {
        View view = new View();
        view.setPhysicalBody(new PhysicalBody());
        view.setPhysicalEnvironment(new PhysicalEnvironment());
        Canvas3D canvas = new Canvas3D(SimpleUniverse.getPreferredConfiguration());
        view.addCanvas3D(canvas);
        ViewPlatform viewPlatform = new ViewPlatform();
        view.attachViewPlatform(viewPlatform);
	
        VirtualUniverse universe = new VirtualUniverse();
        Locale locale = new Locale(universe);
        BranchGroup head = new BranchGroup();
	
        BoundingSphere bounds = new BoundingSphere(new Point3d(), Double.MAX_VALUE);
	
        Background background = new Background(new Color3f(29f / 255f, 43f / 255f, 153f / 255f));
        background.setApplicationBounds(bounds);
        head.addChild(background);
	
        DirectionalLight light = new DirectionalLight
            (new Color3f(100f / 255f, 100f / 255f, 100f / 255f), 
             new Vector3f(0f, 0f, -1f));
        light.setInfluencingBounds(bounds);
        head.addChild(light);

	TransformGroup rotationTransformation = new TransformGroup();
	rotationTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	rotationTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	MouseRotate rotateBehavior = new MouseRotate(rotationTransformation);
	rotateBehavior.setSchedulingBounds(bounds);
	rotationTransformation.addChild(rotateBehavior);

	head.addChild(rotationTransformation);

	star = new Star(5, 1);
	rotationTransformation.addChild(star);

	Transform3D transform = new Transform3D();
	transform.setTranslation(new Vector3d(0, 0, 4));
	TransformGroup zoomTransformation = new TransformGroup(transform);
	zoomTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	zoomTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	MouseZoom zoomBehavior = new MouseZoom(zoomTransformation);
	zoomBehavior.setSchedulingBounds(bounds);
	zoomTransformation.addChild(zoomBehavior);
	
	head.addChild(zoomTransformation);
	zoomTransformation.addChild(viewPlatform);

	locale.addBranchGraph(head);

	return canvas;
    }

    public void adjustmentValueChanged(AdjustmentEvent e) {
	star.setIndex(starIndex.getValue());
    }

    public static void main(String[] args) {
        new MainFrame(new LineTest(), 400, 300);
    }
}


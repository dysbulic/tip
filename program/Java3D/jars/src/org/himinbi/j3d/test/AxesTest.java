package org.himinbi.j3d.test;


import javax.media.j3d.*;
import javax.vecmath.*;
import javax.swing.*;
import com.sun.j3d.utils.applet.*;
import com.sun.j3d.utils.universe.*;
import com.miltec.mvis.*;
import com.sun.j3d.utils.behaviors.mouse.*;
import java.awt.*;
import java.awt.event.*;

public class AxesTest extends JApplet implements ActionListener {
    Axes axes;
    JCheckBox visibleBox;

    public AxesTest() {
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

	visibleBox = new JCheckBox("Visible", axes.isVisible());
	layout.setConstraints(visibleBox, gridbag);
	getContentPane().add(visibleBox);
	visibleBox.addActionListener(this);
    }

    private Canvas3D createCanvas() {
	float length = 1f;
	float radius = .04f;

	View view = new View();
        view.setPhysicalBody(new PhysicalBody());
        view.setPhysicalEnvironment(new PhysicalEnvironment());
	Canvas3D canvas = new Canvas3D(SimpleUniverse.getPreferredConfiguration());
	view.addCanvas3D(canvas);
	ViewPlatform viewPlatform = new ViewPlatform();
	view.attachViewPlatform(viewPlatform);
	
	SimpleScene scene = new SimpleScene(canvas);

	Transform3D transform = new Transform3D();
	transform.setTranslation(new Vector3f(0f, -length / 5f, -length * 3f));
	TransformGroup axesTransformation = new TransformGroup(transform);
	axesTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	axesTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	axesTransformation.setCapability(TransformGroup.ALLOW_CHILDREN_READ);
	axesTransformation.setCapability(TransformGroup.ALLOW_CHILDREN_WRITE);
	axesTransformation.setCapability(TransformGroup.ALLOW_CHILDREN_EXTEND);

	BoundingSphere bounds = new BoundingSphere(new Point3d(), Double.MAX_VALUE);

	MouseZoom zoomBehavior = new MouseZoom(axesTransformation);
	zoomBehavior.setSchedulingBounds(bounds);
	axesTransformation.addChild(zoomBehavior);

	MouseRotate rotateBehavior = new MouseRotate(axesTransformation);
	rotateBehavior.setSchedulingBounds(bounds);
        axesTransformation.addChild(rotateBehavior);

	BranchGroup head = new BranchGroup();
	head.addChild(axesTransformation);
	head.addChild(viewPlatform);
	axes = new Axes(axesTransformation, radius, length);

	scene.getRoot().addChild(head);
	scene.makeLive();

	return canvas;
    }

    public void actionPerformed(ActionEvent e) {
	if(e.getSource() == visibleBox) {
	    axes.setVisible(visibleBox.isSelected());
	}
    }

    public static void main(String[] args) {
	new MainFrame(new AxesTest(), 400, 300);
    }
}

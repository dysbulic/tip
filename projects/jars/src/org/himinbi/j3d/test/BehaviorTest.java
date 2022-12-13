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

public class BehaviorTest extends JApplet
    implements ActionListener, MouseBehaviorCallback {
    Vector3dPanel vectorPanel;
    JButton vectorButton = new JButton("Compute");
    Matrix4dPanel matrixPanel;
    JButton matrixButton = new JButton("Compute");
    TransformGroup axesTransformation;
    JTextField scaleField = new JTextField("0");
    JButton scaleButton = new JButton("Compute");
    JButton resetButton = new JButton("Reset");
    JCheckBox visibleBox;

    SimpleScene scene;

    public BehaviorTest() {
	GridBagLayout layout = new GridBagLayout();
	GridBagConstraints gridbag = new GridBagConstraints();
	getContentPane().setLayout(layout);

	gridbag.gridwidth = GridBagConstraints.RELATIVE;
        
	gridbag.weightx = 1;
	gridbag.weighty = 1;
	gridbag.gridheight = 9;
	gridbag.fill = GridBagConstraints.BOTH;

	Canvas3D canvas = createCanvas();
	layout.setConstraints(canvas, gridbag);
	getContentPane().add(canvas);

	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	gridbag.weightx = 0;
	gridbag.weighty = 0;
	gridbag.gridheight = 1;

	matrixPanel = new Matrix4dPanel(new Matrix4d());
	layout.setConstraints(matrixPanel, gridbag);
	getContentPane().add(matrixPanel);

	layout.setConstraints(matrixButton, gridbag);
	getContentPane().add(matrixButton);	
	matrixButton.addActionListener(this);

	vectorPanel = new Vector3dPanel(new Vector3d());
	layout.setConstraints(vectorPanel, gridbag);
	getContentPane().add(vectorPanel);

	layout.setConstraints(vectorButton, gridbag);
	getContentPane().add(vectorButton);	
	vectorButton.addActionListener(this);

	JLabel label = new JLabel("Scale:");
	layout.setConstraints(label, gridbag);
	getContentPane().add(label);
	
	gridbag.insets.left = 5;
	scaleField.addActionListener(this);
	layout.setConstraints(scaleField, gridbag);
	getContentPane().add(scaleField);

	gridbag.insets.left = 0;
	scaleButton.addActionListener(this);
	layout.setConstraints(scaleButton, gridbag);
	getContentPane().add(scaleButton);

	resetButton.addActionListener(this);
	layout.setConstraints(resetButton, gridbag);
	getContentPane().add(resetButton);
    }

    private Canvas3D createCanvas() {
	float length = 1f;
	float radius = .05f;

	View view = new View();
        view.setPhysicalBody(new PhysicalBody());
        view.setPhysicalEnvironment(new PhysicalEnvironment());
	view.setBackClipDistance(5000);
	Canvas3D canvas = new Canvas3D(SimpleUniverse.getPreferredConfiguration());
	view.addCanvas3D(canvas);
	ViewPlatform viewPlatform = new ViewPlatform();
	view.attachViewPlatform(viewPlatform);
	
	scene = new SimpleScene(canvas, length);

	new Axes(scene.getRoot(), radius, length);

	BoundingSphere bounds = new BoundingSphere(new Point3d(), Double.MAX_VALUE);

	Transform3D axesTransform = new Transform3D();
	axesTransform.setTranslation(new Vector3f(0, 0, length));
	scene.getRoot().setTransform(axesTransform);
	
	scene.getRoot().setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	scene.getRoot().setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	MouseSphereRotate rotateBehavior = new MouseSphereRotate(scene.getRoot());
	rotateBehavior.setSchedulingBounds(bounds);
	rotateBehavior.setupCallback(this);
        scene.getRoot().addChild(rotateBehavior);

	Transform3D transform = new Transform3D();
	transform.setTranslation(new Vector3f(0f, length / 5f, length * 5f));
	TransformGroup viewTransformation = new TransformGroup(transform);
	viewTransformation.addChild(viewPlatform);
	scene.getHead().addChild(viewTransformation);

	scene.makeLive();

	return canvas;
    }

    public void transformChanged(int type, Transform3D transform) {
	Matrix4d matrix = new Matrix4d();
	transform.get(matrix);
	matrixPanel.setMatrix(matrix);
	scaleField.setText(Double.toString(transform.getScale()));
    }

    public void actionPerformed(ActionEvent e) {
	Transform3D transform = new Transform3D();
	if(e.getSource() == vectorButton) {
	    transform.setEuler(vectorPanel.getVector());
	} else if(e.getSource() == matrixButton) {
	    transform.set(matrixPanel.getMatrix());
	} else if(e.getSource() == scaleButton) {
	    try {
		transform.setScale(Double.parseDouble(scaleField.getText()));
	    } catch(NumberFormatException ex) {
		scaleField.setText("0");
		return;
	    }
	} else if(e.getSource() == resetButton) {
	    vectorPanel.setVector(new Vector3d());
	}
	try {
	    scene.getRoot().setTransform(transform);
	    transformChanged(0, transform);
	} catch(BadTransformException ex) {
	    JOptionPane.showMessageDialog(null, "Non-affine transform");
	}
    }

    public static void main(String[] args) {
	new MainFrame(new BehaviorTest(), 400, 300);
    }
}

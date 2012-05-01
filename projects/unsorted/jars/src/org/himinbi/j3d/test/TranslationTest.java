/**
 * This is a little test program to show a bug in the implementation of
 * the 1.2 api. As the distance increases beyond ~100000 meters the
 * translations of different shapes become non-uniform. This does not
 * occur in the 1.1 implementation.
 *
 * As always I could not be contented not to play a bit so the scene
 * is that of a satellite orbiting the earth. It is possible to texture
 * map the earth using the -t argument on the command line. Also the s
 * key will sync the earth and orbit speeds. The v key will dump the
 * current status of program variables. Left, right, up, down, page up,
 * page down, home and end will change the distance with increasing
 * magnitudes of change.
 *
 * Another thing that I am curious about is the behavior of the rotation
 * interpolators when the alpha has an increasing duration of 1 or two. 
 * It doesn't look right to me though that might just be me. =)
 *
 * 2000/09/25 -- wjh
 */
package org.himinbi.j3d.test;

import javax.media.j3d.*;
import javax.vecmath.*;
import javax.swing.*;
import javax.swing.event.*;
import com.sun.j3d.utils.applet.*;
import com.sun.j3d.utils.universe.*;
import com.sun.j3d.utils.behaviors.mouse.*;
import com.sun.j3d.utils.geometry.*;
import com.sun.j3d.utils.image.TextureLoader;
import java.util.Vector;
import java.awt.*;
import java.awt.event.*;

public class TranslationTest extends JApplet
    implements AdjustmentListener, ChangeListener, KeyListener {
    JScrollBar zoomBar = new JScrollBar(Adjustable.HORIZONTAL);
    JScrollBar[] speedBar = new JScrollBar[3];
    String[] labelText = new String[3];
    Alpha[] rotationAlpha = new Alpha[3];
    JCheckBox[] pause = new JCheckBox[3];
    RotationInterpolator[] rotator = new RotationInterpolator[3];
    StatedCanvas3D canvas;
    Transform3D orbitTransform;
    TransformGroup orbitTranslation;

    public TranslationTest() {
	this(false);
    }

    public TranslationTest(boolean textureMapEarth) {
	GridBagLayout layout = new GridBagLayout();
	GridBagConstraints gridbag = new GridBagConstraints();
	getContentPane().setLayout(layout);

	gridbag.gridwidth = GridBagConstraints.REMAINDER;
        
	gridbag.weightx = 1;
	gridbag.weighty = 1;
	gridbag.fill = GridBagConstraints.BOTH;
	
	canvas = new StatedCanvas3D(SimpleUniverse.getPreferredConfiguration());
	canvas.addChangeListener(this);
	canvas.addKeyListener(this);
	layout.setConstraints(canvas, gridbag);
	getContentPane().add(canvas);

	gridbag.weightx = 0;
	gridbag.weighty = 0;
	JLabel label;

	gridbag.gridwidth = 1;
	
	label = new JLabel("Distance");
	layout.setConstraints(label, gridbag);
	getContentPane().add(label);
	
	gridbag.gridwidth = GridBagConstraints.REMAINDER;
	
	zoomBar.addAdjustmentListener(this);
	layout.setConstraints(zoomBar, gridbag);
	getContentPane().add(zoomBar);

	int i = 0;
	labelText[i++] = new String("Earth Speed");
	labelText[i++] = new String("Orbit Speed");
	labelText[i++] = new String("Body Speed");

	i = 0;
	int[] start = new int[3];
	start[i++] = 8500;
	start[i++] = 9300;
	start[i++] = 6000;
	
	for(i = 0; i < labelText.length; i++) {
	    gridbag.gridwidth = 1;

	    label = new JLabel(labelText[i]);
	    layout.setConstraints(label, gridbag);
	    getContentPane().add(label);

	    gridbag.gridwidth = GridBagConstraints.RELATIVE;
	    gridbag.weightx = 1;

	    speedBar[i] = new JScrollBar(Adjustable.HORIZONTAL);
	    speedBar[i].setMinimum(1);
	    speedBar[i].setMaximum(10000);
	    speedBar[i].setValue(start[i]);

	    speedBar[i].addAdjustmentListener(this);
	    speedBar[i].addKeyListener(this);
	    layout.setConstraints(speedBar[i], gridbag);
	    getContentPane().add(speedBar[i]);

	    gridbag.gridwidth = GridBagConstraints.REMAINDER;
	    gridbag.weightx = 0;

	    pause[i] = new JCheckBox("paused", false);
	    pause[i].addChangeListener(this);
	    pause[i].addKeyListener(this);
	    layout.setConstraints(pause[i], gridbag);
	    getContentPane().add(pause[i]);
	}
	
	setupScene(textureMapEarth);
    }

    private void setupScene(boolean textureMapEarth) {
	float length = 1f;
	float radius = .05f;
	float earthRadius = 4 * length;

	zoomBar.setMinimum((int)earthRadius);
	zoomBar.setMaximum(6000000);
	zoomBar.setValue((int)(earthRadius * 2));

	View view = new View();
        view.setPhysicalBody(new PhysicalBody());
        view.setPhysicalEnvironment(new PhysicalEnvironment());
	view.setBackClipDistance(10000);
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
             new Vector3f(-1f, -1f, -1f));
        light.setInfluencingBounds(bounds);
        head.addChild(light);

	TransformGroup[] rotation = new TransformGroup[rotator.length];

	for(int i = 0; i < rotator.length; i++) {
	    rotation[i] = new TransformGroup();
	    rotation[i].setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	    rotation[i].setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	    rotationAlpha[i] = new Alpha(-1, Alpha.INCREASING_ENABLE,
					 0, 0, speedBar[i].getValue(),
					 0, 0, 0, 0, 0);
	    rotator[i] = new RotationInterpolator(rotationAlpha[i],
						  rotation[i],
						  new Transform3D(), 0,
						  (float)Math.PI * 2f);
	    rotator[i].setSchedulingBounds(bounds);
	    rotation[i].addChild(rotator[i]);
	}

	head.addChild(rotation[0]);

	Color3f lightBlue = new Color3f(38f / 255f, 229f / 255f, 255f / 255f);
        Color3f red = new Color3f(250f / 255f, 12f / 255f, 12f / 255f);
        Color3f green = new Color3f(48f / 255f, 119f / 255f, 41f / 255f);
        Color3f purple = new Color3f(42f / 255f, 14f / 255f, 58f / 255f);
        Color3f black = new Color3f(0, 0, 0);
        Color3f white = new Color3f(1, 1, 1);

	Appearance appearance = new Appearance();
	appearance.setMaterial(new Material(black, purple, white, green, .7f));

	if(textureMapEarth) {
	    String filename = "images/photo.jpg";
            TextureLoader texture = new TextureLoader(filename, "RGB", canvas);
            if(texture != null && texture.getTexture() != null) {
                appearance.setTexture(texture.getTexture());
            } else {
                System.out.println("No texture loaded from \"" + filename + "\"");
            }
	}

	rotation[0].addChild(new Sphere(earthRadius,
					Sphere.GENERATE_NORMALS | Sphere.GENERATE_TEXTURE_COORDS,
					180, appearance));
	
	head.addChild(rotation[1]);

	orbitTransform = new Transform3D();
	orbitTransform.setTranslation(new Vector3d(0f, 0f, zoomBar.getValue()));
	orbitTranslation = new TransformGroup(orbitTransform);
	orbitTranslation.setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	orbitTranslation.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	rotation[1].addChild(orbitTranslation);

	orbitTranslation.addChild(rotation[2]);

	Transform3D zAxis = new Transform3D();
	zAxis.rotX(Math.PI / 2);
	rotator[2].setTransformAxis(zAxis);

	rotation[2].addChild(createBody());

	TransformGroup viewRotateTransformation = new TransformGroup();
	viewRotateTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	viewRotateTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);	
	orbitTranslation.addChild(viewRotateTransformation);

	MouseRotate rotateBehavior = new MouseRotate(viewRotateTransformation);
	rotateBehavior.setSchedulingBounds(bounds);
        viewRotateTransformation.addChild(rotateBehavior);

	Transform3D transform = new Transform3D();
	transform.setTranslation(new Vector3f(0f, -length / 4, length * 5));
	TransformGroup viewZoomTransformation = new TransformGroup(transform);
	viewZoomTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	viewZoomTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);	
	viewRotateTransformation.addChild(viewZoomTransformation);
	viewZoomTransformation.addChild(viewPlatform);

	MouseZoom zoomBehavior = new MouseZoom(viewZoomTransformation);
	zoomBehavior.setSchedulingBounds(bounds);
        viewZoomTransformation.addChild(zoomBehavior);	

	locale.addBranchGraph(head);
    }

    public void adjustmentValueChanged(AdjustmentEvent e) {
	if(e.getSource() == zoomBar && orbitTranslation != null) {
	    orbitTransform.setTranslation(new Vector3d(0f, 0f, zoomBar.getValue()));
	    orbitTranslation.setTransform(orbitTransform);
	} else {
	    for(int i = 0; i < speedBar.length; i++) {
		if(e.getSource() == speedBar[i] && rotationAlpha[i] != null) {
		    rotationAlpha[i].setIncreasingAlphaDuration(speedBar[i].getValue());
		    break;
		}
	    }
	}
    }

    public void stateChanged(ChangeEvent e) {
	if(e.getSource() == canvas) {
	    Graphics g = canvas.getGraphics();
	    g.setColor(Color.white);
	    g.drawString("Distance: " + zoomBar.getValue() + "m", 5, 15);
	} else {
	    for(int i = 0; i < pause.length; i++) {
		if(e.getSource() == pause[i]) {
		    rotator[i].setEnable(!pause[i].isSelected());
		    break;
		}
	    }
	}
    }

    public void keyPressed(KeyEvent e) {
        if(e.getKeyCode() == KeyEvent.VK_UP) {
	    zoomBar.setValue(zoomBar.getValue() + 10);
	} else if(e.getKeyCode() == KeyEvent.VK_DOWN) {
	    zoomBar.setValue(zoomBar.getValue() - 10);
	} else if(e.getKeyCode() == KeyEvent.VK_RIGHT) {
	    zoomBar.setValue(zoomBar.getValue() + 1);
	} else if(e.getKeyCode() == KeyEvent.VK_LEFT) {
	    zoomBar.setValue(zoomBar.getValue() - 1);
	} else if(e.getKeyCode() == KeyEvent.VK_PAGE_UP) {
	    zoomBar.setValue(zoomBar.getValue() + 1000);
	} else if(e.getKeyCode() == KeyEvent.VK_PAGE_DOWN) {
	    zoomBar.setValue(zoomBar.getValue() - 1000);
	} else if(e.getKeyCode() == KeyEvent.VK_HOME) {
	    zoomBar.setValue(zoomBar.getMaximum());
	} else if(e.getKeyCode() == KeyEvent.VK_END) {
	    zoomBar.setValue(zoomBar.getMinimum());
	} else if(e.getKeyCode() == KeyEvent.VK_S) {
	    speedBar[0].setValue(speedBar[1].getValue());
	} else if(e.getKeyCode() == KeyEvent.VK_V) {
	    StringBuffer info = new StringBuffer(); 
	    info.append("Distance: " + zoomBar.getValue() + "\n");
	    for(int i = 0; i < labelText.length; i++) {
		info.append(labelText[i] + ": " + speedBar[i].getValue() + "\n");
	    }
	    JOptionPane.showMessageDialog(null, info.toString());
	}
    }

    public void keyReleased(KeyEvent e) {}
    public void keyTyped(KeyEvent e) {}    

    public static void main(String[] args) {
	boolean textureMapEarth = false;

	for(int i = 0; i < args.length; i++) {
	    if(args[i].toLowerCase().startsWith("-t")) {
		textureMapEarth = true;
	    }
	}

	new MainFrame(new TranslationTest(textureMapEarth), 500, 550);
    }

    class SizeVector extends Vector {
	Size getSize(int index) {
	    return (Size)get(index);
	}
    }
    
    class Size {
	float radius = 0;
	float length = 0;
	    
	Size(float radius, float length) {
	    this.radius = radius;
	    this.length = length;
	}
    }

    public Node createBody() {	
	int i = 0, j = 0;
	SizeVector size = new SizeVector();
	
	size.add(new Size(.22f, .22f));
	size.add(new Size(.25f, .05f));
	size.add(new Size(.18f, .62f));
	size.add(new Size(.12f, .03f));
	size.add(new Size(.11f, .12f));
	size.add(new Size(.08f, .04f));
	size.add(new Size(.07f, .07f));
	size.add(new Size(.07f, .04f));
	size.add(new Size(.04f, .29f));

	Color3f silver = new Color3f(127f / 255f, 121f / 255f, 121f / 255f);
	Color3f black = new Color3f(0, 0, 0);
	Color3f white = new Color3f(1, 1, 1);

	Appearance appearance = new Appearance();
	appearance.setMaterial(new Material
	    (black, silver, white, silver, .7f));
	
	int coneIndex = 2;
	
	float extraConeLength = 0;
	for(i = coneIndex + 1; i < size.size(); i++) {
	    extraConeLength += size.getSize(i).length;
	}
	size.getSize(coneIndex).length += extraConeLength;
	
	Transform3D transform;
	TransformGroup transformation;
	
	Transform3D frameTransform = new Transform3D();
	frameTransform.rotX(-7 * Math.PI / 16);
	TransformGroup frameTransformation = new TransformGroup(frameTransform);
	
	Transform3D cgTransform = new Transform3D();
	cgTransform.setTranslation(new Vector3f(0f, .23f, 0));
	TransformGroup cgTransformation = new TransformGroup(cgTransform);
	frameTransformation.addChild(cgTransformation);
	
	float offset = 0;
	for(i = 0; i < size.size(); i++, offset = 0) {
	    if(i != coneIndex) {
		offset = size.getSize(i).length / 2;
		offset += size.getSize(coneIndex).length / 2;
		for(j = Math.min(coneIndex, i) + 1; j <= Math.max(coneIndex, i) - 1; j++) {
		    offset += size.getSize(j).length;
		}
		if(i > coneIndex) {
		    offset -= extraConeLength;
		} else {
		    offset = -offset;
		}
		transform = new Transform3D();
		transform.setTranslation(new Vector3f(0f, offset, 0f));
		transformation = new TransformGroup(transform);
		transformation.addChild(new Cylinder(size.getSize(i).radius, size.getSize(i).length, appearance));
	    } else {
		transformation = new TransformGroup();
		transformation.addChild
                    (new Cone(size.getSize(i).radius, size.getSize(i).length, Primitive.GENERATE_NORMALS, appearance));
	    }
	    cgTransformation.addChild(transformation);
	}
	return frameTransformation;
    }
}

class StatedCanvas3D extends Canvas3D {
    EventListenerList listenerList = new EventListenerList();

    public StatedCanvas3D(GraphicsConfiguration graphicsConfiguration) {
	super(graphicsConfiguration);
    }

    public void addChangeListener(ChangeListener listener) {
        listenerList.add(ChangeListener.class, listener);
    }

    public void removeChangeListener(ChangeListener listener) {
        listenerList.remove(ChangeListener.class, listener);
    }

    public void postSwap() {
	Object[] listeners = listenerList.getListenerList();
	ChangeEvent event = new ChangeEvent(this);

	for(int i = listeners.length - 2; i >= 0; i -= 2) {
	    if(listeners[i] == ChangeListener.class) {
		if(event == null) {
		    event = new ChangeEvent(this);
		}
		((ChangeListener)listeners[i + 1]).stateChanged(event);
	    }
	}
    }
}

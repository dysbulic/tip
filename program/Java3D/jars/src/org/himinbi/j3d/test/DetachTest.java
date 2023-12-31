package org.himinbi.j3d.test;

import java.awt.event.*;
import javax.swing.*;
import javax.media.j3d.*;
import javax.vecmath.*;
import com.sun.j3d.utils.universe.*;
import com.sun.j3d.utils.behaviors.mouse.*;
import com.sun.j3d.utils.applet.MainFrame;
import com.sun.j3d.utils.geometry.ColorCube;

public class DetachTest extends JApplet implements KeyListener {
    int speed;
    static float defaultLength = 1;
    static double defaultCenterOffset = 3;
    static int defaultNumSections = 20;
    static int defaultSpeed = 3000;
    Alpha rotationAlpha;
    BranchGroup[] cubeGroup;
    TransformGroup[] offset;
    //RotationInterpolator[] rotator;
    TransformInterpolator[] rotator;
    TransformGroup[] initialPosition;
    Group holder;
    
    final int UP = 1;
    final int DOWN = 2;
    int direction = 0;
    int index = 0;
    boolean paused = false;
    Vector3d offsetVector = new Vector3d();

    public DetachTest() {
	this(defaultLength, defaultCenterOffset, defaultNumSections, defaultSpeed);
    }

    public DetachTest(float length) {
	this(length, defaultCenterOffset, defaultNumSections, defaultSpeed);
    }

    public DetachTest(float length, double centerOffset) {
	this(length, centerOffset, defaultNumSections, defaultSpeed);
    }

    public DetachTest(float length, double centerOffset, int numSections) {
	this(length, centerOffset, numSections, defaultSpeed);
    }

    public DetachTest(float length, double centerOffset, int numSections, int speed) {
	this.speed = speed;

	VirtualUniverse universe = new VirtualUniverse();
	Locale locale = new Locale(universe);
        BranchGroup head = new BranchGroup();
	holder = new Group();
	holder.setCapability(Group.ALLOW_CHILDREN_WRITE);
	holder.setCapability(Group.ALLOW_CHILDREN_EXTEND);

        BoundingSphere bounds = new BoundingSphere(new Point3d(), Double.MAX_VALUE);

        Background background = new Background(new Color3f(29f / 255f, 43f / 255f, 153f / 255f));
        background.setApplicationBounds(bounds);
        head.addChild(background);

	TransformGroup rotationTransformation = new TransformGroup();
	rotationTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	rotationTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	head.addChild(rotationTransformation);
	
	MouseRotate rotateBehavior = new MouseRotate(rotationTransformation);
        rotateBehavior.setSchedulingBounds(bounds);
        rotationTransformation.addChild(rotateBehavior);
	
	numSections = Math.max(numSections, 1);
	offsetVector = new Vector3d(centerOffset, 0, 0);
	Transform3D rotation;
	Transform3D translation = new Transform3D();
	translation.setTranslation(offsetVector);
	initialPosition = new TransformGroup[numSections];
	TransformGroup[] interpolator = new TransformGroup[numSections];
	offset = new TransformGroup[numSections];
	cubeGroup = new BranchGroup[numSections];
	rotationAlpha = new Alpha(-1, Alpha.INCREASING_ENABLE,
					0, 0,
					speed, 0, 0,
					0, 0, 0);
	Transform3D axis = new Transform3D();
	rotator = new RotationInterpolator[numSections];

	for(index = 0; index < numSections; index++) {
	    axis.rotX(index * Math.PI * 2 / numSections);
	    rotation = new Transform3D();
	    rotation.rotZ(index * Math.PI * 2 / numSections);
	    rotation.mul(translation);
	    initialPosition[index] = new TransformGroup(rotation);
	    initialPosition[index].setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	    initialPosition[index].setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	    rotationTransformation.addChild(initialPosition[index]);
	    interpolator[index] = new TransformGroup();
	    interpolator[index].setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	    interpolator[index].setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	    initialPosition[index].addChild(interpolator[index]);
	    offset[index] = new TransformGroup(translation);
	    offset[index].setCapability(Group.ALLOW_CHILDREN_WRITE);
	    offset[index].setCapability(Group.ALLOW_CHILDREN_EXTEND);
	    offset[index].setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	    interpolator[index].addChild(offset[index]);
	    cubeGroup[index] = new BranchGroup();
	    cubeGroup[index].setCapability(BranchGroup.ALLOW_DETACH);
	    offset[index].addChild(cubeGroup[index]);
	    cubeGroup[index].addChild(new ColorCube(length));
	    rotator[index] = new RotationInterpolator(rotationAlpha,
                                                       interpolator[index],
                                                       axis, 0,
                                                       (float)Math.PI * 2f);
	    rotator[index].setSchedulingBounds(bounds);
	    interpolator[index].addChild(rotator[index]);
	}

	index = numSections - 1;
	direction = DOWN;
	
        Canvas3D canvas = new Canvas3D(SimpleUniverse.getPreferredConfiguration());
        View view = new View();
        view.setPhysicalBody(new PhysicalBody());
        view.setPhysicalEnvironment(new PhysicalEnvironment());
	view.setBackClipDistance(5000);
        view.addCanvas3D(canvas);
        ViewPlatform viewPlatform = new ViewPlatform();
        view.attachViewPlatform(viewPlatform);

	translation.setTranslation(new Vector3d(0, 0, (length + centerOffset) * 7));
	TransformGroup viewTransformation = new TransformGroup(translation);
	viewTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	viewTransformation.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	head.addChild(viewTransformation);
	viewTransformation.addChild(viewPlatform);
	
	MouseZoom zoomBehavior = new MouseZoom(viewTransformation);
        zoomBehavior.setSchedulingBounds(bounds);
        viewTransformation.addChild(zoomBehavior);

	getContentPane().add(canvas);
	canvas.addKeyListener(this);
	addKeyListener(this);

	head.compile();
	locale.addBranchGraph(head);
    }

    public void keyPressed(KeyEvent e) {
	if(e.getKeyCode() == KeyEvent.VK_UP) {
	    speed -= 10;
	    rotationAlpha.setIncreasingAlphaDuration(speed);
	} else if(e.getKeyCode() == KeyEvent.VK_DOWN) {
	    speed += 10;
	    rotationAlpha.setIncreasingAlphaDuration(speed);
	} else if(e.getKeyCode() == KeyEvent.VK_PAGE_UP) {
	    speed -= 500;
	    rotationAlpha.setIncreasingAlphaDuration(speed);
	} else if(e.getKeyCode() == KeyEvent.VK_PAGE_DOWN) {
	    speed += 500;
	    rotationAlpha.setIncreasingAlphaDuration(speed);
	} else if(e.getKeyCode() == KeyEvent.VK_P) {
	    paused = !paused;
	    if(paused) {
		rotationAlpha.setIncreasingAlphaDuration(0);
	    } else {
		rotationAlpha.setIncreasingAlphaDuration(speed);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_Q) {
	    Transform3D axis = new Transform3D();
	    for(int i = 0; i < rotator.length; i++) {
		if(i % 2 == 0) {
		    axis.rotX(Math.PI / 2);
		} else {
		    axis.rotX(-Math.PI / 2);
		}
		rotator[i].setTransformAxis(axis);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_W) {
	    Transform3D axis = new Transform3D();
	    for(int i = 0; i < rotator.length; i++) {
		axis.rotX(i * Math.PI * 2 / rotator.length);
		rotator[i].setTransformAxis(axis);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_E) {
	    Transform3D axis = new Transform3D();
	    for(int i = 0; i < rotator.length; i++) {
		if(i < rotator.length / 2) {
		    axis.rotX(i * Math.PI * 2 / rotator.length);
		} else {
		    axis.rotX(-i * Math.PI * 2 / rotator.length);
		}
		rotator[i].setTransformAxis(axis);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_A) {
	    Transform3D axis = new Transform3D();
	    axis.rotX(Math.PI / 2);
	    for(int i = 0; i < rotator.length; i++) {
		rotator[i].setTransformAxis(axis);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_S) {
	    Transform3D axis = new Transform3D();
	    axis.rotY(Math.PI / 2);
	    for(int i = 0; i < rotator.length; i++) {
		rotator[i].setTransformAxis(axis);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_D) {
	    Transform3D axis = new Transform3D();
	    axis.rotZ(Math.PI / 2);
	    for(int i = 0; i < rotator.length; i++) {
		rotator[i].setTransformAxis(axis);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_Z) {
	    Transform3D axis = new Transform3D();
	    for(int i = 0; i < rotator.length; i++) {
		axis.setEuler(new Vector3d(Math.random() * Math.PI * 2,
					   Math.random() * Math.PI * 2,
					   Math.random() * Math.PI * 2));
		rotator[i].setTransformAxis(axis);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_X) {
	    Transform3D axis = new Transform3D();
	    for(int i = 0; i < rotator.length; i++) {
		axis.setEuler(new Vector3d(i * Math.PI * 2 / rotator.length,
					   i * Math.PI * 2 / rotator.length,
					   i * Math.PI * 2 / rotator.length));
		rotator[i].setTransformAxis(axis);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_C) {
	    Transform3D axis = new Transform3D();
	    for(int i = 0; i < rotator.length; i++) {
		axis.rotZ(i * Math.PI * 2 / rotator.length);
		rotator[i].setTransformAxis(axis);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_NUMPAD8) {
	    Transform3D translation = new Transform3D();
	    Transform3D position = new Transform3D();
	    offsetVector.x += .5;
	    translation.setTranslation(offsetVector);
	    for(int i = 0; i < offset.length; i++) {
		initialPosition[i].getTransform(position);
		position.setTranslation(new Vector3d());
		position.mul(translation);
		initialPosition[i].setTransform(position);
		offset[i].setTransform(translation);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_NUMPAD2) {
	    Transform3D translation = new Transform3D();
	    Transform3D position = new Transform3D();
	    offsetVector.x -= .5;
	    translation.setTranslation(offsetVector);
	    for(int i = 0; i < offset.length; i++) {
		initialPosition[i].getTransform(position);
		position.setTranslation(new Vector3d());
		position.mul(translation);
		initialPosition[i].setTransform(position);
		offset[i].setTransform(translation);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_NUMPAD6) {
	    Transform3D translation = new Transform3D();
	    Transform3D position = new Transform3D();
	    offsetVector.y += .5;
	    translation.setTranslation(offsetVector);
	    for(int i = 0; i < offset.length; i++) {
		initialPosition[i].getTransform(position);
		position.setTranslation(new Vector3d());
		position.mul(translation);
		initialPosition[i].setTransform(position);
		offset[i].setTransform(translation);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_NUMPAD4) {
	    Transform3D translation = new Transform3D();
	    Transform3D position = new Transform3D();
	    offsetVector.y -= .5;
	    translation.setTranslation(offsetVector);
	    for(int i = 0; i < offset.length; i++) {
		initialPosition[i].getTransform(position);
		position.setTranslation(new Vector3d());
		position.mul(translation);
		initialPosition[i].setTransform(position);
		offset[i].setTransform(translation);
	    }
	} else if(e.getKeyCode() == KeyEvent.VK_R) {
	    if(index < 0) {
		index = 0;
		direction = UP;
	    } else if(index >= cubeGroup.length) {
		index = cubeGroup.length - 1;
		direction = DOWN;
	    }

	    if(direction == DOWN) {
		holder.moveTo(cubeGroup[index]);
		index--;
	    } else if(direction == UP) {
		offset[index].moveTo(cubeGroup[index]);
		index++;
	    }
	    return;
	}
    }

    public void keyReleased(KeyEvent e) {}
    public void keyTyped(KeyEvent e) {}

    public static void main(String[] args) {
	float length = DetachTest.defaultLength;
	double offset = DetachTest.defaultCenterOffset;
	int numSections  = DetachTest.defaultNumSections;
	int speed  = DetachTest.defaultSpeed;
	boolean error = false;

	for(int i = 0; i < args.length; i++) {
	    if(args[i].length() > 2) {
		if(args[i].toLowerCase().startsWith("-l")) {
		    try {
			length = Float.parseFloat(args[i].substring(2));
		    } catch(NumberFormatException e) {
			System.err.println("\"" + args[i].substring(2) + "\" is not a valid float.");
			error = true;
		    }
		} else if(args[i].toLowerCase().startsWith("-o")) {
		    try {
			offset = Double.parseDouble(args[i].substring(2));
		    } catch(NumberFormatException e) {
			System.err.println("\"" + args[i].substring(2) + "\" is not a valid double.");
			error = true;
		    }
		} else if(args[i].toLowerCase().startsWith("-n")) {
		    try {
			numSections = Integer.parseInt(args[i].substring(2));
		    } catch(NumberFormatException e) {
			System.err.println("\"" + args[i].substring(2) + "\" is not a valid integer.");
			error = true;
		    }
		} else if(args[i].toLowerCase().startsWith("-s")) {
		    try {
			speed = Integer.parseInt(args[i].substring(2));
		    } catch(NumberFormatException e) {
			System.err.println("\"" + args[i].substring(2) + "\" is not a valid integer.");
			error = true;
		    }
		} else {
		    System.err.println("Unknown option \"" + args[i].substring(0, 2) + "\"");
		    error = true;
		}
	    } else {
		if(args[i].toLowerCase().startsWith("-h")) {
		    error = true;
		} else {
		    System.err.println("Unknown option \"" + args[i] + "\"");
		    error = true;
		}
	    }
	}
	if(error) {
	    System.out.println("Usage:");
	    System.out.println("  DetachTest -l(float) -h(double) -n(integer) -s(integer) -h");
	    System.out.println("   -l => length => Sets the size of the color cubes");
	    System.out.println("   -o => offset => Sets rotation distance");
	    System.out.println("   -n => number => Sets the number of cubes");
	    System.out.println("   -s => speed => Sets the delay in milliseconds");
	    System.out.println("   -h => help => Prints this message");
	    System.out.println("");
	    System.out.println(" Once the program is started pressing the up and down keys will");
	    System.out.println("  increase the delay by 10 millisecond and pgup and pgdown will");
	    System.out.println("  increase it by 500. Also the p key will toggle it between its");
	    System.out.println("  current state and 0. Some setting like -n50 -o0 are pretty.");
	    System.out.println("  (Well, if your machine can get better than .3fps.) =)");
	    System.out.println("");
	    System.out.println(" The purpose of this program is to illustrate some bugs in the java");
	    System.out.println("  3d libraries. Whenever the r key is pressed one of the cubes will");
	    System.out.println("  be removed from the scene graph and attached to a group not in the");
	    System.out.println("  scene using the Group.moveTo(BranchGroup) method. In the 1.1");
	    System.out.println("  implementation the cubes do not disappear when removed, or at least");
	    System.out.println("  not until they are begun to be put back in. In the 1.2 implementation");
	    System.out.println("  they disappear, but if you add and remove them too quickly you get");
	    System.out.println("  a null pointer exception and rendering stops.");
	} else {
	    new MainFrame(new DetachTest(length, offset, numSections, speed), 400, 300);
	}
    }
}

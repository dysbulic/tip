package org.himinbi.j3d.test;

import javax.xml.parsers.*;
import org.w3c.dom.*;
import org.w3c.dom.Node;
import org.xml.sax.*;
import javax.swing.*;
import javax.swing.event.ChangeListener;
import javax.swing.event.ChangeEvent;
import java.io.*;
import java.net.*;
import java.util.*;
import java.awt.*;
import java.awt.event.*;
import java.awt.image.*;
import java.awt.font.*;
import javax.media.j3d.*;
import javax.media.j3d.Locale;
import javax.vecmath.*;
import java.text.*;
import java.beans.PropertyChangeListener;
import java.beans.PropertyChangeEvent;

import com.sun.j3d.utils.behaviors.vp.OrbitBehavior;
import com.sun.j3d.utils.behaviors.mouse.*;
import com.sun.j3d.utils.image.TextureLoader;
import com.sun.j3d.utils.applet.MainFrame;
import com.sun.j3d.utils.geometry.*;
import com.sun.j3d.utils.universe.*;

import org.j3d.ui.navigation.MouseViewHandler;
import org.j3d.ui.navigation.MouseViewBehavior;
import org.j3d.ui.navigation.NavigationState;
import com.xith.java3d.overlay.*;

public class ViewPlatformTest extends JApplet {
    Hashtable cameras = new Hashtable();
    BranchGroup cameraMount = new BranchGroup();
    LabelOverlay translationLabel;
    LabelOverlay cameraLabel;
    TransformGroup viewTransform;
    
    public ViewPlatformTest() {
	getContentPane().setLayout(new BorderLayout());
	final ViewPlatformTestSettingsPanel settingsPanel =
	    new ViewPlatformTestSettingsPanel(60,                          // number of spheres
					      6,                           // number of spirals
					      5,                           // number of camera mounts
					      new Point3d(1, 6.5, 0),      // initial camera position
					      new Vector3d(0, 0, 2),       // sphere offset
					      Color.white,                 // ambient color
					      Color.black,                 // diffuse color
					      1f,                          // saturation
					      120f,                        // shininess: 1 - 128
					      .25f,                        // radius
					      new Color(29f  / 255f,       // background color
							43f  / 255f,
							153f / 255f),
					      new Color(200f / 255f,       // light color
							200f / 255f,
							200f / 255f),
					      new Vector3f(0f, 0f, -1f));  // light direction
	getContentPane().add(settingsPanel, BorderLayout.CENTER);
	final JButton okButton = new JButton("Ok");
	okButton.addActionListener(new ActionListener() {
		public void actionPerformed(ActionEvent e) {
		    Container container = (Container)okButton.getParent();
		    container.remove(settingsPanel);
		    container.remove(okButton);
		    setupScene(settingsPanel);
		    validate();
		    repaint();
		}
	    });
	getContentPane().add(okButton, BorderLayout.SOUTH);
    }

    public void setupScene(ViewPlatformTestSettings settings) {
	// Make menus heavyweight so they show up
	JPopupMenu.setDefaultLightWeightPopupEnabled(false); 

	JMenuBar menuBar = new JMenuBar();
	setJMenuBar(menuBar);

	JMenu fileMenu = new JMenu("File");
	fileMenu.setMnemonic(KeyEvent.VK_F);
	menuBar.add(fileMenu);

        GraphicsConfigTemplate3D template = new GraphicsConfigTemplate3D();
        GraphicsConfiguration config = GraphicsEnvironment.getLocalGraphicsEnvironment().
            getDefaultScreenDevice().getBestConfiguration(template);
        Canvas3D canvas = new Canvas3D(config);

	Vector cameraMounts = new Vector();
	createScene(settings, canvas, cameraMounts);

	ActionListener cameraListener = new ActionListener() {
		public void actionPerformed(ActionEvent e) {
		    cameraLabel.setText(((AbstractButton)e.getSource()).getText());
		    ((Group)cameras.get(e.getSource())).moveTo(cameraMount);
		}
	    };

	ButtonGroup cameraMenuButtons = new ButtonGroup();
	Enumeration menuItems = cameraMounts.elements();
	int cameraCount = 0;
	while(menuItems.hasMoreElements()) {
	    JRadioButtonMenuItem cameraMenuItem =
		new JRadioButtonMenuItem("Camera: " + (++cameraCount));
	    cameraMenuItem.addActionListener(cameraListener);
	    cameraMenuItem.setSelected(true);
	    cameraMenuButtons.add(cameraMenuItem);
	    fileMenu.add(cameraMenuItem);
	    cameras.put(cameraMenuItem, menuItems.nextElement());
	}

	fileMenu.addSeparator();

	JMenuItem exitItem = new JMenuItem("Exit", KeyEvent.VK_X);
	exitItem.setAccelerator(KeyStroke.getKeyStroke(KeyEvent.VK_X, ActionEvent.ALT_MASK));
	fileMenu.add(exitItem);
	exitItem.addActionListener(new ActionListener() {
		public void actionPerformed(ActionEvent e) {
		    System.exit(0);
		}
	    });

        getContentPane().add(canvas, BorderLayout.CENTER);
        canvas.requestFocus();
    }

    private void createScene(ViewPlatformTestSettings settings, Canvas3D canvas, Vector cameraMounts) {
	VirtualUniverse universe = new VirtualUniverse();
	Locale locale = new Locale(universe);
	View view = new View();
	BranchGroup sceneRoot = new BranchGroup();

	view.setPhysicalBody(new PhysicalBody());
	view.setPhysicalEnvironment(new PhysicalEnvironment());
	view.setBackClipDistance(1000000);
	view.addCanvas3D(canvas);

        BoundingSphere bounds = new BoundingSphere(new Point3d(), 100000);

	Transform3D viewTranslation = new Transform3D();
        viewTranslation.lookAt(settings.getCameraPosition(),
			       new Point3d(0, 0, 0),
			       new Vector3d(0, 1, 0));
	viewTranslation.invert();
	viewTransform = new TransformGroup(viewTranslation);
	viewTransform.setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	viewTransform.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);

	Rectangle overlaySize = new Rectangle(10, 10, 200, 20);
	translationLabel = new LabelOverlay(canvas, overlaySize, "Distance");
	translationLabel.setColor(Color.white);
	viewTransform.addChild(translationLabel.getRoot());

	overlaySize = new Rectangle(10, 10, 100, 20);
	cameraLabel = new LabelOverlay(canvas, overlaySize);
	cameraLabel.setRelativePosition(Overlay.PLACE_RIGHT, Overlay.PLACE_BOTTOM);
	cameraLabel.setColor(Color.white);
	viewTransform.addChild(cameraLabel.getRoot());	

	overlaySize = new Rectangle(10, 10, 100, 20);
	LabelOverlay fpsLabel = new LabelOverlay(canvas, overlaySize, "FPS") {
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
	fpsLabel.setColor(Color.white);
	fpsLabel.setRelativePosition(Overlay.PLACE_RIGHT, Overlay.PLACE_TOP);
        viewTransform.addChild(fpsLabel.getRoot());

	BranchGroup cameraBase = new BranchGroup();
	cameraBase.setCapability(Group.ALLOW_CHILDREN_EXTEND);
	sceneRoot.addChild(cameraBase);
	cameraMounts.add(cameraBase);

	cameraMount.setCapability(BranchGroup.ALLOW_DETACH);
	cameraBase.addChild(cameraMount);
	cameraMount.addChild(viewTransform);

	ViewPlatform viewPlatform = new ViewPlatform();
	viewTransform.addChild(viewPlatform);
	view.attachViewPlatform(viewPlatform);

	MouseViewHandler viewHandler = new MouseViewHandler();		    
	viewHandler.setButtonNavigation(MouseEvent.BUTTON1_MASK, NavigationState.TILT_STATE);
	viewHandler.setButtonNavigation(MouseEvent.BUTTON2_MASK, NavigationState.FLY_STATE);
	viewHandler.setButtonNavigation(MouseEvent.BUTTON3_MASK, NavigationState.PAN_STATE);
	viewHandler.setViewInfo(view, viewTransform);
	MouseViewBehavior viewBehavior = new MouseViewBehavior(viewHandler) {
		Transform3D viewTranslation = new Transform3D();
		Vector3d translationVector = new Vector3d();
		NumberFormat format = new DecimalFormat("#.00");
		String preface = "Offset: ";
		Font textFont = new Font("Helvetica", Font.BOLD, 14);
		Font numberFont = textFont.deriveFont(Font.ITALIC | Font.BOLD);
		Color textColor = Color.white;
		Color numberColor = Color.green;

		public void processStimulus(Enumeration criteria) {
		    super.processStimulus(criteria);
		    viewTransform.getTransform(viewTranslation);
		    viewTranslation.get(translationVector);
		    
		    String outputText =
			preface + 
			"<" + format.format(translationVector.x) +
			", " + format.format(translationVector.y) +
			", " + format.format(translationVector.z) + ">";
		    AttributedString text = 
			new AttributedString(outputText);

		    int start = 0;
		    int end = preface.length();
		    text.addAttribute(TextAttribute.FONT, textFont, start, end);
		    text.addAttribute(TextAttribute.FOREGROUND, textColor, start, end);

		    start = end;
		    end = outputText.length();
		    text.addAttribute(TextAttribute.FONT, numberFont, start, end);
		    text.addAttribute(TextAttribute.FOREGROUND, numberColor, start, end);

		    translationLabel.setText(text);
		}
	    };    
	viewBehavior.setViewInfo(view, viewTransform);
	viewBehavior.setSchedulingBounds(bounds);
       	viewTransform.addChild(viewBehavior);
	
	Background sceneBackground = settings.getSceneBackground();
        sceneBackground.setApplicationBounds(bounds);
        sceneRoot.addChild(sceneBackground);

        DirectionalLight light = new DirectionalLight(settings.getLightColor(),
						      settings.getLightDirection());
        light.setInfluencingBounds(bounds);

        viewTransform.addChild(light);

	double theta = 360 * settings.getNumSpirals() / settings.getNumSpheres();
	for(int i = settings.getNumSpheres() - 1; i >= 0; i--) {
	    Vector3d point = new Vector3d(Math.toRadians(180d * i / settings.getNumSpheres() + 90),
					  Math.toRadians(theta * i),
					  Math.toRadians(0));
	    Color3f color = new Color3f(Color.getHSBColor((float)(point.y % (2 * Math.PI) / (2 * Math.PI)),
							  settings.getSaturation(),
							  (float)i / settings.getNumSpheres()));
	    Transform3D transform = new Transform3D();
	    transform.setEuler(point);
	    TransformGroup rotation = new TransformGroup(transform);
	    sceneRoot.addChild(rotation);
	    
	    transform.setIdentity();
	    transform.setTranslation(settings.getSphereOffset());
	    TransformGroup translation = new TransformGroup(transform);
	    rotation.addChild(translation);
	    
	    Appearance appearance = new Appearance();
	    appearance.setMaterial(new Material(settings.getAmbientColor(),
						color,
						settings.getDiffuseColor(),
						color,
						settings.getShininess()));
	    translation.addChild(new Sphere(settings.getRadius(), appearance));
		
	    if(settings.getNumCameras() > 0
	       && i % Math.max(1, (settings.getNumSpheres() / settings.getNumCameras())) == 0) {
		cameraBase = new BranchGroup();
		cameraBase.setCapability(Group.ALLOW_CHILDREN_EXTEND);
		translation.addChild(cameraBase);
		cameraMounts.add(cameraBase);
	    }
	}
	sceneRoot.compile();
	locale.addBranchGraph(sceneRoot);
    }

    public static void main(String[] args) {
        ViewPlatformTest view = new ViewPlatformTest();
	new MainFrame(view, 700, 700);
    }
}

interface ViewPlatformTestSettings {
    public int getNumSpheres();
    public int getNumSpirals();
    public int getNumCameras();
    public Point3d getCameraPosition();
    public Vector3d getSphereOffset();
    public Color3f getAmbientColor();
    public Color3f getDiffuseColor();
    public float getSaturation();
    public float getShininess();
    public float getRadius();
    public Background getSceneBackground();
    public Color3f getLightColor();
    public Vector3f getLightDirection();
}

class ViewPlatformTestSettingsPanel extends JPanel implements ViewPlatformTestSettings {
    public final static int SINGLE = 1;
    public final static int numProperties = 13;

    public final static int NONE = -1;
    public final static int NUM_SPHERES = 0;
    public final static int NUM_SPIRALS = 1;
    public final static int NUM_CAMERAS = 2;
    public final static int CAMERA_POSITION = 3;
    public final static int SPHERE_OFFSET = 4;
    public final static int AMBIENT_COLOR = 5;
    public final static int DIFFUSE_COLOR = 6;
    public final static int SATURATION = 7;
    public final static int SHININESS = 8;
    public final static int RADIUS = 9;
    public final static int BACKGROUND = 10;
    public final static int LIGHT_COLOR = 11;
    public final static int LIGHT_DIRECTION = 12;

    Object[] value = new Object[numProperties];
    Object[] control = new Object[numProperties];
    boolean[] dirty = new boolean[numProperties];

    public ViewPlatformTestSettingsPanel(int numSpheres,
					 int numSpirals,
					 int numCameras,
					 Point3d cameraPosition,
					 Vector3d sphereOffset,
					 Color ambientColor,
					 Color diffuseColor,
					 float saturation,
					 float shininess,
					 float radius,
					 Color backgroundColor,
					 Color lightColor,
					 Vector3f lightDirection) {
	GridBagLayout layout = new GridBagLayout();
        setLayout(layout);
        GridBagConstraints gridbag = new GridBagConstraints();
        gridbag.fill = GridBagConstraints.HORIZONTAL;
	gridbag.weightx = .5;

	addComponent(NONE, layout, SINGLE, gridbag, new JLabel("Spheres:"));
	addComponent(NONE, layout, GridBagConstraints.RELATIVE, gridbag, new JLabel("Spirals:"));
	addComponent(NONE, layout, GridBagConstraints.REMAINDER, gridbag, new JLabel("Cameras:"));
	addComponent(NUM_SPHERES, layout, SINGLE, gridbag, 
		     new JTextField(String.valueOf(numSpheres)));
	addComponent(NUM_SPIRALS, layout, GridBagConstraints.RELATIVE, gridbag,
		     new JTextField(String.valueOf(numSpirals)));
	addComponent(NUM_CAMERAS, layout, GridBagConstraints.REMAINDER, gridbag,
		     new JTextField(String.valueOf(numCameras)));
	addComponent(NONE, layout, SINGLE, gridbag, new JLabel("Camera Position:"));
	addComponent(CAMERA_POSITION, layout, GridBagConstraints.REMAINDER, gridbag,
		     new JTuple3dField(cameraPosition));
	addComponent(NONE, layout, SINGLE, gridbag, new JLabel("Sphere Offset:"));
	addComponent(SPHERE_OFFSET, layout, GridBagConstraints.REMAINDER ,gridbag,
		     new JTuple3dField(sphereOffset));
	addComponent(NONE, layout, SINGLE, gridbag, new JLabel("Saturation:"));
	addComponent(NONE, layout, GridBagConstraints.RELATIVE, gridbag, new JLabel("Shininess:"));
	addComponent(NONE, layout, GridBagConstraints.REMAINDER, gridbag, new JLabel("Radius:"));
	addComponent(SATURATION, layout, SINGLE, gridbag,
		     new JTextField(String.valueOf(saturation)));
	addComponent(SHININESS, layout, GridBagConstraints.RELATIVE, gridbag,
		     new JTextField(String.valueOf(shininess)));
	addComponent(RADIUS, layout, GridBagConstraints.REMAINDER, gridbag,
		     new JTextField(String.valueOf(radius)));
	addComponent(NONE, layout, SINGLE, gridbag, new JLabel("Ambient Color:"));
	addComponent(NONE, layout, GridBagConstraints.RELATIVE, gridbag, new JLabel("Diffuse Color:"));
	addComponent(NONE, layout, GridBagConstraints.REMAINDER, gridbag, new JLabel("Background Color:"));

	class ColorButton extends JButton {
	    {
		addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
			    changeColor();
			}
		    });
	    }

	    public ColorButton(Color initialColor) {
		setBackground(initialColor);
	    }

	    public void changeColor() {
		Color background = 
		    JColorChooser.showDialog(this, "Choose Color", getBackground());
		if(background != null) {
		    setBackground(background);
		}
	    }
	}

	gridbag.fill = GridBagConstraints.BOTH;
	gridbag.weighty = .5;
	
	addComponent(AMBIENT_COLOR, layout, SINGLE, gridbag, new ColorButton(ambientColor));
	addComponent(DIFFUSE_COLOR, layout, GridBagConstraints.RELATIVE, gridbag, new ColorButton(diffuseColor));
	addComponent(BACKGROUND, layout, GridBagConstraints.REMAINDER, gridbag, new ColorButton(backgroundColor));

	gridbag.fill = GridBagConstraints.HORIZONTAL;
	gridbag.weighty = 0;

	addComponent(NONE, layout, SINGLE, gridbag, new JLabel("Light Color:"));
	addComponent(NONE, layout, GridBagConstraints.REMAINDER, gridbag, new JLabel("Light Direction:"));

	gridbag.fill = GridBagConstraints.BOTH;
	gridbag.weighty = .5;

	addComponent(LIGHT_COLOR, layout, SINGLE, gridbag, new ColorButton(lightColor));

	gridbag.fill = GridBagConstraints.HORIZONTAL;
	gridbag.weighty = 0;

	addComponent(LIGHT_DIRECTION, layout, GridBagConstraints.REMAINDER, gridbag,
		     new JTuple3dField(lightDirection));

	for(int i = dirty.length - 1; i >= 0; i--) {
	    dirty[i] = true;
	}
    }

    private void addComponent(int property,
			      GridBagLayout layout,
			      int gridwidth,
			      GridBagConstraints gridbag,
			      Component component) {
	if(property != NONE) {
	    control[property] = component;
	}
	gridbag.gridwidth = gridwidth;
	layout.setConstraints(component, gridbag);
	add(component);
    }

    public int getNumSpheres() {
	return ((Number)getProperty(NUM_SPHERES)).intValue();
    }

    public int getNumSpirals() {
	return ((Number)getProperty(NUM_SPIRALS)).intValue();
    }

    public int getNumCameras() {
	return ((Number)getProperty(NUM_CAMERAS)).intValue();
    }

    public Point3d getCameraPosition() {
	return (Point3d)getProperty(CAMERA_POSITION);
    }

    public Vector3d getSphereOffset() {
	return (Vector3d)getProperty(SPHERE_OFFSET);
    }
	
    public Color3f getAmbientColor() {
	return (Color3f)getProperty(AMBIENT_COLOR);
    }

    public Color3f getDiffuseColor() {
	return (Color3f)getProperty(DIFFUSE_COLOR);
    }

    public float getSaturation() {
	return ((Number)getProperty(SATURATION)).floatValue();
    }

    public float getShininess() {
	return ((Number)getProperty(SHININESS)).floatValue();
    }

    public float getRadius() {
	return ((Number)getProperty(RADIUS)).floatValue();
    }

    public Background getSceneBackground() {
	return (Background)getProperty(BACKGROUND);
    }

    public Color3f getLightColor() {
	return (Color3f)getProperty(LIGHT_COLOR);
    }

    public Vector3f getLightDirection() {
	return (Vector3f)getProperty(LIGHT_DIRECTION);
    }

    public Object getProperty(int property) {
	if(dirty[property]) {
	    clean(property);
	}
	return value[property];
    }

    protected void clean(int property) {
	switch(property) {
	case NUM_SPHERES:       // Integer
	case NUM_SPIRALS:
	case NUM_CAMERAS:
	    value[property] = Integer.valueOf(((JTextField)control[property]).getText());
	    break;
	case CAMERA_POSITION:  // Point3d
	    value[property] = new Point3d(((JTuple3dField)control[property]).getValue());
	    break;
	case LIGHT_DIRECTION:  // Vector3f
	    value[property] = new Vector3f(((JTuple3dField)control[property]).getValue());
	    break;
	case SPHERE_OFFSET:    // Vector3d
	    value[property] = new Vector3d(((JTuple3dField)control[property]).getValue());
	    break;
	case AMBIENT_COLOR:    // Color
	case DIFFUSE_COLOR:
	case LIGHT_COLOR:
	    value[property] = new Color3f(((JButton)control[property]).getBackground());
	    break;
	case BACKGROUND:      // Background
	    value[property] = new Background(new Color3f(((JButton)control[property]).getBackground()));
	    break;
	case SATURATION:       // Float
	case SHININESS:
	case RADIUS:
	    value[property] = Float.valueOf(((JTextField)control[property]).getText());
	    break;
	default:
	    System.err.println("Property index not matched, " + property + "; " +
			       "proerty not cleaned");
	    break;
	}
	dirty[property] = false;
    }
}

class JTuple3dField extends JPanel {
    JTextField[] field = new JTextField[3];
    
    public JTuple3dField(Tuple3f tuple) {
	float[] floatValue = new float[3];
	tuple.get(floatValue);
	double[] value = new double[floatValue.length];
	for(int i = floatValue.length - 1; i >= 0; i--) {
	    value[i] = floatValue[i];
	}
	setupLayout(value);
    }

    public JTuple3dField(Tuple3d tuple) {
	double[] value = new double[3];
	tuple.get(value);
	setupLayout(value);
    }
    
    private void setupLayout(double[] value) {
	GridBagLayout layout = new GridBagLayout();
        setLayout(layout);
        GridBagConstraints gridbag = new GridBagConstraints();
        gridbag.fill = GridBagConstraints.HORIZONTAL;
	gridbag.weightx = .5;
	gridbag.gridwidth = 1;
	gridbag.insets.right = 5;
	gridbag.insets.left = 5;

	for(int i = 0; i <= field.length - 1; i++) {
	    field[i] = new JTextField(String.valueOf(value[i]));
	    layout.setConstraints(field[i], gridbag);
	    add(field[i]);
	}
    }

    public Tuple3d getValue() {
	double[] value = new double[3];
	for(int i = field.length - 1; i >= 0; i--) {
	    value[i] = Double.parseDouble(field[i].getText());
	}
	return new Point3d(value);
    }
}

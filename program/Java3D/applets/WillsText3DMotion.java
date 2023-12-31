/*
 *	@(#)Text3DMotion.java 1.1 98/05/12 14:37:32
 *
 * Copyright (c) 1996-1998 Sun Microsystems, Inc. All Rights Reserved.
 *
 * Sun grants you ("Licensee") a non-exclusive, royalty free, license to use,
 * modify and redistribute this software in source and binary code form,
 * provided that i) this copyright notice and license appear on all copies of
 * the software; and ii) Licensee does not utilize the software in a manner
 * which is disparaging to Sun.
 *
 * This software is provided "AS IS," without a warranty of any kind. ALL
 * EXPRESS OR IMPLIED CONDITIONS, REPRESENTATIONS AND WARRANTIES, INCLUDING ANY
 * IMPLIED WARRANTY OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE OR
 * NON-INFRINGEMENT, ARE HEREBY EXCLUDED. SUN AND ITS LICENSORS SHALL NOT BE
 * LIABLE FOR ANY DAMAGES SUFFERED BY LICENSEE AS A RESULT OF USING, MODIFYING
 * OR DISTRIBUTING THE SOFTWARE OR ITS DERIVATIVES. IN NO EVENT WILL SUN OR ITS
 * LICENSORS BE LIABLE FOR ANY LOST REVENUE, PROFIT OR DATA, OR FOR DIRECT,
 * INDIRECT, SPECIAL, CONSEQUENTIAL, INCIDENTAL OR PUNITIVE DAMAGES, HOWEVER
 * CAUSED AND REGARDLESS OF THE THEORY OF LIABILITY, ARISING OUT OF THE USE OF
 * OR INABILITY TO USE SOFTWARE, EVEN IF SUN HAS BEEN ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGES.
 *
 * This software is not designed or intended for use in on-line control of
 * aircraft, air traffic, aircraft navigation or aircraft communications; or in
 * the design, construction, operation or maintenance of any nuclear
 * facility. Licensee represents and warrants that it will not use or
 * redistribute the Software for such purposes.
 */

import java.applet.Applet;
import java.awt.BorderLayout;
import com.sun.j3d.utils.applet.MainFrame;
import com.sun.j3d.utils.geometry.Sphere;
import com.sun.j3d.utils.universe.*;
import javax.media.j3d.*;
import javax.vecmath.*;
import java.util.Enumeration;
import java.awt.*;
import java.lang.String;

public class WillsText3DMotion extends Applet {

    // Flag indicates whether to use directional or point light
    // This flag is set based on command line argument
    private static boolean usePointLights = true;

    public BranchGroup createSceneGraph(SimpleUniverse u) {
	Color3f eColor    = new Color3f(0.0f, 0.0f, 0.0f);
	Color3f sColor    = new Color3f(1.0f, 1.0f, 1.0f);
	Color3f objColor  = new Color3f(0.6f, 0.6f, 0.6f);
	Color3f lColor1   = new Color3f(1.0f, 0.0f, 0.0f);
	Color3f lColor2   = new Color3f(0.0f, 1.0f, 0.0f);
	Color3f alColor   = new Color3f(0.2f, 0.2f, 0.2f);
	Color3f bgColor   = new Color3f(0.05f, 0.05f, 0.2f);

	Transform3D t;

	// Create the root of the branch graph
	BranchGroup objRoot = new BranchGroup();

        // Create a Transformgroup to scale all objects so they
        // appear in the scene.
        TransformGroup objScale = new TransformGroup();
        Transform3D t3d = new Transform3D();
        t3d.setScale(0.4);
        objScale.setTransform(t3d);
        objRoot.addChild(objScale);

	// Create a bounds for the background and lights
	BoundingSphere bounds =
	    new BoundingSphere(new Point3d(0.0,0.0,0.0), 100.0);

	// Set up the background
	Background bg = new Background(bgColor);
	bg.setApplicationBounds(bounds);
	objScale.addChild(bg);

	// Create a Sphere object, generate one copy of the sphere,
	// and add it into the scene graph.
	Material m = new Material(objColor, eColor, objColor, sColor, 100.0f);
	Appearance a = new Appearance();
	m.setLightingEnable(true);
	a.setMaterial(m);
	//Sphere sph = new Sphere(1.0f, Sphere.GENERATE_NORMALS, 80, a);
	Font3D f3d = new Font3D(new Font("TestFont", Font.PLAIN, 1),
                        new FontExtrusion());
        Text3D txt = new Text3D(f3d, new String("Will Holcomb"),
                new Point3f(-2.0f, .0f, .0f));
	Shape3D sph = new Shape3D();
	sph.setGeometry(txt);
	sph.setAppearance(a);
	objScale.addChild(sph);

	// Create the transform group node for the each light and initialize
	// it to the identity.  Enable the TRANSFORM_WRITE capability so that
	// our behavior code can modify it at runtime.  Add them to the root
	// of the subgraph.
	TransformGroup l1RotTrans = new TransformGroup();
	l1RotTrans.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	objScale.addChild(l1RotTrans);

	TransformGroup l2RotTrans = new TransformGroup();
	l2RotTrans.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	objScale.addChild(l2RotTrans);

	// Create transformations for the positional lights
	t = new Transform3D();
	Vector3d lPos1 =  new Vector3d(0.0, 0.0, 2.0);
	t.set(lPos1);
	TransformGroup l1Trans = new TransformGroup(t);
	l1RotTrans.addChild(l1Trans);

	t = new Transform3D();
	Vector3d lPos2 = new Vector3d(0.5, 0.8, 2.0);
	t.set(lPos2);
	TransformGroup l2Trans = new TransformGroup(t);
	l2RotTrans.addChild(l2Trans);

	// Create Geometry for point lights
	ColoringAttributes caL1 = new ColoringAttributes();
	ColoringAttributes caL2 = new ColoringAttributes();
	caL1.setColor(lColor1);
	caL2.setColor(lColor2);
	Appearance appL1 = new Appearance();
	Appearance appL2 = new Appearance();
	appL1.setColoringAttributes(caL1);
	appL2.setColoringAttributes(caL2);
	l1Trans.addChild(new Sphere(0.05f, appL1));
	l2Trans.addChild(new Sphere(0.05f, appL2));

	// Create lights
	AmbientLight aLgt = new AmbientLight(alColor);

	Light lgt1;
	Light lgt2;

	if (usePointLights) {
	    Point3f lPoint  = new Point3f(0.0f, 0.0f, 0.0f);
	    Point3f atten = new Point3f(1.0f, 0.0f, 0.0f);
	    lgt1 = new PointLight(lColor1, lPoint, atten);
	    lgt2 = new PointLight(lColor2, lPoint, atten);
	}
	else {
	    Vector3f lDirect1 = new Vector3f(lPos1);
	    Vector3f lDirect2 = new Vector3f(lPos2);
	    lDirect1.negate();
	    lDirect2.negate();
	    lgt1 = new DirectionalLight(lColor1, lDirect1);
	    lgt2 = new DirectionalLight(lColor2, lDirect2);
	}

	// Set the influencing bounds
	aLgt.setInfluencingBounds(bounds);
	lgt1.setInfluencingBounds(bounds);
	lgt2.setInfluencingBounds(bounds);

	// Add the lights into the scene graph
	objScale.addChild(aLgt);
	l1Trans.addChild(lgt1);
	l2Trans.addChild(lgt2);

	// Create a new Behavior object that will perform the desired
	// operation on the specified transform object and add it into the
	// scene graph.
	Transform3D yAxis = new Transform3D();
	Alpha rotor1Alpha = new Alpha(-1, Alpha.INCREASING_ENABLE,
				     0, 0,
				     4000, 0, 0,
				     0, 0, 0);
	RotationInterpolator rotator1 =
	    new RotationInterpolator(rotor1Alpha,
				     l1RotTrans,
				     yAxis,
				     0.0f, (float) Math.PI*2.0f);
	rotator1.setSchedulingBounds(bounds);
	l1RotTrans.addChild(rotator1);

	// Create a new Behavior object that will perform the desired
	// operation on the specified transform object and add it into the
	// scene graph.
	Alpha rotor2Alpha = new Alpha(-1, Alpha.INCREASING_ENABLE,
				     0, 0,
				     1000, 0, 0,
				     0, 0, 0);
	RotationInterpolator rotator2 =
	    new RotationInterpolator(rotor2Alpha,
				     l2RotTrans,
				     yAxis,
				     0.0f, 0.0f);
	bounds = new BoundingSphere(new Point3d(0.0,0.0,0.0), 100.0);
	rotator2.setSchedulingBounds(bounds);
	l2RotTrans.addChild(rotator2);

	// Create a position interpolator and attach it to the view
	// platform
	TransformGroup vpTrans =
	    u.getViewingPlatform().getViewPlatformTransform();
	Transform3D axisOfTranslation = new Transform3D();
	Alpha transAlpha = new Alpha(-1,
				      Alpha.INCREASING_ENABLE |
				      Alpha.DECREASING_ENABLE,
				      0, 0,
				      5000, 0, 0,
				      5000, 0, 0);
	axisOfTranslation.rotY(-Math.PI/2.0);
	PositionInterpolator translator =
	    new PositionInterpolator(transAlpha,
				     vpTrans,
				     axisOfTranslation,
				     2.0f, 3.5f);
	translator.setSchedulingBounds(bounds);
	objScale.addChild(translator);

        // Let Java 3D perform optimizations on this scene graph.
        objRoot.compile();

	return objRoot;
    }

    public WillsText3DMotion() {
	setLayout(new BorderLayout());
	Canvas3D c = new Canvas3D(null);
	add("Center", c);

	SimpleUniverse u = new SimpleUniverse(c);
	BranchGroup scene = createSceneGraph(u);

        // This will move the ViewPlatform back a bit so the
        // objects in the scene can be viewed.
        u.getViewingPlatform().setNominalViewingTransform();

	u.addBranchGraph(scene);
    }

    //
    // The following allows Text3DMotion to be run as an application
    // as well as an applet
    //
    public static void main(String[] args) {
        // Parse the Input Arguments
	String usage = "Usage: java Text3DMotion [-point | -dir]";
        for (int i = 0; i < args.length; i++) {
            if (args[i].startsWith("-")) {
                if (args[i].equals("-point")) {
		    System.out.println("Using point lights");
                    usePointLights = true;
                }
		else if (args[i].equals("-dir")) {
		    System.out.println("Using directional lights");
		    usePointLights = false;
                }
		else {
		    System.out.println(usage);
                    System.exit(0);
                }
            }
	    else {
		System.out.println(usage);
		System.exit(0);
	    }
        }

	new MainFrame(new WillsText3DMotion(), 700, 700);
    }
}

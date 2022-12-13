package com.xith.java3d.testbed;

import java.applet.*;
import javax.swing.*;
import java.awt.*;
import javax.media.j3d.*;
import javax.vecmath.*;
import com.sun.j3d.utils.applet.MainFrame;
import com.sun.j3d.utils.geometry.*;
import com.sun.j3d.utils.universe.*;
import com.sun.j3d.utils.image.TextureLoader;
import com.sun.j3d.utils.behaviors.vp.OrbitBehavior;
import com.sun.j3d.utils.behaviors.mouse.MouseRotate;
import com.sun.j3d.utils.behaviors.mouse.MouseZoom;

/**
 * Basic testbed platform for Java3d.
 * Copyright:    Copyright (c) 2000,2001
 * Company:      Teseract Software, LLP
 * @author David Yazel
 * @version
 */

public class TestBed extends Applet {
    protected Canvas3D canvas;
    protected SimpleUniverse universe;
    protected float cubeSize = 1f;

    /**
     * Override to add new items to the scene
     */
    public void addNodes(BranchGroup sceneRoot, TransformGroup viewTransform) {
    }

    public BranchGroup createSceneGraph(Canvas3D c, ViewingPlatform viewingPlatform) {
        BranchGroup sceneRoot = new BranchGroup();

        // Create a bounds for the background and behaviors
        BoundingSphere bounds =
            new BoundingSphere(new Point3d(0.0, 0.0, 0.0), 100.0);

        Color3f backgroundColor = new Color3f(0.15f, 0.15f, 0.6f);
        Background background = new Background(backgroundColor);
        background.setApplicationBounds(bounds);
        sceneRoot.addChild(background);

	Transform3D initialCubePosition = new Transform3D();
        initialCubePosition.setTranslation(new Vector3d(0, 0, -cubeSize * 5));

        TransformGroup cubeMovement = new TransformGroup(initialCubePosition);

        Transform3D yAxis = new Transform3D();
        Alpha rotationAlpha = new Alpha(-1, 4000);

        TransformGroup cubeRotation = new TransformGroup();
        cubeRotation.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);

        RotationInterpolator rotator =
            new RotationInterpolator(rotationAlpha, cubeRotation, yAxis,
                                     0, (float)(Math.PI * 2));
        rotator.setSchedulingBounds(bounds);
        cubeRotation.addChild(rotator);

        sceneRoot.addChild(cubeRotation);
        cubeRotation.addChild(new ColorCube(cubeSize));

        addNodes(sceneRoot, viewingPlatform.getViewPlatformTransform());

        sceneRoot.compile();

        return sceneRoot;
    }

    public void init() {
        setLayout(new BorderLayout());

        GraphicsConfigTemplate3D template = new GraphicsConfigTemplate3D();
        GraphicsConfiguration config = GraphicsEnvironment.getLocalGraphicsEnvironment().
	    getDefaultScreenDevice().getBestConfiguration(template);

        canvas = new Canvas3D(config);
        add(BorderLayout.CENTER, canvas);
	validate();
	canvas.requestFocus();

        SimpleUniverse universe = new SimpleUniverse(canvas);
        universe.getViewingPlatform().setNominalViewingTransform();
        ViewingPlatform viewingPlatform = universe.getViewingPlatform();
        BranchGroup scene = createSceneGraph(canvas, viewingPlatform);
        universe.addBranchGraph(scene);

        OrbitBehavior orbit = new OrbitBehavior(canvas,
						OrbitBehavior.PROPORTIONAL_ZOOM);
        orbit.setMinRadius(20);
        orbit.setSchedulingBounds(new BoundingSphere());
        universe.getViewingPlatform().setViewPlatformBehavior(orbit);

        Transform3D transform = new Transform3D();
        transform.lookAt(new Point3d(20,0,20),new Point3d(0,0,0),new Vector3d(0,1,0));
        transform.invert();
        viewingPlatform.getViewPlatformTransform().setTransform(transform);
    }

    public void destroy() {
        universe.removeAllLocales();
    }

    public static void main(String[] args) throws Exception {
        new MainFrame(new TestBed(), 600, 600);
    }
}

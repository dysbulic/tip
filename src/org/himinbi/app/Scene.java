package org.himinbi.app;

import java.net.URL;
import java.awt.*;
import java.beans.*;
import javax.swing.*;
import javax.media.j3d.*;
import javax.vecmath.*;
import com.sun.j3d.utils.behaviors.mouse.*;
import com.sun.j3d.utils.image.TextureLoader;
import com.sun.j3d.utils.geometry.*;
import com.sun.j3d.utils.universe.*;

public class Scene implements PropertyChangeListener {
    Component observer;
    BranchGroup sceneRoot;
    TransformGroup earthPosition;
    TransformGroup earthSpin;
    ViewPlatform viewPlatform;
    Background background;
    Appearance appearance;
    Alpha rotationAlpha;
    RotationInterpolator rotator;
    Locale locale;
    
    public Scene(Component observer) {
	this(observer, new SceneSettings(observer));
    }

    public Scene(Component observer, SceneSettings settings) {
	this.observer = observer;
	settings.addPropertyChangeListener(this);
	VirtualUniverse universe = new VirtualUniverse();
	locale = new Locale(universe);
	sceneRoot = new BranchGroup();

	viewPlatform = new ViewPlatform();
	sceneRoot.addChild(viewPlatform);

	BoundingSphere bounds = new BoundingSphere(new Point3d(), Double.MAX_VALUE);
	background = new Background(new Color3f(29f / 255f, 43f / 255f, 153f / 255f));
	
	URL key = getClass().getResource("/images/textures/maps.jpl.nasa.gov_hipparcos_starmap.png");
	if(key != null) {
	    setBackground(settings.getImage(key.toString()));
	    System.out.println("bg: " + key);
	}

	background.setCapability(Background.ALLOW_IMAGE_WRITE);
	background.setApplicationBounds(bounds);
	sceneRoot.addChild(background);

	DirectionalLight light = new DirectionalLight
	    (new Color3f(100f / 255f, 100f / 255f, 100f / 255f),
	     new Vector3f(0f, 0f, -1f));
	light.setInfluencingBounds(bounds);
	sceneRoot.addChild(light);

	Transform3D transform = new Transform3D();
	transform.setTranslation(new Vector3f(0f, 0f, -settings.getEarthRadius() * 5f));
	earthPosition = new TransformGroup(transform);
	earthPosition.setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	earthPosition.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);

	MouseRotate rotateBehavior = new MouseRotate(earthPosition);
	rotateBehavior.setSchedulingBounds(bounds);
	earthPosition.addChild(rotateBehavior);

	MouseZoom zoomBehavior = new MouseZoom(earthPosition);
	zoomBehavior.setSchedulingBounds(bounds);
	earthPosition.addChild(zoomBehavior);

	sceneRoot.addChild(earthPosition);

	earthSpin = new TransformGroup();
	earthSpin.setCapability(TransformGroup.ALLOW_TRANSFORM_READ);
	earthSpin.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
	earthPosition.addChild(earthSpin);

	rotationAlpha = new Alpha(-1, Alpha.INCREASING_ENABLE,
				  0, 0,
				  settings.getSpeed(), 0, 0,
				  0, 0, 0);
	Transform3D axis = new Transform3D();

	rotator = new RotationInterpolator(rotationAlpha,
					   earthSpin,
					   axis, 0, (float)Math.PI * 2f);
	rotator.setSchedulingBounds(bounds);
	earthSpin.addChild(rotator);
	appearance = new Appearance();
	appearance.setCapability(Appearance.ALLOW_TEXTURE_WRITE);

	key = getClass().getResource("/images/textures/org.himinbi_blend_earth_map.jpg");
	if(key != null) {
	    setTexture(settings.getTexture(key.toString()));
	    System.out.println("tex: " + key);
	}

	Shape3D earth = new Shape3D(new Sphere(settings.getEarthRadius(),
					       Sphere.GENERATE_NORMALS | Sphere.GENERATE_TEXTURE_COORDS,
					       180).getShape().getGeometry());
	earth.setAppearance(appearance);
	earthSpin.addChild(earth);
    }

    public ViewPlatform getViewPlatform() {
	return viewPlatform;
    }

    public void makeLive() {
	locale.addBranchGraph(sceneRoot);
    }

    public void propertyChange(PropertyChangeEvent e) {
	String property = e.getPropertyName();
	if(property.equals("texture")) {
	    setTexture((Texture)e.getNewValue());
	} else if(property.equals("background")) {
	    setBackground((ImageComponent2D)e.getNewValue());
	} else if(property.equals("speed")) {
	    rotationAlpha.setIncreasingAlphaDuration(((Integer)e.getNewValue()).intValue());
	} else {
	    System.out.println("Changing: " + property);
	}
    }

    public void setAnimated(boolean state) {
	rotator.setEnable(state);
    }

    public boolean getAnimated() {
	return rotator.getEnable();
    }

    public void setTexture(Texture texture) {
	appearance.setTexture(texture);
    }

    public void setBackground(ImageComponent2D image) {
	background.setImage(image);
    }
}

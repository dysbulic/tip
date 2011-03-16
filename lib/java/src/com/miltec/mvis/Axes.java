package com.miltec.mvis;

import javax.media.j3d.*;
import javax.vecmath.*;
import com.sun.j3d.utils.geometry.*;

public class Axes {
    static float defaultLength = 1f;
    static float defaultRadius = .01f;

    boolean visible = false;
    Switch switchGroup = new Switch();
    
    public Axes(Group parent) {
	this(parent, defaultRadius, defaultLength);
    }

    public Axes(Group parent, float radius, float length) {
	Color3f black = new Color3f(0, 0, 0);
	Color3f white = new Color3f(1, 1, 1);
	Color3f red = new Color3f(252f / 255f, 30f / 255f, 30f / 255f);
	Color3f green = new Color3f(46f / 255f, 220f / 255f, 23f / 255f);
	Color3f blue = new Color3f(28f / 255f, 60f / 255f, 239f / 255f);
	float shininess = .75f;
	
	switchGroup.setCapability(Switch.ALLOW_SWITCH_WRITE);

	Appearance appearance;
	Transform3D positionTransform;
        Transform3D rotationTransform;
	TransformGroup transformation;

	for(int i = 1; i <= 3; i++) {
	    appearance = new Appearance();
	    positionTransform = new Transform3D();
            rotationTransform = new Transform3D();

	    switch(i) {
	    case 1: // x-axis
                appearance.setMaterial(new Material(black, red, white, red, shininess));
                appearance.setColoringAttributes
                    (new ColoringAttributes(red, ColoringAttributes.NICEST));
                rotationTransform.rotZ(Math.toRadians(-90));
                break;
	    case 2: // y-axis
                appearance.setMaterial(new Material(black, green, white, green, shininess));
                appearance.setColoringAttributes
                    (new ColoringAttributes(green, ColoringAttributes.NICEST));
                break;
	    case 3: // z-axis
                appearance.setMaterial(new Material(black, blue, white, blue, shininess));
                appearance.setColoringAttributes
                    (new ColoringAttributes(blue, ColoringAttributes.NICEST));
                rotationTransform.rotX(Math.toRadians(90));
                break;
            }
            positionTransform.setTranslation(new Vector3f(0f, length / 2f, 0f));
            rotationTransform.mul(positionTransform);
            transformation = new TransformGroup(rotationTransform);
	    transformation.addChild(new Cylinder(radius, length, appearance));
	    switchGroup.addChild(transformation);
	}
	appearance = new Appearance();
	appearance.setMaterial(new Material(black, black, white, black, shininess));
	switchGroup.addChild(new Sphere(radius, appearance));

	parent.addChild(switchGroup);

	setVisible(true);
    }
    
    public void setVisible(boolean visible) {
	if(this.visible != visible) {
	    if(!visible) {
		switchGroup.setWhichChild(Switch.CHILD_NONE);
	    } else {
		switchGroup.setWhichChild(Switch.CHILD_ALL);
	    }
	    this.visible = visible;
	}
    }

    public boolean isVisible() {
	return visible;
    }
}

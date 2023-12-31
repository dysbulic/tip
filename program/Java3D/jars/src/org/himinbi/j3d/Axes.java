package org.himinbi.j3d;

import javax.media.j3d.*;
import javax.vecmath.*;
import java.awt.*;
import com.sun.j3d.utils.geometry.*;

public class Axes {
    static float defaultLength = 1f;
    static float defaultRadius = .01f;

    public static Color3f black = new Color3f(0, 0, 0);
    public static Color3f white = new Color3f(200f / 255f, 200f / 255f, 200f / 255f);
    public static Color3f red = new Color3f(252f / 255f, 15f / 255f, 15f / 255f);
    public static Color3f green = new Color3f(4f / 255f, 174f / 255f, 15f / 255f);
    public static Color3f blue = new Color3f(28f / 255f, 60f / 255f, 239f / 255f);
    public static Color3f lightGray = new Color3f(175f / 255f, 175f / 255f, 175f / 255f);
    
    static float shininess = 80f;

    public static Material redMaterial = new Material(red, black, red, white, shininess);
    public static Material greenMaterial = new Material(green, black, green, lightGray, shininess);
    public static Material blueMaterial = new Material(blue, black, blue, lightGray, shininess);

    boolean visible = false;
    Switch switchGroup = new Switch();
    
    public Axes(Group parent) {
	this(parent, defaultRadius, defaultLength);
    }

    public Axes(Group parent, float radius, float length) {
	
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
                appearance.setMaterial(redMaterial);
                rotationTransform.rotZ(Math.toRadians(-90));
		break;
	    case 2: // y-axis
                appearance.setMaterial(greenMaterial);
                break;
	    case 3: // z-axis
                appearance.setMaterial(blueMaterial);
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

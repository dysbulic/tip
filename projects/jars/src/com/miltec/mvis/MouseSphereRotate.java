package com.miltec.mvis;

import java.awt.*;
import java.awt.event.*;
import java.util.*;
import javax.media.j3d.*;
import javax.vecmath.*;
import com.sun.j3d.utils.behaviors.mouse.*;
import java.text.*;

public class MouseSphereRotate extends MouseBehavior {
    double x_factor = -.01;
    double y_factor = -.01;
    int max_factor = 500;
    
    NumberFormat format = new DecimalFormat("###.000");

    private MouseBehaviorCallback callback = null;

    public MouseSphereRotate(TransformGroup transformGroup) {
	super(transformGroup);
    }
    
    public MouseSphereRotate() {
	this(0);
    }

    public MouseSphereRotate(int flags) {
	super(flags);
    }

    public void initialize() {
	super.initialize();
	if ((flags & INVERT_INPUT) == INVERT_INPUT) {
	    invert = true;
	    x_factor *= -1;
	    y_factor *= -1;
	}
    }

    public double getXFactor() {
	return x_factor;
    }
  
    public double getYFactor() {
	return y_factor;
    }
    
    public void setXFactor(double factor) {
	x_factor = factor;
    }

    public void setYFactor(double factor) {
	y_factor = factor;
    }
    
    public void setFactor(double factor) {
	x_factor = y_factor = factor;
    }

    public void processStimulus(Enumeration criteria) {
	WakeupCriterion wakeup;
	AWTEvent[] event;
	int id;
	
	while(criteria.hasMoreElements()) {
	    wakeup = (WakeupCriterion)criteria.nextElement();
	    if(wakeup instanceof WakeupOnAWTEvent) {
		event = ((WakeupOnAWTEvent)wakeup).getAWTEvent();
		for(int i = 0; i < event.length; i++) { 
		    processMouseEvent((MouseEvent)event[i]);
		    
		    if(((buttonPress) && ((flags & MANUAL_WAKEUP) == 0)) ||
		       ((wakeUp) && ((flags & MANUAL_WAKEUP) != 0))){
			
			id = event[i].getID();
			if((id == MouseEvent.MOUSE_DRAGGED)) {
			    if(!reset && !((MouseEvent)event[i]).isMetaDown()
			       && !((MouseEvent)event[i]).isAltDown()) {
				x = ((MouseEvent)event[i]).getX();
				y = ((MouseEvent)event[i]).getY();
				
				transformGroup.getTransform(currXform);
				boolean translationPresent = true;

				Vector3d translation = new Vector3d();
				Matrix3d dcm = new Matrix3d();
				currXform.get(translation);

				double phi = (x - x_last) * x_factor;
				double theta = (y - y_last) * y_factor;
				
				if(Math.abs(phi) > Math.abs(x_factor * max_factor)) {
				    phi = x_factor;
				}
				
				if(Math.abs(theta) > Math.abs(y_factor * max_factor)) {
				    theta = y_factor;
				}
				
				double magnitude = translation.length();
				
				if(((MouseEvent)event[i]).isAltDown()) {
				    translation.x *= 1 + theta / magnitude;
				    translation.y *= 1 + theta / magnitude;
				    translation.z *= 1 + theta / magnitude;
				} else {
				    if(magnitude == 0) {
					currXform.get(dcm);
					translation.x = dcm.m02;
					translation.y = dcm.m12;
					translation.z = dcm.m22;
					magnitude = translation.length();
					translationPresent = false;
				    }
				    double xP[] = new double[2];
				    
				    xP[0] = Math.sqrt(translation.x * translation.x + translation.z * translation.z);
				    xP[1] = xP[0] * Math.cos(theta) - translation.y * Math.sin(theta);
				    
				    if(xP[0] * xP[1] < 0) {
					y_factor *= -1;
				    }

				    Vector3d newTranslation = new Vector3d
					(translation.x * xP[1] / xP[0],
					 xP[0] * Math.sin(theta) + translation.y * Math.cos(theta),
					 translation.z * xP[1] / xP[0]);
				    
				    translation.x = newTranslation.x * Math.cos(phi) - newTranslation.z * Math.sin(phi);
				    translation.y = newTranslation.y;
				    translation.z = newTranslation.x * Math.sin(phi) + newTranslation.z * Math.cos(phi);

				    double newMagnitude = translation.length();
				
				    translation.x = translation.x * magnitude / newMagnitude;
				    translation.y = translation.y * magnitude / newMagnitude;
				    translation.z = translation.z * magnitude / newMagnitude;
				}

				// Z unit vector (v / ||v||)
				dcm.m02 = translation.x / magnitude;
				dcm.m12 = translation.y / magnitude;
				dcm.m22 = translation.z / magnitude;

				// X unit vector
				// (sqrt(x^2 + y^2 + z^2) = 1
				//  vx * x + vy * y + vz * z + 0 = 0
				//  y = 0)
				dcm.m00 = -1 / Math.sqrt(Math.pow(translation.x / translation.z, 2) + 1);
				dcm.m10 = 0;
				dcm.m20 = 1 / Math.sqrt(Math.pow(translation.z / translation.x, 2) + 1);

				if(translation.x * translation.z <= 0) {
				    dcm.m20 *= -1;
				}
				
				if(translation.z >= 0) {
				    dcm.m00 *= -1;
				    dcm.m10 *= -1;
				    dcm.m20 *= -1;
				}

				// Y unit vector (Uz x Ux)
				dcm.m01 = dcm.m12 * dcm.m20 - dcm.m22 * dcm.m10;
				dcm.m11 = dcm.m22 * dcm.m00 - dcm.m02 * dcm.m20;
				dcm.m21 = dcm.m02 * dcm.m10 - dcm.m12 * dcm.m00;

				//Vector3d uX = new Vector3d(dcm.m00, dcm.m10, dcm.m20);
				//Vector3d uY = new Vector3d(dcm.m01, dcm.m11, dcm.m21);
				//Vector3d uZ = new Vector3d(dcm.m02, dcm.m12, dcm.m22);

				currXform.set(dcm);
				if(translationPresent) {
				    currXform.setTranslation(translation);
				}
				transformGroup.setTransform(currXform);

				transformChanged(currXform);

				if(callback != null) {
				    callback.transformChanged(MouseBehaviorCallback.ROTATE,
							      currXform);
				}
			    } else {
				reset = false;
			    }
			    x_last = x;
			    y_last = y;
			} else if(id == MouseEvent.MOUSE_PRESSED) {
			    x_last = ((MouseEvent)event[i]).getX();
			    y_last = ((MouseEvent)event[i]).getY();
			}
		    }
		}
	    }
	}
	wakeupOn (mouseCriterion);
    }

    public void transformChanged( Transform3D transform ) {
    }

    public void setupCallback(MouseBehaviorCallback callback) {
	this.callback = callback;
    }
}

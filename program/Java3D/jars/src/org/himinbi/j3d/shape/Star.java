package org.himinbi.j3d.shape;

import javax.media.j3d.*;
import javax.vecmath.*;

public class Star extends Shape3D {
    Color3f activeColor = new Color3f(0f, 0f, 0f);
    Color3f inactiveColor = new Color3f(1f, 1f, 1f);
    int currentIndex = -1;
    Color3f[] colors;
    LineStripArray geometry;
    int numPoints = 0;

    public Star() {
	this(5, 1, null);
    }

    public Star(int numPoints, double radius) {
	this(numPoints, radius, null);
    }

    public Star(Appearance appearance) {
	this(5, 1, appearance);
    }

    public Star(int numPoints, double radius, Appearance appearance) {
	this.numPoints = numPoints;
	setAppearance(appearance);

	Point3d verts[] = new Point3d[numPoints];
	
	int i = 0;

        double angleIncrement =  2 * Math.PI / numPoints;
	double initialAngle = Math.PI / 2 - angleIncrement / 2;
	double theta = initialAngle - angleIncrement / 2;
	Point3d center = new Point3d();

	for(i = 0; i < numPoints; i++, theta += angleIncrement) {
	    verts[i] = new Point3d
		(center.x + radius * Math.cos(theta),
		 center.y + radius * Math.sin(theta),
		 center.z + 0);
	}
	
	int[] count;
	Point3d points[];
	if(numPoints % 2 != 0) {
	    points = new Point3d[numPoints + 1];

	    for(i = 0; i < points.length; i++) {
		points[i] = verts[(i * 2) % numPoints];
	    }

	    count = new int[1];
	    count[0] = points.length;
	} else {
	    points = new Point3d[numPoints + 2];
	    for(i = 0; i < points.length / 2; i++) {
		points[i] = verts[(i * 2) % numPoints];
		points[i + points.length / 2] = verts[(i * 2 + 1) % numPoints];
	    }

	    count = new int[2];
	    count[0] = points.length / 2;
	    count[1] = points.length / 2;
	}

	colors = new Color3f[points.length];
	
	for(i = 0; i < colors.length; i++) {
	    colors[i] = inactiveColor;
	}

	geometry = new LineStripArray
	    (points.length, GeometryArray.COORDINATES | GeometryArray.COLOR_3, count);
	geometry.setCapability(GeometryArray.ALLOW_COLOR_WRITE);

	geometry.setCoordinates(0, points);
	geometry.setColors(0, colors);

	setGeometry(geometry);
    }

    public int getNumIndices() {
	return colors.length;
    }
    
    public int getNumPoints() {
	return numPoints;
    }

    public int getIndex() {
	return currentIndex;
    }

    public void setIndex(int index) {
	int i = 0;
	int newIndex = 0;
	if(index > currentIndex) {
	    newIndex = Math.min(index, colors.length - 1);
	    for(i = currentIndex + 1; i <= newIndex; i++) {
		colors[i] = activeColor;
	    }
	} else {
	    newIndex = Math.max(-1, index);
	    for(i = currentIndex; i > newIndex; i--) {
		colors[i] = inactiveColor;
	    }
	}
	geometry.setColors(0, colors);
	currentIndex = newIndex;
    }
}

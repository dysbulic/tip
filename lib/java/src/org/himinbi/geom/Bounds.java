package org.himinbi.geom;

public class Bounds {
    double boundOne;
    double boundTwo;
    
    public Bounds() {
	this(Math.random(), Math.random());
    }

    public Bounds(double boundOne, double boundTwo) {
	this.boundOne = boundOne;
	this.boundTwo = boundTwo;
    }

    public int boundedInt() {
	return (int)boundedDouble();
    }

    public float boundedFloat() {
	return (float)boundedDouble();
    }

    public double boundedDouble() {
	return Math.abs(boundOne - boundTwo) * Math.random() + Math.min(boundOne, boundTwo);
    }

    public boolean withinBounds(double value) {
	return overLowerBound(value) && underUpperBound(value);
    }

    public boolean overLowerBound(double value) {
	return value >= lowerBound();
    }

    public boolean underUpperBound(double value) {
	return value <= upperBound();
    }

    public double upperBound() {
	return Math.max(boundOne, boundTwo);
    }

    public double lowerBound() {
	return Math.min(boundOne, boundTwo);
    }
}


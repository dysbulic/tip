/*Point.java
	Written by Andy Trent
This class represents a joint on the arm.  It is simply two concentric
circles that can be drawn wherever the programmer wants them. */


import point;

class joint {
	public point center;	//a point object to represent the center
	public int inner = 15;	//the diameter of the inner circle
	public int outer = 20;	//the diameter of the outer circle
	
	public joint() {
		/* the default constructor, simply initializes a new point
		to be the center of the joint. */

		center = new point();
	}


	public joint(int x, int y) {
		/*  the constructor for when a specific location is known
		when the object is instatiated */

		center = new point(x, y);
	}

}

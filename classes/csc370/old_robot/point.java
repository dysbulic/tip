/*  Point.java
	Written by Andy Trent
This is a very simple class that has two public integers, X and Y, and two
constructors.  One default that sets each value to 0, and another setting
them to whatever value is passed.  This class has the fairly simple task 
of representing a point in two-space.  */ 

 class point {
	public int x;
	public int y;

	public point( int initX, int initY) {
		x = initX;
		y = initY;
	}
	
	public point() {
		x = 0;
		y = 0;
	}
}

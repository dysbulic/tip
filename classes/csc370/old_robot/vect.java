import java.lang.Math;

public class vect {
	/* vect.class
		written by Andy Trent
	A bunch of methods and vars to represent 2-D vectors */

	int rectX = 0;		//the X rectangular coordinate
	int rectY = 0;		//the Y rectangular coordinate
	int mag = 0;		//the magnitude of polar representation
	int angle = 0;		//the angle of the polar representation

	public vect(int intialMag, int intialAngle) {
		//the constructor when a maginitude and angle are given
		mag = intialMag;
		angle = intialAngle;
		updateRect();
	}
	
	public vect(int intialMag) {
		//the constructor if only an intial magnitude is given
		mag = intialMag;
	}		

	public void addAngle(int x) {
		//adds an angle to the vector

		angle += x;
		if(angle >= 360)
			angle = angle - 360;
		if(angle <= -360)
			angle = angle + 360;
		updateRect();
	}

	private void updateRect() {
		//takes the polar coords of the vector and calculates the
		// rectangular

		double radtmp = 0;
		radtmp = angle * (Math.PI / 180);
		rectX = (int)(Math.round( mag * Math.cos(radtmp) ));
		rectY = (int)(Math.round( mag * Math.sin(radtmp) ));
	}

	public void changeAngle(int theta) {
		//sets the angle to an arbitrary value

		angle = theta;
		updateRect();
	}

	public int getX() {
		//returns the X rectangular coord

		return (int)(rectX);
	}

	public int getY() {
		//returns the Y rectangular coord

		return (int)(rectY);
	}

	public int getAngle() {
		//returns the angle of the vector

		return angle;
	}

	public void makePerp(int normal) {
		//takes the vector and makes it perpendicular
		//to the given angle (normal)

		angle = 90 + normal;
		updateRect();
	}

} 

import point;
import vect;
import java.awt.*;

public class arm {
	/*arm.class
		Written by Andy Trent
	THis class represents one 'limb' of the robot.  There is a lot of
	stuff that isn't used because I was/am still working with it.  Most of
	that has to do with polygons. */

	public vect backbone = new vect(40); //vector representingthe limb
	public Polygon body = new Polygon(); //not used in this version
	point origin = new point();  // the first point of the limb
	point next = new point();    //the last point
	vect perpVect = new vect(5); //also not used in this version

	public arm(point initPoint, int initAng) {
	//constructor taking a piont to center the arm at and that
	//sections intial angle.

		origin = initPoint;
		backbone.angle = initAng;
	}

	public point move(int theta, point start) {
		//this method takes a starting point for the segment
		//and the  number of degrees it needs to be moved.
		//it returns the endpoint of the segment.

		body.npoints = 4;
		backbone.addAngle(theta);
		origin = start;
		perpVect.makePerp(backbone.getAngle());
		body.xpoints[0] = origin.x + backbone.getX();
		body.ypoints[0] = origin.y + backbone.getY();
		body.xpoints[1] = origin.x - backbone.getX();
		body.ypoints[1] = origin.y - backbone.getY();
		next.x = origin.x + backbone.getX();
		next.y = origin.y + backbone.getY();
		body.xpoints[2] = next.x + backbone.getX();
		body.ypoints[2] = next.y + backbone.getY();
		body.xpoints[3] = next.x - backbone.getX();
		body.ypoints[3] = next.y - backbone.getY();
		return next;
	}
} 
	

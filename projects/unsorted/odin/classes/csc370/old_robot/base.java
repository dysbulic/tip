//		VERSION A1 -- it works, but isn't double-buffered or
//		anything fancy like that...

import java.awt.*;
import point;
import vect;


public class base extends Canvas {
	/*base.class
		written by Andy Trent
		This class represents the base of the robot, and extends
	canvas so we can paint it into the interface. */

	point next = new point();	//the end of the arrow
	point center = new point(150,100); // the center of the circle
	vect direction = new vect(40,0);   // vector holding direction
	int radius = 50;		   // radius of the circle
	vect perpvect = new vect(10);	   //a vector to help make the
					   // triangle
	Polygon arrow = new Polygon();	   // the triangle that points
//	Image offScreenImage;		   // part of double buffering
//	Graphics offScreenGc;		   // stuff that doesn't work

	public void init() {
	 //empty method that I need when I run this as an applet
	}



	public void base() {
		//empty constructor
	}		
		

	public void paint(Graphics gc) {
		/* this class draws the base, and takes only a graphics
			object */

		//gc.setColor(Color.black);
		//gc.fillRect(0, 0, 200, 100);
		arrow.npoints = 3;
		perpvect.makePerp(direction.getAngle());
		next.x = center.x + direction.getX();
		next.y = center.y + direction.getY();
		arrow.xpoints[0] = next.x;
		arrow.ypoints[0] = next.y;
		arrow.xpoints[1] = center.x + perpvect.getX();
		arrow.ypoints[1] = center.y + perpvect.getY();
		arrow.xpoints[2] = center.x - perpvect.getX();
		arrow.ypoints[2] = center.y - perpvect.getY();
		gc.setColor(Color.yellow);
		gc.drawOval(center.x-radius, center.y-radius, 2*radius,
2*radius);
		gc.fillPolygon(arrow);
		gc.drawString("0", center.x + radius + 5, center.y+4);
		gc.drawString("90", center.x - 5, center.y + radius +10);
		gc.drawString("180", center.x - radius - 22, center.y+4); 
		gc.drawString("270", center.x-10, center.y - radius - 5);

	}


	public void reset() {
		//this method sets the base back to the orginal
		//orientation.

		direction.changeAngle(0);
		repaint();
	}

	public void move(int theta) {
		//this moves the base, accepting the number of degrees
		//to be moved.  It does it in two degree steps which I had 
		//hoped would make it animated, but it never worked...

		int i, j;
		
		if(theta >= 0)
			for(i=0; i < theta; i=i+2) {
				direction.addAngle(2);
				repaint();
			}
		else {
			theta *= -1;
			for(i=0; i <= theta; i=i+2) {
				direction.addAngle(-2);
				repaint();
			}
		}
	}

/*
	public void update(Graphics g) {
		//this is the double-buffering stuff that doesn't
		//work.

		if(offScreenImage == null) {
			offScreenImage = createImage(300, 200);
			offScreenGc = offScreenImage.getGraphics();
		}
		
		paint(offScreenGc);
		g.drawImage(offScreenImage, 0, 0, this);
	}
*/			
	


}
	

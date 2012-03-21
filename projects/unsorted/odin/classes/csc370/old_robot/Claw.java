/*this file is actually two classes, rect and Claw.*/

import point;

class rect {

	/*rect.class
		written by Andy Trent
		a simple way of keeping all the coordinates of a rectangle
		together.  Used only in class Claw.*/

	public int x = 0;	//the x coord of top-right point
	public int y = 0;	//y coord of top-right point
	public int width = 0;	//width of rectangle
	public int height = 0;	//height of rectangle
	
	public rect() {}	//empty constructor
}


class Claw {
	
	/*Claw.class
		Written by Andy Trent
		this class serves as the hand or claw of our
		robot. */
	
	int gap;	//this keeps the current width of opening
	int maxgap = 10;//this is the maximum width
	public rect r1 = new rect();  //the top of the claw
	public rect r2 = new rect();  //the left side
	public rect r3 = new rect();  //the right side

	public Claw(point center) {
			/*this constructor takes a point that the claw is
			to be centered at and sets initial gap to be 10*/
		gap = 10;
		move(0, center);//this line calls the move function which
				//figures out the coordinates of the 
				//rectangles. Rather than write it
				//twice, I just call the move function
				//with a movement of 0
	}

	public void move(int dist, point center) {
		/*the first parameter, dist, is the number of pixels you
		want the claw to open (if the number is positive) or close 
		(if it is negative).  The second is the point around which
		you want to draw the claw. */
		
		if(dist + gap > maxgap) 
			gap = 10;
			//if the distanced to be moved will cause the
			//width of the claw to exceed the max, just set
			//the gap to its max value

		else if(gap + dist < 0)
			gap = 0;
			//if not, if the the dist will cause the width to
			//be less than 0, set the gap to 0.

			else gap += dist;
				//if neither of those are true, add dist
				//to gap.
		//these do all the stuff to put the claw in the 
		//right places...
		r1.x = center.x - gap;
		r1.y = center.y + 8;
		r1.width = gap*2;
		r1.height = 4;
		r2.x = center.x - gap;
		r2.y = center.y + 10;
		r2.width = 3;
		r2.height = 20;
		r3.x = center.x + gap - 3;
		r3.y = center.y + 10;
		r3.width = 3;
		r3.height = 20;
	}
	
}

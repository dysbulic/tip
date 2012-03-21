import point;
import vect;
import joint;
import arm;
import Claw;
import java.awt.*;
import java.applet.Applet;

public class robot extends Canvas {
	/*robot.class
		Written by Andy Trent
	This ties the two arms and the claw together */

	joint joint1 = new joint(150,100);
	joint joint2 = new joint();
	joint joint3 = new joint();
	arm arm1 = new arm(joint1.center, 90);
	arm arm2;
	Claw claw;

	public void robot() {
	//default constructor that never seems to be called
	}

	public void init() {
		//the stuff that should be in the constructor
		//but that I had to call explicitly to make work.

		joint2.center = arm1.move(0, joint1.center);
		arm2 = new arm(joint2.center, 0);
		joint3.center = arm2.move(0, joint2.center);
		claw = new Claw(joint3.center);
	}

	public void paint(Graphics gc) {
		//the code that paints the robot

		gc.setColor(Color.black);
	//	gc.translate(0,100);
		gc.fillRect(0,0,200, 100);
		gc.setColor(Color.yellow);
		gc.fillPolygon(arm1.body);
		gc.fillOval(joint1.center.x -
joint1.outer/2,joint1.center.y -joint1.outer/2,joint1.outer, joint1.outer);
		gc.fillOval(joint2.center.x -
joint2.outer/2,joint2.center.y-joint2.outer/2,joint2.outer,joint2.outer);
		gc.drawLine(joint1.center.x, joint1.center.y,
joint2.center.x, joint2.center.y);
		gc.fillOval(joint3.center.x -
joint3.outer/2,joint3.center.y -joint3.outer/2,joint3.outer, joint3.outer);
		gc.drawLine(joint3.center.x, joint3.center.y,
joint2.center.x, joint2.center.y);
		gc.fillRect(claw.r1.x, claw.r1.y, claw.r1.width,
claw.r1.height);
		gc.fillRect(claw.r2.x, claw.r2.y, claw.r2.width,
claw.r2.height);
		gc.fillRect(claw.r3.x, claw.r3.y, claw.r3.width,
claw.r3.height);
		gc.setColor(Color.black);
		gc.fillOval(joint1.center.x -
joint1.inner/2,joint1.center.y - joint1.inner/2, joint1.inner,
joint1.inner);
		gc.fillOval(joint2.center.x -
joint2.inner/2,joint2.center.y - joint2.inner/2, joint2.inner,
joint2.inner);
		gc.fillOval(joint3.center.x -
joint3.inner/2,joint3.center.y - joint3.inner/2, joint3.inner,
joint3.inner);
	}

	public void reset() {
		//this method returns the robot to its intial position

		arm1.backbone.changeAngle(90);
		claw.move(10, joint3.center);
		init();
		repaint();
	}

	public void moveControl(int a, int b, int c) {
		//this method takes three ints, one for each of the 
		//joints, and each represents the number of degrees
		//that joint is to be moved.

		int temp = 0;
/*		if(( arm1.backbone.getAngle() + a) > 180)
			a = 180-arm1.backbone.getAngle();
		if(( arm1.backbone.getAngle() + a) < 0)
			a = 0-arm1.backbone.getAngle(); */			
		joint2.center = arm1.move(a, joint1.center);
		joint3.center = arm2.move(a+b, joint2.center);
		claw.move(c, joint3.center);
		repaint();
	}

}

import javax.media.j3d.*;
import javax.vecmath.*;
import java.lang.*;
import Joint;
import Axis;
import Block;
import com.sun.j3d.utils.geometry.Cylinder;
import com.sun.j3d.utils.geometry.Sphere;
import java.awt.event.*;

public class Robot implements MovementRequestListener {
	/*Written by Andy Trent
		Input:  none
		Output:  none
	This class will be instantiated to form a robot.  It currently contains no inputs and 
	has no outputs.*/


	private BranchGroup RobotBase = new BranchGroup();			//the root of the scenegraph
	private TransformGroup RobotBaseTG = new TransformGroup();	//Transform from root to first node
	private Appearance ltBlue = new Appearance();				//a light blue appearance
	private Appearance dkBlue = new Appearance();				//a darker blue appearance
	private Joint Joint0;			//Joint object representing the 0th joint
	private Joint Joint1;			//Joint object representing the 1st joint
	private Joint Joint2;			//Joint object representing the 2nd joint
	private Joint Joint3;			//Joint object representing the 3rd joint
	private Joint Joint4;			//Joint object representing the 4th joint
	private Joint Joint5;			//Joint object representing the 5th joint
	private Joint Joint6;			//Joing object representing the 6th joint
	Claw claw;						//a Claw object that is the end effector for this robot

	final private void addWriteCap(Group temp) {
		/*Written by Andy Trent
		Input:  temp, of type Group
		Output:  none

		This function is largely a time-saver.  I needed to add the following capability to 
		several nodes, so I just made a function to do it.  In the future, if I need to add
		capabilities to every node in that set, I can just add them here once.  The capability 
		added allows the children of a GroupNode to be written to.
			It's one input is any node of extending Group.  The function is finalized
		so that it will be compiled inline.*/

		temp.setCapability(Group.ALLOW_CHILDREN_WRITE);
	}

	final private float degToRad(float deg)  {
		/*Written by Andy Trent
		Input:  deg, a floating point variable
		Output:  a floating point value that is the radian equivalent of 'deg'
		This function simply converts a degree measurement to a radian mesurement*/
		
		return (float)(deg * 2 * Math.PI / 360);
	}

	private void buildRobot() {
		/*Written by Andy Trent
		Input:  none
		Output:  none
		Variables:  tempM:		a Matrix3f which is the rotations necessary to place the 0th joint where
									needs to be.
					LinkXT:		The Transform3D object corresponding to LinkX
					LinkXTG:	The TransformGroup object corresponding to LinkX
					Link0:		The geometry for the cylinder which supports the robot
					Link0a:		The wide cylinder that Link0 seems to be anchored to.  It sits on the floor
					Link1:		The cylinder extending horizontally from Link0.  Link2 attaches to it.
					Link2:		The box that is the upper arm.
					Link3a:		Cylinder to cover the space between Link2 and Link3.
					Link3:		The box that is the lower arm.
					Link4:		The sphere which covers the gap between Link3 and the EE
		This function adds to the global class variable 'RobotBase.'  What it adds is the scenegraph
		representing the robot. It builds all joints and links, then adds each to the base*/

		RobotBase.setCapability(BranchGroup.ALLOW_CHILDREN_WRITE);
		RobotBaseTG.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE | TransformGroup.ALLOW_PICK 
									| TransformGroup.ALLOW_PICKABLE_WRITE | TransformGroup.ALLOW_PICKABLE_READ
									);
		addWriteCap(RobotBaseTG);

		Matrix3f tempM = new Matrix3f(	0f, 1f, 0f,
										0f, 0f, 1f,
										1f, 0f, 0f);
	//set up all the joints...
		Joint0 = new Joint(tempM, new Vector3f(0f, .6604f, 0f));
		Joint1 = new Joint(((float)(Math.PI / 2)), (-(float)(Math.PI / 2)), 0, 0, -160, 160 );
		Joint2 = new Joint(0f, 0f, .432f, .1495f, -225, 45);
		Joint3 = new Joint(((float)(Math.PI / 2)), ((float)(Math.PI / 2)), 0f, 0f, -45, 225);
		Joint4 = new Joint(0f, (-(float)(Math.PI / 2)), 0f, .432f, -110, 170);
		Joint5 = new Joint(0f, ((float)(Math.PI / 2)), 0f, 0f, -100, 100);
		Joint6 = new Joint(0f, 0f, 0f, .0565f, -266, 266);

	//build the links...
		//starting with the base
		Transform3D Link0T = new Transform3D();
		Link0T.rotX((float)(Math.PI * .5));
		Link0T.setTranslation(new Vector3f(0f, 0f, -.3302f));
		TransformGroup Link0TG = new TransformGroup(Link0T);
		addWriteCap(Link0TG);
		Cylinder Link0 = new Cylinder(.07f, .6604f, ltBlue);
		Link0TG.addChild(Link0);

		//then the short cylinder around the bottom of the base
		Cylinder Link0a = new Cylinder(.3f, .1f, dkBlue);
		Transform3D Link0aT = new Transform3D();
		Link0aT.rotX((float)(Math.PI * .5));
		Link0aT.setTranslation(new Vector3f(0f, 0f, -.6104f));
		TransformGroup Link0aTG = new TransformGroup(Link0aT);
		addWriteCap(Link0aTG);
		Link0aTG.addChild(Link0a);

		//Link1, aka Shoulder
		Transform3D Link1T = new Transform3D();
		Link1T.rotX(-(float)(Math.PI * .5));
		Link1T.setTranslation(new Vector3f(0f, 0f, ((float)(.1495 * .5))));
		TransformGroup Link1TG = new TransformGroup(Link1T);
		addWriteCap(Link1TG);
		Cylinder Link1 = new Cylinder(.07f, (float)(2*.1495), dkBlue);
		Link1TG.addChild(Link1);

		//now for the second link -- upper arm...
		Transform3D Link2T = new Transform3D();
		Link2T.setTranslation(new Vector3f((-(float)(.4325 * .5)), 0f, 0f));
		TransformGroup Link2TG = new TransformGroup(Link2T);
		addWriteCap(Link2TG);
		Block Link2 = new Block((float)(.4325 * .5), .09f, .07f, ltBlue);
		Link2TG.addChild(Link2);
		
		//add link3A....a thingy to cover up the wierd joint....
		Transform3D Link3aT = new Transform3D();
		Link3aT.rotX((float)(Math.PI * .5));
		Cylinder Link3a = new Cylinder(.07f, .15f, dkBlue);
		TransformGroup Link3aTG = new TransformGroup(Link3aT);
		addWriteCap(Link3aTG);
		Link3aTG.addChild(Link3a);

		//and Link3...
		Transform3D Link3T = new Transform3D();
		Link3T.setTranslation(new Vector3f(0f, (float)(.4325 * .5), 0f));
		TransformGroup Link3TG = new TransformGroup(Link3T);
		addWriteCap(Link3TG);
		Block Link3 = new Block(.07f, (float)(.4325 * .5), .06f, ltBlue);
		Link3TG.addChild(Link3);

		// add link4...the sphere to which the EE attaches
		Transform3D Link4T = new Transform3D();
		TransformGroup Link4TG = new TransformGroup(Link4T);
		addWriteCap(Link4TG);
		Sphere Link4 = new Sphere(((float)(.0565)), dkBlue);
		Link4TG.addChild(Link4);

	//next, string all the joints together...
		RobotBaseTG.addChild(Joint0);
		Joint0.getTransformGroup().addChild(Joint1);
		Joint1.getTransformGroup().addChild(Joint2);
		Joint2.getTransformGroup().addChild(Joint3);
		Joint3.getTransformGroup().addChild(Joint4);
		Joint4.getTransformGroup().addChild(Joint5);
		Joint5.getTransformGroup().addChild(Joint6);

	//then add the links to the joints...			
		Joint0.getTransformGroup().addChild(Link0TG);
		Joint0.getTransformGroup().addChild(Link0aTG);
		Joint1.getTransformGroup().addChild(Link1TG);
		Joint2.getTransformGroup().addChild(Link2TG);
		Joint2.getTransformGroup().addChild(Link3aTG);
		Joint4.getTransformGroup().addChild(Link3TG);		
		Joint5.getTransformGroup().addChild(Link4TG);

	//then add the claw
		claw = new Claw(ltBlue, dkBlue);
		Joint6.getTransformGroup().addChild(claw.getClawBG());
	//and finally add the whole three to the root of the robot graph
		RobotBase.addChild(RobotBaseTG);
  }

	private void keepUpAppearances() {
		/*Written by Andy Trent
		Input:  none
		Output:  none
		This method just defines the two appearances used for color*/

		ltBlue.setColoringAttributes(new ColoringAttributes(.5f, .6f, .7f, 0));
		dkBlue.setColoringAttributes(new ColoringAttributes(0f, 0f, 1.0f, 100));	
	}

	public BranchGroup getRobotGraph() {
		/*Written by Andy Trent
		Input:  none
		Output:  BranchGroup representing the root of the robot graph
		This method returns the base of the robot scene-graph*/

		return RobotBase;
	}
	
	public void movementRequested(MovementRequestEvent e) {
		/*Written by Andy Trent
		Input:  One MovementRequestEvent, e
		Output:  None
		This processes all the commands sent to the robot in a big switch statement.  The parts 
		commented out are things which we don't have the commands worked out for yet.*/
		switch(e.getID()) {

		case MovementRequestEvent.ROTATE:
			//this one causes the robot to rotate the specified angles and appear at the new position
			Joint1.addTheta(e.arg[0]);
			Joint2.addTheta(e.arg[1]);
			Joint3.addTheta(e.arg[2]);
			Joint4.addTheta(e.arg[3]);
			Joint5.addTheta(e.arg[4]);
			Joint6.addTheta(e.arg[5]);
			break;
/*		case MovementRequestEvent.ROTATE:
			//this will eventually be an animated version of ROTATE
			Joint0.moveJoint(e.arg[0]);
			Joint1.moveJoint(e.arg[1]);
			Joint2.moveJoint(e.arg[2]);
			Joint3.moveJoint(e.arg[3]);
			Joint4.moveJoint(e.arg[4]);
			Joint5.moveJoint(e.arg[5]);
			break;
		case MovementRequestEvent.MOVETO:
			//this takes care of moving to a particular point
			processVector(stuff......);
			break;
*/
		case MovementRequestEvent.RESET:
			//this will return the robot to its original position
			Joint1.reset();
			Joint2.reset();
			Joint3.reset();
			Joint4.reset();
			Joint5.reset();
			Joint6.reset(); 
			break;  
		case MovementRequestEvent.TOGGLEAXIS:
			//this one toggles a visual representation of the axis for each joint of the robot
			switch(e.arg[0]) {
				case 0:
					Joint0.toggleAxis();
					break;
				case 1:
					Joint1.toggleAxis();
					break;
				case 2:
					Joint2.toggleAxis();
					break;
				case 3:
					Joint3.toggleAxis();
					break;
				case 4:
					Joint4.toggleAxis();
					break;
				case 5:
					Joint5.toggleAxis();
					break;
				case 6:
					Joint6.toggleAxis();
					break;
			}
			break;
		}
	}



	private Transform3D processVector(float x, float y, float z, float aboutX, float aboutY, float aboutZ) {
		/*Written by Andy Trent
		Input:  x:		the new x coordinate for the EE
				y:		the new y coordinate for the EE
				z:		the new z coordinate for the EE
				aboutX:	Any rotation about the X-axis for the new position
				aboutY: Any rotation about the Y-axis for the new position
				aboutZ: Any rotation about the Z-axis for the new position
		Output:  returns a Transform3D that is a transformation to the new coordinate frame of joint6
		Variables:	Xrot:  Transform3D with the new X rotation matrix
					Yrot:  Transform3D with the new Y rotation matrix
					Zrot:  Transform3D with the new Z rotation matrix
					result: Transform3D built from all the rotations and the translation
		This method takes the parameters of the new position for the EE and creates a transformation 
		to that point.  This will later be input into the inverse kinematics algorithm to compute the
		path.*/

		Transform3D Xrot = new Transform3D();
		Transform3D Yrot = new Transform3D();
		Transform3D Zrot = new Transform3D();
		Transform3D result = new Transform3D();
		Xrot.rotX(degToRad(aboutX));
		Yrot.rotY(degToRad(aboutY));
		Zrot.rotZ(degToRad(aboutZ));
		result.mul(Xrot);
		result.mul(Yrot);
		result.mul(Zrot);
		result.setTranslation(new Vector3f(x, y, z));
		return result;
	}

	public Robot()  {
		/*Written by Andy Trent
		Input:  none
		Output:  None
		This is the constructor for the robot.  It builds the scene-graph and sets up the colors. 
		From that point, the robot just sits and waits for events.*/

		keepUpAppearances();
		buildRobot();
	}
}
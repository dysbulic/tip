import javax.media.j3d.*;
import javax.vecmath.*;
import java.lang.*;
import Axis;

class Joint extends BranchGroup {
	/*Written by Andy Trent
	Input: none
	Output: none
	Variables:	theta:		angle the joint is rotated.  This is the only of these first four which
								changes.
				alpha:		the alpha value of that joint according to a looked-up value
				a:			the a value for the joint according to looked-up value
				d:			d value according to looked up value
				thetaInit:	the initial angle or rotation for the joint.  Used to return robot to 
								initial state
				lowerBound:	the lower bound of this joint's motion
				upperBound:	the upper bound of this joint's motion
				jointTrans:	the Transform3D object representing the joint's location
				jointTG:	the TransformGroup that other things (geometry etc) can attach to.
				haveAxis:	flag stating whether the graphical coordinate frame is turned on or off
				axis:		an axis object attached.  When visible it will show the axis for the
								coordinate frame of this joint.
				axisAttachBG:  the BranchGroup that the axis attaches to.

	This class represents a joint on the robot.  It extends branchgroup to make it easier to add 
	where ever it needs to be and so that it may be detached from the scene-graph at run-time.*/

	private float theta, alpha, a, d, thetaInit;
	private float lowerBound, upperBound;
	private Transform3D jointTrans = new Transform3D();
	private TransformGroup jointTG = new TransformGroup();

	private int haveAxis = 0;
	private Axis axis = new Axis();
	private BranchGroup axisAttachBG = new BranchGroup();
	


	private void calcTransform()  {
		/*Written by Andy Trent
		Input: none
		Output: none
		Variables: m:	This is a 4x4 floating point matrix whose values are calculated according
							to the kinematic equations for the PUMA robot. It is then used as the 
							Transform3D of the joint.
		This method just calculates the transformation for the joing from its a, d, theta, and alpha
		values then sets that transformation into the jointTG.*/

		Matrix4f m = new Matrix4f();
		m.m00 = (float)(Math.cos(theta));
		m.m01 = -(float)(Math.cos(alpha) * Math.sin(theta));
		m.m02 = (float)(Math.sin(alpha) * Math.sin(theta));
		m.m03 = (float)(a * Math.cos(theta));
		m.m10 = (float)(Math.sin(theta));
		m.m11 = (float)(Math.cos(alpha) * Math.cos(theta));
		m.m12 = -(float)(Math.sin(alpha) * Math.cos(theta));
		m.m13 = (float)(a * Math.sin(theta));
		m.m20 = 0;
		m.m21 = (float)(Math.sin(alpha));
		m.m22 = (float)(Math.cos(alpha));
		m.m23 = d;
		m.m30 = 0;
		m.m31 = 0;
		m.m32 = 0;
		m.m33 = 1;
		jointTrans.set(m);
		jointTG.setTransform(jointTrans);
	}



	public Joint(float inTheta, float inAlpha, float inA, float inD, float inLower, float inUpper) {
		/*Written by Andy Trent
		Input:	inTheta:	the initial value for theta
				inAlpha:	the initial value for alpha
				inA:		the intial value for a
				inD:		the intial value for d
				inLower:	the lower bound of this joint's motion
				inUpper:	the upper bound of this joint's motion
		Output: none
		This constructor is called for all but the 0th joint and just sets the joint's internal variables 
		to the values passed to the joint.*/

		alpha = inAlpha;
		theta = inTheta;
		thetaInit = inTheta;
		a = inA;
		d = inD;
		calcTransform();
		jointTG.setCapability(jointTG.ALLOW_TRANSFORM_WRITE);
		jointTG.setCapability(ALLOW_CHILDREN_EXTEND);
		lowerBound = degToRad(inLower);
		upperBound = degToRad(inUpper);
		setupAxis();
		setBGAttribs();
		this.addChild(jointTG);
	}


	public Joint(Matrix3f rotation, Vector3f translation) {
		/*Written by Andy Trent
		Input:	rotation:	the rotation matrix which puts the 0th joint in the proper orientation
				translation:the translation that puts it in the correct position
		Output: none
		Variables: none
		This constructor is called by the 0th joint and puts it in the proper place to build the rest
		of the robot on top of it.  This is necessary because in all the books, the robot just starts 
		with joint1 being defined from a coordinate frame that does not match the standard x=horizontal,
		y=vertical etc model.  I have to get the 0th frame in the correct orientation so that joint1 and
		all subsequent will be correctly represented.*/

		jointTrans.setRotation(rotation);
		jointTrans.setTranslation(translation);
		jointTG.setCapability(jointTG.ALLOW_TRANSFORM_WRITE);
		jointTG.setCapability(ALLOW_CHILDREN_EXTEND);
		jointTG.setTransform(jointTrans);
		setupAxis();
		setBGAttribs();
		thetaInit = 0;
		this.addChild(jointTG);
	}


	private void setBGAttribs() {
		/*Written by Andy Trent
		Input: none
		Output: none
		Variables:	none
		This method sets the capabilities for the joint.  Since two constructors were needed and
		the capabilities are the same for each type of joint, I stuck them in a method rather 
		than write it all out twice.*/

		this.setCapability(ALLOW_CHILDREN_WRITE);
		this.setCapability(ALLOW_DETACH);
		this.setCapability(ALLOW_COLLISION_BOUNDS_WRITE);
	}


	private void setupAxis() {
		/*Written by Andy Trent
		Input: none
		Output: none
		Variables: none
		This method prepares the BranchGroup that is used to attach the coordinate frame or axis
		when that function is called up.*/

		axisAttachBG.setCapability(ALLOW_CHILDREN_EXTEND);
		axisAttachBG.setCapability(ALLOW_CHILDREN_WRITE);
		this.addChild(axisAttachBG);
	}


	public void toggleAxis() {
		/*Written by Andy Trent
		Input:  none
		Output: none
		Variables: none
		If this joint has the graphical representation of it's axis turned on, it turns it off and 
		if it is turned off, it turns it on.*/

		if(haveAxis == 0) {
			axisAttachBG.addChild(axis.getAxisBG());
			haveAxis = 1;
		}
		else {
			axisAttachBG.removeChild(0); 
			//the axis is always child 0 because it is the only thing added to the BG
			haveAxis = 0;
		}
	}

	
	public void reset() {
		/*Written by Andy Trent
		Input: none
		Output: none
		Variables: none
		This method returns the theta variable of the joint to its intitial value then recalculates
		its Transform3D object.*/
		theta = thetaInit;
		calcTransform();
	}

	
	public void addTheta(float newTheta)  {
		/*Written by Andy Trent
		Input: none
		Output: none
		Variables: none
		This method adds the value of theta to be added to the old value.  If the result is outside
		the bounds of the joint, it sets the new angle to whichever bound was exceeded.  
		calcTransform() is then called to update the jointTrans object.	*/

		theta += degToRad(newTheta);
		if(theta > upperBound)
			theta = upperBound;
		if(theta < lowerBound)
			theta = lowerBound;
		calcTransform();
		return;
	}


	final private float degToRad(float deg)  {
		/*Written by Andy Trent
		Input: deg:  An angle measure in degrees
		Output: returns an equivalent angle measure in radians
		Variables: none
		Simply takes a degree measure and returns a radian.  Finalized to inline it.*/
		return (float)(deg * 2 * Math.PI / 360);
	}

	
	public void moveJoint(float range) {
		/*Written by Andy Trent
		Input: a range over which the joing should rotate
		Output: none
		Variables: not yet determined
		This method doesn't work and I'm not sure how to make it.  it is going to be responsible
		for moving the joint around in an animated fashion...but now it just throws errors.*/

		float motion;

		if(theta + range > upperBound) {
			motion = upperBound - theta;
			theta = upperBound;
		}
		else {
			if(range + theta < lowerBound) {
				motion = lowerBound - theta;
				theta = lowerBound;
			}
			else {
				theta += range;
				motion = range;
			}
		}

		Alpha rotationAlpha = new Alpha(1, Alpha.INCREASING_ENABLE,
					0, 0,			//triggerTime, phaseDelayDuration
					10000, 0, 0,	//increaseAlpha, incAlphaRamp, atOneDuration
					0, 0, 0);		//decAlpha, decAlphaRamp, alphaAtZero
	

		Transform3D Axis = new Transform3D();
		Axis.rotX(Math.PI * .5f);
		RotationInterpolator rotator =
		    new RotationInterpolator(rotationAlpha, jointTG, Axis,
						0.0f, motion);
		BoundingSphere bounds =
		    new BoundingSphere(new Point3d(1.0f,1.0f,1.0f), 100.0);
		rotator.setSchedulingBounds(bounds);
		this.detach();
		jointTG.addChild(rotator);
	}


	public Transform3D getTransform() {
		/*Written by Andy Trent
		Input: none
		Output: returns a Transform3D object
		Variables: none
		This is just an accessor method that lets other methods do things with this joint's 
		Transform3D.  I don't think it is used, but it has been at various times as I've tried 
		to make the animation work.*/ 

		return jointTrans;
	}


	public TransformGroup getTransformGroup() {
		/*Written by Andy Trent
		Input: none
		Output: returns a TransformGroup object
		Variables:	none
		This is another accessor method that lets others do things with this joint's TransformGroup. 
		The most important use is for a robot object to add geometry to this joint*/

		return jointTG;
	}
}

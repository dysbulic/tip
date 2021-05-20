import javax.media.j3d.*;
import javax.vecmath.*;
import java.lang.*;
import Block;

public class Claw {
/*Written by Andy Trent

		___		___
		|S|		|S|
		|i|		|i|
		|d|		|d| <- Diagram of what the sides and back of the claw are
		|e|_____|e|
		|	back  |
		-----------
	Variables:	bx:		The x value of the back
				by:		The y value of the back
				bz:		The z value of the back
				sx:		The x value of each side
				sy:		The y value of each side
				sz:		The z value of each side
				ClawBase:		The BranchGroup rooting this graph
				ClawBaseT:		The TransformGroup underneath the root to which 
									all geometry is attached
				AttachPoint:	This is a TransformGroup that any carried object's
									graph will be attached to.
				
	This method is currently just the geometry of the end effector for the robot.
	Eventually it will also contain methods for grasping and releasing objects
	as well as any sensors are necesessary to do what it needs to do.*/

	private float bx = .03f;	//back height
	private float by = .11f;	//back length
	private float bz = .015f;	//back width
	private float sx = .03f;	//side height
	private float sy = .015f;	//side thickness
	private float sz = .09f;	//side length


	private BranchGroup ClawBase = new BranchGroup();
	private Transform3D ClawBaseT = new Transform3D();
	public TransformGroup AttachPoint;

	
	public Claw(Appearance Color1, Appearance Color2) {
	/*Written by Andy Trent
		Input: Color1, Color2 are both colors used later.
		Output: none
		Variables:  ClawBack:	This is the geometry for the "back" in above diagram
					TransToAttach:	This is a Transform3D from the origin of this objects'
										geometry to the AttachPoint
					ClawBaseTG:	This is the TransformGroup to the beginning of the claw
					Side1T:		This is the transform to put Side1 in place
					Side1TG:	This is the TransformGroup Side1T is placed in
					Side1:		This is the geometry for that side
					Side2T:		This is the transform to put Side2 in place
					Side2TG:	This is the TransformGroup Side2T is placed in
					Side2:		This is the geometry for that side

	This constructor simply builds the graph for the geometry of the claw.*/

		ClawBaseT.setTranslation(new Vector3f(0f, 0f, (float)(bz + .0)));
		TransformGroup ClawBaseTG = new TransformGroup(ClawBaseT);
		ClawBase.addChild(ClawBaseTG);
		Block ClawBack = new Block(bx, by, bz, Color1);
		ClawBaseTG.addChild(ClawBack);

		Transform3D TransToAttach = new Transform3D();
		TransToAttach.setTranslation(new Vector3f(0f, 0f, ((2*bz) + .02f)));
		AttachPoint = new TransformGroup(TransToAttach);
		ClawBaseTG.addChild(AttachPoint);
		
		Transform3D Side1T = new Transform3D();
		Side1T.setTranslation(new Vector3f(0f, by-sy, bz+sz));
		TransformGroup Side1TG = new TransformGroup(Side1T);
		Side1TG.setCapability(Side1TG.ALLOW_TRANSFORM_WRITE);
		Block Side1 = new Block(sx, sy, sz, Color2);
		Side1TG.addChild(Side1);
		ClawBaseTG.addChild(Side1TG);
	
		Transform3D Side2T = new Transform3D();
		Side2T.setTranslation(new Vector3f(0f, -(by-sy), bz+sz));
		TransformGroup Side2TG = new TransformGroup(Side2T);
		Side2TG.setCapability(Side2TG.ALLOW_TRANSFORM_WRITE);
		Block Side2 = new Block(sx, sy, sz, Color2);
		Side2TG.addChild(Side2);
		ClawBaseTG.addChild(Side2TG);
	}


	public BranchGroup getClawBG() {
	/*Written by Andy Trent
		Output:  returns BranchGroup ClawBase
	This is just an accessor method that returns the base of the claw graph.*/

		return ClawBase;
	}
}

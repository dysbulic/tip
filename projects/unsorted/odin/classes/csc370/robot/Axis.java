import javax.media.j3d.*;
import javax.vecmath.*;
import com.sun.j3d.utils.geometry.Cylinder;


public class Axis {
/*Written by Andy Trent
	Input:	none
	Output:	none
	Variables:	AxisBG:	The BranchGroup that roots this graph
				Red:	A red appearance
				Blue:	A blue appearance
				Green:	A green appearance
This class is something I can add just about anywhere in the program that will let me 
see the coordinate frame for a particular TransformGroup.  It helps me debug things, 
and could be used in showing people how this particular represetation works.  The set
of axis (x, y, z) are represented by red, blue, and green respectively.  I picked that 
order because it was easy to remember...xyz = RGB....*/

	private BranchGroup AxisBG = new BranchGroup();
	private Appearance Red = new Appearance();
	private Appearance Blue = new Appearance();
	private Appearance Green = new Appearance();

	public Axis() {
	/*Written by Andy Trent
		Input:  none
		Ouput:	none
		Variables:	X:	This cylinder is the geometry for the X axis
					Y:	This is the geometry for Y axis
					Z:	The geometry for Z axis
					XT:	This is the Transform3D which correctly orients the X cylinder
					YT:	Orients the Y cylinder
					ZT:	Orients the Z cylinder
					XTG:	The TransformGroup XT is placed in
					YTG:	The TransformGroup YT is placed in
					ZTG:	The TransformGroup ZT is placed in
	This just sets up a branchgraph that will have one cylinder of appropriate color
	pointing down each axis...*/

		Red.setColoringAttributes(new ColoringAttributes(1.0f, 0f, .2f, 100));
		Blue.setColoringAttributes(new ColoringAttributes(0f, 0f, 1.0f, 100));
		Green.setColoringAttributes(new ColoringAttributes(0f, 1.0f, 0f, 100));

		AxisBG.setCapability(AxisBG.ALLOW_DETACH);

		Cylinder X = new Cylinder(.01f, 1.2f, Red);
		Transform3D XT = new Transform3D();
		XT.rotZ(-(float)(Math.PI * .5));      //flip it -90 deg about Z
		XT.setTranslation(new Vector3f(.6f, 0f, 0f));
		TransformGroup XTG = new TransformGroup(XT);
		XTG.addChild(X);
		AxisBG.addChild(XTG);

		Cylinder Y = new Cylinder(.01f, 1.2f, Green);
		Transform3D YT = new Transform3D();	  //Cylinders are along the Y axis by default
											  //so this one doesn't need rotating
		YT.setTranslation(new Vector3f(0f, .6f, 0f));
		TransformGroup YTG = new TransformGroup(YT);
		YTG.addChild(Y);
		AxisBG.addChild(YTG);

		Cylinder Z = new Cylinder(.01f, 1.2f, Blue);
		Transform3D ZT = new Transform3D();
		ZT.rotX((float)(Math.PI * .5));		  //rotate 90 deg about X
		ZT.setTranslation(new Vector3f(0f, 0f, .6f));
		TransformGroup ZTG = new TransformGroup(ZT);
		ZTG.addChild(Z);
		AxisBG.addChild(ZTG);
	}


	public BranchGroup getAxisBG() {
	/*Written by Andy Trent
		Output:  BranchGroup AxisBG
	This accessor returns the base of the graph*/

		return AxisBG;
	}
	

	public void detachAxis() {
	/*Written by Andy Trent
	This method detaches this graph from whatever it is attached to.  Enables the Axis object
	and it's nifty cylinders to be "turned off."*/

		AxisBG.detach();
	}
}
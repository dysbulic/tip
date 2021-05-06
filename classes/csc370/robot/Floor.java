import javax.media.j3d.*;
import javax.vecmath.*;
import java.lang.*;

public class Floor {
/*Written by Andy Trent
	Input: none
	Output: none
	Variables:  FloorBase:  a BranchGroup that is the root for this objects scene-graph
	This forms the floor of the factory.  There is probably an easier way to do it, but
	I haven't had to use this type of geometry enough to really know how to use
	it well.*/

	private BranchGroup FloorBase = new BranchGroup();

	public Floor() {
	/*Written by Andy Trent
		Input:
		Output:
		Variables:	Red:		an appearance that sets the color of the geometry
					length:		the length of the floor (and width, for that matter)
					interval:	distance between lines
					index:		the number of lines
					FloorTG:	TransformGroup underneath the FloorBase group
					FloorGeometry:	the points lines are to be drawn between.
					Latitudinal:	the set of lines going in the x direction
					Longitudinal:	set of lines which will later be in z direction
					LongitudinalT:	Transform3D that flips Long. into z direction
					LongitudinalTG:	TransformGroup LongitudinalT is placed into
		This method has the neat task of drawing the floor.  It only works if the interval
		is .25 meters.  I've tried to make it a bit more robust, but haven't had any luck
		and it isn't *that* important to me right now.  Ideally it would take a length and
		an intended line density the calculate the number of lines needed and draw them. 
		Doing that runs into problems like having an odd number of points in the array.  
		When that happens, the edges of the floor don't show up because their isn't a pair 
		of points to connect.  
		*/

		Appearance Red = new Appearance();
		Red.setColoringAttributes(new ColoringAttributes(1.0f, 0f, .2f, 100));

		float length = 4f;
		float interval = .25f;
	/*	if((length % interval) != 0)
			interval = (int)(length / interval);
*/
		int index = ((int)((length / interval + 1) * 2));

		TransformGroup FloorTG = new TransformGroup();
		LineArray FloorGeometry = new LineArray(index, GeometryArray.COORDINATES);

		int i = 0;
		for(float z = -length/2; z <= length/2; z+=interval)  {
			FloorGeometry.setCoordinate(i, new Point3f(-length/2, 0, z));
			i++;
			FloorGeometry.setCoordinate(i, new Point3f(length/2, 0, z));
			i++;
		}
		
		Shape3D Latitudinal = new Shape3D(FloorGeometry, Red);

		Shape3D Longitudinal = new Shape3D(FloorGeometry, Red);
		Transform3D LongitudinalT = new Transform3D();
		LongitudinalT.rotY((float)(Math.PI * .5));
		TransformGroup LongitudinalTG = new TransformGroup(LongitudinalT);
		LongitudinalTG.addChild(Longitudinal);

		FloorTG.addChild(Latitudinal);
		FloorTG.addChild(LongitudinalTG);
		FloorBase.addChild(FloorTG);
	}


	public BranchGroup getFloorGraph() {
	/*Written by Andy Trent
		Input: none
		Output:  none
		Variables: none
	This is just a simple accessor method that returns a reference to the root of
	the graph for this object.  Not very exciting.*/

		return FloorBase;
	}
}
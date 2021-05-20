import javax.media.j3d.*;
import javax.vecmath.*;
import java.lang.*;
import java.applet.Applet;
import java.awt.*;
import java.awt.event.*;
import Robot;
import Floor;
import com.sun.j3d.utils.universe.*;
import com.sun.j3d.utils.geometry.Cylinder;
import com.sun.j3d.utils.geometry.Sphere;

public class FactoryFloor extends Panel {	
/*  Written by Andy Trent
 INPUTS:  None
 OUTPUTS:  None
 VARIABLES: One of type Robot named George.  In the future, robots will be stored
		in an array to which they can be added and deleted.
This class represents the floor of the factory -- a "blank slate"
in which the user can perform different factory operations.*/

	
	/*private Robot RobotArray[] = new Robot();

	public addRobot(string name) {
		Robot temp = new Robot();
		/*create temporary array with n+1 members, assign temp to n+1...
		set old RobotArray equal to it/
	}
*/
	public Robot George;	//our one robot


	public BranchGroup createSceneGraph() {
		/*  Written by Andy Trent
		Inputs:  none
		Outputs:  returns a BranchGroup that is the root of the entire scene's scene-graph
		Variables:	objRoot:	BranchGroup that is the root of the scene-graph
					baseTransG:	the TransformGroup that moves everything below the root
					baseTrans:	the Transform3D set in baseTransG
					floor:		the wire-frame floor
		This method builds the scene graph for this scene and returns it to the 
		constructor it takes no arguments and returns the root node
		of a branchgraph.*/

		//create root of graph
		BranchGroup objRoot = new BranchGroup();

		//create transform to base
		TransformGroup baseTransG = new TransformGroup();
		baseTransG.setCapability(TransformGroup.ALLOW_TRANSFORM_WRITE);
		
		//add to root
		objRoot.addChild(baseTransG);

		//create transform to be applied to baseTransG 
		Transform3D baseTrans = new Transform3D();

		baseTrans.setTranslation(new Vector3f(0f, -1f, 0f));
		//set it as transform of baseTransG
		baseTransG.setTransform(baseTrans);

		George = new Robot();  //creates new robot and adds to scene
		baseTransG.addChild(George.getRobotGraph());
		
		Floor floor = new Floor(); // creates new Floor object and adds to scene
		baseTransG.addChild(floor.getFloorGraph());
		
		objRoot.compile();
		return objRoot;
	}

 
	public FactoryFloor() {
		/*Written by Andy Trent
		This constructor takes no arguments and has no return value.  It 
		prepares a canvas onto which the scene will be rendered and then renders
		the scene.  I'm not sure what a lot of this does, but the Java3D book says
		to do it.*/

		setLayout(new BorderLayout());
		GraphicsConfiguration config = SimpleUniverse.getPreferredConfiguration();

		Canvas3D c = new Canvas3D(config);
		add("Center", c);

		BranchGroup scene = createSceneGraph();
		SimpleUniverse u = new SimpleUniverse(c);

		u.getViewingPlatform().setNominalViewingTransform();
		u.getViewer().getView().setFieldOfView(1.3f);
		
		u.addBranchGraph(scene);
	}
}

import com.sun.j3d.utils.geometry.*;
import javax.media.j3d.*;
import javax.vecmath.*;


public class Block extends Box {
/*Written by Andy Trent
	This is a fairly simple extension of the Box class in the j3d utility
	package.  All my extension does is allow me to make each side a different 
	color.  This was kind of useful at one point so I used it but now I'm not
	if I ever use that option.  I still use the class though because everynow
	and then it's nice to be able to see whether I'm looking at the top, bottom,
	or a side of some part of the robot.*/

	public Block() {
	/*Written by Andy Trent
		Input: none
		Output:  none
		Variables: none
	This does nothing but call the constructor for Box with default values*/

		super(1.0f, 1.0f, 1.0f, GENERATE_NORMALS, null);
	}

  
	public Block(float xdim, float ydim, float zdim, Appearance ap) {
	/*Written by Andy Trent
		Input:	xdim:	x dimensions for the block
				ydim:	y dimensions for the block
				zdim:	z dimensions for the block
				ap:		an Appearance object used to specify color.
		Output:  none
		Variables: none
	This calls the Box constructor with dimensions and an appearance, using
	a default for the primitive flags field.*/

		super(xdim, ydim, zdim, GENERATE_NORMALS, ap);
	}


	public Block(float xdim, float ydim, float zdim, int primflags,
			Appearance ap) {
	/*Written by Andy Trent
		Input:	xdim:		x dimensions for the block
				ydim:		y dimensions for the block
				zdim:		z dimensions for the block
				primflags:	integer with various bits set specifying attributes
								of the geometry.
				ap:		an Appearance object used to specify color.
		Output:  none
		Variables: none
	Once again, just a constructor.  This one doesn't use any defaults though.*/

		super(xdim, ydim, zdim, primflags, ap);
	}
    
  
	public void setAppearance(Appearance ap, int partId) {
	/*Written by Andy Trent
		Input:	ap:		an Appearance object used to specify color.
				partId:	integer corresponding to TOP, BOTTOM, LEFT, RIGHT, FRONT, BACK
							as defined in Box.
		Output:  none
		Variables: none
	This sets the color of the side noted by partId to the specified color*/

		if ((partId >= FRONT) && (partId <= BOTTOM)) //if partId is member [1...6]
			((Shape3D)((Group)getChild(0)).getChild(partId)).setAppearance(ap);
	}
}


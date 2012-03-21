import java.lang.Math;
public class AffineTransforms
{

   public static Matrix scale(double x, double y, double z)
   {
      Matrix S=new Matrix(4,4);

      S.setElement(1,1,x);
      S.setElement(1,2,0);
      S.setElement(1,3,0);
      S.setElement(1,4,0);

      S.setElement(2,1,0);
      S.setElement(2,2,y);
      S.setElement(2,3,0);
      S.setElement(2,4,0);

      S.setElement(3,1,0);
      S.setElement(3,2,0);
      S.setElement(3,3,z);
      S.setElement(3,4,0);

      S.setElement(4,1,0);
      S.setElement(4,2,0);
      S.setElement(4,3,0);
      S.setElement(4,4,1);

      return S;
   }
   
   public static Matrix translate(double x, double y, double z)
   {
      Matrix S=new Matrix(4,4);

      S.setElement(1,1,1);
      S.setElement(1,2,0);
      S.setElement(1,3,0);
      S.setElement(1,4,x);

      S.setElement(2,1,0);
      S.setElement(2,2,1);
      S.setElement(2,3,0);
      S.setElement(2,4,y);

      S.setElement(3,1,0);
      S.setElement(3,2,0);
      S.setElement(3,3,1);
      S.setElement(3,4,z);

      S.setElement(4,1,0);
      S.setElement(4,2,0);
      S.setElement(4,3,0);
      S.setElement(4,4,1);

      return S;
   }
   
   
   public static Matrix lookAt(Vertex eye, Vertex lookAt)
   {
      Vector n = eye.subtract(lookAt);
      Vector Vup = new Vector(0.0, 1.0, 0.0);
      
      Vector v = Vup.subtract( n.scaleBy( (Vup.dot(n))/(n.dot(n)) ) );
      Vector u = v.cross(n);

      //normalize the vectors
      u.normalize();
      v.normalize();
      n.normalize();             
      
      double x=eye.getX();
      double y=eye.getY();
      double z=eye.getZ();         
      
      Matrix S=new Matrix(4,4);

      S.setElement(1,1,u.getX());
      S.setElement(1,2,u.getY());
      S.setElement(1,3,u.getZ());
      S.setElement(1,4, (-x*u.getX())-(y*u.getY())-(z*u.getZ()));

      S.setElement(2,1,v.getX());
      S.setElement(2,2,v.getY());
      S.setElement(2,3,v.getZ());
      S.setElement(2,4, (-x*v.getX())-(y*v.getY())-(z*v.getZ()));

      S.setElement(3,1,n.getX());
      S.setElement(3,2,n.getY());
      S.setElement(3,3,n.getZ());
      S.setElement(3,4, (-x*n.getX())-(y*n.getY())-(z*n.getZ()));

      S.setElement(4,1,0);
      S.setElement(4,2,0);
      S.setElement(4,3,0);
      S.setElement(4,4,1);
      
      /* Debugging
      System.out.println("\nu is: " + u);
      System.out.println("v is: " + v);
      System.out.println("n is: " + n);
      System.out.println("u dot v is " + u.dot(v));
	  System.out.println("v dot n is " + v.dot(n));
	  System.out.println("n dot u is " + n.dot(u));
	  */
	  
	        
      return S;
   }

   /**
    * returns the rotation matrix for a rotation about x the given degree
    * @param degrees the number of degrees to rotate about the X axis
    * @return        the rotation Matrix about X axis
    **/
   public static Matrix rotateX(double degrees)
   {
      double radians = degrees/180*3.14;
      Matrix Rx=new Matrix(4,4);

      Rx.setElement(1,1,1);
      Rx.setElement(1,2,0);
      Rx.setElement(1,3,0);
      Rx.setElement(1,4,0);

      Rx.setElement(2,1,0);
      Rx.setElement(2,2,Math.cos(radians));
      Rx.setElement(2,3,-Math.sin(radians));
      Rx.setElement(2,4,0);

      Rx.setElement(3,1,0);
      Rx.setElement(3,2,Math.sin(radians));
      Rx.setElement(3,3,Math.cos(radians));
      Rx.setElement(3,4,0);

      Rx.setElement(4,1,0);
      Rx.setElement(4,2,0);
      Rx.setElement(4,3,0);
      Rx.setElement(4,4,1);

      return Rx;         
   }
   
   /**
    * returns the rotation matrix for a rotation about y the given degree
    * @param degrees the number of degrees to rotate about the Y axis
    * @return        the rotation Matrix about Y axis
    **/
   public static Matrix rotateY(double degrees)
   {
      double radians = degrees/180*3.14;
      Matrix Ry=new Matrix(4,4);

      Ry.setElement(1,1,Math.cos(radians));
      Ry.setElement(1,2,0);
      Ry.setElement(1,3,Math.sin(radians));
      Ry.setElement(1,4,0);

      Ry.setElement(2,1,0);
      Ry.setElement(2,2,1);
      Ry.setElement(2,3,0);
      Ry.setElement(2,4,0);

      Ry.setElement(3,1,-Math.sin(radians));
      Ry.setElement(3,2,0);
      Ry.setElement(3,3,Math.cos(radians));
      Ry.setElement(3,4,0);

      Ry.setElement(4,1,0);
      Ry.setElement(4,2,0);
      Ry.setElement(4,3,0);
      Ry.setElement(4,4,1);

      return Ry;          
   }
   
   /**
    * returns the rotation matrix for a rotation about z the given degree
    * @param degrees the number of degrees to rotate about the Z axis
    * @return        the rotation Matrix about Z axis
    **/
   public static Matrix rotateZ(double degrees)
   {
      double radians = degrees/180*3.14;
      Matrix Rz=new Matrix(4,4);

      Rz.setElement(1,1,Math.cos(radians));
      Rz.setElement(1,2,-Math.sin(radians));
      Rz.setElement(1,3,0);
      Rz.setElement(1,4,0);

      Rz.setElement(2,1,Math.sin(radians));
      Rz.setElement(2,2,Math.cos(radians));
      Rz.setElement(2,3,0);
      Rz.setElement(2,4,0);

      Rz.setElement(3,1,0);
      Rz.setElement(3,2,0);
      Rz.setElement(3,3,1);
      Rz.setElement(3,4,0);

      Rz.setElement(4,1,0);
      Rz.setElement(4,2,0);
      Rz.setElement(4,3,0);
      Rz.setElement(4,4,1);

      return Rz;     
   }

   /**
    * transform to pixel coords
    * assumes point being transformed is in canonical coords
    **/
   public static Matrix window(int xPixels, int yPixels)
   {
      Matrix window = new Matrix(4,4);

      window.setElement(1,1,xPixels/2);
      window.setElement(1,2,0);
      window.setElement(1,3,0);
      window.setElement(1,4,(xPixels-1)/2);

      window.setElement(2,1,0);
      window.setElement(2,2,-yPixels/2);
      window.setElement(2,3,0);
      window.setElement(2,4,(yPixels-1)/2);

      window.setElement(3,1,0);
      window.setElement(3,2,0);
      window.setElement(3,3,1);
      window.setElement(3,4,0);

      window.setElement(4,1,0);
      window.setElement(4,2,0);
      window.setElement(4,3,0);
      window.setElement(4,4,1);

      return window;
   }


   public static Matrix orthoNormal(double zmax, double zmin)
   {
      Matrix window = new Matrix(4,4);
      
      double alpha = (1*(zmax+zmin)) / (zmax-zmin) ;
      double beta  = -(2*zmax*zmin) / (zmax-zmin) ;

      window.setElement(1,1,1);
      window.setElement(1,2,0);
      window.setElement(1,3,0);
      window.setElement(1,4,0);

      window.setElement(2,1,0);
      window.setElement(2,2,1);
      window.setElement(2,3,0);
      window.setElement(2,4,0);

      window.setElement(3,1,0);
      window.setElement(3,2,0);
      window.setElement(3,3,alpha);
      window.setElement(3,4,beta);

      window.setElement(4,1,0);
      window.setElement(4,2,0);
      window.setElement(4,3,-1);
      window.setElement(4,4,0);

      return window;
   }
}


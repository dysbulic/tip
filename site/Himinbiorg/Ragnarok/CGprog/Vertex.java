import java.text.DecimalFormat;
import java.awt.*;
import java.util.ArrayList;

public class Vertex
{
   private Matrix vertex;
   private Matrix world;
   private Color color;

   public Vertex(double x, double y, double z)
   {
      vertex = new Matrix(4,1);  //4D column matrix
      vertex.setElement(1,1,x);
      vertex.setElement(2,1,y);
      vertex.setElement(3,1,z);
      vertex.setElement(4,1,1.0);
      this.color=new Color(1.0,1.0,1.0);  //default color is white
   }   
   
   public Vertex(double x, double y, double z, double h)
   {
      vertex = new Matrix(4,1);  //4D column matrix
      vertex.setElement(1,1,x);
      vertex.setElement(2,1,y);
      vertex.setElement(3,1,z);
      vertex.setElement(4,1,h);
      this.color=new Color(1.0,1.0,1.0);  //default color is white
   }  

   public Vertex(double x, double y, double z, Color color)
   {
      vertex = new Matrix(4,1);  //4D column matrix
      vertex.setElement(1,1,x);
      vertex.setElement(2,1,y);
      vertex.setElement(3,1,z);
      vertex.setElement(4,1,1.0);
      this.color=color;
   }

   public Color calcColor(Light light, Vertex eyept, Color ambient, Vector faceNormal)
   {
      Vertex w = this.getWorld();
      //w = this;
      
      //define specular color and shininess coefficient alpha
      Color specular = new Color(1.0, 1.0, 1.0);
      double alpha = 19.0;
      
      Vector incidence = light.getLightPoint().subtract(w);
      double d = incidence.normAndGetMag();
      
      //compute this once instead of several times
      double ldotn = incidence.dot(faceNormal);
      
      Vector reflection = faceNormal.scaleBy(2*(ldotn)).subtract(incidence);
      
      //get attenuation constants from light
      double a = light.getA();
      double b = light.getB();
      double c = light.getC();
      
      //viewer direction is always +z direction because of LookAt transform
      //Vector v = new Vector(0,0,1);
      Vector v = eyept.subtract(w);
      v.normalize();
                  
      //compute these once instead of once for each color
      double specFactor = Math.pow(reflection.dot(v), alpha);
      if (specFactor < 0)
      {
      	specFactor = 0;
      }
            
      double att = 1/(a + b*d + c*d*d);
      
      //Debugging 
      /*System.out.println("color.getRed():    "   + color.getRed());
      System.out.println("light.getRed():    "   + light.getRed());
      System.out.println("ambient.getRed():  " + ambient.getRed());
      System.out.println("attenuation:		 " + att);
      System.out.println("ldotn				 " + ldotn);
      System.out.println("distance d:		 " + d);
      System.out.println("incidence:		 " + incidence);
      System.out.println("reflection:		 " + reflection);
      */
            
      double red = att*(color.getRed()*ldotn*light.getRed() + specular.getRed()*specFactor*light.getRed()) +
                   ambient.getRed();
                   
      double blue = att*(color.getBlue()*ldotn*light.getBlue() +
                   specular.getBlue()*specFactor*light.getBlue()) +
                   ambient.getBlue();
                   
      double green = att*(color.getGreen()*ldotn*light.getGreen() +
                   specular.getGreen()*specFactor*light.getGreen()) +
                   ambient.getGreen();
                   
	  //System.out.println("red:		 " + red);
                   
      return new Color(red, green, blue);
   }

   public void setX(double x)
   {
      vertex.setElement(1,1,x);
   }

   public double getX()
   {
      return vertex.getElement(1,1);
   }

   public void setY(double y)
   {
      vertex.setElement(2,1,y);
   }

   public double getY()
   {
      return vertex.getElement(2,1);
   }

   public void setZ(double z)
   {
      vertex.setElement(3,1,z);
   }

   public double getZ()
   {
      return vertex.getElement(3,1);
   }

   //for preserving a copy of world coords
   public void setWorld(Vertex w)
   {
      world = new Matrix(4,1);  //4D column matrix
      world.setElement(1,1,w.getX());
      world.setElement(2,1,w.getY());
      world.setElement(3,1,w.getZ());
   }
	
	public Vertex getWorld()
	{
     return new Vertex(world.getElement(1,1), world.getElement(2,1), world.getElement(3,1));
   } 
	
   public void setH(double h)
   {
      vertex.setElement(4,1,h);
   }

   public double getH()
   {
      return vertex.getElement(4,1);
   }

   public double getRed() 
   {
      return color.getRed();
   }

   public double getGreen() 
   {
      return color.getGreen();
   }

   public double getBlue() 
   {
      return color.getBlue();
   }
   
   public void setColor(Color colors)
   {
      color = colors;
   }
   
   public void setRed(double red)
   {
      color.setRed(red);
   }

   public void setGreen(double green)
   {
      color.setGreen(green);
   }

   public void setBlue(double blue)
   {
      color.setBlue(blue);
   }

   public void homogenize()
   {
      this.setX( this.getX()/this.getH() );
      this.setY( this.getY()/this.getH() );
      this.setZ( this.getZ()/this.getH() );
      this.setH( 1.0 );    
   }

   public Vertex multiply(Matrix matrix)
   {
      Matrix mult = matrix.multiply(vertex);
      return new Vertex(mult.getElement(1,1), mult.getElement(2,1), mult.getElement(3,1), mult.getElement(4,1));
   }

   public Vector subtract(Vertex vertex)
   {
      Vector subtract = new Vector(this.getX() - vertex.getX(), 
                                   this.getY() - vertex.getY(),
                                   this.getZ() - vertex.getZ());
      return subtract;
   }

   public Vertex add(Vector vector)
   {
      Vertex added = new Vertex(this.getX() + vector.getX(), 
                                this.getY() + vector.getY(), 
                                this.getZ() + vector.getZ());
      return added;
   }

   public String toString()
   {
      DecimalFormat fmt=new DecimalFormat("0.00");
      String temp;

      temp="( " + fmt.format(getX()) + " , " + fmt.format(getY()) + " , " + fmt.format(getZ()) + " , " + fmt.format(getH()) + " )";
      return temp;
   }

}


/** 
 *  A face is defined by vertices specified in correct order to determine edges and outward normals
 *  A face is assumed to be a flat polygon
 */
import java.util.ArrayList;
import java.util.Iterator;
import java.lang.Math;
import java.awt.*;

public class Face
{
   private ArrayList vertices;
   private int size;
   private Vector normal;
   private Color material;
   private Color ambient;

   public Face()
   {
      vertices = new ArrayList();
      size=0;
      material = new Color(1.0,0,0);
   }

   public void setMaterial(Color color)
   {
      material = color;
   }
   
   public Color getMaterial()
   {
      return material;
   }
   
   public void setAmbient(Color color)
   {
      ambient = color;
   }
   
   public Color getAmbient()
   {
      return ambient;
   }

   public int size()
   {
      return size;
   }

   public void addVertex(Vertex vertex)
   {
      vertex.setColor(material);
      
      vertices.add(vertex);
      size++;
      
      if (size == 3)
      {
         Vector t = ((Vertex)vertices.get(2)).subtract((Vertex)vertices.get(1));
         Vector s = ((Vertex)vertices.get(0)).subtract((Vertex)vertices.get(1));
         normal = t.cross(s);
      }

   }

	public void updateNormal()
	{
      Vector t = ((Vertex)vertices.get(2)).subtract((Vertex)vertices.get(1));
      Vector s = ((Vertex)vertices.get(0)).subtract((Vertex)vertices.get(1));
      normal = t.cross(s);
      normal.normalize();
   }
	
   public void setNormal(Vector norm)
   {
      normal = norm;
   }
   
   public Vector getNormal()
   {
      return normal;
   }
	
   public Vertex getVertex(int index)
   {
      return (Vertex) vertices.get(index-1);
   }

   public void render(Graphics g, Matrix zbuff, Vertex eyept, Vertex atpt, Light light)
   {

      renderFaces(g, zbuff, eyept, atpt, light);
      
   }

   public String toString()
   {

      String temp="";
      Iterator iter=vertices.iterator();

      while(iter.hasNext())
      {
         temp=temp+iter.next().toString() + "  ";
      }

      return temp;

   }

   private void renderFaces(Graphics g, Matrix zbuff, Vertex eyept, Vertex atpt, Light light)
   {   
   	   Vector v = eyept.subtract(atpt);
   	   double c = normal.dot(v);
   	   
   	   //only render forward facing faces
   	   if (c >= 0)
   	   {
  
        
       //FIRST tesselate the face into new faces with 3 vertex's each
       //number of triangle faces will be (size - 2) with this simple algorithm
       //only handles convex cases
       int numTriangles = size - 2;
       ArrayList triangles = new ArrayList();
       
       //Debugging
       //System.out.println("1 Rendering Face of Color: " + this.material );
       
       for(int i = 1; i <= numTriangles; i++)
       {
          Face temp = new Face();
          temp.setMaterial(this.material);
            
          temp.addVertex( getVertex(1) );
          temp.addVertex( getVertex(i+1) );
          temp.addVertex( getVertex(i+2) );
          
          triangles.add(temp);
       }
         
       //SECOND iterate over triangles, rendering using Barycentric Coordinates
       for(int j = 1; j <= numTriangles; j++)
       {
         Face curr = (Face)triangles.get(j-1);
         
         //determine xmin, xmax, ymin, ymax
         double xmin, xmax, ymin, ymax;
         
         xmin = Math.min( curr.getVertex(2).getX(), curr.getVertex(1).getX() );
         xmin = Math.min( curr.getVertex(3).getX(), xmin );
         
         ymin = Math.min( curr.getVertex(2).getY(), curr.getVertex(1).getY() );
         ymin = Math.min( curr.getVertex(3).getY(), ymin );
         
         xmax = Math.max( curr.getVertex(2).getX(), curr.getVertex(1).getX() );
         xmax = Math.max( curr.getVertex(3).getX(), xmax );
         
         ymax = Math.max( curr.getVertex(2).getY(), curr.getVertex(1).getY() );
         ymax = Math.max( curr.getVertex(3).getY(), ymax );
         
         //determine vectors t and s
         Vector t = (curr.getVertex(2)).subtract(curr.getVertex(1));
         Vector s = (curr.getVertex(3)).subtract(curr.getVertex(1));
         
         /* Debugging
         System.out.println("P:	       " + curr.getVertex(1) );
         System.out.println("Q:	       " + curr.getVertex(2) );
         System.out.println("R:	       " + curr.getVertex(3) );
         System.out.println("t (Q-P):  " + t );
         System.out.println("s (R-P):  " + s );
         */
         
         Color vcolor1 = curr.getVertex(1).calcColor(light, eyept, ambient, normal);
         Color vcolor2 = curr.getVertex(2).calcColor(light, eyept, ambient, normal);
         Color vcolor3 = curr.getVertex(3).calcColor(light, eyept, ambient, normal);
         
         //determine tc and sc for color interpolation
         //make color Vertexes where x corresponds to Red, y to green, and z to blue
         Vertex color1 = new Vertex( vcolor1.getRed(), 
         							 vcolor1.getGreen(),
         							 vcolor1.getBlue() );
         Vertex color2 = new Vertex( vcolor2.getRed(), 
         							 vcolor2.getGreen(),
         							 vcolor2.getBlue() );
         Vertex color3 = new Vertex( vcolor3.getRed(), 
         						     vcolor3.getGreen(),
         						     vcolor3.getBlue() );

         Vector tc = color2.subtract(color1);
         Vector sc = color3.subtract(color1);
         
         //loop over xmin -> xmax
         for(int x = (int)xmin; x <= xmax; x++)
         {
            //loop over ymin -> ymax
            for(int y = (int)ymin; y <= ymax; y++)
            {
               double px = (double)x - curr.getVertex(1).getX();
               double py = (double)y - curr.getVertex(1).getY();
               double alpha = (px*t.getY() - py*t.getX()) / (s.getX()*t.getY() - s.getY()*t.getX()) ; 
               double beta  = (py*s.getX() - px*s.getY()) / (s.getX()*t.getY() - s.getY()*t.getX()) ;
               
               /* Debugging
               System.out.println("x:	       " + x);
               System.out.println("y:	       " + y);
               System.out.println("t:	       " + t);
               System.out.println("s:          " + s);
               System.out.println("Alpha:      " + alpha);
               System.out.println("Beta:       " + beta);
               */
               
               if(alpha >= 0 && alpha <= 1 && beta >= 0 && beta <= 1 && (alpha + beta) <= 1)
               {  
                  double z = alpha*s.getZ() + beta*t.getZ() + curr.getVertex(1).getZ();
                  
                  try{
                     if (z >= -1 && z<= 1 && zbuff.getElement(x, y) < z)
                     {
                        zbuff.setElement(x, y, z);
                        //INTERPOLATE color
                        double red   = alpha*sc.getX() + beta*tc.getX() + color1.getX();
                        double green = alpha*sc.getY() + beta*tc.getY() + color1.getY();
                        double blue  = alpha*sc.getZ() + beta*tc.getZ() + color1.getZ();
                        Color currColor =  new Color(red, green, blue);
                                              
                        g.setColor(currColor.getColor());                
                        g.fillRect(x, y ,1, 1);
                     }
                  }
                  catch (MatrixException e)
                  {
                  }
               }
            }
         }
       
    	 }
       }
   }
   
   private void renderWireFrame(Graphics g)
   {
       //System.out.println("rendering in Face, vertices=" + this);
       g.setColor(java.awt.Color.cyan);
      
       for(int i = 0; i < size; i++)
       {
          Vertex A = (Vertex)vertices.get(i);
          Vertex B = (Vertex)vertices.get((i+1)%size);
          
          //System.out.println("\nI got here. Vertex A = " + A.toString());
          
          double m = (B.getY() - A.getY()) / (B.getX() - A.getX());
      
          if (Math.abs(m) <= 1)
          {
          	
          	if(A.getX() > B.getX())
          	{
          		Vertex temp = A;
          		A = B;
          		B = temp;
          	}
          		
          	for(int j = 0; j <= B.getX() - A.getX(); j++)
            {
               g.fillRect((int)(A.getX() + j), (int)((m * j) + A.getY()),1, 1);
               //System.out.println("\nI filled pixel (" + (A.getX() + j) + ", " + ((m * j) + A.getY()) + ")");
            }
         }
         else
         {
            m = 1 / m;  //invert slope to go from Ay to By
            if ((B.getX() - A.getX()) == 0)
            {
               m=0;
            }
            
            if(A.getY() > B.getY())
          	{
          		Vertex temp = A;
          		A = B;
          		B = temp;
          	}
          		
          	for(int j = 0; j <= B.getY() - A.getY(); j++)
            {
               g.fillRect((int)((m * j) + A.getX()), (int)(A.getY() + j),1,1);
               //System.out.println("\nI filled pixel (" + ((m * j) + A.getX()) + ", " + (A.getY() + j) + ")");
            }
         }
       }  
   }
}


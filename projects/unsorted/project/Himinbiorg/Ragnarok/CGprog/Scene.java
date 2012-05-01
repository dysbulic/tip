/** 
 *  An object is a closed hollow surface made up of flat polygonal faces
 *  Each face is defined by vertices specified in correct order to determine edges and outward normals
 *  Each face is assumed to be a flat polygon
 *  BasicObjects are in their own local coordinate system
 */
import java.util.ArrayList;
import java.util.Iterator;
import java.awt.*;

public class Scene
{
   private Matrix transform;
   private ArrayList scene;
   private int size;
   private Light light;
   private Color ambient;
   private Vertex eyept;
   private Vertex atpt;
   
   public Scene()
   {
      scene=new ArrayList();
      size=0;
      transform=Matrix.identity(4);
      ambient = new Color(0.3, 0.3, 0.3);
   }

   public Scene(Matrix transform)
   {
      scene=new ArrayList();  //array list of instance objects
      size=0;
      this.transform=transform;
      ambient = new Color(0.3, 0.3, 0.3);
   }

   public void reset()
   {
      transform=Matrix.identity(4);
      scene=new ArrayList();
      size=0;
   }

   public int size()
   {
      return size;
   }

   public void addLight(Light lite)
   {
      light = lite;
      ambient = new Color(.15, 0, .15);
   }
   
   public void setEye(Vertex eye)
   {
	  eyept = eye;
   }
	
   public void setAt(Vertex at)
   {
	  atpt = at;
   }
	 
   public void addObject(InstanceObject obj)
   {
   	  // Debugging
   	  //System.out.println("Adding Object to scene of Color: " + obj.getMaterial() );
   	
      scene.add(obj);
      size++;
   }

   public InstanceObject getObject(int index)
   {
      return (InstanceObject) scene.get(index-1);
   }

   public void buildTransform(Matrix matrix)
   {
      //multiple calls to this method will achieve the proper concatenation (later matrices on left)
      transform = matrix.multiply(transform);      
   }

   public BasicObject getTransformedObject(int index)
   {
      //apply the Scene transform to all of the InstanceObjects
      //create the transformed object and return it
      BasicObject newObject=new BasicObject();
      BasicObject transformed = this.getObject(index).getTransformedObject();
      
      //loop over the faces in the object
      for(int i = 1; i <= transformed.size(); i++)
      {
         Face newFace = new Face();
         Face current = transformed.getFace(i);
         
         //above is transform from Local to World coords, determine new normals here
         //determine new normal for Face current
         current.updateNormal();
         
         //loop over the vertexes in the current face, transform them, and add them to the newFace
         for(int j = 1; j <= current.size(); j++)
         {
            Vertex newVertex = current.getVertex(j).multiply(this.transform);
            newVertex.homogenize();
            newVertex.setWorld(current.getVertex(j));
            newFace.addVertex(newVertex);  
         }
         newFace.setNormal(current.getNormal());
         newObject.addFace(newFace);
      }
      
      newObject.setMaterial(this.getObject(index).getMaterial());
      newObject.setAmbient(ambient);
      //Debugging
       //System.out.println("Rendering newObject of Color: " + newObject.getMaterial() );
      
      return newObject;     
   }

   public void render(Graphics g,int xPixels,int yPixels)
   {
      //make a z-buffer and initialize all elements to -1
      Matrix zbuff = new Matrix(xPixels, yPixels);      
      for(int x = 1; x <= xPixels; x++)
      {
         for(int y = 1; y <= yPixels; y++)
         {
            zbuff.setElement(x, y, -1.0);
         }
      }
      
      //loop over all the Instance Objects in the Scene, transform them, and render them
      for(int i = 1; i <= size; i++)
      {
         this.getTransformedObject(i).render(g, zbuff, eyept, atpt, light);
      }
   }

   public String toString()
   {

      String temp="";
      Iterator iter=scene.iterator();

      while(iter.hasNext())
      {
         temp=temp+iter.next().toString() + "  ";
      } 

      return temp;

   }

}


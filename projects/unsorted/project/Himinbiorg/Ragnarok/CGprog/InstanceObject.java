import java.awt.*;
import java.util.ArrayList;

public class InstanceObject
{
   private BasicObject object;
   private Matrix transform;
   private Color material;
   private Color ambient;

   public InstanceObject(BasicObject object, Matrix transform)
   {
      this.object=object;
      this.transform=transform;
      material = new Color(0.5,0.5,1);
      ambient = new Color(0.5, 0.5, 0.5);
   }

   public InstanceObject(BasicObject object)
   {
      this.object=object;
      this.transform=Matrix.identity(4);
   }

   public int size()
   {
      return object.size();
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

   public Matrix getTransform()
   {
      return transform;
   }

   public void buildTransform(Matrix matrix)
   {
      //multiple calls to this method will achieve the proper concatenation (later matrices are on the left)
      transform = matrix.multiply(transform);      
   }

   public BasicObject getTransformedObject()
   {
      //create the transformed object and return it
      BasicObject newObject=new BasicObject();
      
      //loop over the faces in the object
      for(int i = 1; i <= this.object.size(); i++)
      {
         Face newFace = new Face();
         
         //loop over the vertexes in the current face, transform them, and add them to the newFace
         for(int j = 1; j <= this.object.getFace(i).size(); j++)
         {
            newFace.addVertex(this.object.getFace(i).getVertex(j).multiply(this.transform));
         }
         
         newObject.addFace(newFace);
      }
      
      //Debugging
      //System.out.println("Rendering InstanceObj of Color: " + this.material );
      
      newObject.setMaterial(material);
      newObject.setAmbient(ambient);
      return newObject;      
   }

   public String toString()
   {
      String temp="";
      temp=transform.toString();
      return temp;
   }

}


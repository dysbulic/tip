/** 
 *  An object is a closed hollow surface made up of flat polygonal faces
 *  Each face is defined by vertices specified in correct order to determine edges and outward normals
 *  Each face is assumed to be a flat polygon
 *  BasicObjects are in their own local coordinate system
 */
import java.util.ArrayList;
import java.util.Iterator;
import java.awt.*;

public class BasicObject
{
   private ArrayList faces;
   private int size;
   private Color material;
   private Color ambient;
   
   public BasicObject()
   {
      faces=new ArrayList();
      size=0;
      material = new Color(0,1,0);
      ambient = new Color (.3,.3,.3);
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

   public void addFace(Face face)
   {
      faces.add(face);
      size++;
   }

   public Face getFace(int index)
   {
      return (Face) faces.get(index-1);
   }

   public void render(Graphics g, Matrix zbuff, Vertex eyept, Vertex atpt, Light light)
   {
      //loop over faces, rendering them
      for(int i = 0; i < size; i++)
      {
         Face tempFace = (Face)faces.get(i);
         tempFace.setMaterial(material);
         tempFace.setAmbient(ambient);
         
         //Debugging
         //System.out.println("Line 72 Basic Object, ambient: " + ambient);
         //System.out.println("Rendering tempFace of Color: " + this.material );
         
         tempFace.render(g, zbuff, eyept, atpt, light);
      }
   }

   public String toString()
   {

      String temp="";
      Iterator iter=faces.iterator();

      while(iter.hasNext())
      {
         temp=temp+iter.next().toString() + "  ";
      }

      return temp;

   }

}


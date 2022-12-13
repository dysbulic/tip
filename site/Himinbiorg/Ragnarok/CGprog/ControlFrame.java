import java.awt.*;
import javax.swing.*;
import java.awt.event.*;
import java.util.StringTokenizer;
import java.util.ArrayList;
import java.util.Iterator;
import java.io.IOException;

public class ControlFrame extends JFrame implements ActionListener
{
   private Container cont;

   private JTextField file;
   private JLabel lblfile;

   private JTextField mat;
   private JLabel lblmat;
   private JTextField scale;
   private JLabel lblscale;
   private JTextField rot;
   private JLabel lblrot;
   private JTextField trans;
   private JLabel lbltrans;
   private JTextField eye;
   private JLabel lbleye;
   private JTextField at;
   private JLabel lblat;
   private JTextField fov;
   private JLabel lblfov;
   private JTextField zmaxmin;
   private JLabel lblzmaxmin;
   private JTextField light;
   private JLabel lbllight;

   private JButton apply;

   private Scene scene;
   private JFrame image;
   private JPanel pnl;

   public ControlFrame(Scene scene, JFrame image, JPanel pnl)
   {
      //need a ref to the scene, the jframe that will be rendering the image, and the panel that will contain the image
      super("Control Panel");
      setSize(400,250);

      cont=getContentPane();
      EasyGridBag grid=new EasyGridBag(11,3);
      cont.setLayout(grid);

      apply=new JButton("Apply");

      file=new JTextField(15);
      file.setText("sphere.obj");

      lblfile=new JLabel("File Name");

      mat=new JTextField(5);     //Material
      mat.setText("0,0,1");
      scale=new JTextField(5);   //Scale
      scale.setText("1,1,1");
      rot=new JTextField(5);     //Rotation
      rot.setText("0,0,0");
      trans=new JTextField(5);   //Translation
      trans.setText("0,0,0");
      eye=new JTextField(5);     //Eye Point
      eye.setText("0,0,4");
      at=new JTextField(5);      //At Point
      at.setText("0,0,0");
      fov=new JTextField(5);     //Field of View
      fov.setText("45");
      zmaxmin=new JTextField(5); //Z max and min
      zmaxmin.setText("2,5");
      light=new JTextField(5);   //Light
      light.setText("3,3,3");
      
      lblmat=new JLabel("Material (r,g,b)");
      lblscale=new JLabel("Scale (x,y,z)");
      lblrot=new JLabel("Rotation (x,y,z degrees)");
      lbltrans=new JLabel("Translation (x,y,z)");
      lbleye = new JLabel("Point to look from (x,y,z coords)");
      lblat = new JLabel("Point to look at (x,y,z coords)");
      lblfov = new JLabel("Field of view (degrees 1-180)");
      lblzmaxmin = new JLabel("Z max and min (-zmax, -zmin)");
      lbllight=new JLabel("Light (x,y,z)");

      grid.fillCell(1,1,lblfile,cont);
      grid.fillCell(1,2,file,cont);

      grid.fillCell(2,1,lblscale,cont);
      grid.fillCell(2,2,scale,cont);
      grid.fillCell(3,1,lblrot,cont);
      grid.fillCell(3,2,rot,cont);
      grid.fillCell(4,1,lbltrans,cont);
      grid.fillCell(4,2,trans,cont);
      grid.fillCell(5,1,lbleye,cont);
      grid.fillCell(5,2,eye,cont);
      grid.fillCell(6,1,lblat,cont);
      grid.fillCell(6,2,at,cont);
      grid.fillCell(7,1,lblfov,cont);
      grid.fillCell(7,2,fov,cont);
      grid.fillCell(8,1,lblzmaxmin,cont);
      grid.fillCell(8,2,zmaxmin,cont);
      grid.fillCell(9,1,lblmat,cont);
      grid.fillCell(9,2,mat,cont);
      grid.fillCell(10,1,lbllight,cont);
      grid.fillCell(10,2,light,cont);

      grid.fillCell(11,3,apply,cont);

      cont.setBackground(java.awt.Color.white);
      apply.setBackground(java.awt.Color.white);
      apply.addActionListener(this);

      file.addActionListener(this);
      scale.addActionListener(this);
      rot.addActionListener(this);
      trans.addActionListener(this);
      eye.addActionListener(this);
      at.addActionListener(this);
      fov.addActionListener(this);
      zmaxmin.addActionListener(this);
      mat.addActionListener(this);
      light.addActionListener(this);
      
      this.scene=scene;
      this.image=image;
      this.pnl=pnl;
      
   }

   public void actionPerformed(ActionEvent evt)
   {
      double[] coord;
      try
      {
         scene.reset();  //compute image from the beginning

         BasicObject obj=readObject(file.getText());

         //the dimensions of the panel on which the drawing will occur
         int width=pnl.getSize().width;  
         int height=pnl.getSize().height;
         
         coord=getValues(fov.getText());
         double angle = coord[0];  //field of view (try changing this to verify your solution)
         angle=angle/180*3.14;  //get the angle in radians

         double xmax = Math.tan(angle/2);
         
         //the height of the camera is determined by the aspect ratio of the panel upon which the image will be rendered and xmax
         double ymax = xmax * ((double)height / (double)width);
         
         //build the LookAt transformation
         coord=getValues(eye.getText());
         Vertex eye = new Vertex(coord[0], coord[1], coord[2]);
         
         coord=getValues(at.getText());
         Vertex at = new Vertex(coord[0], coord[1], coord[2]);
         
         Matrix lookAt=AffineTransforms.lookAt(eye, at);
         
         //assign scene transformations
         //we will assume that the range for x and y values is -1 to 1
         //therefore, for assign 1 only, scale x and y to achieve this                      

         //get zmax and zmin from user
         coord=getValues(zmaxmin.getText());
         double zmax = -coord[0];
         double zmin = -coord[1];
         Matrix normal=AffineTransforms.orthoNormal(zmax, zmin);
         
         Matrix aspect=AffineTransforms.scale((1.0/xmax), (1.0/ymax), 1.0);  
         //the windowing transform is based on the width and height of the rendering panel
         Matrix window=AffineTransforms.window(width, height);
                 
         //put the transforms into the scene
         scene.buildTransform(lookAt); 
         scene.buildTransform(aspect);
         scene.buildTransform(normal);
         scene.buildTransform(window);
                 
         InstanceObject transformObject=new InstanceObject(obj);   
         
         //set the color
         coord=getValues(mat.getText());
         transformObject.setMaterial(
              new Color(coord[0], coord[1], coord[2] ) );
         
         //transform the object using TRS
         //notice that scaling is first, followed by rotations, and finally translations
         //scales
         coord=getValues(scale.getText());
         transformObject.buildTransform(AffineTransforms.scale(coord[0],coord[1],coord[2]));

         //complete the transformations for the object
         //rotations
         coord=getValues(rot.getText());
         transformObject.buildTransform(AffineTransforms.rotateY(coord[1]));
         transformObject.buildTransform(AffineTransforms.rotateX(coord[0]));
         transformObject.buildTransform(AffineTransforms.rotateZ(coord[2]));
         
         //translations
         coord=getValues(trans.getText());
         transformObject.buildTransform(AffineTransforms.translate(coord[0],coord[1],coord[2]));

         
         //System.out.println("\n\nAdding this InstanceObject to scene. Vertices = " + transformObject.getTransformedObject().toString());
         //add the light to the scene
         coord=getValues(light.getText());
         Light light = new Light(coord[0],coord[1],coord[2]);
         scene.addLight(light);
         scene.setEye(eye);
         scene.setAt(at);
         
         scene.addObject(transformObject);
         //note that we could have additional instance objects in our scene

         image.repaint();  //render the image

      }
      catch(NumberFormatException e)
      {
         SimpleDialogs.normalOutput("Error in scene input","Error");
      }
      catch(IndexOutOfBoundsException e)
      {
      	 SimpleDialogs.normalOutput("Error in supplied transformation.","Error");
      }


   }

   public static BasicObject readObject(String file)
   {
      FileIO io;
      ArrayList vertices=new ArrayList();
      BasicObject obj=new BasicObject();

      try
      {

         io=new FileIO(file," ");  //specify how to tokenize each line

         while(!io.EOF())
         {
            Iterator iter=io.getTokens();

            while(iter.hasNext())
            {
               String temp=iter.next().toString();
               if (temp.equals("v"))
               {
                  //process a vertex

                  double x=Double.parseDouble(iter.next().toString());
                  double y=Double.parseDouble(iter.next().toString());
                  double z=Double.parseDouble(iter.next().toString());
                  Vertex vertex=new Vertex(x,y,z);
                  vertices.add(vertex);
 
               }
               else if (temp.equals("vn"))
               {
                  //process a vertex normal
                  //we probably won't use these--  we will calculate the normals manually when we need them

                  double x=Double.parseDouble(iter.next().toString());
                  double y=Double.parseDouble(iter.next().toString());
                  double z=Double.parseDouble(iter.next().toString());
               }
               else if (temp.equals("f"))
               {

                  //process a face
                  Face face=new Face();
                  ArrayList vertexList=new ArrayList();

                  int index=getVertexIndex(iter);
                  int counter=0;
                  while (index!=-1)
                  {
                     Vertex vertex=(Vertex) vertices.get(index-1);
                     vertexList.add(counter,vertex);
 
                     if (vertexList.size()>2)
                     {
                        Vertex vertex1=(Vertex) vertexList.get(counter-2);
                        Vertex vertex2=(Vertex) vertexList.get(counter-1);
                        Vertex vertex3=(Vertex) vertexList.get(counter);

                        boolean collinear=colin(vertex1,vertex2,vertex3);

                        if (collinear)
                        {
                           vertexList.remove(counter-1);  //remove the middle collinear vertex
                           counter--;
                        }
      
                     }
                     
                     index=getVertexIndex(iter);
                     counter++;  //the next available index
                  }

                  //the actual list with noncollinearities removed
                  //we may still have concave polygons from Wings3D so be careful
                  for (int x=0;x<vertexList.size();x++)
                  {
                     Vertex vertex=(Vertex) vertexList.get(x);

                     face.addVertex(vertex);
                  }
                  obj.addFace(face);  

               }
         
            }
         }
         io.close();
      }
      catch(IOException e)
      {
      	 SimpleDialogs.normalOutput(e.getMessage(),"Error");
         //System.out.println(e.getMessage());
         //System.exit(0);
      }
	
      //System.out.println("\n\nGetting this BasicObject from file. Vertices = " + obj.toString());
      return obj;
   }

   private static boolean colin(Vertex one, Vertex two, Vertex three)
   {
      double x1=one.getX();
      double x2=two.getX();
      double x3=three.getX();

      double y1=one.getY();
      double y2=two.getY();
      double y3=three.getY();

      double z1=one.getZ();
      double z2=two.getZ();
      double z3=three.getZ();

      double alpha=(x2-x1)/(x1-x3);

      double testy=(1-alpha)*y1 + alpha*y3; 
      double testz=(1-alpha)*z1 + alpha*z3;  

      if ( (Math.abs(testy-y2) < .0001) && (Math.abs(testz-z2) < .0001) )
      {
          return true;
      }
      else
      {
          return false;
      }
   }

   private static int getVertexIndex(Iterator iter)
   {
      String text;
      if (iter.hasNext())
      {
          text=(String) iter.next();
      }
      else
      {
          return -1;
      }

      String temp="";
      int x=0;
      while(x<text.length() && text.charAt(x)!='/')
      {
         temp=temp+text.charAt(x);
         x++;
      }

      int index=Integer.parseInt(temp);
      return index;
       
   }

   private static double[] getValues(String text)
   {

      double[] values=new double[3];

      StringTokenizer tok=new StringTokenizer(text,",");
      int count=0;
      while(tok.hasMoreTokens())
      {
         values[count]=Double.parseDouble(tok.nextToken());
         count++;
      }
      
      return values;

   }

}
public class Light
{

   private Color color;		//color of the light
   private Vertex lightpt;	//point where the light is in the scene
   private double a, b, c;      	//attenuation constants

   public Light(double x, double y, double z)
   {
      lightpt = new Vertex(x, y, z);
      color = new Color(1.0, 1.0, 1.0);
      a = 0;
      b = .2;
      c = 0;
   }

   public Vertex getLightPoint()
   {
      return lightpt;
   }
   
   public double getA()
   {
      return a;
   }
   
   public double getB()
   {
      return b;
   }
   
   public double getC()
   {
      return c;
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
}


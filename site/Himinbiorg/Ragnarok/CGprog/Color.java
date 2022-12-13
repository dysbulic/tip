public class Color
{

   private double red;
   private double blue;
   private double green;
   private double alpha;    //alpha measures opacity (0.0 transparent, 1.0 opaque)

   public Color(double r, double g, double b, double a)
   {
      clamp(r,g,b,a);
   }

   public Color(double r, double g, double b)
   {
      clamp(r,g,b,1.0);  //assumed to be opaque
   }

   private static double clampColor(double c)
   { 
      if (c>1.0)
      {
         c=1.0;
      }
      else if (c<0.0)
      {
         c=0.0;
      }

      return c;
   }

   private void clamp(double r, double g, double b, double a)
   {
      red=clampColor(r);
      green=clampColor(g);
      blue=clampColor(b);
      alpha=clampColor(a);
   }

   public double getRed()
   {
      return red;
   }

   public double getGreen()
   {
      return green;
   }

   public double getBlue()
   {
      return blue;
   }

   public double getAlpha()
   {
      return alpha;
   }

   public void setRed(double r)
   {
      red=clampColor(r);
   }

   public void setGreen(double g)
   {
      green=clampColor(g);
   }

   public void setBlue(double b)
   {
      blue=clampColor(b);
   }

   public void setAlpha(double a)
   {
      alpha=clampColor(a);
   }

   public java.awt.Color getColor()
   {
      return new java.awt.Color( (float) red, (float) green, (float) blue, (float) alpha );
   }
   
   public String toString()
   {
      return ("R: " + getRed() + ", G: " + getGreen() + ", B: " + getBlue());
   }

}
import java.text.DecimalFormat;

public class Vector
{
   private Matrix vector;

   public Vector(double x, double y, double z)
   {
      vector = new Matrix(4,1);  //4D column matrix
      vector.setElement(1,1,x);
      vector.setElement(2,1,y);
      vector.setElement(3,1,z);
      vector.setElement(4,1,0.0);
   }

   public void setX(double x)
   {
      vector.setElement(1,1,x);
   }

   public double getX()
   {
      return vector.getElement(1,1);
   }

   public void setY(double y)
   {
      vector.setElement(2,1,y);
   }

   public double getY()
   {
      return vector.getElement(2,1);
   }

   public void setZ(double z)
   {
      vector.setElement(3,1,z);
   }

   public double getZ()
   {
      return vector.getElement(3,1);
   }
   
   public Vector cross(Vector cross)
   {
      Vector crossProd = new Vector((this.getY() * cross.getZ()) - (this.getZ() * cross.getY()),
                                    (this.getZ() * cross.getX()) - (this.getX() * cross.getZ()),
                                    (this.getX() * cross.getY()) - (this.getY() * cross.getX()));

      return crossProd;      
   }

   public double dot(Vector dot)
   {
      return (this.getX() * dot.getX()) + (this.getY() * dot.getY()) + (this.getZ() * dot.getZ());
   }

   public void normalize()
   {
      double normFactor = Math.sqrt(Math.pow(this.getX(), 2) + 
                                    Math.pow(this.getY(), 2) +
                                    Math.pow(this.getZ(), 2));
                                    
      this.setX(this.getX() / normFactor);
      this.setY(this.getY() / normFactor);
      this.setZ(this.getZ() / normFactor);
   }
   
   public double normAndGetMag()
   {
      double magnitude  = Math.sqrt(Math.pow(this.getX(), 2) + 
                                    Math.pow(this.getY(), 2) +
                                    Math.pow(this.getZ(), 2));
                                    
      this.setX(this.getX() / magnitude);
      this.setY(this.getY() / magnitude);
      this.setZ(this.getZ() / magnitude);
      
      return magnitude;
   }

   public Vector multiply(Matrix matrix)
   {
      double x= matrix.getElement(1, 1) * this.getX() +
                matrix.getElement(1, 2) * this.getY() +
                matrix.getElement(1, 3) * this.getZ();
                
      double y= matrix.getElement(2, 1) * this.getX() +
                matrix.getElement(2, 2) * this.getY() +
                matrix.getElement(2, 3) * this.getZ();
                
      double z= matrix.getElement(3, 1) * this.getX() +
                matrix.getElement(3, 2) * this.getY() +
                matrix.getElement(3, 3) * this.getZ();

      return new Vector(x, y, z);
   }
   
   public Vector scaleBy(double scalar)
   {
      double x = this.getX() * scalar;
      double y = this.getY() * scalar;
      double z = this.getZ() * scalar;
      
      return new Vector(x, y, z);
   }

   public Vector add(Vector vector)
   {
      Vector added = new Vector(this.getX() + vector.getX(), 
                                this.getY() + vector.getY(), 
                                this.getZ() + vector.getZ());
                                
      return added;
   }

   public Vector subtract(Vector vector)
   {
      Vector subtracted = new Vector(this.getX() - vector.getX(), 
                          			 this.getY() - vector.getY(), 
                          			 this.getZ() - vector.getZ());
                                
      return subtracted;
   }

   public String toString()
   {
      DecimalFormat fmt=new DecimalFormat("0.00");
      String temp;

      temp="( " + fmt.format(getX()) + " , " + fmt.format(getY()) + " , " + fmt.format(getZ()) + " , 0 )";
      return temp;
   }

}


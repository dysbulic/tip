import java.text.DecimalFormat;

/**
 *  This class extends the functionality of the BasicMatrix by implementing the common
 *  Matrix operations specified in MatrixOperationsInterface.  Direct access to the 2-D
 *  array is not possible (declared private in BasicMatrix), 
 *  but use of the public BasicMatrix methods is all that is required to perform
 *  the operations.
 */
public class Matrix extends BasicMatrix implements MatrixOperationsInterface
{

   /** 
    * Constructor to create the Matrix with the given size. <br> 
    * Preconditions: Valid number of rows and columns specified. <br>
    * Postconditions: The Matrix object is created and is ready for use. <br>
    * Throws: MatrixException if rows or columns < 1. <br>
    * Note: Ragged Matrices are not allowed (Matrices will always be rectangular).
    */
   public Matrix(int rows, int columns)
   {
      super(rows,columns);
   }

   public Matrix scale(double scale) throws MatrixException
   {
      Matrix result=null;
      double x=0;

      result=new Matrix(getNumRows(),getNumColumns());

      //loop over all elements of resulting matrix
      for (int i=1;i<=result.getNumRows();i++)
      {
         for (int j=1;j<=result.getNumColumns();j++)
         {
            result.setElement(i,j,getElement(i,j)*scale);
         }
      }

      return result;
   }

   public Matrix multiply(Matrix yourMatrix) throws MatrixException
   {
      Matrix result=null;
      double x=0;

      if (yourMatrix==null || getNumColumns() != yourMatrix.getNumRows())
         throw new MatrixException ("Cannot multiply these two matrices.");
      else 
         result=new Matrix(getNumRows(),yourMatrix.getNumColumns());

      //loop over all elements of resulting matrix
      for (int i=1;i<=result.getNumRows();i++)
      {
         for (int j=1;j<=result.getNumColumns();j++)
         {
            x=0;
            //sum up multiplying matrices to obtain value placed in new matrix
            for (int k=1;k<=getNumColumns();k++)
            {
               x = x + getElement(i,k)*yourMatrix.getElement(k,j);
            }
            result.setElement(i,j,x);
         }
      }

      return result;
   }

   public Matrix add(Matrix yourMatrix) throws MatrixException
   {
      Matrix result=null;

      if( yourMatrix==null || getNumRows()!=yourMatrix.getNumRows() || getNumColumns()!=yourMatrix.getNumColumns())
         throw new MatrixException ("Cannot add these two matrices.");
      else 
         result=new Matrix(getNumRows(),getNumColumns());    

      for (int i=1;i<=getNumRows();i++)
      {
         for (int j=1;j<=getNumColumns();j++)
         {
            result.setElement(i,j,getElement(i,j)+yourMatrix.getElement(i,j));
         }
      }

      return result;  
   }

   public Matrix transpose()
   {
      Matrix result=new Matrix(getNumColumns(),getNumRows());

      for (int i=1;i<=getNumRows();i++)
      {
         for (int j=1;j<=getNumColumns();j++)
         {
            result.setElement(j,i,getElement(i,j));
         }
      }
    
      return result;
   }

   public double determinant()
   {
      if ( getNumRows()!=getNumColumns() ) //must be a square matrix
         throw new MatrixException("Determinant not defined.");

      double myDet=detRec(this);
      return myDet;
   }

   private static double detRec(Matrix myMatrix)
   {
      double myDet=0;
      if (myMatrix.getNumRows()==1)
         return myMatrix.getElement(1,1);
      else if (myMatrix.getNumRows()==2)  //base case
      {
         return myMatrix.getElement(1,1)*myMatrix.getElement(2,2)-myMatrix.getElement(1,2)*myMatrix.getElement(2,1);
      }

      else
      {
         Matrix cofact=new Matrix(myMatrix.getNumRows(),1);
         Matrix yourMatrix=new Matrix(myMatrix.getNumRows()-1,myMatrix.getNumColumns()-1);

         for (int j=1;j<=myMatrix.getNumColumns();j++)
         {

            for (int i=2;i<=myMatrix.getNumRows();i++)  //throw away row 1
            {
               for (int k=1;k<=myMatrix.getNumColumns();k++)
               {
                  if (k!=j)  //throw away column j
                  {
                     if (k>j)
                     {
                        yourMatrix.setElement(i-1,k-1,myMatrix.getElement(i,k));
                     }
                     else
                     {
                        yourMatrix.setElement(i-1,k,myMatrix.getElement(i,k));
                     }
                  }
               }
            }
            cofact.setElement(j,1,Math.pow(-1,1+j)*detRec(yourMatrix));
            myDet+=myMatrix.getElement(1,j)*cofact.getElement(j,1);
         }
      }

      return myDet;
   }

   public Matrix inverse()
   {
      double myDet=determinant();
      if (myDet==0)
         throw new MatrixException("Matrix cannot be inverted.");

      Matrix cofact=new Matrix(getNumRows(),getNumColumns());
      Matrix yourMatrix=new Matrix(getNumRows()-1,getNumColumns()-1);

      for (int i=1;i<=getNumRows();i++)
      {
         for (int j=1;j<=getNumColumns();j++)
         {

            for (int l=1;l<=getNumRows();l++)  //create the smaller matrix
            {
               for (int k=1;k<=getNumColumns();k++)
               {
                  if (k!=j && l!=i)  //throw away column j and row i
                  {
                     if (k>j)
                     {
                        if (l>i)
                        {
                           yourMatrix.setElement(l-1,k-1,getElement(l,k));
                        }
                        else
                        {
                           yourMatrix.setElement(l,k-1,getElement(l,k));
                        }
                     }
                     else
                     {
                        if (l>i)
                        {
                           yourMatrix.setElement(l-1,k,getElement(l,k));
                        }
                        else
                        {
                           yourMatrix.setElement(l,k,getElement(l,k));
                        }
                     }
                  }
               }
            }
            
            cofact.setElement(i,j,Math.pow(-1,i+j)*detRec(yourMatrix)/myDet);
         }
      }
      cofact=cofact.transpose();
      return cofact;
   }

   public static Matrix identity(int size)
   {
      Matrix I=new Matrix(size,size);

      for (int i=1;i<=I.getNumRows();i++)
      {
         for (int j=1;j<=I.getNumColumns();j++)
         {
            if (i==j)
            {
               I.setElement(i,j,1);
            }
            else
            {
               I.setElement(i,j,0);
            }
         }
      }

      return I;
   }

   /** Constants used in the toString() method for decently formatted output of the Matrix. <br> */
   private static final int SPACING=8;
   private static final int PLACES=4;
   private static final int LEADING=4;

   public String toString()
   {
      DecimalFormat fmt;
      if (PLACES==1)
         fmt=new DecimalFormat("0.0");
      else if (PLACES==2)
         fmt=new DecimalFormat("0.00");
      else if (PLACES==3)
         fmt=new DecimalFormat("0.000");
      else if (PLACES==4)
         fmt=new DecimalFormat("0.0000");
      fmt.setMinimumIntegerDigits(LEADING);
      fmt.setNegativePrefix("");
      fmt.setNegativeSuffix("-");

      String temp="";
      for (int i=1;i<=getNumRows();i++)
      {
         for (int j=1;j<=getNumColumns();j++)
         {
            String spacer="";
            StringBuffer formatter;
            if ( getElement(i,j)>=-0.000001 && getElement(i,j)<=0.00000 )
               setElement(i,j,0.0);
            formatter=new StringBuffer(fmt.format(getElement(i,j)));
          
            for (int k=1;k<=(SPACING-formatter.length());k++)
            {
               spacer+=" ";
            }
            for (int k=0;k<formatter.length();k++)
            {
               if (formatter.charAt(k)=='0' && formatter.charAt(k+1)!='.')
               {
                  formatter.setCharAt(k,' ');
               }
               else if (formatter.charAt(k+1)=='.')
               {
                  break;
               }
            }
            temp+= formatter + spacer;
            
         }
         temp+="\n";  //next row
      }

      return temp;
    
   }

}


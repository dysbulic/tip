/**
 *  This interface defines fundamental Matrix operations such as setting and retrieving individual elements
 *  and obtaining the Matrix dimensions.
 */

public interface BasicMatrixInterface
{

   /** 
    * Method to allow the setting of individual Matrix elements. <br> 
    * Preconditions: Valid row and column specified. <br>
    * Postconditions: The Matrix element is set to the specified double. <br>
    * Throws: MatrixException if (rows or columns < 1) or (rows or columns > the valid range--  the max size specified upon construction).
    */
   public void setElement(int row, int column, double value) throws MatrixException;

   /** 
    * Method to return the value at a specified element in the Matrix. <br> 
    * Preconditions: Valid row and column specified. <br>
    * Postconditions: The double contained at the specified location is returned. <br>
    * Throws: MatrixException if (rows or columns < 1) or (rows or columns > the valid range).
    */
   public double getElement(int row, int column) throws MatrixException;

   /** 
    * Method to return the number of rows in the Matrix. <br> 
    * Preconditions: None. <br>
    * Postconditions: The number of rows in the Matrix is returned. <br>
    * Throws: None.
    */
   public int getNumRows();

   /** 
    * Method to return the number of columns in the Matrix. <br> 
    * Preconditions: None. <br>
    * Postconditions: The number of columns in the Matrix is returned. <br>
    * Throws: None.
    */
   public int getNumColumns();

}


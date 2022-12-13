/**
 *  This interface specifies common Matrix operations.  Typically, two Matrices are needed.
 *  The active Matrix is used and a passed in Matrix.  A new Matrix is created and returned after
 *  the requested operation has been performed.  The methods could be made static with two matrices passed in.
 */

public interface MatrixOperationsInterface
{

   /** 
    * Method to multiply two matrices together. <br> 
    * Preconditions: A Matrix with dimensions compatible to <b>this</b> is passed in. <br>
    * Postconditions: A new Matrix that is the result of mulitplying <b>this</b> with the passed in Matrix is returned. The original Matrices are unaffected. <br>
    * Throws: MatrixException if the two matrices cannot be multiplied together (dimensions are not compatible). <br>
    * Note: This method could be made static if two Matrices were passed in.
    */
   public Matrix multiply(Matrix yourMatrix) throws MatrixException;

   /** 
    * Method to multiply two matrices together. <br> 
    * Preconditions: A Matrix with dimensions compatible to <b>this</b> is passed in. <br>
    * Postconditions: A new Matrix that is the result of adding <b>this</b> with the passed in Matrix is returned. The original Matrices are unaffected. <br>
    * Throws: MatrixException if the two matrices cannot be added together (dimensions are not compatible). <br>
    */
   public Matrix add(Matrix yourMatrix) throws MatrixException;

   /** 
    * Method to return the transpose of the active Matrix. <br> 
    * Preconditions: None. <br>
    * Postconditions: A new Matrix that is the result of taking the transpose of <b>this</b> is returned. The original Matrix is unaffected. <br>
    * Throws: None. <br>
    */
   public Matrix transpose();

   /** 
    * Method to return the determinant of the active Matrix. <br> 
    * Preconditions: The active Matrix must be square. <br>
    * Postconditions: The determinant of the active Matrix is returned as a double. <br>
    * Throws: MatrixException if the active Matrix is not square. <br>
    * Note: I should not mention how the determinant calculation is implemented (in our case, recursively). <br>
    */
   public double determinant();

   /** 
    * Method to return the inverse of the active Matrix. <br> 
    * Preconditions: The active Matrix must have a nonzero determinant (which means that it also must be square). <br>
    * Postconditions: The inverse of the active Matrix is returned. The original Matrix is unaffected. <br>
    * Throws: MatrixException if the active Matrix has a zero determinant or is not square. <br>
    */
   public Matrix inverse();

}


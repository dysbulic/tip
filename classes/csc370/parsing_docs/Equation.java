/**
 * This is an interface representing the implementing object is an equation.
 *
 * @author Will Holcomb
 */
public interface Equation
{
	/**
	 * Returns the solution for the equation. Currently only integer equations
	 * are supported.
	 *
	 * @return the solution for the equation
	 */
	public int solution();

	/**
	 * Returns a string representing the equation.
	 *
	 * @return string representing the equation being solved
	 */
	public String toString();
}
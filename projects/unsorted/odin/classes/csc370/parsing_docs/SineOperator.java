/**
 * This represents the operator sin in an arithmetic equation.
 * It returns the trigonometric sine.
 *
 * @author Will Holcomb
 */
public class SineOperator extends Operator
{
	/**
	 * Creates an {@link Operator} with:
	 * <ul>
	 *	<li>token: sin</li>
	 *	<li> precedence: 2</li>
	 *	<li>numberOperands: 1</li>
	 *	<li>preceededByAnOperand: false</li>
	 * </ul>
	 */
	public SineOperator()
	{
		token = new String("sin");
		precedence = 2;
		numberOperands = 1;
		preceededByAnOperand = false;
	}

	/**
	 * @param x[] the operands in degrees
	 * @return (int){@link Math#sin Math.sin}(x[0])
	 */
	public int operate(int [] x)
	{
		int solution = 0;

		if(x.length == numberOperands)
			solution = (int)Math.sin(Math.toRadians(x[0]));
		else
			solution = 0;

		return solution;
	}
}	
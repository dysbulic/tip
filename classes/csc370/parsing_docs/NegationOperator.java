/**
 * This represents the binary operator - in an arithmetic equation; as in -(3) = -3.
 *
 * @author Will Holcomb
 */
public class NegationOperator extends Operator
{
	/**
	 * Creates an {@link Operator} with:
	 * <ul>
	 *	<li>token: -</li>
	 *	<li> precedence: 1</li>
	 *	<li>numberOperands: 1</li>
	 *	<li>preceededByAnOperand: false</li>
	 * </ul>
	 */
	public NegationOperator()
	{
		token = new String("-");
		precedence = 1;
		numberOperands = 1;
		preceededByAnOperand = false;
	}

	/**
	 * @param x[] the operands
	 * @return -x[0]
	 */
	public int operate(int [] x)
	{
		int solution;

		if(x.length == numberOperands)
				solution = -x[0];
		else
		{
			System.out.println("Error in \"" + token + "\"");
			solution = 0;
		}

		return solution;
	}
}	
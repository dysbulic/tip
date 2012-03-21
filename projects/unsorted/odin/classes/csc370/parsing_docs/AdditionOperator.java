/**
 * This represents the operator + in an arithmetic equation; as in 3 + 5 = 8.
 *
 * @author Will Holcomb
 */
public class AdditionOperator extends Operator
{
	/**
	 * Creates an {@link Operator} with:
	 * <ul>
	 *	<li>token: +</li>
	 *	<li> precedence: 3</li>
	 *	<li>numberOperands: 2</li>
	 *	<li>preceededByAnOperand: true</li>
	 * </ul>
	 */
	public AdditionOperator()
	{
		token = new String("+");
		precedence = 3;
		numberOperands = 2;
		preceededByAnOperand = true;
	}

	/**
	 * @param x[] the operands
	 * @return x[0] + x[1]
	 */
	public int operate(int [] x)
	{
		int solution;

		if(x.length == numberOperands)
			solution = x[0] + x[1];
		else
		{
			System.out.println("Error in \"" + token + "\"");
			solution = 0;
		}

		return solution;
	}
}	
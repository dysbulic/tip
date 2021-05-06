/**
 * Class representing the concept of an operator in an equation. For example, in
 * X + 3 = Y X, 3 and Y are operands representing values whereas + and = are operators
 * representing actions to be performed on the operands.
 */
public abstract class Operator
{
	/**
	 * The token representing the operation in an equation.
	 */
	public String token;

	/**
	 * The precedence of the operator. In equations different operators can have
	 * an order of operations defined; for instance in normal arithmetic operations
	 * there are the basic operators + (addition), - (subtraction), * (multiplication),
	 * and / (division). There is an order of operations which states that all
	 * multiplication and division is performed before any addition or subtraction.
	 * <i>This is ignoring the concept of grouping symbols such as parenthesis.</i>
	 * All multipliaction and division is performed left to right and then all addition
	 * and subtraction is performed left to right. This could be expanded to a more
	 * operators; each time the highest order of precedence is performed left to
	 * right until none of that operator remain, then the same is repeated for the next
	 * lower precedence until eventually there is but a single value left.
	 */
	public int precedence;

	/**
	 * The number of operands that the operator acts on.
	 */
	public int numberOperands;

	/**
	 * Flag allowing for the contextualization of operators. It is the case sometimes
	 * that in an equation the same symbol may represent more than one operator.
	 * For instance in arithmetic the - represents both subtration 3 - 5 and negation
	 * -2. Which action is intended by the writer is known by the reader of the equation
	 * based upon the context where it appears. In 3 - 5 = -2 it is known that the first
	 * - is a minus because it is preceeded by 3 and the second is negation becasue it
	 * is preceeded by a =.
	 */
	public boolean preceededByAnOperand;

	/**
	 * Abstact method that all operators must implement. This represents the operator
	 * acting upon a series of operands.
	 *
	 * @return the new operand produced by the operation
	 */
	public abstract int operate(int [] x);

	/**
	 * @param o an operator to be compared to this one
	 *
	 * @return <code>true</code> if this operator is of higher precedence
	 */
	public boolean isHigherPrecedence(Operator o)
	{
		return precedence < o.precedence;
	}

	/**
	 * @param o an operator to be compared to this one
	 *
	 * @return <code>true</code> if this operator is of lower precedence
	 */
	public boolean isLowerPrecedence(Operator o)
	{
		return precedence > o.precedence;
	}

	/**
	 * @return the token for the current operator
	 */
	public String toString()
	{
		return token;
	}
}	